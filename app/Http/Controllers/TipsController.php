<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tips;

class TipsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getTipsList()
    {
    	$tips = new Tips;

    	$tips_list = [];

    	$tips_list = $tips->getAllTips();

    	if(@count($tips_list) > 0)
    	{
    		return $tips_list;
    	}
    	else
    	{
    		return response()->json(['error'=>'Tips Not Found'], 404);		
    	}
    }

    public function getTipsById($id)
    {
    	if(!isset($id))
    	{
    		return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
    	}

    	$tips      = new Tips;

    	$curr_tips = $tips->getTipsById($id);

    	if(isset($curr_tips->id))
    	{
    		return $curr_tips;
    	}
    	else
    	{
    		return response()->json(['error'=>'Tips Not Found'], 404);
    	}
    }

    public function getNOrderedTips()
    {
    	$tips       = new Tips;

    	$tips_list  = [];

        $image_list = [];

    	$tips_list  = $tips->getLastNTips();

    	if(@count($tips_list) > 0)
    	{
            $image_list = $this->copyAndGenerateLinks($tips_list);

            $temp_list  = [];

            for($i = 0; $i < count($tips_list); $i++ )
            {
                $temp_list[$i] = $tips_list[$i];

                if(count($image_list) <= count($tips_list))
                {
                    $temp_list[$i]->tip_image = $image_list[$i];
                }
                else
                {
                    $temp_list[$i]->tip_image = 'default_tips';
                }
            }

    		return $temp_list;
    	}
    	else
    	{
    		return response()->json(['error'=>'Tips Not Found'], 404);		
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
                if(file_exists($originalImagesDirectoryPath.'/'.$image[$i]->tip_image) == 1)
                {
                    copy($originalImagesDirectoryPath.'/'.$image[$i]->tip_image, $destinationImagesDirectoryPath.'/'.$image[$i]->tip_image);
                    $imageLinks[] = asset($image[$i]->tip_image);  
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
