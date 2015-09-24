<?php
header('Content-Type: application/json');

    require_once("../settings.php");    
    require_once("../lib/firebase.php");

    require_once('../lib/firebaseInterface.php');
    require_once('../lib/firebaseLib.php');
    require_once('../lib/firebaseStub.php');

    $newmeetup = $_GET["meetup"];
    if(!$newmeetup){
        echo "error";
        return;
    }

    $newmeetup = strtolower($newmeetup);

    //Firebase setup
    $DEFAULT_URL = "https://$FIREBASE_APP_ID.firebaseio.com/";
    $firebase = new \Firebase\FirebaseLib($DEFAULT_URL, $FIREBASE_AUTH_KEY);

    //Meetup API
    $meetup_api = "https://api.meetup.com/%s?&sign=true&photo-host=public&omit=next_event,group_photo,photos&key=%s";
    $url = sprintf($meetup_api,$newmeetup,$MEETUP_API_KEY);
    $meetups = file_get_contents($url);
    // regex necessary because encoding as PHP looses accuacy of long ints
    // credit http://blog.pixelastic.com/2011/10/12/fix-floating-issue-json-decode-php-5-3/
    $gdgmeetup = json_decode(preg_replace('/([^\\\])":([0-9]{10,})(,|})/', '$1":"$2"$3', $meetups));
    //print_r($gdgmeetups);
    $ansArray = array('success' => true);
    if($gdgmeetup->errors){  // Check for meetup API Error
        $ansArray['success'] = false;   
        $ansArray['response'] = $gdgmeetup;
        $ansArray['url']=$url;
    } else {
        $data = array(
                'name' => $gdgmeetup->name,
                'meetup'=>(array) $gdgmeetup
            );
        $endpoint = urlencode("/chapters/".$newmeetup);
        $ans = $firebase->set($endpoint, $data);
        $ansArray['data'] = $data;
    }

    echo json_encode($ansArray);