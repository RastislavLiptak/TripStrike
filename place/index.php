<?php
  include "../uni/code/php-head.php";
  include "../uni/code/php-backend/add-currency.php";
  include "../uni/code/php-backend/rating-summary.php";
  include "../uni/code/php-backend/number-form.php";
  include "../uni/code/php-backend/convert-link-in-string.php";
  include "php-backend/place-traffic-log.php";
  $idSts = "unset";
  if (isset($_GET['id'])) {
    $plcId = $_GET['id'];
    $idSts = "good";
  }
  if ($idSts == "good") {
    $sqlBeId = $link->query("SELECT beid FROM idlist WHERE id='$plcId'");
    if ($sqlBeId->num_rows > 0) {
      $plcBeId = $sqlBeId->fetch_assoc()['beid'];
      $sqlLasId = $link->query("SELECT id FROM idlist WHERE beid='$plcBeId' ORDER BY fullDate DESC LIMIT 1");
      $plcLastId = $sqlLasId->fetch_assoc()['id'];
      if ($plcId != $plcLastId) {
        header("Location: ../place/?id=".$plcLastId);
      }
    } else {
      $idSts = "not-exist";
    }
  }
  $plcLat = 0.000000000000000;
  $plcLng = 0.000000000000000;
  $plcType = "cottage";
  $plcName = $wrd_unknown;
  $plcDesc = "";
  $plcPriceWork = 0;
  $plcPriceWeek = 0;
  $plcCurrency = "eur";
  $plcPriceMode = "";
  if ($idSts == "good") {
    $sqlPlace = $link->query("SELECT * FROM places WHERE beid='$plcBeId'");
    if ($sqlPlace->num_rows > 0) {
      if ($sqlPlace->num_rows == 1) {
        $plc = $sqlPlace->fetch_assoc();
        if ($plc['status'] == "active") {
          $plcType = $plc["type"];
          $plcName = nl2br($plc['name']);
          $plcGuests = intval($plc["guestNum"]);
          $plcBedroom = intval($plc["bedNum"]);
          $plcBathroom = intval($plc["bathNum"]);
          $plcDistanceFromTheWater = intval($plc["distanceFromTheWater"]);
          $plcDesc = $plc['description'];
          $plcPriceMode = $plc["priceMode"];
          $plcCurrency = $plc["currency"];
          if ($plc["weekDayPrice"] == $plc["workDayPrice"]) {
            $plcPriceWork = $plc["workDayPrice"];
            $plcPriceWeek = $plc["workDayPrice"];
          } else {
            $plcPriceWork = $plc["workDayPrice"];
            $plcPriceWeek = $plc["weekDayPrice"];
          }
          $plcLat = $plc['lat'];
          $plcLng = $plc['lng'];
          $plcOperation = $plc['operation'];
          $plcOperationFrom = $plc['operationFrom'];
          $plcOperationTo = $plc['operationTo'];
          $plcAccBeId = $plc['usrbeid'];
          if ($plcAccBeId == $usrBeId) {
            $myPlc = true;
          } else {
            $myPlc = false;
          }
          $plcAccId = getAccountData($plcAccBeId, "id");
          $plcAccFirstname = getAccountData($plcAccBeId, "firstname");
          $plcAccProfImg = getAccountData($plcAccBeId, "small-profile-image");
          $plcAccContactPhone = getAccountData($plcAccBeId, "contact-phone-num");
          ratingSummary($plcAccBeId);
          ratingSummary($plcBeId);
          ratingCriticSummary($plcBeId);
          $sqlSummRating = $link->query("SELECT percentage FROM ratingsummary WHERE beid='$plcBeId'");
          if ($sqlSummRating->num_rows > 0) {
            $plcAccRating = str_replace('.',',',round($sqlSummRating->fetch_assoc()["percentage"] * 5 / 100, 2));
          } else {
            $plcAccRating =  "none";
          }
          $plcSectData = [];
          $sqlPlcCondBeId = $link->query("SELECT beid FROM placeconditionskey WHERE plcbeid='$plcBeId' LIMIT 1");
          if ($sqlPlcCondBeId->num_rows > 0) {
            $plcCondBeId = $sqlPlcCondBeId->fetch_assoc()['beid'];
            $sqlPlcCondTxt = $link->query("SELECT txt FROM conditionsofstayofthehost WHERE beid='$plcCondBeId' LIMIT 1");
            if ($sqlPlcCondTxt->num_rows > 0) {
              $plcCondTxt = nl2br(str_replace("&apos;", "'", $sqlPlcCondTxt->fetch_assoc()['txt']));
            } else {
              $plcCondTxt = $wrd_automaticProcessingOfHostConditionsFailed." (conditions-txt-not-found)";
            }
          } else {
            $plcCondTxt = $wrd_automaticProcessingOfHostConditionsFailed." (no-conditions-found)";
          }
        } else if ($plc['status'] == "delete") {
          $idSts = "place-deleted";
        }
      } else {
        $idSts = "too-many-places";
      }
    } else {
      $idSts = "not-matching-data";
    }
  }
  if ($idSts == "good") {
    if ($plcOperationFrom == 1) {
      $operationFromMonth = $wrd_january;
    } else if ($plcOperationFrom == 2) {
      $operationFromMonth = $wrd_february;
    } else if ($plcOperationFrom == 3) {
      $operationFromMonth = $wrd_march;
    } else if ($plcOperationFrom == 4) {
      $operationFromMonth = $wrd_april;
    } else if ($plcOperationFrom == 5) {
      $operationFromMonth = $wrd_may;
    } else if ($plcOperationFrom == 6) {
      $operationFromMonth = $wrd_june;
    } else if ($plcOperationFrom == 7) {
      $operationFromMonth = $wrd_july;
    } else if ($plcOperationFrom == 8) {
      $operationFromMonth = $wrd_august;
    } else if ($plcOperationFrom == 9) {
      $operationFromMonth = $wrd_september;
    } else if ($plcOperationFrom == 10) {
      $operationFromMonth = $wrd_october;
    } else if ($plcOperationFrom == 11) {
      $operationFromMonth = $wrd_november;
    } else if ($plcOperationFrom == 12) {
      $operationFromMonth = $wrd_december;
    }
    if ($plcOperationTo == 1) {
      $operationToMonth = $wrd_january;
    } else if ($plcOperationTo == 2) {
      $operationToMonth = $wrd_february;
    } else if ($plcOperationTo == 3) {
      $operationToMonth = $wrd_march;
    } else if ($plcOperationTo == 4) {
      $operationToMonth = $wrd_april;
    } else if ($plcOperationTo == 5) {
      $operationToMonth = $wrd_may;
    } else if ($plcOperationTo == 6) {
      $operationToMonth = $wrd_june;
    } else if ($plcOperationTo == 7) {
      $operationToMonth = $wrd_july;
    } else if ($plcOperationTo == 8) {
      $operationToMonth = $wrd_august;
    } else if ($plcOperationTo == 9) {
      $operationToMonth = $wrd_september;
    } else if ($plcOperationTo == 10) {
      $operationToMonth = $wrd_october;
    } else if ($plcOperationTo == 11) {
      $operationToMonth = $wrd_november;
    } else if ($plcOperationTo == 12) {
      $operationToMonth = $wrd_december;
    }
  }
  $subtitle = preg_replace("/<br\W*?\/>/", "", $plcName);
