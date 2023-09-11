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
  include "place-verification.php";
  include "check-booking-term-availability.php";
  include "get-booking-edit-task.php";
  include "get-technical-shutdown-edit-task.php";
  include "calendar-changes-manager.php";
  include "edit-booking/add-booking.php";
  include "edit-booking/cancel-booking.php";
  include "edit-booking/update-booking.php";
  include "edit-technical-shutdown/add-technical-shutdown.php";
  include "edit-technical-shutdown/cancel-technical-shutdown.php";
  include "edit-technical-shutdown/update-technical-shutdown.php";
  header('Content-Type: application/json');
  $output = [];
  $unavailableBecauseOfBookingSchedule = [];
  $unavailableBecauseOfTechnicalShutdownSchedule = [];
  $editTasksOutputArray = [];
  $numOfUnavailable = 0;
  $getBookingEditTaskErrorTxt = "";
  $getTechnicalShutdownEditTaskErrorTxt = "";
  $doneOfUnavailable = 0;
  $connectTSCancelTotal = 0;
  $updatedTechnicalShutdownBeID = "";
  $connectTSCancelDone = 0;
  $connectTSCancelFailed = 0;
  $connectTSCancelFailedTxt = "";
  $connectTechnicalShutdownCancelCheckReady = true;
  $verificated_to_make_changes = $_POST['verificated_to_make_changes'];
  $url_id = mysqli_real_escape_string($link, $_POST['plc_id']);
  $ts_category = mysqli_real_escape_string($link, $_POST['category']);
  $ts_notes = mysqli_real_escape_string($link, $_POST['notes']);
  $f_d = mysqli_real_escape_string($link, $_POST['f_d']);
  $f_m = mysqli_real_escape_string($link, $_POST['f_m']);
  $f_y = mysqli_real_escape_string($link, $_POST['f_y']);
  $firstDay = $_POST['fromAvailability'];
  $t_d = mysqli_real_escape_string($link, $_POST['t_d']);
  $t_m = mysqli_real_escape_string($link, $_POST['t_m']);
  $t_y = mysqli_real_escape_string($link, $_POST['t_y']);
  $lastDay = $_POST['toAvailability'];
  $placeVerify = placeVerification($url_id);
  if ($placeVerify == "good") {
    if ($f_y."-".$f_m."-".$f_d != $t_y."-".$t_m."-".$t_d) {
      if (new DateTime($f_y."-".$f_m."-".$f_d) < new DateTime($t_y."-".$t_m."-".$t_d)) {
        $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$url_id' LIMIT 1");
        $beId = $sqlIdToBeId->fetch_assoc()["beid"];
        checkBookingTermAvailability($beId, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay);
      } else {
        error("Dates are in wrong order");
      }
    } else {
      error("From and To dates are same");
    }
  } else {
    error($placeVerify);
  }

  function checkBookingTermAvailabilityOutput($sts, $txt, $type) {
    global $beId, $ts_category, $ts_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay, $unavailableBecauseOfBookingSchedule, $unavailableBecauseOfTechnicalShutdownSchedule, $numOfUnavailable;
    if ($sts == "available") {
      addTechnicalShutdown($beId, $ts_category, $ts_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay);
    } elseif ($sts == "unavailable") {
      if ($type == "booking") {
        if (!in_array($txt, $unavailableBecauseOfBookingSchedule)) {
          array_push($unavailableBecauseOfBookingSchedule, $txt);
        }
      } elseif ($type == "technical-shutdown") {
        if (!in_array($txt, $unavailableBecauseOfTechnicalShutdownSchedule)) {
          array_push($unavailableBecauseOfTechnicalShutdownSchedule, $txt);
        }
      }
    } elseif ($sts == "loop-unavailable") {
      $numOfUnavailable = sizeof($unavailableBecauseOfBookingSchedule) + sizeof($unavailableBecauseOfTechnicalShutdownSchedule);
      foreach ($unavailableBecauseOfBookingSchedule as $blockingBooking) {
        getBookingEditTask($blockingBooking, "technical-shutdown", "", "", "", "", "", $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay, "", "");
      }
      foreach ($unavailableBecauseOfTechnicalShutdownSchedule as $blockingTechnicalShutdown) {
        getTechnicalShutdownEditTask($blockingTechnicalShutdown, "technical-shutdown", $ts_category, $ts_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay);
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

  function addTechnicalShutdownOutput($type, $txt) {
    global $verificated_to_make_changes;
    if ($verificated_to_make_changes == "no") {
      if ($type == "done") {
        done();
      } else {
        error($txt);
      }
    } else {
      if (contentChangesCheck()) {
        if ($type == "done") {
          done();
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
        done();
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

  function cancelBookingOutput($type, $txt) {
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

  function updateBookingOutput($type, $txt) {
    if (contentChangesCheck()) {
      if ($type == "done") {
        done();
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

  function addBookingOutput($type, $txt) {
    if (contentChangesCheck()) {
      if ($type == "done") {
        done();
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

  function performMainTask() {
    global $beId, $ts_category, $ts_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay;
    addTechnicalShutdown($beId, $ts_category, $ts_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay);
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
        done();
      } else {
        if (contentChangesCheck()) {
          if ($sts != "done") {
            array_push($output, [
              "type" => "error",
              "error" => "(".$mailType.") - mail failed"
            ]);
          }
          done();
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

  function connectTechnicalShutdown($listOfTechnicalShutdownsToConnect, $e_fD, $e_fM, $e_fY, $e_tD, $e_tM, $e_tY, $e_firstDay, $e_lastDay) {
    global $link, $updatedTechnicalShutdownBeID, $connectTSCancelTotal, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay;
    $f_d = $e_fD;
    $f_m = $e_fM;
    $f_y = $e_fY;
    $firstDay = $e_firstDay;
    $t_d = $e_tD;
    $t_m = $e_tM;
    $t_y = $e_tY;
    $lastDay = $e_lastDay;
    $updatedTechnicalShutdownBeID = $listOfTechnicalShutdownsToConnect[0];
    array_splice($listOfTechnicalShutdownsToConnect, 0, 1);
    $connectTSCancelTotal = sizeof($listOfTechnicalShutdownsToConnect);
    for ($tsTC=0; $tsTC < $connectTSCancelTotal; $tsTC++) {
      $deleteTechnicalShutdownBeID = $listOfTechnicalShutdownsToConnect[$tsTC];
      $sqlAboutTechnicalShutdown = $link->query("SELECT * FROM technicalshutdown WHERE beid='$deleteTechnicalShutdownBeID' LIMIT 1");
      $aboutTechnicalShutdown = $sqlAboutTechnicalShutdown->fetch_assoc();
      cancelTechnicalShutdown(
        $aboutTechnicalShutdown['plcbeid'],
        $aboutTechnicalShutdown['fromd'],
        $aboutTechnicalShutdown['fromm'],
        $aboutTechnicalShutdown['fromy'],
        $aboutTechnicalShutdown['tod'],
        $aboutTechnicalShutdown['tom'],
        $aboutTechnicalShutdown['toy']
      );
    }
    connectTechnicalShutdownCancelHandler();
  }

  function connectTechnicalShutdownCancelDone() {
    global $connectTSCancelDone;
    ++$connectTSCancelDone;
  }

  function connectTechnicalShutdownCancelFailed($txt) {
    global $connectTSCancelFailed, $connectTSCancelFailedTxt;
    $connectTSCancelFailed = $connectTSCancelFailedTxt."(connect technical shutdown cancelation failed) ".$txt."<br>";
    ++$connectTSCancelFailed;
  }

  function connectTechnicalShutdownCancelHandler() {
    global $connectTechnicalShutdownCancelCheckReady, $connectTSCancelFailedTxt, $updatedTechnicalShutdownBeID, $beId, $ts_category, $ts_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay;
    if (connectTechnicalShutdownCancelCheck() && $connectTechnicalShutdownCancelCheckReady) {
      $connectTechnicalShutdownCancelCheckReady = false;
      if ($connectTSCancelFailedTxt == "") {
        updateTechnicalShutdown($updatedTechnicalShutdownBeID, $beId, $ts_category, $ts_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay);
      } else {
        error($connectTSCancelFailedTxt);
      }
    }
  }

  function connectTechnicalShutdownCancelCheck() {
    global $connectTSCancelTotal, $connectTSCancelDone, $connectTSCancelFailed;
    if ($connectTSCancelDone + $connectTSCancelFailed == $connectTSCancelTotal) {
      return true;
    } else {
      return false;
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
