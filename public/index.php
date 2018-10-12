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


$app->get('/', function ($request, $response) {
    return $this->renderer->render($response, 'index.phtml');
});

$app->get('/users', function ($request, $response) use ($repo) {
    $params = [
        'users' => $repo->all()
    ];
    return $this->renderer->render($response, 'users/show.phtml', $params);
});

$app->get('/users/new', function ($request, $response) {
    return $this->renderer->render($response, 'users/new.phtml');
});

$app->get('/users/[{id}]', function ($request, $response, $args) {
    $params = ['id' => $args['id']];
    return $this->renderer->render($response, 'users/edit.phtml', $params);
});

$app->post('/users', function ($request, $response) use ($repo) {
    $user = $request->getParsedBodyParam('user');
    $repo->save($user);
    return $response->withRedirect('/users');
});

$app->get('/user/{id}/edit', function ($request, $response, array $args) use ($repo) {
    $id = $args['id'];
    $user = $repo->find($id);

    $params = [
        'user' => $user
    ];

    return $this->renderer->render($response, "users/edit.phtml", $params);
});
$app->run();
