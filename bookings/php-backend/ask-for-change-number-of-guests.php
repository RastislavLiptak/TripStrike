<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/account-data-check.php";
  include "../../uni/code/php-backend/total-price-calculator.php";
  include "../../uni/code/php-backend/random-hash-maker.php";
  include "../../uni/code/php-backend/add-currency.php";
  include "booking-verification.php";
  include "../../email-sender/php-backend/send-mail.php";
  include "../../email-sender/php-backend/ask-for-booking-update-guests-mail.php";
  header('Content-Type: application/json');
  $output = [];
  $date = date("Y-m-d H:i:s");
  $dateY = date("Y");
  $dateM = date("m");
  $dateD = date("d");
  $url_id = mysqli_real_escape_string($link, $_POST['urlId']);
  $numOfGuests = mysqli_real_escape_string($link, $_POST['guests']);
  $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$url_id' LIMIT 1");
  if ($sqlIdToBeId->num_rows > 0) {
    $bookingBeId = $sqlIdToBeId->fetch_assoc()["beid"];
    $bookingVerify = bookingVerification($bookingBeId);
    if ($bookingVerify == "good") {
      $sqlBooking = $link->query("SELECT * FROM booking WHERE beid='$bookingBeId'");
      $booking = $sqlBooking->fetch_assoc();
      $plcBeId = $booking['plcbeid'];
      $sqlPlc = $link->query("SELECT * FROM places WHERE beid='$plcBeId'");
      $plc = $sqlPlc->fetch_assoc();
      $hostBeID = $plc['usrbeid'];
      $sqlHst = $link->query("SELECT * FROM users WHERE beid='$hostBeID' and status='active'");
      $hst = $sqlHst->fetch_assoc();
      if ($numOfGuests != "") {
        if ($numOfGuests > 0) {
          if ($numOfGuests <= $plc['guestNum']) {
            if ($numOfGuests != $booking['guestnum']) {
              $saveDone = false;
              $sendMail = false;
              $sqlBookUpdtReqSelect = $link->query("SELECT * FROM bookingupdaterequest WHERE bookingbeid='$bookingBeId' and status='ready' and type='guests'");
              if ($sqlBookUpdtReqSelect->num_rows > 0) {
                $reqRow = $sqlBookUpdtReqSelect->fetch_assoc();
                if ($numOfGuests != $reqRow['guestNum']) {
                  $reqBeId = $reqRow['beid'];
                  $sqlBookUpdtReqUpdate = "UPDATE bookingupdaterequest SET guestNum='$numOfGuests', fulldate='$date', datey='$dateY', datem='$dateM', dated='$dateD' WHERE beid='$reqBeId'";
                  if (mysqli_query($link, $sqlBookUpdtReqUpdate)) {
                    $saveDone = true;
                    $sendMail = true;
                  } else {
                    error("Update request in the database failed<br>".mysqli_error($link));
                  }
                } else {
                  $saveDone = true;
                }
              } else {
                $beIdReady = false;
                while (!$beIdReady) {
                  $reqBeId = randomHash(64);
                  if ($link->query("SELECT * FROM backendidlist WHERE beid='$reqBeId'")->num_rows == 0) {
                    $beIdReady = true;
                  } else {
                    $beIdReady = false;
                  }
                }
                $idReady = false;
                while (!$idReady) {
                  $reqId = randomHash(11);
                  if ($link->query("SELECT * FROM idlist WHERE id='$reqId'")->num_rows == 0) {
                    $idReady = true;
                  } else {
                    $idReady = false;
                  }
                }
                $backendIDNum = $link->query("SELECT * FROM backendidlist")->num_rows;
                $sqlBeID = "INSERT INTO backendidlist (beid, numid, type) VALUES('$reqBeId', '$backendIDNum', 'change-request')";
                if (mysqli_query($link, $sqlBeID)) {
                  $sqlID = "INSERT INTO idlist(beid, id, fullDate, dateD, dateM, dateY) VALUES('$reqBeId', '$reqId', '$date', '$dateD', '$dateM', '$dateY')";
                  if (mysqli_query($link, $sqlID)) {
                    $sqlBookUpdtReqInsert = "INSERT INTO bookingupdaterequest (beid, bookingbeid, status, type, guestNum, fromdate, fromy, fromm, fromd, firstday, todate, toy, tom, tod, lastday, fulldate, datey, datem, dated) VALUES ('$reqBeId', '$bookingBeId', 'ready', 'guests', '$numOfGuests', '$date', '0', '0', '0', '-', '$date', '0', '0', '0', '-', '$date', '$dateY', '$dateM', '$dateD')";
                    if (mysqli_query($link, $sqlBookUpdtReqInsert)) {
                      $saveDone = true;
                      $sendMail = true;
                    } else {
                      error("Insert request to the database failed<br>".mysqli_error($link));
                    }
                  } else {
                    error("Saving request ID failed<br>".mysqli_error($link));
                  }
                } else {
                  error("Saving request backend ID failed<br>".mysqli_error($link));
                }
              }
              $sqlBookDateReqSelect = $link->query("SELECT * FROM bookingupdaterequest WHERE bookingbeid='$bookingBeId' and status='ready' and type='date'");
              if ($sqlBookDateReqSelect->num_rows > 0) {
                $dtReqRow = $sqlBookDateReqSelect->fetch_assoc();
                $new_total = totalPriceCalc(
                  $plcBeId,
                  $numOfGuests,
                  $dtReqRow['fromy'],
                  $dtReqRow['fromm'],
                  $dtReqRow['fromd'],
                  $dtReqRow['firstday'],
                  $dtReqRow['toy'],
                  $dtReqRow['tom'],
                  $dtReqRow['tod'],
                  $dtReqRow['lastday']
                );
              } else {
                $new_total = totalPriceCalc(
                  $plcBeId,
                  $numOfGuests,
                  $booking['fromy'],
                  $booking['fromm'],
                  $booking['fromd'],
                  $booking['firstday'],
                  $booking['toy'],
                  $booking['tom'],
                  $booking['tod'],
                  $booking['lastday']
                );
              }
              if ($new_total < $booking['totalprice']) {
                $price_diff = $booking['totalprice'] - $new_total;
                $price_diff = "-".$price_diff;
              } else if ($new_total > $booking['totalprice']) {
                $price_diff = $new_total - $booking['totalprice'];
                $price_diff = "+".$price_diff;
              } else {
                $price_diff = 0;
              }
              if ($saveDone) {
                if ($sendMail) {
                  askForBookingUpdateGuestsMail(
                    $hst['language'],
                    $hst['contactemail'],
                    getFrontendId($plcBeId),
                    $plc['name'],
                    $booking['fromd'],
                    $booking['fromm'],
                    $booking['fromy'],
                    $booking['firstday'],
                    $booking['tod'],
                    $booking['tom'],
                    $booking['toy'],
                    $booking['lastday'],
                    $booking['guestnum'],
                    $numOfGuests,
                    $new_total,
                    $price_diff,
                    $booking['totalcurrency'],
                    $url_id,
                    $booking['name'],
                    $booking['email'],
                    $booking['phonenum']
                  );
                } else {
                  done();
                }
              }
            } else {
              $sqlBookUpdtReqDelete = "UPDATE bookingupdaterequest SET status='canceled' WHERE bookingbeid='$bookingBeId' and type='guests'";
              if (mysqli_query($link, $sqlBookUpdtReqDelete)) {
                $sqlBookDateReqSelect = $link->query("SELECT * FROM bookingupdaterequest WHERE bookingbeid='$bookingBeId' and status='ready' and type='date'");
                if ($sqlBookDateReqSelect->num_rows > 0) {
                  $dtReqRow = $sqlBookDateReqSelect->fetch_assoc();
                  $new_total = totalPriceCalc(
                    $plcBeId,
                    $numOfGuests,
                    $dtReqRow['fromy'],
                    $dtReqRow['fromm'],
                    $dtReqRow['fromd'],
                    $dtReqRow['firstday'],
                    $dtReqRow['toy'],
                    $dtReqRow['tom'],
                    $dtReqRow['tod'],
                    $dtReqRow['lastday']
                  );
                } else {
                  $new_total = $booking['totalprice'];
                }
                if ($new_total < $booking['totalprice']) {
                  $price_diff = $booking['totalprice'] - $new_total;
                  $price_diff = "-".$price_diff;
                } else if ($new_total > $booking['totalprice']) {
                  $price_diff = $new_total - $booking['totalprice'];
                  $price_diff = "+".$price_diff;
                } else {
                  $price_diff = 0;
                }
                $numOfGuests = "org";
                done();
              } else {
                error("Delete original request in the database failed<br>".mysqli_error($link));
              }
            }
          } else {
            error("Number of guests is too high (max: ".$plc['guestNum'].")");
          }
        } else {
          error("Number of guests is too low");
        }
      } else {
        error("Number of guests not filled in");
      }
    } else {
      error("Booking verification error: ".$bookingVerify);
    }
  } else {
    error("ID not connected to any backend ID");
  }

  function mailDone($sts, $mailType) {
    if ($sts == "done") {
      done();
    } else {
      error("New data was saved, but contacting the host failed. Please inform him/her about the changes in person via email or contact phone");
    }
  }

  function done() {
    global $output, $numOfGuests, $new_total, $price_diff, $booking;
    array_push($output, [
      "type" => "done",
      "request" => $numOfGuests,
      "currency" => $booking['totalcurrency'],
      "newTotal" => $new_total,
      "priceDiff" => $price_diff
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
