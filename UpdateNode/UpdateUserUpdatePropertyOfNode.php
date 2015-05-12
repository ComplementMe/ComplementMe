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

//updates property of a person node
//takes in username, the key of the property and the value of the property to update

$getUserName = htmlspecialchars($_GET['username']);
$getKey = htmlspecialchars($_GET['key']);
$getValue = htmlspecialchars($_GET['value']);

//MATCH (n:Person { name: 'abc' }) SET n.key='value' return n

$queryString = "MATCH (n:Person { name: '" . $getUserName . "' }) SET n." . $getKey . "='" . $getValue . "' return n";


$query = new Everyman\Neo4j\Cypher\Query($client, $queryString);
$result = $query->getResultSet();
?>