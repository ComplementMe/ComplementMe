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


//returns a list of all the people this user follows


$getUserName = htmlspecialchars($_GET['userID']);

//MATCH (dawn { name:'dawn' })-->(Person) RETURN Person.name
$queryString = "MATCH (Person { userID:'" . $getUserName . "' })-[r:Follows]->(n) RETURN n.userID";


$client->sendCypherQuery($queryString);

$result = $client->getRows();


$JSON_RETURN = json_encode($result);

header("Content-type: application/json");
echo $JSON_RETURN;
?>

