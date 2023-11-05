<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Enums\Activity;
use App\Enums\InputType;
use App\Models\SmsGateway;
use App\Models\GatewayOption;
use Illuminate\Database\Seeder;

class SmsGatewayTableSeederVersionOne extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public array $gateways = [
        [
            "name"    => "Twilio",
            "slug"    => "twilio",
            "misc"    => null,
            "status"  => Activity::DISABLE,
            "options" => [
                [
                    "option"     => 'twilio_account_sid',
                    "type"       => InputType::TEXT,
                    "activities" => '',
                ],
                [
                    "option"     => 'twilio_auth_token',
                    "type"       => InputType::TEXT,
                    "activities" => '',
                ],
                [
                    "option"     => 'twilio_from',
                    "type"       => InputType::TEXT,
                    "activities" => '',
                ],
                [
                    "option"     => 'twilio_status',
                    "value"      => Activity::DISABLE,
                    "type"       => InputType::SELECT,
                    "activities" => [
                        Activity::ENABLE  => "enable",
                        Activity::DISABLE => "disable",
                    ],

                ],
            ]
        ]
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
