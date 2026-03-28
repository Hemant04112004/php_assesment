<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'quantity_in_stock',
        'price_per_item',
        'submitted_at',
        'total_value_number',
    ];

    protected $casts = [
        'quantity_in_stock' => 'integer',
        'price_per_item' => 'decimal:2',
        'submitted_at' => 'datetime',
        'total_value_number' => 'decimal:2',
    ];
}
