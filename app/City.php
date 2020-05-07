<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\State;

class City extends Model
{
    protected $table      = 'city';

    protected $primaryKey = 'city_code';

    public $timestamps = false;

    public function state()
    {
        return $this->belongsTo(State::class,'state_id')->withDefault();
    }

    public function getCityByCode($code)
    {
    	return City::find($code);
    }

    public function getAllCities()
    {
    	return City::all();
    }

    public function getCityByName($city)
    {
        return City::where('city_name', $city)->first();
    }
}
