<?php
/**
 * Created by Ran Chang
 * Date: 8/8/2019
 * File: routes.php
 * Decription:
 **/

$app->get('/', function ($request, $response, $args) {
    return $response->write('Welcome to Chatter API!');
});

$app->get('/hello/{name}', function ($request, $response, $args) {
    return $response->write("Hello " . $args['name']);
});


// User routes
$app->group('/users', function () {
    $this->get('', 'UserController:index');
    $this->get('/{id}', 'UserController:view');
    $this->get('/{id}/messages', 'UserController:viewMessages');
    $this->get('/{id}/comments', 'UserController:viewComments');

    $this->post('', 'UserController:create');
    $this->put('/{id}', 'UserController:update');
    $this->patch('/{id}', 'UserController:update');
    $this->delete('/{id}', 'UserController:delete');
});

// Route groups
$app->group('/messages', function () {
    // The Message group

    $this->get('', 'MessageController:index'); // "Class" is registered in DIC
    $this->get('/{id}', 'MessageController:view');
    $this->get('/{id}/comments', 'MessageController:viewComments');
    $this->get('/{id}/user', 'MessageController:viewUser');

    $this->post('', 'MessageController:create');
    $this->put('/{id}', 'MessageController:update');//Postman PUT Boyd with x-www-form-urlencoded to send new information.
    $this->patch('/{id}', 'MessageController:update');//Postman PATCH Boyd with x-www-form-urlencoded to send new information.
    $this->delete('/{id}', 'MessageController:delete');

});

// The Comment group
$app->group('/comments', function () {
    $this->get('', 'CommentController:index'); // "Class" is registered in DIC
    $this->get('/{id}', 'CommentController:view');
    $this->get('/{id}/message', 'CommentController:viewMessage');
    $this->get('/{id}/user', 'CommentController:viewUser');

    //TODO: Post needs CUD
//        $this->post('', 'CommentController:create');
//        $this->put('/{id}', 'CommentController:update');
//        $this->patch('/{id}', 'CommentController:update');
//        $this->delete('/{id}', 'CommentController:delete');

});
$app->run();
//})->add(new MyAuthenticator());
//})->add(new BasicAuthenticator());
//})->add(new BearerAuthenticator());
//})->add(new JWTAuthenticator()); // this was used.
//})->add(new OAuth2Authenticator());  // Needs to test it in a browser, but not in Postman