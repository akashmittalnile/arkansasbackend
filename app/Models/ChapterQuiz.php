<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChapterQuiz extends Model
{
    use HasFactory;
    protected $table = 'chapter_quiz';
    protected $primaryKey = 'id ';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'title',
        'type',
        'file',
        'desc',
        'chapter_id',
        // Add more attributes as needed...
    ];
}
