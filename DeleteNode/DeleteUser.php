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



//deletes a specified user node

$getUserName = htmlspecialchars($_GET['username']);



//delete user with relations

$queryString = "MATCH (n:Person { name: '" . $getUserName . "' })-[r]-() DELETE n,r";
$client->sendCypherQuery($queryString);

$result = $client->getRows();

header("Content-type: application/json");

var_dump($result);
$JSON_RETURN = json_encode($result);

echo $JSON_RETURN;


//delete user with no relations
$queryString = "MATCH (n:Person { name: '" . $getUserName . "' }) DELETE n";
$client->sendCypherQuery($queryString);

$result = $client->getRows();
$JSON_RETURN = json_encode($result);

echo $JSON_RETURN;
?>