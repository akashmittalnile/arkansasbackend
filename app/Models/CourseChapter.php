<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseChapter extends Model
{
    use HasFactory;
    protected $table = 'course_chapter';
    protected $primaryKey = 'id ';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'course_id',
    ];
}
