<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    public    $timestamps = false;

    protected $primaryKey = 'topic_code';

    public function getTopicByCode($code)
    {
    	return Topic::find($code);
    }

    public function getTopicByName($name)
    {
        return Topic::where('topic_name',$name)->first();
    }

    public function getAllTopics()
    {
    	return Topic::all();
    }

    public function getTopicBySubjectAndAreaCodes($subjectCode, $areaCode)
    {
        return Topic::where(['ss_code' => $subjectCode, 'area_code' => $areaCode])->get();
    }
}
