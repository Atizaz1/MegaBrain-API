<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getImagePathSubjectWise($subjectCode, $areaCode, $topicCode)
    {
    	if(!isset($subjectCode) || !isset($areaCode) || !isset($topicCode))
    	{
    		return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
    	}

    	$image      = new Image;

    	$curr_image = $image->getImagePathsSubjectWise($subjectCode, $areaCode, $topicCode);

    	if(@count($curr_image) > 0)
    	{
    		$links = $this->copyAndGenerateLinks($curr_image);

    		if(@count($links) > 0)
    		{
    			return response()->json($links, 200);
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

    	$originalImagesDirectoryPath    = "C:/Users/Atizaz/Desktop/API/images";

    	$destinationImagesDirectoryPath = "C:/Users/Atizaz/Desktop/API/megabrainapi/public";

    	if(is_a($image, 'Illuminate\Database\Eloquent\Collection'))
    	{
    		for ($i = 0; $i < @count($image); $i++) 
    		{
    			if(file_exists($originalImagesDirectoryPath.'/'.$image[$i]->image_name) == 1)
    			{
    				copy($originalImagesDirectoryPath.'/'.$image[$i]->image_name, $destinationImagesDirectoryPath.'/'.$image[$i]->image_name);
    				$imageLinks[] = asset($image[$i]->image_name);	
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
