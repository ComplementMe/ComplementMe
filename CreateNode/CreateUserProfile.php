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



//add profile
//birthday, my location, sercurity question, security question answer, purpose, age range min, age range max, match location
//modifieddate, modifiedby

$getUserID = htmlspecialchars($_GET['userID']);
$getEmail = htmlspecialchars($_GET['email']);


$getBirthday = htmlspecialchars($_GET['birthday']);
$getLocation = htmlspecialchars($_GET['location']);
$getSecurityQuestion = htmlspecialchars($_GET['securityQn']);
$getSecurityAns = htmlspecialchars($_GET['securityAns']);
$getPurpose = htmlspecialchars($_GET['purpose']);
$getMinAge = htmlspecialchars($_GET['matchMinAge']);
$getMaxAge = htmlspecialchars($_GET['matchMaxAge']);
$getMatchLocation = htmlspecialchars($_GET['matchLocation']);


date_default_timezone_set("Asia/Singapore");
$currentTimestamp = date('Y-m-d H:i:s', time());


$queryString = "MATCH (n:Person { userID: '" . $getUserID . "', email:'" . $getEmail . "' }) SET n.birthday='" . $getBirthday . "' , n.location='" . $getLocation . "' , n.securityQn='" .
        $getSecurityQuestion . "' , n.securityAns='" . $getSecurityAns . "' , n.purpose='" . $getPurpose . "' , n.matchMinAge='" .
        $getMinAge . "' , n.matchMaxAge='" . $getMaxAge . "' , n.matchLocation='" . $getMatchLocation . "', n.modifiedDate='" . $currentTimestamp . "', n.modifiedBy = '" . $getUserID . "'  return n";


$client->sendCypherQuery($queryString);

$result = $client->getRows();

header("Content-type: application/json");

$JSON_RETURN = json_encode($result);

echo $JSON_RETURN;
?>