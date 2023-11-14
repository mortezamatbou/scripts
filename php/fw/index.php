<?php

function pre_print($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

require "./vendor/autoload.php";

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$response = new Response();

echo '<pre>';
print_r($request->get('aa'));
exit;

switch ($request->getPathInfo()) {
    case '/':
        $response->setContent('This is the website home');
        break;
    default:
        $response->setContent('Not found !');
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
}
