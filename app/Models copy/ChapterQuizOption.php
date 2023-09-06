<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChapterQuizOption extends Model
{
    use HasFactory;
    protected $table = 'chapter_quiz_options';
    protected $primaryKey = 'id ';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'quiz_id',
        'answer_option_key',
        'answer_value',
        'status'
    ];
}
