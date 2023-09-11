<?php
  if ($plcAllreadyRated) {
    $ratingsOverviewStyle = "style='display: flex;'";
  } else {
    $ratingsOverviewStyle = "";
  }
  if ($ratingPlcSectLct == "none") {
    $ratingPlcSectLctClass = "r-o-s-d-block-not-rated";
    $ratingPlcSectLctPerc = 0;
  } else {
    $ratingPlcSectLctClass = "";
    $ratingPlcSectLctPerc = $ratingPlcSectLct;
  }
  if ($ratingPlcSectTidy == "none") {
    $ratingPlcSectTidyClass = "r-o-s-d-block-not-rated";
    $ratingPlcSectTidyPerc = 0;
  } else {
    $ratingPlcSectTidyClass = "";
    $ratingPlcSectTidyPerc = $ratingPlcSectTidy;
  }
  if ($ratingPlcSectPrc == "none") {
    $ratingPlcSectPrcClass = "r-o-s-d-block-not-rated";
    $ratingPlcSectPrcPerc = 0;
  } else {
    $ratingPlcSectPrcClass = "";
    $ratingPlcSectPrcPerc = $ratingPlcSectPrc;
  }
  if ($ratingPlcSectPark == "none") {
    $ratingPlcSectParkClass = "r-o-s-d-block-not-rated";
    $ratingPlcSectParkPerc = 0;
  } else {
    $ratingPlcSectParkClass = "";
    $ratingPlcSectParkPerc = $ratingPlcSectPark;
  }
  if ($ratingPlcSectAd == "none") {
    $ratingPlcSectAdClass = "r-o-s-d-block-not-rated";
    $ratingPlcSectAdPerc = 0;
  } else {
    $ratingPlcSectAdClass = "";
    $ratingPlcSectAdPerc = $ratingPlcSectAd;
  }
  if ($hostAllreadyRated) {
    $hostRatingBtnStyle = "style='display: none;'";
    $hostRatingOverviewStyle = "";
  } else {
    $hostRatingBtnStyle = "";
    $hostRatingOverviewStyle = "style='display: none;'";
  }
  if ($ratingHstSectLang == "none") {
    $ratingHstSectLangClass = "r-o-s-d-block-not-rated";
    $ratingHstSectLangPerc = 0;
  } else {
    $ratingHstSectLangClass = "";
    $ratingHstSectLangPerc = $ratingHstSectLang;
  }
  if ($ratingHstSectComm == "none") {
    $ratingHstSectCommClass = "r-o-s-d-block-not-rated";
    $ratingHstSectCommPerc = 0;
  } else {
    $ratingHstSectCommClass = "";
    $ratingHstSectCommPerc = $ratingHstSectComm;
  }
  if ($ratingHstSectPrsn == "none") {
    $ratingHstSectPrsnClass = "r-o-s-d-block-not-rated";
    $ratingHstSectPrsnPerc = 0;
  } else {
    $ratingHstSectPrsnClass = "";
    $ratingHstSectPrsnPerc = $ratingHstSectPrsn;
  }
