<?php
  include "../uni/code/php-head.php";
  include "../../uni/code/php-backend/add-currency.php";
  include "../uni/code/php-backend/check-timeliness-of-booking.php";
  include "php-backend/get-booking-history.php";
  include "php-backend/get-additional-updates.php";
  $idSts = "unset";
  $bookingID = "none";
  if (isset($_GET['id'])) {
    $bookingID = $_GET['id'];
    $idSts = "good";
  }
  $sectionSts = "unset";
  if (isset($_GET['section'])) {
    $contentSection = $_GET['section'];
    $sectionSts = "good";
  }
  $navSts = "unset";
  if (isset($_GET['nav'])) {
    $selectedNav = $_GET['nav'];
    $navSts = "good";
  }
  if ($sectionSts != "good" || $navSts != "good") {
    if ($idSts == "good") {
      if ($sectionSts == "good") {
        if ($navSts != "good") {
          header("Location: ../booking/?section=".$contentSection."&nav=about&id=".$bookingID);
        }
      } else {
        if ($navSts == "good") {
          header("Location: ../booking/?section=bookings&nav=".$selectedNav."&id=".$bookingID);
        }  else {
          header("Location: ../booking/?section=bookings&nav=about&id=".$bookingID);
        }
      }
    } else {
      if ($sectionSts == "good") {
        if ($navSts != "good") {
          header("Location: ../booking/?section=".$contentSection."&nav=about");
        }
      } else {
        if ($navSts == "good") {
          header("Location: ../booking/?section=bookings&nav=".$selectedNav);
        }  else {
          header("Location: ../booking/?section=bookings&nav=about");
        }
      }
    }
  }
  $bookingStatus = "";
  $bookingPaymentStatus = "";
  if ($idSts == "good") {
    $sqlBeId = $link->query("SELECT beid FROM idlist WHERE id='$bookingID'");
    if ($sqlBeId->num_rows > 0) {
      $bookingBeId = $sqlBeId->fetch_assoc()['beid'];
      $sqlBookingsArchive = $linkBD->query("SELECT * FROM bookingarchive WHERE beid='$bookingBeId'");
      if ($sqlBookingsArchive->num_rows > 0) {
        $bookArch = $sqlBookingsArchive->fetch_assoc();
        $bookingStatus = $bookArch['status'];
        $bookingPaymentStatus = $bookArch['paymentStatus'];
        $plcBeId = $bookArch['plcbeid'];
        $sqlPlace = $link->query("SELECT * FROM places WHERE beid='$plcBeId'");
        if ($sqlPlace->num_rows > 0) {
          $plc = $sqlPlace->fetch_assoc();
          if ($plc['status'] == "active") {
            $placeStatus = "good";
          } else {
            $placeStatus = $plc['status'];
          }
        } else {
          $placeStatus = "not-exist";
        }
        $hostBeId = $bookArch['hostbeid'];
        $sqlHost = $linkBD->query("SELECT * FROM usersarchive WHERE beid='$hostBeId'");
        if ($sqlHost->num_rows > 0) {
          $hst = $sqlHost->fetch_assoc();
          if ($hst['status'] == "active") {
            $hostStatus = "good";
          } else {
            $hostStatus = $hst['status'];
          }
        } else {
          $hostStatus = "not-exist";
        }
        $gstID = "-";
        $gstName = "";
        $gstEmail = "";
        $gstPhone = "";
        $gstLanguage = "";
        $guestBeId = $bookArch['usrbeid'];
        if ($guestBeId != "-") {
          $gstID = getFrontendId($guestBeId);
        }
        $sqlGuest = $link->query("SELECT * FROM booking WHERE beid='$bookingBeId'");
        if ($sqlGuest->num_rows > 0) {
          $gst = $sqlGuest->fetch_assoc();
          $gstStatus = "good";
          $gstName = $gst['name'];
          $gstEmail = $gst['email'];
          $gstPhone = $gst['phonenum'];
          $gstLanguage = $gst['language'];
        } else {
          $gstStatus = "not-exist";
        }
        if ($gstStatus != "good" && $guestBeId != "-") {
          $sqlGuest = $linkBD->query("SELECT * FROM usersarchive WHERE beid='$guestBeId'");
          if ($sqlGuest->num_rows > 0) {
            $gst = $sqlGuest->fetch_assoc();
            if ($gst['status'] == "active") {
              $gstStatus = "good";
              $gstName = $gst['firstname']." ".$gst['lastname'];
              $gstEmail = $gst['contactemail'];
              $gstPhone = $gst['contactphonenum'];
              $gstLanguage = $gst['language'];
            } else {
              $gstStatus = $gst['status'];
            }
          } else {
            $gstStatus = "not-exist";
          }
        }
      } else {
        $idSts = "No data found in archive database";
      }
    } else {
      $idSts = "ID does not exist in database";
    }
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <?php
      include "../uni/code/php-frontend/head.php";
    ?>
    <script src="js/booking.js"></script>
    <script src="js/history.js"></script>
    <script src="js/additional-updates.js"></script>
    <script src="../../uni/code/js/add-currency.js"></script>
    <script src="../uni/code/js/details-page.js"></script>
    <script src="../../uni/code/js/scroll-to-animation.js"></script>
    <link rel="stylesheet" type="text/css" href="css/history.css">
    <link rel="stylesheet" type="text/css" href="css/additional-updates.css">
    <link rel="stylesheet" type="text/css" href="../../uni/code/css/table.css">
    <link rel="stylesheet" type="text/css" href="../uni/code/css/details-page.css">
    <title><?php echo $wrd_bookingID.": ".$bookingID." - ".$title." Back Door"; ?></title>
  </head>
  <body>
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
    ?>
    <div id="page-layout">
      <?php
        include "../uni/code/php-frontend/main-menu.php";
      ?>
      <div id="content-wrp">
        <div id="content">
          <?php
            if ($idSts == "good") {
          ?>
              <div class="details-page-wrp">
                <div class="details-page-header">
                  <div class="details-page-header-title-size">
                    <div class="details-page-header-title-wrp">
                      <h1 class="details-page-header-title"><?php echo $wrd_bookingID.": "; ?><span><?php echo $bookingID; ?></span></h1>
                    </div>
                  </div>
                  <div class="details-page-header-options-wrp">
                    <?php
                      $headerOptionBtnUnpaid = "";
                      $headerOptionBtnPaid = "";
                      if ($bookingPaymentStatus == "1") {
                        $headerOptionBtnUnpaid = "";
                        $headerOptionBtnPaid = "details-page-header-options-btn-hidden";
                      } else {
                        $headerOptionBtnUnpaid = "details-page-header-options-btn-hidden";
                        $headerOptionBtnPaid = "";
                      }
                    ?>
                    <button class="btn btn-big btn-sec details-page-header-options-btn <?php echo $headerOptionBtnUnpaid; ?>" id="details-page-header-options-btn-unpaid" onclick="bookingPaymentStsToggle();"><?php echo $wrd_unpaid ?></button>
                    <button class="btn btn-big btn-sec details-page-header-options-btn <?php echo $headerOptionBtnPaid; ?>" id="details-page-header-options-btn-paid" onclick="bookingPaymentStsToggle();"><?php echo $wrd_paid ?></button>
                  </div>
                </div>
                <div class="details-page-nav-wrp">
                  <div class="details-page-nav-slider-btn-wrp details-page-nav-slider-btn-wrp-left" id="details-page-nav-slider-btn-wrp-left-booking-nav">
                    <button type="button" name="button" class="details-page-nav-slider-btn" onclick="detailsPageNavSliderBtnLeftOnclick('booking-nav')"></button>
                  </div>
                  <div class="details-page-nav-slider-btn-wrp details-page-nav-slider-btn-wrp-right" id="details-page-nav-slider-btn-wrp-right-booking-nav">
                    <button type="button" name="button" class="details-page-nav-slider-btn" onclick="detailsPageNavSliderBtnRightOnclick('booking-nav')"></button>
                  </div>
                  <div class="details-page-nav-slider" id="details-page-nav-slider-booking-nav" onscroll="detailsPageNavSliderBtnHandler('booking-nav')">
                    <div class="details-page-nav-slider-content" id="details-page-nav-slider-content-booking-nav">
                      <div class="details-page-nav-slider-content-border-bottom"></div>
                      <?php
                        $navClassAbout = "";
                        $navClassHistory = "";
                        $navClassAdditionalUpdates = "";
                        if ($selectedNav == "about") {
                          $navClassAbout = "details-page-nav-link-selected";
                        } else if ($selectedNav == "history") {
                          $navClassHistory = "details-page-nav-link-selected";
                        } else if ($selectedNav == "additionalUpdates") {
                          $navClassAdditionalUpdates = "details-page-nav-link-selected";
                        }
                      ?>
                      <a href="../booking/?section=<?php echo $contentSection; ?>&nav=about&id=<?php echo $bookingID; ?>" class="details-page-nav-link <?php echo $navClassAbout; ?>"><?php echo $wrd_about; ?></a>
                      <a href="../booking/?section=<?php echo $contentSection; ?>&nav=history&id=<?php echo $bookingID; ?>" class="details-page-nav-link <?php echo $navClassHistory; ?>"><?php echo $wrd_history; ?></a>
                      <a href="../booking/?section=<?php echo $contentSection; ?>&nav=additionalUpdates&id=<?php echo $bookingID; ?>" class="details-page-nav-link <?php echo $navClassAdditionalUpdates; ?>"><?php echo $wrd_additionalUpdates; ?></a>
                    </div>
                  </div>
                </div>
                <div class="details-page-content-wrp">
                  <div class="details-page-content-size">
                    <div class="details-page-content-layout">
                      <?php
                        if ($selectedNav == "about") {
                          include "php-frontend/about.php";
                        } else if ($selectedNav == "history") {
                          include "php-frontend/history.php";
                        } else if ($selectedNav == "additionalUpdates") {
                          include "php-frontend/additional-updates.php";
                        }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
          <?php
            } else {
          ?>
            <div class="content-error-wrp">
              <p class="content-error-txt"><?php echo $idSts; ?></p>
            </div>
          <?php
            }
          ?>
        </div>
      </div>
    </div>
  </body>
</html>
