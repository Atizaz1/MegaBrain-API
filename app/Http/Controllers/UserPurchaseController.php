<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserPurchase;
use App\Http\Requests\UserPurchaseRequest;
use Carbon\Carbon;

class UserPurchaseController extends Controller
{
	private $userPurchase;

    public function __construct(UserPurchase $userPurchase)
    {
        $this->middleware('auth:api');
        $this->userPurchase = $userPurchase;
    }

    public function getPurchasedSubjectsByUser($email)
    {
    	$userPurchase = $this->userPurchase->getConfirmedPurchaseByEmail($email);
    	if(isset($userPurchase->Id))
    	{
    		$subjectList = explode(";", $userPurchase->ss_to_activate);
    		return response()->json(['subjects' => $subjectList, 'expiryDate'=>$userPurchase->purchase_limit_date], 200);
    	}
    	else
    	{
    		return response()->json(['error' => 'No Purchased Subjects Found'], 404);
    	}
    }

    // public function lockContent($email)
    // {
    // 	$purchase = $this->userPurchase->getUserPurchaseByEmail($email);

    // 	if(isset($purchase->Id))
    // 	{
    // 		$curr_purchase = $this->userPurchase->updateContentDate($email);
    // 		$subjectList = explode(";", $curr_purchase->ss_to_activate);
    // 		return response()->json(['subjects' => $subjectList, 'expiryDate'=>$curr_purchase->purchase_limit_date], 200);
    // 	}
    // 	else
    // 	{
    // 		return response()->json(['error' => 'No Purchase Found'], 404);
    // 	}
    // }

    public function verifyPurchase(UserPurchaseRequest $request)
    {
    	$form_collect = $request->input();

    	$purchase = $this->userPurchase->getUserPurchaseByEmail($form_collect['email']);

    	if(isset($purchase->Id))
    	{
    		if($purchase->purchase_limit_date < Carbon::today()->toDateString())
    		{
    			return response()->json(['error' => 'Activation Code has been used or expired.'], 400);
    		}
    		elseif($purchase->purchase_activation_code === $request->code)
    		{
    			if($purchase->purchase_activated == '0' || $purchase->purchase_activated == 0)
    			{
    					$form_collect['id'] = $purchase['Id'];
    					$form_collect['purchase_activated'] = 1;
    					$tempUserPurchase = $this->userPurchase->updateUserPurchaseById($form_collect);
    					$subjectList = explode(";", $tempUserPurchase->ss_to_activate);
    					return response()->json(['subjects' => $subjectList, 'expiryDate'=>$tempUserPurchase->purchase_limit_date], 200);
    			}
    			else
    			{
    				return response()->json(['error' => 'Subject already Unlocked'], 400);
    			}
    		}
    		else
    		{
    			return response()->json(['error' => 'Invalid Code'], 400);
    		}
    	}
    	else
    	{
    		return response()->json(['error' => 'No Purchase Found'], 404);
    	}

    	// $matchFlag = true;

    	// if(count($userPurchase) > 0)
    	// {
    	// 	foreach ($userPurchase as $purchase) 
    	// 	{
    	// 		if($purchase['purchase_activation_code'] === $request->code)
    	// 		{
    	// 			$matchFlag = true;
    	// 			if($purchase['purchase_activated'] == '0' || $purchase['purchase_activated'] == 0)
    	// 			{
    	// 				$form_collect['id'] = $purchase['Id'];
    	// 				$form_collect['purchase_activated'] = 1;
    	// 				$tempUserPurchase = $this->userPurchase->updateUserPurchaseById($form_collect);
    	// 				return response()->json(['subject' => $tempUserPurchase->purchase_ss_code], 200);
    	// 			}
    	// 			else
    	// 			{
    	// 				return response()->json(['error' => 'Subject already Unlocked'], 400);
    	// 			}
    	// 		}
    	// 		else
    	// 		{
    	// 			$matchFlag = false;
    	// 		}
    	// 	}
    	// 	if(!$matchFlag)
    	// 	{
    	// 		return response()->json(['error' => 'Invalid Code'], 400);
    	// 	}
    	// }
    	// else
    	// {
    	// 	return response()->json(['error' => 'No Purchase Found'], 404);
    	// }
    }
}
