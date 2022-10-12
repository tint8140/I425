<?php
//Message.php

namespace Chatter\Models;

use \Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    // The table associated with this model
    protected $table = 'messages';
    protected $primaryKey = 'id';

    //map the one-to-many relationship
    public function comments()
    {
        return $this->hasMany(Comment::class, 'message_id');
    }

    //Inverse of the one-to-many relationship
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //get all messages, with pagination, sort and search by query features.
    public static function getMessages($request)
    {

        //get the total number of memmages
        $count = self::count();

        //get query string variables from url
        $params = $request->getQueryParams();

        //Do limit and offset exist?
        $limit = array_key_exists('limit', $params) ? (int)$params['limit'] : 10; // items per page
        $offset = array_key_exists('offset', $params) ? (int)$params['offset'] : 0; // offset of the first item

        //Get search terms
        $term = array_key_exists('q', $params) ? $params['q'] : null;

        if (!is_null($term)) {
            $messages = self::searchMessages($term);
            return $messages;
        } else {
            //Pagination
            $links = self::getLinks($request, $limit, $offset);

            // Sorting.
            $sort_key_array = self::getSortKeys($request);

            $query = Message::with('comments');
            //$query = Message::all();
            $query = $query->skip($offset)->take($limit);  // limit the rows

            // sort the output by one or more columns
            foreach ($sort_key_array as $column => $direction) {
                $query->orderBy($column, $direction);
            }

            $messages = $query->get();

            //construct data for the response
            $results = [
                'totalCount' => $count,
                'limit' => $limit,
                'offset' => $offset,
                'links' => $links,
                'sort' => $sort_key_array,
                'data' => $messages
            ];
            return $results;
        }
    }

    //get a message by message id
    public static function getMessageById($id)
    {
        $message = self::findOrFail($id);
        return $message;
    }

    //get all comments for a message
    public static function getCommentsByMessage($id)
    {
        $comments = self::findOrFail($id)->comments;
        return $comments;
    }

    //get a user for a message
    public static function getUserByMessage($id)
    {
        $user = self::findOrFail($id)->user;
        return $user;
    }

    //create a message
    public static function createMessage($request)
    {
        $params = $request->getParsedBody();

        //Cretea a new message object
        $message = new Message();

        foreach ($params as $field => $value) {
            $message->$field = $value;
        }

        $message->save();
        return $message;
    }

    //update a message
    public static function updateMessage($request)
    {
        $params = $request->getParsedBody();
        $id = $request->getAttribute('id');
        $message = self::findOrFail($id);

        foreach ($params as $field => $value) {
            $message->$field = $value;
        }
        $message->save();
        return $message;
    }


    //delete a message
    public static function deleteMessage($request)
    {
        $id = $request->getAttribute('id');
        $message = self::findOrFail($id);
        return($message->delete());
    }

    // This function returns an array of links for pagination. The array includes links for the current, first, next, and last pages.
    public static function getLinks($request, $limit, $offset)
    {
        $count = self::count();

        // Get request uri and parts
        $uri = $request->getUri();
        $base_url = $uri->getBaseUrl();
        $path = $uri->getPath();

        // Construct links for pagination
        $links = array();
        $links[] = ['rel' => 'self', 'href' => $base_url . $path . "?limit=$limit&offset=$offset"];
        $links[] = ['rel' => 'first', 'href' => $base_url . $path . "?limit=$limit&offset=0"];
        if ($offset - $limit >= 0) {
            $links[] = ['rel' => 'prev', 'href' => $base_url . $path . "?limit=$limit&offset=" . ($offset - $limit)];
        }
        if ($offset + $limit < $count) {
            $links[] = ['rel' => 'next', 'href' => $base_url . $path . "?limit=$limit&offset=" . ($offset + $limit)];
        }
        $links[] = ['rel' => 'last', 'href' => $base_url . $path . "?limit=$limit&offset=" . $limit * (ceil($count / $limit) - 1)];

        return $links;
    }

    /*
     * Sort keys are optionally enclosed in [ ], separated with commas;
     * Sort directions can be optionally appended to each sort key, separated by :.
     * Sort directions can be 'asc' or 'desc' and defaults to 'asc'.
     * Examples: sort=[number:asc,title:desc], sort=[number, title:desc]
     * This function retrieves sorting keys from uri and returns an array.
    */
    public static function getSortKeys($request)
    {
        $sort_key_array = array();

        // Get querystring variables from url
        $params = $request->getQueryParams();

        if (array_key_exists('sort', $params)) {
            $sort = preg_replace('/^\[|\]$|\s+/', '', $params['sort']);  // remove white spaces, [, and ]
            $sort_keys = explode(',', $sort); //get all the key:direction pairs
            foreach ($sort_keys as $sort_key) {
                $direction = 'asc';
                $column = $sort_key;
                if (strpos($sort_key, ':')) {
                    list($column, $direction) = explode(':', $sort_key);
                }
                $sort_key_array[$column] = $direction;
            }
        }

        return $sort_key_array;
    }

    public static function searchMessages($terms)
    {
        if (is_numeric($terms)) {
            $query = self::where('id', "like", "%$terms%");
        } else {
            $query = self::where('body', 'like', "%$terms%")
                ->orWhere('created_at', 'like', "%$terms%")
                ->orWhere('updated_at', 'like', "%$terms%");
        }
        $results = $query->get();
        return $results;
    }
}
