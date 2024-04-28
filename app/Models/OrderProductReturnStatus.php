<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProductReturnStatus extends Model
{
    use HasFactory;
    protected $table = 'order_product_return_status';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
