<?php
  require_once("settings.php");
  require_once("lib/firebase.php");

$data = '{"item": "the RAW data string I want to send"}';
echo firebasePutItem("test/test3",$data);
