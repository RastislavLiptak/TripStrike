<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-user.php";
  include "../../email-sender/php-backend/send-mail.php";
  include "../../email-sender/php-backend/host-rejected-the-booking-mail.php";
  include "../../uni/code/php-backend/add-currency.php";
  include "../../uni/code/php-backend/calendar/calc-date.php";
  include "../../uni/code/php-backend/random-hash-maker.php";
  include "../../uni/code/php-backend/total-price-calculator.php";
  include "../../bookings/php-backend/reject-booking.php";
  include "../../editor/php-backend/place-verification.php";
  include "../../editor/php-backend/check-booking-term-availability.php";
  include "../../editor/php-backend/get-booking-edit-task.php";
  include "../../editor/php-backend/get-technical-shutdown-edit-task.php";
  include "../../editor/php-backend/calendar-changes-manager.php";
  include "../../editor/php-backend/edit-booking/add-booking.php";
  include "../../editor/php-backend/edit-booking/cancel-booking.php";
  include "../../editor/php-backend/edit-booking/update-booking.php";
  include "../../editor/php-backend/edit-technical-shutdown/add-technical-shutdown.php";
  include "../../editor/php-backend/edit-technical-shutdown/cancel-technical-shutdown.php";
  include "../../editor/php-backend/edit-technical-shutdown/update-technical-shutdown.php";
  header('Content-Type: application/json');
  $output = [];
  $unavailableBecauseOfBookingSchedule = [];
  $unavailableBecauseOfTechnicalShutdownSchedule = [];
  $editTasksOutputArray = [];
  $numOfUnavailable = 0;
  $getBookingEditTaskErrorTxt = "";
  $getTechnicalShutdownEditTaskErrorTxt = "";
  $doneOfUnavailable = 0;
  $connectBCancelTotal = 0;
  $connectBCancelDone = 0;
  $connectBCancelFailed = 0;
  $connectBCancelFailedTxt = "";
  $connectBookingCancelCheckReady = true;
  $verificated_to_make_changes = $_POST['verificated_to_make_changes'];
  $plcID = mysqli_real_escape_string($link, $_POST['plc_id']);
  $reqID = mysqli_real_escape_string($link, $_POST['req_id']);
  $sqlPlcIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$plcID' LIMIT 1");
  if ($sqlPlcIdToBeId->num_rows > 0) {
    $plcBeId = $sqlPlcIdToBeId->fetch_assoc()["beid"];
    $sqlPlaces = $link->query("SELECT * FROM places WHERE beid='$plcBeId' LIMIT 1");
    $plc = $sqlPlaces->fetch_assoc();
    $hostBeId = $plc['usrbeid'];
    $sqlHost = $link->query("SELECT * FROM users WHERE beid='$hostBeId' and status='active' LIMIT 1");
    $hst = $sqlHost->fetch_assoc();
    $sqlReqIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$reqID' LIMIT 1");
    if ($sqlReqIdToBeId->num_rows > 0) {
      $reqBeId = $sqlReqIdToBeId->fetch_assoc()["beid"];
      $sqlGetRequest = $link->query("SELECT * FROM bookingupdaterequest WHERE beid='$reqBeId'");
      if ($sqlGetRequest->num_rows > 0) {
        $requestRow = $sqlGetRequest->fetch_assoc();
        if ($requestRow['status'] == "ready") {
          $bookingBeId = $requestRow['bookingbeid'];
          $sqlGetBooking = $link->query("SELECT * FROM booking WHERE beid='$bookingBeId'");
          if ($sqlGetBooking->num_rows > 0) {
            $bookingRow = $sqlGetBooking->fetch_assoc();
            if ($bookingRow['status'] == "booked" || $bookingRow['status'] == "waiting") {
              if ($bookingRow['plcbeid'] == $plcBeId) {
                $g_name = $bookingRow['name'];
                $g_email = $bookingRow['email'];
                $g_phone = $bookingRow['phonenum'];
                $g_notes = $bookingRow['notes'];
                if ($requestRow['type'] == "date") {
                  $g_guest = $bookingRow['guestnum'];
                  $f_d = $requestRow['fromd'];
                  $f_m = $requestRow['fromm'];
                  $f_y = $requestRow['fromy'];
                  $firstDay = $requestRow['firstday'];
                  $t_d = $requestRow['tod'];
                  $t_m = $requestRow['tom'];
                  $t_y = $requestRow['toy'];
                  $lastDay = $requestRow['lastday'];
                } else if ($requestRow['type'] == "guests") {
                  $g_guest = $requestRow['guestNum'];
                  $f_d = $bookingRow['fromd'];
                  $f_m = $bookingRow['fromm'];
                  $f_y = $bookingRow['fromy'];
                  $firstDay = $bookingRow['firstday'];
                  $t_d = $bookingRow['tod'];
                  $t_m = $bookingRow['tom'];
                  $t_y = $bookingRow['toy'];
                  $lastDay = $bookingRow['lastday'];
                }
                $deposit = $bookingRow['deposit'];
                $fullAmount = $bookingRow['fullAmount'];
                checkBookingTermAvailability($plcBeId, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay);
              } else {
                error("place-of-the-booking-not-matches-with-place-id-from-url");
              }
            } else {
              error("booking-not-active");
            }
          } else {
            error("booking-not-found");
          }
        } else {
          if ($requestRow['status'] == "canceled") {
            error("request-already-canceled");
          } else if ($requestRow['status'] == "confirmed") {
            error("request-already-confirmed");
          } elseif ($requestRow['status'] == "rejected") {
            error("request-already-rejected");
          } else {
            error("request-status: ".$requestRow['status']);
          }
        }
      } else {
        error("request-details-not-found");
      }
    } else {
      error("request-id-not-connected-to-backend");
    }
  } else {
    error("place-id-not-connected-to-backend");
  }

  function checkBookingTermAvailabilityOutput($sts, $txt, $type) {
    global $plcBeId, $bookingBeId, $g_name, $g_email, $g_phone, $g_guest, $g_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay, $unavailableBecauseOfBookingSchedule, $unavailableBecauseOfTechnicalShutdownSchedule, $numOfUnavailable, $deposit, $fullAmount;
    if ($sts == "available") {
      performMainTask();
    } elseif ($sts == "unavailable") {
      if ($type == "booking") {
        if ($bookingBeId != $txt && !in_array($txt, $unavailableBecauseOfBookingSchedule)) {
          array_push($unavailableBecauseOfBookingSchedule, $txt);
        }
      } elseif ($type == "technical-shutdown") {
        if (!in_array($txt, $unavailableBecauseOfTechnicalShutdownSchedule)) {
          array_push($unavailableBecauseOfTechnicalShutdownSchedule, $txt);
        }
      }
    } elseif ($sts == "loop-unavailable") {
      $numOfUnavailable = sizeof($unavailableBecauseOfBookingSchedule) + sizeof($unavailableBecauseOfTechnicalShutdownSchedule);
      if ($numOfUnavailable > 0) {
        foreach ($unavailableBecauseOfBookingSchedule as $blockingBooking) {
          getBookingEditTask($blockingBooking, "booking", $g_name, $g_email, $g_phone, $g_guest, $g_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay, $deposit, $fullAmount);
        }
        foreach ($unavailableBecauseOfTechnicalShutdownSchedule as $blockingTechnicalShutdown) {
          getTechnicalShutdownEditTask($blockingTechnicalShutdown, "booking", "", "", $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay);
        }
      } else {
        checkBookingTermAvailabilityOutput("available", "good", $type);
      }
    } else {
      error($txt);
    }
  }

  function getBookingEditTaskOutput($type, $txt, $tsk) {
    global $getBookingEditTaskErrorTxt, $getTechnicalShutdownEditTaskErrorTxt, $numOfUnavailable, $doneOfUnavailable, $verificated_to_make_changes, $editTasksOutputArray, $f_d, $f_m, $f_y, $t_d, $t_m, $t_y, $firstDay, $lastDay;
    ++$doneOfUnavailable;
    if ($type == "task") {
      array_push($editTasksOutputArray, [
        "type" => "booking",
        "bookingBeID" => $txt,
        "task" => $tsk
      ]);
    } else {
      $getBookingEditTaskErrorTxt = $txt."<br>";
    }
    if ($doneOfUnavailable == $numOfUnavailable) {
      if ($getBookingEditTaskErrorTxt == "" && $getTechnicalShutdownEditTaskErrorTxt == "") {
        if ($verificated_to_make_changes == "no") {
          alertAboutNeededChanges($editTasksOutputArray);
        } else {
          calendarChangesManager($editTasksOutputArray, $f_d, $f_m, $f_y, $t_d, $t_m, $t_y, $firstDay, $lastDay);
        }
      } else {
        if ($getBookingEditTaskErrorTxt != "" && $getTechnicalShutdownEditTaskErrorTxt != "") {
          error($getBookingEditTaskErrorTxt."<br>".$getTechnicalShutdownEditTaskErrorTxt);
        } else if ($getBookingEditTaskErrorTxt != "") {
          error($getBookingEditTaskErrorTxt);
        } else if ($getTechnicalShutdownEditTaskErrorTxt != "") {
          error($getTechnicalShutdownEditTaskErrorTxt);
        }
      }
    }
  }

  function getTechnicalShutdownEditTaskOutput($type, $txt, $tsk) {
    global $getBookingEditTaskErrorTxt, $getTechnicalShutdownEditTaskErrorTxt, $numOfUnavailable, $doneOfUnavailable, $verificated_to_make_changes, $editTasksOutputArray, $f_d, $f_m, $f_y, $t_d, $t_m, $t_y, $firstDay, $lastDay;
    ++$doneOfUnavailable;
    if ($type == "task") {
      array_push($editTasksOutputArray, [
        "type" => "technical-shutdown",
        "technicalShutdownBeID" => $txt,
        "task" => $tsk
      ]);
    } else {
      $getTechnicalShutdownEditTaskErrorTxt = $txt."<br>";
    }
    if ($doneOfUnavailable == $numOfUnavailable) {
      if ($getBookingEditTaskErrorTxt == "" && $getTechnicalShutdownEditTaskErrorTxt == "") {
        if ($verificated_to_make_changes == "no") {
          alertAboutNeededChanges($editTasksOutputArray);
        } else {
          calendarChangesManager($editTasksOutputArray, $f_d, $f_m, $f_y, $t_d, $t_m, $t_y, $firstDay, $lastDay);
        }
      } else {
        if ($getBookingEditTaskErrorTxt != "" && $getTechnicalShutdownEditTaskErrorTxt != "") {
          error($getBookingEditTaskErrorTxt."<br>".$getTechnicalShutdownEditTaskErrorTxt);
        } else if ($getBookingEditTaskErrorTxt != "") {
          error($getBookingEditTaskErrorTxt);
        } else if ($getTechnicalShutdownEditTaskErrorTxt != "") {
          error($getTechnicalShutdownEditTaskErrorTxt);
        }
      }
    }
  }

  function alertAboutNeededChanges($editTasksOutputArray) {
    global $link, $output;
    for ($aC = 0;$aC < sizeof($editTasksOutputArray);$aC++) {
      if ($editTasksOutputArray[$aC]['type'] == "booking") {
        $bookingBeId = $editTasksOutputArray[$aC]['bookingBeID'];
        $sqlAboutBooking = $link->query("SELECT * FROM booking WHERE beid='$bookingBeId' LIMIT 1");
        $aboutBooking = $sqlAboutBooking->fetch_assoc();
        if ($aboutBooking['status'] == "booked") {
          array_push($output, [
            "type" => "permission-needed",
            "data" => "booking",
            "task" => $editTasksOutputArray[$aC]['task'],
            "name" => $aboutBooking['name'],
            "email" => $aboutBooking['email'],
            "phone" => $aboutBooking['phonenum'],
            "from" => $aboutBooking['fromd'].".".$aboutBooking['fromm'].".".$aboutBooking['fromy'],
            "to" => $aboutBooking['tod'].".".$aboutBooking['tom'].".".$aboutBooking['toy']
          ]);
        } else {
          array_push($output, [
            "type" => "permission-needed",
            "data" => "booking",
            "task" => $editTasksOutputArray[$aC]['task'],
            "name" => "-",
            "email" => "-",
            "phone" => "-",
            "from" => $aboutBooking['fromd'].".".$aboutBooking['fromm'].".".$aboutBooking['fromy'],
            "to" => $aboutBooking['tod'].".".$aboutBooking['tom'].".".$aboutBooking['toy']
          ]);
        }
      } else if ($editTasksOutputArray[$aC]['type'] == "technical-shutdown") {
        $technicalShutdownBeId = $editTasksOutputArray[$aC]['technicalShutdownBeID'];
        $sqlAboutTechnicalShutdown = $link->query("SELECT * FROM technicalshutdown WHERE beid='$technicalShutdownBeId' LIMIT 1");
        $aboutTechnicalShutdown = $sqlAboutTechnicalShutdown->fetch_assoc();
        array_push($output, [
          "type" => "permission-needed",
          "data" => "technical-shutdown",
          "task" => $editTasksOutputArray[$aC]['task'],
          "category" => $aboutTechnicalShutdown['category'],
          "notes" => $aboutTechnicalShutdown['notes'],
          "from" => $aboutTechnicalShutdown['fromd'].".".$aboutTechnicalShutdown['fromm'].".".$aboutTechnicalShutdown['fromy'],
          "to" => $aboutTechnicalShutdown['tod'].".".$aboutTechnicalShutdown['tom'].".".$aboutTechnicalShutdown['toy']
        ]);
      }
    }
    returnOutput();
  }

  function performMainTask() {
    global $bookingBeId, $plcBeId, $g_name, $g_email, $g_phone, $g_guest, $g_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay, $deposit, $fullAmount;
    updateBooking($bookingBeId, $plcBeId, $g_name, $g_email, $g_phone, $g_guest, $g_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay, $deposit, $fullAmount, true);
  }

  function connectBooking($listOfBookingsToConnect, $e_fD, $e_fM, $e_fY, $e_tD, $e_tM, $e_tY, $e_firstDay, $e_lastDay) {
    global $link, $connectBCancelTotal, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay;
    $f_d = $e_fD;
    $f_m = $e_fM;
    $f_y = $e_fY;
    $firstDay = $e_firstDay;
    $t_d = $e_tD;
    $t_m = $e_tM;
    $t_y = $e_tY;
    $lastDay = $e_lastDay;
    $connectBCancelTotal = sizeof($listOfBookingsToConnect);
    for ($bTC=0; $bTC < $connectBCancelTotal; $bTC++) {
      $deleteBookingBeID = $listOfBookingsToConnect[$bTC];
      $sqlAboutBooking = $link->query("SELECT * FROM booking WHERE beid='$deleteBookingBeID' LIMIT 1");
      $aboutBooking = $sqlAboutBooking->fetch_assoc();
      cancelBooking(
        $aboutBooking['plcbeid'],
        $aboutBooking['fromd'],
        $aboutBooking['fromm'],
        $aboutBooking['fromy'],
        $aboutBooking['tod'],
        $aboutBooking['tom'],
        $aboutBooking['toy'],
        "",
        "",
        "",
        "booking",
        false
      );
    }
    connectBookingCancelHandler();
  }

  function connectBookingCancelDone() {
    global $connectBCancelDone;
    ++$connectBCancelDone;
  }

  function connectBookingCancelFailed($txt) {
    global $connectBCancelFailed, $connectBCancelFailedTxt;
    $connectBCancelFailed = $connectBCancelFailedTxt."(connect booking cancelation failed) ".$txt."<br>";
    ++$connectBCancelFailed;
  }

  function connectBookingCancelHandler() {
    global $connectBookingCancelCheckReady, $connectBCancelFailedTxt, $bookingBeId, $plcBeId, $g_name, $g_email, $g_phone, $g_guest, $g_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay, $deposit, $fullAmount;
    if (connectBookingCancelCheck() && $connectBookingCancelCheckReady) {
      $connectBookingCancelCheckReady = false;
      if ($connectBCancelFailedTxt == "") {
        updateBooking($bookingBeId, $plcBeId, $g_name, $g_email, $g_phone, $g_guest, $g_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay, $deposit, $fullAmount, true);
      } else {
        error($connectBCancelFailedTxt);
      }
    }
  }

  function connectBookingCancelCheck() {
    global $connectBCancelTotal, $connectBCancelDone, $connectBCancelFailed;
    if ($connectBCancelDone + $connectBCancelFailed == $connectBCancelTotal) {
      return true;
    } else {
      return false;
    }
  }

  function cancelBookingOutput($type, $txt) {
    if (contentChangesCheck()) {
      if ($type == "done") {
        connectBookingCancelDone();
      } else {
        connectBookingCancelFailed($txt);
      }
      connectBookingCancelHandler();
    } else {
      if ($type == "done") {
        anotherChangeDone();
      } else {
        anotherChangefailed($txt);
      }
      contentChangesHandler();
    }
  }

  function updateBookingOutput($type, $txt) {
    global $verificated_to_make_changes;
    if ($verificated_to_make_changes == "no") {
      if ($type == "done") {
        confirmBookingUpdateRequest();
      } else {
        error($txt);
      }
    } else {
      if (contentChangesCheck()) {
        if ($type == "done") {
          confirmBookingUpdateRequest();
        } else {
          error($txt);
        }
      } else {
        if ($type == "done") {
          anotherChangeDone();
        } else {
          anotherChangefailed($txt);
        }
        contentChangesHandler();
      }
    }
  }

  function addBookingOutput($type, $txt) {
    if (contentChangesCheck()) {
      if ($type == "done") {
        confirmBookingUpdateRequest();
      } else {
        error($txt);
      }
    } else {
      if ($type == "done") {
        anotherChangeDone();
      } else {
        anotherChangefailed($txt);
      }
      contentChangesHandler();
    }
  }

  function addTechnicalShutdownOutput($type, $txt) {
    if (contentChangesCheck()) {
      if ($type == "done") {
        confirmBookingUpdateRequest();
      } else {
        error($txt);
      }
    } else {
      if ($type == "done") {
        anotherChangeDone();
      } else {
        anotherChangefailed($txt);
      }
      contentChangesHandler();
    }
  }

  function cancelTechnicalShutdownOutput($type, $txt) {
    if (contentChangesCheck()) {
      if ($type == "done") {
        connectTechnicalShutdownCancelDone();
      } else {
        connectTechnicalShutdownCancelFailed($txt);
      }
      connectTechnicalShutdownCancelHandler();
    } else {
      if ($type == "done") {
        anotherChangeDone();
      } else {
        anotherChangefailed($txt);
      }
      contentChangesHandler();
    }
  }

  function updateTechnicalShutdownOutput($type, $txt) {
    if (contentChangesCheck()) {
      if ($type == "done") {
        confirmBookingUpdateRequest();
      } else {
        error($txt);
      }
    } else {
      if ($type == "done") {
        anotherChangeDone();
      } else {
        anotherChangefailed($txt);
      }
      contentChangesHandler();
    }
  }

  function mailDone($sts, $mailType) {
    global $output, $verificated_to_make_changes;
    if ($mailType != "pay-full-amount-mail-alert" && $mailType != "unpaid-full-amount-call-mail") {
      if ($verificated_to_make_changes == "no") {
        if ($sts != "done") {
          array_push($output, [
            "type" => "error",
            "error" => "(".$mailType.") - mail failed"
          ]);
        }
        confirmBookingUpdateRequest();
      } else {
        if (contentChangesCheck()) {
          if ($sts != "done") {
            array_push($output, [
              "type" => "error",
              "error" => "(".$mailType.") - mail failed"
            ]);
          }
          confirmBookingUpdateRequest();
        } else {
          if ($sts == "done") {
            anotherChangeDone();
          } else {
            anotherChangefailed("(".$mailType.") - mail failed");
          }
          contentChangesHandler();
        }
      }
    } else {
      if ($sts != "done") {
        array_push($output, [
          "type" => "error",
          "error" => "(".$mailType.") - mail failed"
        ]);
      }
    }
  }

  function confirmBookingUpdateRequest() {
    global $link, $reqBeId;
    $sqlConfirmRequest = "UPDATE bookingupdaterequest SET status='confirmed' WHERE beid='$reqBeId'";
    if (mysqli_query($link, $sqlConfirmRequest)) {
      done();
    } else {
      error("request-confirmation-failed: <br>".mysqli_error($link));
    }
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
