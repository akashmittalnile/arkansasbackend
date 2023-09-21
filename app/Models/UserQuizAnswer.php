<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuizAnswer extends Model
{
    use HasFactory;
    protected $table = 'user_quiz_answer';
    protected $primaryKey = 'id ';
    public $timestamps = false;
}
