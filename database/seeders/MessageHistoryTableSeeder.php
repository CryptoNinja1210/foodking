<?php

namespace Database\Seeders;

use App\Enums\Ask;
use App\Models\MessageHistory;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;

class MessageHistoryTableSeeder extends Seeder
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
            MessageHistory::insert([
                [
                    'message_id' => 1,
                    'user_id'    => 3,
                    'text'       => "How long will the product take to arrive?",
                    'is_read'    => Ask::YES,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'message_id' => 2,
                    'user_id'    => 3,
                    'text'       => "Can you give me your branch manager's number?",
                    'is_read'    => Ask::YES,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'message_id' => 1,
                    'user_id'    => 1,
                    'text'       => "Around 30 to 40 minutes",
                    'is_read'    => Ask::NO,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'message_id' => 2,
                    'user_id'    => 1,
                    'text'       => "Yes sure (+8801257654433)",
                    'is_read'    => Ask::NO,
                    'created_at' => now(),
                    'updated_at' => now()
                ]

            ]);
        }
    }
}
