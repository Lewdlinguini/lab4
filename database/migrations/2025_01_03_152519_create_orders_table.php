<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up()
    {
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->decimal('total_amount', 8, 2);
        $table->enum('payment_status', ['pending', 'paid', 'failed']);
        $table->enum('shipping_status', ['pending', 'shipped', 'delivered'])->default('pending');
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
