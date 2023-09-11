<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $primaryKey = 'id ';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'product_desc',
        'price',
        'unit',
        'tags',
        'status',
    ];
}
