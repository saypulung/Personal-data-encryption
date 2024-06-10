<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Person;

final class HomeAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        $data = [
            'message'   => 'Welcome to Slim API',
            'encrypted_message'   => encrypt('Welcome to Slim API'),
            'person'    => Person::all(),
        ];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}