<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Area;

class SchoolSubject extends Model
{
    public    $timestamps = false;

    protected $primaryKey = 'ss_code';

    protected $table      = 'school_subjects';

    public function getSchoolSubjectByCode($code)
    {
    	return SchoolSubject::find($code);
    }

    public function getSchoolSubjectByName($name)
    {
        return SchoolSubject::where('ss_name',$name)->first();
    }

    public function getAllSchoolSubject()
    {
    	return SchoolSubject::all();
    }

    public function areas()
    {
        return $this->hasMany(Area::class,'ss_code');
    }
}
