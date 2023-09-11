<?php
  include "get-lang-shortcut.php";
  $sign = "no";
  if (isset($_SESSION["signID"]) && isset($_SESSION["email"])) {
    $sign = "yes";
    $signId = $_SESSION["signID"];
    $em = $_SESSION["email"];
  } else if (isset($_COOKIE["signID"]) && isset($_COOKIE["email"])) {
    $sign = "yes";
    $signId = $_COOKIE["signID"];
    $em = $_COOKIE["email"];
  } else {
    $sign = "no";
  }
  if ($sign == "yes") {
    $sqlUser = $link->query("SELECT * FROM users WHERE email='$em' && status='active'");
    if ($sqlUser->num_rows > 0) {
      $usr = $sqlUser->fetch_assoc();
      $usrBeId = $usr['beid'];
      $sqlLog = $link->query("SELECT * FROM signin WHERE beid='$usrBeId' && signinid='$signId' && status='in'");
      if ($sqlLog->num_rows <= 0) {
        $usrBeId = "";
      }
    } else {
      $usrBeId = "";
    }
  } else {
    $usrBeId = "";
  }
  $lngShrt = langShortcut($usrBeId);
  setcookie("language", $lngShrt, time() + (900*24*60*60*1000), "/");
  include "langs/".$lngShrt.".php";
?>
