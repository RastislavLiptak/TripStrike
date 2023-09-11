<?php
  include "../../../uni/code/php-backend/data.php";
  include "../../../uni/code/php-backend/get-frontend-id.php";
  include "../../../uni/code/php-backend/get-data-from-date.php";
  include "../../../uni/dictionary/lang-select.php";
  include "../../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/check-sign-in.php";
  include "get-list-of-payment-references-fees.php";
  header('Content-Type: application/json');
  $output = [];
  $lastPaymentReference = mysqli_real_escape_string($link, $_POST['paymentReference']);
  $search = mysqli_real_escape_string($link, $_POST['search']);
  $backDoorCheckSignInSts = backDoorCheckSignIn();
  if ($backDoorCheckSignInSts == "good") {
    $loadedListData = getListOfPaymentReferencesFees("load-amount", $lastPaymentReference, $search);
    array_push($output, [
      "type" => "load-amount",
      "all-bookings" => $loadedListData['all-bookings'],
      "loaded" => $loadedListData['loaded'],
      "remain" => $loadedListData['remain']
    ]);
    $listOfPaymentReferencesFees = getListOfPaymentReferencesFees("list", $lastPaymentReference, $search);
    for ($lOPRF=0; $lOPRF < sizeof($listOfPaymentReferencesFees); $lOPRF++) {
      if ($listOfPaymentReferencesFees[$lOPRF]['status'] == "") {
        $rowStatus = "<i>".$wrd_unknown." (empty)</i>";
      } else if ($listOfPaymentReferencesFees[$lOPRF]['status'] == "-") {
        $rowStatus = "<i>".$wrd_unknown." (-)</i>";
      } else if ($listOfPaymentReferencesFees[$lOPRF]['status'] == "rejected") {
        $rowStatus = "<i>".$wrd_rejected."</i>";
      } else if ($listOfPaymentReferencesFees[$lOPRF]['status'] == "canceled") {
        $rowStatus = "<i>".$wrd_canceled."</i>";
      } else if ($listOfPaymentReferencesFees[$lOPRF]['status'] == "waiting") {
        $rowStatus = $wrd_waitingForConfirmation;
      } else if ($listOfPaymentReferencesFees[$lOPRF]['status'] == "booked") {
        $rowStatus = $wrd_booked;
      } else if ($listOfPaymentReferencesFees[$lOPRF]['status'] == "paid") {
        $rowStatus = "<span class='table-data-green'>".$wrd_paid."</span>";
      } else if ($listOfPaymentReferencesFees[$lOPRF]['status'] == "unpaid") {
        $rowStatus = "<span class='table-data-red'>".$wrd_unpaid."</span>";
      }
      if ($listOfPaymentReferencesFees[$lOPRF]['paymentStatus'] == "paid") {
        $paymentBtnTxt = $wrd_unpaid;
      } else if ($listOfPaymentReferencesFees[$lOPRF]['paymentStatus'] == "unpaid") {
        $paymentBtnTxt = $wrd_paid;
      } else {
        $paymentBtnTxt = "";
      }
      array_push($output, [
        "type" => "payment-reference",
        "status" => $rowStatus,
        "hostID" => $listOfPaymentReferencesFees[$lOPRF]['hostID'],
        "hostName" => $listOfPaymentReferencesFees[$lOPRF]['hostName'],
        "payment-reference" => $listOfPaymentReferencesFees[$lOPRF]['payment-reference'],
        "numOfBookings" => $listOfPaymentReferencesFees[$lOPRF]['numOfBookings'],
        "currency" => $listOfPaymentReferencesFees[$lOPRF]['currency'],
        "totalFee" => $listOfPaymentReferencesFees[$lOPRF]['totalFee'],
        "paymentStatus" => $listOfPaymentReferencesFees[$lOPRF]['paymentStatus'],
        "paymentBtnTxt" => $paymentBtnTxt,
        "wShowMore" => $wrd_showMore
      ]);
    }
    returnOutput();
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
