<?php

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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders');
            $table->foreignId('branch_id')->constrained('branches');
            $table->foreignId('item_id')->constrained('items');
            $table->integer('quantity')->default(1);
            $table->decimal('discount', 13, 6);
            $table->decimal('price', 13, 6);
            $table->longText('item_variations')->nullable();
            $table->longText('item_extras')->nullable();
            $table->decimal('item_variation_total', 13, 6)->nullable()->default(0);
            $table->decimal('item_extra_total', 13, 6)->nullable()->default(0);
            $table->decimal('total_price', 13, 6)->nullable()->default(0);
            $table->text('instruction')->nullable();
            $table->string('creator_type',)->nullable();
            $table->bigInteger('creator_id',)->nullable();
            $table->string('editor_type',)->nullable();
            $table->bigInteger('editor_id',)->nullable();
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
        Schema::dropIfExists('order_items');
    }
};