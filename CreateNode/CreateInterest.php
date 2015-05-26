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


//creates an interest node
//FOR NOW ONLY BY ADMIN...
//CREATE CONSTRAINT ON (interest:Interest) ASSERT interest.interest IS UNIQUE

$getInterestCreator = htmlspecialchars($_GET['userID']);
$getInterest = strtoupper(htmlspecialchars($_GET['interest']));

date_default_timezone_set("Asia/Singapore");
$creationTimestamp = date('Y-m-d H:i:s', time());

$queryString = "MERGE (n:Interest { interest: '" . $getInterest . "' , status:'approved' , createdDate : '" . $creationTimestamp . "', modifiedDate : '" . $creationTimestamp . "', createdBy:'" . $getInterestCreator . "', modifiedBy:'" . $getInterestCreator . "' }) RETURN n";

$client->sendCypherQuery($queryString);
$result = $client->getRows();

header("Content-type: application/json");
$JSON_RETURN = json_encode($result);
echo $JSON_RETURN;
?>