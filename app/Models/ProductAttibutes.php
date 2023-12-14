<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttibutes extends Model
{
    use HasFactory;
    protected $table = 'product_details';
    protected $primaryKey = 'id ';
    public $timestamps = false;
    protected $fillable = [
        'product_id',
        'attribute_type',
        'attribute_code',
        'attribute_value',
        'created_date',
    ];
}
