<?php

namespace App\Http\Controllers\megabrainApiv2;

use Illuminate\Http\Request;
use App\FreeUser;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;

class FreeUsersController extends Controller
{
    public function storeFreeUser(UserRequest $request)
    {
        $form_collect = $request->input();

        $freeUser = new FreeUser;

        $currentFreeUser = $freeUser->storeFreeUser($form_collect);

        if(isset($currentFreeUser->userId))
        {
            return response()->json(['success'=>'Free User Information has been saves successfully','user'=>$currentFreeUser], 200);
        }
        else
        {
            return response()->json(['errors'=>'Something Went Wrong. Please Try Again.'], 500);
        }
    }

    public function checkFreeUser($email)
    {
    	if(!isset($email))
    	{
    		return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
    	}
    	else if( !filter_var(trim(strtolower($email)), FILTER_VALIDATE_EMAIL) )
    	{
    		return response()->json(['error'=>'Bad Request. Invalid Email Format'], 400);
    	}

        $freeUser = new FreeUser;

        $currentFreeUser = $freeUser->getFreeUserByEmail($email);

        if(isset($currentFreeUser->userId))
        {
            return response()->json(['success'=>'Free User Found','user'=>$currentFreeUser], 200);
        }
        else
        {
            return response()->json(['errors'=>'Free User Not Found.'], 404);
        }
    }

    public function verifyFreeUserToken(Request $request)
    {
    	$form_collect = $request->input();

    	if(!isset($form_collect['token']) || !isset($form_collect['id']))
    	{
    		return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
    	}

        $freeUser = new FreeUser;

        $currentFreeUser = $freeUser->getFreeUserById($form_collect['id']);

        if(isset($currentFreeUser->userId))
        {
            if($currentFreeUser->verifytoken == $form_collect['token'])
            {
                $tempFreeUser = $freeUser->approveFreeUserAccount($form_collect['id']);

                if(isset($tempFreeUser))
                {
        			return response()->json(['success'=>'Account Activated Successfully'], 200);
                }

            }
            else
            {
                return response()->json(['error'=>'Bad Token'], 401);
            }
        }
        else
        {
            return response()->json(['error'=>'User Not Found'], 404);
        }
    }
}
