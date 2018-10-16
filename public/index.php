<?php

namespace App;

require __DIR__ . '/../vendor/autoload.php';

$opt = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC);
try {
    $pdo = new \PDO('pgsql:host=localhost;port=5432;dbname=slimapp', 'postgres', 'Zydfhm2013', $opt);
} catch (PDOException $e) {
    $response->write('Невозможно установить соединение с БД');
}

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

$app->get('/db', function ($request, $response) use ($pdo) {
    
    $query = 'SELECT VERSION() AS version';
    $ver = $pdo->query($query);
    $version = $ver->fetch();
    $response->write("{$version['version']}");
    return $response;
});

$app->get('/', function ($request, $response) {
    return $this->renderer->render($response, 'index.phtml');
});

$app->get('/users', function ($request, $response) use ($pdo) {
    $flash = $this->flash->getMessages();
    $users = $pdo->query('SELECT * FROM users')->fetchAll();

    $params = [
        'flash' => $flash,
        'users' => $users
    ];
    return $this->renderer->render($response, 'users/show.phtml', $params);
})->setName('users');

$app->get('/users/new', function ($request, $response) {
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

$app->post('/users', function ($request, $response) use ($pdo) {
    $userData = $request->getParsedBodyParam('user');

    $validator = new Validator();
    $errors = $validator->validate($userData);

    if (count($errors) === 0) {
        $pdo->exec("INSERT INTO users VALUES (DEFAULT, '{$userData['name']}', 
        '{$userData['email']}', '{$userData['password']}', '{$userData['city']}')");
        $this->flash->addMessage('success', 'User has been created');
        return $response->withRedirect($this->router->pathFor('users'));
    }
    $params = [
        'userData' => $userData,
        'errors' => $errors
    ];
    return $this->renderer->render($response, 'users/new.phtml', $params);
});

$app->get('/users/{id}/edit', function ($request, $response, array $args) use ($pdo) {
    $id = (string)$args['id'];
    $user = $pdo->query("SELECT * FROM users WHERE id = '{$id}'")->fetchAll()[0];
    $params = [
        'user' => $user,
        'userData' => $user
    ];
    return $this->renderer->render($response, 'users/edit.phtml', $params);
});

$app->patch('/users/{id}', function ($request, $response, array $args) use ($pdo) {
    $id = (string)$args['id'];
    $user = $pdo->query("SELECT * FROM users WHERE id = '{$id}'")->fetchAll()[0];
    $userData = $request->getParsedBodyParam('user');
    $validator = new Validator();
    $errors = $validator->validate($userData);

    if (count($errors) === 0) {
        $pdo->exec("UPDATE users SET name = '{$userData['name']}', email = '{$userData['email']}', 
        password = '{$userData['password']}', city = '{$userData['city']}' WHERE id = '{$id}'");
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

$app->delete('/users/{id}', function ($request, $response, array $args) use ($pdo) {
    $pdo->exec("DELETE FROM users WHERE id = {$args['id']}");
    $this->flash->addMessage('success', 'User has been deleted');
    return $response->withRedirect($this->router->pathFor('users'));
});

$app->run();
