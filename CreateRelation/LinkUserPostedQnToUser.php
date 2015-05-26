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


//userID ask question to another user.
//This user (userID) will ask a question to another user (userAsked)
// 2 relationships will be created, userID-->question-->userAsked


$getUser = htmlspecialchars($_GET['userID']);
$question = htmlspecialchars($_GET['question']);
$getUserAsked = htmlspecialchars($_GET['userAsked']);


date_default_timezone_set("Asia/Singapore");
$creationTimestamp = date('Y-m-d H:i:s', time());



//create relationship from userID to question node

$queryString = "Match (a:Person),(b:Question) WHERE a.userID = '" . $getUser . "' AND b.question = '" . $question . "' CREATE UNIQUE (a)-[r:AskTo {date:'" . $creationTimestamp . "', askTo:'" . $getUserAsked . "'}]->(b) RETURN r";
$client->sendCypherQuery($queryString);
$result = $client->getRows();

//create relationship from question node to the person who is being asked the question

$queryString = "Match (a:Question),(b:Person) WHERE a.question = '" . $question . "' AND b.userID = '" . $getUserAsked . "' CREATE UNIQUE (a)-[r:AskedBy {date:'" . $creationTimestamp . "', askedBy:'" . $getUser . "'}]->(b) RETURN r";
$client->sendCypherQuery($queryString);
$result = $client->getRows();


header("Content-type: application/json");

$JSON_RETURN = json_encode($result);

echo $JSON_RETURN;
?>