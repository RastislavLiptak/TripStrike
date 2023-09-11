<?php
  include "random-hash-maker.php";
  function ratingSummary($beId) {
    global $link;
    $sqlRating = $link->query("SELECT * FROM rating WHERE beid='$beId'");
    if ($sqlRating->num_rows > 0) {
      $sqlID = $link->query("SELECT type FROM backendidlist WHERE beid='$beId' LIMIT 1");
      if ($sqlID->num_rows == 1) {
        $idType = $sqlID->fetch_assoc()['type'];
        ratingSectionSummary($beId);
        $rateTotal = 0;
        $rateNum = 0;
        $sqlSectPerct = $link->query("SELECT percentage FROM ratingsectionsummary WHERE beid='$beId'");
        if ($sqlSectPerct->num_rows > 0) {
          while($perct = $sqlSectPerct->fetch_assoc()) {
            $rateTotal = $rateTotal + $perct['percentage'];
            ++$rateNum;
          }
          if ($idType == "cottage") {
            $sqlUsr = $link->query("SELECT usrbeid FROM places WHERE beid='$beId'");
            if ($sqlUsr->num_rows == 1) {
              $usrBeId = $sqlUsr->fetch_assoc()['usrbeid'];
              ratingSummary($usrBeId);
              $sqlUsrRating = $link->query("SELECT percentage FROM ratingsummary WHERE beid='$usrBeId'");
              if ($sqlUsrRating->num_rows > 0) {
                $rateTotal = $rateTotal + $sqlUsrRating->fetch_assoc()["percentage"];
                ++$rateNum;
              }
            } else {
              //no cottage with this id or there is more than one
            }
          }
          $averagePercentage = $rateTotal / $rateNum;
          $sqlRatingCheck = $link->query("SELECT percentage FROM ratingsummary WHERE beid='$beId'");
          if ($sqlRatingCheck->num_rows > 1) {
            $delete = true;
          } else {
            if ($sqlRatingCheck->num_rows > 0) {
              if ($averagePercentage != $sqlRatingCheck->fetch_assoc()['percentage']) {
                $delete = true;
              } else {
                $delete = false;
                $insert = false;
              }
            } else {
              $delete = false;
              $insert = true;
            }
          }
          if ($delete) {
            $sqlRatingDelete = "DELETE FROM ratingsummary WHERE beid='$beId'";
            if (mysqli_query($link, $sqlRatingDelete)) {
              $insert = true;
            } else {
              $insert = false;
            }
          }
          if ($insert) {
            $sqlRatingInsert = "INSERT INTO ratingsummary (beid, percentage) VALUES ('$beId', '$averagePercentage')";
            mysqli_query($link, $sqlRatingInsert);
          }
        } else {
          // no section ratings
        }
      } else {
        // backend ID not exist or there is more than one
      }
    } else {
      //no rating
    }
  }

  $sectsDone = [];
  function ratingSectionSummary($beId) {
    global $link, $sectsDone;
    $sqlSect = $link->query("SELECT section FROM rating WHERE beid='$beId' and section NOT IN ('".implode("', '", $sectsDone)."') LIMIT 1");
    if ($sqlSect->num_rows > 0) {
      $section = $sqlSect->fetch_assoc()['section'];
      $sectTotal = 0;
      $sectRatingsNum = 0;
      $sqlCalc = $link->query("SELECT percentage FROM rating WHERE beid='$beId' and section='$section'");
      if ($sqlCalc->num_rows > 0) {
        while($calc = $sqlCalc->fetch_assoc()) {
          $sectTotal = $sectTotal + $calc['percentage'];
          ++$sectRatingsNum;
        }
        $averageSectPerct = $sectTotal / $sectRatingsNum;
        $sqlSectSumm = $link->query("SELECT percentage FROM ratingsectionsummary WHERE beid='$beId' and section='$section'");
        if ($sqlSectSumm->num_rows > 1) {
          $delete = true;
        } else {
          if ($sqlSectSumm->num_rows > 0) {
            if ($averageSectPerct != $sqlSectSumm->fetch_assoc()['percentage']) {
              $delete = true;
            } else {
              $delete = false;
              $insert = false;
            }
          } else {
            $delete = false;
            $insert = true;
          }
        }
        if ($delete) {
          $sqlSectRatingDelete = "DELETE FROM ratingsectionsummary WHERE beid='$beId' and section='$section'";
          if (mysqli_query($link, $sqlSectRatingDelete)) {
            $insert = true;
          } else {
            $insert = false;
          }
        }
        if ($insert) {
          $sqlSectRating = "INSERT INTO ratingsectionsummary (beid, section, percentage) VALUES ('$beId', '$section', '$averageSectPerct')";
          mysqli_query($link, $sqlSectRating);
        }
      } else {
        //no rating for this section
      }
      array_push($sectsDone, $section);
      ratingSectionSummary($beId);
    } else {
      //no ratings
    }
  }

  $criticDone = [];
  function ratingCriticSummary($beId) {
    global $link, $criticDone;
    $sqlCritic = $link->query("SELECT critic FROM rating WHERE beid='$beId' and critic NOT IN ('".implode("', '", $criticDone)."') LIMIT 1");
    if ($sqlCritic->num_rows > 0) {
      $critic = $sqlCritic->fetch_assoc()['critic'];
      $criticTotal = 0;
      $criticRatingsNum = 0;
      $sqlCalc = $link->query("SELECT percentage FROM rating WHERE beid='$beId' and critic='$critic'");
      if ($sqlCalc->num_rows > 0) {
        while($calc = $sqlCalc->fetch_assoc()) {
          $criticTotal = $criticTotal + $calc['percentage'];
          ++$criticRatingsNum;
        }
        $averageCriticPerct = $criticTotal / $criticRatingsNum;
        $sqlCriticSumm = $link->query("SELECT percentage FROM ratingcriticsummary WHERE beid='$beId' and critic='$critic'");
        if ($sqlCriticSumm->num_rows > 1) {
          $delete = true;
        } else {
          if ($sqlCriticSumm->num_rows > 0) {
            if ($averageCriticPerct != $sqlCriticSumm->fetch_assoc()['percentage']) {
              $delete = true;
            } else {
              $delete = false;
              $insert = false;
            }
          } else {
            $delete = false;
            $insert = true;
          }
        }
        if ($delete) {
          $sqlCriticRatingDelete = "DELETE FROM ratingcriticsummary WHERE beid='$beId' and critic='$critic'";
          if (mysqli_query($link, $sqlCriticRatingDelete)) {
            $insert = true;
          } else {
            $insert = false;
          }
        }
        if ($insert) {
          $date = date("Y-m-d H:i:s");
          $dateY = date("Y");
          $dateM = date("m");
          $dateD = date("d");
          $crIdCheck = false;
          while (!$crIdCheck) {
            $crId = randomHash(11);
            $sqlCrIdCH = $link->query("SELECT * FROM idlist WHERE id='$crId'");
            if ($sqlCrIdCH->num_rows == 0) {
              $crIdCheck = true;
            } else {
              $crIdCheck = false;
            }
          }
          $crBeIdCheck = false;
          while (!$crBeIdCheck) {
            $crBeId = randomHash(64);
            $sqlCrBeIdCH = $link->query("SELECT * FROM backendidlist WHERE beid='$crBeId'");
            if ($sqlCrBeIdCH->num_rows == 0) {
              $crBeIdCheck = true;
            } else {
              $crBeIdCheck = false;
            }
          }
          $backendIDNum = $link->query("SELECT * FROM backendidlist")->num_rows;
          $sqlCrBeID = "INSERT INTO backendidlist (beid, numid, type) VALUES('$crBeId', '$backendIDNum', 'rating-critic')";
          if (mysqli_query($link, $sqlCrBeID)) {
            $sqlCrID = "INSERT INTO idlist(beid, id, fullDate, dateD, dateM, dateY) VALUES('$crBeId', '$crId', '$date', '$dateD', '$dateM', '$dateY')";
            if (mysqli_query($link, $sqlCrID)) {
              $sqlCriticRating = "INSERT INTO ratingcriticsummary (critic, beid, ratingbeid, percentage, fulldate, datey, datem, dated) VALUES ('$critic', '$beId', '$crBeId', '$averageCriticPerct', '$date', '$dateY', '$dateM', '$dateD')";
              mysqli_query($link, $sqlCriticRating);
            }
          }
        }
      } else {
        //no rating for this section
      }
      array_push($criticDone, $critic);
      ratingCriticSummary($beId);
    } else {
      //no ratings
    }


  }
?>
