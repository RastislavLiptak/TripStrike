<?php
  include "../data.php";
  include "../password-edit.php";
  include "../account-data-check.php";
  $output = [];
  if (isset($_SESSION["signID"]) && isset($_SESSION["email"])) {
    $signId = $_SESSION["signID"];
    $em = $_SESSION["email"];
    $sqlUser = $link->query("SELECT * FROM users WHERE email='$em' && status='active'");
    if ($sqlUser->num_rows > 0) {
      $usr = $sqlUser->fetch_assoc();
      $beId = $usr['beid'];
      $ph = $usr['phonenum'];
      $oldFirstname = $usr['firstname'];
      $oldLastname = $usr['lastname'];
      $sqlLog = $link->query("SELECT * FROM signin WHERE beid='$beId' && signinid='$signId' && status='in'");
      if ($sqlLog->num_rows > 0) {
        $newEmail = mysqli_real_escape_string($link, $_POST['email']);
        $emailSts = mysqli_real_escape_string($link, $_POST['emailSts']);
        $newPhone = mysqli_real_escape_string($link, str_replace("plus", "+", $_POST['phone']));
        $phoneSts = mysqli_real_escape_string($link, $_POST['phoneSts']);
        $bankAccount = mysqli_real_escape_string($link, $_POST['bankAccount']);
        $iban = mysqli_real_escape_string($link, $_POST['iban']);
        $bicswift = mysqli_real_escape_string($link, $_POST['bicswift']);
        $birthD = mysqli_real_escape_string($link, $_POST['birthD']);
        $birthM = mysqli_real_escape_string($link, $_POST['birthM']);
        $birthY = mysqli_real_escape_string($link, $_POST['birthY']);
        $password = $_POST['password'];
        $sqlPlcs = $link->query("SELECT * FROM places WHERE usrbeid='$beId' && status='active'");
        if ($sqlPlcs->num_rows == 0) {
          $ibanSts = true;
          if ($iban == "") {
            $iban = "-";
          }
        } else {
          if ($iban == "") {
            $ibanSts = false;
          } else {
            $ibanSts = true;
          }
        }
        if ($bankAccount == "") {
          $bankAccount = "-";
        }
        if ($bicswift == "") {
          $bicswift = "-";
        }
        if ($ibanSts) {
          if (check($newEmail, "empty")) {
            if (check($newEmail, "email")) {
              $sqlEmailDuplicateCheck = $link -> query("SELECT * FROM users WHERE (email='$newEmail' or contactemail='$newEmail') && status!='delete' && beid!='$beId'");
              if ($sqlEmailDuplicateCheck -> num_rows == 0) {
                if (check($newPhone, "empty")) {
                  if (check($newPhone, "tel")) {
                    $sqlPhoneNumDuplicateCheck = $link -> query("SELECT * FROM users WHERE (phonenum='$newPhone' or contactphonenum='$newPhone') && status!='delete' && beid!='$beId'");
                    if ($sqlPhoneNumDuplicateCheck -> num_rows == 0) {
                      if (strlen($iban) <= 34) {
                        if (strlen($bicswift) == 8 || strlen($bicswift) == 11 || $bicswift == "-") {
                          if (check($birthD, "empty")) {
                            if (!check($birthD, "num")) {
                              if (check($birthM, "empty")) {
                                if (!check($birthM, "num")) {
                                  if (check($birthY, "empty")) {
                                    if (!check($birthY, "num")) {
                                      if ($birthM > 0) {
                                        if ($birthM < 13) {
                                          if ($birthD > 0) {
                                            if ($birthD <= cal_days_in_month(CAL_GREGORIAN, $birthM, $birthY)) {
                                              if (date("Y-m-d") > $birthY."-".sprintf("%02d", $birthM)."-".sprintf("%02d", $birthD)) {
                                                if (floor(abs(strtotime(date("Y-m-d")) - strtotime($birthY."-".sprintf("%02d", $birthM)."-".sprintf("%02d", $birthD))) / (365*60*60*24)) > 17) {
                                                  $emailChanged = false;
                                                  $phoneChanged = false;
                                                  if ($em != $newEmail) {
                                                    $passSts = passCheck($password, $newEmail, $newPhone);
                                                    $emailChanged = true;
                                                    if ($passSts == "good") {
                                                      $passAccepted = true;
                                                    } else {
                                                      $passAccepted = false;
                                                    }
                                                  } else {
                                                    $passAccepted = true;
                                                  }
                                                  if ($newPhone != $ph) {
                                                    $passSts = passCheck($password, $newEmail, $newPhone);
                                                    $phoneChanged = true;
                                                    if (passCheck($password, $newEmail, $newPhone) != "good") {
                                                      $passAccepted = false;
                                                    }
                                                  }
                                                  if ($passAccepted) {
                                                    if ($emailSts == "true") {
                                                      $emailSts = 1;
                                                    } else {
                                                      $emailSts = 0;
                                                    }
                                                    if ($phoneSts == "true") {
                                                      $phoneSts = 1;
                                                    } else {
                                                      $phoneSts = 0;
                                                    }
                                                    $fullBirth = $birthY."-".sprintf("%02d", $birthM)."-".sprintf("%02d", $birthD);
                                                    if ($emailSts == 1 && $phoneSts == 1) {
                                                      $sql = "UPDATE users SET email='$newEmail', contactemail='$newEmail', syncemailsts='$emailSts', phonenum='$newPhone', contactphonenum='$newPhone', syncnumsts='$phoneSts', bankaccount='$bankAccount', iban='$iban', bicswift='$bicswift', fullbirth='$fullBirth', birthy='$birthY', birthm='$birthM', birthd='$birthD' WHERE beid='$beId'";
                                                    } else if ($emailSts == 1) {
                                                      $sql = "UPDATE users SET email='$newEmail', contactemail='$newEmail', syncemailsts='$emailSts', phonenum='$newPhone', syncnumsts='$phoneSts', bankaccount='$bankAccount', iban='$iban', bicswift='$bicswift', fullbirth='$fullBirth', birthy='$birthY', birthm='$birthM', birthd='$birthD' WHERE beid='$beId'";
                                                    } else if ($phoneSts == 1) {
                                                      $sql = "UPDATE users SET email='$newEmail', syncemailsts='$emailSts', phonenum='$newPhone', contactphonenum='$newPhone', syncnumsts='$phoneSts', bankaccount='$bankAccount', iban='$iban', bicswift='$bicswift', fullbirth='$fullBirth', birthy='$birthY', birthm='$birthM', birthd='$birthD' WHERE beid='$beId'";
                                                    } else {
                                                      $sql = "UPDATE users SET email='$newEmail', syncemailsts='$emailSts', phonenum='$newPhone', syncnumsts='$phoneSts', bankaccount='$bankAccount', iban='$iban', bicswift='$bicswift', fullbirth='$fullBirth', birthy='$birthY', birthm='$birthM', birthd='$birthD' WHERE beid='$beId'";
                                                    }
                                                    if (mysqli_query($link, $sql)) {
                                                      if ($emailSts == 1 && $phoneSts == 1) {
                                                        $sqlArchive = "UPDATE usersarchive SET contactemail='$newEmail', contactphonenum='$newPhone', bankaccount='$bankAccount', iban='$iban', bicswift='$bicswift', fullbirth='$fullBirth', birthy='$birthY', birthm='$birthM', birthd='$birthD' WHERE beid='$beId'";
                                                      } else if ($emailSts == 1) {
                                                        $sqlArchive = "UPDATE usersarchive SET contactemail='$newEmail', bankaccount='$bankAccount', iban='$iban', bicswift='$bicswift', fullbirth='$fullBirth', birthy='$birthY', birthm='$birthM', birthd='$birthD' WHERE beid='$beId'";
                                                      } else if ($phoneSts == 1) {
                                                        $sqlArchive = "UPDATE usersarchive SET contactphonenum='$newPhone', bankaccount='$bankAccount', iban='$iban', bicswift='$bicswift', fullbirth='$fullBirth', birthy='$birthY', birthm='$birthM', birthd='$birthD' WHERE beid='$beId'";
                                                      } else {
                                                        $sqlArchive = "UPDATE usersarchive SET bankaccount='$bankAccount', iban='$iban', bicswift='$bicswift', fullbirth='$fullBirth', birthy='$birthY', birthm='$birthM', birthd='$birthD' WHERE beid='$beId'";
                                                      }
                                                      if (mysqli_query($linkBD, $sqlArchive)) {
                                                        setcookie("email", $newEmail, time() + (900*24*60*60*1000), "/");
                                                        $_SESSION['email'] = $newEmail;
                                                        done();
                                                      } else {
                                                        error("Failed to save to archive database<br>".mysqli_error($link));
                                                      }
                                                    } else {
                                                      error("Failed to save to database<br>".mysqli_error($link));
                                                    }
                                                  } else {
                                                    if ($emailChanged) {
                                                      askForPasswordChanges("email", $em, $newEmail);
                                                    }
                                                    if ($phoneChanged) {
                                                      askForPasswordChanges("phone", $ph, $newPhone);
                                                    }
                                                    askForPassword($passSts);
                                                  }
                                                } else {
                                                  error("You have to be over 18 years old!");
                                                }
                                              } else {
                                                error("Invalid birthday date");
                                              }
                                            } else {
                                              error("Birthday -> day value is too high");
                                            }
                                          } else {
                                            error("Birthday -> day value is too low");
                                          }
                                        } else {
                                          error("Birthday -> month value is too high");
                                        }
                                      } else {
                                        error("Birthday -> month value is too low");
                                      }
                                    } else {
                                      error("Birthday -> year contains invalid value");
                                    }
                                  } else {
                                    error("Birthday -> year field is empty");
                                  }
                                } else {
                                  error("Birthday -> month contains invalid value");
                                }
                              } else {
                                error("Birthday -> month field is empty");
                              }
                            } else {
                              error("Birthday -> day contains invalid value");
                            }
                          } else {
                            error("Birthday -> day field is empty");
                          }
                        } else {
                          error("BIC/SWIFT has invalid number of characters");
                        }
                      } else {
                        error("IBAN has invalid number of characters");
                      }
                    } else {
                      error("This phone number is already registered in our database");
                    }
                  } else {
                    error("Phone number is not in valid form");
                  }
                } else {
                  error("Phone number is empty");
                }
              } else {
                error("This email is already registered in our database");
              }
            } else {
              error("Email is not in valid form");
            }
          } else {
            error("Email is empty");
          }
        } else {
          error("At least the IBAN field has to be filled");
        }
      } else {
        error("action denied - sign in data not matching with data in database");
      }
    } else {
      error("data from session not maching with data from database");
    }
  } else {
    error("session error - missing data");
  }

  function passCheck($password, $email, $phone) {
    global $link, $beId;
    $sqlPass = $link->query("SELECT * FROM users WHERE beid='$beId'");
    $pass = $sqlPass->fetch_assoc();
    if ($password == "") {
      if ($email == $pass['email'] && $phone == $pass['phonenum']) {
        return "good";
      } else {
        return "Empty password";
      }
    } else {
      if (password_verify(passEdit($password), passEdit($pass['password']))) {
        return "good";
      } else {
        return "Wrong password";
      }
    }
  }

  function askForPasswordChanges($changed, $changedFrom, $changedTo) {
    global $output;
    array_push($output, [
      "type" => "password-needed",
      "chenged" => $changed,
      "txt" => $changedFrom." -> ".$changedTo
    ]);
  }

  function askForPassword($msg) {
    global $output;
    array_push($output, [
      "type" => "password-error",
      "error" => $msg
    ]);
    returnOutput();
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
    global $output, $link, $beId, $oldFirstname, $oldLastname;
    $sqlNewData = $link->query("SELECT * FROM users WHERE beid='$beId'");
    $nd = $sqlNewData->fetch_assoc();
    array_push($output, [
      "type" => "done",
      "firstname" => $nd['firstname'],
      "oldFirstname" => $oldFirstname,
      "lastname" => $nd['lastname'],
      "oldLastname" => $oldLastname,
      "email" => $nd['email'],
      "contactemail" => $nd['contactemail'],
      "syncemailsts" => $nd['syncemailsts'],
      "phonenum" => $nd['phonenum'],
      "contactphonenum" => $nd['contactphonenum'],
      "syncnumsts" => $nd['syncnumsts']
    ]);
    returnOutput();
  }

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
