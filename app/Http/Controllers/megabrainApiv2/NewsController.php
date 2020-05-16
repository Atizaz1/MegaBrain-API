<?php

namespace App\Http\Controllers\megabrainApiv2;

use Illuminate\Http\Request;
use App\News;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    public function getNewsList()
    {
    	$news = new News;

    	$news_list = [];

    	$news_list = $news->getAllNews();

    	if(@count($news_list) > 0)
    	{
    		return $news_list;
    	}
    	else
    	{
    		return response()->json(['error'=>'News Not Found'], 404);		
    	}
    }

    public function getNewsById($id)
    {
    	if(!isset($id))
    	{
    		return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
    	}

    	$news      = new News;

    	$curr_news = $news->getNewsById($id);

    	if(isset($curr_news->id))
    	{
    		return $curr_news;
    	}
    	else
    	{
    		return response()->json(['error'=>'News Not Found'], 404);
    	}
    }

    public function getNOrderedNews()
    {
    	$news = new News;

    	$news_list = [];

    	$numberOfNews = 5;

    	$news_list = $news->getLastNNews($numberOfNews);

    	if(@count($news_list) > 0)
    	{
    		return $news_list;
    	}
    	else
    	{
    		return response()->json(['error'=>'News Not Found'], 404);		
    	}
    }
}
