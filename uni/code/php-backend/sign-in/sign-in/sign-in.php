<?php
  include "../../data.php";
  include "../../password-edit.php";
  include "../../get-frontend-id.php";
  include "../sign-in-log.php";
  $output = [];
  $user = $link->escape_string($_POST['user']);
  if ($user != "") {
    if ($_POST['pass'] != "") {
      $isEmail = false;
      $isPhone = false;
      $unknown = false;
      if (!filter_var($user, FILTER_VALIDATE_EMAIL)) {
        $user = str_replace("plus", "+", $user);
        if (!preg_match("/[^0-9\s+-\/]/", $user)) {
          $isPhone = true;
        } else {
          $isPhone = false;
        }
      } else {
        $isEmail = true;
      }
      if ($isEmail) {
        $sql = $link -> query("SELECT * FROM users WHERE email='$user' && status='active'");
      } else if ($isPhone) {
        $sql = $link -> query("SELECT * FROM users WHERE phonenum='$user' && status='active'");
      } else {
        $unknown = true;
      }
      if (!$unknown) {
        if ($sql->num_rows > 0) {
          $row = $sql->fetch_assoc();
          if (password_verify(passEdit($_POST['pass']), passEdit($row['password']))) {
            signInLog($row['beid']);
          } else {
            error("Wrong account ID or password");
          }
        } else {
          error("Wrong account ID or password");
        }
      } else {
        error("Account ID is not in valid form");
      }
    } else {
      error("Password is empty");
    }
  } else {
    error("Account ID input is empty");
  }

  function error($msg) {
    global $output;
    array_push($output, [
      "type" => "error",
      "error" => $msg
    ]);
    returnOutput();
  }

  function done() {
    global $output;
    array_push($output, [
      "type" => "done"
    ]);
    returnOutput();
  }

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
