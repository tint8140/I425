<?php


namespace Chatter\Models;

use \Illuminate\Database\Eloquent\Model;

class User extends Model
{

    // The table associated with this model
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false;

    //map the one-to-many relationship
    public function messages()
    {
        return $this->hasMany(Message::class, 'user_id');
    }

    //map the one-to-many relationship
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    //get all users
    public static function getUsers()
    {
        //all() methdod only retrieves the comments.
        $users = self::all();
        return $users;
    }

    //get a user by id
    public static function getUserById($id)
    {
        $user = self::findOrFail($id);
        return $user;
    }

    //get all messages post by a user
    public static function getMessagesByUser($id)
    {
        $messages = self::findOrFail($id)->messages;
        return $messages;
    }

    //get all comments posted by a user
    public static function getCommentByUser($id)
    {
        $comments = self::findOrFail($id)->comments;
        return $comments;
    }

    // Create a new user
    public static function createUser($request)
    {
        // Retrieve parameters from request body
        $params = $request->getParsedBody();

        // Create a new User instance
        $user = new User();

        // Set the user's attributes
        foreach ($params as $field => $value) {

            // Need to hash password
            if ($field == 'password') {
                $value = password_hash($value, PASSWORD_DEFAULT);
            }

            $user->$field = $value;
        }

        // Insert the user into the database
        $user->save();
        return $user;
    }

    // Update a user
    public static function updateUser($request)
    {
        // Retrieve parameters from request body
        $params = $request->getParsedBody();

        //Retrieve the user's id from url and then the user from the database
        $id = $request->getAttribute('id');
        $user = self::findOrFail($id);

        // Update attributes of the professor
        $user->email = $params['email'];
        $user->username = $params['username'];
        $user->password = password_hash($params['password'], PASSWORD_DEFAULT);
        $user->profile_icon = $params['profile_icon'];

        // Update the professor
        $user->save();
        return $user;
    }

    // Delete a user
    public static function deleteUser($id)
    {
        $user = self::findOrFail($id);
        return ($user->delete());
    }

}