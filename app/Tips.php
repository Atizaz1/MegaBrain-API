<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tips extends Model
{
    public    $timestamps = false;

    protected $primaryKey = 'id';

    protected $table      = 'tips';

    public function getAllTips()
    {
    	return Tips::all();
    }

    public function getTipsById($id)
    {
    	return Tips::find($id);
    }

    public function getLastNTips()
    {
    	return Tips::orderBy('tip_priority', 'asc')->get();
    }
}
