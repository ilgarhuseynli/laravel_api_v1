<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->float('price');
            $table->float('total');
            $table->float('total_discount');
            $table->float('quantity');
            $table->float('discount_value');
            $table->tinyInteger('discount_type');

            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id', 'order_fk_94143')->references('id')->on('orders')->onDelete('cascade');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id', 'product_fk_212843')->references('id')->on('products')->onDelete('RESTRICT');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
