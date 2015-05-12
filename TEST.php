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

echo $grapheneUrl;

$client = new Everyman\Neo4j\Client($grapheneUrl['host'], $grapheneUrl['port']);
$client->getTransport()->setAuth($grapheneUrl['user'], $grapheneUrl['pass']);

print_r($client->getServerInfo());
?>