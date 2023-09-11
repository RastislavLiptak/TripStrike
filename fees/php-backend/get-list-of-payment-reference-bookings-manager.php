<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/code/php-backend/get-data-from-date.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-user.php";
  include "get-list-of-payment-reference-bookings.php";
  header('Content-Type: application/json');
  $output = [];
  $lastID = mysqli_real_escape_string($link, $_POST['lastID']);
  $paymentReferenceCode = mysqli_real_escape_string($link, $_POST['paymentreference']);
  if ($sign == "yes" && $bnft_add_cottage == "good") {
    if ($lastID != "") {
      $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$lastID' LIMIT 1");
      $lastBeID = $sqlIdToBeId->fetch_assoc()["beid"];
    } else {
      $lastBeID = "";
    }
    $loadedListData = getListOfPaymentReferenceBookings("load-amount", $paymentReferenceCode, $lastBeID);
    array_push($output, [
      "type" => "load-amount",
      "all-bookings" => $loadedListData['all-bookings'],
      "loaded" => $loadedListData['loaded'],
      "remain" => $loadedListData['remain']
    ]);
    $listOfBookingsFees = getListOfPaymentReferenceBookings("list", $paymentReferenceCode, $lastBeID);
    for ($lOBF=0; $lOBF < sizeof($listOfBookingsFees); $lOBF++) {
      if ($listOfBookingsFees[$lOBF]['status'] == "") {
        $bookingSts = "<i>".$wrd_unknown." (empty)</i>";
      } else if ($listOfBookingsFees[$lOBF]['status'] == "-") {
        $bookingSts = "<i>".$wrd_unknown." (-)</i>";
      } else if ($listOfBookingsFees[$lOBF]['status'] == "rejected") {
        $bookingSts = "<i>".$wrd_rejected."</i>";
      } else if ($listOfBookingsFees[$lOBF]['status'] == "canceled") {
        $bookingSts = "<i>".$wrd_canceled."</i>";
      } else if ($listOfBookingsFees[$lOBF]['status'] == "waiting") {
        $bookingSts = $wrd_waitingForConfirmation;
      } else if ($listOfBookingsFees[$lOBF]['status'] == "booked") {
        $bookingSts = $wrd_booked;
      } else if ($listOfBookingsFees[$lOBF]['status'] == "paid") {
        $bookingSts = "<span class='table-data-green'>".$wrd_paid."</span>";
      } else if ($listOfBookingsFees[$lOBF]['status'] == "unpaid") {
        $bookingSts = "<span class='table-data-red'>".$wrd_unpaid."</span>";
      }
      if ($listOfBookingsFees[$lOBF]['plcSts'] == "active") {
        $plcName = $listOfBookingsFees[$lOBF]['plcName'];
      } else {
        if ($listOfBookingsFees[$lOBF]['plcName'] != "-") {
          $plcName = "<i>".$listOfBookingsFees[$lOBF]['plcName']."</i>";
        } else {
          $plcName = "<i>".$wrd_placeDeleted."</i>";
        }
      }
      array_push($output, [
        "type" => "booking",
        "status" => $bookingSts,
        "bookingID" => $listOfBookingsFees[$lOBF]['bookingID'],
        "plcSts" => $listOfBookingsFees[$lOBF]['plcSts'],
        "plcID" => $listOfBookingsFees[$lOBF]['plcID'],
        "plcName" => $plcName,
        "fromD" => $listOfBookingsFees[$lOBF]['fromD'],
        "fromM" => $listOfBookingsFees[$lOBF]['fromM'],
        "fromY" => $listOfBookingsFees[$lOBF]['fromY'],
        "toD" => $listOfBookingsFees[$lOBF]['toD'],
        "toM" => $listOfBookingsFees[$lOBF]['toM'],
        "toY" => $listOfBookingsFees[$lOBF]['toY'],
        "currency" => $listOfBookingsFees[$lOBF]['currency'],
        "fee" => $listOfBookingsFees[$lOBF]['fee'],
        "percentageFee" => $listOfBookingsFees[$lOBF]['percentageFee'],
        "wShowMore" => $wrd_showMore
      ]);
    }
    returnOutput();
  } else {
    if ($sign != "yes") {
      error("You are not signed in");
    } else if ($bnft_add_cottage != "good") {
      error("Feature not available");
    }
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
