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
      $conctEm = $usr['contactemail'];
      $oldFirstname = $usr['firstname'];
      $oldLastname = $usr['lastname'];
      $sqlLog = $link->query("SELECT * FROM signin WHERE beid='$beId' && signinid='$signId' && status='in'");
      if ($sqlLog->num_rows > 0) {
        $firstname = mysqli_real_escape_string($link, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($link, $_POST['lastname']);
        $contactEmail = mysqli_real_escape_string($link, $_POST['contEmail']);
        $contactEmailSts = mysqli_real_escape_string($link, $_POST['contEmailSts']);
        $contactPhone = mysqli_real_escape_string($link, str_replace("plus", "+", $_POST['contPhone']));
        $contactPhoneSts = mysqli_real_escape_string($link, $_POST['contPhoneSts']);
        $password = $_POST['password'];
        if (check($firstname, "empty")) {
          if (check($firstname, "numIn")) {
            if (check($lastname, "empty")) {
              if (check($lastname, "numIn")) {
                if (check($contactEmail, "empty")) {
                  if (check($contactEmail, "email")) {
                    $sqlEmailDuplicateCheck = $link -> query("SELECT * FROM users WHERE (email='$contactEmail' or contactemail='$contactEmail') && status!='delete' && beid!='$beId'");
                    if ($sqlEmailDuplicateCheck -> num_rows == 0) {
                      if (check($contactPhone, "empty")) {
                        if (check($contactPhone, "tel")) {
                          $sqlPhoneNumDuplicateCheck = $link -> query("SELECT * FROM users WHERE (phonenum='$contactPhone' or contactphonenum='$contactPhone') && status!='delete' && beid!='$beId'");
                          if ($sqlPhoneNumDuplicateCheck -> num_rows == 0) {
                            $emailChanged = false;
                            $phoneChanged = false;
                            if ($contactEmailSts == "true" && $em != $contactEmail) {
                              $passSts = passCheck($password, $contactEmail, $contactPhone);
                              $emailChanged = true;
                              if ($passSts == "good") {
                                $passAccepted = true;
                              } else {
                                $passAccepted = false;
                              }
                            } else {
                              $passAccepted = true;
                            }
                            if ($contactPhoneSts == "true" && $contactPhone != $ph) {
                              $passSts = passCheck($password, $contactEmail, $contactPhone);
                              $phoneChanged = true;
                              if (passCheck($password, $contactEmail, $contactPhone) != "good") {
                                $passAccepted = false;
                              }
                            }
                            if ($passAccepted) {
                              if ($contactEmailSts == "true") {
                                $contactEmailSts = 1;
                              } else {
                                $contactEmailSts = 0;
                              }
                              if ($contactPhoneSts == "true") {
                                $contactPhoneSts = 1;
                              } else {
                                $contactPhoneSts = 0;
                              }
                              $sqlBookings = "UPDATE booking SET email='$contactEmail' WHERE usrbeid='$beId' and email='$conctEm'";
                              if (mysqli_query($link, $sqlBookings)) {
                                if ($emailChanged && $phoneChanged) {
                                  $sql = "UPDATE users SET firstname='$firstname', lastname='$lastname', email='$contactEmail', contactemail='$contactEmail', syncemailsts='$contactEmailSts', phonenum='$contactPhone', contactphonenum='$contactPhone', syncnumsts='$contactPhoneSts' WHERE beid='$beId'";
                                } else if ($emailChanged) {
                                  $sql = "UPDATE users SET firstname='$firstname', lastname='$lastname', email='$contactEmail', contactemail='$contactEmail', syncemailsts='$contactEmailSts', contactphonenum='$contactPhone', syncnumsts='$contactPhoneSts' WHERE beid='$beId'";
                                } else if ($phoneChanged) {
                                  $sql = "UPDATE users SET firstname='$firstname', lastname='$lastname', contactemail='$contactEmail', syncemailsts='$contactEmailSts', phonenum='$contactPhone', contactphonenum='$contactPhone', syncnumsts='$contactPhoneSts' WHERE beid='$beId'";
                                } else {
                                  $sql = "UPDATE users SET firstname='$firstname', lastname='$lastname', contactemail='$contactEmail', syncemailsts='$contactEmailSts', contactphonenum='$contactPhone', syncnumsts='$contactPhoneSts' WHERE beid='$beId'";
                                }
                                if (mysqli_query($link, $sql)) {
                                  $sqlArchive = "UPDATE usersarchive SET firstname='$firstname', lastname='$lastname', contactemail='$contactEmail', contactphonenum='$contactPhone' WHERE beid='$beId'";
                                  if (mysqli_query($linkBD, $sqlArchive)) {
                                    if ($contactEmailSts == 1) {
                                      setcookie("email", $contactEmail, time() + (900*24*60*60*1000), "/");
                                      $_SESSION['email'] = $contactEmail;
                                    }
                                    done();
                                  } else {
                                    error("Failed to save to archive database");
                                  }
                                } else {
                                  error("Failed to save to database");
                                }
                              } else {
                                error("Update contact emails in your bookings failed");
                              }
                            } else {
                              if ($emailChanged) {
                                askForPasswordChanges("email", $em, $contactEmail);
                              }
                              if ($phoneChanged) {
                                askForPasswordChanges("phone", $ph, $contactPhone);
                              }
                              askForPassword($passSts);
                            }
                          } else {
                            error("This contact phone number is already registered in our database");
                          }
                        } else {
                          error("Contact phone number is not in valid form");
                        }
                      } else {
                        error("Contact phone number is empty");
                      }
                    } else {
                      error("This contact email is already registered in our database");
                    }
                  } else {
                    error("Email is not in valid form");
                  }
                } else {
                  error("Contact email is empty");
                }
              } else {
                error("Lastname is not in valid form");
              }
            } else {
              error("Lastname is empty");
            }
          } else {
            error("Firstname is not in valid form");
          }
        } else {
          error("Firstname is empty");
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
