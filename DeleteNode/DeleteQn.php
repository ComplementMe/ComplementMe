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


//deletes a specified question node

$getQuestion = htmlspecialchars($_GET['question']);



//delete node with relations

$queryString = "MATCH (n:Question { question: '" . $getQuestion . "' })-[r]-() DELETE n,r";
$client->sendCypherQuery($queryString);

$result = $client->getRows();

header("Content-type: application/json");

$JSON_RETURN1 = json_encode($result);


//delete node with no relations
$queryString = "MATCH (n:Question { question: '" . $getQuestion . "' }) DELETE n";
$client->sendCypherQuery($queryString);

$result = $client->getRows();

header("Content-type: application/json");

$JSON_RETURN2 = json_encode($result);

if (($JSON_RETURN1 == $JSON_RETURN2)) {

    echo "{\"Status\":\"Question Deleted\"}";
} else {
    echo "{\"Status\":\"FAILURE\"}";
}
?>