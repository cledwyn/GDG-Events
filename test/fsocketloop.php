<?php 
require_once("../settings.php");

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
$time_start = microtime_float();


$vars ='{"socket":{"data":1234}}'; 

$host = gethostbyaddr($_SERVER['REMOTE_ADDR']); 

# working vars 
$service = sprintf("%s.firebaseio.com",$FIREBASE_APP_ID); 
$service_uri = "/%s.json?auth=%s";

$echo .=  $host."\r\n";
$echo .=  $service."\r\n";
$echo .=  $service_uri."\r\n";
$echo .=  $vars."\r\n";

# compose HTTP request header 


for ($x = 0; $x <= 20; $x++) {
    $fp = pfsockopen("ssl://".$service, 443, $errno, $errstr); 
    if (!$fp) { 
       $echo .=  "$errstr ($errno)<br/>\n"; 
       $echo .=  $fp; 
    } else { 
        $echo .= "\r\nThe number is: $x <br>\r\n";
        $vars ='{"socket":{"data'.$x.'":1234}}'; 

        $header = "Content-Type: application/json\r\n"; 
        $header .= "Host: $FIREBASE_APP_ID.firebaseio.com\r\n";
        $header .= "Content-Length: ".strlen($vars)."\r\n"; 
        $header .= "Connection: close\r\n";
        $header .= "\r\n"; 
//        echo $header; 

        $endpoint = "sockettest";
        $service_endpoint = sprintf($service_uri,$endpoint,$FIREBASE_AUTH_KEY);


        fputs($fp, "PUT $service_endpoint  HTTP/1.0\r\n"); 
        $echo .=  $vars."\r\n";
        fputs($fp, $header.$vars); 
        fwrite($fp, $out); 
        while (!feof($fp)) { 
            $echo .=  fgets($fp, 128); 
        } 
       fclose($fp); 
    } 
} 


$time_end = microtime_float();
$time = $time_end - $time_start;
echo "Did nothing in $time seconds\n";

echo $echo;



?>