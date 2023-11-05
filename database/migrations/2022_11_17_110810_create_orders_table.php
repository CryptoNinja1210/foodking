<?php

use App\Enums\Ask;
use App\Enums\OrderType;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_serial_no')->nullable();
            $table->string('token')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('branch_id')->constrained('branches');
            $table->decimal('subtotal', 13, 6);
            $table->decimal('discount', 13, 6)->nullable()->default(0);
            $table->decimal('delivery_charge', 13, 6)->nullable()->default(0);
            $table->decimal('total', 13, 6);
            $table->tinyInteger('order_type')->default(OrderType::DELIVERY);
            $table->timestamp('order_datetime')->default(date('y-m-d h:m:s'));
            $table->string('delivery_time')->nullable();
            $table->integer('preparation_time')->default(0);
            $table->tinyInteger('is_advance_order')->default(Ask::YES);
            $table->bigInteger('payment_method')->default(PaymentGateway::CASH_ON_DELIVERY);
            $table->tinyInteger('payment_status')->default(PaymentStatus::UNPAID);
            $table->tinyInteger('status');
            $table->bigInteger('delivery_boy_id')->nullable();
            $table->text('reason')->nullable();
            $table->string('source')->nullable();
            $table->string('creator_type')->nullable();
            $table->bigInteger('creator_id')->nullable();
            $table->string('editor_type')->nullable();
            $table->bigInteger('editor_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
