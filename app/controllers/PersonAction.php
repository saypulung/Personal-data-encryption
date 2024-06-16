<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Person;

final class PersonAction
{
    public function datatable(Request $request, Response $response): Response
    {
        $allowedSearch = ["name"];
        $searchQuery = [];
        $QS = $request->getQueryParams();
        foreach ($QS as $key => $val) {
            if (in_array($key, $allowedSearch)) $searchQuery[$key] = $val;
        }
        $personQ = Person::select("*");

        if (isset($QS['nik'])) {
            $nikEnc = encrypt($QS['nik']);
            $personQ->where('nik', $nikEnc);
        }

        if (isset($QS['cecar'])) {
            $nikEnc = encrypt($QS['cecar']);
            $personQ->where('cecar', $nikEnc);
        }

        $subQuery = "";
        if (isset($searchQuery['name'])) {
            $sessionId = session_id();
            // call NodeJS API to define TEMPIDS to perform LIKE search by sessionId

        }

        $data = [
            'message'   => 'Welcome to Slim API',
            'encrypted_message'   => encrypt('Welcome to Slim API'),
            'person'    => $personQ->get()->toArray(),
        ];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function add(Request $request, Response $response): Response
    {
        $payload = $request->getParsedBody();
        $person = Person::create([
            'name'  => encrypt($payload['name']),
            'nik'   => encrypt($payload['nik']),
            'cecar'    => encrypt($payload['cecar'])
        ]);
        $respData = $person->toArray();
        foreach (['name', 'nik', 'cecar'] as $key) {
            $respData[$key] = decrypt($respData[$key]);
        }
        $response->getBody()->write(json_encode($respData));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function update(Request $request, Response $response, $args): Response
    {
        $payload = $request->getParsedBody();
        $person = Person::find($args['id']);
        $person->update([
            'name'  => encrypt($payload['name']),
            'nik'   => encrypt($payload['nik']),
            'cecar'    => encrypt($payload['cecar'])
        ]);
        $respData = $person->toArray();
        foreach (['name', 'nik', 'cecar'] as $key) {
            $respData[$key] = decrypt($respData[$key]);
        }
        $response->getBody()->write(json_encode($respData));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function delete(Request $request, Response $response, $args): Response
    {
        $person = Person::find($args['id']);
        $person->delete();
        $response->getBody()->write(json_encode(['message' => 'Data deleted']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}