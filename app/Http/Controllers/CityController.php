<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;

class CityController extends Controller
{
    public function getCityByCode($code)
    {
    	if(!isset($code))
    	{
    		return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
    	}

    	$city = new City;

    	$city = $city->getCityByCode($code);

    	if(isset($city->city_code))
    	{
            return response()->json($city, 200);
    	}
    	else
    	{
    		return response()->json(['error'=>'City Not Found'], 404);
    	}
    }

    public function getCityList()
    {
    	$city = new City;

        $city_list = [];

        $city_list = $city->getAllCities();

        if(@count($city_list) > 0)
        {
            return response()->json($city_list, 200);
        }
        else
        {
            return response()->json(['error'=>'Cities Not Found'], 404);
        }
    }

    public function getStateByCityName($cityName)
    {
        if(!isset($cityName))
        {
            return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
        }

        $city = new City;

        $city = $city->getCityByName($cityName);

        if(isset($city->city_code))
        {
            return response()->json($city->state, 200);
        }
        else
        {
            return response()->json(['error'=>'City Not Found'], 404);
        }
    }

    public function getStateByCityCode($cityCode)
    {
        if(!isset($cityCode))
        {
            return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
        }

        $city = new City;

        $city = $city->getCityByCode($cityCode);

        if(isset($city->city_code))
        {
            return response()->json($city->state, 200);
        }
        else
        {
            return response()->json(['error'=>'City Not Found'], 404);
        }
    } 
}
