<?php
  include "../../../uni/code/php-backend/data.php";
  include "../../../uni/code/php-backend/get-frontend-id.php";
  include "../../../uni/code/php-backend/get-data-from-date.php";
  include "../../../uni/dictionary/lang-select.php";
  include "../../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/check-sign-in.php";
  header('Content-Type: application/json');
  $output = [];
  $day = mysqli_real_escape_string($link, $_POST['day']);
  $time = mysqli_real_escape_string($link, $_POST['time']);
  if (sizeof(explode(":", $time)) == 2) {
    $hours = explode(":", $time)[0];
    $minutes = explode(":", $time)[1];
    $backDoorCheckSignInSts = backDoorCheckSignIn();
    if ($backDoorCheckSignInSts == "good") {
      if (is_numeric($day)) {
        if (is_numeric($hours)) {
          if (is_numeric($minutes)) {
            if (floor($day) == $day) {
              if (floor($hours) == $hours) {
                if (floor($minutes) == $minutes) {
                  if ($day > 0 && $day < 32) {
                    if ($hours >= 0 && $hours < 25) {
                      if ($minutes >= 0 && $minutes < 61) {
                        $outputError = "";
                        $sqlCheckDtbDay = $linkBD->query("SELECT * FROM settings WHERE name='date-of-call-for-fees-day'");
                        if ($sqlCheckDtbDay->num_rows > 0) {
                          $sqlUpdateDay = "UPDATE settings SET value='$day' WHERE name='date-of-call-for-fees-day'";
                          if (!mysqli_query($linkBD, $sqlUpdateDay)) {
                            if ($outputError == "") {
                              $outputError = "Failed to update day: ".mysqli_error($linkBD);
                            } else {
                              $outputError = $outputError."<br>Failed to update day: ".mysqli_error($linkBD);
                            }
                          }
                        } else {
                          $sqlInsertDay = "INSERT INTO settings (name, value, type) VALUES ('date-of-call-for-fees-day', '$day', 'txt')";
                          if (!mysqli_query($linkBD, $sqlInsertDay)) {
                            if ($outputError == "") {
                              $outputError = "Failed to insert day: ".mysqli_error($linkBD);
                            } else {
                              $outputError = $outputError."<br>Failed to insert day: ".mysqli_error($linkBD);
                            }
                          }
                        }
                        $sqlCheckDtbHours = $linkBD->query("SELECT * FROM settings WHERE name='date-of-call-for-fees-time-hours'");
                        if ($sqlCheckDtbHours->num_rows > 0) {
                          $sqlUpdateHours = "UPDATE settings SET value='$hours' WHERE name='date-of-call-for-fees-time-hours'";
                          if (!mysqli_query($linkBD, $sqlUpdateHours)) {
                            if ($outputError == "") {
                              $outputError = "Failed to update hours: ".mysqli_error($linkBD);
                            } else {
                              $outputError = $outputError."<br>Failed to update hours: ".mysqli_error($linkBD);
                            }
                          }
                        } else {
                          $sqlInsertHours = "INSERT INTO settings (name, value, type) VALUES ('date-of-call-for-fees-time-hours', '$hours', 'txt')";
                          if (!mysqli_query($linkBD, $sqlInsertHours)) {
                            if ($outputError == "") {
                              $outputError = "Failed to insert hours: ".mysqli_error($linkBD);
                            } else {
                              $outputError = $outputError."<br>Failed to insert hours: ".mysqli_error($linkBD);
                            }
                          }
                        }
                        $sqlCheckDtbMinutes = $linkBD->query("SELECT * FROM settings WHERE name='date-of-call-for-fees-time-minutes'");
                        if ($sqlCheckDtbMinutes->num_rows > 0) {
                          $sqlUpdateMinutes = "UPDATE settings SET value='$minutes' WHERE name='date-of-call-for-fees-time-minutes'";
                          if (!mysqli_query($linkBD, $sqlUpdateMinutes)) {
                            if ($outputError == "") {
                              $outputError = "Failed to update minutes".mysqli_error($linkBD);
                            } else {
                              $outputError = $outputError."<br>Failed to update minutes".mysqli_error($linkBD);
                            }
                          }
                        } else {
                          $sqlInsertMinutes = "INSERT INTO settings (name, value, type) VALUES ('date-of-call-for-fees-time-minutes', '$minutes', 'txt')";
                          if (!mysqli_query($linkBD, $sqlInsertMinutes)) {
                            if ($outputError == "") {
                              $outputError = "Failed to insert minutes".mysqli_error($linkBD);
                            } else {
                              $outputError = $outputError."<br>Failed to insert minutes".mysqli_error($linkBD);
                            }
                          }
                        }
                        if ($outputError == "") {
                          done();
                        } else {
                          error($outputError);
                        }
                      } else {
                        error("Minutes value is not in range of 0-60");
                      }
                    } else {
                      error("Hours value is not in range of 0-24");
                    }
                  } else {
                    error("Day value is not in range of 1-31");
                  }
                } else {
                  error("Minutes value cannot be in deciaml format");
                }
              } else {
                error("Hours value cannot be in deciaml format");
              }
            } else {
              error("Day value cannot be in deciaml format");
            }
          } else {
            error("Invalid format of the minutes (only numbers allowed)");
          }
        } else {
          error("Invalid format of the hours (only numbers allowed)");
        }
      } else {
        error("Invalid format of the day (only numbers allowed)");
      }
    } else {
      error($backDoorCheckSignInSts);
    }
  } else {
    error("Failed to format time into hours and minutes");
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
