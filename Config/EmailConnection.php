<?php

require_once '../vendor/autoload.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$SENDGRID_USERNAME = "app36675546@heroku.com";

$SENDGRID_PASSWORD = "iubmcvdv0752";

$ComplementMeNoReply = "no-reply@ComplementME.com";


$sendgrid = new SendGrid($SENDGRID_USERNAME, $SENDGRID_PASSWORD);
?>