<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'user_review';
    protected $primaryKey = 'id ';
    public $timestamps = false;

    protected $fillable = [
        'userid',
        'object_id',
        'object_type',
        'rating',
        'review',
        'status',
        // Add more attributes as needed...
    ];
}
