<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $primaryKey = 'Id';

    public function members()
    {
        return $this->hasMany(Character::class, 'CompanyId', 'Id');
    }

    public function membersCount()
    {
        return Character::where('CompanyId', $this->Id)->count();
    }

    public function logs()
    {
        return GroupLog::where('GroupType', 'company')->where('GroupId', $this->Id);
    }

    public function getActivity(Carbon $from, Carbon $to)
    {
        return AccountActivityGroup::getActivity($this->Id, 2, $from, $to);
    }

    public function getColor($alpha = 1)
    {
        $color = config('constants.companyColors')[0];

        if (config('constants.companyColors')[$this->Id]) {
            $color = config('constants.companyColors')[$this->Id];
        }

        return "rgba(".$color[0].", ".$color[1].", ".$color[2].", ".$alpha.")";
    }
}
