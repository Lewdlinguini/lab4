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
        $table->foreignId('user_id')->constrained('users');
        $table->decimal('total_amount', 8, 2);
        $table->string('payment_status')->default('Pending');
        $table->string('shipping_status')->default('Pending');
        $table->timestamps();
    });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
