<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Enums\Activity;
use App\Enums\InputType;
use App\Models\SmsGateway;
use App\Models\GatewayOption;
use Illuminate\Database\Seeder;

class SmsGatewayTableSeederVersionTwo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public array $gateways = [
        [
            "name"    => "Clickatell",
            "slug"    => "clickatell",
            "misc"    => null,
            "status"  => Activity::DISABLE,
            "options" => [
                [
                    "option"     => 'clickatell_apikey',
                    "type"       => InputType::TEXT,
                    "activities" => '',
                ],
                [
                    "option"     => 'clickatell_status',
                    "value"      => Activity::DISABLE,
                    "type"       => InputType::SELECT,
                    "activities" => [
                        Activity::ENABLE  => "enable",
                        Activity::DISABLE => "disable",
                    ]
                ]
            ]
        ],
        [
            "name"    => "Nexmo",
            "slug"    => "nexmo",
            "misc"    => null,
            "status"  => Activity::DISABLE,
            "options" => [
                [
                    "option"     => 'nexmo_key',
                    "type"       => InputType::TEXT,
                    "activities" => '',
                ],
                [
                    "option"     => 'nexmo_secret',
                    "type"       => InputType::TEXT,
                    "activities" => '',
                ],
                [
                    "option"     => 'nexmo_status',
                    "value"      => Activity::DISABLE,
                    "type"       => InputType::SELECT,
                    "activities" => [
                        Activity::ENABLE  => "enable",
                        Activity::DISABLE => "disable",
                    ],

                ],
            ]
        ],
        [
            "name"    => "Msg91",
            "slug"    => "msg91",
            "misc"    => null,
            "status"  => Activity::DISABLE,
            "options" => [
                [
                    "option"     => 'msg91_key',
                    "type"       => InputType::TEXT,
                    "activities" => '',
                ],
                [
                    "option"     => 'msg91_sender_id',
                    "type"       => InputType::TEXT,
                    "activities" => '',
                ],
                [
                    "option"     => 'msg91_status',
                    "value"      => Activity::DISABLE,
                    "type"       => InputType::SELECT,
                    "activities" => [
                        Activity::ENABLE  => "enable",
                        Activity::DISABLE => "disable",
                    ],

                ],
            ]
        ],
    ];

    public function run(): void
    {
        foreach ($this->gateways as $gateway) {
            $sms = SmsGateway::create([
                'name'   => $gateway['name'],
                'slug'   => $gateway['slug'],
                'misc'   => json_encode($gateway['misc']),
                'status' => Status::ACTIVE,
            ]);
            $this->gatewayOption($sms->id, $gateway['options']);
        }
    }

    public function gatewayOption($id, $options): void
    {
        foreach ($options as $option) {
            GatewayOption::create([
                'model_id'   => $id,
                'model_type' => 'App\Models\SmsGateway',
                'option'     => $option['option'],
                'value'      => $option['value'] ?? "",
                'type'       => $option['type'],
                'activities' => json_encode($option['activities'])
            ]);
        }
    }
}
