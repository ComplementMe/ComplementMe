<?php

require_once('../Config/DBConnection.php');

//enable cross origin resources sharing (CORS)
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
}


//removes a property key value pair from a node
//takes in 2 arguments: username, property key of property to remove

$getUserName = htmlspecialchars($_GET['username']);
$getKey = htmlspecialchars($_GET['key']);


$queryString = "MATCH (n:Person { name: '" . $getUserName . "' }) SET n." . $getKey . "= NULL return n";


$query = new Everyman\Neo4j\Cypher\Query($client, $queryString);
$result = $query->getResultSet();
?>