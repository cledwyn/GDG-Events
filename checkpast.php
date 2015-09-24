<?php
  require_once("settings.php");
  require_once("lib/firebase.php");

require_once('lib/firebaseInterface.php');
require_once('lib/firebaseLib.php');
require_once('lib/firebaseStub.php');

$DEFAULT_URL = "https://$FIREBASE_APP_ID.firebaseio.com/";

const DEFAULT_PATH = '/chapters';

$firebase = new \Firebase\FirebaseLib($DEFAULT_URL, $FIREBASE_AUTH_KEY);


$json = file_get_contents('https://gdgevents.firebaseio.com/chapters.json');
$obj = json_decode($json);
// print_r($obj);

$meetup_api_events = "https://api.meetup.com/2/events?&sign=true&photo-host=public&group_id=%s&status=past&page=3&desc=true&key=%s";
$meetup_api_event = "https://api.meetup.com/2/event/%s?&sign=true&photo-host=public&key=%s";

foreach ($obj as $gdgname => $gdg) {
    echo "\n\n<h2>".$gdg->name."</h2>";
    echo "<h2>".$gdg->meetup->url."</h2>";
    $url = sprintf($meetup_api_events,$gdg->meetup->id,$MEETUP_API_KEY);
    $meetups = file_get_contents($url);
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
            $data = (array) $mu;
            //print_r($data);
            $endpoint = "/events/meetup/".$gdg->meetup->url."/".$mu->id;
            $ans = $firebase->set($endpoint, $data);
            $endpoint = urlencode("/chapters/".$gdgname."/meetup/events/".$mu->id);
            $ans = $firebase->set($endpoint, $data);
            //echo $ans;
        }
    }
}
