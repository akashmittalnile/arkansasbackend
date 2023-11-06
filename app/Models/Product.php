<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'product';
    protected $primaryKey = 'id ';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'product_desc',
        'price',
        'sale_price',
        'unit',
        'tags',
        'status',
        'added_by',
        'full_description',
        'refund_policy',
        'product_weight',
        'product_length',
        'product_width',
        'product_height',
        'product_weight_unit',
        'product_length_unit',
        'product_width_unit',
        'product_height_unit',
        'package_weight',
        'package_length',
        'package_width',
        'package_height',
        'package_weight_unit',
        'package_length_unit',
        'package_width_unit',
        'package_height_unit',
        'sku_code',
        'stock_available'
    ];
}
