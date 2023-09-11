<?php
  include "../uni/code/php-head.php";
  include "../uni/code/php-backend/rating-summary.php";
  include "php-backend/uni/rating-verify.php";
  $subtitle = $wrd_ratingCapital;
  if (
    isset($_GET['booking']) &&
    isset($_GET['fromd']) &&
    isset($_GET['fromm']) &&
    isset($_GET['fromy']) &&
    isset($_GET['tod']) &&
    isset($_GET['tom']) &&
    isset($_GET['toy']) &&
    isset($_GET['plc'])
  ) {
    $rt_booking = $_GET['booking'];
    $rt_fromd = $_GET['fromd'];
    $rt_fromm = $_GET['fromm'];
    $rt_fromy = $_GET['fromy'];
    $rt_tod = $_GET['tod'];
    $rt_tom = $_GET['tom'];
    $rt_toy = $_GET['toy'];
    $rt_plcID = $_GET['plc'];
    $ratingsReady = ratingVerify($rt_plcID, $rt_booking, $rt_fromd, $rt_fromm, $rt_fromy, $rt_tod, $rt_tom, $rt_toy);
    if ($ratingsReady != "place-with-ID-n-exist") {
      $sqlBeId = $link->query("SELECT beid FROM idlist WHERE id='$rt_plcID'");
      $rt_plcBeId = $sqlBeId->fetch_assoc()['beid'];
      if ($ratingsReady == "relocation") {
        $sqlLasId = $link->query("SELECT id FROM idlist WHERE beid='$rt_plcBeId' ORDER BY fullDate DESC LIMIT 1");
        $rt_plcLastId = $sqlLasId->fetch_assoc()['id'];
        header("Location: ../ratings/?booking=".$rt_booking."&fromd=".$rt_fromd."&fromm=".$rt_fromm."&fromy=".$rt_fromy."&tod=".$rt_tod."&tom=".$rt_tom."&toy=".$rt_toy."&plc=".$rt_plcLastId);
      }
    }
  } else {
    $ratingsReady = "lack-of-data";
    $rt_plcID = "#";
  }
  $plcName = "";
  $plcNameShrt = "";
  $plcHostID = "#";
  $plcHostName = "";
  $plcAllreadyRated = false;
  $criticsRatingSummaryPlace = 0;
  $criticsRatingSummaryHost = 0;
  $ratingPlcSectLct = "none";
  $ratingPlcSectTidy = "none";
  $ratingPlcSectPrc = "none";
  $ratingPlcSectPark = "none";
  $ratingPlcSectAd = "none";
  $hostAllreadyRated = false;
  $ratingHstSectLang = "none";
  $ratingHstSectComm = "none";
  $ratingHstSectPrsn = "none";
  if ($ratingsReady == "good") {
    $sqlPlace = $link->query("SELECT * FROM places WHERE beid='$rt_plcBeId'");
    $plc = $sqlPlace->fetch_assoc();
    $hostBeID = $plc['usrbeid'];
    $sqlHost = $link->query("SELECT * FROM users WHERE beid='$hostBeID'");
    $hst = $sqlHost->fetch_assoc();
    $plcName = $plc['name'];
    if (strlen($plc['name']) > 28) {
      $plcNameShrt = mb_substr($plc['name'], 0, 25, "utf-8")."...";
    } else {
      $plcNameShrt = $plc['name'];
    }
    $plcHostID = getFrontendId($hostBeID);
    if (strlen($hst['firstname']." ".$hst['lastname']) > 28) {
      $plcHostName = mb_substr($hst['firstname']." ".$hst['lastname'], 0, 25, "utf-8")."...";
    } else {
      $plcHostName = $hst['firstname']." ".$hst['lastname'];
    }
    $sqlCriticsSummaryPlace = $link->query("SELECT percentage FROM ratingcriticsummary WHERE beid='$rt_plcBeId' and critic='$usrBeId'");
    if ($sqlCriticsSummaryPlace->num_rows > 0) {
      $criticsRatingSummaryPlace = $sqlCriticsSummaryPlace->fetch_assoc()['percentage'];
      $criticsRatingSummaryPlace = str_replace('.',',',round($criticsRatingSummaryPlace * 5 / 100, 2));
    }
    $sqlCriticsSummaryHost = $link->query("SELECT percentage FROM ratingcriticsummary WHERE beid='$hostBeID' and critic='$usrBeId'");
    if ($sqlCriticsSummaryHost->num_rows > 0) {
      $criticsRatingSummaryHost = $sqlCriticsSummaryHost->fetch_assoc()['percentage'];
      $criticsRatingSummaryHost = str_replace('.',',',round($criticsRatingSummaryHost * 5 / 100, 2));
    }
    $sqlPlcRating = $link->query("SELECT * FROM rating WHERE beid='$rt_plcBeId' and critic='$usrBeId'");
    if ($sqlPlcRating->num_rows > 0) {
      $plcAllreadyRated = true;
      while($rowPlcRating = $sqlPlcRating->fetch_assoc()) {
        if ($rowPlcRating['section'] == "lct") {
          $ratingPlcSectLct = $rowPlcRating['percentage'];
        } else if ($rowPlcRating['section'] == "tidy") {
          $ratingPlcSectTidy = $rowPlcRating['percentage'];
        } else if ($rowPlcRating['section'] == "prc") {
          $ratingPlcSectPrc = $rowPlcRating['percentage'];
        } else if ($rowPlcRating['section'] == "park") {
          $ratingPlcSectPark = $rowPlcRating['percentage'];
        } else if ($rowPlcRating['section'] == "ad") {
          $ratingPlcSectAd = $rowPlcRating['percentage'];
        }
      }
      $sqlHostRating = $link->query("SELECT * FROM rating WHERE beid='$hostBeID' and critic='$usrBeId'");
      if ($sqlHostRating->num_rows > 0) {
        $hostAllreadyRated = true;
        while($rowHostRating = $sqlHostRating->fetch_assoc()) {
          if ($rowHostRating['section'] == "lang") {
            $ratingHstSectLang = $rowHostRating['percentage'];
          } else if ($rowHostRating['section'] == "comm") {
            $ratingHstSectComm = $rowHostRating['percentage'];
          } else if ($rowHostRating['section'] == "prsn") {
            $ratingHstSectPrsn = $rowHostRating['percentage'];
          }
        }
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="<?php echo $wrd_htmlLang; ?>">
  <head>
    <meta charset="UTF-8">
    <?php
      include "../uni/code/php-frontend/head.php";
    ?>
    <script src="../uni/code/js/set-up-step-by-step.js"></script>
    <script src="js/uni/ratings.js"></script>
    <script src="js/uni/overview.js"></script>
    <script src="js/places/overall-rating.js"></script>
    <link rel="stylesheet" type="text/css" href="../uni/code/css/set-up-step-by-step.css">
    <link rel="stylesheet" type="text/css" href="css/uni/overview.css">
    <link rel="stylesheet" type="text/css" href="css/users/rate-host.css">
    <meta name="description" content="<?php echo $wrd_metaDescriptionRatings; ?>">
    <title><?php echo $wrd_ratingCapital." - ".$plcName." - ".$title; ?></title>
  </head>
  <body onload="<?php echo $onload; ?>">
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
      if ($ratingsReady == "good") {
    ?>
      <div class="set-up-step-by-step-wrp">
        <div class="set-up-step-by-step-blck">
          <?php
            include "php-frontend/uni/overview.php";
            // cottage
            include "php-frontend/places/overall-rating.php";
            // section - lct: location
            include "php-frontend/places/location.php";
            // section - tidy: tidines
            include "php-frontend/places/tidines.php";
            // section - prc: price
            include "php-frontend/places/price.php";
            // section - park: parking
            include "php-frontend/places/parking.php";
            // section - ad: web
            include "php-frontend/places/ad.php";
            // comment
            include "php-frontend/places/comment.php";

            // user
            include "php-frontend/users/rate-host.php";
            // section - lang: language
            include "php-frontend/users/language.php";
            // section - comm: communication
            include "php-frontend/users/communication.php";
            // section - prsn: personality
            include "php-frontend/users/personality.php";
            // comment
            include "php-frontend/users/comment.php";
          ?>
        </div>
      </div>
    <?php
      } else {
    ?>
      <div class="page-error">
        <p class="page-error-p">
          <?php
            if ($ratingsReady == "lack-of-data") {
              echo $wrd_theURLDoesNotContainEnoughDataAreYouSureYouHaveTheRightLink;
            } else if ($ratingsReady == "place-with-ID-n-exist" || $ratingsReady == "place-n-exist") {
              echo $wrd_cottageWithIdNotExist." (".$ratingsReady.")";
            } else if ($ratingsReady == "to-many-places") {
              echo $wrd_foundMoreThanOneCottageWithTheSameID;
            } else if ($ratingsReady == "host-n-exist") {
              echo $wrd_noCottageHostFound;
            } else if ($ratingsReady == "to-many-hosts") {
              echo $wrd_moreThanOneHostWithTheSameDataWasFound;
            } else if ($ratingsReady == "booking-n-exist") {
              echo $wrd_noBookingFound;
            } else if ($ratingsReady == "to-many-bookings") {
              echo $wrd_moreThanOneBookingWithTheSameDataWasFound;
            } else if ($ratingsReady == "less-than-12h") {
              echo $wrd_youCanRateYourBookingAsSoonAs12HoursAfterItsEnd;
            } else if ($ratingsReady == "task-is-banned") {
              echo $wrd_youHaveBeenBannedThisFeatureIfAnErrorHasOccurredOrWantYouToKnowMoreContactUs;
            } else if ($ratingsReady == "task-not-available") {
              echo $wrd_benefitNotAllowed;
            } else if ($ratingsReady == "task-unexpected-status") {
              echo $wrd_weWereUnableToDetermineWithCertaintyWhetherOrNotYouHaveThisFeatureEnabledThereforePleaseContactUsOrFillInAndSendTheApplicationAgainWeApologizeForTheInconvenience;
            } else if ($ratingsReady == "sign-up") {
              echo $wrd_signUpToRateYourReservation;
            } else if ($ratingsReady == "sign-in") {
              echo $wrd_signInToRateYourReservation;
            } else {
              echo $wrd_unknownError." (".$ratingsReady.")";
            }
          ?>
        </p>
        <?php
          if ($ratingsReady == "sign-up") {
        ?>
          <div class="page-error-btn-wrp">
            <button class="btn btn-mid btn-prim page-error-btn" onclick="signUpModal('show')"><?php echo $wrd_signUp ?></button>
          </div>
        <?php
          } else if ($ratingsReady == "sign-in") {
        ?>
          <div class="page-error-btn-wrp">
            <button class="btn btn-mid btn-prim page-error-btn" onclick="signInModal('show')"><?php echo $wrd_signIn ?></button>
          </div>
        <?php
          }
         ?>
      </div>
    <?php
      }
    ?>
  </body>
</html>
