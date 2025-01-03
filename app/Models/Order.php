<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Define the fields that are mass assignable
    protected $fillable = [
        'user_id',
        'total_amount',
        'payment_status',
        'shipping_status',
    ];

    // Define the relationship with the User model (an order belongs to a user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
