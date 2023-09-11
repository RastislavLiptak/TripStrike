<?php
  include "../uni/code/php-backend/get-account-data.php";
  include "../email-sender/php-backend/ask-for-rating-mail.php";
  include "../email-sender/php-backend/ask-for-rating-update-mail.php";
  function rateYourBookingHandler() {
    global $link;
    $date = date("Y-m-d H:i:s");
    $sqlAllBookings = $link->query("SELECT * FROM booking WHERE ratingMail='0' and status='booked'");
    if ($sqlAllBookings->num_rows > 0) {
      while($allBookings = $sqlAllBookings->fetch_assoc()) {
        if ($allBookings['lastday'] == "half") {
          $toDate = $allBookings['todate']." 14:00";
        } else {
          $toDate = $allBookings['todate']." 00:00";
        }
        $diff = abs(strtotime($toDate) - strtotime($date));
        $hours = floor($diff / 3600);
        if ($date < $toDate) {
          $hours = $hours * -1;
        }
        if ($allBookings['email'] != "-") {
          if ($hours >= 24) {
            $plcBeID = $allBookings['plcbeid'];
            $sqlPlc = $link->query("SELECT * FROM places WHERE beid='$plcBeID' and status='active'");
            if ($sqlPlc->num_rows > 0) {
              $plc = $sqlPlc->fetch_assoc();
              $usrBeID = $plc['usrbeid'];
              $sqlUsr = $link->query("SELECT * FROM users WHERE beid='$usrBeID' and status='active'");
              if ($sqlUsr->num_rows > 0) {
                $usr = $sqlUsr->fetch_assoc();
                $guestBeId = $allBookings['usrbeid'];
                if ($guestBeId != "-") {
                  if (getAccountData($guestBeId, "feature-add-rating") == "good") {
                    $sqlRatingPlcList = $link->query("SELECT * FROM rating WHERE beid='$plcBeID' and critic='$guestBeId'");
                    if ($sqlRatingPlcList->num_rows > 0) {
                      $sqlRating = $link->query("SELECT * FROM rating WHERE (beid='$plcBeID' or beid='$usrBeID') and critic='$guestBeId'");
                      if ($sqlRating->num_rows > 0) {
                        $ratingPlcLct = "-";
                        $ratingPlcTidy = "-";
                        $ratingPlcPrc = "-";
                        $ratingPlcPark = "-";
                        $ratingPlcAd = "-";
                        $ratingHstLang = "-";
                        $ratingHstComm = "-";
                        $ratingHstPrsn = "-";
                        while($rowRating = $sqlRating->fetch_assoc()) {
                          if ($rowRating['section'] == "lct") {
                            $ratingPlcLct = str_replace('.',',',round($rowRating['percentage'] * 5 / 100, 2))."/5";
                          } else if ($rowRating['section'] == "tidy") {
                            $ratingPlcTidy = str_replace('.',',',round($rowRating['percentage'] * 5 / 100, 2))."/5";
                          } else if ($rowRating['section'] == "prc") {
                            $ratingPlcPrc = str_replace('.',',',round($rowRating['percentage'] * 5 / 100, 2))."/5";
                          } else if ($rowRating['section'] == "park") {
                            $ratingPlcPark = str_replace('.',',',round($rowRating['percentage'] * 5 / 100, 2))."/5";
                          } else if ($rowRating['section'] == "ad") {
                            $ratingPlcAd = str_replace('.',',',round($rowRating['percentage'] * 5 / 100, 2))."/5";
                          } else if ($rowRating['section'] == "lang") {
                            $ratingPlcLang = str_replace('.',',',round($rowRating['percentage'] * 5 / 100, 2))."/5";
                          } else if ($rowRating['section'] == "comm") {
                            $ratingPlcComm = str_replace('.',',',round($rowRating['percentage'] * 5 / 100, 2))."/5";
                          } else if ($rowRating['section'] == "prsn") {
                            $ratingPlcPrsn = str_replace('.',',',round($rowRating['percentage'] * 5 / 100, 2))."/5";
                          }
                        }
                        askForRatingUpdateMail(
                          $allBookings['beid'],
                          getFrontendId($allBookings['beid']),
                          $allBookings['fromd'],
                          $allBookings['fromm'],
                          $allBookings['fromy'],
                          $allBookings['tod'],
                          $allBookings['tom'],
                          $allBookings['toy'],
                          $allBookings['guestnum'],
                          getFrontendId($plc['beid']),
                          $plc['name'],
                          getFrontendId($usrBeID),
                          $usr['firstname'],
                          $usr['lastname'],
                          $allBookings['email'],
                          $allBookings['language'],
                          $ratingPlcLct,
                          $ratingPlcTidy,
                          $ratingPlcPrc,
                          $ratingPlcPark,
                          $ratingPlcAd,
                          $ratingHstLang,
                          $ratingHstComm,
                          $ratingHstPrsn
                        );
                      }
                    } else {
                      askForRatingMail(
                        $allBookings['beid'],
                        getFrontendId($allBookings['beid']),
                        $allBookings['fromd'],
                        $allBookings['fromm'],
                        $allBookings['fromy'],
                        $allBookings['tod'],
                        $allBookings['tom'],
                        $allBookings['toy'],
                        $allBookings['guestnum'],
                        getFrontendId($plc['beid']),
                        $plc['name'],
                        getFrontendId($usrBeID),
                        $usr['firstname'],
                        $usr['lastname'],
                        $allBookings['email'],
                        $allBookings['language']
                      );
                    }
                  }
                } else {
                  askForRatingMail(
                    $allBookings['beid'],
                    getFrontendId($allBookings['beid']),
                    $allBookings['fromd'],
                    $allBookings['fromm'],
                    $allBookings['fromy'],
                    $allBookings['tod'],
                    $allBookings['tom'],
                    $allBookings['toy'],
                    $allBookings['guestnum'],
                    getFrontendId($plc['beid']),
                    $plc['name'],
                    getFrontendId($usrBeID),
                    $usr['firstname'],
                    $usr['lastname'],
                    $allBookings['email'],
                    $allBookings['language']
                  );
                }
              }
            }
          }
        }
      }
    }
  }
?>
