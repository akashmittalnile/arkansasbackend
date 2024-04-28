<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CourseChapterStep extends Model
{
    use HasFactory;
    protected $table = 'course_chapter_steps';
    protected $primaryKey = 'id ';
    public $timestamps = false;

    public function quiz()
    {
        return $this->hasMany(ChapterQuiz::class, 'step_id', 'id');
    }

    public function chapterStep($id)
    {
        $status = $this->belongsTo(UserChapterStatus::class, 'id', 'step_id')->where('userid', $id)->select('status', 'file')->first();
        return $status;
    }

    protected $fillable = [
        'id',
        'title',
        'type',
        'details',
        'description',
        'course_chapter_id',
        '',
        // Add more attributes as needed...
    ];
}
