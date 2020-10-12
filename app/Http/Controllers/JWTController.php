<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRegisterRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\User;
use App\Token;
use Hash;
use JWTAuth;
use Auth;
use JWTFactory;

class JWTController extends Controller
{
    
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','verifyUserToken','verifyPasswordResetToken','sendPasswordResetToken','resetPassword','socialRegister', 'storeFreeUser2']]);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) 
        {
            $user = $this->guard()->user();

            if($user->isVerify == 1)
            {
                return $this->respondWithToken($token);
            }
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function register(UserRequest $request)
    {
        $form_collect = $request->input();

        $user = new User;

        $curr_user = $user->storeUser($form_collect);

        if(isset($curr_user->userId))
        {
            return response()->json(['success'=>'You have been registered successfully. You can now Log In','user'=>$curr_user], 200);
        }
        else
        {
            return response()->json(['errors'=>'Something Went Wrong. Please Try Again.'], 500);
        }
    }

    public function socialRegister(Request $request)
    {
        $form_collect = $request->input();

        if(!isset($form_collect['email']))
        {
            return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
        }

        $user = new User;

        $existingUser = $user->getUserByEmail($form_collect['email']);

        if(isset($existingUser->userId))
        {
            $token = $this->guard()->fromUser($existingUser);

            return $this->respondWithToken($token);
        }
        else
        {
            $curr_user = $user->registerSocialUser($form_collect);

            if(isset($curr_user->userId))
            {
                $token = $this->guard()->fromUser($curr_user);

                return $this->respondWithToken($token);
            }
            else
            {
                return response()->json(['error'=>'Something Went Wrong. We couldn\'t store user Information'], 500);
            }
        }
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json($this->guard()->user());
    }

    public function updateUserProfile(UserRequest $request)
    {
        $form_collect = $request->input();

        $user = new User;

        $curr_user = $user->updateUser($form_collect);

        if(isset($curr_user->userId))
        {
            return response()->json(['success'=>'Your profile has been updated successfully','user'=>$curr_user], 200);
        }
        else
        {
            return response()->json(['errors'=>'Something Went Wrong. Please Try Again.'], 500);
        }
    }

    public function updateUserPartnerCode($email, $partnerCode)
    {
        $user = new User;

        $curr_user = $user->updateUserPartnerCode($email, $partnerCode);

        if(isset($curr_user->userId))
        {
            return response()->json(['success'=>'Partner Code Has been Updated Successfully','user'=>$curr_user], 200);
        }
        else
        {
            return response()->json(['errors'=>'Something Went Wrong. Please Try Again.'], 500);
        }
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json(
        [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }

    public function resetPassword(Request $request)
    {
        $form_collect = $request->input();

        if(!isset($form_collect['email']) || !isset($form_collect['password']))
        {
            return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
        }

        $user = new User;

        $user = $user->getUserByEmail($form_collect['email']);

        if(isset($user->userId))
        {
            $form_collect['id'] = $user->userId;

            $curr_user = $user->resetUserPassword($form_collect);

            if(isset($curr_user->userId))
            {
                return response()->json(['success'=>'User Password Updated Successfully'], 200);
            }
            else
            {
                return response()->json(['error'=>'User Not Found'], 404);
            }
        }
        else
        {
            return response()->json(['error'=>'User Not Found'], 404);
        }

    }

    public function storeFreeUser2(UserRequest $request)
    {
        $form_collect = $request->input();

        $user = new User;

        $curr_user = $user->storeUser2($form_collect);

        if(isset($curr_user->userId))
        {
            return response()->json(['success'=>'Free User Information has been saves successfully','user'=>$curr_user], 200);
        }
        else
        {
            return response()->json(['errors'=>'Something Went Wrong. Please Try Again.'], 500);
        }
    }
}
