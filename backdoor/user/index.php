<?php
  include "../uni/code/php-head.php";
  include "../../uni/code/php-backend/add-currency.php";
  include "php-backend/get-list-of-host-bookings.php";
  $idSts = "unset";
  $userID = "none";
  if (isset($_GET['id'])) {
    $userID = $_GET['id'];
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
  $periodM = 0;
  $periodMSts = "unset";
  if (isset($_GET['m'])) {
    $periodM = $_GET['m'];
    if (!is_numeric($periodM)) {
      $periodM = 0;
    }
    $periodMSts = "good";
  }
  $periodY = 0;
  $periodYSts = "unset";
  if (isset($_GET['y'])) {
    $periodY = $_GET['y'];
    if (!is_numeric($periodY)) {
      $periodY = 0;
    }
    $periodYSts = "good";
  }
  $urlPaymentReference = "";
  if (isset($_GET['paymentreference'])) {
    $urlPaymentReference = $_GET['paymentreference'];
  }
  if ($sectionSts != "good" || $navSts != "good" || $periodMSts != "good" || $periodYSts != "good") {
    if ($idSts == "good") {
      if ($sectionSts == "good") {
        if ($navSts != "good") {
          if ($periodMSts != "good") {
            if ($periodYSts != "good") {
              header("Location: ../user/?section=".$contentSection."&nav=about&id=".$userID."&m=&y=");
            } else {
              header("Location: ../user/?section=".$contentSection."&nav=about&id=".$userID."&m=&y=".$periodY);
            }
          } else {
            if ($periodYSts != "good") {
              header("Location: ../user/?section=".$contentSection."&nav=about&id=".$userID."&m=".$periodM."&y=");
            } else {
              header("Location: ../user/?section=".$contentSection."&nav=about&id=".$userID."&m=".$periodM."&y=".$periodY);
            }
          }
        } else {
          if ($periodMSts != "good") {
            if ($periodYSts != "good") {
              header("Location: ../user/?section=".$contentSection."&nav=".$selectedNav."&id=".$userID."&m=&y=");
            } else {
              header("Location: ../user/?section=".$contentSection."&nav=".$selectedNav."&id=".$userID."&m=&y=".$periodY);
            }
          } else {
            if ($periodYSts != "good") {
              header("Location: ../user/?section=".$contentSection."&nav=".$selectedNav."&id=".$userID."&m=".$periodM."&y=");
            } else {
              header("Location: ../user/?section=".$contentSection."&nav=".$selectedNav."&id=".$userID."&m=".$periodM."&y=".$periodY);
            }
          }
        }
      } else {
        if ($navSts == "good") {
          if ($periodMSts != "good") {
            if ($periodYSts != "good") {
              header("Location: ../user/?section=users&nav=".$selectedNav."&id=".$userID."&m=&y=");
            } else {
              header("Location: ../user/?section=users&nav=".$selectedNav."&id=".$userID."&m=&y=".$periodY);
            }
          } else {
            if ($periodYSts != "good") {
              header("Location: ../user/?section=users&nav=".$selectedNav."&id=".$userID."&m=".$periodM."&y=");
            } else {
              header("Location: ../user/?section=users&nav=".$selectedNav."&id=".$userID."&m=".$periodM."&y=".$periodY);
            }
          }
        }  else {
          if ($periodMSts != "good") {
            if ($periodYSts != "good") {
              header("Location: ../user/?section=users&nav=about&id=".$userID."&m=&y=");
            } else {
              header("Location: ../user/?section=users&nav=about&id=".$userID."&m=&y=".$periodY);
            }
          } else {
            if ($periodYSts != "good") {
              header("Location: ../user/?section=users&nav=about&id=".$userID."&m=".$periodM."&y=");
            } else {
              header("Location: ../user/?section=users&nav=about&id=".$userID."&m=".$periodM."&y=".$periodY);
            }
          }
        }
      }
    } else {
      if ($sectionSts == "good") {
        if ($navSts != "good") {
          if ($periodMSts != "good") {
            if ($periodYSts != "good") {
              header("Location: ../user/?section=".$contentSection."&nav=about&m=&y=");
            } else {
              header("Location: ../user/?section=".$contentSection."&nav=about&m=&y=".$periodY);
            }
          } else {
            if ($periodYSts != "good") {
              header("Location: ../user/?section=".$contentSection."&nav=about&m=".$periodM."&y=");
            } else {
              header("Location: ../user/?section=".$contentSection."&nav=about&m=".$periodM."&y=".$periodY);
            }
          }
        } else {
          if ($periodMSts != "good") {
            if ($periodYSts != "good") {
              header("Location: ../user/?section=".$contentSection."&nav=".$selectedNav."&m=&y=");
            } else {
              header("Location: ../user/?section=".$contentSection."&nav=".$selectedNav."&m=&y=".$periodY);
            }
          } else {
            if ($periodYSts != "good") {
              header("Location: ../user/?section=".$contentSection."&nav=".$selectedNav."&m=".$periodM."&y=");
            } else {
              header("Location: ../user/?section=".$contentSection."&nav=".$selectedNav."&m=".$periodM."&y=".$periodY);
            }
          }
        }
      } else {
        if ($navSts == "good") {
          if ($periodMSts != "good") {
            if ($periodYSts != "good") {
              header("Location: ../user/?section=users&nav=".$selectedNav."&m=&y=");
            } else {
              header("Location: ../user/?section=users&nav=".$selectedNav."&m=&y=".$periodY);
            }
          } else {
            if ($periodYSts != "good") {
              header("Location: ../user/?section=users&nav=".$selectedNav."&m=".$periodM."&y=");
            } else {
              header("Location: ../user/?section=users&nav=".$selectedNav."&m=".$periodM."&y=".$periodY);
            }
          }
        } else {
          if ($periodMSts != "good") {
            if ($periodYSts != "good") {
              header("Location: ../user/?section=users&nav=about&m=&y=");
            } else {
              header("Location: ../user/?section=users&nav=about&m=&y=".$periodY);
            }
          } else {
            if ($periodYSts != "good") {
              header("Location: ../user/?section=users&nav=about&m=".$periodM."&y=");
            } else {
              header("Location: ../user/?section=users&nav=about&m=".$periodM."&y=".$periodY);
            }
          }
        }
      }
    }
  }
  if ($idSts == "good") {
    $sqlBeId = $link->query("SELECT beid FROM idlist WHERE id='$userID'");
    if ($sqlBeId->num_rows > 0) {
      $userBeId = $sqlBeId->fetch_assoc()['beid'];
      $sqlAboutUser = $linkBD->query("SELECT * FROM usersarchive WHERE beid='$userBeId'");
      if ($sqlAboutUser->num_rows > 0) {
        $aboutUsr = $sqlAboutUser->fetch_assoc();
        
      } else {
        $idSts = "User not found in database";
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
    <script src="js/host-bookings.js"></script>
    <script src="../uni/code/js/details-page.js"></script>
    <script src="../../uni/code/js/add-currency.js"></script>
    <script src="../../uni/code/js/scroll-to-animation.js"></script>
    <link rel="stylesheet" type="text/css" href="css/host-bookings.css">
    <link rel="stylesheet" type="text/css" href="../../uni/code/css/table.css">
    <link rel="stylesheet" type="text/css" href="../uni/code/css/details-page.css">
    <title><?php echo $wrd_userID.": ".$userID." - ".$title." Back Door"; ?></title>
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
                      <h1 class="details-page-header-title"><?php echo $wrd_userID.": "; ?><span><?php echo $userID; ?></span></h1>
                    </div>
                  </div>
                </div>
                <div class="details-page-nav-wrp">
                  <div class="details-page-nav-slider-btn-wrp details-page-nav-slider-btn-wrp-left" id="details-page-nav-slider-btn-wrp-left-user-nav">
                    <button type="button" name="button" class="details-page-nav-slider-btn" onclick="detailsPageNavSliderBtnLeftOnclick('user-nav')"></button>
                  </div>
                  <div class="details-page-nav-slider-btn-wrp details-page-nav-slider-btn-wrp-right" id="details-page-nav-slider-btn-wrp-right-user-nav">
                    <button type="button" name="button" class="details-page-nav-slider-btn" onclick="detailsPageNavSliderBtnRightOnclick('user-nav')"></button>
                  </div>
                  <div class="details-page-nav-slider" id="details-page-nav-slider-user-nav" onscroll="detailsPageNavSliderBtnHandler('user-nav')">
                    <div class="details-page-nav-slider-content" id="details-page-nav-slider-content-user-nav">
                      <div class="details-page-nav-slider-content-border-bottom"></div>
                      <?php
                        $navClassAbout = "";
                        $navClassHostBookings = "";
                        if ($selectedNav == "about") {
                          $navClassAbout = "details-page-nav-link-selected";
                        } else if ($selectedNav == "hostbookings") {
                          $navClassHostBookings = "details-page-nav-link-selected";
                        }
                      ?>
                      <a href="../user/?section=<?php echo $contentSection; ?>&nav=about&id=<?php echo $userID; ?>&m=&y=" class="details-page-nav-link <?php echo $navClassAbout; ?>"><?php echo $wrd_about; ?></a>
                      <a href="../user/?section=<?php echo $contentSection; ?>&nav=hostbookings&id=<?php echo $userID; ?>&m=&y=" class="details-page-nav-link <?php echo $navClassHostBookings; ?>"><?php echo $wrd_bookingsHost; ?></a>
                    </div>
                  </div>
                </div>
                <div class="details-page-content-wrp">
                  <div class="details-page-content-size">
                    <div class="details-page-content-layout">
                      <?php
                        if ($selectedNav == "about") {
                          include "php-frontend/about.php";
                        } else if ($selectedNav == "hostbookings") {
                          include "php-frontend/host-bookings.php";
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
