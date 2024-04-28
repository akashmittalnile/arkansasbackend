<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationCreator extends Model
{
    use HasFactory;
    protected $table = 'notifications_creators';
    protected $primaryKey = 'id ';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id')->withDefault(['first_name' => 'NA', 'last_name' => 'NA', 'email' => 'NA']);
    }
}
