<?php

require_once('DBConnection.php');

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
header("Content-type: application/json");

echo "SETTING CONSTRAINTS <br>";
//set database constraints
$queryString = "CREATE CONSTRAINT ON (userID:Person) ASSERT userID.userID IS UNIQUE";
$client->sendCypherQuery($queryString);
$result = $client->getRows();
$JSON_RETURN = json_encode($result);
echo $JSON_RETURN;


$queryString = "CREATE CONSTRAINT ON (email:Person) ASSERT email.email IS UNIQUE";
$client->sendCypherQuery($queryString);
$result = $client->getRows();
$JSON_RETURN = json_encode($result);
echo $JSON_RETURN;

$queryString = "CREATE CONSTRAINT ON (question:Question) ASSERT question.question IS UNIQUE";
$client->sendCypherQuery($queryString);
$result = $client->getRows();
$JSON_RETURN = json_encode($result);
echo $JSON_RETURN;

$queryString = "CREATE CONSTRAINT ON (answer:Answer) ASSERT answer.answer IS UNIQUE";
$client->sendCypherQuery($queryString);
$result = $client->getRows();
$JSON_RETURN = json_encode($result);
echo $JSON_RETURN;

$queryString = "CREATE CONSTRAINT ON (interest:Interest) ASSERT interest.interest IS UNIQUE";
$client->sendCypherQuery($queryString);
$result = $client->getRows();
$JSON_RETURN = json_encode($result);
echo $JSON_RETURN;
echo "CONSTRAINTS SET<br>";


echo "Clearing DB<br>";

//clear db
$queryString = "MATCH (n) OPTIONAL MATCH (n)-[r]-() DELETE n,r";
$client->sendCypherQuery($queryString);
$result = $client->getRows();
$JSON_RETURN = json_encode($result);
echo $JSON_RETURN;

echo "DB Cleared<br>";

//bootstrap with standard dataset, adding nodes....
$data = array(
    'http://localhost/ComplementMe/CreateNode/CreateUser.php?userID=alice&password=xxx&email=alice@complementme.com&gender=m',
    'http://localhost/ComplementMe/CreateNode/CreateUser.php?userID=bob&password=xxx&email=bob@complementme.com&gender=m',
    'http://localhost/ComplementMe/CreateNode/CreateUser.php?userID=charlie&password=xxx&email=charlie@complementme.com&gender=m',
    'http://localhost/ComplementMe/CreateNode/CreateQn.php?userID=admin&question=can%20you%20swim?',
    'http://localhost/ComplementMe/CreateNode/CreateQn.php?userID=admin&question=can%20you%20dance?',
    'http://localhost/ComplementMe/CreateNode/CreateAns.php?userID=admin&answer=yes',
    'http://localhost/ComplementMe/CreateNode/CreateAns.php?userID=admin&answer=no'
);
$r = multiRequest($data);

echo '<br>CURL Bootstrap Nodes:';
print_r($r);


//these will add relations...
$data = array(
    'http://localhost/ComplementMe/CreateRelation/LinkUserPostedQnToUser.php?userID=alice&question=can%20you%20swim?&userAsked=bob',
    'http://localhost/ComplementMe/CreateRelation/LinkUserPostedQnToUser.php?userID=alice&question=can%20you%20swim?&userAsked=charlie',
    'http://localhost/ComplementMe/CreateRelation/LinkUserPostedAnsToQn.php?userID=bob&answer=no&userReplyTo=alice&question=can%20you%20swim?',
    'http://localhost/ComplementMe/CreateRelation/LinkUserPostedAnsToQn.php?userID=charlie&answer=yes&userReplyTo=alice&question=can%20you%20swim?'
);
$r = multiRequest($data);

echo '<br>CURL Bootstrap Relations:';
print_r($r);

//
//
//
//
//
//
//
//from: http://www.phpied.com/simultaneuos-http-requests-in-php-with-curl/
function multiRequest($data, $options = array()) {

    // array of curl handles
    $curly = array();
    // data to be returned
    $result = array();

    // multi handle
    $mh = curl_multi_init();

    // loop through $data and create curl handles
    // then add them to the multi-handle
    foreach ($data as $id => $d) {

        $curly[$id] = curl_init();

        $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
        curl_setopt($curly[$id], CURLOPT_URL, $url);
        curl_setopt($curly[$id], CURLOPT_HEADER, 0);
        curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);

        // post?
        if (is_array($d)) {
            if (!empty($d['post'])) {
                curl_setopt($curly[$id], CURLOPT_POST, 1);
                curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
            }
        }

        // extra options?
        if (!empty($options)) {
            curl_setopt_array($curly[$id], $options);
        }

        curl_multi_add_handle($mh, $curly[$id]);
    }

    // execute the handles
    $running = null;
    do {
        curl_multi_exec($mh, $running);
    } while ($running > 0);


    // get content and remove handles
    foreach ($curly as $id => $c) {
        $result[$id] = curl_multi_getcontent($c);
        curl_multi_remove_handle($mh, $c);
    }

    // all done
    curl_multi_close($mh);

    return $result;
}

?>