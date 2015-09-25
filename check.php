<?php
echo 'starting<br/>';
  require_once("settings.php");
  require_once("lib/firebase.php");
  require_once("lib/helpers.php");

require_once('lib/firebaseInterface.php');
require_once('lib/firebaseLib.php');
require_once('lib/firebaseStub.php');

$timer = new timer();
$timer->start();

$FIREBASE_BASE_URL = "https://$FIREBASE_APP_ID.firebaseio.com/";

const DEFAULT_PATH = '/chapters';

$firebase = new \Firebase\FirebaseLib($FIREBASE_BASE_URL, $FIREBASE_AUTH_KEY);


$json = file_get_contents('https://gdgevents.firebaseio.com/chapters.json?orderBy="updated"&limitToFirst=10');
$obj = json_decode($json);
// print_r($obj);

$meetup_api_events = "https://api.meetup.com/2/events?&sign=true&photo-host=public&group_id=%s&page=200&time=-14d,2m&status=upcoming,past&key=%s";

foreach ($obj as $gdgname => $gdg) {
    echo "\n\n<h2>".$gdg->name.$gdg->meetup->url."</h2>";
    echo $timer->sofar()."\r\n";
    $timer2 = new timer();
    $timer2->start();
    $url = sprintf($meetup_api_events,$gdg->meetup->id,$MEETUP_API_KEY);
    $meetups = file_get_contents($url);
//    var_dump($http_response_header);
    foreach ($http_response_header as $s) {
        if (substr($s, 0,strlen("X-RateLimit-")) == "X-RateLimit-"){
            echo $s."\r\n";
        }
    }
    //echo $meetups;
    // regex necessary because encoding as PHP looses accuacy of long ints
    // credit http://blog.pixelastic.com/2011/10/12/fix-floating-issue-json-decode-php-5-3/
    $gdgmeetups = json_decode(preg_replace('/([^\\\])":([0-9]{10,})(,|})/', '$1":"$2"$3', $meetups));
    //print_r($gdgmeetups);
    if($gdgmeetups->code){
        echo "<h2>$meetups</h2>";
        echo "<h3>$url</h3>";
    } else {
    //    print_r($gdgmeetups);
        foreach ($gdgmeetups->results as $mu) {
            // need to check for mutated event id see https://goo.gl/v6XEpG
            $eventid = $mu->id;
            $eventurlid = explode("/", $mu->event_url)[5];
            //echo "eventURLid: $eventurlid \r\n";
            if ($eventid != $eventurlid){ $eventid = $eventurlid; }
            //echo "eventid: $eventid \r\n";
            //
            $data = (array) $mu; //print_r($data);            
            $endpoint = "/events/meetup/".$eventid;         
            // get existing element so we do not destroy additional data elements that may have been added
            $orignal_object = array();
            $meetup_firebase_url = $FIREBASE_BASE_URL."/events/meetup/".$eventid.".json";
            $meetup_firebase = file_get_contents($meetup_firebase_url);
            try {
                $orignal_object = json_decode(preg_replace('/([^\\\])":([0-9]{10,})(,|})/', '$1":"$2"$3',$meetup_firebase));
                $orignal_object = (array) $orignal_object;
                //echo "------------original FB Object--------------\r\n";                    
                //var_dump($orignal_object);
            } catch (Exception $e) { echo $e->getMessage();  }
            //echo "------------original--------------\r\n";
            //print_r($orignal_object);
            $data = upsertArray($orignal_object,$data);
            // persist the data to Firebase
            $ans = $firebase->set($endpoint, $data);
            // per https://www.firebase.com/docs/rest/guide/structuring-data.html 
            // it is better to double reference and try and normalize the data
            $endpoint = urlencode("/chapters/".$gdgname."/meetup/events/".$eventid);
            $ans = $firebase->set($endpoint, true);
            //echo $ans;
        }
    }
    $endpoint = "/chapters/$gdgname/updated";
    echo "$endpoint"."\r\n";
    $ans = $firebase->set($endpoint,time());
    $timer2->stop();
    echo "individual: ".$timer2->result()."\r\n";
    echo "overall: ".$timer->sofar()."\r\n";
}
