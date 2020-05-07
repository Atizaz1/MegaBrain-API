<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    public    $timestamps = false;

    protected $primaryKey = 'id';

    protected $table      = 'news';

    public function getAllNews()
    {
    	return News::all();
    }

    public function getNewsById($id)
    {
    	return News::find($id);
    }

    public function getLastNNews($numberOfNews)
    {
    	return News::orderBy('news_priority', 'asc')->take($numberOfNews)->get();
    }
}
