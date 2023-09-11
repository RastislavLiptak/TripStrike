<?php
  include "../../../uni/code/php-backend/data.php";
  include "../../../uni/code/php-backend/get-frontend-id.php";
  include "../../../uni/code/php-backend/get-data-from-date.php";
  include "../../../uni/dictionary/lang-select.php";
  include "../../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/check-sign-in.php";
  include "get-additional-updates.php";
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
      $loadedListData = getAdditionalUpdates("load-amount", $bookingBeId, $lastBeID);
      array_push($output, [
        "type" => "load-amount",
        "all-bookings" => $loadedListData['all-bookings'],
        "loaded" => $loadedListData['loaded'],
        "remain" => $loadedListData['remain']
      ]);
      $listOfAdditionalUpdates = getAdditionalUpdates("list", $bookingBeId, $lastBeID);
      for ($lOAU=0; $lOAU < sizeof($listOfAdditionalUpdates); $lOAU++) {
        if ($listOfAdditionalUpdates[$lOAU]['status'] == "") {
          $rowStatus = $wrd_unknown." (empty)";
        } else if ($listOfAdditionalUpdates[$lOAU]['status'] == "-") {
          $rowStatus = $wrd_unknown." (-)";
        } else if ($listOfAdditionalUpdates[$lOAU]['status'] == "rejected") {
          $rowStatus = $wrd_rejected;
        } else if ($listOfAdditionalUpdates[$lOAU]['status'] == "canceled") {
          $rowStatus = $wrd_canceled;
        } else if ($listOfAdditionalUpdates[$lOAU]['status'] == "waiting") {
          $rowStatus = $wrd_waitingForConfirmation;
        } else if ($listOfAdditionalUpdates[$lOAU]['status'] == "booked") {
          $rowStatus = $wrd_booked;
        }
        if ($listOfBookingHistory[$lOBU]['paymentStatus'] == "1") {
          $rowPaymentStatus = $wrd_paid;
        } else if ($listOfBookingHistory[$lOBU]['paymentStatus'] == "0") {
          $rowPaymentStatus = $wrd_unpaid;
        }
        if ($listOfAdditionalUpdates[$lOAU]['source'] == "booking-form") {
          $rowSource = $wrd_bookingForm;
        } else if ($listOfAdditionalUpdates[$lOAU]['source'] == "editor") {
          $rowSource = $wrd_bookingEditor;
        } else {
          $rowSource = $listOfAdditionalUpdates[$lOAU]['source'];
        }
        if ($listOfAdditionalUpdates[$lOAU]['firstday'] == "whole") {
          $fromDate = $listOfAdditionalUpdates[$lOAU]['fromD'].".".$listOfAdditionalUpdates[$lOAU]['fromM'].".".$listOfAdditionalUpdates[$lOAU]['fromY']." (".$wrd_theWholeDay.")";
        } else if ($listOfAdditionalUpdates[$lOAU]['firstday'] == "half") {
          $fromDate = $listOfAdditionalUpdates[$lOAU]['fromD'].".".$listOfAdditionalUpdates[$lOAU]['fromM'].".".$listOfAdditionalUpdates[$lOAU]['fromY']." (".$wrd_from." 14:00)";
        } else {
          $fromDate = $listOfAdditionalUpdates[$lOAU]['fromD'].".".$listOfAdditionalUpdates[$lOAU]['fromM'].".".$listOfAdditionalUpdates[$lOAU]['fromY']." (".$listOfAdditionalUpdates[$lOAU]['firstday'].")";
        }
        if ($listOfAdditionalUpdates[$lOAU]['lastday'] == "whole") {
          $toDate = $listOfAdditionalUpdates[$lOAU]['toD'].".".$listOfAdditionalUpdates[$lOAU]['toM'].".".$listOfAdditionalUpdates[$lOAU]['toY']." (".$wrd_theWholeDay.")";
        } else if ($listOfAdditionalUpdates[$lOAU]['lastday'] == "half") {
          $toDate = $listOfAdditionalUpdates[$lOAU]['toD'].".".$listOfAdditionalUpdates[$lOAU]['toM'].".".$listOfAdditionalUpdates[$lOAU]['toY']." (".$wrd_to." 11:00)";
        } else {
          $toDate = $listOfAdditionalUpdates[$lOAU]['toD'].".".$listOfAdditionalUpdates[$lOAU]['toM'].".".$listOfAdditionalUpdates[$lOAU]['toY']." (".$listOfAdditionalUpdates[$lOAU]['lastday'].")";
        }
        if ($listOfAdditionalUpdates[$lOAU]['priceMode'] == "nights") {
          $rowPriceMode = $wrd_pricePerNight;
        } else {
          $rowPriceMode = $wrd_pricePerNightForOneGuest;
        }
        array_push($output, [
          "type" => "update",
          "updateID" => $listOfAdditionalUpdates[$lOAU]['updateID'],
          "status" => $rowStatus,
          "paymentStatus" => $rowPaymentStatus,
          "plcID" => $listOfAdditionalUpdates[$lOAU]['plcID'],
          "plcName" => $listOfAdditionalUpdates[$lOAU]['plcName'],
          "hostID" => $listOfAdditionalUpdates[$lOAU]['hostID'],
          "hostName" => $listOfAdditionalUpdates[$lOAU]['hostName'],
          "guestID" => $listOfAdditionalUpdates[$lOAU]['guestID'],
          "guestName" => $listOfAdditionalUpdates[$lOAU]['guestName'],
          "source" => $rowSource,
          "numOfGuests" => $listOfAdditionalUpdates[$lOAU]['numOfGuests'],
          "fromDate" => $fromDate,
          "toDate" => $toDate,
          "currency" => $listOfAdditionalUpdates[$lOAU]['currency'],
          "priceMode" => $rowPriceMode,
          "workPrice" => $listOfAdditionalUpdates[$lOAU]['workPrice'],
          "weekPrice" => $listOfAdditionalUpdates[$lOAU]['weekPrice'],
          "totalPrice" => $listOfAdditionalUpdates[$lOAU]['totalPrice'],
          "fee" => $listOfAdditionalUpdates[$lOAU]['fee'],
          "percentageFee" => $listOfAdditionalUpdates[$lOAU]['percentageFee'],
          "validFrom" => $listOfAdditionalUpdates[$lOAU]['validFrom']
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
