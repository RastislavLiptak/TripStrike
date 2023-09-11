<?php
  if ($ratingHstSectComm == "none") {
    $ratingHstSectCommPercSlide = 50;
  } else {
    $ratingHstSectCommPercSlide = $ratingHstSectComm;
  }
?>
<div class="set-up-step-by-step-content set-up-step-by-step-content-users" id="set-up-step-by-step-content-users-communication">
  <div class="set-up-step-by-step-content-title-wrp">
    <span class="set-up-step-by-step-content-title-details"><a href="<?php echo "../place/?id=".$rt_plcID; ?>" target="_blank"><?php echo $plcNameShrt; ?></a></span>
    <div class="set-up-step-by-step-content-title-txt">
      <h1 class="set-up-step-by-step-content-title-h1"><?php echo $wrd_howWasTheCommunicationWithTheHost; ?></h1>
    </div>
    <div class="set-up-step-by-step-content-title-txt">
      <h2 class="set-up-step-by-step-content-title-h2"><?php echo $wrd_wasItPossibleToSontactHimHerDidHeSheAnswerQuestionsAndWasHeSheWillingToHelpYouInCaseOfProblem; ?></h2>
    </div>
  </div>
  <div class="set-up-step-by-step-content-form-wrp">
    <div class="set-up-step-by-step-content-slider-row">
      <div class="set-up-step-by-step-content-slider-about">
        <p id="set-up-step-by-step-content-slider-about-users-communication-progress"><?php echo $ratingHstSectCommPercSlide; ?></p>
      </div>
      <div class="set-up-step-by-step-content-slider-legend">
        <img src="../uni/icons/sad.svg" alt="sad face" class="set-up-step-by-step-content-slider-legend-img set-up-step-by-step-content-slider-legend-img-sad" onclick="setUpStepByStepSliderFncHappySadClick('users-communication', 'sad')">
        <img src="../uni/icons/happy.svg" alt="sad face" class="set-up-step-by-step-content-slider-legend-img set-up-step-by-step-content-slider-legend-img-happy" onclick="setUpStepByStepSliderFncHappySadClick('users-communication', 'happy')">
      </div>
      <div class="set-up-step-by-step-content-slider-blck" onmousedown="setUpStepByStepSliderFncMouseDown('users-communication', event)">
        <div class="set-up-step-by-step-content-slider-bar" id="set-up-step-by-step-content-slider-bar-users-communication">
          <div class="set-up-step-by-step-content-slider-progress" id="set-up-step-by-step-content-slider-progress-users-communication" style="width: <?php echo $ratingHstSectCommPercSlide.'%'; ?>;">
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
          $ratingHstSectCommSliderTxt_1 = "";
          $ratingHstSectCommSliderTxt_2 = "";
          $ratingHstSectCommSliderTxt_3 = "";
          $ratingHstSectCommSliderTxt_4 = "";
          $ratingHstSectCommSliderTxt_5 = "";
          if ($ratingHstSectCommPercSlide == 0) {
            $ratingHstSectCommSliderTxt_1 = "set-up-step-by-step-content-slider-txt-selected";
          } else if ($ratingHstSectCommPercSlide == 25) {
            $ratingHstSectCommSliderTxt_2 = "set-up-step-by-step-content-slider-txt-selected";
          } else if ($ratingHstSectCommPercSlide == 50) {
            $ratingHstSectCommSliderTxt_3 = "set-up-step-by-step-content-slider-txt-selected";
          } else if ($ratingHstSectCommPercSlide == 75) {
            $ratingHstSectCommSliderTxt_4 = "set-up-step-by-step-content-slider-txt-selected";
          } else if ($ratingHstSectCommPercSlide == 100) {
            $ratingHstSectCommSliderTxt_5 = "set-up-step-by-step-content-slider-txt-selected";
          }
        ?>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-users-communication <?php echo $ratingHstSectCommSliderTxt_1; ?>"><?php echo $wrd_bad; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-users-communication <?php echo $ratingHstSectCommSliderTxt_2; ?>"><?php echo $wrd_guiteBad; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-users-communication <?php echo $ratingHstSectCommSliderTxt_3; ?>"><?php echo $wrd_good; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-users-communication <?php echo $ratingHstSectCommSliderTxt_4; ?>"><?php echo $wrd_veryGood; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-users-communication <?php echo $ratingHstSectCommSliderTxt_5; ?>"><?php echo $wrd_great; ?></span>
      </div>
    </div>
  </div>
  <div class="set-up-step-by-step-content-error-wrp" id="set-up-step-by-step-content-error-wrp-users-communication">
    <p class="set-up-step-by-step-content-error-txt" id="set-up-step-by-step-content-error-txt-users-communication"></p>
  </div>
  <div class="set-up-step-by-step-content-footer-wrp">
    <div class="set-up-step-by-step-content-footer-btn-wrp set-up-step-by-step-content-footer-btn-wrp-left">
      <button class="btn btn-mid btn-sec set-up-step-by-step-content-footer-btn set-up-step-by-step-content-footer-back-btn" onclick="ratingsContentBack(this.value)"><?php echo $wrd_back; ?></button>
    </div>
    <div class="set-up-step-by-step-content-footer-counter">
      <p class="set-up-step-by-step-content-footer-counter-txt set-up-step-by-step-content-footer-counter-txt-users">0 / 0</p>
    </div>
    <div class="set-up-step-by-step-content-footer-btn-wrp set-up-step-by-step-content-footer-btn-wrp-right">
      <button class="btn btn-mid btn-prim set-up-step-by-step-content-footer-btn" id="set-up-step-by-step-content-footer-continue-btn-users-communication" onclick="ratingsContentContinueSave('users', 'communication');"><?php echo $wrd_continue; ?></button>
    </div>
  </div>
</div>
