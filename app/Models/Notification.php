<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $table = 'notifications';
    protected $primaryKey = 'id ';
    public $timestamps = false;

    public function notificationCreator()
    {
        return $this->hasMany(NotificationCreator::class, 'notification_id', 'id');
    }
}
