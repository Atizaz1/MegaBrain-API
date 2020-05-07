<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\State;

class StateController extends Controller
{
    public function getStateByCode($code)
    {
    	if(!isset($code))
    	{
    		return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
    	}

    	$state = new State;

    	$state = $state->getStateByCode($code);

    	if(isset($state->state_code))
    	{
            return response()->json($state, 200);
    	}
    	else
    	{
    		return response()->json(['error'=>'State Not Found'], 404);
    	}
    }

    public function getStateList()
    {
    	$state = new State;

        $state_list = [];

        $state_list = $state->getAllStates();

        if(@count($state_list) > 0)
        {
            return response()->json($state_list, 200);
        }
        else
        {
            return response()->json(['error'=>'States Not Found'], 404);
        }
    }  

    public function getCitiesListByStateName($stateName)
    {
        if(!isset($stateName))
        {
            return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
        }

        $state = new State;

        $state = $state->getStateByName($stateName);

        if(isset($state->state_code))
        {
            return response()->json($state->cities, 200);
        }
        else
        {
            return response()->json(['error'=>'State Not Found'], 404);
        }
    }

    public function getCitiesListByStateCode($stateCode)
    {
        if(!isset($stateCode))
        {
            return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
        }

        $state = new State;

        $state = $state->getStateByCode($stateCode);

        if(isset($state->state_code))
        {
            return response()->json($state->cities, 200);
        }
        else
        {
            return response()->json(['error'=>'State Not Found'], 404);
        }
    }
}
