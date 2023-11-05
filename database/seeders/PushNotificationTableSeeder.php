<?php

namespace Database\Seeders;

use App\Models\PushNotification;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;

class PushNotificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            PushNotification::insert([
                [
                    'title'       => 'New beef offer',
                    'description' => 'Uplifting anytime offer. 7% off on any beef item.',
                    'role_id'     => 2,
                    'user_id'     => 0,
                    'branch_id'   => 1,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'title'       => 'Any non-veg offer',
                    'description' => 'Savory and satisfying offer. 5% off on any non-veg item.',
                    'role_id'     => 2,
                    'user_id'     => 0,
                    'branch_id'   => 1,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],

            ]);
        }
    }
}
