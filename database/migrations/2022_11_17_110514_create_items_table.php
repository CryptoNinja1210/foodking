<?php

use App\Enums\Ask;
use App\Enums\Featured;
use App\Enums\ItemType;
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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_category_id')->constrained('item_categories');
            $table->foreignId('tax_id')->nullable()->constrained('taxes');
            $table->string('name');
            $table->string('slug');
            $table->longText('caution')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 13, 6)->default(0);
            $table->tinyInteger('status')->default(\App\Enums\Status::ACTIVE)->comment(\App\Enums\Status::ACTIVE . '=' . trans('statuse.' . \App\Enums\Status::ACTIVE) . ', ' . \App\Enums\Status::INACTIVE . '=' . trans('statuse.' . \App\Enums\Status::INACTIVE));
            $table->tinyInteger('item_type')->default(ItemType::VEG);
            $table->bigInteger('order')->default(1);
            $table->tinyInteger('is_featured')->default(Ask::YES);
            $table->string('creator_type',)->nullable();
            $table->bigInteger('creator_id',)->nullable();
            $table->string('editor_type',)->nullable();
            $table->bigInteger('editor_id',)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('items');
    }
};
