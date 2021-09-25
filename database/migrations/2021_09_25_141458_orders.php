<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Orders extends Migration
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
            $table->unsignedBigInteger('product');
            $table->unsignedBigInteger('dispatcher');
            $table->unsignedBigInteger('dispatched_to')->nullable();

            $table->json('customer_details');
            $table->set('status', ['pending', 'confirmed', 'in_delivery', 'rejected', 'delivered']);
            $table->set('delivery_status', ['in_delivery', 'delivered'])->nullable();
            $table->text('token');

            $table->foreign('product')->references('id')->on('products');
            $table->foreign('dispatcher')->references('id')->on('users');
            $table->foreign('dispatched_to')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIsExists('orders');
    }
}
