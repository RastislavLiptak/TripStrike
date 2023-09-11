<?php
  include "../uni/code/php-head.php";
  include "../uni/code/php-backend/add-currency.php";
  include "../uni/code/php-backend/youtube-about-video.php";
  $plcName = $wrd_unknown;
  $plcDesc = "";
  $plcId = "";
  $plcLat = 0.000000000000000;
  $plcLng = 0.000000000000000;
  $plcGuestMaxNum = 0;
  $plcType = "cottage";
  $plcBedroom = 0;
  $plcBathrooms = 0;
  $plcDistanceFromTheWater = "";
  $plcOperation = "year-round";
  $plcOperationFrom = 0;
  $plcOperationTo = 0;
  $plcPriceWork = 0;
  $plcPriceWeek = 0;
  $plcPriceMode = "nights";
  if ($bnft_edit_cottage == "good") {
    $editor_sts = "unset";
    if (isset($_GET['id'])) {
      $plcId = $_GET['id'];
      $editor_sts = "good";
    }
    if ($editor_sts == "good") {
      $sqlBeId = $link->query("SELECT beid FROM idlist WHERE id='$plcId'");
      if ($sqlBeId->num_rows > 0) {
        $plcBeId = $sqlBeId->fetch_assoc()['beid'];
        $sqlLasId = $link->query("SELECT id FROM idlist WHERE beid='$plcBeId' ORDER BY fullDate DESC LIMIT 1");
        $plcLastId = $sqlLasId->fetch_assoc()['id'];
        if ($plcId != $plcLastId) {
          header("Location: ../editor/?id=".$plcLastId);
        }
      } else {
        $editor_sts = "not-exist";
      }
    }
    if ($editor_sts == "good") {
      $sqlPlace = $link->query("SELECT * FROM places WHERE beid='$plcBeId'");
      if ($sqlPlace->num_rows > 0) {
        if ($sqlPlace->num_rows == 1) {
          $plc = $sqlPlace->fetch_assoc();
          if ($plc['status'] == "active") {
            if ($plc['usrbeid'] == $usrBeId) {
              $plcName = $plc['name'];
              $plcLat = $plc['lat'];
              $plcLng = $plc['lng'];
              $plcDesc = $plc['description'];
              $plcGuestMaxNum = $plc['guestNum'];
              $plcType = $plc["type"];
              $plcBedroom = $plc["bedNum"];
              $plcBathrooms = $plc["bathNum"];
              $plcDistanceFromTheWater = $plc["distanceFromTheWater"];
              $plcOperation = $plc["operation"];
              $plcOperationFrom = $plc["operationFrom"];
              $plcOperationTo = $plc["operationTo"];
              $plcPriceWork = $plc["workDayPrice"];
              $plcPriceWeek = $plc["weekDayPrice"];
              $plcPriceMode = $plc["priceMode"];
              if (is_numeric($plcPriceWork) && floor($plcPriceWork) == $plcPriceWork) {
                $plcPriceWork = floor($plcPriceWork);
              }
              if (is_numeric($plcPriceWeek) && floor($plcPriceWeek) == $plcPriceWeek) {
                $plcPriceWeek = floor($plcPriceWeek);
              }
            } else {
              $editor_sts = "foreign";
            }
          } else if ($plc['status'] == "delete") {
            $editor_sts = "place-deleted";
          }
        } else {
          $editor_sts = "too-many-places";
        }
      } else {
        $editor_sts = "not-matching-data";
      }
    }
  } else {
    if ($bnft_edit_cottage == "none") {
      $editor_sts = "unavailable";
    } else if ($bnft_edit_cottage == "ban") {
      $editor_sts = "feature_ban";
    } else {
      $editor_sts = "feature_error";
    }
  }
  $subtitle = $wrd_editor;
