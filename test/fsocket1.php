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

$fp = fsockopen("ssl://".$service, 443, $errno, $errstr, 30);
if (!$fp) {
    echo "$errstr ($errno)<br />\n";
} else {
    $out = "GET /.json HTTP/1.1\r\n";   
    $out .= "Host: gdgevents.firebaseio.com\r\n";
    $out .= "Content-Type: application/json\r\n";
    $out .= "Connection: Close\r\n\r\n";
    fwrite($fp, $out);
    while (!feof($fp)) {
        echo fgets($fp, 128);
    }
    fclose($fp);
}
?>