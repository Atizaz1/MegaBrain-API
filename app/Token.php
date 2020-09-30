<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use DB;

class Token extends Model
{
    protected $table      = 'token';

    protected $primaryKey = 'Id';

    public    $timestamps = false;

    public function getTokenById($id)
    {
      return Token::find($id)->first();
    }

    public function getTokenByUserId($user_id)
    {
      return Token::where('userId',$user_id)->first();
    }

    public function savePasswordResetToken($object)
    {
       return DB::transaction(function () use ($object)  
       {
            $token      = new Token;

            $curr_token = $token->getTokenByUserId($object['user_id']);

            if(isset($curr_token->Id))
            {
                $curr_token->Token = \Hash::make($object['token']);

                $curr_token->update();

                return with($curr_token);
            }
            else
            {
                $token->UserId          = $object['user_id'];

                $token->Token           = \Hash::make($object['token']);

                $token->CreatedDate     = Carbon::now();

                $token->LastAccessDate  = Carbon::now();

                $token->isExpired       = 0;

                $token->save();

                return with($token);
            }
        });
    }

    public function verifyUserAccountToken($object)
    {
        $user      = new User;

        $curr_user = $user->getUserById($object['id']);

        if(isset($curr_user->userId))
        {
            if($curr_user->verifytoken == $object['token'])
            {
                $user->approveUserAccount($object['id']);
            }
            else
            {
                return 401;
            }
        }
        else
        {
            return 404;
        }
    }
}
