<?php
  include "../uni/code/php-head.php";
  include "../uni/code/php-backend/rating-summary.php";
  include "../uni/code/php-backend/convert-link-in-string.php";
  $accBeId = "";
  $accExist = "empty-id";
  $sect = "about";
  if (isset($_GET['section']) && $_GET['section'] != "") {
    $sect = $_GET['section'];
  }
  $accId = $setid;
  if (isset($_GET['id']) && $_GET['id'] != "") {
    $accId = $_GET['id'];
  }
  if ($accId != "") {
    $accExist = "exist";
  } else {
    if ($sign == "yes") {
      $accId = $setid;
      $accExist = "exist";
    }
  }
  if ($accExist == "exist") {
    $sqlId = $link->query("SELECT beid FROM idlist WHERE id='$accId'");
    if ($sqlId->num_rows > 0) {
      $accBeId = $sqlId->fetch_assoc()['beid'];
      if ($accId != getAccountData($accBeId, "id") || !isset($_GET['id']) || $_GET['id'] == "" || !isset($_GET['section']) || $_GET['section'] == "") {
        header("Location: ../user/?id=".getAccountData($accBeId, "id")."&section=".$sect);
      }
      if (getAccountData($accBeId, "status") == "active") {
        if ($usrBeId == $accBeId) {
          $userIdentify = "my-profile";
        } else {
          $userIdentify = "stranger-profile";
        }
      } else if (getAccountData($accBeId, "status") == "temp") {
        $accExist = "not-activated";
      } else if (getAccountData($accBeId, "status") == "delete") {
        $accExist = "account-deleted";
      }
    } else {
      $accExist = "no-account";
    }
  }

  if ($accExist == "exist") {
    ratingSummary($accBeId);
    ratingCriticSummary($accBeId);
    $accmidimg = getAccountData($accBeId, "medium-profile-image");
    $accfirstname = getAccountData($accBeId, "firstname");
    $acclastname = getAccountData($accBeId, "lastname");
    $accemail = getAccountData($accBeId, "contact-email");
    $accphonenum = getAccountData($accBeId, "contact-phone-num");
    $accdesc = getAccountData($accBeId, "description");
    $acclang_string = $languages_string = rtrim(getAccountData($accBeId, "languages-string"), ", ");
    $acclang_array_length = sizeof(getAccountData($accBeId, "languages-array"));
    $accJoinedD = getAccountData($accBeId, "sign-up-day");
    $accJoinedM = getAccountData($accBeId, "sign-up-month");
    $accJoinedY = getAccountData($accBeId, "sign-up-year");
  } else {
    $userIdentify = "stranger-profile";
    $accmidimg = "uni/images/profile-image-2.png";
    $accfirstname = $wrd_unknown;
    $acclastname = "";
    $accemail = $wrd_unknown;
    $accphonenum = "";
    $accdesc = "";
    $acclang_string = "";
    $acclang_array_length = 0;
    $accJoinedD = 00;
    $accJoinedM = 00;
    $accJoinedY = 0000;
  }
  if ($sect == "huts") {
    $pageTitle = $accfirstname." ".$acclastname." | ".$wrd_huts." - ".$title;
  } else {
    $pageTitle = $accfirstname." ".$acclastname." | ".$wrd_about." - ".$title;
  }
  $subtitle = $accfirstname." ".$acclastname;
