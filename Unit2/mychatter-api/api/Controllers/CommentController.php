<?php


namespace Chatter\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Chatter\Models\Comment;

class CommentController
{
    //list all comments in database
    public function index(Request $request, Response $response, array $args){
        $results = Comment::getComments();
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    //get single comment by id
    public function view(Request $request, Response $response, array $args){
        $id = $args['id'];
        $results = Comment::getCommentById($id);
        $code = array_key_exists('status', $results) ? 500 : 200;
        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    //get single message by the comment
    public function viewMessage(Request $request, Response $response, array $args){
        $id = $args['id'];
        $results = Comment::getMessageByComment($id);
        $code = array_key_exists('status', $results) ? 500 : 200;

        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    //get the user information by the comment
    public function viewUser(Request $request, Response $response, array $args){
        $id = $args['id'];
        $results = Comment::getUserByComment($id);
        $code = array_key_exists('status', $results) ? 500 : 200;

        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }
}