?>
<div id="ratings-overview-wrp" <?php echo $ratingsOverviewStyle; ?>>
  <div class="ratings-overview-section-wrp">
    <div class="set-up-step-by-step-content-title-wrp">
      <span class="set-up-step-by-step-content-title-details"><a href="<?php echo "../place/?id=".$rt_plcID; ?>" target="_blank"><?php echo $plcNameShrt; ?></a></span>
    </div>
    <div class="ratings-overview-section-layout">
      <div class="ratings-overview-section-summary">
        <div class="ratings-overview-section-summary-layout">
          <img alt="" src="../uni/icons/star.svg" class="ratings-overview-section-summary-stars-img">
          <p class="ratings-overview-section-summary-stars-num" id="ratings-overview-section-summary-stars-num-place"><?php echo $criticsRatingSummaryPlace; ?></p>
        </div>
      </div>
      <div class="ratings-overview-section-details">
        <div class="ratings-overview-section-details-grid">
          <div class="r-o-s-d-block <?php echo $ratingPlcSectLctClass; ?>" id="r-o-s-d-block-places-location">
            <div class="r-o-s-d-about">
              <p class="r-o-s-d-name"><?php echo $wrd_location; ?></p>
              <button type="button" class="r-o-s-d-edit-btn" onclick="ratingsOverviewRateSection('location', 'places');"><?php echo $wrd_edit; ?></button>
            </div>
            <div class="r-o-s-d-bar">
              <div class="r-o-s-d-progress" id="r-o-s-d-progress-places-location" style='<?php echo "width: ".$ratingPlcSectLctPerc."%;" ?>'></div>
            </div>
          </div>
          <div class="r-o-s-d-block <?php echo $ratingPlcSectTidyClass; ?>" id="r-o-s-d-block-places-tidines">
            <div class="r-o-s-d-about">
              <p class="r-o-s-d-name"><?php echo $wrd_tidines; ?></p>
              <button type="button" class="r-o-s-d-edit-btn" onclick="ratingsOverviewRateSection('tidines', 'places');"><?php echo $wrd_edit; ?></button>
            </div>
            <div class="r-o-s-d-bar">
              <div class="r-o-s-d-progress" id="r-o-s-d-progress-places-tidines" style='<?php echo "width: ".$ratingPlcSectTidyPerc."%;" ?>'></div>
            </div>
          </div>
          <div class="r-o-s-d-block <?php echo $ratingPlcSectPrcClass; ?>" id="r-o-s-d-block-places-price">
            <div class="r-o-s-d-about">
              <p class="r-o-s-d-name"><?php echo $wrd_price; ?></p>
              <button type="button" class="r-o-s-d-edit-btn" onclick="ratingsOverviewRateSection('price', 'places');"><?php echo $wrd_edit; ?></button>
            </div>
            <div class="r-o-s-d-bar">
              <div class="r-o-s-d-progress" id="r-o-s-d-progress-places-price" style='<?php echo "width: ".$ratingPlcSectPrcPerc."%;" ?>'></div>
            </div>
          </div>
          <div class="r-o-s-d-block <?php echo $ratingPlcSectParkClass; ?>" id="r-o-s-d-block-places-parking">
            <div class="r-o-s-d-about">
              <p class="r-o-s-d-name"><?php echo $wrd_parking; ?></p>
              <button type="button" class="r-o-s-d-edit-btn" onclick="ratingsOverviewRateSection('parking', 'places');"><?php echo $wrd_edit; ?></button>
            </div>
            <div class="r-o-s-d-bar">
              <div class="r-o-s-d-progress" id="r-o-s-d-progress-places-parking" style='<?php echo "width: ".$ratingPlcSectParkPerc."%;" ?>'></div>
            </div>
          </div>
          <div class="r-o-s-d-block <?php echo $ratingPlcSectAdClass; ?>" id="r-o-s-d-block-places-ad">
            <div class="r-o-s-d-about">
              <p class="r-o-s-d-name"><?php echo $wrd_ad; ?></p>
              <button type="button" class="r-o-s-d-edit-btn" onclick="ratingsOverviewRateSection('ad', 'places');"><?php echo $wrd_edit; ?></button>
            </div>
            <div class="r-o-s-d-bar">
              <div class="r-o-s-d-progress" id="r-o-s-d-progress-places-ad" style='<?php echo "width: ".$ratingPlcSectAdPerc."%;" ?>'></div>
            </div>
          </div>
        </div>
        <div class="ratings-overview-section-details-comments-wrp">
          <div class="ratings-overview-section-details-comment-list" id="ratings-overview-section-details-comment-list-places">
            <?php
              $sqlPlcComments = $link->query("SELECT comment, commentbeid FROM comments WHERE critic='$usrBeId' and beid='$rt_plcBeId' ORDER BY fulldate DESC");
              if ($sqlPlcComments->num_rows > 0) {
                while($rowPlcComm = $sqlPlcComments->fetch_assoc()) {
            ?>
                  <div class="ratings-overview-section-details-comment-row" id="ratings-overview-section-details-comment-row-<?php echo getFrontendId($rowPlcComm['commentbeid']); ?>">
                    <button type="button" value="<?php echo getFrontendId($rowPlcComm['commentbeid']); ?>" onclick="ratingsDeleteComment(this)" class="ratings-overview-section-details-comment-delete-btn"></button>
                    <div class="ratings-overview-section-details-comment-img-wrp">
                      <img src="<?php echo "../".$midImg; ?>" alt="Profile image" class="ratings-overview-section-details-comment-img">
                    </div>
                    <div class="ratings-overview-section-details-comment-txt-wrp">
                      <p class="ratings-overview-section-details-comment-txt"><?php echo nl2br($rowPlcComm['comment']); ?></p>
                    </div>
                  </div>
            <?php
                }
              }
            ?>
          </div>
          <?php
            if ($bnft_add_comment == "good") {
          ?>
              <div class="ratings-overview-section-details-comment-row">
                <div class="ratings-overview-section-details-comment-img-wrp">
                  <img src="<?php echo "../".$midImg; ?>" alt="Profile image" class="ratings-overview-section-details-comment-img">
                </div>
                <div class="ratings-overview-section-details-comment-txt-wrp">
                  <button type="button" class="ratings-overview-section-details-comment-add-btn" onclick="ratingsOverviewRateSection('comment', 'places');"><?php echo $wrd_addComment; ?></button>
                </div>
              </div>
          <?php
            }
          ?>
        </div>
      </div>
    </div>
  </div>
  <div class="ratings-overview-border"></div>
  <div class="ratings-overview-section-wrp">
    <div class="set-up-step-by-step-content-title-wrp">
      <span class="set-up-step-by-step-content-title-details"><a href="<?php echo "../place/?id=".$rt_plcID; ?>" target="_blank"><?php echo $plcNameShrt; ?></a> &bull; <a href="../user/?id=<?php echo $plcHostID; ?>&section=about" target="_blank"><?php echo $plcHostName; ?></a></span>
    </div>
    <div class="ratings-overview-section-rate-btn-wrp" id="ratings-overview-section-rate-btn-wrp-host" <?php echo $hostRatingBtnStyle; ?>>
      <button type="button" class="btn btn-big btn-prim" onclick="ratingsOverviewRateHost();"><?php echo $wrd_rateTheHost; ?></button>
    </div>
    <div class="ratings-overview-section-layout" id="ratings-overview-section-layout-host" <?php echo $hostRatingOverviewStyle; ?>>
      <div class="ratings-overview-section-summary">
        <div class="ratings-overview-section-summary-layout">
          <img alt="" src="../uni/icons/star.svg" class="ratings-overview-section-summary-stars-img">
          <p class="ratings-overview-section-summary-stars-num" id="ratings-overview-section-summary-stars-num-host"><?php echo $criticsRatingSummaryHost; ?></p>
        </div>
      </div>
      <div class="ratings-overview-section-details">
        <div class="ratings-overview-section-details-grid">
          <div class="r-o-s-d-block <?php echo $ratingHstSectLangClass; ?>" id="r-o-s-d-block-users-language">
            <div class="r-o-s-d-about">
              <p class="r-o-s-d-name"><?php echo $wrd_language; ?></p>
              <button type="button" class="r-o-s-d-edit-btn" onclick="ratingsOverviewRateSection('language', 'users');"><?php echo $wrd_edit; ?></button>
            </div>
            <div class="r-o-s-d-bar">
              <div class="r-o-s-d-progress" id="r-o-s-d-progress-users-language" style='<?php echo "width: ".$ratingHstSectLangPerc."%;" ?>'></div>
            </div>
          </div>
          <div class="r-o-s-d-block <?php echo $ratingHstSectCommClass; ?>" id="r-o-s-d-block-users-communication">
            <div class="r-o-s-d-about">
              <p class="r-o-s-d-name"><?php echo $wrd_communication; ?></p>
              <button type="button" class="r-o-s-d-edit-btn" onclick="ratingsOverviewRateSection('communication', 'users');"><?php echo $wrd_edit; ?></button>
            </div>
            <div class="r-o-s-d-bar">
              <div class="r-o-s-d-progress" id="r-o-s-d-progress-users-communication" style='<?php echo "width: ".$ratingHstSectCommPerc."%;" ?>'></div>
            </div>
          </div>
          <div class="r-o-s-d-block <?php echo $ratingHstSectPrsnClass; ?>" id="r-o-s-d-block-users-personality">
            <div class="r-o-s-d-about">
              <p class="r-o-s-d-name"><?php echo $wrd_personality; ?></p>
              <button type="button" class="r-o-s-d-edit-btn" onclick="ratingsOverviewRateSection('personality', 'users');"><?php echo $wrd_edit; ?></button>
            </div>
            <div class="r-o-s-d-bar">
              <div class="r-o-s-d-progress" id="r-o-s-d-progress-users-personality" style='<?php echo "width: ".$ratingHstSectPrsnPerc."%;" ?>'></div>
            </div>
          </div>
        </div>
        <div class="ratings-overview-section-details-comments-wrp">
          <div class="ratings-overview-section-details-comment-list" id="ratings-overview-section-details-comment-list-host">
            <?php
              $sqlUsrComments = $link->query("SELECT comment, commentbeid FROM comments WHERE critic='$usrBeId' and beid='$hostBeID' ORDER BY fulldate DESC");
              if ($sqlUsrComments->num_rows > 0) {
                while($rowUsrComm = $sqlUsrComments->fetch_assoc()) {
            ?>
                  <div class="ratings-overview-section-details-comment-row" id="ratings-overview-section-details-comment-row-<?php echo getFrontendId($rowUsrComm['commentbeid']); ?>">
                    <button type="button" value="<?php echo getFrontendId($rowUsrComm['commentbeid']); ?>" onclick="ratingsDeleteComment(this)" class="ratings-overview-section-details-comment-delete-btn"></button>
                    <div class="ratings-overview-section-details-comment-img-wrp">
                      <img src="<?php echo "../".$midImg; ?>" alt="Profile image" class="ratings-overview-section-details-comment-img">
                    </div>
                    <div class="ratings-overview-section-details-comment-txt-wrp">
                      <p class="ratings-overview-section-details-comment-txt"><?php echo nl2br($rowUsrComm['comment']); ?></p>
                    </div>
                  </div>
            <?php
                }
              }
            ?>
          </div>
          <?php
            if ($bnft_add_comment == "good") {
          ?>
              <div class="ratings-overview-section-details-comment-row">
                <div class="ratings-overview-section-details-comment-img-wrp">
                  <img src="<?php echo "../".$midImg; ?>" alt="Profile image" class="ratings-overview-section-details-comment-img">
                </div>
                <div class="ratings-overview-section-details-comment-txt-wrp">
                  <button type="button" class="ratings-overview-section-details-comment-add-btn" onclick="ratingsOverviewRateSection('comment', 'users');"><?php echo $wrd_addComment; ?></button>
                </div>
              </div>
          <?php
            }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
