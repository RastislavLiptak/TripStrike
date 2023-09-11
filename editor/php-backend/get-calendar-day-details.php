<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-user.php";
  include "place-verification.php";
  header('Content-Type: application/json');
  $output = [];
  $url_id = mysqli_real_escape_string($link, $_POST['plc_id']);
  $y = mysqli_real_escape_string($link, $_POST['y']);
  $m = mysqli_real_escape_string($link, $_POST['m']);
  $d = mysqli_real_escape_string($link, $_POST['d']);
  $placeVerify = placeVerification($url_id);
  if ($placeVerify == "good") {
    $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$url_id' LIMIT 1");
    $beId = $sqlIdToBeId->fetch_assoc()["beid"];
    $sqlPlc = $link->query("SELECT guestNum FROM places WHERE beid='$beId' and status='active' LIMIT 1");
    $rowPlc = $sqlPlc->fetch_assoc();
    if (is_numeric($y) && is_numeric($m) && is_numeric($d)) {
      $sqlBookings = $link->query("SELECT beid FROM bookingdates WHERE plcbeid='$beId' and status!='canceled' and year='$y' and month='$m' and day='$d'");
      if ($sqlBookings->num_rows > 0) {
        if ($sqlBookings->num_rows > 1) {
          while($rowBookBeId = $sqlBookings->fetch_assoc()) {
            $bookingBeId = $rowBookBeId['beid'];
            $sqlBooking = $link->query("SELECT * FROM booking WHERE beid='$bookingBeId' LIMIT 1");
            if ($sqlBooking->num_rows > 0) {
              $book = $sqlBooking->fetch_assoc();
              if ($book['fullAmount'] == 1 || $book['lessthan48h'] == "0") {
                $lessthan48h = 0;
              } else {
                $lessthan48h = 1;
              }
              $sqlArchive = $linkBD->query("SELECT * FROM bookingarchive WHERE beid='$bookingBeId' LIMIT 1");
              if ($sqlArchive->num_rows > 0) {
                $arch = $sqlArchive->fetch_assoc();
                $fee = $arch['fee'];
                $feePerc = $arch['percentagefee'];
              } else {
                $fee = 0;
                $feePerc = 0;
              }
              $diff = abs(strtotime(date("Y-m-d H:i:s")) - strtotime($book['fulldate']));
              $tillCancel = floor($diff / 3600);
              $tillCancel = 48 - $tillCancel;
              pushBookingToArrayBooking(
                getFrontendId($bookingBeId),
                $book['status'],
                $book['name'],
                $book['email'],
                $book['phonenum'],
                $book['guestnum'],
                $rowPlc['guestNum'],
                $book['notes'],
                $book['fromd'],
                $book['fromm'],
                $book['fromy'],
                $book['firstday'],
                $book['tod'],
                $book['tom'],
                $book['toy'],
                $book['lastday'],
                $book['totalcurrency'],
                $book['totalprice'],
                $fee,
                $feePerc,
                $book['deposit'],
                $book['fullAmount'],
                $lessthan48h,
                $tillCancel
              );
            }
          }
        } else {
          $bookingBeId = $sqlBookings->fetch_assoc()['beid'];
          $sqlBooking = $link->query("SELECT * FROM booking WHERE beid='$bookingBeId' LIMIT 1");
          if ($sqlBooking->num_rows > 0) {
            $book = $sqlBooking->fetch_assoc();
            if ($book['fullAmount'] == 1 || $book['lessthan48h'] == "0") {
              $lessthan48h = 0;
            } else {
              $lessthan48h = 1;
            }
            $sqlArchive = $linkBD->query("SELECT * FROM bookingarchive WHERE beid='$bookingBeId' LIMIT 1");
            if ($sqlArchive->num_rows > 0) {
              $arch = $sqlArchive->fetch_assoc();
              $fee = $arch['fee'];
              $feePerc = $arch['percentagefee'];
            } else {
              $fee = 0;
              $feePerc = 0;
            }
            $diff = abs(strtotime(date("Y-m-d H:i:s")) - strtotime($book['fulldate']));
            $tillCancel = floor($diff / 3600);
            $tillCancel = 48 - $tillCancel;
            pushBookingToArrayBooking(
              getFrontendId($bookingBeId),
              $book['status'],
              $book['name'],
              $book['email'],
              $book['phonenum'],
              $book['guestnum'],
              $rowPlc['guestNum'],
              $book['notes'],
              $book['fromd'],
              $book['fromm'],
              $book['fromy'],
              $book['firstday'],
              $book['tod'],
              $book['tom'],
              $book['toy'],
              $book['lastday'],
              $book['totalcurrency'],
              $book['totalprice'],
              $fee,
              $feePerc,
              $book['deposit'],
              $book['fullAmount'],
              $lessthan48h,
              $tillCancel
            );
          }
        }
      }
      $sqlTechnicalShutdown = $link->query("SELECT beid FROM technicalshutdowndates WHERE plcbeid='$beId' and status!='canceled' and year='$y' and month='$m' and day='$d'");
      if ($sqlTechnicalShutdown->num_rows > 0) {
        if ($sqlTechnicalShutdown->num_rows > 1) {
          while($rowTechnicalShutdownBeId = $sqlTechnicalShutdown->fetch_assoc()) {
            $technicalShutdownBeId = $rowTechnicalShutdownBeId['beid'];
            $sqlTechnicalShutdownData = $link->query("SELECT * FROM technicalshutdown WHERE beid='$technicalShutdownBeId' LIMIT 1");
            if ($sqlTechnicalShutdownData->num_rows > 0) {
              $techShutdown = $sqlTechnicalShutdownData->fetch_assoc();
              pushTechnicalShutdownToArrayTechnicalShutdown(
                $techShutdown['category'],
                $techShutdown['notes'],
                $techShutdown['fromd'],
                $techShutdown['fromm'],
                $techShutdown['fromy'],
                $techShutdown['firstday'],
                $techShutdown['tod'],
                $techShutdown['tom'],
                $techShutdown['toy'],
                $techShutdown['lastday']
              );
            }
          }
        } else {
          $technicalShutdownBeId = $sqlTechnicalShutdown->fetch_assoc()['beid'];
          $sqlTechnicalShutdown = $link->query("SELECT * FROM technicalshutdown WHERE beid='$technicalShutdownBeId' LIMIT 1");
          if ($sqlTechnicalShutdown->num_rows > 0) {
            $techShutdown = $sqlTechnicalShutdown->fetch_assoc();
            pushTechnicalShutdownToArrayTechnicalShutdown(
              $techShutdown['category'],
              $techShutdown['notes'],
              $techShutdown['fromd'],
              $techShutdown['fromm'],
              $techShutdown['fromy'],
              $techShutdown['firstday'],
              $techShutdown['tod'],
              $techShutdown['tom'],
              $techShutdown['toy'],
              $techShutdown['lastday']
            );
          }
        }
      }
      returnOutput();
    } else {
      error("date-is-not-in-valid-form<br>day: ".$d."<br>month: ".$m."<br>year: ".$y."");
    }
  } else {
    error($placeVerify);
  }

  function pushBookingToArrayBooking($bookingID, $sts, $name, $email, $phonenum, $guestNum, $maxGuestNum, $notes, $fromd, $fromm, $fromy, $firstday, $tod, $tom, $toy, $lastday, $currency, $totalprice, $fee, $feePerc, $deposit, $fullAmount, $lessthan48h, $tillCancel) {
    global $output, $usrBeId;
    if ($sts == "waiting") {
      $name = "";
      $email = "";
      $phonenum = "";
      $notes = "";
      $deposit = "";
      $fullAmount = "";
    }
    array_push($output, [
      "type" => "booking",
      "bookingID" => $bookingID,
      "status" => $sts,
      "name" => $name,
      "email" => $email,
      "phone" => $phonenum,
      "guest" => $guestNum,
      "max_guest" => $maxGuestNum,
      "notes" => $notes,
      "f_d" => $fromd,
      "f_m" => $fromm,
      "f_y" => $fromy,
      "firstday" => $firstday,
      "t_d" => $tod,
      "t_m" => $tom,
      "t_y" => $toy,
      "lastday" => $lastday,
      "currency" => $currency,
      "totalprice" => $totalprice,
      "fee" => $fee,
      "feeperc" => $feePerc,
      "deposit" => $deposit,
      "fullAmount" => $fullAmount,
      "lessthan48h" => $lessthan48h,
      "tillCancel" => $tillCancel,
      "countdownBanner" => getAccountData($usrBeId, "cancel-unpaid-bookings")
    ]);
  }

  function pushTechnicalShutdownToArrayTechnicalShutdown($category, $notes, $fromd, $fromm, $fromy, $firstday, $tod, $tom, $toy, $lastday) {
    global $output;
    array_push($output, [
      "type" => "technical-shutdown",
      "category" => $category,
      "notes" => $notes,
      "f_d" => $fromd,
      "f_m" => $fromm,
      "f_y" => $fromy,
      "firstday" => $firstday,
      "t_d" => $tod,
      "t_m" => $tom,
      "t_y" => $toy,
      "lastday" => $lastday
    ]);
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
