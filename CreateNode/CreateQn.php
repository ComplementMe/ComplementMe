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



//REMEMBER TO CHANGE APPROVED BACK TO PENDING APPROVAL after sprint 1

$getQnCreator = htmlspecialchars($_GET['userID']);
$getQuestion = htmlspecialchars($_GET['question']);


date_default_timezone_set("Asia/Singapore");
$creationTimestamp = date('Y-m-d H:i:s', time());

$queryString = "MERGE (n:Question { question: '" . $getQuestion . "' , status:'approved' , createdDate : '" . $creationTimestamp . "', modifiedDate : '" . $creationTimestamp . "', createdBy:'" . $getQnCreator . "', modifiedBy:'" . $getQnCreator . "' }) RETURN n";

$client->sendCypherQuery($queryString);
$result = $client->getRows();
header("Content-type: application/json");
$JSON_RETURN = json_encode($result);
echo $JSON_RETURN;
?>