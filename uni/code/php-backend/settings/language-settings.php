<?php
  include "../data.php";
  $lngShrt = $_POST['lang'];
  if (in_array($lngShrt, $default_langs_arr)) {
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
    if ($usrBeId != "") {
      $sqlBookingLangUpdate = "UPDATE booking SET language='$lngShrt' WHERE usrbeid='$usrBeId' and (status='waiting' or status='booked')";
      if (mysqli_query($link, $sqlBookingLangUpdate)) {
        $sqlUserLangUpdate = "UPDATE users SET language='$lngShrt' WHERE beid='$usrBeId'";
        if (mysqli_query($link, $sqlUserLangUpdate)) {
          $sqlUserArchiveLangUpdate = "UPDATE usersarchive SET language='$lngShrt' WHERE beid='$usrBeId'";
          if (mysqli_query($linkBD, $sqlUserArchiveLangUpdate)) {
            setcookie("language", $lngShrt, time() + (900*24*60*60*1000), "/");
            setcookie("languageBanner", "", time() - 3600, "/");
            echo "done";
          } else {
            echo "user-archive-database-sql-faild";
          }
        } else {
          echo "user-database-sql-faild";
        }
      } else {
        echo "user-database-sql-faild";
      }
    } else {
      setcookie("language", $lngShrt, time() + (900*24*60*60*1000), "/");
      setcookie("languageBanner", "", time() - 3600, "/");
      echo "done";
    }
  } else {
    echo "lang-n-exist";
  }
?>
