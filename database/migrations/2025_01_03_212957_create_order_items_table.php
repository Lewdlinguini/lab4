<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_order_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('order_id'); // Foreign key to orders table
            $table->unsignedBigInteger('product_id'); // Foreign key to products table
            $table->string('product_name');
            $table->integer('quantity');
            $table->decimal('price', 8, 2); // Product price at time of order
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
