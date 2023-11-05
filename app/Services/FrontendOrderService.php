<?php

namespace App\Services;


use Exception;
use App\Models\Tax;
use App\Models\Item;
use App\Enums\TaxType;
use App\Models\Address;
use App\Enums\OrderType;
use App\Models\Currency;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use App\Models\OrderCoupon;
use App\Events\SendOrderSms;
use App\Models\OrderAddress;
use App\Events\SendOrderMail;
use App\Events\SendOrderPush;
use App\Libraries\AppLibrary;
use App\Models\FrontendOrder;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PaginateRequest;
use Smartisan\Settings\Facades\Settings;
use App\Http\Requests\OrderStatusRequest;
use App\Events\SendOrderGotPush;

class FrontendOrderService
{

    public object $frontendOrder;
    protected array $frontendOrderFilter = [
        'order_serial_no',
        'user_id',
        'branch_id',
        'total',
        'order_type',
        'order_datetime',
        'payment_method',
        'payment_status',
        'status',
        'delivery_boy_id'
    ];

    protected array $exceptFilter = [
        'excepts'
    ];

    /**
     * @throws Exception
     */
    public function myOrder(PaginateRequest $request)
    {
        try {
            $requests            = $request->all();
            $method              = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue         = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $frontendOrderColumn = $request->get('order_column') ?? 'id';
            $frontendOrderType   = $request->get('order_by') ?? 'desc';

            return FrontendOrder::where('order_type', "!=", OrderType::POS)->where(function ($query) use ($requests) {
                $query->where('user_id', auth()->user()->id);
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->frontendOrderFilter)) {
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
            })->orderBy($frontendOrderColumn, $frontendOrderType)->$method(
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
                $this->frontendOrder = FrontendOrder::create(
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
                        $taxId     = isset($items[$item->item_id]) ? $items[$item->item_id] : 0;
                        $taxName   = isset($taxes[$taxId]) ? $taxes[$taxId]->name : null;
                        $taxRate   = isset($taxes[$taxId]) ? $taxes[$taxId]->tax_rate : 0;
                        $taxType   = isset($taxes[$taxId]) ? $taxes[$taxId]->type : TaxType::FIXED;
                        $taxPrice  = $taxType === TaxType::FIXED ? $taxRate : ($item->total_price * $taxRate) / 100;
                        $itemsArray[$i] = [
                            'order_id'             => $this->frontendOrder->id,
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
                        $totalTax = $totalTax + ($taxPrice * $item->quantity);
                        $i++;
                    }
                }

                if (!blank($itemsArray)) {
                    OrderItem::insert($itemsArray);
                }

                $this->frontendOrder->order_serial_no = date('dmy') . $this->frontendOrder->id;
                $this->frontendOrder->total_tax = $totalTax;
                $this->frontendOrder->save();

                if ($request->address_id) {
                    $address = Address::find($request->address_id);
                    if ($address) {
                        OrderAddress::create([
                            'order_id'  => $this->frontendOrder->id,
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
                        'order_id'  => $this->frontendOrder->id,
                        'coupon_id' => $request->coupon_id,
                        'user_id'   => Auth::user()->id,
                        'discount'  => $request->discount
                    ]);
                }
                SendOrderMail::dispatch(['order_id' => $this->frontendOrder->id, 'status' => OrderStatus::PENDING]);
                SendOrderSms::dispatch(['order_id' => $this->frontendOrder->id, 'status' => OrderStatus::PENDING]);
                SendOrderPush::dispatch(['order_id' => $this->frontendOrder->id, 'status' => OrderStatus::PENDING]);
                SendOrderGotPush::dispatch(['order_id' => $this->frontendOrder->id]);
            });
            return $this->frontendOrder;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(FrontendOrder $frontendOrder): FrontendOrder | array
    {
        try {
            if ($frontendOrder->user_id == Auth::user()->id) {
                return $frontendOrder;
            }
            return [];
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function changeStatus(FrontendOrder $frontendOrder, OrderStatusRequest $request): FrontendOrder
    {
        try {
            if ($frontendOrder->user_id == Auth::user()->id) {
                if ($request->status == OrderStatus::CANCELED) {
                    if ($frontendOrder->status >= OrderStatus::ACCEPT) {
                        throw new Exception(trans('all.message.order_accept'), 422);
                    } else {
                        if ($frontendOrder->transaction) {
                            app(PaymentService::class)->cashBack(
                                $frontendOrder,
                                'credit',
                                rand(111111111111111, 99999999999999)
                            );
                        }
                        SendOrderMail::dispatch(['order_id' => $frontendOrder->id, 'status' => $request->status]);
                        SendOrderSms::dispatch(['order_id' => $frontendOrder->id, 'status' => $request->status]);
                        SendOrderPush::dispatch(['order_id' => $frontendOrder->id, 'status' => $request->status]);
                        $frontendOrder->status = $request->status;
                        $frontendOrder->save();
                    }
                }
            }
            return $frontendOrder;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
