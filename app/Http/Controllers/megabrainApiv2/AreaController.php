<?php

namespace App\Http\Controllers\megabrainApiv2;

use Illuminate\Http\Request;
use App\Area;
use App\Http\Controllers\Controller;

class AreaController extends Controller
{
    public function getAreaByCode($code)
    {
    	if(!isset($code))
    	{
    		return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
    	}

    	$area      = new Area;

    	$curr_area = $area->getAreaByCode($code);

    	if(isset($curr_area->area_code))
    	{
    		return $curr_area;
    	}
    	else
    	{
    		return response()->json(['error'=>'Area Not Found'], 404);
    	}
    }

    public function getAreaList()
    {
    	$area = new Area;

    	$area_list = [];

    	$area_list = $area->getAllAreas();

    	if(@count($area_list) > 0)
    	{
    		return $area_list;
    	}
    	else
    	{
    		return response()->json(['error'=>'Areas Not Found'], 404);		
    	}
    }

    public function getAreaByName($name)
    {
    	if(!isset($name))
    	{
    		return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
    	}

    	$area      = new Area;

    	$curr_area = $area->getAreaByName($name);

    	if(isset($curr_area->area_code))
    	{
    		return $curr_area;
    	}
    	else
    	{
    		return response()->json(['error'=>'Area Not Found'], 404);
    	}
    }

    public function getSubjectByAreaName($areaName)
    {
    	if(!isset($areaName))
        {
            return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
        }

        $area = new Area;

        $curr_area = $area->getAreaByName($areaName);

        if(isset($curr_area->area_code))
        {
            return response()->json($curr_area->subject, 200);
        }
        else
        {
            return response()->json(['error'=>'Area Not Found'], 404);
        }
    }

    public function getSubjectByAreaCode($areaCode)
    {
    	if(!isset($areaCode))
        {
            return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
        }

        $area = new Area;

        $curr_area = $area->getAreaByCode($areaCode);

        if(isset($curr_area->area_code))
        {
            return response()->json($curr_area->subject, 200);
        }
        else
        {
            return response()->json(['error'=>'Area Not Found'], 404);
        }
    }	
}
