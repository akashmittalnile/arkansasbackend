<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturningReason extends Model
{
    use HasFactory;
    protected $table = 'returning_reasons';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
