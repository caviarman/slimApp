<?php

namespace App;

require __DIR__ . '/../vendor/autoload.php';


$config = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$repo = new Repository();

$app = new \Slim\App($config);
$container = $app->getContainer();
$container['renderer'] = new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};
//new
$app->get('/', function ($request, $response) {
    return $this->renderer->render($response, 'index.phtml');
});
//new
$app->get('/users', function ($request, $response) use ($repo) {
    $flash = $this->flash->getMessages();

    $params = [
        'flash' => $flash,
        'users' => $repo->all()
    ];
    return $this->renderer->render($response, 'users/show.phtml', $params);
})->setName('users');

//new 
$app->get('/users/new', function ($request, $response) use ($repo) {
    $params = [
        'userData' => [],
        'errors' => []
    ];
    return $this->renderer->render($response, 'users/new.phtml', $params);
});

$app->get('/users/[{id}]', function ($request, $response, $args) {
    $params = ['id' => $args['id']];
    return $this->renderer->render($response, 'users/edit.phtml', $params);
});
//new
$app->post('/users', function ($request, $response) use ($repo) {
    $userData = $request->getParsedBodyParam('user');

    $validator = new Validator();
    $errors = $validator->validate($userData);

    if (count($errors) === 0) {
        $repo->save($userData);
        $this->flash->addMessage('success', 'User has been created');
        return $response->withRedirect($this->router->pathFor('users'));
    }

    $params = [
        'userData' => $userData,
        'errors' => $errors
    ];

    return $this->renderer->render($response, 'users/new.phtml', $params);
});
//new
$app->get('/users/{id}/edit', function ($request, $response, array $args) use ($repo) {
    $user = $repo->find($args['id']);
    $params = [
        'user' => $user,
        'userData' => $user
    ];
    return $this->renderer->render($response, 'users/edit.phtml', $params);
});
//new
$app->patch('/users/{id}', function ($request, $response, array $args) use ($repo) {
    $user = $repo->find($args['id']);
    $userData = $request->getParsedBodyParam('user');

    $validator = new Validator();
    $errors = $validator->validate($userData);

    if (count($errors) === 0) {
        $user['name'] = $userData['name'];
        $user['email'] = $userData['email'];
        $user['city'] = $userData['city'];
        $user['password'] = $userData['password'];
        $repo->save($user);
        $this->flash->addMessage('success', 'User has been updated');
        return $response->withRedirect($this->router->pathFor('users'));
    }
    $params = [
        'user' => $user,
        'userData' => $userData,
        'errors' => $errors
    ];

    return $this->renderer->render($response, 'users/edit.phtml', $params);
});
//new
$app->delete('/users/{id}', function ($request, $response, array $args) use ($repo) {
    $repo->destroy($args['id']);
    $this->flash->addMessage('success', 'User has been deleted');
    return $response->withRedirect($this->router->pathFor('users'));
});
$app->run();
