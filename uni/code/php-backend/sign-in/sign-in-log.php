<?php
  include "../../random-hash-maker.php";
  function signInLog($beid) {
    global $link;
    $sql = $link->query("SELECT * FROM users WHERE beid='$beid'");
    if ($sql->num_rows > 0){
      $row = $sql->fetch_assoc();
      $idCheck = false;
      while (!$idCheck) {
        $signId = randomHash(21);
        $sqlCH = $link -> query("SELECT * FROM signin WHERE signinid='$signId'");
        if ($sqlCH -> num_rows == 0) {
          $idCheck = true;
        } else {
          $idCheck = false;
        }
      }
      $date = date("Y-m-d H:i:s");
      $dateY = date("Y");
      $dateM = date("m");
      $dateD = date("d");
      $email = $row['email'];
      $sql = "INSERT INTO signin (beid, signinid, status, date, y, m, d) VALUES ('$beid' ,'$signId', 'in','$date', '$dateY', '$dateM', '$dateD')";
      if (mysqli_query($link, $sql)) {
        setcookie("signID", $signId, time() + (900*24*60*60*1000), "/");
        setcookie("email", $email, time() + (900*24*60*60*1000), "/");
        $_SESSION['signID'] = $signId;
        $_SESSION['email'] = $email;
        done();
      } else {
        error("Sign In log error: Faild to save to the database");
      }
    } else {
      error("Sign In log error: Faild to load data from the database");
    }
  }
?>
