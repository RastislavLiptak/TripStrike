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
  $connectBCancelTotal = 0;
  $updatedBookingBeID = "";
  $connectBCancelDone = 0;
  $connectBCancelFailed = 0;
  $connectBCancelFailedTxt = "";
  $connectBookingCancelCheckReady = true;
  $verificated_to_make_changes = $_POST['verificated_to_make_changes'];
  $url_id = mysqli_real_escape_string($link, $_POST['plc_id']);
  $g_name = mysqli_real_escape_string($link, $_POST['name']);
  $g_email = mysqli_real_escape_string($link, $_POST['email']);
  $g_phone = mysqli_real_escape_string($link, str_replace("plus", "+", $_POST['phone']));
  $g_guest = mysqli_real_escape_string($link, $_POST['guest']);
  $g_notes = mysqli_real_escape_string($link, $_POST['notes']);
  $f_d = mysqli_real_escape_string($link, $_POST['f_d']);
  $f_m = mysqli_real_escape_string($link, $_POST['f_m']);
  $f_y = mysqli_real_escape_string($link, $_POST['f_y']);
  $firstDay = $_POST['fromAvailability'];
  $t_d = mysqli_real_escape_string($link, $_POST['t_d']);
  $t_m = mysqli_real_escape_string($link, $_POST['t_m']);
  $t_y = mysqli_real_escape_string($link, $_POST['t_y']);
  $lastDay = $_POST['toAvailability'];
  $deposit = $_POST['deposit'];
  $fullAmount = $_POST['fullAmount'];
  if ($firstDay != "whole") {
    $firstDay = "half";
  }
  if ($lastDay != "whole") {
    $lastDay = "half";
  }
  if ($fullAmount == "1") {
    $fullAmount = 1;
    $deposit = 1;
  } else {
    $fullAmount = 0;
  }
  if ($deposit == "1") {
    $deposit = 1;
  } else {
    $deposit = 0;
  }
  $placeVerify = placeVerification($url_id);
  if ($placeVerify == "good") {
    if ($f_y."-".$f_m."-".$f_d != $t_y."-".$t_m."-".$t_d) {
      if (new DateTime($f_y."-".$f_m."-".$f_d) < new DateTime($t_y."-".$t_m."-".$t_d)) {
        if (!preg_match("/[^0-9\s+-\/]/", $g_phone) || $g_phone == "" || $g_phone == "-") {
          $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$url_id' LIMIT 1");
          $beId = $sqlIdToBeId->fetch_assoc()["beid"];
          $sqlPlc = $link->query("SELECT guestNum FROM places WHERE beid='$beId' and status='active' LIMIT 1");
          $rowPlc = $sqlPlc->fetch_assoc();
          $g_guest = intval($g_guest);
          if ($g_guest > 0) {
            if ($g_guest <= intval($rowPlc["guestNum"])) {
              checkBookingTermAvailability($beId, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay);
            } else {
              error("Number of guests is too high");
            }
          } else {
            error("Number of guests is too low");
          }
        } else {
          error("Phone number invalid");
        }
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
    global $beId, $g_name, $g_email, $g_phone, $g_guest, $g_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay, $unavailableBecauseOfBookingSchedule, $unavailableBecauseOfTechnicalShutdownSchedule, $numOfUnavailable, $deposit, $fullAmount;
    if ($sts == "available") {
      addBooking($beId, $g_name, $g_email, $g_phone, $g_guest, $g_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay, $deposit, $fullAmount, 0, "editor", 0, true);
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
        getBookingEditTask($blockingBooking, "booking", $g_name, $g_email, $g_phone, $g_guest, $g_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay, $deposit, $fullAmount);
      }
      foreach ($unavailableBecauseOfTechnicalShutdownSchedule as $blockingTechnicalShutdown) {
        getTechnicalShutdownEditTask($blockingTechnicalShutdown, "booking", "", "", $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay);
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
    global $beId, $g_name, $g_email, $g_phone, $g_guest, $g_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay, $deposit, $fullAmount;
    addBooking($beId, $g_name, $g_email, $g_phone, $g_guest, $g_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay, $deposit, $fullAmount, 0, "editor", 0, true);
  }

  function connectBooking($listOfBookingsToConnect, $e_fD, $e_fM, $e_fY, $e_tD, $e_tM, $e_tY, $e_firstDay, $e_lastDay) {
    global $link, $updatedBookingBeID, $connectBCancelTotal, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay;
    $f_d = $e_fD;
    $f_m = $e_fM;
    $f_y = $e_fY;
    $firstDay = $e_firstDay;
    $t_d = $e_tD;
    $t_m = $e_tM;
    $t_y = $e_tY;
    $lastDay = $e_lastDay;
    $updatedBookingBeID = $listOfBookingsToConnect[0];
    array_splice($listOfBookingsToConnect, 0, 1);
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
    global $connectBookingCancelCheckReady, $connectBCancelFailedTxt, $updatedBookingBeID, $beId, $g_name, $g_email, $g_phone, $g_guest, $g_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay, $deposit, $fullAmount;
    if (connectBookingCancelCheck() && $connectBookingCancelCheckReady) {
      $connectBookingCancelCheckReady = false;
      if ($connectBCancelFailedTxt == "") {
        updateBooking($updatedBookingBeID, $beId, $g_name, $g_email, $g_phone, $g_guest, $g_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay, $deposit, $fullAmount, true);
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

  function addTechnicalShutdownOutput($type, $txt) {
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
