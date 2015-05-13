<?php

require_once 'vendor/autoload.php';

use Neoxygen\NeoClient\ClientBuilder;

// ...

$url = parse_url("http://app36675546:wCMi0lmUvgUi0YZCDE4W@app36675546.sb05.stations.graphenedb.com:24789");

////this line is the problem with heroku... it cant seem to detect the class.
//$client = new Everyman\Neo4j\Client($grapheneUrl['host'], $grapheneUrl['port']);
//echo var_dump($client);
//
//$client->getTransport()->setAuth($grapheneUrl['user'], $grapheneUrl['pass']);
//
$client = ClientBuilder::create()
        ->addConnection('default', $url['scheme'], $url['host'], $url['port'], true, $url['user'], $url['pass'])
        ->setAutoFormatResponse(true)
        ->build();


print_r($client->getNeoClientVersion());
?>