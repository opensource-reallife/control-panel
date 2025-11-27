<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketBan extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = 'CreatedAt';

    protected $primaryKey = 'Id';
    protected $casts = ['CreatedAt' => 'datetime', 'BannedUntil' => 'datetime'];


    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }

    public function admin()
    {
        return $this->hasOne(User::class, 'Id', 'AdminId');
    }
}
