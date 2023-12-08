<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'course';
    protected $primaryKey = 'id ';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'admin_id',
        'title',
        'description',
        'fee_type',
        'course_fee',
        'valid_upto',
        'tags',
        'certificates',
        'status',
        'category_id'
    ];
}
