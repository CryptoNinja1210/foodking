<?php

namespace Database\Seeders;

use App\Models\TimeSlot;
use App\Models\Transaction;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;

class TransactionTableSeeder extends Seeder
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
            Transaction::create([
                'sign'           => '+',
                'order_id'       => 2,
                'transaction_no' => '2P498472RR750594R',
                'amount'         => 23.380000,
                'payment_method' => 'paypal',
                'type'           => 'payment',
                'created_at'     => now(),
                'updated_at'     => now()
            ]);
        }
    }
}
