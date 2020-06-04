<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public    $timestamps = false;

    public function getMessageById($id)
    {
        return Message::find($id);
    }

    public function getAllMessages()
    {
    	return Message::all();
    }

    public function getMessagesByIncreasingPriority()
    {
    	return Message::orderBy('message_priority', 'asc')->get();
    }

    public function getMessagesByDecreasingPriority()
    {
    	return Message::orderBy('message_priority', 'desc')->get();
    }

    public function getMessageByPriority($priority)
    {
    	return Message::where('message_priority', $priority)->get();
    }

    public function getTopPriorityMessage()
    {
    	return Message::where('message_priority', 1)->first();
    }
}
