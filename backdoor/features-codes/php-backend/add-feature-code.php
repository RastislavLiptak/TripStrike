<?php
  include "../../../uni/code/php-backend/data.php";
  include "../../../uni/code/php-backend/get-frontend-id.php";
  include "../../../uni/code/php-backend/get-data-from-date.php";
  include "../../../uni/dictionary/lang-select.php";
  include "../../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/check-sign-in.php";
  include "../../../uni/code/php-backend/random-hash-maker.php";
  include "search-for-users.php";
  header('Content-Type: application/json');
  $output = [];
  $date = date("Y-m-d H:i:s");
  $codeName = mysqli_real_escape_string($link, $_POST['codeName']);
  $codeCheckbox = mysqli_real_escape_string($link, $_POST['codeCheckbox']);
  $fcUser = mysqli_real_escape_string($link, $_POST['user']);
  $codeNum = mysqli_real_escape_string($link, $_POST['codeNum']);
  $featuresList = json_decode($_POST['features']);
  $backDoorCheckSignInSts = backDoorCheckSignIn();
  if ($backDoorCheckSignInSts == "good") {
    if (($codeName == "" && $codeCheckbox == "1") || ($codeName != "" && $codeCheckbox == "0")) {
      if (is_numeric($codeNum)) {
        if ($codeNum > 0) {
          if (floor($codeNum) == $codeNum) {
            if ($codeNum == 1 || ($codeNum > 1 && $codeCheckbox == "1")) {
              $listOfUsers = searchForUsers($fcUser);
              if (sizeof($listOfUsers) == 1 || $fcUser == "") {
                $fcUserSts = true;
                $fcUserBeId = "-";
                if ($fcUser != "") {
                  $fcUserID = $listOfUsers[0]['id'];
                  $sqlBeId = $link->query("SELECT beid FROM idlist WHERE id='$fcUserID'");
                  if ($sqlBeId->num_rows > 0) {
                    $fcUserBeId = $sqlBeId->fetch_assoc()['beid'];
                  } else {
                    $fcUserSts = false;
                  }
                }
                if ($fcUserSts) {
                  $numOfDone = 0;
                  $numOfError = 0;
                  $featuresDoneTxt = "";
                  $featuresErrorTxt = "";
                  for ($g=0; $g < $codeNum; $g++) {
                    if ($codeCheckbox == "1") {
                      $codeNameReady = false;
                      while (!$codeNameReady) {
                        $codeName = randomHash(rand(5,11));
                        $sqlCodeCH = $linkBD->query("SELECT * FROM featuresvalidation WHERE id='$codeName'");
                        if ($sqlCodeCH->num_rows == 0) {
                          $codeNameReady = true;
                        } else {
                          $codeNameReady = false;
                        }
                      }
                    }
                    foreach ($featuresList as $fL => $feature) {
                      if ($feature->select != "" || $feature->value != "") {
                        if (($feature->select != "" && $feature->value == "") || ($feature->select == "" && $feature->value != "")) {
                          if ($feature->select != "") {
                            $featureCode = $feature->select;
                          } else {
                            $featureCode = $feature->value;
                          }
                          $duplicateCheck = true;
                          $sqlCheckDuplicate = $linkBD->query("SELECT * FROM featuresvalidation WHERE id='$codeName' and name='$featureCode'");
                          if ($sqlCheckDuplicate->num_rows > 0) {
                            while($rowCheckDuplicate = $sqlCheckDuplicate->fetch_assoc()) {
                              if ($fcUserBeId != "-") {
                                $featureCodeBeID = $rowCheckDuplicate['beid'];
                                $sqlCheckUserDuplicate = $linkBD->query("SELECT * FROM featuresinuse WHERE beid='$featureCodeBeID' and usrbeid='$fcUserBeId'");
                                if ($sqlCheckUserDuplicate->num_rows > 0) {
                                  $duplicateCheck = false;
                                }
                              } else {
                                $duplicateCheck = false;
                              }
                            }
                          }
                          if ($duplicateCheck) {
                            $tskSts = "";
                            $updateCodeBeId = "";
                            if ($fcUserBeId != "-") {
                              $sqlCheckFeatureActivation = $linkBD->query("SELECT beid FROM featuresvalidation WHERE id='$codeName' and name='$featureCode' and sts='inactive'");
                              if ($sqlCheckFeatureActivation->num_rows > 0) {
                                $updateCodeBeId = $sqlCheckFeatureActivation->fetch_assoc()['beid'];
                                $tskSts = "update";
                              } else {
                                $tskSts = "insert";
                              }
                            } else {
                              $tskSts = "insert";
                            }
                            if ($tskSts == "insert") {
                              $insertCodeBeIdReady = false;
                              while (!$insertCodeBeIdReady) {
                                $insertCodeBeId = randomHash(64);
                                $sqlBeIDCH = $linkBD->query("SELECT * FROM featuresvalidation WHERE beid='$insertCodeBeId'");
                                if ($sqlBeIDCH->num_rows == 0) {
                                  $insertCodeBeIdReady = true;
                                } else {
                                  $insertCodeBeIdReady = false;
                                }
                              }
                              if ($fcUserBeId == "-") {
                                $sqlCodeFeatureInsert = "INSERT INTO featuresvalidation (beid, id, name, sts, fulldate) VALUES('$insertCodeBeId', '$codeName', '$featureCode', 'inactive', '$date')";
                              } else {
                                $sqlCodeFeatureInsert = "INSERT INTO featuresvalidation (beid, id, name, sts, fulldate) VALUES('$insertCodeBeId', '$codeName', '$featureCode', 'active', '$date')";
                              }
                              if (mysqli_query($linkBD, $sqlCodeFeatureInsert)) {
                                if ($fcUserBeId == "-") {
                                  ++$numOfDone;
                                  if ($featuresDoneTxt == "") {
                                    $featuresDoneTxt = "Done (code: ".$codeName."; feature: ".$featureCode.")";
                                  } else {
                                    $featuresDoneTxt = $featuresDoneTxt."<br>Done (code: ".$codeName."; feature: ".$featureCode.")";
                                  }
                                } else {
                                  $sqlCodeFeatureInUse = "INSERT INTO featuresinuse (beid, usrbeid) VALUES('$insertCodeBeId', '$fcUserBeId')";
                                  if (mysqli_query($linkBD, $sqlCodeFeatureInUse)) {
                                    ++$numOfDone;
                                    if ($featuresDoneTxt == "") {
                                      $featuresDoneTxt = "Done (code: ".$codeName."; feature: ".$featureCode.")";
                                    } else {
                                      $featuresDoneTxt = $featuresDoneTxt."<br>Done (code: ".$codeName."; feature: ".$featureCode.")";
                                    }
                                  } else {
                                    ++$numOfError;
                                    if ($featuresErrorTxt == "") {
                                      $featuresErrorTxt = "Failed to insert into featuresinuse database (insert code) (code: ".$codeName."; feature: ".$featureCode.")<br>".mysqli_error($linkBD);
                                    } else {
                                      $featuresErrorTxt = $featuresErrorTxt."<br>Failed to insert into featuresinuse database (insert code) (code: ".$codeName."; feature: ".$featureCode.")<br>".mysqli_error($linkBD);
                                    }
                                  }
                                }
                              } else {
                                ++$numOfError;
                                if ($featuresErrorTxt == "") {
                                  $featuresErrorTxt = "Failed to insert into featuresvalidation database (code: ".$codeName."; feature: ".$featureCode.")<br>".mysqli_error($linkBD);
                                } else {
                                  $featuresErrorTxt = $featuresErrorTxt."<br>Failed to insert into featuresvalidation database (code: ".$codeName."; feature: ".$featureCode.")<br>".mysqli_error($linkBD);
                                }
                              }
                            } else if ($tskSts == "update") {
                              $sqlCodeFeatureUpdt = "UPDATE featuresvalidation SET sts='active' WHERE beid='$updateCodeBeId'";
                              if (mysqli_query($linkBD, $sqlCodeFeatureUpdt)) {
                                $sqlCodeFeatureInUse = "INSERT INTO featuresinuse (beid, usrbeid) VALUES('$updateCodeBeId', '$fcUserBeId')";
                                if (mysqli_query($linkBD, $sqlCodeFeatureInUse)) {
                                  ++$numOfDone;
                                  if ($featuresDoneTxt == "") {
                                    $featuresDoneTxt = "Done (code: ".$codeName."; feature: ".$featureCode.")";
                                  } else {
                                    $featuresDoneTxt = $featuresDoneTxt."<br>Done (code: ".$codeName."; feature: ".$featureCode.")";
                                  }
                                } else {
                                  ++$numOfError;
                                  if ($featuresErrorTxt == "") {
                                    $featuresErrorTxt = "Failed to insert into featuresinuse database (update code) (code: ".$codeName."; feature: ".$featureCode.")<br>".mysqli_error($linkBD);
                                  } else {
                                    $featuresErrorTxt = $featuresErrorTxt."<br>Failed to insert into featuresinuse database (update code) (code: ".$codeName."; feature: ".$featureCode.")<br>".mysqli_error($linkBD);
                                  }
                                }
                              } else {
                                ++$numOfError;
                                if ($featuresErrorTxt == "") {
                                  $featuresErrorTxt = "Failed to update status to active (code: ".$codeName."; feature: ".$featureCode.")<br>".mysqli_error($linkBD);
                                } else {
                                  $featuresErrorTxt = $featuresErrorTxt."<br>Failed to update status to active (code: ".$codeName."; feature: ".$featureCode.")<br>".mysqli_error($linkBD);
                                }
                              }
                            }
                          } else {
                            ++$numOfError;
                            if ($featuresErrorTxt == "") {
                              $featuresErrorTxt = "The code data matches the existing code (code: ".$codeName."; feature: ".$featureCode.")";
                            } else {
                              $featuresErrorTxt = $featuresErrorTxt."<br>The code data matches the existing code (code: ".$codeName."; feature: ".$featureCode.")";
                            }
                          }
                        } else {
                          ++$numOfError;
                          if ($featuresErrorTxt == "") {
                            $featuresErrorTxt = "Select only one of the options in feature (code: ".$codeName."; feature: ".$featureCode.")";
                          } else {
                            $featuresErrorTxt = $featuresErrorTxt."<br>Select only one of the options in feature (code: ".$codeName."; feature: ".$featureCode.")";
                          }
                        }
                      }
                    }
                  }
                  if ($numOfError > 0) {
                    if ($numOfDone > 0) {
                      error($featuresDoneTxt."<br>".$featuresErrorTxt);
                    } else {
                      error($featuresErrorTxt);
                    }
                  } else {
                    done();
                  }
                } else {
                  error("User ID not connected to backend ID");
                }
              } else {
                if (sizeof($listOfUsers) > 1) {
                  error("More than one user has been found");
                } else {
                  error("Less than one user has been found");
                }
              }
            } else {
              error("The codes would be repeated");
            }
          } else {
            error("Number of generated code cannot be decimal");
          }
        } else {
          error("Number of generated code has to be equal to 1 or higher");
        }
      } else {
        error("Number of generatd codes is not a number");
      }
    } else {
      error("Type code name or check checkbox for random code name");
    }
  } else {
    error($backDoorCheckSignInSts);
  }

  function done() {
    global $output;
    array_push($output, [
      "type" => "done"
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

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
