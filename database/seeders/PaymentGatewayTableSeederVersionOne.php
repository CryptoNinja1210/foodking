<?php

namespace Database\Seeders;

use App\Enums\GatewayMode;
use App\Enums\Activity;
use App\Enums\InputType;
use App\Models\GatewayOption;
use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewayTableSeederVersionOne extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public array $gateways = [
        [
            "name"    => "Cash On Delivery",
            "slug"    => "cash-on-delivery",
            "misc"    => null,
            "status"  => Activity::ENABLE,
            "options" => [],
        ],
        [
            "name"    => "Credit",
            "slug"    => "credit",
            "misc"    => null,
            "status"  => Activity::ENABLE,
            "options" => [],
        ],
        [
            "name"    => "Paypal",
            "slug"    => "paypal",
            "misc"    => null,
            "status"  => Activity::DISABLE,
            "options" => [
                [
                    "option"     => 'paypal_app_id',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'paypal_client_id',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'paypal_client_secret',
                    "type"       => InputType::TEXT,
                    "activities" => ''
                ],
                [
                    "option"     => 'paypal_mode',
                    "type"       => InputType::SELECT,
                    "activities" => [
                        GatewayMode::SANDBOX => 'sandbox',
                        GatewayMode::LIVE    => 'live'
                    ]
                ],
                [
                    "option"     => 'paypal_status',
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
            "name"    => "Stripe",
            "slug"    => "stripe",
            "misc"    => [
                'input'  => ['stripe.stripeInput.blade.php'],
                'js'     => ['stripe.stripeJs.blade.php'],
                'submit' => true
            ],
            "status"  => Activity::DISABLE,
            "options" => [
                [
                    "option"     => 'stripe_key',
                    "type"       => InputType::TEXT,
                    "activities" => ''

                ],
                [
                    "option"     => 'stripe_secret',
                    "type"       => InputType::TEXT,
                    "activities" => ''

                ],
                [
                    "option"     => 'stripe_mode',
                    "type"       => InputType::SELECT,
                    "activities" => [
                        GatewayMode::SANDBOX => 'sandbox',
                        GatewayMode::LIVE    => 'live'
                    ]
                ],
                [
                    "option"     => 'stripe_status',
                    "value"      => Activity::DISABLE,
                    "type"       => InputType::SELECT,
                    "activities" => [
                        Activity::ENABLE  => "enable",
                        Activity::DISABLE => "disable",
                    ]
                ],
            ]
        ]
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
