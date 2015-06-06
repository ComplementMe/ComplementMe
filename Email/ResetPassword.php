<?php

// using SendGrid's PHP Library - https://github.com/sendgrid/sendgrid-php
require_once('../Config/EmailConnection.php');
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


$getUser = htmlspecialchars($_GET['userID']);

$email = new SendGrid\Email();



//get users email, determine who to send emailTo
$queryString = "MATCH (n:Person { userID: '" . $getUser . "' }) RETURN n.email";
$client->sendCypherQuery($queryString);
$result = $client->getRows();
$JSON_RETURN = json_encode($result);
$returnArr = json_decode($JSON_RETURN, true);

$emailTo = $returnArr["n.email"][0];


//randomize hash for user temp password
//randomPW is the newly generated random password for user to log in

date_default_timezone_set("Asia/Singapore");
$creationTimestamp = date('Y-m-d H:i:s', time());

$randomPW = md5($creationTimestamp . $emailTo);
$randomPW = substr($randomPW, rand(0, 24), 8);

//write the password to the DB, so as to allow user to login
$queryString = "MATCH (n:Person { userID: '" . $getUser . "' }) SET n.password='" . $randomPW . "' return n";
$client->sendCypherQuery($queryString);
$result = $client->getRows();
$JSON_RETURN = json_encode($result);


//send email to the user, containing his new password, which allows him to login.
$email->addTo($emailTo)
        ->setFrom($ComplementMeNoReply)
        ->setSubject("ComplementMe Account Password Reset")
        ->setHtml("Your password has been reset. Please use this temporary password to login, and change your password immediately: " . $randomPW);

$sendgrid->send($email);

header("Content-type: application/json");
echo "{\"status\":\"Email Sent\"}";
?>