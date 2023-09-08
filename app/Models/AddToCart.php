<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddToCart extends Model
{
    use HasFactory;
    protected $table = 'shopping_cart';
    protected $primaryKey = 'id ';
    public $timestamps = false;

    protected $fillable = [
        'userid ',
        'object_id	',
        'object_type',
        'cart_value',
        'created_date'
    ];
}
