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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('ticket_number');
            $table->string('address');
            $table->string('phone');
            $table->string('name');
            $table->string('note')->nullable();
            $table->string('manager_note')->nullable();
            $table->float('total_amount');
            $table->float('total_discount');
            $table->float('total_to_pay');
            $table->tinyInteger('payment_type');
            $table->tinyInteger('status');
            $table->dateTimeTz('order_date');
            $table->dateTimeTz('completed_at')->nullable();

            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id', 'customer_fk_522743')->references('id')->on('users')->onDelete('RESTRICT');

            $table->unsignedBigInteger('creator_id')->nullable();
            $table->foreign('creator_id', 'creator_fk_224143')->references('id')->on('users')->onDelete('RESTRICT');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
