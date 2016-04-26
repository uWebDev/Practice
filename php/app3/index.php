<?php

use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client;

define('API_KEY', 'YOUR_ACCESS_KEY'); //TODO

require_once __DIR__ . '/vendor/autoload.php';

$app = new Silex\Application();

$app->get('/', function (Request $request) {

    try {
        $client = new Client(['base_uri' => 'http://api.lbs.yandex.net/']);
        $datageo = $client->request('POST', 'geolocation', [
            'form_params' => [
                'json' => json_encode([
                    'common' => [
                        'version' => '1.0',
                        'api_key' => API_KEY
                    ],
                    'ip' => [
                        'address_v4' => $request->getClientIp()
                    ]
                ])
            ]
        ]);
        $body = json_decode($datageo->getBody());

        return "{lat: {$body->position->latitude}, lon: {$body->position->longitude}}";
    } catch (\Exception $e) {
        return "Error: {$e->getMessage()}";
    }
});

$app->run();