<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class FreeUser extends Model
{
    public    $timestamps = false;

    protected $primaryKey = 'userId';

    protected $table      = 'users_free';

    public function getFreeUserById($id)
    {
        return FreeUser::find($id);
    }

    public function getFreeUserByEmail($email)
    {
        return FreeUser::where('email', $email)->first();
    }

    public function storeFreeUser($object)
    {
       return  DB::transaction(function () use ($object)  
       {
            $user                 = new FreeUser;
            
            $user->fullname       = $object['fullname'];

            $user->email          = $object['email'];
            
            $user->borndate       = date('Y-m-d',strtotime($object['borndate']));

            $user->register_date  = date('Y-m-d',strtotime(Date('Y-m-d')));

            $user->sex            = $object['sex'];

            $user->verifyToken    = $object['verifyToken'];

            $user->isVerify       = $object['isVerify'];

            $user->save();

            return with($user);

        });
    }

    public function approveFreeUserAccount($id)
    {
       return  DB::transaction(function () use ($id)  
       {
            $freeUser  = $this->getFreeUserById($id);

            if(isset($freeUser->userId))
            {
                $freeUser->isVerify   = 1;

                $freeUser->update();

                return with($freeUser);
            }
            else
            {
                return 404;
            }
            
        });
    }
}
