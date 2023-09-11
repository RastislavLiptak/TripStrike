<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/code/php-backend/get-data-from-date.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-user.php";
  include "get-list-of-guest-bookings.php";
  header('Content-Type: application/json');
  $output = [];
  $lastID = mysqli_real_escape_string($link, $_POST['lastID']);
  $search = mysqli_real_escape_string($link, $_POST['search']);
  if ($sign == "yes") {
    if ($bnft_add_cottage == "good") {
      if ($lastID != "") {
        $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$lastID' LIMIT 1");
        $lastBeID = $sqlIdToBeId->fetch_assoc()["beid"];
      } else {
        $lastBeID = "";
      }
      $loadedListData = getlistOfGuestBookings("load-amount", $usrBeId, $lastBeID, $search);
      array_push($output, [
        "type" => "load-amount",
        "all-bookings" => $loadedListData['all-bookings'],
        "loaded" => $loadedListData['loaded'],
        "remain" => $loadedListData['remain']
      ]);
      $listOfGuestBookings = getlistOfGuestBookings("list", $usrBeId, $lastBeID, $search);
      for ($lOGB=0; $lOGB < sizeof($listOfGuestBookings); $lOGB++) {
        if ($listOfGuestBookings[$lOGB]['status'] == "canceled") {
          $bookingSts = "<i>".$wrd_canceled."</i>";
        } else if ($listOfGuestBookings[$lOGB]['status'] == "waiting") {
          $bookingSts = $wrd_waitingForConfirmation;
        } else if ($listOfGuestBookings[$lOGB]['status'] == "booked") {
          $bookingSts = $wrd_booked;
        }
        $guestID = $listOfGuestBookings[$lOGB]['guestID'];
        $guestName = $listOfGuestBookings[$lOGB]['guestName'];
        if ($listOfGuestBookings[$lOGB]['guestID'] != "-") {
          $guestSts = "signed-user";
        } else {
          $guestSts = "not-signed-user";
        }
        if ($listOfGuestBookings[$lOGB]['plcSts'] == "active") {
          $plcName = $listOfGuestBookings[$lOGB]['plcName'];
        } else {
          if ($listOfGuestBookings[$lOGB]['plcName'] != "-") {
            $plcName = "<i>".$listOfGuestBookings[$lOGB]['plcName']."</i>";
          } else {
            $plcName = "<i>".$wrd_placeDeleted."</i>";
          }
        }
        array_push($output, [
          "type" => "booking",
          "status" => $bookingSts,
          "bookingID" => $listOfGuestBookings[$lOGB]['bookingID'],
          "guestSts" => $guestSts,
          "guestID" => $listOfGuestBookings[$lOGB]['guestID'],
          "guestName" => $guestName,
          "plcSts" => $listOfGuestBookings[$lOGB]['plcSts'],
          "plcID" => $listOfGuestBookings[$lOGB]['plcID'],
          "plcName" => $plcName,
          "fromD" => $listOfGuestBookings[$lOGB]['fromD'],
          "fromM" => $listOfGuestBookings[$lOGB]['fromM'],
          "fromY" => $listOfGuestBookings[$lOGB]['fromY'],
          "toD" => $listOfGuestBookings[$lOGB]['toD'],
          "toM" => $listOfGuestBookings[$lOGB]['toM'],
          "toY" => $listOfGuestBookings[$lOGB]['toY'],
          "wShowMore" => $wrd_showMore
        ]);
      }
      returnOutput();
    } else {
      error("This feature is not available");
    }
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
