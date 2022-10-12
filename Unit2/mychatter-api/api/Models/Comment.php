<?php
//Comment.php

namespace Chatter\Models;

use \Illuminate\Database\Eloquent\Model;
class Comment extends Model
{
    // The table associated with this model
    protected $table = 'comments';
    protected $primaryKey = 'id';

    //Inverse of the one-to-many relationship
    public function message(){
        return $this->belongsTo(Message::class, 'message_id');
    }

    //Inverse of the one-to-many relationship
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function getComments(){
        //all() methdod only retrieves the comments.
        $comments = self::all();
        return $comments;
    }

    public static function getCommentById($id){
        $comment = self::findOrFail($id);
        return $comment;
    }

    public static function getMessageByComment($id){
        $message = self::findOrFail($id)->message;
        return $message;
    }

    public static function getUserByComment($id){
        $user = self::findOrFail($id)->user;
        return $user;
    }
}