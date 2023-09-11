<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $table = 'order_product_detail';
    protected $primaryKey = 'id ';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'product_desc',
        'price',
        'unit',
        'tags',
        'status',
    ];
}
