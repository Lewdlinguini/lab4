<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('user_id'); // Foreign key to users table
            $table->decimal('total_amount', 8, 2); // Total order amount
            $table->string('payment_status')->default('Pending'); // Payment status (e.g., Pending, Completed)
            $table->string('shipping_status')->default('Pending'); // Shipping status (e.g., Pending, Shipped, Delivered)
            $table->timestamps(); // created_at and updated_at timestamps

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

