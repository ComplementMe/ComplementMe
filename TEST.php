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

$grapheneUrl = parse_url("http://app36675546:wCMi0lmUvgUi0YZCDE4W@app36675546.sb05.stations.graphenedb.com:24789");

//echo var_dump($grapheneUrl);

$client = new Everyman\Neo4j\Client($grapheneUrl['host'], $grapheneUrl['port']);
echo var_dump($client);

$client->getTransport()->setAuth($grapheneUrl['user'], $grapheneUrl['pass']);



//print_r($client->getServerInfo());
?>