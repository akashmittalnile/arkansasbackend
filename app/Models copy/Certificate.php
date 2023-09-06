<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;
    protected $table = 'certificates';
    protected $primaryKey = 'id ';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'certificate_image',
        'status'
    ];
}
