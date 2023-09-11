<?php
  include "../../../uni/code/php-backend/data.php";
  include "../../../uni/code/php-backend/get-frontend-id.php";
  include "../../../uni/code/php-backend/get-data-from-date.php";
  include "../../../uni/dictionary/lang-select.php";
  include "../../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/check-sign-in.php";
  include "get-list-of-hosts-fees.php";
  header('Content-Type: application/json');
  $output = [];
  $lastID = mysqli_real_escape_string($link, $_POST['lastID']);
  $lastY = mysqli_real_escape_string($link, $_POST['lastY']);
  $lastM = mysqli_real_escape_string($link, $_POST['lastM']);
  $search = mysqli_real_escape_string($link, $_POST['search']);
  $backDoorCheckSignInSts = backDoorCheckSignIn();
  if ($backDoorCheckSignInSts == "good") {
    if ($lastID != "") {
      $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$lastID' LIMIT 1");
      $lastBeID = $sqlIdToBeId->fetch_assoc()["beid"];
    } else {
      $lastBeID = "";
    }
    $loadedListData = getListOfHostsFees("load-amount", $lastBeID, $lastY, $lastM, $search);
    array_push($output, [
      "type" => "load-amount",
      "all-bookings" => $loadedListData['all-bookings'],
      "loaded" => $loadedListData['loaded'],
      "remain" => $loadedListData['remain']
    ]);
    $listOfHostsFees = getListOfHostsFees("list", $lastBeID, $lastY, $lastM, $search);
    for ($lOHF=0; $lOHF < sizeof($listOfHostsFees); $lOHF++) {
      if ($listOfHostsFees[$lOHF]['status'] == "") {
        $rowStatus = "<i>".$wrd_unknown." (empty)</i>";
      } else if ($listOfHostsFees[$lOHF]['status'] == "-") {
        $rowStatus = "<i>".$wrd_unknown." (-)</i>";
      } else if ($listOfHostsFees[$lOHF]['status'] == "rejected") {
        $rowStatus = "<i>".$wrd_rejected."</i>";
      } else if ($listOfHostsFees[$lOHF]['status'] == "canceled") {
        $rowStatus = "<i>".$wrd_canceled."</i>";
      } else if ($listOfHostsFees[$lOHF]['status'] == "waiting") {
        $rowStatus = $wrd_waitingForConfirmation;
      } else if ($listOfHostsFees[$lOHF]['status'] == "booked") {
        $rowStatus = $wrd_booked;
      } else if ($listOfHostsFees[$lOHF]['status'] == "paid") {
        $rowStatus = "<span class='table-data-green'>".$wrd_paid."</span>";
      } else if ($listOfHostsFees[$lOHF]['status'] == "unpaid") {
        $rowStatus = "<span class='table-data-red'>".$wrd_unpaid."</span>";
      }
      if ($listOfHostsFees[$lOHF]['paymentStatus'] == "paid") {
        $paymentBtnTxt = $wrd_unpaid;
      } else if ($listOfHostsFees[$lOHF]['paymentStatus'] == "unpaid") {
        $paymentBtnTxt = $wrd_paid;
      } else {
        $paymentBtnTxt = "";
      }
      array_push($output, [
        "type" => "hosts",
        "status" => $rowStatus,
        "id" => $listOfHostsFees[$lOHF]['id'],
        "username" => $listOfHostsFees[$lOHF]['username'],
        "periodM" => $listOfHostsFees[$lOHF]['periodM'],
        "periodY" => $listOfHostsFees[$lOHF]['periodY'],
        "numOfBookings" => $listOfHostsFees[$lOHF]['numOfBookings'],
        "currency" => $listOfHostsFees[$lOHF]['currency'],
        "totalPrice" => $listOfHostsFees[$lOHF]['totalPrice'],
        "totalFee" => $listOfHostsFees[$lOHF]['totalFee'],
        "paymentStatus" => $listOfHostsFees[$lOHF]['paymentStatus'],
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
