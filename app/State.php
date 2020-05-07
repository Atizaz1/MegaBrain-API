<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\City;

class State extends Model
{
    protected $table      = 'state';

    protected $primaryKey = 'state_code';

    public $timestamps = false;

    public function cities()
    {
        return $this->hasMany(City::class,'state_id');
    }

    public function getStateByCode($code)
    {
    	return State::find($code);
    }

    public function getAllStates()
    {
    	return State::all();
    }

    public function getStateByName($state)
    {
        return State::where('state_name', $state)->first();
    }
}
