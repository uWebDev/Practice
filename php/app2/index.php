<?php


require_once 'vendor/autoload.php';


$container = [
    'vk' => [
        'authorize' => [
            'id' => '',
            'secret' => '',
            'redirect' => 'http://localhost/auth/vk/',
            'scope' => 'sex', //notify,email,bdate,sex,photo_max_orig
        ],
        'version' => '5.44',
    ]
];

try {
    $res = new \OAuth\OAuth('Vk', $container['vk']);
    $res->authenticate();
    echo $res->getId() . ' / ' . $res->getNameProvider();
} catch(\Exception $e){
    echo $e->getMessage();
}
