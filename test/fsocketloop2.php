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

$host = gethostbyaddr($_SERVER['REMOTE_ADDR']); 


$echo .= $host."\r\n";
$echo .=  $service."\r\n";
$echo .=  $service_uri."\r\n";
$echo .=  $vars."\r\n";

# compose HTTP request header 


for ($x = 0; $x <= 100; $x++) {
        $endpoint = "sockettest";

        $echo .=  "\r\nThe number is: $x <br>\r\n";
        $vars ='{"socket":{"data'.$x.'":1234}}'; 

        firebasePutItemSocket($FIREBASE_APP_ID, $FIREBASE_AUTH_KEY, $endpoint, $vars);

} 


$time_end = microtime_float();
$time = $time_end - $time_start;
echo "Did nothing in $time seconds\n";

echo $echo;


?>