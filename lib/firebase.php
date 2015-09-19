<?php

function firebasePutItem($endpoint,$body){
    global $FIREBASE_APP_ID, $FIREBASE_AUTH_KEY;

//    $body = '{"item": "the RAW data string I want to send"}';

    // Start curl
    $ch = curl_init();
    // URL for curl
    $url = "https://%s.firebaseio.com/%s.json?auth=%s";
    $url = sprintf($url,$FIREBASE_APP_ID,$endpoint,$FIREBASE_AUTH_KEY);

    // Clean up string
    $putString = stripslashes($body);
    // Put string into a temporary file
    $putData = tmpfile();
    // Write the string to the temporary file
    fwrite($putData, $putString);
    // Move back to the beginning of the file
    fseek($putData, 0);

    // Headers
    $headers = array(
        'Accept: application/json',
        'Content-Type: application/json',
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    // Binary transfer i.e. --data-BINARY
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    // Using a PUT method i.e. -XPUT
    curl_setopt($ch, CURLOPT_PUT, true);
    // Instead of POST fields use these settings
    curl_setopt($ch, CURLOPT_INFILE, $putData);
    curl_setopt($ch, CURLOPT_INFILESIZE, strlen($putString));

    $output = curl_exec($ch);
    //echo $output;

    // Close the file
    fclose($putData);
    // Stop curl
    curl_close($ch);

    return $output;
}