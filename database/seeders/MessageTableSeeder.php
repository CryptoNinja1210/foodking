<?php

namespace Database\Seeders;

use App\Models\Message;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;

class MessageTableSeeder extends Seeder
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
            Message::insert([
                [
                    'branch_id'  => 1,
                    'user_id'    => 3,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'branch_id'  => 2,
                    'user_id'    => 3,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }
    }
}
