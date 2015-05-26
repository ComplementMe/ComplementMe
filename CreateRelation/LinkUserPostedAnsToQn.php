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
// 2 relationships will be created, userID-[r:replyTo]->Answer-->Question, where r:replyTo is an attribute of the userID/Answer relationship
//aanswer node has to be created by CreateAns webservice before this can be used.
//bob, icantswim, alice, canyouswim

$getUser = htmlspecialchars($_GET['userID']);
$answer = htmlspecialchars($_GET['answer']);
$getReplyTo = htmlspecialchars($_GET['userReplyTo']);
$question = htmlspecialchars($_GET['question']);


date_default_timezone_set("Asia/Singapore");
$creationTimestamp = date('Y-m-d H:i:s', time());



//create relationship from userID to answer, this relation has replyTo attribute.
//the replyTo attribute is the userID of the person who ask the question

$queryString = "Match (a:Person),(b:Answer) WHERE a.userID = '" . $getUser . "' AND b.answer = '" . $answer . "' CREATE UNIQUE (a)-[r:ReplyTo {date:'" . $creationTimestamp . "', replyTo:'" . $getReplyTo . "'}]->(b) RETURN r";
$client->sendCypherQuery($queryString);
$result = $client->getRows();

//create relationship from answer node to question node
//this query is similar to LinkAnsToQuestion

$queryString = "Match (a:Answer),(b:Question) WHERE a.answer = '" . $getAnswer . "' AND b.question = '" . $getQuestion . "' CREATE UNIQUE (a)-[r:AnswerOf {date:'" . $creationTimestamp . "', linkQnA : a.answer + '<->' + b.question }]->(b) RETURN r";
$client->sendCypherQuery($queryString);
$result = $client->getRows();


header("Content-type: application/json");

$JSON_RETURN = json_encode($result);

echo $JSON_RETURN;
?>