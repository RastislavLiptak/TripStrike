<?php
  function ratingVerify($rt_plcID, $rt_bookingID, $rt_fromd, $rt_fromm, $rt_fromy, $rt_tod, $rt_tom, $rt_toy) {
    global $link, $usrBeId, $sign, $bnft_add_rating;
    $sqlBookingBeId = $link->query("SELECT beid FROM idlist WHERE id='$rt_bookingID'");
    if ($sqlBookingBeId->num_rows > 0) {
      $rt_bookingBeId = $sqlBookingBeId->fetch_assoc()['beid'];
      $sqlPlaceBeId = $link->query("SELECT beid FROM idlist WHERE id='$rt_plcID'");
      if ($sqlPlaceBeId->num_rows > 0) {
        $rt_plcBeId = $sqlPlaceBeId->fetch_assoc()['beid'];
        $sqlLasId = $link->query("SELECT id FROM idlist WHERE beid='$rt_plcBeId' ORDER BY fullDate DESC LIMIT 1");
        $rt_plcLastId = $sqlLasId->fetch_assoc()['id'];
        if ($rt_plcID != $rt_plcLastId) {
          return "relocation";
        } else {
          $sqlPlace = $link->query("SELECT * FROM places WHERE beid='$rt_plcBeId'");
          if ($sqlPlace->num_rows > 0) {
            if ($sqlPlace->num_rows == 1) {
              $plc = $sqlPlace->fetch_assoc();
              $hostBeID = $plc['usrbeid'];
              $sqlHost = $link->query("SELECT * FROM users WHERE beid='$hostBeID'");
              if ($sqlHost->num_rows > 0) {
                if ($sqlHost->num_rows == 1) {
                  $hst = $sqlHost->fetch_assoc();
                  $sqlBooking = $link->query("SELECT * FROM booking WHERE beid='$rt_bookingBeId' and plcbeid='$rt_plcBeId' and fromd='$rt_fromd' and fromm='$rt_fromm' and fromy='$rt_fromy' and tod='$rt_tod' and tom='$rt_tom' and toy='$rt_toy'");
                  if ($sqlBooking->num_rows > 0) {
                    if ($sqlBooking->num_rows == 1) {
                      $book = $sqlBooking->fetch_assoc();
                      $date = date("Y-m-d H:i:s");
                      if ($book['lastday'] == "half") {
                        $toDate = $book['todate']." 11:00";
                      } else {
                        $toDate = $book['todate']." 23:59";
                      }
                      $diff = abs(strtotime($toDate) - strtotime($date));
                      $hours = floor($diff / 3600);
                      if ($date < $toDate) {
                        $hours = $hours * -1;
                      }
                      if ($hours >= 12) {
                        $guestBeId = $book['usrbeid'];
                        if ($guestBeId != "-") {
                          if ($sign == "yes") {
                            if ($usrBeId == $guestBeId) {
                              if ($bnft_add_rating == "good") {
                                return "good";
                              } else {
                                if ($bnft_add_rating == "none") {
                                  return "task-not-available";
                                } else if ($bnft_add_rating == "ban") {
                                  return "task-is-banned";
                                } else {
                                  return "task-unexpected-status";
                                }
                              }
                            } else {
                              return "This booking seems not to belong to you";
                            }
                          } else {
                            return "sign-in";
                          }
                        } else {
                          return "sign-up";
                        }
                      } else {
                        return "less-than-12h";
                      }
                    } else {
                      return "to-many-bookings";
                    }
                  } else {
                    return "booking-n-exist";
                  }
                } else {
                  return "to-many-hosts";
                }
              } else {
                return "host-n-exist";
              }
            } else {
              return "to-many-places";
            }
          } else {
            return "place-n-exist";
          }
        }
      } else {
        return "place-with-ID-n-exist";
      }
    } else {
      return "booking-with-ID-n-exist";
    }
  }
?>
