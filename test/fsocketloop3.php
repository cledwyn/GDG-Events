<?php 
require_once("../settings.php");
require_once("../lib/firebase.php");

$echo = "";

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
$time_start = microtime_float();

$vars ='{"socket":{"data":1234}}'; 


require_once('../lib/firebaseInterface.php');
require_once('../lib/firebaseLib.php');
require_once('../lib/firebaseStub.php');

$DEFAULT_URL = "https://$FIREBASE_APP_ID.firebaseio.com/";

$firebase = new \Firebase\FirebaseLib($DEFAULT_URL, $FIREBASE_AUTH_KEY);

for ($x = 0; $x <= 100; $x++) {
      $endpoint = "/sockettest/socket";
      $arrayName = array('data'.$x => 1234 );
      $ans = $firebase->set($endpoint, $arrayName);
      echo $ans;
} 


$time_end = microtime_float();
$time = $time_end - $time_start;
echo "Did nothing in $time seconds\n";

echo $echo;


?>