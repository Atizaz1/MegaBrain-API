<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SchoolSubject;

class SchoolSubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getSchoolSubjectByCode($code)
    {
    	if(!isset($code))
    	{
    		return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
    	}

    	$schoolSubject      = new SchoolSubject;

    	$curr_schoolSubject = $schoolSubject->getSchoolSubjectByCode($code);

    	if(isset($curr_schoolSubject->ss_code))
    	{
    		return $curr_schoolSubject;
    	}
    	else
    	{
    		return response()->json(['error'=>'School Subject Not Found'], 404);
    	}
    }

    public function getSchoolSubjectByName($name)
    {
        if(!isset($name))
        {
            return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
        }

        $schoolSubject      = new SchoolSubject;

        $curr_schoolSubject = $schoolSubject->getSchoolSubjectByName($name);

        if(isset($curr_schoolSubject->ss_code))
        {
            return $curr_schoolSubject;
        }
        else
        {
            return response()->json(['error'=>'School Subject Not Found'], 404);
        }
    }

    public function getSchoolSubjectList()
    {
    	$schoolSubject  = new SchoolSubject;    	

    	$school_subjects_list = [];

    	$school_subjects_list = $schoolSubject->getAllSchoolSubject();

    	if(@count($school_subjects_list) > 0)
    	{
    		return $school_subjects_list;
    	}
    	else
    	{
    		return response()->json(['error'=>'School Subjects Not Found'], 404);		
    	}
    }

    public function getAreasListBySubjectName($subjectName)
    {
        if(!isset($subjectName))
        {
            return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
        }

        $subject = new SchoolSubject;

        $curr_subject = $subject->getSchoolSubjectByName($subjectName);

        if(isset($curr_subject->ss_code))
        {
            return response()->json($curr_subject->areas, 200);
        }
        else
        {
            return response()->json(['error'=>'Subject Not Found'], 404);
        }
    }

    public function getAreasListBySubjectCode($subjectCode)
    {
        if(!isset($subjectCode))
        {
            return response()->json(['error'=>'Bad Request. Incomplete or invalid Information'], 400);
        }

        $subject = new SchoolSubject;

        $curr_subject = $subject->getSchoolSubjectByCode($subjectCode);

        if(isset($curr_subject->ss_code))
        {
            return response()->json($curr_subject->areas, 200);
        }
        else
        {
            return response()->json(['error'=>'Subject Not Found'], 404);
        }
    }
}
