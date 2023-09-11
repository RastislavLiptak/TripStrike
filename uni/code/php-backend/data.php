<?php
  session_start();
  date_default_timezone_set("Europe/Berlin");
  $title = "TripStrike";
  $domain = "http://localhost:8888/projects/Cottages";
  $link = mysqli_connect("localhost", "root", "root", "cottages");
  $linkBD = mysqli_connect("localhost", "root", "root", "backdoor");
  $projectFolder = "/projects/Cottages";

  // language
  $default_langs_arr = ["eng", "cz", "sk"];
  $default_langs_arr_title = ["English", "Čeština", "Slovenčina"];
  // currency
  $default_currency_arr = ["eur"];
  $default_currency_arr_title = ["Euro"];
  $default_currency_arr_symbol = ["€"];

  $youtubeAPIKey = "";

  // output of settings from BackDoor
  include "backdoor-settings.php";
?>
