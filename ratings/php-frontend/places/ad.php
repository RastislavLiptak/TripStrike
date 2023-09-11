<?php
  if ($ratingPlcSectAd == "none") {
    $ratingPlcSectAdPercSlide = 50;
  } else {
    $ratingPlcSectAdPercSlide = $ratingPlcSectAd;
  }

?>
<div class="set-up-step-by-step-content set-up-step-by-step-content-places" id="set-up-step-by-step-content-places-ad">
  <div class="set-up-step-by-step-content-title-wrp">
    <span class="set-up-step-by-step-content-title-details"><a href="<?php echo "../place/?id=".$rt_plcID; ?>" target="_blank"><?php echo $plcNameShrt; ?></a></span>
    <div class="set-up-step-by-step-content-title-txt">
      <h1 class="set-up-step-by-step-content-title-h1"><?php echo $wrd_howWellDoesRepresent; ?> <a href="<?php echo "../place/?id=".$rt_plcID; ?>" target="_blank"><?php echo $wrd_theAdOnThisChatPageRepresentCottage." ".$plcName; ?></a>?</h1>
    </div>
  </div>
  <div class="set-up-step-by-step-content-form-wrp">
    <div class="set-up-step-by-step-content-slider-row">
      <div class="set-up-step-by-step-content-slider-about">
        <p id="set-up-step-by-step-content-slider-about-places-ad-progress"><?php echo $ratingPlcSectAdPercSlide; ?></p>
      </div>
      <div class="set-up-step-by-step-content-slider-legend">
        <img src="../uni/icons/sad.svg" alt="sad face" class="set-up-step-by-step-content-slider-legend-img set-up-step-by-step-content-slider-legend-img-sad" onclick="setUpStepByStepSliderFncHappySadClick('places-ad', 'sad')">
        <img src="../uni/icons/happy.svg" alt="sad face" class="set-up-step-by-step-content-slider-legend-img set-up-step-by-step-content-slider-legend-img-happy" onclick="setUpStepByStepSliderFncHappySadClick('places-ad', 'happy')">
      </div>
      <div class="set-up-step-by-step-content-slider-blck" onmousedown="setUpStepByStepSliderFncMouseDown('places-ad', event)">
        <div class="set-up-step-by-step-content-slider-bar" id="set-up-step-by-step-content-slider-bar-places-ad">
          <div class="set-up-step-by-step-content-slider-progress" id="set-up-step-by-step-content-slider-progress-places-ad" style="width: <?php echo $ratingPlcSectAdPercSlide.'%'; ?>;">
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
          $ratingPlcSectAdSliderTxt_1 = "";
          $ratingPlcSectAdSliderTxt_2 = "";
          $ratingPlcSectAdSliderTxt_3 = "";
          $ratingPlcSectAdSliderTxt_4 = "";
          $ratingPlcSectAdSliderTxt_5 = "";
          if ($ratingPlcSectAdPercSlide == 0) {
            $ratingPlcSectAdSliderTxt_1 = "set-up-step-by-step-content-slider-txt-selected";
          } else if ($ratingPlcSectAdPercSlide == 25) {
            $ratingPlcSectAdSliderTxt_2 = "set-up-step-by-step-content-slider-txt-selected";
          } else if ($ratingPlcSectAdPercSlide == 50) {
            $ratingPlcSectAdSliderTxt_3 = "set-up-step-by-step-content-slider-txt-selected";
          } else if ($ratingPlcSectAdPercSlide == 75) {
            $ratingPlcSectAdSliderTxt_4 = "set-up-step-by-step-content-slider-txt-selected";
          } else if ($ratingPlcSectAdPercSlide == 100) {
            $ratingPlcSectAdSliderTxt_5 = "set-up-step-by-step-content-slider-txt-selected";
          }
        ?>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-places-ad <?php echo $ratingPlcSectAdSliderTxt_1; ?>"><?php echo $wrd_bad; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-places-ad <?php echo $ratingPlcSectAdSliderTxt_2; ?>"><?php echo $wrd_guiteBad; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-places-ad <?php echo $ratingPlcSectAdSliderTxt_3; ?>"><?php echo $wrd_good; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-places-ad <?php echo $ratingPlcSectAdSliderTxt_4; ?>"><?php echo $wrd_veryGood; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-places-ad <?php echo $ratingPlcSectAdSliderTxt_5; ?>"><?php echo $wrd_great; ?></span>
      </div>
    </div>
  </div>
  <div class="set-up-step-by-step-content-error-wrp" id="set-up-step-by-step-content-error-wrp-places-ad">
    <p class="set-up-step-by-step-content-error-txt" id="set-up-step-by-step-content-error-txt-places-ad"></p>
  </div>
  <div class="set-up-step-by-step-content-footer-wrp">
    <div class="set-up-step-by-step-content-footer-btn-wrp set-up-step-by-step-content-footer-btn-wrp-left">
      <button class="btn btn-mid btn-sec set-up-step-by-step-content-footer-btn set-up-step-by-step-content-footer-back-btn" onclick="ratingsContentBack(this.value)"><?php echo $wrd_back; ?></button>
    </div>
    <div class="set-up-step-by-step-content-footer-counter">
      <p class="set-up-step-by-step-content-footer-counter-txt set-up-step-by-step-content-footer-counter-txt-places">0 / 0</p>
    </div>
    <div class="set-up-step-by-step-content-footer-btn-wrp set-up-step-by-step-content-footer-btn-wrp-right">
      <button class="btn btn-mid btn-prim set-up-step-by-step-content-footer-btn" id="set-up-step-by-step-content-footer-continue-btn-places-ad" onclick="ratingsContentContinueSave('places', 'ad');"><?php echo $wrd_continue; ?></button>
    </div>
  </div>
</div>
