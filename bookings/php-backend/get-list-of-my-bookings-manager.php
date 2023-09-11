<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/code/php-backend/get-data-from-date.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-user.php";
  include "get-list-of-my-bookings.php";
  header('Content-Type: application/json');
  $output = [];
  $lastID = mysqli_real_escape_string($link, $_POST['lastID']);
  $search = mysqli_real_escape_string($link, $_POST['search']);
  if ($sign == "yes") {
    if ($lastID != "") {
      $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$lastID' LIMIT 1");
      $lastBeID = $sqlIdToBeId->fetch_assoc()["beid"];
    } else {
      $lastBeID = "";
    }
    $loadedListData = getListOfMyBookings("load-amount", $usrBeId, $lastBeID, $search);
    array_push($output, [
      "type" => "load-amount",
      "all-bookings" => $loadedListData['all-bookings'],
      "loaded" => $loadedListData['loaded'],
      "remain" => $loadedListData['remain']
    ]);
    $listOfMyBookings = getListOfMyBookings("list", $usrBeId, $lastBeID, $search);
    for ($lOMB=0; $lOMB < sizeof($listOfMyBookings); $lOMB++) {
      if ($listOfMyBookings[$lOMB]['status'] == "canceled") {
        $bookingSts = "<i>".$wrd_canceled."</i>";
      } else if ($listOfMyBookings[$lOMB]['status'] == "waiting") {
        $bookingSts = $wrd_waitingForConfirmation;
      } else if ($listOfMyBookings[$lOMB]['status'] == "booked") {
        $bookingSts = $wrd_booked;
      }
      if ($listOfMyBookings[$lOMB]['plcSts'] == "active") {
        $plcName = $listOfMyBookings[$lOMB]['plcName'];
      } else {
        if ($listOfMyBookings[$lOMB]['plcName'] != "-") {
          $plcName = "<i>".$listOfMyBookings[$lOMB]['plcName']."</i>";
        } else {
          $plcName = "<i>".$wrd_placeDeleted."</i>";
        }
      }
      array_push($output, [
        "type" => "booking",
        "status" => $bookingSts,
        "bookingID" => $listOfMyBookings[$lOMB]['bookingID'],
        "plcSts" => $listOfMyBookings[$lOMB]['plcSts'],
        "plcID" => $listOfMyBookings[$lOMB]['plcID'],
        "plcName" => $plcName,
        "fromD" => $listOfMyBookings[$lOMB]['fromD'],
        "fromM" => $listOfMyBookings[$lOMB]['fromM'],
        "fromY" => $listOfMyBookings[$lOMB]['fromY'],
        "toD" => $listOfMyBookings[$lOMB]['toD'],
        "toM" => $listOfMyBookings[$lOMB]['toM'],
        "toY" => $listOfMyBookings[$lOMB]['toY'],
        "numOfGuests" => $listOfMyBookings[$lOMB]['numOfGuests'],
        "wShowMore" => $wrd_showMore
      ]);
    }
    returnOutput();
  } else {
    error("You need to sign in to use this feature");
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
