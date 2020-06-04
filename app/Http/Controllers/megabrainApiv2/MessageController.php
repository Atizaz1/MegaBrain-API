<?php

namespace App\Http\Controllers\megabrainApiv2;

use Illuminate\Http\Request;
use App\Message;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function getMessageList()
    {
    	$message = new Message;

    	$message_list = [];

    	$message_list = $message->getAllMessages();

    	if(@count($message_list) > 0)
    	{
    		return $message_list;
    	}
    	else
    	{
    		return response()->json(['error'=>'Message Not Found'], 404);		
    	}
    }

    public function getMessageById($id)
    {
    	if(!isset($id))
    	{
    		return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
    	}

    	$message      = new Message;

    	$curr_message = $message->getMessageById($id);

    	if(isset($curr_message->id))
    	{
    		return $curr_message;
    	}
    	else
    	{
    		return response()->json(['error'=>'Message Not Found'], 404);
    	}
    }

    public function getTopPriorityMessage()
    {
    	$message       = new Message;

    	$curr_message  = $message->getTopPriorityMessage();

    	if(isset($curr_message->id))
    	{
            $message_img = $this->copyAndGenerateLink($curr_message);

            if(isset( $message_img ) )
            {
            	$curr_message->message_image = $message_img;

            	return $curr_message;
            }
    	}
    	else
    	{
    		return response()->json(['error'=>'Message Not Found'], 404);		
    	}
    }

    public function copyAndGenerateLink($message)
    {
        $imageLink = null;

        $originalImagesDirectoryPath    = "d:\web\localuser\megabrain-enem\www\images";

        $destinationImagesDirectoryPath = "d:\web\localuser\megabrain-enem\www\API";

        if(is_a($message, 'App\Message'))
        {
            if(file_exists($originalImagesDirectoryPath.'/'.$message->message_image) == 1)
            {
                copy($originalImagesDirectoryPath.'/'.$message->message_image, $destinationImagesDirectoryPath.'/'.$message->message_image);
                $imageLink = asset($message->message_image);  
            }

            return $imageLink;   
        }
        else
        {
            return response()->json(['error'=>'Source Image Not Found'], 404);
        }
    }
}
