<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['product', 'customer_details', 'token'];

    protected $guarded = [
        'status',
        'dispatcher',
        'delivery_driver',
        'token',
        'customer_details'
    ];
}
