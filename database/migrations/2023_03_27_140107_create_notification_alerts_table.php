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
        Schema::create('notification_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('language');
            $table->string('mail_message')->nullable();
            $table->string('sms_message')->nullable();
            $table->string('push_notification_message')->nullable();
            $table->tinyInteger('mail')->nullable();
            $table->tinyInteger('sms')->nullable();
            $table->tinyInteger('push_notification')->nullable();
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
        Schema::dropIfExists('notification_alerts');
    }
};