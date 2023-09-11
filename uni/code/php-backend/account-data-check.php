<?php
function check($string, $test) {
  if ($test == "empty") {
    if (preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "", $string)) != "") {
      return true;
    } else {
      return false;
    }
  } else if ($test == "num") {
    if (!is_numeric($string)) {
      return true;
    } else {
      return false;
    }
  } else if ($test == "numIn") {
    if (!preg_match("/[0-9]/", $string) && !preg_match("/[\'^£$%&*()}{@#~?><>,|=_+¬-]/", $string)) {
      return true;
    } else {
      return false;
    }
  } else if ($test == "email") {
    if (filter_var($string, FILTER_VALIDATE_EMAIL)) {
      return true;
    } else {
      return false;
    }
  } else if ($test == "emailSql") {
    global $link;
    $sqlE = $link -> query("SELECT * FROM users WHERE (email='$string' or contactemail='$string') && status!='delete'");
    if ($sqlE -> num_rows == 0) {
      return true;
    } else {
      return false;
    }
  } else if ($test == "tel") {
    if (!preg_match("/[^0-9\s+-\/]/", $string)) {
      return true;
    } else {
      return false;
    }
  } else if ($test == "telSql") {
    global $link;
    $sqlT = $link -> query("SELECT * FROM users WHERE (phonenum='$string' or contactphonenum='$string') && status!='delete'");
    if ($sqlT -> num_rows == 0) {
      return true;
    } else {
      return false;
    }
  } else if ($test == "pass") {
    global $pass;
    if ($string == $pass) {
      return true;
    } else {
      return false;
    }
  } else if ($test == "m") {
    if ($string > 0 && $string < 13) {
      return true;
    } else {
      return false;
    }
  } else if ($test == "d") {
    global $y, $m;
    if ($string == 31) {
      if ($m == 1 || $m == 3 || $m == 5 || $m == 7 || $m == 8 || $m == 10 || $m == 12) {
        return true;
      } else {
        return false;
      }
    } else if ($string == 30) {
      if ($m != 2) {
        return true;
      } else {
        return false;
      }
    } else if ($string == 29) {
      if ($m == 2) {
        if ($y % 4 == 0) {
          return true;
        } else {
          return false;
        }
      } else {
        return true;
      }
    } else if ($string < 29) {
      return true;
    } else {
      return false;
    }
  } else if ($test == "age") {
    global $y, $m, $d;
    $m = sprintf("%02d", $m);
    $d = sprintf("%02d", $d);
    $userDob = $y."-".$m."-".$d;
    $dob = new DateTime($userDob);
    $now = new DateTime();
    $difference = $now->diff($dob);
    $age = $difference->y;
    if ($age >= 18) {
      return true;
    } else {
      return false;
    }
  } else if ($test == "length") {
    $strLength = strlen($string);
    if ($strLength > 3) {
      return true;
    } else {
      return false;
    }
  }
}
?>