?>
<!DOCTYPE html>
<html lang="<?php echo $wrd_htmlLang; ?>">
  <head>
    <meta charset="UTF-8">
    <?php
      include "../uni/code/php-frontend/head.php";
    ?>
    <link rel="stylesheet" type="text/css" href="css/place.css">
    <link rel="stylesheet" type="text/css" href="css/book-modal.css">
    <link rel="stylesheet" type="text/css" href="css/book-calendar.css">
    <link rel="stylesheet" type="text/css" href="css/place-calendar.css">
    <link rel="stylesheet" type="text/css" href="../uni/code/css/uni-details-block.css">
    <link rel="stylesheet" type="text/css" href="../uni/code/css/share.css">
    <script src="../uni/code/js/uni-details-block.js"></script>
    <script src="../uni/code/js/share.js"></script>
    <script src="../uni/code/js/add-currency.js"></script>
    <script src="js/place.js" async></script>
    <script src="js/book-modal.js" async></script>
    <script src="js/book-calendar.js" async></script>
    <?php
      include "../uni/code/php-frontend/calendar-head.php";
    ?>
    <meta name="description" content="<?php echo strip_tags($plcDesc); ?>">
    <title><?php echo preg_replace("/<br\W*?\/>/", "", $plcName)." - ".$title; ?></title>
  </head>
  <body onload="
    <?php echo $onload; ?>
    mapManager('place', <?php echo $plcLat; ?>, <?php echo $plcLng; ?>);
    plcDictionary('<?php echo $wrd_showAll; ?>');
    plcCommentLoader();
    bookData(
      '<?php echo $plcPriceMode; ?>',
      '<?php echo $plcPriceWork; ?>',
      '<?php echo $plcPriceWeek; ?>',
      '<?php echo $plcCurrency; ?>'
    );
    plcBookData(
      '<?php echo $plcPriceWork; ?>',
      '<?php echo $plcPriceWeek; ?>',
      '<?php echo $plcCurrency; ?>'
    );
    plcDetailsCalendar('<?php echo $plcId; ?>');
    <?php
      if (isset($_GET['instantBooking'])) {
    ?>
      instantBookingCalendarHandler();
    <?php
      }
    ?>
  ">
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
      if ($idSts == "good") {
    ?>
      <div id="plc-imgs-blck-size">
        <div id="plc-imgs-wrp">
          <div id="plc-imgs-grid">
            <?php
              $plcImgBeIdArr = [];
              $sqlMainImgBeId = $link->query("SELECT imgbeid FROM placeimages WHERE cottbeid='$plcBeId' and type='big' and sts='main' ORDER BY numid DESC LIMIT 1");
              if ($sqlMainImgBeId->num_rows > 0) {
                array_push($plcImgBeIdArr, $sqlMainImgBeId->fetch_assoc()["imgbeid"]);
              }
              $sqlCommonImgBeId = $link->query("SELECT imgbeid FROM placeimages WHERE cottbeid='$plcBeId' and type='mid' and sts='common' ORDER BY numid DESC LIMIT 4");
              if ($sqlCommonImgBeId->num_rows > 0) {
                while($rowCommonImg = $sqlCommonImgBeId->fetch_assoc()) {
                  array_push($plcImgBeIdArr, $rowCommonImg["imgbeid"]);
                }
              }
              $plcImgNum = 0;
              foreach ($plcImgBeIdArr as $img) {
                $sqlPlcImgBeId = $link->query("SELECT imgbeid, sts FROM placeimages WHERE imgbeid='$img' LIMIT 1");
                if ($sqlPlcImgBeId->num_rows > 0) {
                  $rowPlcImgBeId = $sqlPlcImgBeId->fetch_assoc();
                  $plcImgBeId = $rowPlcImgBeId["imgbeid"];
                  $plcImgSts = $rowPlcImgBeId["sts"];
                  $sqlPlcImgSrc = $link->query("SELECT src FROM images WHERE name='$plcImgBeId'");
                  if ($sqlPlcImgSrc ->num_rows > 0) {
                    $plcImgSrc = $sqlPlcImgSrc->fetch_assoc()['src'];
                    if ($plcImgSts == "main") {
                      $plcImgClass = "plc-img-main-wrp";
                      $plcImgBtn = "plc-img-main-btn";
                    } else {
                      $plcImgClass = "plc-img-secondary-wrp";
                      $plcImgBtn = "plc-img-secondary-btn";
                    }
            ?>
                <div class="plc-img-wrp plc-img-rezize-wrp <?php echo $plcImgClass; ?>">
                  <button class="plc-img-btn <?php echo $plcImgBtn; ?>" aria-label="Image of the place" onclick="placeImgFullScreen('show', <?php echo $plcImgNum; ?>)">
                    <div class="plc-img" style="background-image: url('../<?php echo $plcImgSrc; ?>')"></div>
                    <div class="plc-img-fullsize-icon"></div>
                  </button>
                </div>
            <?php
                    ++$plcImgNum;
                  }
                }
              }
              $numOfFakeImgs = 5 - count($plcImgBeIdArr);
              for ($i = 0; $i < $numOfFakeImgs; $i++) {
                if ($numOfFakeImgs == 5 && $i == 0) {
                  $plcImgClass = "plc-img-main-wrp";
                  $plcImgBtn = "plc-img-main-btn";
                } else {
                  $plcImgClass = "plc-img-secondary-wrp";
                  $plcImgBtn = "plc-img-secondary-btn";
                }
            ?>
            <div class="plc-img-wrp <?php echo $plcImgClass; ?>">
              <div class="plc-img-btn <?php echo $plcImgBtn; ?>">
                <div class="fake-plc-img"></div>
              </div>
            </div>
            <?php
              }
            ?>
          </div>
        </div>
      </div>
      <div id="plc-full-screen-wrp">
        <button type="button" id="plc-full-screen-close" onclick="placeImgFullScreen('hide', '')"></button>
        <div id="plc-full-screen-layout">
          <div class="plc-full-screen-arrow-wrp" id="plc-full-screen-arrow-wrp-left">
            <button type="button" class="plc-full-screen-arrow-btn" id="plc-full-screen-arrow-btn-left" value="" onclick="selectFullScreen(this.value)"></button>
          </div>
          <div id="plc-full-screen-content-wrp">
            <div id="plc-full-screen-img-list">
              <?php
                $plcFullImgBeIdArr = [];
                $sqlBigMainImgBeId = $link->query("SELECT imgbeid FROM placeimages WHERE cottbeid='$plcBeId' and type='huge' and sts='main' ORDER BY numid DESC LIMIT 1");
                if ($sqlBigMainImgBeId->num_rows > 0) {
                  array_push($plcFullImgBeIdArr, $sqlBigMainImgBeId->fetch_assoc()["imgbeid"]);
                }
                $sqlBigCommonImgBeId = $link->query("SELECT imgbeid FROM placeimages WHERE cottbeid='$plcBeId' and type='huge' and sts='common' ORDER BY numid DESC");
                if ($sqlBigCommonImgBeId->num_rows > 0) {
                  while($rowBigCommonImg = $sqlBigCommonImgBeId->fetch_assoc()) {
                    array_push($plcFullImgBeIdArr, $rowBigCommonImg["imgbeid"]);
                  }
                }
                $plcFullScImgNum = 0;
                foreach ($plcFullImgBeIdArr as $fullImg) {
                  $sqlPlcFullScImgBeId = $link->query("SELECT imgbeid, sts FROM placeimages WHERE imgbeid='$fullImg' LIMIT 1");
                  if ($sqlPlcFullScImgBeId->num_rows > 0) {
                    $rowPlcFullScImgBeId = $sqlPlcFullScImgBeId->fetch_assoc();
                    $plcFullScImgBeId = $rowPlcFullScImgBeId["imgbeid"];
                    $sqlPlcFullScImgSrc = $link->query("SELECT src FROM images WHERE name='$plcFullScImgBeId'");
                    if ($sqlPlcFullScImgSrc ->num_rows > 0) {
                      $plcFullScImgSrc = $sqlPlcFullScImgSrc->fetch_assoc()['src'];
                ?>
                  <div class="plc-full-screen-img-wrp" id="plc-full-screen-img-wrp-<?php echo $plcFullScImgNum; ?>">
                    <p class="plc-full-screen-img-src" id="plc-full-screen-img-src-<?php echo $plcFullScImgNum; ?>"><?php echo "../".$plcFullScImgSrc; ?></p>
                    <img alt="" src="../uni/gifs/loader-tail.svg" class="plc-full-screen-img-loader" id="plc-full-screen-img-loader-<?php echo $plcFullScImgNum; ?>">
                    <img alt="" src="" class="plc-full-screen-img" id="plc-full-screen-img-<?php echo $plcFullScImgNum; ?>">
                  </div>
                <?php
                      ++$plcFullScImgNum;
                    }
                  }
                }
              ?>
            </div>
            <div id="plc-full-screen-txt-wrp">
              <p id="plc-full-screen-img-counter"></p>
            </div>
            <div id="plc-full-screen-mobile-arrows-wrp">
              <button type="button" class="plc-full-screen-mobile-arrows-btn" id="plc-full-screen-mobile-arrows-btn-left" value="" onclick="selectFullScreen(this.value)"></button>
              <button type="button" class="plc-full-screen-mobile-arrows-btn" id="plc-full-screen-mobile-arrows-btn-right" value="" onclick="selectFullScreen(this.value)"></button>
            </div>
          </div>
          <div class="plc-full-screen-arrow-wrp" id="plc-full-screen-arrow-wrp-right">
            <button type="button" class="plc-full-screen-arrow-btn" id="plc-full-screen-arrow-btn-right" value="" onclick="selectFullScreen(this.value)"></button>
          </div>
        </div>
      </div>
      <div id="plc-details-wrp">
        <div id="plc-details-about-wrp">
          <div id="plc-details-about-grid">
            <div id="plc-details-top-grid">
              <div class="p-d-a-block">
                <?php
                  if ($plcType == "cottage") {
                ?>
                  <div id="plc-top-booking-info-wrp">
                    <div id="plc-top-booking-nearest-wrp">
                      <span class="plc-top-booking-txt" id="plc-top-booking-nearest-available-txt"><?php echo $wrd_nearestAvailableDateIs; ?></span>
                      <span class="plc-top-booking-txt" id="plc-top-booking-multiple-nearest-available-txt"><?php echo $wrd_nearestAvailableDatesAre; ?></span>
                      <span class="plc-top-booking-txt" id="plc-top-booking-nearest-available-date"></span>
                      <span class="plc-top-booking-txt" id="plc-top-booking-nearest-all-available-txt"><?php echo $wrd_theFollowingTwoMonthsAreFreeForAllDates; ?></span>
                      <span class="plc-top-booking-txt" id="plc-top-booking-nearest-all-available-since-txt"><?php echo $wrd_theFollowingTwoMonthsAreFreeForAllDatesSince; ?></span>
                      <span class="plc-top-booking-txt" id="plc-top-booking-nearest-all-available-since-date"></span>
                      <span class="plc-top-booking-txt" id="plc-top-booking-nearest-unavailable-txt"><?php echo $wrd_thereAreNoAvailableDates; ?></span>
                      <button type="button" id="plc-top-booking-book-btn" onclick="bookModal('show')">
                        <p class="plc-top-booking-txt" id="plc-top-booking-book-btn-txt"><?php echo $wrd_chooseDate; ?></p>
                        <div id="plc-top-booking-book-btn-icn"></div>
                      </button>
                    </div>
                  </div>
                <?php
                  }
                ?>
                <div class="p-d-a-txt-blck">
                  <h1 id="name"><?php echo $plcName; ?></h1>
                </div>
                <div id="plc-content-mobile-wrp">
                  <div id="plc-payment-mobile-blck">
                    <div id="plc-payment-mobile-txt-wrp">
                      <?php
                        if ($plcPriceMode == "guests") {
                          $perWhat = $wrd_perPersonPerNight;
                        } else {
                          $perWhat = $wrd_perNight;
                        }
                        if ($plcPriceWork == $plcPriceWeek) {
                      ?>
                      <p id="plc-payment-mobile-txt-price"><?php echo addCurrency($plcCurrency, $plcPriceWork)." ".$perWhat; ?></p>
                      <p id="plc-payment-mobile-txt-desc"><?php echo $wrd_singlePrice; ?></p>
                      <?php
                        } else {
                      ?>
                      <p id="plc-payment-mobile-txt-price"><?php echo addCurrency($plcCurrency, $plcPriceWork)." / ".addCurrency($plcCurrency, $plcPriceWeek)." ".$perWhat; ?></p>
                      <p id="plc-payment-mobile-txt-desc"><?php echo $wrd_workweek." / ".$wrd_weekend; ?></p>
                      <?php
                        }
                      ?>
                    </div>
                    <button class="btn btn-big btn-prim" id="plc-payment-mobile-btn" onclick="bookModal('show')"></button>
                  </div>
                </div>
                <div id="place-space">
                  <?php
                    if ($plcGuests > 4) {
                      $wrdGuest = $wrd_guests2;
                    } else if ($plcGuests > 1) {
                      $wrdGuest = $wrd_guests;
                    } else {
                      $wrdGuest = $wrd_guest;
                    }
                    if ($plcBedroom > 4) {
                      $wrdBedroom = $wrd_bedrooms2;
                    } else if ($plcBedroom > 1) {
                      $wrdBedroom = $wrd_bedrooms;
                    } else {
                      $wrdBedroom = $wrd_bedroom;
                    }
                    if ($plcBathroom > 4) {
                      $wrdBathroom = $wrd_bathrooms2;
                    } else if ($plcBedroom > 1) {
                      $wrdBathroom = $wrd_bathrooms;
                    } else {
                      $wrdBathroom = $wrd_bathroom;
                    }
                  ?>
                  <div class="place-space-blck">
                    <div class="place-space-icn-wrp">
                      <div class="place-space-icn place-space-icn-guests"></div>
                    </div>
                    <p class="place-space-txt"><?php echo $plcGuests." ".$wrdGuest; ?></p>
                  </div>
                  <div class="place-space-blck">
                    <div class="place-space-icn-wrp">
                      <div class="place-space-icn place-space-icn-bedrooms"></div>
                    </div>
                    <p class="place-space-txt"><?php echo $plcBedroom." ".$wrdBedroom; ?></p>
                  </div>
                  <div class="place-space-blck">
                    <div class="place-space-icn-wrp">
                      <div class="place-space-icn place-space-icn-bathrooms"></div>
                    </div>
                    <p class="place-space-txt"><?php echo $plcBathroom." ".$wrdBathroom; ?></p>
                  </div>
                  <?php
                    if ($plcDistanceFromTheWater > 0) {
                      if ($plcDistanceFromTheWater < 26) {
                  ?>
                      <div class="place-space-blck">
                        <div class="place-space-icn-wrp">
                          <div class="place-space-icn place-space-icn-wave"></div>
                        </div>
                        <p class="place-space-txt"><?php echo $wrd_closeToTheWater; ?></p>
                      </div>
                  <?php
                      } else {
                  ?>
                      <div class="place-space-blck">
                        <div class="place-space-icn-wrp">
                          <div class="place-space-icn place-space-icn-wave"></div>
                        </div>
                        <p class="place-space-txt"><?php echo $plcDistanceFromTheWater." ".$wrd_metersToTheWater; ?></p>
                      </div>
                  <?php
                      }
                    }
                  ?>
                  <button type="button" class="place-space-blck place-space-btn" id="place-space-btn-mobile-user" onclick="location.href = '../user/?id=<?php echo $plcAccId; ?>&section=about'">
                    <div class="place-space-icn-wrp">
                      <div class="place-space-icn">
                        <img src="../<?php echo $plcAccProfImg; ?>" alt="" id="place-space-icn-prof-img">
                      </div>
                    </div>
                    <p class="place-space-txt place-space-txt-opacity" id="place-space-txt-name"><?php echo $plcAccFirstname; ?></p>
                  </button>
                </div>
                <?php
                  $maxDescChars = 1000;
                  $plcDescShrt = mb_substr($plcDesc, 0, $maxDescChars, "utf-8");
                  $maxDescChars = $maxDescChars - (substr_count($plcDescShrt, "\n") * 25);
                  $plcDescShrt = mb_substr($plcDesc, 0, $maxDescChars, "utf-8");
                  if (trim(preg_replace('/\s\s+/', ' ', $plcDescShrt)) != trim(preg_replace('/\s\s+/', ' ', $plcDesc))) {
                    $descLess = "description-show";
                    $descMore = "description-hide";
                  } else {
                    $descLess = "description-hide";
                    $descMore = "description-show";
                  }
                  if (preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "", $plcDesc)) != "") {
                ?>
                  <p class="description" id="description-title"><?php echo $wrd_cottageDescription; ?></p>
                <?php
                  }
                ?>
                <div id="description-less" class="<?php echo $descLess; ?>">
                  <div class="p-d-a-txt-blck">
                    <p class="description"><?php echo nl2br(convertLinkInString($plcDescShrt))."..."; ?></p>
                  </div>
                  <button type="button" id="description-btn-more" onclick="descriptionShow()"><?php echo $wrd_showMore; ?></button>
                </div>
                <div id="description-more" class="<?php echo $descMore; ?>">
                  <div class="p-d-a-txt-blck">
                    <p class="description"><?php echo nl2br(convertLinkInString($plcDesc)); ?></p>
                  </div>
                </div>
              </div>
              <div id="p-d-a-border-wrp">
                <?php
                  if ($plcType == "cottage") {
                ?>
                  <button class="btn btn-big btn-prim" onclick="bookModal('show')"><?php echo $wrd_book; ?></button>
                  <div id="p-d-a-border"></div>
                <?php
                  } else {
                ?>
                  <div id="p-d-a-border" style="margin-left: 0px;"></div>
                <?php
                  }
                ?>
              </div>
            </div>
            <?php
              if ($plcType == "cottage") {
            ?>
              <div class="p-d-a-block" id="p-d-a-block-calendar">
                <div id="plc-calendar-size">
                  <div id="plc-calendar-wrp"></div>
                  <div id="plc-calendar-footer">
                    <div id="plc-calendar-footer-layout">
                      <?php
                        if ($plcPriceMode == "guests") {
                          $perWhatShrt = $wrd_personPerNight;
                        } else {
                          $perWhatShrt = $wrd_night;
                        }
                        if ($plcPriceWork == $plcPriceWeek) {
                      ?>
                        <div class="plc-calendar-footer-calc-section">
                          <span class="plc-calendar-footer-calc-section-txt plc-calendar-footer-calc-section-txt-times" id="plc-calendar-footer-calc-all-days-times"></span>
                          <span class="plc-calendar-footer-calc-section-txt"><?php echo addCurrency($plcCurrency, $plcPriceWork)." /".$perWhatShrt; ?></span>
                        </div>
                      <?php
                        } else {
                      ?>
                        <div class="plc-calendar-footer-calc-section" id="plc-calendar-footer-calc-section-work">
                          <span class="plc-calendar-footer-calc-section-txt plc-calendar-footer-calc-section-txt-times" id="plc-calendar-footer-calc-work-times"></span>
                          <span class="plc-calendar-footer-calc-section-txt"><?php echo addCurrency($plcCurrency, $plcPriceWork)." /".$perWhatShrt; ?></span>
                        </div>
                        <span class="plc-calendar-footer-calc-section-txt plc-calendar-footer-calc-marks" id="plc-calendar-footer-calc-marks-plus">+</span>
                        <div class="plc-calendar-footer-calc-section" id="plc-calendar-footer-calc-section-week">
                          <span class="plc-calendar-footer-calc-section-txt plc-calendar-footer-calc-section-txt-times" id="plc-calendar-footer-calc-week-times"></span>
                          <span class="plc-calendar-footer-calc-section-txt"><?php echo addCurrency($plcCurrency, $plcPriceWeek)." /".$perWhatShrt; ?></span>
                        </div>
                      <?php
                        }
                      ?>
                      <span class="plc-calendar-footer-calc-section-txt plc-calendar-footer-calc-marks">=</span>
                      <span class="plc-calendar-footer-calc-section-txt" id="plc-calendar-footer-calc-total"></span>
                      <button type="button" id="plc-calendar-footer-btn" onclick="plcBookSummaryModal('show')">
                        <p class="plc-calendar-footer-calc-section-txt" id="plc-calendar-footer-btn-txt"><?php echo $wrd_continue; ?></p>
                        <div id="plc-calendar-footer-btn-icn"></div>
                      </button>
                    </div>
                  </div>
                  <?php
                    if ($plcOperation == "summer" || $plcOperation == "winter") {
                  ?>
                      <div id="plc-calendar-operation">
                        <p id="plc-calendar-operation-txt">
                          <?php
                            if ($plcOperation == "summer") {
                              echo $wrd_theOperationOfTheCottageIsLimitedToTheSummerSeason." (".$operationFromMonth." - ".$operationToMonth.")";
                            } else if ($plcOperation == "winter") {
                              echo $wrd_theOperationOfTheCottageIsLimitedToTheWinterSeason." (".$operationFromMonth." - ".$operationToMonth.")";
                            }
                          ?>
                        </p>
                      </div>
                  <?php
                    }
                  ?>
                </div>
              </div>
            <?php
              }
              $sqlPlcEqui = $link->query("SELECT name, src FROM placeequipment WHERE beid='$plcBeId'");
              if ($sqlPlcEqui->num_rows > 0) {
            ?>
              <div class="p-d-a-block">
                <div class="p-d-a-title-wrp">
                  <h2 class="p-d-a-block-title"><?php echo $wrd_equipment; ?></h2>
                </div>
                <div id="plc-details-equi-list">
                  <?php
                    while($rowPlcEqui = $sqlPlcEqui->fetch_assoc()) {
                  ?>
                    <div class="plc-d-e-wrp">
                      <div class="plc-d-e-icn" style="background-image: url('../uni/icons/<?php echo $rowPlcEqui['src']; ?>')"></div>
                      <p class="plc-d-e-txt"><?php echo $rowPlcEqui['name']; ?></p>
                    </div>
                  <?php
                    }
                  ?>
                </div>
              </div>
            <?php
              }
              $sqlPlcVids = $link->query("SELECT videoid FROM placevideos WHERE beid='$plcBeId'");
              if ($sqlPlcVids->num_rows > 0) {
            ?>
              <div class="p-d-a-block">
                <div class="p-d-a-title-wrp">
                  <h2 class="p-d-a-block-title"><?php echo $wrd_videos; ?></h2>
                </div>
                <div id="plc-details-videos-grid">
                  <?php
                    while($rowPlcVids = $sqlPlcVids->fetch_assoc()) {
                  ?>
                    <div class="plc-d-vid-wrp">
                      <iframe class="plc-d-vid-player" src="https://www.youtube.com/embed/<?php echo $rowPlcVids['videoid']; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                  <?php
                    }
                  ?>
                </div>
              </div>
            <?php
              }
              if ($plcLat != 0.000000000000000 && $plcLng != 0.000000000000000) {
            ?>
              <div class="p-d-a-block">
                <div class="p-d-a-title-wrp">
                  <h2 class="p-d-a-block-title" id="p-d-a-block-title-map"><?php echo $wrd_map; ?></h2>
                  <a class="p-d-a-block-detail" id="plc-map-address" target="_blank"></a>
                  <p class="p-d-a-block-error" id="plc-map-error"></p>
                </div>
                <div id="plc-map"></div>
                <?php
                  if ($plcType == "cottage") {
                ?>
                    <div id="plc-map-book-btn">
                      <button class="btn btn-mid btn-prim" onclick="bookModal('show')"><?php echo $wrd_book; ?></button>
                    </div>
                <?php
                  }
                ?>
              </div>
            <?php
              }
            ?>
            <div id="p-d-a-rating-blck">
              <div class="p-d-a-block" id="p-d-a-block-rating">
                <div class="p-d-a-title-wrp">
                  <h2 class="p-d-a-block-title"><?php echo $wrd_ratingCapital; ?></h2>
                </div>
                <?php
                  if ($plcAccRating != "none") {
                ?>
                  <div id="plc-rating-sumary-blck">
                    <div id="plc-rating-main-blck">
                      <div id="plc-rating-stars-wrp">
                        <img alt="" src="../uni/icons/star.svg" id="plc-rating-stars-img">
                        <p id="plc-rating-stars-num"><?php echo $plcAccRating; ?></p>
                      </div>
                      <div id="plc-rating-critic-num-wrp">
                        <p id="plc-rating-critic-num">
                          <?php
                            $sqlCriticsNum = $link->query("SELECT * FROM ratingcriticsummary WHERE beid='$plcBeId'");
                            $criticsNum = $sqlCriticsNum->num_rows;
                            if ($criticsNum > 1) {
                              echo numForm($criticsNum)." ".$wrd_ratings;
                            } else {
                              echo numForm($criticsNum)." ".$wrd_rating;
                            }
                          ?>
                        </p>
                      </div>
                    </div>
                    <div id="plc-rating-sect-wrp">
                      <div id="plc-rating-sect-grid">
                        <?php
                          $sqlSections = $link->query("SELECT section, percentage FROM ratingsectionsummary WHERE beid='$plcBeId'");
                          if ($sqlSections->num_rows > 0) {
                            while($sec = $sqlSections->fetch_assoc()) {
                              if ($sec['section'] == "lct") {
                                $secTxt = $wrd_location;
                                $secIcn = "location.svg";
                              } else if ($sec['section'] == "tidy") {
                                $secTxt = $wrd_tidines;
                                $secIcn = "tidy.svg";
                              } else if ($sec['section'] == "prc") {
                                $secTxt = $wrd_price;
                                $secIcn = "price.svg";
                              } else if ($sec['section'] == "park") {
                                $secTxt = $wrd_parking;
                                $secIcn = "parking.svg";
                              } else if ($sec['section'] == "ad") {
                                $secTxt = $wrd_ad;
                                $secIcn = "web.svg";
                              } else {
                                $secTxt = $wrd_unknown;
                                $secIcn = "question3.svg";
                              }
                              $secStars = str_replace('.',',',round($sec['percentage'] * 5 / 100, 2));
                              array_push($plcSectData, [
                                "txt" => $secTxt,
                                "icn" => $secIcn,
                                "percent" => $sec['percentage'],
                                "num" => $secStars
                              ]);
                            }
                            $sqlHostRating = $link->query("SELECT percentage FROM ratingsummary WHERE beid='$plcAccBeId'");
                            if ($sqlHostRating->num_rows == 1) {
                              $rowHostR = $sqlHostRating->fetch_assoc();
                              array_push($plcSectData, [
                                "txt" => $wrd_host,
                                "icn" => "host.svg",
                                "percent" => $rowHostR["percentage"],
                                "num" => str_replace('.',',',round($rowHostR["percentage"] * 5 / 100, 2))
                              ]);
                            }
                          }
                          for ($i=0; $i < sizeof($plcSectData); $i++) {
                        ?>
                            <div class="p-r-s-blck">
                              <div class="p-r-s-identify-wrp">
                                <div class="p-r-s-icn-wrp">
                                  <img alt="" src="../uni/icons/<?php echo $plcSectData[$i]['icn']; ?>" class="p-r-s-icn">
                                </div>
                                <div class="p-r-s-txt-wrp">
                                  <p class="p-r-s-name"><?php echo $plcSectData[$i]['txt']; ?></p>
                                </div>
                              </div>
                              <div class="p-r-s-bar-wrp">
                                <div class="p-r-s-bar">
                                  <div class="p-r-s-progress" style="width: <?php echo $plcSectData[$i]['percent'].'%'; ?>"></div>
                                </div>
                              </div>
                              <div class="p-r-s-txt-wrp">
                                <p class="p-r-s-stars"><?php echo $plcSectData[$i]['num']; ?></p>
                              </div>
                            </div>
                        <?php
                          }
                        ?>
                      </div>
                    </div>
                  </div>
                <?php
                  } else {
                ?>
                  <div id="plc-no-rating-wrp">
                    <p id="plc-no-rating-title"><?php echo "0 ".$wrd_ratings; ?></p>
                    <p id="plc-no-rating-desc"><?php echo $wrd_cottageNotRated; ?></p>
                  </div>
                <?php
                  }
                ?>
              </div>
              <?php
                if ($plcAccRating != "none") {
              ?>
                <div class="p-d-a-block">
                  <div id="p-d-a-comments-wrp">
                    <div id="p-d-a-comments-list"></div>
                    <div id="p-d-a-btn-wrp">
                      <button type="button" id="p-d-a-btn" onclick="plcCommentLoader()">
                        <div id="p-d-a-btn-txt-wrp">
                          <p id="p-d-a-btn-txt"><?php echo $wrd_loadMore; ?></p>
                          <div class="p-d-a-btn-img" id="p-d-a-btn-more-icn"></div>
                        </div>
                        <div class="p-d-a-btn-img" id="p-d-a-btn-loader"></div>
                      </button>
                    </div>
                    <div id="p-d-a-error-blck">
                      <div class="p-d-a-error-wrp" id="p-d-a-error-wrp-1">
                        <p class="p-d-a-error-txt"><?php echo $wrd_noCommentsFound; ?></p>
                      </div>
                      <div class="p-d-a-error-wrp" id="p-d-a-error-wrp-2">
                        <p class="p-d-a-error-txt"><?php echo $wrd_loadCommentScriptError; ?></p>
                        <div class="p-d-a-error-btn-wrp">
                          <button class="btn btn-sml btn-prim" onclick="plcCommentLoaderReset()"><?php echo $wrd_reset;?></button>
                        </div>
                      </div>
                      <div class="p-d-a-error-wrp" id="p-d-a-error-wrp-3">
                        <p class="p-d-a-error-txt"><?php echo $wrd_plcIDNotExist; ?></p>
                        <div class="p-d-a-error-btn-wrp">
                          <button class="btn btn-sml btn-prim" onclick="location.reload()"><?php echo $wrd_refresh;?></button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php
              }
              ?>
            </div>
          </div>
        </div>
        <div id="plc-details-tools-wrp">
          <div id="plc-details-btns-wrp">
            <button type="button" class="p-d-t-btn p-d-t-btn-sec" aria-label="share this cottage" onclick="share('show')">
              <div class="p-d-t-btn-wrp">
                <div class="p-d-t-btn-icn-wrp" id="p-d-t-btn-share-icn-wrp">
                  <img src="../uni/icons/share.svg" alt="" id="p-d-t-share-icn">
                </div>
              </div>
            </button>
            <?php
              if ($plcAccRating != "none") {
            ?>
              <button type="button" class="p-d-t-btn p-d-t-btn-sec" onclick="scrollRating()">
                <div class="p-d-t-btn-wrp" id="p-d-t-btn-wrp-rating">
                  <div class="p-d-t-btn-icn-wrp">
                    <img src="../uni/icons/star.svg" alt="" id="p-d-t-rating-icn">
                  </div>
                  <p class="p-d-t-btn-txt" id="p-d-t-btn-txt-rating"><?php echo $plcAccRating; ?></p>
                </div>
              </button>
            <?php
            }
            ?>
            <button type="button" class="p-d-t-btn p-d-t-btn-sec" onclick="location.href = '../user/?id=<?php echo $plcAccId; ?>&section=about'">
              <div class="p-d-t-btn-wrp" id="p-d-t-btn-wrp-user">
                <?php
                  if ($myPlc) {
                    $userIconId = "my-place-icn";
                    $userUsernameId = "my-place-username";
                  } else {
                    $userIconId = "";
                    $userUsernameId = "";
                  }
                ?>
                <p class="p-d-t-btn-txt p-d-t-btn-txt-user" id="<?php echo $userUsernameId; ?>"><?php echo $plcAccFirstname; ?></p>
                <div class="p-d-t-btn-icn-wrp" id="<?php echo $userIconId; ?>">
                  <img src="../<?php echo $plcAccProfImg; ?>" alt="" id="p-d-t-user-icn">
                </div>
              </div>
            </button>
            <?php
              if ($myPlc) {
            ?>
              <button class="p-d-t-btn btn-prim" id="p-d-t-btn-blind">
                <label for="p-d-t-btn-book" id="p-d-t-btn-label-book">
                  <div class="p-d-t-btn-wrp" id="p-d-t-btn-wrp-book">
                    <p class="p-d-t-btn-txt" id="p-d-t-btn-txt-book"><?php echo $wrd_editor; ?></p>
                    <div class="p-d-t-btn-icn-wrp">
                      <img src="../uni/icons/arrow-right.svg" alt="" id="p-d-t-book-icn">
                    </div>
                  </div>
                </label>
              </button>
            <?php
              } else {
                if ($plcType == "cottage") {
            ?>
              <button class="p-d-t-btn btn-prim" id="p-d-t-btn-blind">
                <label for="p-d-t-btn-book" id="p-d-t-btn-label-book">
                  <div class="p-d-t-btn-wrp" id="p-d-t-btn-wrp-book">
                    <p class="p-d-t-btn-txt" id="p-d-t-btn-txt-book"><?php echo $wrd_book; ?></p>
                    <div class="p-d-t-btn-icn-wrp">
                      <img src="../uni/icons/arrow-right.svg" alt="" id="p-d-t-book-icn">
                    </div>
                  </div>
                </label>
              </button>
            <?php
                }
              }
            ?>
          </div>
          <div id="plc-details-payment-wrp">
            <div id="plc-details-payment-header">
              <p id="plc-details-payment-header-title"><?php echo $wrd_payment; ?></p>
              <?php
                if ($myPlc) {
              ?>
                <button type="button" class="p-d-t-btn btn-sxth" id="p-d-t-btn-book" onclick="location.href = '../editor/?id=<?php echo $plcId; ?>'">
                  <div class="p-d-t-btn-wrp" id="p-d-t-btn-wrp-book-pay">
                    <p class="p-d-t-btn-txt" id="p-d-t-btn-txt-book-pay"><?php echo $wrd_editor; ?></p>
                    <div class="p-d-t-btn-icn-wrp">
                      <img src="../uni/icons/arrow-right.svg" alt="" id="p-d-t-book-icn-pay">
                    </div>
                  </div>
                </button>
              <?php
                } else {
                  if ($plcType == "cottage") {
              ?>
                <button type="button" class="p-d-t-btn btn-prim" id="p-d-t-btn-book" onclick="bookModal('show')">
                  <div class="p-d-t-btn-wrp" id="p-d-t-btn-wrp-book-pay">
                    <p class="p-d-t-btn-txt" id="p-d-t-btn-txt-book-pay"><?php echo $wrd_book; ?></p>
                    <div class="p-d-t-btn-icn-wrp">
                      <img src="../uni/icons/arrow-right.svg" alt="" id="p-d-t-book-icn-pay">
                    </div>
                  </div>
                </button>
              <?php
                  }
                }
              ?>
            </div>
            <div id="plc-details-payment-content">
              <div id="plc-details-payment-content-img-wrp">
                <div id="plc-details-payment-content-img"></div>
              </div>
              <div id="plc-details-payment-price-wrp">
                <?php
                  if ($plcPriceMode == "guests") {
                    $perWhatShrt = $wrd_personPerNight;
                  } else {
                    $perWhatShrt = $wrd_night;
                  }
                  if ($plcPriceWork == $plcPriceWeek) {
                ?>
                  <p class="p-d-p-p-nights"><?php echo $wrd_singlePrice; ?></p>
                  <p class="p-d-p-p-price"><?php echo addCurrency($plcCurrency, $plcPriceWork)." /".$perWhatShrt; ?></p>
                <?php
                  } else {
                ?>
                  <p class="p-d-p-p-nights"><?php echo $wrd_sunday_short."-".$wrd_monday_short.", ".$wrd_monday_short."-".$wrd_tuesday_short.", ".$wrd_tuesday_short."-".$wrd_wednesday_short.", ".$wrd_wednesday_short."-".$wrd_thursday_short.", ".$wrd_thursday_short."-".$wrd_friday_short; ?></p>
                  <p class="p-d-p-p-price"><?php echo addCurrency($plcCurrency, $plcPriceWork)." /".$perWhatShrt; ?></p>
                  <p class="p-d-p-p-nights p-d-p-p-zoom-out-1"><?php echo $wrd_friday_short."-".$wrd_saturday_short.", ".$wrd_saturday_short."-".$wrd_sunday_short; ?></p>
                  <p class="p-d-p-p-price p-d-p-p-zoom-out-2"><?php echo addCurrency($plcCurrency, $plcPriceWeek)." /".$perWhatShrt; ?></p>
                <?php
                  }
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php
        include "php-frontend/book-modal.php";
        include "../uni/code/php-frontend/share.php";
      } else {
    ?>
      <div class="page-error">
        <p class="page-error-p">
          <?php
            if ($idSts == "unset") {
              echo $wrd_wrongUrl;
            } else if ($idSts == "not-exist") {
              echo $wrd_cottageWithIdNotExist;
            } else if ($idSts == "not-matching-data") {
              echo $wrd_idExistButDetailsNot;
            } else if ($idSts == "too-many-places") {
              echo $wrd_multiPlaceToOneID;
            } else if ($idSts == "place-deleted") {
              echo $wrd_placeDeleted;
            }
          ?>
        </p>
      </div>
    <?php
      }
    ?>
  </body>
</html>
