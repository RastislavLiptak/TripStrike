<?php
  include "../../../uni/code/php-backend/data.php";
  include "../../../uni/code/php-backend/get-frontend-id.php";
  include "../../../uni/code/php-backend/get-data-from-date.php";
  include "../../../uni/dictionary/lang-select.php";
  include "../../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/check-sign-in.php";
  include "get-list-of-host-bookings.php";
  header('Content-Type: application/json');
  $output = [];
  $lastID = mysqli_real_escape_string($link, $_POST['lastID']);
  $userID = mysqli_real_escape_string($link, $_POST['userID']);
  $periodY = mysqli_real_escape_string($link, $_POST['periodY']);
  $periodM = mysqli_real_escape_string($link, $_POST['periodM']);
  $paymentReference = mysqli_real_escape_string($link, $_POST['paymentReference']);
  $search = mysqli_real_escape_string($link, $_POST['search']);
  $backDoorCheckSignInSts = backDoorCheckSignIn();
  if ($backDoorCheckSignInSts == "good") {
    $sqlUserIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$userID' LIMIT 1");
    if ($sqlUserIdToBeId->num_rows > 0) {
      $userBeId = $sqlUserIdToBeId->fetch_assoc()["beid"];
      if ($lastID != "") {
        $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$lastID' LIMIT 1");
        $lastBeID = $sqlIdToBeId->fetch_assoc()["beid"];
      } else {
        $lastBeID = "";
      }
      $loadedListData = getListOfHostBookings("load-amount", $lastBeID, $userBeId, $periodY, $periodM, $paymentReference, $search);
      array_push($output, [
        "type" => "load-amount",
        "all-bookings" => $loadedListData['all-bookings'],
        "loaded" => $loadedListData['loaded'],
        "remain" => $loadedListData['remain']
      ]);
      $listOfHostBookings = getListOfHostBookings("list", $lastBeID, $userBeId, $periodY, $periodM, $paymentReference, $search);
      for ($lOHB=0; $lOHB < sizeof($listOfHostBookings); $lOHB++) {
        if ($listOfHostBookings[$lOHB]['status'] == "") {
          $bookingSts = "<i>".$wrd_unknown." (empty)</i>";
        } else if ($listOfHostBookings[$lOHB]['status'] == "-") {
          $bookingSts = "<i>".$wrd_unknown." (-)</i>";
        } else if ($listOfHostBookings[$lOHB]['status'] == "rejected") {
          $bookingSts = "<i>".$wrd_rejected."</i>";
        } else if ($listOfHostBookings[$lOHB]['status'] == "canceled") {
          $bookingSts = "<i>".$wrd_canceled."</i>";
        } else if ($listOfHostBookings[$lOHB]['status'] == "waiting") {
          $bookingSts = $wrd_waitingForConfirmation;
        } else if ($listOfHostBookings[$lOHB]['status'] == "booked") {
          $bookingSts = $wrd_booked;
        } else if ($listOfHostBookings[$lOHB]['status'] == "paid") {
          $bookingSts = "<span class='table-data-green'>".$wrd_paid."</span>";
        } else if ($listOfHostBookings[$lOHB]['status'] == "unpaid") {
          $bookingSts = "<span class='table-data-red'>".$wrd_unpaid."</span>";
        }
        if ($listOfHostBookings[$lOHB]['plcSts'] == "active") {
          $plcName = $listOfHostBookings[$lOHB]['plcName'];
        } else {
          if ($listOfHostBookings[$lOHB]['plcName'] != "-") {
            $plcName = "<i>".$listOfHostBookings[$lOHB]['plcName']."</i>";
          } else {
            $plcName = "<i>".$wrd_placeDeleted."</i>";
          }
        }
        array_push($output, [
          "type" => "booking",
          "status" => $bookingSts,
          "bookingID" => $listOfHostBookings[$lOHB]['bookingID'],
          "plcSts" => $listOfHostBookings[$lOHB]['plcSts'],
          "plcID" => $listOfHostBookings[$lOHB]['plcID'],
          "plcName" => $plcName,
          "fromD" => $listOfHostBookings[$lOHB]['fromD'],
          "fromM" => $listOfHostBookings[$lOHB]['fromM'],
          "fromY" => $listOfHostBookings[$lOHB]['fromY'],
          "toD" => $listOfHostBookings[$lOHB]['toD'],
          "toM" => $listOfHostBookings[$lOHB]['toM'],
          "toY" => $listOfHostBookings[$lOHB]['toY'],
          "currency" => $listOfHostBookings[$lOHB]['currency'],
          "totalPrice" => $listOfHostBookings[$lOHB]['totalPrice'],
          "fee" => $listOfHostBookings[$lOHB]['fee'],
          "percentageFee" => $listOfHostBookings[$lOHB]['percentageFee'],
          "wShowMore" => $wrd_showMore
        ]);
      }
      returnOutput();
    } else {
      error("User ID not exist");
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
