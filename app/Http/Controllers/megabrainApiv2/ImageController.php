<?php

namespace App\Http\Controllers\megabrainApiv2;

use Illuminate\Http\Request;
use App\Image;
use App\Http\Controllers\Controller;

class ImageController extends Controller
{
    public function getImagePathSubjectWise($subjectCode, $areaCode, $topicCode)
    {
    	if(!isset($subjectCode) || !isset($areaCode) || !isset($topicCode))
    	{
    		return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
    	}

    	$image      = new Image;

    	$curr_image = $image->getImagePathsSubjectWise($subjectCode, $areaCode, $topicCode);

        $final_images = [];

    	if(@count($curr_image) > 0)
    	{
            $final_images = $this->copyAndGenerateLinks($curr_image);

    		if(@count($final_images) > 0)
    		{
    			return response()->json($final_images, 200);
    		}
    		else
    		{
    			return response()->json(['error'=>'No Images Found'], 404);	
    		}
    	}
    	else
    	{
    		return response()->json(['error'=>'No Images Found'], 404);
    	}
    }

    /* 
		Server Paths
		
		Server Original Path = "d:\web\localuser\megabrain-enem\www\images"

		Server Source Path   = "d:\web\localuser\megabrain-enem\www\API"
    */

    public function copyAndGenerateLinks($image)
    {
    	$imageLinks = [];

    	$originalImagesDirectoryPath    = "d:\web\localuser\megabrain-enem\www\images";

    	$destinationImagesDirectoryPath = "d:\web\localuser\megabrain-enem\www\API";

    	if(is_a($image, 'Illuminate\Database\Eloquent\Collection'))
    	{
    		for ($i = 0; $i < @count($image); $i++) 
    		{
    			if(file_exists($originalImagesDirectoryPath.'/'.$image[$i]->image_name) == 1)
    			{
    				copy($originalImagesDirectoryPath.'/'.$image[$i]->image_name, $destinationImagesDirectoryPath.'/'.$image[$i]->image_name);
    				$imageLinks[] = $image[$i];	
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

    
    public function getImagePathList()
    {
        $image = new Image;

        $image_list = [];

        $image_list = $image->getAllImagePaths();

        if(@count($image_list) > 0)
        {
            return $image_list;
        }
        else
        {
            return response()->json(['error'=>'Images Not Found'], 404);     
        }
    }
}
