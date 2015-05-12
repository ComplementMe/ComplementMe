<?php

// https://github.com/jadell/neo4jphp
// in composer.json:
// {
//   "require": {
//     "everyman/neo4jphp": "dev-master"
//   }
// }
// require at the top of the script
require('vendor/autoload.php');

// ...

$grapheneUrl = parse_url(getenv('GRAPHENEDB_URL'));

//this line is the problem with heroku... it cant seem to detect the class.
$client = new Everyman\Neo4j\Client($grapheneUrl['host'], $grapheneUrl['port']);
echo var_dump($client);

$client->getTransport()->setAuth($grapheneUrl['user'], $grapheneUrl['pass']);



//print_r($client->getServerInfo());
?>