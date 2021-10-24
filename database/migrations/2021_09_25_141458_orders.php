<?php declare(strict_types=1);

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
            $table->unsignedBigInteger('product')->nullable();
            $table->unsignedBigInteger('dispatcher')->nullable();
            $table->unsignedBigInteger('delivery_driver')->nullable();
            $table->json('customer_details');
            $table->set('status', ['pending', 'rejected', 'dispatched', 'shipped', 'in_dlivery', 'delivered'])->default('pending');
            $table->text('token');
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
        Schema::dropIsExists('orders');
    }
}
