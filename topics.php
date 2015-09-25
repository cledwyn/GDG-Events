<?php
require_once("settings.php");
require_once("lib/firebase.php");
require_once("lib/helpers.php");
require_once('lib/firebaseInterface.php');
require_once('lib/firebaseLib.php');
require_once('lib/firebaseStub.php');

$timer = new timer();
$timer->start();
$FIREBASE_BASE_URL = "https://$FIREBASE_APP_ID.firebaseio.com/";
$firebase = new \Firebase\FirebaseLib($FIREBASE_BASE_URL, $FIREBASE_AUTH_KEY);

$eventid = $_GET["event"];
if(!$eventid){return;}

$path = "/events/meetup/$eventid/topics";

if($_POST["topics"]){
    $data = explode(",", $_POST["topics"]);
    foreach ($data as $key => $value) {$data[$key] = trim($value);   }
    echo $firebase->set($path,$data);
}

$json = file_get_contents($FIREBASE_BASE_URL.$path.".json");
$obj = json_decode($json);
//print_r($obj);
$topics_str = implode(", ", $obj);

$eventjson = file_get_contents($FIREBASE_BASE_URL."/events/meetup/$eventid.json");
$event = json_decode($eventjson);
// 

?>
<form method="POST">
<input type="text" id="topics" name="topics" value="<?php echo $topics_str ?> "/><input type="submit" value="save">
    </form>
    <a href="http://meetup.com/<?php echo $event->group->urlname ?>/events/<?php echo $eventid ?>">return to event</a>