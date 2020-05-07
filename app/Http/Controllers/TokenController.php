<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Token;
use App\User;
use Str;

class TokenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['verifyPasswordResetToken','verifyUserAccountToken','sendPasswordResetToken']]);
    }

    public function verifyUserAccountToken(Request $request)
    {
        $form_collect = $request->input();

        if(!isset($form_collect['token']) || !isset($form_collect['id']))
        {
            return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
        }

        $token   = new Token;

        $status  = $token->verifyUserAccountToken($form_collect);

        if($status == 401)
        {
            return response()->json(['error'=>'Bad Token'], 401);
        }

        if($status == 404)
        {
            return response()->json(['error'=>'User Not Found'], 404);
        }

        return response()->json(['success'=>'Account Activated Successfully'], 200);

    }

    public function sendPasswordResetToken(Request $request)
    {
        $form_collect = $request->input();

        if(!isset($form_collect['email']))
        {
            return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
        }

        $user   = new User;

        $user = $user->getUserByEmail($form_collect['email']);

        if(isset($user->userId))
        {
            // $user_password_token = Str::random(8);

            $user_password_token = rand(1000,9999); 

            $token_object['token']   = $user_password_token;

            $token_object['user_id'] = $user->userId;

            $token = new Token;

            $curr_token = $token->savePasswordResetToken($token_object);

            if(isset($curr_token))
            {
                return response()->json(['success'=>'Token Sent', 'token' => $user_password_token], 200);
            }
            else
            {
                return response()->json(['error'=>'Something Went Wrong'], 500);
            }
        }
        else
        {
            return response()->json(['error'=>'User Not Found. Incorrect Email'], 422);
        }
    }

    public function verifyPasswordResetToken(Request $request)
    {
        $form_collect = $request->input();

        if(!isset($form_collect['email']) || !isset($form_collect['token']))
        {
            return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
        }

        $token        = new Token;

        $user         = new User;

        $curr_user    = $user->getUserByEmail($form_collect['email']);

        if(isset($curr_user->userId))
        {
            $curr_token = $token->getTokenByUserId($curr_user->userId);

            if(isset($curr_token->Id))
            {
                if(\Hash::check($form_collect['token'], $curr_token->Token))
                {
                    return response()->json(['success'=>'Token Match', 'email' => $curr_user->email], 200);
                }
                else
                {
                    return response()->json(['error'=>'Token Mismatch', 'email' => $curr_user->email], 422);
                }
            }
            else
            {
                return response()->json(['error'=>'Token Not Found', 'email' => $curr_user->email], 404);
            }
        }
        else
        {
            return response()->json(['error'=>'User Not Found'], 404);
        }
    }
}
