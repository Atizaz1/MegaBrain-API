<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

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
    	return UserPurchase::where('email', $email)->where('purchase_limit_date' , '>=', Carbon::today()->toDateString())->where('purchase_activated','1')->first();
    }

    // public function updateContentDate($email)
    // {
    // 	return DB::transaction(function () use ($email)  
    //    {
    //         $userPurchase             = $this->getUserPurchaseByEmail($email);

    //         if(isset($userPurchase->Id))
    //         {
    //             $userPurchase->purchase_activated  = '0';

    //             $userPurchase->update();

    //             return with($userPurchase);
    //         }
    //     });
    // }

    public function updatePurchasedEquipmentByUser($id, $equipment)
    {
    	return DB::transaction(function () use ($id, $equipment)  
       {
            $userPurchase             = $this->getUserPurchaseById($id);

            if(isset($userPurchase->Id))
            {
                $userPurchase->purchase_equipment  = $equipment;

                $userPurchase->update();

                return with($userPurchase);
            }
        });
    }

    public function generateAccessRecoveryTokenByUser($id, $token)
    {
    	return DB::transaction(function () use ($id, $token)  
       {
            $userPurchase             = $this->getUserPurchaseById($id);

            if(isset($userPurchase->Id))
            {
                $userPurchase->equipment_recover_verifytoken  = $token;

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

                if(isset($object['partner_code']))
                {
                    $userPurchase->partner_code       = $object['partner_code'];
                }

                $userPurchase->update();

                return with($userPurchase);
            }
        });
    }
}
