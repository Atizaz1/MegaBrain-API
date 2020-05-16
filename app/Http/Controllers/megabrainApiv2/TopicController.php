<?php

namespace App\Http\Controllers\megabrainApiv2;

use Illuminate\Http\Request;
use App\Topic;
use App\Http\Controllers\Controller;

class TopicController extends Controller
{
    public function getTopicByCode($code)
    {
    	if(!isset($code))
    	{
    		return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
    	}

    	$topic      = new Topic;

    	$curr_topic = $topic->getTopicByCode($code);

    	if(isset($curr_topic->topic_code))
    	{
    		return $curr_topic;
    	}
    	else
    	{
    		return response()->json(['error'=>'Topic Not Found'], 404);
    	}
    }

    public function getTopicByName($name)
    {
        if(!isset($name))
        {
            return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
        }

        $topic      = new Topic;

        $curr_topic = $topic->getTopicByName($name);

        if(isset($curr_topic->topic_code))
        {
            return $curr_topic;
        }
        else
        {
            return response()->json(['error'=>'Topic Not Found'], 404);
        }
    }

    public function getTopicList()
    {
    	$topic = new Topic;

    	$topic_list = [];

    	$topic_list = $topic->getAllTopics();

    	if(@count($topic_list) > 0)
    	{
    		return $topic_list;
    	}
    	else
    	{
    		return response()->json(['error'=>'Topics Not Found'], 404);    		
    	}
    }

    public function getTopicBySubjectAndAreaCodes($subjectCode, $areaCode)
    {
        if(!isset($subjectCode) || !isset($areaCode))
        {
            return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
        }

        $topic      = new Topic;

        $curr_topic = $topic->getTopicBySubjectAndAreaCodes($subjectCode, $areaCode);

        if(@count($curr_topic))
        {
            return $curr_topic;
        }
        else
        {
            return response()->json(['error'=>'Topic Not Found'], 404);
        }
    }
}
