<?php
  include "../data.php";
  include "../password-edit.php";
  include "../get-frontend-id.php";
  include "../../../dictionary/lang-select.php";
  include "../../../../bookings/php-backend/reject-booking.php";
  include "../../../../email-sender/php-backend/send-mail.php";
  include "../../../../email-sender/php-backend/host-rejected-the-booking-mail.php";
  include "../../../../editor/php-backend/place-delete.php";
  include "../../../../backdoor/uni/code/php-backend/cancel-admin.php";
  include "../../../../editor/php-backend/edit-booking/cancel-booking.php";
  header('Content-Type: application/json');
  $output = [];
  $canceledBookingsArray = [];
  $allBookingsCanceled = false;
  $partialErrors = "";
  $id = $link->escape_string($_POST['id']);
  $email = $link->escape_string($_POST['email']);
  $phonenum = $link->escape_string(str_replace("plus", "+", $_POST['phonenum']));
  if ($_POST['pass'] != "") {
    $beId = getFrontendId($id);
    if ($beId != "none" && $email != "none" && $phonenum != "none") {
      $sql = $link->query("SELECT * FROM users WHERE beid='$beId' && email='$email' && phonenum='$phonenum'");
    } else {
      if ($beId == "none") {
        $sql = $link->query("SELECT * FROM users WHERE email='$email' && phonenum='$phonenum'");
      } else if ($email == "none") {
        $sql = $link->query("SELECT * FROM users WHERE beid='$beId' && phonenum='$phonenum'");
      } else if ($phonenum == "none") {
        $sql = $link->query("SELECT * FROM users WHERE beid='$beId' && email='$email'");
      } else {
        $sql = "none";
      }
    }
    if ($sql != "none") {
      if ($sql->num_rows > 0) {
        while($row = $sql->fetch_assoc()) {
          if ($row['status'] != "delete") {
            if (password_verify(passEdit($_POST['pass']), passEdit($row['password']))) {
              $beId = $row['beid'];
              $hostName = $row['firstname']." ".$row['lastname'];
              $hostEmail = $row['contactemail'];
              $hostPhone = $row['contactphonenum'];

              $sqlBookingFeesCheck = $linkBD->query("SELECT * FROM bookingarchive WHERE hostbeid='$beId' and paymentStatus='0' and status='booked' and fee!='0' and fromdate<=NOW()");
              if ($sqlBookingFeesCheck->num_rows == 0) {
                $adminCancelSts = cancelAdmin($beId);
                if ($adminCancelSts == "done") {
                  $sqlDel = "UPDATE users SET status='delete', firstname='-', lastname='-', email='-', contactemail='-', syncemailsts='0', phonenum='0', contactphonenum='0', syncnumsts='0', bankaccount='-', iban='-', bicswift='-', password='-', fullbirth=NOW(), birthy='0', birthm='0', birthd='0', description='-', language='-', signupfulldate=NOW(), signupy='0', signupm='0', signupd='0' WHERE beid='$beId'";
                  if (mysqli_query($link, $sqlDel)) {
                    $sqlArchiveDel = "UPDATE usersarchive SET status='delete', bankaccount='-', iban='-', bicswift='-', fullbirth=NOW(), birthy='0', birthm='0', birthd='0', description='-' WHERE beid='$beId'";
                    if (mysqli_query($linkBD, $sqlArchiveDel)) {
                      if (!mysqli_query($link, "DELETE FROM languages WHERE beid='$beId'")) {
                        $partialErrors = $partialErrors."Failed to delete from languages database: ".mysqli_error($link)."<br>";
                      }
                      if (!mysqli_query($link, "DELETE FROM userscheduled WHERE beid='$beId'")) {
                        $partialErrors = $partialErrors."Failed to delete from userscheduled database: ".mysqli_error($link)."<br>";
                      }
                      if (!mysqli_query($link, "DELETE FROM comments WHERE beid='$beId'")) {
                        $partialErrors = $partialErrors."Failed to delete from comments database: ".mysqli_error($link)."<br>";
                      }
                      if (!mysqli_query($link, "DELETE FROM rating WHERE beid='$beId'")) {
                        $partialErrors = $partialErrors."Failed to delete from rating database: ".mysqli_error($link)."<br>";
                      }
                      if (!mysqli_query($link, "DELETE FROM ratingcriticsummary WHERE beid='$beId'")) {
                        $partialErrors = $partialErrors."Failed to delete from ratingcriticsummary database: ".mysqli_error($link)."<br>";
                      }
                      if (!mysqli_query($link, "DELETE FROM ratingsectionsummary WHERE beid='$beId'")) {
                        $partialErrors = $partialErrors."Failed to delete from ratingsectionsummary database: ".mysqli_error($link)."<br>";
                      }
                      if (!mysqli_query($link, "DELETE FROM ratingsummary WHERE beid='$beId'")) {
                        $partialErrors = $partialErrors."Failed to delete from ratingsummary database: ".mysqli_error($link)."<br>";
                      }
                      if (!mysqli_query($link, "UPDATE booking SET usrbeid='-' WHERE usrbeid='$beId'")) {
                        $partialErrors = $partialErrors."Failed to update booking database: ".mysqli_error($link)."<br>";
                      }
                      $sqlAllPlaces = $link->query("SELECT beid FROM places WHERE usrbeid='$beId'");
                      if ($sqlAllPlaces->num_rows > 0) {
                        while($rowPlaces = $sqlAllPlaces->fetch_assoc()) {
                          $numOfPlaces = $sqlAllPlaces->num_rows;
                          $plcBeID = $rowPlaces['beid'];
                          $today = date("Y-m-d");
                          $sqlBookingR = $link->query("SELECT * FROM booking WHERE plcbeid='$plcBeID' and status='waiting'");
                          $sqlBookingN = $link->query("SELECT * FROM booking WHERE plcbeid='$plcBeID' and fromdate > '$today' and status='booked'");
                          array_push($canceledBookingsArray, [
                            "beID" => $plcBeID,
                            "max" => $sqlBookingR->num_rows + $sqlBookingN->num_rows,
                            "done" => 0
                          ]);
                          if ($sqlBookingR->num_rows > 0) {
                            while ($bookingR = $sqlBookingR->fetch_assoc()) {
                              $bookingBeId = $bookingR['beid'];
                              $sqlRejectBookingUpdateRequests = "UPDATE bookingupdaterequest SET status='rejected' WHERE bookingbeid='$bookingBeId'";
                              if (!mysqli_query($link, $sqlRejectBookingUpdateRequests)) {
                                $partialErrors = $partialErrors."Failed to update bookingupdaterequest database (reject): ".mysqli_error($link)."<br>";
                              }
                              $rejectSts = rejectBooking($bookingBeId, "../../../../");
                              if ($rejectSts != "mails") {
                                canceledBookingsDone();
                              }
                            }
                          }
                          if ($sqlBookingN->num_rows > 0) {
                            while ($bookingN = $sqlBookingN->fetch_assoc()) {
                              $bookingBeId = $bookingN['beid'];
                              $sqlNotifyBookingUpdateRequests = "UPDATE bookingupdaterequest SET status='rejected' WHERE bookingbeid='$bookingBeId'";
                              if (!mysqli_query($link, $sqlNotifyBookingUpdateRequests)) {
                                $partialErrors = $partialErrors."Failed to update bookingupdaterequest database (cancel): ".mysqli_error($link)."<br>";
                              }
                              if ($bookingN['email'] != "-") {
                                $sqlBookNPlace = $link->query("SELECT * FROM places WHERE beid='$plcBeID' LIMIT 1");
                                $rowBookNPlace = $sqlBookNPlace->fetch_assoc();
                                cancelBooking(
                                  $plcBeID,
                                  $bookingN['fromd'],
                                  $bookingN['fromm'],
                                  $bookingN['fromy'],
                                  $bookingN['tod'],
                                  $bookingN['tom'],
                                  $bookingN['toy'],
                                  $hostName,
                                  $hostEmail,
                                  $hostPhone,
                                  "place",
                                  true
                                );
                              } else {
                                canceledBookingsDone();
                              }
                            }
                          }
                        }
                        canceledBookingsCheck();
                      } else {
                        deletProfileImage();
                      }
                    } else {
                      outputCreate(2, "Failed to update user archive database ".mysqli_error($linkBD));
                    }
                  } else {
                    outputCreate(2, "Failed to update user database ".mysqli_error($link));
                  }
                } else {
                  outputCreate(2, "Failed to cancel from admins database: ".$adminCancelSts);
                }
              } else {
                outputCreate(2, "This profile has not paid all booking fees and therefore cannot be cancelled");
              }
            } else {
              outputCreate(2, "Wrong password");
            }
          } else {
            outputCreate(2, "User status is already 'delete'");
          }
        }
      } else {
        outputCreate(2, "User data does not match the database. Try refreshing the page");
      }
    } else {
      outputCreate(2, "Failed to get user data from frontend. Try refreshing the page");
    }
  } else {
    outputCreate(2, "Password field is empty");
  }

  function mailDone($sts, $mailType) {
    global $partialErrors;
    if ($sts != "done") {
      $partialErrors = $partialErrors."(".$mailType.") - mail failed<br>";
    }
    canceledBookingsDone();
  }

  function cancelBookingOutput($sts, $msg) {
    global $partialErrors;
    if ($sts == "error") {
      $partialErrors = $partialErrors."Cancel booking failed: ".$msg."<br>";
    }
    canceledBookingsDone();
  }

  function canceledBookingsDone() {
    global $canceledBookingsArray;
    $cBD = 0;
    $addOneDone = false;
    while (!$addOneDone) {
      if ($cBD < sizeof($canceledBookingsArray)) {
        if ($canceledBookingsArray[$cBD]["max"] > $canceledBookingsArray[$cBD]["done"]) {
          ++$canceledBookingsArray[$cBD]["done"];
          $addOneDone = true;
        } else {
          ++$cBD;
        }
      } else {
        $addOneDone = true;
      }
    }
    canceledBookingsCheck();
  }

  function canceledBookingsCheck() {
    global $canceledBookingsArray, $allBookingsCanceled;
    $canceledBookingsNotDone = false;
    for ($cBD=0; $cBD < sizeof($canceledBookingsArray); $cBD++) {
      if ($canceledBookingsArray[$cBD]["max"] > $canceledBookingsArray[$cBD]["done"]) {
        $canceledBookingsNotDone = true;
      }
    }
    if (!$canceledBookingsNotDone && !$allBookingsCanceled) {
      $allBookingsCanceled = true;
      for ($cott=0; $cott < sizeof($canceledBookingsArray); $cott++) {
        placeDelete($canceledBookingsArray[$cott]["beID"]);
      }
    }
  }

  $numOfPlacesDone = 0;
  function placeDeleteOutput($sts) {
    global $numOfPlacesDone, $numOfPlaces, $partialErrors;
    ++$numOfPlacesDone;
    if ($sts != "done") {
      $partialErrors = $partialErrors."Delete place failed: ".$sts."<br>";
    }
    if ($numOfPlacesDone == $numOfPlaces) {
      deletProfileImage();
    }
  }

  function deletProfileImage() {
    global $link, $beId, $partialErrors;
    $sqlOut = "UPDATE signin SET status='out' WHERE beid='$beId'";
    if (mysqli_query($link, $sqlOut)) {
      $sqlPImg = $link->query("SELECT * FROM images WHERE beid='$beId' AND (status='prf-small' OR status='prf-mid' OR status='prf-big')");
      $prfImgN = $sqlPImg->num_rows;
      $prfImgNCh = 0;
      if ($prfImgN > 0){
        while($pImg = $sqlPImg->fetch_assoc()) {
          $namePImg = $pImg['name'];
          $srcPImg = $pImg['src'];
          $sqlPImgD = "UPDATE images SET status='delete' WHERE name='$namePImg'";
          if (mysqli_query($link, $sqlPImgD)) {
            if (file_exists("../../../".$srcPImg)) {
              unlink("../../../".$srcPImg);
            }
          } else {
            $partialErrors = $partialErrors."Failed to update status to 'delete' in images database: ".mysqli_error($link)."<br>";
          }
          $prfImgNCh++;
          if ($prfImgNCh >= $prfImgN) {
            destroyBrowserData();
          }
        }
      } else {
        destroyBrowserData();
      }
    } else {
      $partialErrors = $partialErrors."Failed to update status to 'out' in signin database: ".mysqli_error($link)."<br>";
    }
  }

  function destroyBrowserData() {
    unset($_COOKIE['signID']);
    setcookie('signID', null, -1, '/');
    unset($_COOKIE['email']);
    setcookie('email', null, -1, '/');
    session_destroy();
    session_unset();
    outputCreate(1, "done");
  }

  function outputCreate($sts, $msg) {
    global $output, $partialErrors;
    if ($partialErrors != "") {
      if ($msg == "done") {
        $msg = $partialErrors;
      } else {
        $msg = $msg."<br>".$partialErrors;
      }
    }
    array_push($output, [
      "sts" => $sts,
      "msg" => $msg
    ]);
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
