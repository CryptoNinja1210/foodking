<?php

namespace Database\Seeders;

use App\Enums\GatewayMode;
use App\Enums\Activity;
use App\Enums\InputType;
use App\Models\GatewayOption;
use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewayTableSeederVersionTwo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public array $gateways = [
        [
            "name"    => "Flutterwave",
            "slug"    => "flutterwave",
            "misc"    => null,
            "status"  => Activity::DISABLE,
            "options" => [
                [
                    "option"     => 'flutterwave_public_key',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'flutterwave_secret_key',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'flutterwave_mode',
                    "type"       => InputType::SELECT,
                    "activities" => [
                        GatewayMode::SANDBOX => 'sandbox',
                        GatewayMode::LIVE    => 'live'
                    ]
                ],
                [
                    "option"     => 'flutterwave_status',
                    "value"      => Activity::DISABLE,
                    "type"       => InputType::SELECT,
                    "activities" => [
                        Activity::ENABLE  => "enable",
                        Activity::DISABLE => "disable",
                    ]
                ],
            ]
        ],
        [
            "name"    => "Paystack",
            "slug"    => "paystack",
            "misc"    => null,
            "status"  => Activity::DISABLE,
            "options" => [
                [
                    "option"     => 'paystack_public_key',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'paystack_secret_key',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'paystack_payment_url',
                    "type"       => InputType::TEXT,
                    "value"      => 'https://api.paystack.co',
                    "activities" => ''
                ],
                [
                    "option"     => 'paystack_mode',
                    "type"       => InputType::SELECT,
                    "activities" => [
                        GatewayMode::SANDBOX => 'sandbox',
                        GatewayMode::LIVE    => 'live'
                    ]
                ],
                [
                    "option"     => 'paystack_status',
                    "value"      => Activity::DISABLE,
                    "type"       => InputType::SELECT,
                    "activities" => [
                        Activity::ENABLE  => "enable",
                        Activity::DISABLE => "disable",
                    ]
                ],
            ]
        ],
        [
            "name"    => "SslCommerz",
            "slug"    => "sslcommerz",
            "misc"    => null,
            "status"  => Activity::DISABLE,
            "options" => [
                [
                    "option"     => 'sslcommerz_store_name',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'sslcommerz_store_id',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'sslcommerz_store_password',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'sslcommerz_mode',
                    "type"       => InputType::SELECT,
                    "activities" => [
                        GatewayMode::SANDBOX => 'sandbox',
                        GatewayMode::LIVE    => 'live'
                    ]
                ],
                [
                    "option"     => 'sslcommerz_status',
                    "value"      => Activity::DISABLE,
                    "type"       => InputType::SELECT,
                    "activities" => [
                        Activity::ENABLE  => "enable",
                        Activity::DISABLE => "disable",
                    ]
                ],
            ]
        ],
        [
            "name"    => "Mollie",
            "slug"    => "mollie",
            "misc"    => null,
            "status"  => Activity::DISABLE,
            "options" => [
                [
                    "option"     => 'mollie_api_key',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'mollie_mode',
                    "type"       => InputType::SELECT,
                    "activities" => [
                        GatewayMode::SANDBOX => 'sandbox',
                        GatewayMode::LIVE    => 'live'
                    ]
                ],
                [
                    "option"     => 'mollie_status',
                    "value"      => Activity::DISABLE,
                    "type"       => InputType::SELECT,
                    "activities" => [
                        Activity::ENABLE  => "enable",
                        Activity::DISABLE => "disable",
                    ]
                ],
            ]
        ],
        [
            "name"    => "Senangpay",
            "slug"    => "senangpay",
            "misc"    => null,
            "status"  => Activity::DISABLE,
            "options" => [
                [
                    "option"     => 'senangpay_merchant_id',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'senangpay_secret_key',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'senangpay_mode',
                    "type"       => InputType::SELECT,
                    "activities" => [
                        GatewayMode::SANDBOX => 'sandbox',
                        GatewayMode::LIVE    => 'live'
                    ]
                ],
                [
                    "option"     => 'senangpay_status',
                    "value"      => Activity::DISABLE,
                    "type"       => InputType::SELECT,
                    "activities" => [
                        Activity::ENABLE  => "enable",
                        Activity::DISABLE => "disable",
                    ]
                ],
            ]
        ],
        [
            "name"    => "Bkash",
            "slug"    => "bkash",
            "misc"    => null,
            "status"  => Activity::DISABLE,
            "options" => [
                [
                    "option"     => 'bkash_app_key',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'bkash_app_secret',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'bkash_username',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'bkash_password',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'bkash_mode',
                    "type"       => InputType::SELECT,
                    "activities" => [
                        GatewayMode::SANDBOX => 'sandbox',
                        GatewayMode::LIVE    => 'live'
                    ]
                ],
                [
                    "option"     => 'bkash_status',
                    "type"       => InputType::SELECT,
                    "activities" => [
                        Activity::ENABLE  => "enable",
                        Activity::DISABLE => "disable",
                    ]
                ]
            ]
        ],
        [
            "name"    => "Paytm",
            "slug"    => "paytm",
            "misc"    => null,
            "status"  => Activity::DISABLE,
            "options" => [
                [
                    "option"     => 'paytm_merchant_id',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'paytm_merchant_key',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'paytm_merchant_website',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'paytm_channel',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'paytm_industry_type',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'paytm_mode',
                    "type"       => InputType::SELECT,
                    "activities" => [
                        GatewayMode::SANDBOX => 'sandbox',
                        GatewayMode::LIVE    => 'live'
                    ]
                ],
                [
                    "option"     => 'paytm_status',
                    "type"       => InputType::SELECT,
                    "activities" => [
                        Activity::ENABLE  => "enable",
                        Activity::DISABLE => "disable",
                    ]
                ]
            ]
        ],
        [
            "name"    => "Razorpay",
            "slug"    => "razorpay",
            "misc"    => [
                'input'  => [],
                'js'     => ['razorpay.razorpayJs.blade.php'],
                'submit' => false
            ],
            "status"  => Activity::DISABLE,
            "options" => [
                [
                    "option"     => 'razorpay_key',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'razorpay_secret',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'razorpay_mode',
                    "type"       => InputType::SELECT,
                    "activities" => [
                        GatewayMode::SANDBOX => 'sandbox',
                        GatewayMode::LIVE    => 'live'
                    ]
                ],
                [
                    "option"     => 'razorpay_status',
                    "type"       => InputType::SELECT,
                    "activities" => [
                        Activity::ENABLE  => "enable",
                        Activity::DISABLE => "disable",
                    ]
                ]
            ]
        ],
        [
            "name"    => "Mercadopago",
            "slug"    => "mercadopago",
            "misc"    => null,
            "status"  => Activity::DISABLE,
            "options" => [
                [
                    "option"     => 'mercadopago_client_id',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'mercadopago_client_secret',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'mercadopago_mode',
                    "type"       => InputType::SELECT,
                    "activities" => [
                        GatewayMode::SANDBOX => 'sandbox',
                        GatewayMode::LIVE    => 'live'
                    ]
                ],
                [
                    "option"     => 'mercadopago_status',
                    "value"      => Activity::DISABLE,
                    "type"       => InputType::SELECT,
                    "activities" => [
                        Activity::ENABLE  => "enable",
                        Activity::DISABLE => "disable",
                    ]
                ],
            ]
        ],
        [
            "name"    => "Cashfree",
            "slug"    => "cashfree",
            "misc"    => null,
            "status"  => Activity::DISABLE,
            "options" => [
                [
                    "option"     => 'cashfree_app_id',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'cashfree_secret_key',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'cashfree_mode',
                    "type"       => InputType::SELECT,
                    "activities" => [
                        GatewayMode::SANDBOX => 'sandbox',
                        GatewayMode::LIVE    => 'live'
                    ]
                ],
                [
                    "option"     => 'cashfree_status',
                    "type"       => InputType::SELECT,
                    "activities" => [
                        Activity::ENABLE  => "enable",
                        Activity::DISABLE => "disable",
                    ]
                ]
            ]
        ],
    ];

    public function run(): void
    {
        foreach ($this->gateways as $gateway) {
            $payment = PaymentGateway::create([
                'name'   => $gateway['name'],
                'slug'   => $gateway['slug'],
                'misc'   => json_encode($gateway['misc']),
                'status' => $gateway['status']
            ]);

            $payment->addMedia(public_path('/images/payment-gateway/' . $gateway['slug'] . '.png'))->preservingOriginal()->toMediaCollection('payment-gateway');
            $this->gatewayOption($payment->id, $gateway['options']);
        }
    }

    public function gatewayOption($id, $options): void
    {
        if (!blank($options)) {
            foreach ($options as $option) {
                GatewayOption::create([
                    'model_id'   => $id,
                    'model_type' => 'App\Models\PaymentGateway',
                    'option'     => $option['option'],
                    'value'      => $option['value'] ?? "",
                    'type'       => $option['type'],
                    'activities' => json_encode($option['activities']),
                ]);
            }
        }
    }
}
