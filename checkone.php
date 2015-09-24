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

$DEFAULT_URL = "https://$FIREBASE_APP_ID.firebaseio.com/";

const DEFAULT_PATH = '/chapters';

$firebase = new \Firebase\FirebaseLib($DEFAULT_URL, $FIREBASE_AUTH_KEY);


$gdgurl = $_GET["url"];

$meetup_api_events = "https://api.meetup.com/2/events?&sign=true&photo-host=public&group_urlname=%s&page=200&key=%s";

    echo $timer->sofar()."\r\n";
    $timer2 = new timer();
    $timer2->start();
    $url = sprintf($meetup_api_events,$gdgurl,$MEETUP_API_KEY);
    $meetups = file_get_contents($url);
    var_dump($http_response_header);
    //print_r($meetups);
    //echo $meetups."\r\n";
    // regex necessary because encoding as PHP looses accuacy of long ints
    // credit http://blog.pixelastic.com/2011/10/12/fix-floating-issue-json-decode-php-5-3/
    $gdgmeetups = json_decode(preg_replace('/([^\\\])":([0-9]{10,})(,|})/', '$1":"$2"$3', $meetups));
    echo "\n\n<h2>".$gdg->name.$gdg->meetup->url."</h2>";
    //print_r($gdgmeetups);
    if($gdgmeetups->code){
        echo "<h2>$meetups</h2>";
        echo "<h3>$url</h3>";
    } else {
    //    print_r($gdgmeetups);
        foreach ($gdgmeetups->results as $mu) {

            $eventid = $mu->id;
            echo "eventid: $eventid \r\n";
            // need to check for mutated event id see https://goo.gl/v6XEpG
            $eventurlid = explode("/", $mu->event_url)[5];
            echo "eventURLid: $eventurlid \r\n";
            if ($eventid != $eventurlid){ $eventid = $eventurlid; }
            echo "eventid: $eventid \r\n";
            $data = (array) $mu;
            print_r($data);
            $endpoint = "/events/meetup/".$eventid;
            echo "enpoint: $endpoint \r\n";
            $ans = $firebase->set($endpoint, $data);
            echo "set ans (line57): $ans \r\n";

            $endpoint = urlencode("/chapters/".$gdgurl."/meetup/events/".$eventid);
            $ans = $firebase->set($endpoint, $data);
            echo $ans;
        }
    }
    $endpoint = "/chapters/$gdgurl/updated";
    echo "$endpoint";
    $ans = $firebase->set($endpoint,time());
    echo "overall: ".$timer->sofar()."\r\n";
    $timer2->stop();
    echo "individual: ".$timer2->result()."\r\n";