?>
<!DOCTYPE html>
<html lang="<?php echo $wrd_htmlLang; ?>">
  <head>
    <meta charset="UTF-8">
    <meta name="description" content="<?php echo $wrd_metaDescriptionUserPage1." ".$accfirstname." ".$acclastname." ".$wrd_metaDescriptionUserPage2." ".$title." ".$wrd_metaDescriptionUserPage3; ?>">
    <?php
      include "../uni/code/php-frontend/head.php";
      if ($sect == "about") {
    ?>
      <link rel="stylesheet" type="text/css" href="css/user-about.css">
      <script src="js/user-about.js" async></script>
    <?php
      } else {
    ?>
      <link rel="stylesheet" type="text/css" href="css/user-huts.css">
      <script src="js/user-huts.js" async></script>
    <?php
      }
    ?>
    <link rel="stylesheet" type="text/css" href="../uni/code/css/link-image.css">
    <link rel="stylesheet" type="text/css" href="../uni/code/css/uni-details-block.css">
    <link rel="stylesheet" type="text/css" href="../uni/code/css/share.css">
    <script src="../uni/code/js/link-image.js" async></script>
    <script src="../uni/code/js/uni-details-block.js" async></script>
    <script src="../uni/code/js/add-currency.js" async></script>
    <script src="../uni/code/js/share.js" async></script>
    <link rel="stylesheet" type="text/css" href="css/user-uni.css">
    <script src="js/user-uni.js" async></script>
    <script src="../libraries/Anchorme/anchrome.js" async></script>
    <title><?php echo $pageTitle; ?></title>
  </head>
  <body onload="<?php echo $onload; ?>userDictionary('<?php echo $wrd_night; ?>', '<?php echo $wrd_personPerNight; ?>', '<?php echo $wrd_doesNotOfferAnyCottages; ?>', '<?php echo $wrd_speaks; ?>', '<?php echo $wrd_speaksMulti; ?>', '<?php echo $wrd_showMore; ?>')">
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
    ?>
    <div id="user-wrp">
      <?php
        if ($accExist == "exist") {
      ?>
      <div id="user-data-wrp">
        <div id="user-data-width">
          <div id="user-data-fixed">
            <div id="user-data-scroll">
              <div id="user-data">
                <?php
                  include "php-frontend/user-details-block.php";
                ?>
                <div class="user-data-blck uni-details-block-style">
                  <div class="uni-details-block-header-style">
                    <p class="uni-details-block-header-title-style"><?php echo $wrd_huts; ?></p>
                    <a href="../user/?id=<?php echo $accId; ?>&section=huts" class="uni-details-block-header-link-style"><?php echo $wrd_seeAll; ?></a>
                  </div>
                  <div id="user-cott-list">
                    <?php
                      $bottomButtonClass = "u-c-l-new-btn-wrp-hide";
                      $sqlCott = "SELECT * FROM places WHERE usrbeid='$accBeId' and status='active' ORDER BY fullDate DESC LIMIT 3";
                      $resultCott = mysqli_query($link, $sqlCott);
                      if ($resultCott->num_rows > 0) {
                        while($cott = $resultCott->fetch_assoc()) {
                          $bottomButtonClass = "u-c-l-new-btn-wrp-show";
                          $cottBeId = $cott['beid'];
                          $cottName = $cott['name'];
                          $cottDesc = $cott['description'];
                          $cottImgSts = false;
                          $sqlCottImgBeId = $link->query("SELECT imgbeid FROM placeimages WHERE cottbeid='$cottBeId' && type='mini' && sts='main' LIMIT 1");
                          if ($sqlCottImgBeId->num_rows > 0) {
                            $cottImgBeId = $sqlCottImgBeId->fetch_assoc()['imgbeid'];
                            $sqlCottImg = $link->query("SELECT src FROM images WHERE name='$cottImgBeId' && status='plc-mini'");
                            if ($sqlCottImg->num_rows > 0) {
                              $cottImg = $sqlCottImg->fetch_assoc()['src'];
                              $cottImgSts = true;
                            }
                          }
                          $sqlCottID = $link->query("SELECT id FROM idlist WHERE beid='$cottBeId' ORDER BY fullDate DESC LIMIT 1");
                          $cottID = $sqlCottID->fetch_assoc()['id'];
                    ?>
                          <div class="u-c-l-wrp">
                            <div class="u-c-l-blck">
                              <a href="../place/?id=<?php echo $cottID; ?>" class="u-c-l-link">
                                <div class="u-c-l-flex">
                                  <div class="u-c-l-img-wrp">
                                    <div class="u-c-l-img-center">
                                      <?php
                                        if ($cottImgSts) {
                                      ?>
                                          <img src="../<?php echo $cottImg; ?>" alt="<?php echo $cottName; ?> small link thumbnail" class="u-c-l-img">
                                      <?php
                                        } else {
                                      ?>
                                        <div class="fake-u-c-l-img"></div>
                                      <?php
                                        }
                                      ?>
                                    </div>
                                  </div>
                                  <div class="u-c-l-txt">
                                    <div class="u-c-l-txt-padding">
                                      <p class="u-c-l-title"><?php echo $cottName; ?></p>
                                      <p class="u-c-l-desc"><?php echo $cottDesc; ?></p>
                                    </div>
                                  </div>
                                </div>
                              </a>
                            </div>
                          </div>
                    <?php
                        }
                      } else {
                        if ($usrBeId == $accBeId) {
                          if ($bnft_add_cottage == "good") {
                    ?>
                            <button type="button" class="u-c-l-alt-cont-btn" onclick="newCottageModal('show')">
                              <div class="u-c-l-alt-cont-btn-img-wrp">
                                <div class="u-c-l-alt-cont-btn-img"></div>
                              </div>
                              <div class="u-c-l-alt-cont-btn-txt-wrp">
                                <p class="u-c-l-alt-cont-btn-txt"><?php echo $wrd_clickToAddCottage;?></p>
                              </div>
                            </button>
                    <?php
                          } else if ($bnft_add_cottage == "none") {
                    ?>
                            <div class="u-c-l-alt-cont-txt">
                              <p><?php echo $wrd_benefitNotAllowed;?></p>
                            </div>
                    <?php
                          } else if ($bnft_add_cottage == "ban") {
                    ?>
                            <div class="u-c-l-alt-cont-txt">
                              <p><?php echo $wrd_youHaveBeenBannedThisFeatureIfAnErrorHasOccurredOrWantYouToKnowMoreContactUs; ?></p>
                            </div>
                    <?php
                          } else {
                    ?>
                            <div class="u-c-l-alt-cont-txt">
                              <p><?php echo $wrd_weWereUnableToDetermineWithCertaintyWhetherOrNotYouHaveThisFeatureEnabledThereforePleaseContactUsOrFillInAndSendTheApplicationAgainWeApologizeForTheInconvenience; ?></p>
                            </div>
                    <?php
                          }
                        } else {
                    ?>
                          <div class="u-c-l-alt-cont-txt">
                            <p id="u-c-l-alt-cont-txt-no-cottage"><?php echo $accfirstname." ".$acclastname." ".$wrd_doesNotOfferAnyCottages;?></p>
                          </div>
                    <?php
                        }
                      }
                      if ($bnft_add_cottage == "good") {
                    ?>
                      <div id="u-c-l-new-btn-wrp" class="<?php echo $bottomButtonClass; ?>">
                        <button type="button" id="u-c-l-new-btn" onclick="newCottageModal('show')">
                          <div id="u-c-l-new-btn-img"></div>
                          <p><?php echo $wrd_addHut; ?></p>
                        </button>
                      </div>
                    <?php
                      }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="user-content">
        <div id="user-content-real-size">
          <div class="user-content-details-blck" id="<?php echo $userIdentify?>">
            <?php
              include "php-frontend/user-details-block.php";
            ?>
          </div>
          <div id="user-content-center">
            <?php
              if ($sect == "about") {
                include "php-frontend/user-about.php";
              } else {
                include "php-frontend/user-huts.php";
              }
            ?>
          </div>
        </div>
      </div>
      <div id="user-content-center-wrp">
        <div id="user-content-center-width"></div>
      </div>
      <?php
          include "../uni/code/php-frontend/share.php";
        } else {
      ?>
        <div class="page-error">
          <p class="page-error-p">
            <?php
              if ($accExist == "empty-id" || $accExist == "no-account") {
                echo $wrd_userDoesNotExist;
              } else if ($accExist == "not-activated") {
                echo $wrd_userIsNotActivated;
              } else if ($accExist == "account-deleted") {
                echo $wrd_userDeleted;
              }
            ?>
          </p>
        </div>
      <?php
        }
      ?>
    </div>
    <?php
      if ($usrBeId != $accBeId) {
    ?>
      <div class="modal-cover" id="modal-cover-user-report">
        <div class="modal-block" id="modal-cover-user-report-blck">
          <button class="cancel-btn" onclick="modCover('hide', 'modal-cover-user-report');reportReset('all')"></button>
          <div id="user-report-slider">
            <div class="alert-blck-wrp" id="user-report-wrp">
              <div class="user-report-reason-blck-wrp" id="user-report-reason-blck-wrp-1">
                <button class="user-report-reason-btn" id="user-report-reason-btn-1" onclick="reportUser(1)" value="hide">
                  <p class="user-report-reason-p"><?php echo $wrd_userScam; ?></p>
                  <div class="user-report-reason-img"></div>
                </button>
                <div class="user-report-reason-checkbox-wrp" id="user-report-reason-checkbox-wrp-1">
                  <div class="user-report-one-reason-wrp">
                    <p class="user-report-one-reason-p"><?php echo $accfirstname." ".$wrd_userNotResponding; ?></p>
                    <div class="user-report-one-reason-check-wrp">
                      <label class="user-report-check-container">
                        <input type="radio" name="report-user" class="user-report-check-inpt" value="<?php echo $accfirstname." ".$wrd_userNotResponding; ?>">
                        <span class="user-report-check-checkmark" onclick="reportUserCheckClick()"></span>
                      </label>
                    </div>
                  </div>
                  <div class="user-report-one-reason-wrp">
                    <p class="user-report-one-reason-p"><?php echo $wrd_noChaletsAvailable; ?></p>
                    <div class="user-report-one-reason-check-wrp">
                      <label class="user-report-check-container">
                        <input type="radio" name="report-user" class="user-report-check-inpt" value="<?php echo $wrd_noChaletsAvailable; ?>">
                        <span class="user-report-check-checkmark" onclick="reportUserCheckClick()"></span>
                      </label>
                    </div>
                  </div>
                  <div class="user-report-one-reason-wrp">
                    <p class="user-report-one-reason-p"><?php echo $wrd_cottagesDoNotExist; ?></p>
                    <div class="user-report-one-reason-check-wrp">
                      <label class="user-report-check-container">
                        <input type="radio" name="report-user" class="user-report-check-inpt" value="<?php echo $wrd_cottagesDoNotExist; ?>">
                        <span class="user-report-check-checkmark" onclick="reportUserCheckClick()"></span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="user-report-reason-blck-wrp" id="user-report-reason-blck-wrp-2">
                <button class="user-report-reason-btn" id="user-report-reason-btn-2" onclick="reportUser(2)" value="hide">
                  <p class="user-report-reason-p"><?php echo $wrd_inappropriateContent; ?></p>
                  <div class="user-report-reason-img"></div>
                </button>
                <div class="user-report-reason-checkbox-wrp" id="user-report-reason-checkbox-wrp-2">
                  <div class="user-report-one-reason-wrp">
                    <p class="user-report-one-reason-p"><?php echo $wrd_advertisementOrOfferingGoods; ?></p>
                    <div class="user-report-one-reason-check-wrp">
                      <label class="user-report-check-container">
                        <input type="radio" name="report-user" class="user-report-check-inpt" value="<?php echo $wrd_advertisementOrOfferingGoods; ?>">
                        <span class="user-report-check-checkmark" onclick="reportUserCheckClick()"></span>
                      </label>
                    </div>
                  </div>
                  <div class="user-report-one-reason-wrp">
                    <p class="user-report-one-reason-p"><?php echo $wrd_descriptionIsOffensive; ?></p>
                    <div class="user-report-one-reason-check-wrp">
                      <label class="user-report-check-container">
                        <input type="radio" name="report-user" class="user-report-check-inpt" value="<?php echo $wrd_descriptionIsOffensive; ?>">
                        <span class="user-report-check-checkmark" onclick="reportUserCheckClick()"></span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="user-report-reason-blck-wrp" id="user-report-reason-blck-wrp-3">
                <button class="user-report-reason-btn" id="user-report-reason-btn-3" onclick="reportUser(3)" value="hide">
                  <p class="user-report-reason-p"><?php echo $wrd_ruleViolation; ?></p>
                  <div class="user-report-reason-img"></div>
                </button>
                <div class="user-report-reason-checkbox-wrp" id="user-report-reason-checkbox-wrp-3">
                  <div class="user-report-one-reason-wrp">
                    <p class="user-report-one-reason-p"><?php echo $wrd_isDiscriminatory; ?></p>
                    <div class="user-report-one-reason-check-wrp">
                      <label class="user-report-check-container">
                        <input type="radio" name="report-user" class="user-report-check-inpt" value="<?php echo $wrd_isDiscriminatory; ?>">
                        <span class="user-report-check-checkmark" onclick="reportUserCheckClick()"></span>
                      </label>
                    </div>
                  </div>
                  <div class="user-report-one-reason-wrp">
                    <p class="user-report-one-reason-p"><?php echo $wrd_inappropriateBehavior ?></p>
                    <div class="user-report-one-reason-check-wrp">
                      <label class="user-report-check-container">
                        <input type="radio" name="report-user" class="user-report-check-inpt" value="<?php echo $wrd_inappropriateBehavior ?>">
                        <span class="user-report-check-checkmark" onclick="reportUserCheckClick()"></span>
                      </label>
                    </div>
                  </div>
                  <div class="user-report-one-reason-wrp">
                    <p class="user-report-one-reason-p"><?php echo $wrd_rudeOrHostile; ?></p>
                    <div class="user-report-one-reason-check-wrp">
                      <label class="user-report-check-container">
                        <input type="radio" name="report-user" class="user-report-check-inpt" value="<?php echo $wrd_rudeOrHostile; ?>">
                        <span class="user-report-check-checkmark" onclick="reportUserCheckClick()"></span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="user-report-reason-blck-wrp" id="user-report-reason-blck-wrp-4">
                <button class="user-report-reason-btn" id="user-report-reason-btn-4" onclick="reportUser(4)" value="hide">
                  <p class="user-report-reason-p"><?php echo $wrd_report." ".$wrd_personalData2; ?></p>
                  <div class="user-report-reason-img"></div>
                </button>
                <div class="user-report-reason-checkbox-wrp" id="user-report-reason-checkbox-wrp-4">
                  <div class="user-report-one-reason-wrp">
                    <p class="user-report-one-reason-p"><?php echo $wrd_inappropriateUsername; ?></p>
                    <div class="user-report-one-reason-check-wrp">
                      <label class="user-report-check-container">
                        <input type="radio" name="report-user" class="user-report-check-inpt" value="<?php echo $wrd_inappropriateUsername; ?>">
                        <span class="user-report-check-checkmark" onclick="reportUserCheckClick()"></span>
                      </label>
                    </div>
                  </div>
                  <div class="user-report-one-reason-wrp">
                    <p class="user-report-one-reason-p"><?php echo $wrd_fakeContactDetails ?></p>
                    <div class="user-report-one-reason-check-wrp">
                      <label class="user-report-check-container">
                        <input type="radio" name="report-user" class="user-report-check-inpt" value="<?php echo $wrd_fakeContactDetails ?>">
                        <span class="user-report-check-checkmark" onclick="reportUserCheckClick()"></span>
                      </label>
                    </div>
                  </div>
                  <div class="user-report-one-reason-wrp">
                    <p class="user-report-one-reason-p"><?php echo $wrd_foreignContactInformation; ?></p>
                    <div class="user-report-one-reason-check-wrp">
                      <label class="user-report-check-container">
                        <input type="radio" name="report-user" class="user-report-check-inpt" value="<?php echo $wrd_foreignContactInformation; ?>">
                        <span class="user-report-check-checkmark" onclick="reportUserCheckClick()"></span>
                      </label>
                    </div>
                  </div>
                  <div class="user-report-one-reason-wrp">
                    <p class="user-report-one-reason-p"><?php echo $wrd_profileImageCopyright; ?></p>
                    <div class="user-report-one-reason-check-wrp">
                      <label class="user-report-check-container">
                        <input type="radio" name="report-user" class="user-report-check-inpt" value="<?php echo $wrd_profileImageCopyright; ?>">
                        <span class="user-report-check-checkmark" onclick="reportUserCheckClick()"></span>
                      </label>
                    </div>
                  </div>
                  <div class="user-report-one-reason-wrp">
                    <p class="user-report-one-reason-p"><?php echo $wrd_inappropriateProfilePicture; ?></p>
                    <div class="user-report-one-reason-check-wrp">
                      <label class="user-report-check-container">
                        <input type="radio" name="report-user" class="user-report-check-inpt" value="<?php echo $wrd_inappropriateProfilePicture; ?>">
                        <span class="user-report-check-checkmark" onclick="reportUserCheckClick()"></span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="user-report-reason-blck-wrp" id="user-report-reason-blck-wrp-5">
                <button class="user-report-reason-btn" id="user-report-reason-btn-5" onclick="reportUser(5)" value="hide">
                  <p class="user-report-reason-p"><?php echo $wrd_somethingElse; ?></p>
                  <div class="user-report-reason-img"></div>
                </button>
                <div class="user-report-reason-checkbox-wrp" id="user-report-reason-checkbox-wrp-5">
                  <textarea id="user-report-textarea" placeholder="<?php echo $wrd_whyReport; ?>" onkeydown="reportUserAreaKey()" onkeypress="reportUserAreaKey()" onkeyup="reportUserAreaKey()"></textarea>
                </div>
              </div>
            </div>
          </div>
          <div id="user-report-save-wrp">
            <button class="sett-save-btn btn btn-mid btn-prim" id="user-report-btn" onclick="sendUserReport()"><?php echo $wrd_report; ?></button>
          </div>
          <div id="user-report-error-wrp">
            <p id="user-report-err"></p>
          </div>
          <div id="user-report-thanks-wrp">
            <h1><?php echo $wrd_thanksReport; ?></h1>
            <p><?php echo $wrd_thanksReportText1; ?></p>
            <p><?php echo $wrd_thanksReportText2; ?></p>
          </div>
        </div>
      </div>
    <?php
      }
    ?>
    <div class="modal-cover" id="modal-cover-comment-detail">
      <div class="modal-block" id="modal-cover-comment-detail-blck">
        <button class="cancel-btn" onclick="userCommModal('hide', '')"></button>
        <div id="usr-comment-detail-wrp">
          <p id="usr-comment-detail-txt"></p>
        </div>
      </div>
    </div>
  </body>
</html>
