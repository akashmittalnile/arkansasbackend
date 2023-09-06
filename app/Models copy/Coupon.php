<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $table = 'coupons';
    protected $primaryKey = 'id ';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'title',
        'description',
        'fee_type',
        'course_fee',
        'valid_upto',
        'tags',
        'certificates',
        'status'
    ];
}