?>
<!DOCTYPE html>
<html lang="<?php echo $wrd_htmlLang; ?>">
  <head>
    <meta charset="UTF-8">
    <meta name="description" content="<?php echo $wrd_metaDescriptionEditor; ?>">
    <link rel="stylesheet" type="text/css" href="css/editor.css">
    <link rel="stylesheet" type="text/css" href="css/header.css">
    <link rel="stylesheet" type="text/css" href="css/images.css">
    <link rel="stylesheet" type="text/css" href="css/nav.css">
    <link rel="stylesheet" type="text/css" href="css/content/content.css">
    <link rel="stylesheet" type="text/css" href="css/content/basics.css">
    <link rel="stylesheet" type="text/css" href="css/content/details.css">
    <link rel="stylesheet" type="text/css" href="css/content/price.css">
    <link rel="stylesheet" type="text/css" href="css/content/videos.css">
    <link rel="stylesheet" type="text/css" href="css/content/map.css">
    <link rel="stylesheet" type="text/css" href="css/content/conditions.css">
    <link rel="stylesheet" type="text/css" href="css/content/calendar-sync.css">
    <link rel="stylesheet" type="text/css" href="css/content/calendar.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="stylesheet" type="text/css" href="../uni/code/css/permission-needed-modal.css">
    <script src="js/editor.js" async></script>
    <script src="js/header.js" async></script>
    <script src="js/nav.js" async></script>
    <script src="js/map.js" async></script>
    <script src="js/videos.js" async></script>
    <script src="js/conditions.js" async></script>
    <script src="js/calendar.js" async></script>
    <script src="js/images.js" async></script>
    <script src="js/details.js" async></script>
    <script src="js/calendar-sync.js" async></script>
    <script src="../uni/code/js/add-currency.js" async></script>
    <script src="../uni/code/js/permission-needed-modal.js" async></script>
    <?php
      include "../uni/code/php-frontend/calendar-head.php";
      include "../uni/code/php-frontend/head.php";
    ?>
    <title><?php echo $wrd_editor." - ".preg_replace("/<br\W*?\/>/", "", $plcName)." - ".$title; ?></title>
  </head>
  <body onload="
    <?php echo $onload; ?>
    mapManager('editor', <?php echo $plcLat; ?>, <?php echo $plcLng; ?>);
    loadConditionOfStayData('editor', '<?php echo $plcId; ?>');
    calendarRender('editor-content-calendar-blck', '<?php echo $plcId; ?>', 'host-view');
    permissionNeededDictionary(
      '<?php echo $wrd_thisReservationMustBeDeletedDueToNewModifications; ?>',
      '<?php echo $wrd_becauseReservationsOverlapAndContainTheSameInformationTheyWillBeCombinedIntoOne; ?>',
      '<?php echo $wrd_theDatesOfThisReservationWillBePostponedDueToNewModifications; ?>',
      '<?php echo $wrd_newModificationsWillSplitThisReservation; ?>',
      '<?php echo $wrd_inOrderForNewAdjustmentsToBeMadeThisBookingMustBeRejected; ?>',
      '<?php echo $wrd_name; ?>',
      '<?php echo $wrd_email; ?>',
      '<?php echo $wrd_phoneNumber; ?>',
      '<?php echo $wrd_cleaning; ?>',
      '<?php echo $wrd_maintenance; ?>',
      '<?php echo $wrd_reconstruction; ?>',
      '<?php echo $wrd_other; ?>'
    );
    editoCalendarManagerDictionary(
      '<?php echo $wrd_updateChanges; ?>',
      '<?php echo $wrd_cancelBooking; ?>',
      '<?php echo $wrd_name; ?>',
      '<?php echo $wrd_email; ?>',
      '<?php echo $wrd_phoneNumber; ?>',
      '<?php echo $wrd_guestNum; ?>',
      '<?php echo $wrd_from; ?>',
      '<?php echo $wrd_to; ?>',
      '<?php echo $wrd_theWholeDay; ?>',
      '<?php echo $wrd_theDepositIsPaid; ?>',
      '<?php echo $wrd_theFullAmountIsPaid; ?>',
      '<?php echo $wrd_theReservationWasMadeLessThan48HoursBeforeItsStartTheGuestHasBeenInformedToReportByPhone; ?>',
      '<?php echo $wrd_notes; ?>',
      '<?php echo $wrd_type; ?>',
      '<?php echo $wrd_hour; ?>',
      '<?php echo $wrd_hours1; ?>',
      '<?php echo $wrd_hours2; ?>',
      '<?php echo $wrd_thereAre1; ?>',
      '<?php echo $wrd_thereAre2; ?>',
      '<?php echo $wrd_untilThisReservationIsAutomaticallyCanceled; ?>',
      '<?php echo $wrd_thisReservationWillBeAutomaticallyCanceledAtAnyTimeIfThisHasNotAlreadyBeenDoneToPreventThisYouMustCheckTheBoxForTheDepositPaidOrTheFullAmount; ?>',
      '<?php echo $wrd_ifTheGuestDoesNotPayTheDepositOrTheFullAmountWithin48HoursOfMakingTheReservationItWillBeAutomaticallyCanceled; ?>',
      '<?php echo $wrd_totalPrice; ?>',
      '<?php echo $wrd_cleaning; ?>',
      '<?php echo $wrd_maintenance; ?>',
      '<?php echo $wrd_reconstruction; ?>',
      '<?php echo $wrd_other; ?>',
      '<?php echo $wrd_category; ?>',
      '<?php echo $wrd_cancelTechnicalShutdown; ?>',
      '<?php echo $wrd_reject; ?>',
      '<?php echo $wrd_confirm; ?>',
      '<?php echo $wrd_bookingOffer; ?>',
      '<?php echo $wrd_byConfirmingTheBookingYouWillBeProvidedWithAllInformationAboutThisBookingSuchAsTheNameAndContactDetailsOfTheGuestAtTheSameTimeTheGuestWillReceivePaymentInstructionsRejectionWillCancelTheBooking; ?>',
      '<?php echo $wrd_requestsForChangesInTheBooking; ?>',
      '<?php echo $wrd_fee; ?>',
      '<?php echo $wrd_thereAreNoBookingsForThisDate; ?>'
    );
  ">
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
      if ($editor_sts == "good") {
    ?>
      <div id="editor-size">
        <div id="editor-layout">
          <?php
            include "php-frontend/header.php";
            include "php-frontend/images.php";
            include "php-frontend/nav.php";
            include "php-frontend/content/content.php";
            include "php-frontend/footer.php";
          ?>
        </div>
      </div>
    <?php
        include "php-frontend/modals.php";
        include "../uni/code/php-frontend/permission-needed-modal.php";
      } else {
    ?>
      <div class="page-error">
        <p class="page-error-p">
          <?php
            if ($editor_sts == "unset") {
              echo $wrd_wrongUrl;
            } else if ($editor_sts == "not-exist") {
              echo $wrd_cottageWithIdNotExist;
            } else if ($editor_sts == "not-matching-data") {
              echo $wrd_idExistButDetailsNot;
            } else if ($editor_sts == "too-many-places") {
              echo $wrd_multiPlaceToOneID;
            } else if ($editor_sts == "place-deleted") {
              echo $wrd_placeDeleted;
            } else if ($editor_sts == "unavailable") {
              echo $wrd_benefitNotAllowed;
            } else if ($editor_sts == "foreign") {
              echo $wrd_editCottagesYouUpload;
            } else if ($editor_sts == "feature_ban") {
              echo $wrd_youHaveBeenBannedThisFeatureIfAnErrorHasOccurredOrWantYouToKnowMoreContactUs;
            } else if ($editor_sts == "feature_error") {
              echo $wrd_weWereUnableToDetermineWithCertaintyWhetherOrNotYouHaveThisFeatureEnabledThereforePleaseContactUsOrFillInAndSendTheApplicationAgainWeApologizeForTheInconvenience;
            }
          ?>
        </p>
      </div>
    <?php
      }
    ?>
  </body>
</html>
