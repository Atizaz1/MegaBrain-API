<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use DB;
use Hash;

class User extends Authenticatable implements JWTSubject
{
    public $timestamps = false;

    protected $primaryKey = 'userId';
    
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = 
    [
        'nickname', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = 
    [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = 
    [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function storeUser($object)
    {
       return  DB::transaction(function () use ($object)  
       {
            $user                 = new User;
            
            $user->nickname       = $object['nickname'];

            $user->fullname       = $object['fullname'];

            $user->email          = $object['email'];
            
            $user->password       = Hash::make($object['password']);

            $user->borndate       = date('Y-m-d',strtotime($object['borndate']));

            $user->register_date  = date('Y-m-d',strtotime(Date('Y-m-d')));

            // $user->sex            = $object['sex'];

            $user->partner_code   = $object['partner_code'];

            $user->app_code       = $object['app_code'];

            $user->photo          = $this->generateImageFromEncoding($object['photo']);

	        $user->course         = $object['course'];

            $user->state          = $object['state'];

            $user->city           = $object['city'];

            $user->verifyToken    = $object['verifyToken'];

            $user->isVerify       = $object['isVerify'];

            $user->save();

            return with($user);

        });
    }

    public function storeUser2($object)
    {
       return  DB::transaction(function () use ($object)  
       {
            $user                 = new User;

            $user->fullname       = $object['fullname'];

            $user->email          = $object['email'];
            
            $user->password       = Hash::make($object['password']);

            $user->register_date  = date('Y-m-d',strtotime(Date('Y-m-d')));

            // $user->sex            = $object['sex'];

            $user->partner_code   = $object['partner_code'];

            $user->app_code       = $object['app_code'];

            $user->verifyToken    = $object['verifyToken'];

            $user->isVerify       = $object['isVerify'];

            $user->save();

            return with($user);

        });
    }

    public function updateUser($object)
    {
       return  DB::transaction(function () use ($object)  
       {
            $user                 = $this->getUserById($object['userId']);

            if(isset($user->userId))
            {
                $user->nickname       = $object['nickname'];

                $user->fullname       = $object['fullname'];

                $user->email          = $object['email'];
                
                $user->borndate       = date('Y-m-d',strtotime($object['borndate']));

                // $user->sex            = $object['sex'];

                $user->partner_code   = $object['partner_code'];

                $user->course         = $object['course'];

                $user->state          = $object['state'];

                $user->city           = $object['city'];

                $user->photo          = $this->generateImageFromEncoding($object['photo']);

                if(isset($object['password']))
                {
                    $user->password       = Hash::make($object['password']);
                }

                $user->update();

                return with($user);
            }
        });
    }

    public function updateUserPartnerCode($email, $partnerCode)
    {
        return  DB::transaction(function () use ($email, $partnerCode)  
       {
            $user                     = $this->getUserByEmail($email);

            if(isset($user->userId))
            {
                $user->partner_code   = $partnerCode;

                $user->update();

                return with($user);
            }
        });
    }
	
    public function generateImageFromEncoding($encodedImage)
    {
        $directoryPath = 'd:\web\localuser\megabrain-enem\www\API';

        if($encodedImage == 'null')
        {
            return 'user.png'; 
        }
        else
        {
            $parsedEncodedImage = explode(',',$encodedImage);

            $realImage = base64_decode($parsedEncodedImage[0]);

            file_put_contents($directoryPath.'/'.$parsedEncodedImage[1], $realImage);
                
            return $parsedEncodedImage[1];
        }
    }

    public function uploadFile($file)
    {
        $name = uniqid().'.'.$file->getClientOriginalExtension();

        \Storage::disk('local')->put('user_images/'.$name,file_get_contents($file));

        return $name;
    }

    public function getUserById($id)
    {
        return User::find($id);
    }

    public function approveUserAccount($id)
    {
       return  DB::transaction(function () use ($id)  
       {
            $user  = $this->getUserById($id);

            if(isset($user->userId))
            {
                $user->isVerify   = 1;

                $user->update();

                return with($user);
            }
            else
            {
                return 404;
            }
            
        });
    }

    public function resetUserPassword($object)
    {
       return  DB::transaction(function () use ($object)  
       {
            $user  = $this->getUserById($object['id']);

            if(isset($user->userId))
            {
                $user->password   = \Hash::make($object['password']);

                $user->update();

                return with($user);
            }
            else
            {
                return 404;
            }
            
        });
    }

    public function registerSocialUser($object)
    {
       return DB::transaction(function () use ($object)  
       {
            $user                 = new User;
            
            $user->fullname       = $object['name'];

            $user->email          = $object['email'];

            $user->photo          = $this->generateImageFromEncoding('null');

            $user->facebookId     = $object['id'];

            $user->app_code       = $object['app_code'];

            $user->IsSocial       = 1;

            $user->register_date  = date('Y-m-d',strtotime(Date('Y-m-d')));

            $user->save();

            return with($user);

        });
    }


    /*
        DB_CONNECTION=mysql
        DB_HOST=mysql.megabrain-enem.com.br
        DB_PORT=3306
        DB_DATABASE=megabrainenem
        DB_USERNAME=megabrainenem
        DB_PASSWORD=brazil2548
    */

    /*

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=megabrainapi
    DB_USERNAME=root
    DB_PASSWORD=

    */
}
