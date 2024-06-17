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
            $nameQuery = urlencode($searchQuery['name']);
            $urlBlindServer = $_ENV['BLIND_SERVER'] . 'like-search?name=' . $nameQuery
                . '&secret='.$_ENV['CREDENTIALS_TO_JS'] . '&sessionId=' . $sessionId;
            $dataIds = performLikeSearch($urlBlindServer, '');
            if (!empty($dataIds)) {
                $personQ->whereIn('id', $dataIds['data']);
            }
        }

        $people = $personQ->get()->toArray();
        $people = array_map(function ($v) {
            $v['name'] = decrypt($v['name']);
            $v['nik']  = decrypt($v['nik']);
            $v['cecar']= decrypt($v['cecar']);
            return $v;
        }, $people);

        $data = [
            'person'    => $people,
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
        $urlAdd = $_ENV['BLIND_SERVER'].'save-cache?secret='.$_ENV['CREDENTIALS_TO_JS'];
        $blindResp = sendToBlindServer($urlAdd, $respData);

        $response->getBody()->write(
            json_encode([
                'person' => $respData,
                'blindResp' => $blindResp
            ])
        );
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

        $urlAdd = $_ENV['BLIND_SERVER'].'update-cache?secret='.$_ENV['CREDENTIALS_TO_JS'];
        $blindResp = sendToBlindServer($urlAdd, $respData, 'put');

        $response->getBody()->write(
            json_encode([
                'person' => $respData,
                'blindResp' => $blindResp
            ])
        );
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function delete(Request $request, Response $response, $args): Response
    {
        $person = Person::find($args['id']);
        $arrayPerson = $person->toArray();
        $person->delete();
        $urlAdd = $_ENV['BLIND_SERVER'].'delete-cache?secret='.$_ENV['CREDENTIALS_TO_JS'];
        $blindResp = sendToBlindServer($urlAdd, $arrayPerson, 'delete');

        $response->getBody()->write(json_encode(['message' => 'Data deleted']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}