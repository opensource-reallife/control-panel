<?php

namespace App\Models\Logs;

use App\Models\User;
use App\Models\Faction;
use App\Models\Company;
use App\Models\Group;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Model;

class VehicleDamage extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'vehicle_damage';
    protected $connection = 'mysql_logs';
    public $timestamps = false;

    protected $casts = ['StartDate' => 'datetime', 'Date' => 'datetime'];

    public function user()
    {
        return $this->hasOne(User::class, 'Id', 'UserId');
    }

    public function target()
    {
        return $this->hasOne(Vehicle::class, 'Id', 'VehicleId');
    }

}
