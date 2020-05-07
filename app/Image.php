<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public    $timestamps = false;

    protected $primaryKey = 'image_code';

    public function getImagePathByCode($code)
    {
    	return Image::find($code);
    }

    public function getImagePathByName($name)
    {
    	return Image::where('image_name',$name)->first();
    }

    public function getAllImagePaths()
    {
    	return Image::all();
    }

    public function getImagePathsSubjectWise($subjectCode, $areaCode, $topicCode)
    {
    	return Image::where(['ss_code'=>$subjectCode, 'area_code' => $areaCode , 'topic_code' => $topicCode])->get();
    }
}
