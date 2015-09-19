<?php
  require_once("settings.php");
  require_once("lib/firebase.php");

$json = file_get_contents('https://gdgevents.firebaseio.com/chapters.json');
$obj = json_decode($json);
// print_r($obj);

$meetup_api_events = "https://api.meetup.com/2/events?&sign=true&photo-host=public&group_urlname=%s&page=20&key=%s";
$meetup_api_event = "https://api.meetup.com/2/event/%s?&sign=true&photo-host=public&key=%s";

foreach ($obj as $gdg) {
    echo "\n\n<h2>".$gdg->Name."</h2>";
    echo "<h2>".$gdg->meetupurl."</h2>";
    $url = sprintf($meetup_api_events,$gdg->meetup->url,$MEETUP_API_KEY);
    $meetups = file_get_contents($url);
    echo '<textarea  cols="80" rows="5"  >'.$meetups.'</textarea>';
    $gdgmeetups = json_decode($meetups);
    if($gdgmeetups->code){
        echo "<h2>$meetups</h2>";
        echo "<h3>$url</h3>";
    } else {
    //    print_r($gdgmeetups);
        foreach ($gdgmeetups->results as $mu) {
            $endpoint = "chapters/meetup/$gdg->Name/events/$mu->id";
            $event = file_get_contents(sprintf($meetup_api_event,$mu->id,$MEETUP_API_KEY));
            echo "<h4>$mu->id</h4>";
            echo '<textarea  cols="60" rows="5"  >'.$event.'</textarea>';

/// This is failing for some FB JSON FORMAT issue...  :/

            $fbput = firebasePutItem("test/meetup/events/$mu->id",$event);
            echo '<br /><textarea  cols="40" rows="3"  >'.$fbput.'</textarea>';
            
        }
    }
}

$data = '{"item": "the RAW data string I want to send"}';
