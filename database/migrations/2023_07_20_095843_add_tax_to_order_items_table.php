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
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('tax_name')->after('discount')->nullable();
            $table->decimal('tax_rate', 13, 6)->after('tax_name')->nullable();
            $table->tinyInteger('tax_type')->after('tax_rate')->nullable();
            $table->decimal('tax_amount', 13, 6)->after('tax_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('tax_name');
            $table->dropColumn('tax_rate');
            $table->dropColumn('tax_type');
            $table->dropColumn('tax_amount');
        });
    }
};
