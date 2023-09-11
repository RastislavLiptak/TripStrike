<?php
  include "../../data.php";
  include "../../account-data-check.php";
  include "../../password-edit.php";
  include "../../random-hash-maker.php";
  include "../../../../dictionary/get-lang-shortcut.php";
  include "../../img-crop/prof-img-convert.php";
  include "../sign-in-log.php";
  $output = [];
  $date = date("Y-m-d H:i:s");
  $dateY = date("Y");
  $dateM = date("m");
  $dateD = date("d");
  $smlImgReady = false;
  $midImgReady = false;
  $bigImgReady = false;
  $imgConvertError = "";
  $firstname = mysqli_real_escape_string($link, $_POST['firstname']);
  $lastname = mysqli_real_escape_string($link, $_POST['lastname']);
  $birthD = sprintf("%02d", mysqli_real_escape_string($link, $_POST['birthd']));
  $birthM = sprintf("%02d", mysqli_real_escape_string($link, $_POST['birthm']));
  $birthY = mysqli_real_escape_string($link, $_POST['birthy']);
  $email = mysqli_real_escape_string($link, $_POST['email']);
  $contactemail = mysqli_real_escape_string($link, $_POST['contactemail']);
  $phone = mysqli_real_escape_string($link, str_replace("plus", "+", $_POST['phone']));
  $contactphone = mysqli_real_escape_string($link, str_replace("plus", "+", $_POST['contactphone']));
  $password = mysqli_real_escape_string($link, $_POST['password']);
  $passwordverification = mysqli_real_escape_string($link, $_POST['passwordverification']);
  $conditions = mysqli_real_escape_string($link, $_POST['conditions']);
  $fullBirth = $birthY."-".$birthM."-".$birthD;
  if ($contactemail == "") {
    $contactemail = $email;
  }
  if ($contactphone == "") {
    $contactphone = $phone;
  }
  if ($conditions != 1) {
    $conditions = 0;
  }
  if (check($firstname, "empty")) {
    if (check($lastname, "empty")) {
      if (check($email, "empty")) {
        if (check($email, "email")) {
          if (check($email, "emailSql")) {
            if (check($contactemail, "empty")) {
              if (check($contactemail, "email")) {
                if (check($contactemail, "emailSql")) {
                  if (check($phone, "empty")) {
                    if (check($phone, "tel")) {
                      if (check($phone, "telSql")) {
                        if (check($contactphone, "empty")) {
                          if (check($contactphone, "tel")) {
                            if (check($contactphone, "telSql")) {
                              if (check($password, "empty")) {
                                if (check($password, "length")) {
                                  if ($password == $passwordverification) {
                                    if ($conditions == 1) {
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
                                                          if (date("Y-m-d") > $fullBirth) {
                                                            if (floor(abs(strtotime(date("Y-m-d")) - strtotime($fullBirth)) / (365*60*60*24)) > 17) {
                                                              $lang = langShortcut("");
                                                              $lang_name = $default_langs_arr_title[array_search($lang, $default_langs_arr)];
                                                              if ($email == $contactemail) {
                                                                $syncemailsts = 1;
                                                              } else {
                                                                $syncemailsts = 0;
                                                              }
                                                              if ($phone == $contactphone) {
                                                                $syncphonests = 1;
                                                              } else {
                                                                $syncphonests = 0;
                                                              }
                                                              $password_hash = password_hash(passEdit($password), PASSWORD_DEFAULT);
                                                              $beIdReady = false;
                                                              while (!$beIdReady) {
                                                                $beId = randomHash(64);
                                                                if ($link->query("SELECT * FROM backendidlist WHERE beid='$beId'")->num_rows == 0) {
                                                                  $beIdReady = true;
                                                                } else {
                                                                  $beIdReady = false;
                                                                }
                                                              }
                                                              $idReady = false;
                                                              while (!$idReady) {
                                                                $id = randomHash(11);
                                                                if ($link->query("SELECT * FROM idlist WHERE id='$id'")->num_rows == 0) {
                                                                  $idReady = true;
                                                                } else {
                                                                  $idReady = false;
                                                                }
                                                              }
                                                              $backendIDNum = $link->query("SELECT * FROM backendidlist")->num_rows;
                                                              $sqlBeID = "INSERT INTO backendidlist (beid, numid, type) VALUES('$beId', '$backendIDNum', 'user')";
                                                              if (mysqli_query($link, $sqlBeID)) {
                                                                $sqlID = "INSERT INTO idlist (beid, id, fullDate, dateD, dateM, dateY) VALUES('$beId', '$id', '$date', '$dateD', '$dateM', '$dateY')";
                                                                if (mysqli_query($link, $sqlID)) {
                                                                  $sqlLng = "INSERT INTO languages (beid, language) VALUES ('$beId', '$lang_name')";
                                                                  if (mysqli_query($link, $sqlLng)) {
                                                                    $sqlTerms = "INSERT INTO termsaccept (beid, fulldate, dated, datem, datey) VALUES('$beId', '$date', '$dateD', '$dateM', '$dateY')";
                                                                    if (mysqli_query($link, $sqlTerms)) {
                                                                      $sql = "INSERT INTO users (beid, status, firstname, lastname, email, contactemail, syncemailsts, phonenum, contactphonenum, syncnumsts, bankaccount, iban, bicswift, password, fullbirth, birthy, birthm, birthd, description, language, signupfulldate, signupy, signupm, signupd) VALUES ('$beId', 'active', '$firstname', '$lastname', '$email', '$contactemail', '$syncemailsts', '$phone', '$contactphone', '$syncphonests', '-', '-', '-', '$password_hash', '$fullBirth', '$birthY', '$birthM', '$birthD', '', '$lang', '$date', '$dateY', '$dateM', '$dateD')";
                                                                      if (mysqli_query($link, $sql)) {
                                                                        $sqlArchive = "INSERT INTO usersarchive (beid, status, firstname, lastname, contactemail, contactphonenum, bankaccount, iban, bicswift, fullbirth, birthy, birthm, birthd, description, language, signupfulldate, signupy, signupm, signupd) VALUES ('$beId', 'active', '$firstname', '$lastname', '$contactemail', '$contactphone', '-', '-', '-', '$fullBirth', '$birthY', '$birthM', '$birthD', '', '$lang', '$date', '$dateY', '$dateM', '$dateD')";
                                                                        if (mysqli_query($linkBD, $sqlArchive)) {
                                                                          connectWithBookings($beId, $email, $contactemail);
                                                                          featuresAddComment();
                                                                          featuresRateCottage();
                                                                          profileImage($beId, 32, "small", "sign-up", "../../../../../");
                                                                          profileImage($beId, 64, "mid", "sign-up", "../../../../../");
                                                                          profileImage($beId, 256, "big", "sign-up", "../../../../../");
                                                                        } else {
                                                                          error("SQL error - failed to save all your data into archive <br>".mysqli_error($linkBD));
                                                                        }
                                                                      } else {
                                                                        error("SQL error - failed to save all your data <br>".mysqli_error($link));
                                                                      }
                                                                    } else {
                                                                      error("SQL error - failed to save data about conditions <br>".mysqli_error($link));
                                                                    }
                                                                  } else {
                                                                    error("SQL error - failed to save language <br>".mysqli_error($link));
                                                                  }
                                                                } else {
                                                                  error("SQL error - failed to save ID <br>".mysqli_error($link));
                                                                }
                                                              } else {
                                                                error("SQL error - failed to save backend ID <br>".mysqli_error($link));
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
                                      error("You must accept our terms");
                                    }
                                  } else {
                                    error("Password and password verification are different");
                                  }
                                } else {
                                  error("Password is too short (The minimum length is 4 characters)");
                                }
                              } else {
                                error("Password is empty");
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
                        error("This phone number is already registered in our database");
                      }
                    } else {
                      error("Phone number is not in valid form");
                    }
                  } else {
                    error("Phone number is empty");
                  }
                } else {
                  error("This contact email is already registered in our database");
                }
              } else {
                error("Contact email is not in valid form");
              }
            } else {
              error("Contact email is empty");
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
      error("Last name is empty");
    }
  } else {
    error("First name is empty");
  }

  function connectWithBookings($beId, $email, $contactEmail) {
    global $link;
    $sqlBookings = "UPDATE booking SET usrbeid='$beId' WHERE (email='$email' or email='$contactEmail') and usrbeid='-'";
    mysqli_query($link, $sqlBookings);
  }

  function featuresAddComment() {
    global $link, $linkBD, $date, $beId;
    $featureBeIDReady = false;
    while (!$featureBeIDReady) {
      $featureBeID = randomHash(64);
      $sqlBeIDCH = $linkBD->query("SELECT * FROM featuresvalidation WHERE beid='$featureBeID'");
      if ($sqlBeIDCH->num_rows == 0) {
        $featureBeIDReady = true;
      } else {
        $featureBeIDReady = false;
      }
    }
    $featureIDReady = false;
    while (!$featureIDReady) {
      $featureID = randomHash(rand(5,11));
      $sqlIDCH = $linkBD->query("SELECT * FROM featuresvalidation WHERE id='$featureID'");
      if ($sqlIDCH->num_rows == 0) {
        $featureIDReady = true;
      } else {
        $featureIDReady = false;
      }
    }
    $sqlFeaturesValidation = "INSERT INTO featuresvalidation (beid, id, name, sts, fulldate) VALUES('$featureBeID', '$featureID', 'add-comment', 'active', '$date')";
    if (mysqli_query($linkBD, $sqlFeaturesValidation)) {
      $sqlFeaturesInUse = "INSERT INTO featuresinuse (beid, usrbeid) VALUES('$featureBeID', '$beId')";
      mysqli_query($linkBD, $sqlFeaturesInUse);
    }
  }

  function featuresRateCottage() {
    global $link, $linkBD, $date, $beId;
    $featureBeIDReady = false;
    while (!$featureBeIDReady) {
      $featureBeID = randomHash(64);
      $sqlBeIDCH = $linkBD->query("SELECT * FROM featuresvalidation WHERE beid='$featureBeID'");
      if ($sqlBeIDCH->num_rows == 0) {
        $featureBeIDReady = true;
      } else {
        $featureBeIDReady = false;
      }
    }
    $featureIDReady = false;
    while (!$featureIDReady) {
      $featureID = randomHash(rand(5,11));
      $sqlIDCH = $linkBD->query("SELECT * FROM featuresvalidation WHERE id='$featureID'");
      if ($sqlIDCH->num_rows == 0) {
        $featureIDReady = true;
      } else {
        $featureIDReady = false;
      }
    }
    $sqlFeaturesValidation = "INSERT INTO featuresvalidation (beid, id, name, sts, fulldate) VALUES('$featureBeID', '$featureID', 'add-rating', 'active', '$date')";
    if (mysqli_query($linkBD, $sqlFeaturesValidation)) {
      $sqlFeaturesInUse = "INSERT INTO featuresinuse (beid, usrbeid) VALUES('$featureBeID', '$beId')";
      mysqli_query($linkBD, $sqlFeaturesInUse);
    }
  }

  function imgDone($type, $msg, $beId) {
    global $smlImgReady, $midImgReady, $bigImgReady, $imgConvertError, $beId;
    if ($type == "small") {
      $smlImgReady = true;
    } else if ($type == "mid") {
      $midImgReady = true;
    } else if ($type == "big") {
      $bigImgReady = true;
    }
    if ($msg != "done") {
      $imgConvertError = $imgConvertError."<br>".$msg;
    }
    if ($smlImgReady && $midImgReady && $bigImgReady) {
      signInLog($beId);
    }
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
