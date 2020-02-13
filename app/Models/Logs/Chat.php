<?php

namespace App\Models\Logs;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $primaryKey = 'ID';
    protected $table = 'Chat';
    protected $connection = 'mysql_logs';
    public $timestamps = false;

    protected $dates = ['Date'];

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }
}
