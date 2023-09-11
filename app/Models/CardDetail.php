<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardDetail extends Model
{
    use HasFactory;
    protected $table = 'payment_methods';
    protected $primaryKey = 'id ';
    public $timestamps = false;
    protected $fillable = [
        'userid',
        'method_type',
        'card_no',
        'card_type',
        'expiry',
        'CVV',
        'name_on_card',
        'is_default',
        'is_active',
        'created_date',
        'modified_date'
    ];
}
