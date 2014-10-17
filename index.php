<?php
require 'vendor/autoload.php';

const CLIENT_ID     = 'YOUR_CLIENT_ID';
const CLIENT_SECRET = 'YOUR_CLIENT_SECRET';
const REDIRECT_URI           = 'YOUR_CALLBACK_URI';
const AUTHORIZATION_ENDPOINT = 'https://api.gyazo.com/oauth/authorize';
const TOKEN_ENDPOINT         = 'https://gyazo.com/oauth/token';

$client = new OAuth2\Client(CLIENT_ID, CLIENT_SECRET);

$app = new \Slim\Slim();
session_start();
$app->config([
    'debug' => true,
    'templates.path' => './templates'
]);
$app->get('/', function () use ($app, $client) {
    $app->render('index.php', ['auth_url' => $client->getAuthenticationUrl(AUTHORIZATION_ENDPOINT, REDIRECT_URI)]);
});

$app->get('/callback', function () use ($app, $client) {
    $params = [
        'code' => $_GET['code'], 
        'redirect_uri' => REDIRECT_URI
    ];
    $response = $client->getAccessToken(TOKEN_ENDPOINT, 'authorization_code', $params);
    $_SESSION['access_token'] = $response['result']['access_token'];
    $app->redirect('/');
});

$app->post('/post', function () use ($app, $client) {
    if(is_uploaded_file($_FILES["photo"]["tmp_name"])){
        $cfile = new CURLFile($_FILES['photo']['tmp_name']);
        $response = $client->fetch('https://upload.gyazo.com/api/upload', 
        ['imagedata' => $cfile, 'access_token' => $_SESSION['access_token']]
        , 'POST',['Content-Type'=>'multipart/form-data'],1);
    if(isset($response['result']['thumb_url'])){
        $_SESSION['thumb_url'] = $response['result']['thumb_url'];
        $_SESSION['permalink_url'] = $response['result']['permalink_url'];
        $app->redirect('/preview');
    }else{
        $_SESSION['error'] = $response;
        $app->redirect('/error');
    }
    }
});
$app->get('/preview', function () use ($app) {
    $app->render('preview.php');
});
$app->get('/error', function () use ($app) {
    $app->render('error.php');
});

$app->run();
