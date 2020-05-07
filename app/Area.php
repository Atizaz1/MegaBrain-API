<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SchoolSubject;

class Area extends Model
{
    public    $timestamps = false;

    protected $primaryKey = 'area_code';

    public function getAreaByCode($code)
    {
    	return Area::find($code);
    }

    public function getAreaByName($name)
    {
        return Area::where('area_name',$name)->first();
    }

    public function subject()
    {
        return $this->belongsTo(SchoolSubject::class,'ss_code')->withDefault();
    }

    public function getAllAreas()
    {
    	return Area::all();
    }
}
