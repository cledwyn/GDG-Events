<?php 
require_once("../settings.php");

$endpoint = "sockettest";

$vars ='{"socket":{"data":1234}}'; 

$host = gethostbyaddr($_SERVER['REMOTE_ADDR']); 

# working vars 
$service = sprintf("%s.firebaseio.com",$FIREBASE_APP_ID); 
$service_uri = "/%s.json?auth=%s";
$service_uri = sprintf($service_uri,$endpoint,$FIREBASE_AUTH_KEY);

echo $host."\r\n";
echo $service."\r\n";
echo $service_uri."\r\n";
echo $vars."\r\n";

# compose HTTP request header 
$header .= "Content-Type: application/json\r\n"; 
$header .= "Host: $FIREBASE_APP_ID.firebaseio.com\r\n";
$header .= "Content-Length: ".strlen($vars)."\r\n"; 
$header .= "Connection: close\r\n";
$header .= "\r\n"; 
echo $header; 

$fp = fsockopen("ssl://".$service, 443, $errno, $errstr); 
if (!$fp) { 
   echo "$errstr ($errno)<br/>\n"; 
   echo $fp; 
} else { 
    fputs($fp, "PUT $service_uri  HTTP/1.1\r\n"); 
    fputs($fp, $header.$vars); 
    fwrite($fp, $out); 
    while (!feof($fp)) { 
        echo fgets($fp, 128); 
    } 
    fclose($fp); 
} 

?>