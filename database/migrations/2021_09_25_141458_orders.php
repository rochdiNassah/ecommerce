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
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('dispatcher_id')->nullable();
            $table->unsignedBigInteger('delivery_driver_id')->nullable();
            $table->json('customer');
            $table->set('status', ['pending', 'rejected', 'canceled', 'dispatched', 'shipped', 'delivered'])->default('pending');
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
