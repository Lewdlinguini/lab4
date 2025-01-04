<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductIdToOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Add the product_id column
            $table->unsignedBigInteger('product_id');

            // Add foreign key constraint
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['product_id']);
            
            // Drop the product_id column
            $table->dropColumn('product_id');
        });
    }
}
