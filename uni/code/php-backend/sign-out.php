<?php
  include "data.php";
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
    $sql = $link -> query("SELECT * FROM signin WHERE signinid='$signId'");
    if ($sql -> num_rows > 0) {
      $sqlUpdt = "UPDATE signin SET status='out' WHERE signinid='$signId'";
      if (mysqli_query($link, $sqlUpdt)) {
        unset($_COOKIE['signID']);
        setcookie('signID', null, -1, '/');
        unset($_COOKIE['email']);
        setcookie('email', null, -1, '/');
        session_destroy();
        session_unset();
        outputCreate(1, "done");
      } else {
        outputCreate(2, "sql-error");
      }
    } else {
      outputCreate(2, "user-not-exist");
    }
  } else {
    outputCreate(2, "data-fail");
  }

  function outputCreate($sts, $msg) {
    $output = [];
    array_push($output, [
      "sts" => $sts,
      "msg" => $msg
    ]);
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
