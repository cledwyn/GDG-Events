<?php
echo 'starting<br/>';
  require_once("../settings.php");
  require_once("../lib/firebase.php");
  require_once("../lib/helpers.php");

require_once('../lib/firebaseInterface.php');
require_once('../lib/firebaseLib.php');
require_once('../lib/firebaseStub.php');

$timer = new timer();
$timer->start();

$DEFAULT_URL = "https://$FIREBASE_APP_ID.firebaseio.com/";

$firebase = new \Firebase\FirebaseLib($DEFAULT_URL, $FIREBASE_AUTH_KEY);


$json = file_get_contents('https://gdgevents.firebaseio.com/test/test.json?auth='.$FIREBASE_AUTH_KEY);
$obj = json_decode($json);
print_r($obj);

