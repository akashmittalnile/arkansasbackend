<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'payment_detail';
    protected $primaryKey = 'id ';
    public $timestamps = false;
    protected $fillable = [
        'status',
        'transaction_id',
        'card_id',
        'amount',
        'created_date',
    ];
}
