<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserChapterStatus extends Model
{
    use HasFactory;
    protected $table = 'user_chapter_status';
    protected $primaryKey = 'id ';
    public $timestamps = false;

    public function chapterSection()
    {
        return $this->hasMany(CourseChapterStep::class, 'course_chapter_id', 'id');
    }
}
