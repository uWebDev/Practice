<?php

define('DB_CONNECTION', 'mysql:host=localhost;dbname=test');
define('DB_USER', '');
define('DB_PASSWORD', '');

require 'App/Database.php';
require 'App/Model.php';
require 'App/App.php';

$path = '/var/www/text.csv';

try {
    $pdo = new \App\Database();
    $model = new \App\Model($pdo);
    $app = new \App\App($model);
    echo $app->run($path);

}catch(\Exception $e){
    echo "<h1>{$e->getMessage()}</h1>";
}