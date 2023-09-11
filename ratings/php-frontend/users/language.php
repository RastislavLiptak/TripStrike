<?php
  if ($ratingHstSectLang == "none") {
    $ratingHstSectLangPercSlide = 50;
  } else {
    $ratingHstSectLangPercSlide = $ratingHstSectLang;
  }
?>
<div class="set-up-step-by-step-content set-up-step-by-step-content-users" id="set-up-step-by-step-content-users-language">
  <div class="set-up-step-by-step-content-title-wrp">
    <span class="set-up-step-by-step-content-title-details"><a href="<?php echo "../place/?id=".$rt_plcID; ?>" target="_blank"><?php echo $plcNameShrt; ?></a></span>
    <div class="set-up-step-by-step-content-title-txt">
      <h1 class="set-up-step-by-step-content-title-h1"><?php echo $wrd_howWellDidYourHostSpeakTheLanguageHeUsedToCommunicateWithYou; ?></h1>
    </div>
    <div class="set-up-step-by-step-content-title-txt">
      <h2 class="set-up-step-by-step-content-title-h2"><?php echo $wrd_noteTheLanguagesThatTheHostListsOnIts; ?> <a href="#" target="_blank"><?php echo $wrd_profile6thTense; ?></a></h2>
    </div>
  </div>
  <div class="set-up-step-by-step-content-form-wrp">
    <div class="set-up-step-by-step-content-slider-row">
      <div class="set-up-step-by-step-content-slider-about">
        <p id="set-up-step-by-step-content-slider-about-users-language-progress"><?php echo $ratingHstSectLangPercSlide; ?></p>
      </div>
      <div class="set-up-step-by-step-content-slider-legend">
        <img src="../uni/icons/sad.svg" alt="sad face" class="set-up-step-by-step-content-slider-legend-img set-up-step-by-step-content-slider-legend-img-sad" onclick="setUpStepByStepSliderFncHappySadClick('users-language', 'sad')">
        <img src="../uni/icons/happy.svg" alt="sad face" class="set-up-step-by-step-content-slider-legend-img set-up-step-by-step-content-slider-legend-img-happy" onclick="setUpStepByStepSliderFncHappySadClick('users-language', 'happy')">
      </div>
      <div class="set-up-step-by-step-content-slider-blck" onmousedown="setUpStepByStepSliderFncMouseDown('users-language', event)">
        <div class="set-up-step-by-step-content-slider-bar" id="set-up-step-by-step-content-slider-bar-users-language">
          <div class="set-up-step-by-step-content-slider-progress" id="set-up-step-by-step-content-slider-progress-users-language" style="width: <?php echo $ratingHstSectLangPercSlide.'%'; ?>;">
            <div class="set-up-step-by-step-content-slider-circle"></div>
          </div>
          <div class="set-up-step-by-step-content-slider-checkpoints-wrp">
            <div class="set-up-step-by-step-content-slider-checkpoint"></div>
            <div class="set-up-step-by-step-content-slider-checkpoint"></div>
            <div class="set-up-step-by-step-content-slider-checkpoint"></div>
            <div class="set-up-step-by-step-content-slider-checkpoint"></div>
            <div class="set-up-step-by-step-content-slider-checkpoint"></div>
          </div>
        </div>
      </div>
      <div class="set-up-step-by-step-content-slider-txt-wrp">
        <?php
          $ratingHstSectLangSliderTxt_1 = "";
          $ratingHstSectLangSliderTxt_2 = "";
          $ratingHstSectLangSliderTxt_3 = "";
          $ratingHstSectLangSliderTxt_4 = "";
          $ratingHstSectLangSliderTxt_5 = "";
          if ($ratingHstSectLangPercSlide == 0) {
            $ratingHstSectLangSliderTxt_1 = "set-up-step-by-step-content-slider-txt-selected";
          } else if ($ratingHstSectLangPercSlide == 25) {
            $ratingHstSectLangSliderTxt_2 = "set-up-step-by-step-content-slider-txt-selected";
          } else if ($ratingHstSectLangPercSlide == 50) {
            $ratingHstSectLangSliderTxt_3 = "set-up-step-by-step-content-slider-txt-selected";
          } else if ($ratingHstSectLangPercSlide == 75) {
            $ratingHstSectLangSliderTxt_4 = "set-up-step-by-step-content-slider-txt-selected";
          } else if ($ratingHstSectLangPercSlide == 100) {
            $ratingHstSectLangSliderTxt_5 = "set-up-step-by-step-content-slider-txt-selected";
          }
        ?>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-users-language <?php echo $ratingHstSectLangSliderTxt_1; ?>"><?php echo $wrd_bad; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-users-language <?php echo $ratingHstSectLangSliderTxt_2; ?>"><?php echo $wrd_guiteBad; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-users-language <?php echo $ratingHstSectLangSliderTxt_3; ?>"><?php echo $wrd_good; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-users-language <?php echo $ratingHstSectLangSliderTxt_4; ?>"><?php echo $wrd_veryGood; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-users-language <?php echo $ratingHstSectLangSliderTxt_5; ?>"><?php echo $wrd_great; ?></span>
      </div>
    </div>
  </div>
  <div class="set-up-step-by-step-content-error-wrp" id="set-up-step-by-step-content-error-wrp-users-language">
    <p class="set-up-step-by-step-content-error-txt" id="set-up-step-by-step-content-error-txt-users-language"></p>
  </div>
  <div class="set-up-step-by-step-content-footer-wrp">
    <div class="set-up-step-by-step-content-footer-btn-wrp set-up-step-by-step-content-footer-btn-wrp-left">
      <button class="btn btn-mid btn-sec set-up-step-by-step-content-footer-btn set-up-step-by-step-content-footer-back-btn" onclick="ratingsContentBack(this.value)"><?php echo $wrd_back; ?></button>
    </div>
    <div class="set-up-step-by-step-content-footer-counter">
      <p class="set-up-step-by-step-content-footer-counter-txt set-up-step-by-step-content-footer-counter-txt-users">0 / 0</p>
    </div>
    <div class="set-up-step-by-step-content-footer-btn-wrp set-up-step-by-step-content-footer-btn-wrp-right">
      <button class="btn btn-mid btn-prim set-up-step-by-step-content-footer-btn" id="set-up-step-by-step-content-footer-continue-btn-users-language" onclick="ratingsContentContinueSave('users', 'language');"><?php echo $wrd_continue; ?></button>
    </div>
  </div>
</div>
