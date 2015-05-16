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


//creates a question node

/* $getQuestion = htmlspecialchars($_GET['question']);

  $randomID = md5(time() . rand() . rand());


  $queryString = "MERGE (n:Question { questionID : '" . $randomID . "' , question: '" . $getQuestion . "' }) RETURN n";

  $client->sendCypherQuery($queryString);
  $result = $client->getRows();
  header("Content-type: application/json");
  $JSON_RETURN = json_encode($result);
  echo $JSON_RETURN; */

$getQuestion = htmlspecialchars($_GET['question']);

$queryString = "MERGE (n:Question { question: '" . $getQuestion . "' }) RETURN n";

$client->sendCypherQuery($queryString);
$result = $client->getRows();
header("Content-type: application/json");
$JSON_RETURN = json_encode($result);
echo $JSON_RETURN;
?>