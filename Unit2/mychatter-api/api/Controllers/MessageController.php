<?php


namespace Chatter\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Chatter\Models\Message;

class MessageController
{
    //test function
    public function test(Request $request, Response $response, $args)
    {
        return $response->withJson(array("test message" => "This is a test message from /messages/test."));
    }

    //list all messages with pagination, sort, search by query features
    public function index(Request $request, Response $response, array $args)
    {
        $results = Message::getMessages($request);
        $code = array_key_exists("status", $results) ? 500 : 200;

        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    //view a message by id
    public function view(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        $results = Message:: getMessageById($id);
        $code = array_key_exists("status", $results) ? 500 : 200;

        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    //view all comements replying a message
    public function viewComments(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        $results = Message:: getCommentsByMessage($id);
        $code = array_key_exists("status", $results) ? 500 : 200;

        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    //view user information for a message
    public function viewUser(Request $request, Response $response, array $args)
    {
        $id = $args['id'];
        $results = Message:: getUserByMessage($id);
        $code = array_key_exists("status", $results) ? 500 : 200;

        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    // Create a message
    public function create(Request $request, Response $response, array $args)
    {
        // Insert a new student
        $message = Message::createMessage($request);
        if ($message->id) {
            $results = [
                'status' => 'Message created',
                'message_uri' => '/messages/' . $message->id,
                'data' => $message
            ];
            $code = 201;
        } else {
            $code = 500;
        }

        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    // Update a message
    public function update(Request $request, Response $response, array $args)
    {
        // Insert a new student
        $message = Message::updateMessage($request);
        if ($message->id) {
            $results = [
                'status' => 'Message updated',
                'message_uri' => '/messages/' . $message->id,
                'data' => $message
            ];
            $code = 200;
        } else {
            $code = 500;
        }

        return $response->withJson($results, $code, JSON_PRETTY_PRINT);
    }

    // Delete a message
    public function delete(Request $request, Response $response, array $args)
    {
        $id = $request->getAttribute('id');
        Message::deleteMessage($request);
        if (Message::find($id)->exists) {
            return $response->withStatus(500);

        } else {
            $results = [
                'status' => "Message '/messages/$id' has been deleted."
            ];
            return $response->withJson($results, 200, JSON_PRETTY_PRINT);
        }
    }
}