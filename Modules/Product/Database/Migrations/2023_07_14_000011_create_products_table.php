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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('sku')->nullable();
            $table->text('description')->nullable();

            $table->float('price')->nullable();
            $table->tinyInteger('position')->nullable();
            $table->tinyInteger('status')->default(1);

            $table->unsignedInteger('category_id')->nullable();
            $table->foreign('category_id', 'category_fk_623443')->references('id')->on('categories')->onDelete('RESTRICT');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
