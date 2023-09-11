<div id="user-about">
  <div id="u-a-blck-links" class="u-a-blocks">
    <p class="u-a-blocks-title-1"><?php echo $wrd_topPicks; ?></p>
    <div id="u-a-links-list-wrp">
      <div id="u-a-links-list" class="u-c-grid-set">
        <?php
          for ($i=0; $i < 6; $i++) {
        ?>
          <div class="fake-link-img-blck">
            <div class="fake-link-img-wrp">
              <div class="fake-link-img-bck-wrp">
              </div>
              <div class="fake-link-img-txt-wrp">
              </div>
            </div>
          </div>
        <?php
          }
        ?>
      </div>
      <div id="u-a-links-loader"></div>
      <div id="u-a-links-errors-wrp">
        <div class="u-a-links-errors-blck" id="u-a-links-errors-blck-1">
          <p class="u-a-links-errors-txt" id="u-a-links-errors-txt-no-cottage"><?php echo $accfirstname." ".$acclastname." ".$wrd_doesNotOfferAnyCottages;?></p>
        </div>
        <div class="u-a-links-errors-blck" id="u-a-links-errors-blck-2">
          <div class="u-a-links-errors-btn-refresh-btn">
            <button class="btn btn-mid btn-prim" onclick="loadCottageData()"><?php echo $wrd_reset;?></button>
          </div>
          <p class="u-a-links-errors-txt-refresh"><?php echo $wrd_loadCottagesScriptError;?></p>
        </div>
        <div class="u-a-links-errors-blck" id="u-a-links-errors-blck-3">
          <div class="u-a-links-errors-btn-refresh-btn">
            <button class="btn btn-mid btn-prim" onclick="location.reload()"><?php echo $wrd_refresh;?></button>
          </div>
          <p class="u-a-links-errors-txt-refresh"><?php echo $wrd_userIDNotExist;?></p>
        </div>
      </div>
    </div>
  </div>
  <div id="u-a-blck-details" class="u-a-blocks">
    <div class="u-a-details-block" id="u-a-details-block-desc">
      <div id="u-a-details-block-desc-icon-wrp">
        <div id="u-a-details-block-desc-icon"></div>
      </div>
      <div id="u-a-details-block-desc-txt-wrp">
        <div class="u-a-details-block-desc-txt-container">
          <p class="u-a-details-block-desc-txt" id="u-a-details-block-desc-txt-sign-up-date"><?php echo $wrd_joined." ".$accJoinedD.". ".$accJoinedM.". ".$accJoinedY; ?></p>
        </div>
        <div id="u-a-details-block-desc-description-wrp">
          <div class="u-a-details-block-desc-txt-container">
            <?php
              if (str_replace(" ","", trim(preg_replace('/\s\s+/', "", $accdesc))) == "") {
                $accdesc = "...";
              }
            ?>
            <p class="u-a-details-block-desc-txt" id="u-a-details-block-desc-txt-description"><?php echo nl2br(convertLinkInString($accdesc)); ?></p>
          </div>
        </div>
        <div class="u-a-details-block-desc-txt-container">
          <?php
            if ($acclang_array_length < 1) {
              $uaLangClass = "";
              $uaMultiLangClass = "";
            } else if ($acclang_array_length > 1) {
              $uaLangClass = "";
              $uaMultiLangClass = "u-a-details-block-desc-txt-languages-priority";
            } else {
              $uaLangClass = "u-a-details-block-desc-txt-languages-priority";
              $uaMultiLangClass = "";
            }
          ?>
          <p class="u-a-details-block-desc-txt u-a-details-block-desc-txt-languages <?php echo $uaLangClass; ?>" id="u-a-details-block-desc-txt-languages"><?php echo $wrd_speaks." ".$acclang_string; ?></p>
          <p class="u-a-details-block-desc-txt u-a-details-block-desc-txt-languages <?php echo $uaMultiLangClass; ?>" id="u-a-details-block-desc-txt-multi-languages"><?php echo $wrd_speaksMulti." ".$acclang_string; ?></p>
        </div>
      </div>
    </div>
    <div class="u-a-details-block u-a-block-style-1" id="u-a-details-block-rating">
      <div class="u-a-block-style-1-header">
        <p class="u-a-blocks-title-2"><?php echo $wrd_ratingCapital; ?></p>
      </div>
      <div class="u-a-block-style-1-content" id="u-a-details-block-rating-content">
        <?php
          $sqlSummRating = $link->query("SELECT percentage FROM ratingsummary WHERE beid='$accBeId'");
          if ($sqlSummRating->num_rows > 0) {
            $accSummStars = str_replace('.',',',round($sqlSummRating->fetch_assoc()["percentage"] * 5 / 100, 2));
        ?>
          <div id="u-a-details-block-rating-stars-wrp">
            <div id="u-a-d-b-r-s-summary-wrp">
              <div id="u-a-d-b-r-s-summary-img-wrp">
                <img alt="" src="../uni/icons/star.svg" id="u-a-d-b-r-s-summary-img">
              </div>
              <p id="u-a-d-b-r-s-summary-num"><?php echo $accSummStars; ?></p>
            </div>
            <div id="u-a-d-b-r-s-sect-wrp">
              <div id="u-a-d-b-r-s-sect-grid">

                <?php
                  $accSectData = [];
                  $sqlSections = $link->query("SELECT section, percentage FROM ratingsectionsummary WHERE beid='$accBeId'");
                  if ($sqlSections->num_rows > 0) {
                    while($sec = $sqlSections->fetch_assoc()) {
                      if ($sec['section'] == "lang") {
                        $secTxt = $wrd_language;
                        $secIcn = "language2.svg";
                      } else if ($sec['section'] == "comm") {
                        $secTxt = $wrd_communication;
                        $secIcn = "communication.svg";
                      } else if ($sec['section'] == "prsn") {
                        $secTxt = $wrd_personality;
                        $secIcn = "user2.svg";
                      } else {
                        $secTxt = $wrd_unknown;
                        $secIcn = "question3.svg";
                      }
                      $secStars = str_replace('.',',',round($sec['percentage'] * 5 / 100, 2));
                      array_push($accSectData, [
                        "txt" => $secTxt,
                        "icn" => $secIcn,
                        "percent" => $sec['percentage'],
                        "num" => $secStars
                      ]);
                    }
                  }
                  for ($i=0; $i < sizeof($accSectData); $i++) {
                ?>
                  <div class="u-a-rating-section-wrp">
                    <div class="u-a-rating-section-identify-wrp">
                      <div class="u-a-rating-section-img-wrp">
                        <img alt="" src="../uni/icons/<?php echo $accSectData[$i]['icn']; ?>" class="u-a-rating-section-img">
                      </div>
                      <div class="u-a-rating-section-about-wrp">
                        <p class="u-a-rating-section-title"><?php echo $accSectData[$i]['txt']; ?></p>
                        <div class="u-a-rating-section-bar-wrp">
                          <div class="u-a-rating-section-bar">
                            <div class="u-a-rating-section-progress" style="width: <?php echo $accSectData[$i]['percent'].'%'; ?>"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="u-a-rating-section-stars-wrp">
                      <p class="u-a-rating-section-stars"><?php echo $accSectData[$i]['num']; ?></p>
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
          <div id="u-a-details-block-rating-msg-wrp">
            <div class="u-a-details-block-rating-msg">
              <img alt="" src="../uni/icons/star4.svg" class="u-a-details-block-rating-msg-img">
              <?php
                if ($usrBeId == $accBeId) {
                  $profileNotRatedYet = $wrd_willSeeRatingProfile;
                } else {
                  $profileNotRatedYet = $wrd_profileNotRated;
                }
              ?>
              <p class="u-a-details-block-rating-msg-txt"><?php echo $profileNotRatedYet; ?></p>
            </div>
          </div>
        <?php
          }
        ?>
      </div>
    </div>
  </div>
  <?php
    $sqlComments = $link->query("SELECT * FROM comments WHERE beid='$accBeId'");
    if ($sqlComments->num_rows > 0) {
  ?>
      <div id="u-a-blck-ratings" class="u-a-blocks">
        <p class="u-a-blocks-title-1"><?php echo $wrd_usersRating; ?></p>
        <div id="u-a-r-grid-wrp">
          <div id="u-a-r-grid">
          </div>
          <div id="u-a-r-load-more-wrp">
            <button type="button" id="u-a-r-load-more-btn" onclick="loadCommentsData()">
              <div id="u-a-r-load-more-btn-icon"></div>
              <p id="u-a-r-load-more-btn-txt"><?php echo $wrd_loadMore; ?></p>
            </button>
          </div>
          <div id="u-a-r-error-wrp">
            <div id="u-a-r-error-blck">
              <p id="u-a-r-error-text"><?php echo $wrd_errorOccurredReloadOrReturnLater; ?></p>
              <div id="u-a-r-error-code-wrp">
                <p id="u-a-r-error-code-title"><?php echo $wrd_errorCode.":"; ?></p>
                <p id="u-a-r-error-code-text"></p>
              </div>
            </div>
          </div>
        </div>
      </div>
  <?php
    }
  ?>
</div>
