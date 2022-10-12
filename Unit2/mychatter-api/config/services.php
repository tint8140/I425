<?php
/**
 * Created by Ran Chang
 * Date: 8/8/2019
 * File: services.php
 * Decription:
 **/

// Alias to the controllers
use Chatter\Controllers\UserController as UserController;
use Chatter\Controllers\MessageController as MessageController;
use Chatter\Controllers\CommentController as CommentController;

/*
 * The following is the controller and middleware factory. It
 * registers controllers and middleware with the DI container so
 * they can be accessed in other classes. Injecting instances into
 * the DI container so you don't need to pass the entire container or app,
 * rather only the services needed.
 * https://akrabat.com/accessing-services-in-slim-3/#comment-35429
 */
// Register controllers with the DIC. $c is the container itself.
$container['UserController'] = function ($c) {
    return new UserController();
};

$container['MessageController'] = function ($c) {
    return new MessageController();
};

$container['CommentController'] = function ($c) {
    return new CommentController();
};
