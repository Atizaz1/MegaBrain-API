<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserPurchase extends Model
{
    public $timestamps    = false;

    protected $primaryKey = 'Id';

    protected $table      = 'users_purchase';

    public function getUserPurchaseByEmail($email)
    {
    	return UserPurchase::where('email', $email)->first();
    }

    public function getUserPurchaseById($id)
    {
    	return UserPurchase::find($id);
    }

    public function getConfirmedPurchaseByEmail($email)
    {
    	return UserPurchase::where(['email' => $email, 'purchase_activated' => '1'])->first();
    }

    public function updateContentDate($email)
    {
    	return DB::transaction(function () use ($email)  
       {
            $userPurchase             = $this->getUserPurchaseByEmail($email);

            if(isset($userPurchase->Id))
            {
                $userPurchase->purchase_activated  = '0';

                $userPurchase->update();

                return with($userPurchase);
            }
        });
    }

    public function updateUserPurchaseById($object)
    {
       return DB::transaction(function () use ($object)  
       {
            $userPurchase             = $this->getUserPurchaseById($object['id']);

            if(isset($userPurchase->Id))
            {
                $userPurchase->purchase_activation_IP   = $object['purchase_ip'];

                $userPurchase->purchase_activated       = $object['purchase_activated'];

                $userPurchase->purchase_activation_date = date('Y-m-d',strtotime(Date('Y-m-d')));
                
                $userPurchase->purchase_equipment       = $object['purchase_equipment'];

                $userPurchase->update();

                return with($userPurchase);
            }
        });
    }
}
