<?php

namespace App\Services;

use Exception;
use App\Models\Tax;
use App\Models\Item;
use App\Models\User;
use App\Models\Order;
use App\Enums\TaxType;
use App\Models\Address;
use App\Enums\OrderType;
use App\Models\Currency;
use App\Models\OrderItem;
use Stripe\PaymentMethod;
use App\Enums\OrderStatus;
use App\Models\OrderCoupon;
use App\Models\Transaction;
use App\Enums\PaymentStatus;
use App\Events\SendOrderSms;
use App\Models\OrderAddress;
use Illuminate\Http\Request;
use App\Events\SendOrderMail;
use App\Events\SendOrderPush;
use App\Libraries\AppLibrary;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\PosOrderRequest;
use App\Events\SendOrderDeliveryBoySms;
use App\Events\SendOrderDeliveryBoyMail;
use App\Events\SendOrderDeliveryBoyPush;
use Smartisan\Settings\Facades\Settings;
use App\Http\Requests\OrderStatusRequest;
use App\Http\Requests\PaymentStatusRequest;

class OrderService
{
    public object $order;
    protected array $orderFilter = [
        'order_serial_no',
        'user_id',
        'branch_id',
        'total',
        'order_type',
        'order_datetime',
        'payment_method',
        'payment_status',
        'status',
        'delivery_boy_id',
        'source'
    ];

