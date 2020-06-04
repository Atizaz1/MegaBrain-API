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
    	$news       = new News;

    	$news_list  = [];

        $image_list = [];

    	$news_list  = $news->getLastNNews();

    	if(@count($news_list) > 0)
    	{
            $image_list = $this->copyAndGenerateLinks($news_list);

            $temp_list  = [];

            for($i = 0; $i < count($news_list); $i++ )
            {
                $temp_list[$i] = $news_list[$i];

                if(count($image_list) <= count($news_list))
                {
                    $temp_list[$i]->news_image = $image_list[$i];
                }
                else
                {
                    $temp_list[$i]->news_image = 'default_news';
                }
            }

    		return $temp_list;
    	}
    	else
    	{
    		return response()->json(['error'=>'News Not Found'], 404);		
    	}
    }

    public function copyAndGenerateLinks($image)
    {
        $imageLinks = [];

        $originalImagesDirectoryPath    = "d:\web\localuser\megabrain-enem\www\images";

        $destinationImagesDirectoryPath = "d:\web\localuser\megabrain-enem\www\API";

        if(is_a($image, 'Illuminate\Database\Eloquent\Collection'))
        {
            for ($i = 0; $i < @count($image); $i++) 
            {
                if(file_exists($originalImagesDirectoryPath.'/'.$image[$i]->news_image) == 1)
                {
                    copy($originalImagesDirectoryPath.'/'.$image[$i]->news_image, $destinationImagesDirectoryPath.'/'.$image[$i]->news_image);
                    $imageLinks[] = asset($image[$i]->news_image);  
                }   
            }
            if(@count($imageLinks) > 0)
            {
                return $imageLinks; 
            }
        }
        else if(file_exists($originalImagesDirectoryPath.'/'.$image) == 1)
        {
            copy($originalImagesDirectoryPath.'/'.$image, $destinationImagesDirectoryPath.'/'.$image);
            return asset($image);   
        }
        else
        {
            return response()->json(['error'=>'Source Image Not Found'], 404);
        }
    }
}
