<?php
  include "../../../uni/code/php-backend/data.php";
  include "../../../uni/code/php-backend/get-frontend-id.php";
  include "../../../uni/code/php-backend/get-data-from-date.php";
  include "../../../uni/dictionary/lang-select.php";
  include "../../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/check-sign-in.php";
  include "get-booking-history.php";
  header('Content-Type: application/json');
  $output = [];
  $lastID = mysqli_real_escape_string($link, $_POST['lastID']);
  $bookingID = mysqli_real_escape_string($link, $_POST['bookingID']);
  $backDoorCheckSignInSts = backDoorCheckSignIn();
  if ($backDoorCheckSignInSts == "good") {
    $sqlBookingIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$bookingID' LIMIT 1");
    if ($sqlBookingIdToBeId->num_rows > 0) {
      $bookingBeId = $sqlBookingIdToBeId->fetch_assoc()["beid"];
      if ($lastID != "") {
        $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$lastID' LIMIT 1");
        $lastBeID = $sqlIdToBeId->fetch_assoc()["beid"];
      } else {
        $lastBeID = "";
      }
      $loadedListData = getBookingHistory("load-amount", $bookingBeId, $lastBeID);
      array_push($output, [
        "type" => "load-amount",
        "all-bookings" => $loadedListData['all-bookings'],
        "loaded" => $loadedListData['loaded'],
        "remain" => $loadedListData['remain']
      ]);
      $listOfBookingHistory = getBookingHistory("list", $bookingBeId, $lastBeID);
      for ($lOBU=0; $lOBU < sizeof($listOfBookingHistory); $lOBU++) {
        if ($listOfBookingHistory[$lOBU]['status'] == "") {
          $rowStatus = $wrd_unknown." (empty)";
        } else if ($listOfBookingHistory[$lOBU]['status'] == "-") {
          $rowStatus = $wrd_unknown." (-)";
        } else if ($listOfBookingHistory[$lOBU]['status'] == "rejected") {
          $rowStatus = $wrd_rejected;
        } else if ($listOfBookingHistory[$lOBU]['status'] == "canceled") {
          $rowStatus = $wrd_canceled;
        } else if ($listOfBookingHistory[$lOBU]['status'] == "waiting") {
          $rowStatus = $wrd_waitingForConfirmation;
        } else if ($listOfBookingHistory[$lOBU]['status'] == "booked") {
          $rowStatus = $wrd_booked;
        }
        if ($listOfAdditionalUpdates[$lOAU]['paymentStatus'] == "1") {
          $rowPaymentStatus = $wrd_paid;
        } else if ($listOfAdditionalUpdates[$lOAU]['paymentStatus'] == "0") {
          $rowPaymentStatus = $wrd_unpaid;
        }
        if ($listOfBookingHistory[$lOBU]['source'] == "booking-form") {
          $rowSource = $wrd_bookingForm;
        } else if ($listOfBookingHistory[$lOBU]['source'] == "editor") {
          $rowSource = $wrd_bookingEditor;
        } else {
          $rowSource = $listOfBookingHistory[$lOBU]['source'];
        }
        if ($listOfBookingHistory[$lOBU]['firstday'] == "whole") {
          $fromDate = $listOfBookingHistory[$lOBU]['fromD'].".".$listOfBookingHistory[$lOBU]['fromM'].".".$listOfBookingHistory[$lOBU]['fromY']." (".$wrd_theWholeDay.")";
        } else if ($listOfBookingHistory[$lOBU]['firstday'] == "half") {
          $fromDate = $listOfBookingHistory[$lOBU]['fromD'].".".$listOfBookingHistory[$lOBU]['fromM'].".".$listOfBookingHistory[$lOBU]['fromY']." (".$wrd_from." 14:00)";
        } else {
          $fromDate = $listOfBookingHistory[$lOBU]['fromD'].".".$listOfBookingHistory[$lOBU]['fromM'].".".$listOfBookingHistory[$lOBU]['fromY']." (".$listOfBookingHistory[$lOBU]['firstday'].")";
        }
        if ($listOfBookingHistory[$lOBU]['lastday'] == "whole") {
          $toDate = $listOfBookingHistory[$lOBU]['toD'].".".$listOfBookingHistory[$lOBU]['toM'].".".$listOfBookingHistory[$lOBU]['toY']." (".$wrd_theWholeDay.")";
        } else if ($listOfBookingHistory[$lOBU]['lastday'] == "half") {
          $toDate = $listOfBookingHistory[$lOBU]['toD'].".".$listOfBookingHistory[$lOBU]['toM'].".".$listOfBookingHistory[$lOBU]['toY']." (".$wrd_to." 11:00)";
        } else {
          $toDate = $listOfBookingHistory[$lOBU]['toD'].".".$listOfBookingHistory[$lOBU]['toM'].".".$listOfBookingHistory[$lOBU]['toY']." (".$listOfBookingHistory[$lOBU]['lastday'].")";
        }
        if ($listOfBookingHistory[$lOBU]['priceMode'] == "nights") {
          $rowPriceMode = $wrd_pricePerNight;
        } else {
          $rowPriceMode = $wrd_pricePerNightForOneGuest;
        }
        array_push($output, [
          "type" => "update",
          "updateID" => $listOfBookingHistory[$lOBU]['updateID'],
          "status" => $rowStatus,
          "paymentStatus" => $rowPaymentStatus,
          "plcID" => $listOfBookingHistory[$lOBU]['plcID'],
          "plcName" => $listOfBookingHistory[$lOBU]['plcName'],
          "hostID" => $listOfBookingHistory[$lOBU]['hostID'],
          "hostName" => $listOfBookingHistory[$lOBU]['hostName'],
          "guestID" => $listOfBookingHistory[$lOBU]['guestID'],
          "guestName" => $listOfBookingHistory[$lOBU]['guestName'],
          "source" => $rowSource,
          "numOfGuests" => $listOfBookingHistory[$lOBU]['numOfGuests'],
          "fromDate" => $fromDate,
          "toDate" => $toDate,
          "currency" => $listOfBookingHistory[$lOBU]['currency'],
          "priceMode" => $rowPriceMode,
          "workPrice" => $listOfBookingHistory[$lOBU]['workPrice'],
          "weekPrice" => $listOfBookingHistory[$lOBU]['weekPrice'],
          "totalPrice" => $listOfBookingHistory[$lOBU]['totalPrice'],
          "fee" => $listOfBookingHistory[$lOBU]['fee'],
          "percentageFee" => $listOfBookingHistory[$lOBU]['percentageFee'],
          "validFrom" => $listOfBookingHistory[$lOBU]['validFrom']
        ]);
      }
      returnOutput();
    } else {
      error("Booking ID not exist");
    }
  } else {
    error($backDoorCheckSignInSts);
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
