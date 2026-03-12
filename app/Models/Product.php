<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
            'name',
            'category',
            'description',
            'price',
            'rating',        // Added for buyer feedback
            'review_count',  // Added for buyer feedback
            'stock',
            'image',
    ];
}