    protected array $exceptFilter = [
        'excepts'
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_by') ?? 'desc';

            return Order::with('transaction', 'orderItems')->where(function ($query) use ($requests) {
                if (isset($requests['from_date']) && isset($requests['to_date'])) {
                    $first_date = Date('Y-m-d', strtotime($requests['from_date']));
                    $last_date  = Date('Y-m-d', strtotime($requests['to_date']));
                    $query->whereDate('order_datetime', '>=', $first_date)->whereDate(
                        'order_datetime',
                        '<=',
                        $last_date
                    );
                }
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->orderFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }

                    if (in_array($key, $this->exceptFilter)) {
                        $explodes = explode('|', $request);
                        if (is_array($explodes)) {
                            foreach ($explodes as $explode) {
                                $query->where('order_type', '!=', $explode);
                            }
                        }
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function userOrder(PaginateRequest $request, User $user)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_by') ?? 'desc';

            return Order::where('order_type', "!=", OrderType::POS)->where(function ($query) use ($requests, $user) {
                $query->where('user_id', $user->id);
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->orderFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                    if (in_array($key, $this->exceptFilter)) {
                        $explodes = explode('|', $request);
                        if (is_array($explodes)) {
                            foreach ($explodes as $explode) {
                                $query->where('status', '!=', $explode);
                            }
                        }
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function deliveredOrder(PaginateRequest $request, User $user)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_by') ?? 'desc';

            return Order::where('delivery_boy_id', $user->id)->where('order_type', "!=", OrderType::POS)->where(
                function ($query) use ($requests) {
                    foreach ($requests as $key => $request) {
                        if (in_array($key, $this->orderFilter)) {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                        if (in_array($key, $this->exceptFilter)) {
                            $explodes = explode('|', $request);
                            if (is_array($explodes)) {
                                foreach ($explodes as $explode) {
                                    $query->where('status', '!=', $explode);
                                }
                            }
                        }
                    }
                }
            )->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function deliveryBoyOrder(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_by') ?? 'desc';

            return Order::where('order_type', "!=", OrderType::POS)->where('delivery_boy_id', Auth::user()->id)->where(
                function ($query) use ($requests) {
                    foreach ($requests as $key => $request) {
                        if (in_array($key, $this->orderFilter)) {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                        if (in_array($key, $this->exceptFilter)) {
                            $explodes = explode('|', $request);
                            if (is_array($explodes)) {
                                foreach ($explodes as $explode) {
                                    $query->where('status', '!=', $explode);
                                }
                            }
                        }
                    }
                }
            )->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function myOrderStore(OrderRequest $request): object
    {
        try {
            DB::transaction(function () use ($request) {
                $this->order = Order::create(
                    $request->validated() + [
                        'user_id'          => Auth::user()->id,
                        'status'           => OrderStatus::PENDING,
                        'order_datetime'   => date('Y-m-d H:i:s'),
                        'preparation_time' => Settings::group('order_setup')->get('order_setup_food_preparation_time')
                    ]
                );

                $i            = 0;
                $totalTax     = 0;
                $itemsArray   = [];
                $requestItems = json_decode($request->items);
                $items        = Item::get()->pluck('tax_id', 'id');
                $taxes        = AppLibrary::pluck(Tax::get(), 'obj', 'id');

                if (!blank($requestItems)) {
                    foreach ($requestItems as $item) {
                        $taxId          = isset($items[$item->item_id]) ? $items[$item->item_id] : 0;
                        $taxName        = isset($taxes[$taxId]) ? $taxes[$taxId]->name : null;
                        $taxRate        = isset($taxes[$taxId]) ? $taxes[$taxId]->tax_rate : 0;
                        $taxType        = isset($taxes[$taxId]) ? $taxes[$taxId]->type : TaxType::FIXED;
                        $taxPrice       = $taxType === TaxType::FIXED ? $taxRate : ($item->total_price * $taxRate) / 100;
                        $itemsArray[$i] = [
                            'order_id'             => $this->order->id,
                            'branch_id'            => $item->branch_id,
                            'item_id'              => $item->item_id,
                            'quantity'             => $item->quantity,
                            'discount'             => (float)$item->discount,
                            'tax_name'             => $taxName,
                            'tax_rate'             => $taxRate,
                            'tax_type'             => $taxType,
                            'tax_amount'           => $taxPrice * $item->quantity,
                            'price'                => $item->item_price,
                            'item_variations'      => json_encode($item->item_variations),
                            'item_extras'          => json_encode($item->item_extras),
                            'instruction'          => $item->instruction,
                            'item_variation_total' => $item->item_variation_total,
                            'item_extra_total'     => $item->item_extra_total,
                            'total_price'          => $item->total_price,
                        ];
                        $totalTax       = $totalTax + ($taxPrice * $item->quantity);
                        $i++;
                    }
                }

                if (!blank($itemsArray)) {
                    OrderItem::insert($itemsArray);
                }

                $this->order->order_serial_no = date('dmy') . $this->order->id;
                $this->order->total_tax       = $totalTax;
                $this->order->save();

                if ($request->address_id) {
                    $address = Address::find($request->address_id);
                    if ($address) {
                        OrderAddress::create([
                            'order_id'  => $this->order->id,
                            'user_id'   => Auth::user()->id,
                            'label'     => $address->label,
                            'address'   => $address->address,
                            'apartment' => $address->apartment,
                            'latitude'  => $address->latitude,
                            'longitude' => $address->longitude
                        ]);
                    }
                }

                if ($request->coupon_id > 0) {
                    OrderCoupon::create([
                        'order_id'  => $this->order->id,
                        'coupon_id' => $request->coupon_id,
                        'user_id'   => Auth::user()->id,
                        'discount'  => $request->discount
                    ]);
                }

                SendOrderMail::dispatch(['order_id' => $this->order->id, 'status' => $request->status]);
                SendOrderSms::dispatch(['order_id' => $this->order->id, 'status' => $request->status]);
                SendOrderPush::dispatch(['order_id' => $this->order->id, 'status' => $request->status]);
            });
            return $this->order;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function posOrderStore(PosOrderRequest $request): object
    {
        try {
            DB::transaction(function () use ($request) {
                $this->order = Order::create(
                    $request->validated() + [
                        'user_id'          => $request->customer_id,
                        'status'           => OrderStatus::ACCEPT,
                        'token'            => $request->token,
                        'payment_status'   => PaymentStatus::PAID,
                        'order_datetime'   => date('Y-m-d H:i:s'),
                        'preparation_time' => Settings::group('order_setup')->get('order_setup_food_preparation_time')
                    ]
                );

                $i            = 0;
                $totalTax     = 0;
                $itemsArray   = [];
                $requestItems = json_decode($request->items);
                $items        = Item::get()->pluck('tax_id', 'id');
                $taxes        = AppLibrary::pluck(Tax::get(), 'obj', 'id');

                if (!blank($requestItems)) {
                    foreach ($requestItems as $item) {
                        $taxId          = isset($items[$item->item_id]) ? $items[$item->item_id] : 0;
                        $taxName        = isset($taxes[$taxId]) ? $taxes[$taxId]->name : null;
                        $taxRate        = isset($taxes[$taxId]) ? $taxes[$taxId]->tax_rate : 0;
                        $taxType        = isset($taxes[$taxId]) ? $taxes[$taxId]->type : TaxType::FIXED;
                        $taxPrice       = $taxType === TaxType::FIXED ? $taxRate : ($item->total_price * $taxRate) / 100;
                        $itemsArray[$i] = [
                            'order_id'             => $this->order->id,
                            'branch_id'            => $item->branch_id,
                            'item_id'              => $item->item_id,
                            'quantity'             => $item->quantity,
                            'discount'             => (float)$item->discount,
                            'tax_name'             => $taxName,
                            'tax_rate'             => $taxRate,
                            'tax_type'             => $taxType,
                            'tax_amount'           => $taxPrice * $item->quantity,
                            'price'                => $item->item_price,
                            'item_variations'      => json_encode($item->item_variations),
                            'item_extras'          => json_encode($item->item_extras),
                            'instruction'          => $item->instruction,
                            'item_variation_total' => $item->item_variation_total,
                            'item_extra_total'     => $item->item_extra_total,
                            'total_price'          => $item->total_price,
                        ];
                        $totalTax       = $totalTax + ($taxPrice * $item->quantity);
                        $i++;
                    }
                }


                if (!blank($itemsArray)) {
                    OrderItem::insert($itemsArray);
                }

                $this->order->order_serial_no = date('dmy') . $this->order->id;
                $this->order->total_tax       = $totalTax;
                $this->order->save();
            });
            return $this->order;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(Order $order, $auth = false): Order|array
    {
        try {
            if ($auth) {
                if ($order->user_id == Auth::user()->id) {
                    return $order;
                } else {
                    return [];
                }
            } else {
                return $order;
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function orderDetails(User $user, Order $order): Order|array
    {
        try {
            if ($order->user_id == $user->id) {
                return $order;
            } else {
                return [];
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function deliveryBoyOrderDetails(Order $order): Order|array
    {
        try {
            if ($order->delivery_boy_id == Auth::user()->id) {
                return $order;
            } else {
                return [];
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function deliveryBoyDeliveredOrderDetails(User $user, Order $order): Order|array
    {
        try {
            if ($order->delivery_boy_id == $user->id) {
                return $order;
            } else {
                return [];
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function deliveryBoyOrderCount(): array
    {
        try {
            $order                              = new Order;
            $orderCountArray                    = [];
            $orderCountArray['total_delivered'] = $order->where(
                ['delivery_boy_id' => Auth::user()->id, 'status' => OrderStatus::DELIVERED]
            )->count();
            $orderCountArray['total_returned']  = $order->where(
                ['delivery_boy_id' => Auth::user()->id, 'status' => OrderStatus::RETURNED]
            )->count();

            return $orderCountArray;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function deliveryBoyOrderChangeStatus(Order $order, OrderStatusRequest $request): Order
    {
        try {
            $transaction = Transaction::where('order_id', $order->id)->first();

            if (!$transaction && $order->payment_status == PaymentStatus::UNPAID) {
                $order->payment_status = PaymentStatus::PAID;
            }
            SendOrderMail::dispatch(['order_id' => $order->id, 'status' => OrderStatus::DELIVERED]);
            SendOrderSms::dispatch(['order_id' => $order->id, 'status' => OrderStatus::DELIVERED]);
            SendOrderPush::dispatch(['order_id' => $order->id, 'status' => OrderStatus::DELIVERED]);
            $order->status = OrderStatus::DELIVERED;
            $order->save();
            return $order;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function changeStatus(Order $order, $auth = false, OrderStatusRequest $request): Order|array
    {
        try {
            if ($auth) {
                if ($order->user_id == Auth::user()->id) {
                    if ($request->reason) {
                        $order->reason = $request->reason;
                    }

                    if ($request->status == OrderStatus::REJECTED || $request->status == OrderStatus::CANCELED) {
                        if ($order->transaction) {
                            app(PaymentService::class)->cashBack(
                                $order,
                                'credit',
                                rand(111111111111111, 99999999999999)
                            );
                        }
                    }
                    SendOrderMail::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                    SendOrderSms::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                    SendOrderPush::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                    $order->status = $request->status;
                    $order->save();
                }
            } else {
                if ($request->status == OrderStatus::REJECTED || $request->status == OrderStatus::CANCELED) {
                    $request->validate([
                        'reason' => 'required|max:700',
                    ]);

                    if ($request->reason) {
                        $order->reason = $request->reason;
                    }

                    if ($order->transaction) {
                        app(PaymentService::class)->cashBack(
                            $order,
                            'credit',
                            rand(111111111111111, 99999999999999)
                        );
                    }
                }
                SendOrderMail::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                SendOrderSms::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                SendOrderPush::dispatch(['order_id' => $order->id, 'status' => $request->status]);
                $order->status = $request->status;
                $order->save();
            }
            return $order;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function changePaymentStatus(Order $order, $auth = false, PaymentStatusRequest $request): Order|array
    {
        try {
            if ($auth) {
                if ($order->user_id == Auth::user()->id) {
                    $order->payment_status = $request->payment_status;
                    $order->save();
                    return $order;
                } else {
                    return [];
                }
            } else {
                $order->payment_status = $request->payment_status;
                $order->save();
                return $order;
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function selectDeliveryBoy(Order $order, $auth = false, Request $request): Order|array
    {
        try {
            if ($auth) {
                if ($order->user_id == Auth::user()->id) {
                    $order->delivery_boy_id = $request->delivery_boy_id;
                    $order->save();
                    SendOrderDeliveryBoyMail::dispatch(['order_id' => $order->id, 'status' => 101]);
                    SendOrderDeliveryBoySms::dispatch(['order_id' => $order->id, 'status' => 101]);
                    SendOrderDeliveryBoyPush::dispatch(['order_id' => $order->id, 'status' => 101]);
                    return $order;
                } else {
                    return [];
                }
            } else {
                $order->delivery_boy_id = $request->delivery_boy_id;
                $order->save();
                SendOrderDeliveryBoyMail::dispatch(['order_id' => $order->id, 'status' => 101]);
                SendOrderDeliveryBoySms::dispatch(['order_id' => $order->id, 'status' => 101]);
                SendOrderDeliveryBoyPush::dispatch(['order_id' => $order->id, 'status' => 101]);
                return $order;
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Order $order)
    {
        try {
            DB::transaction(function () use ($order) {
                $order->address()->delete();
                $order->coupon()->delete();
                $order->orderItems()->delete();
                $order->delete();
            });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
