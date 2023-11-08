<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

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

    public function orderCourse()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id')->join('course as c', 'c.id', '=', 'order_product_detail.product_id')->where('order_product_detail.product_type', 1)->select('c.title', 'c.id', 'c.status', 'order_product_detail.amount', 'order_product_detail.admin_amount', 'c.introduction_image');
    }

    public function orderProduct()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id')->join('product as p', 'p.id', '=', 'order_product_detail.product_id')->where('order_product_detail.product_type', 2)->select('p.name as title', 'p.id', 'p.status', 'order_product_detail.amount', 'order_product_detail.admin_amount', DB::raw("(select attribute_value from product_details pd where p.id = pd.product_id and attribute_type = 'cover_image') as image"));
    }
}
