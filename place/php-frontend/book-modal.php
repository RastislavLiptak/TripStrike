<?php
  if (isset($_GET['instantBooking'])) {
    $bookModalCoverStyle = "display: table; background-color: rgba(0, 0, 0, 0.65);";
    $bookModalBlckStyle = "display: table; opacity: 1;";
  } else {
    $bookModalCoverStyle = "";
    $bookModalBlckStyle = "";
  }
?>
<div class="modal-cover" id="modal-cover-book" style="<?php echo $bookModalCoverStyle; ?>">
  <div class="modal-block" id="modal-cover-book-blck" style="<?php echo $bookModalBlckStyle; ?>">
    <button class="cancel-btn" onclick="bookModal('hide')"></button>
    <div id="book-wrp">
      <div id="book-header">
        <p id="book-title"><?php echo $wrd_chooseDate; ?></p>
      </div>
      <div id="book-content">
        <div id="book-calendar-content">
          <div id="book-calendar-default-loader-wrp">
            <div class="cal-main-loader"></div>
          </div>
          <div id="book-calendar-wrp"></div>
          <?php
            if ($plcOperation == "summer" || $plcOperation == "winter") {
          ?>
              <div id="book-calendar-operation">
                <p id="book-calendar-operation-txt">
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
        <div id="book-details-wrp">
          <div class="book-details-row" id="r-d-guests-row">
            <p id="r-d-guests-txt"><?php echo $wrd_guestNum; ?></p>
            <div id="r-d-guests-num-wrp">
              <button type="button" id="r-d-guests-btn" value="1" onclick="bookGuestsNumDropdown('toggle')">
                <p id="r-d-guests-btn-txt">1</p>
                <div id="r-d-guests-btn-icn"></div>
              </button>
              <div id="r-d-guests-dropdown">
                <?php
                  for ($g = 1; $g <= $plcGuests; $g++) {
                ?>
                  <button type="button" class="r-d-guests-dropdown-select" onclick="bookGuestsNumSelect(<?php echo $g; ?>); bookTermsCalc();"><?php echo $g; ?></button>
                <?php
                    if ($g != $plcGuests) {
                ?>
                      <div class="r-d-guests-dropdown-select-border"></div>
                <?php
                    }
                  }
                ?>
              </div>
            </div>
          </div>
          <div class="book-details-row r-d-from-to-row" id="r-d-from-to-row-1">
            <p class="r-d-from-to-txt"><?php echo $wrd_from; ?></p>
            <div class="r-d-from-to-arrow"></div>
            <p class="r-d-from-to-txt"><?php echo $wrd_to; ?></p>
          </div>
          <div class="book-details-row r-d-from-to-row" id="r-d-from-to-row-2">
            <p class="r-d-from-to-txt" id="r-d-from-to-txt-2-from"></p>
            <div class="r-d-from-to-arrow"></div>
            <p class="r-d-from-to-txt"><?php echo $wrd_to; ?></p>
          </div>
          <div class="book-details-row r-d-from-to-row" id="r-d-from-to-row-3">
            <p class="r-d-from-to-txt" id="r-d-from-to-txt-3-from"></p>
            <div class="r-d-from-to-arrow"></div>
            <p class="r-d-from-to-txt" id="r-d-from-to-txt-3-to"></p>
          </div>
          <div class="res$plcCurrency" id="r-d-day-calc-wrp">
            <?php
              if ($plcPriceWork == $plcPriceWeek) {
            ?>
              <div class="book-details-row r-d-day-calc-row">
                <p class="r-d-times" id="r-d-times-all-days">0x</p>
                <p class="r-d-price"><?php echo addCurrency($plcCurrency, $plcPriceWork); ?></p>
              </div>
            <?php
              } else {
            ?>
              <div class="book-details-row r-d-day-calc-row">
                <p class="r-d-times" id="r-d-times-work">0x</p>
                <p class="r-d-price"><?php echo addCurrency($plcCurrency, $plcPriceWork); ?></p>
              </div>
              <div class="book-details-row r-d-day-calc-row">
                <p class="r-d-times" id="r-d-times-week">0x</p>
                <p class="r-d-price"><?php echo addCurrency($plcCurrency, $plcPriceWeek); ?></p>
              </div>
            <?php
              }
            ?>
          </div>
          <div class="book-details-row r-d-day-calc-row">
            <p id="r-d-total-txt"><?php echo $wrd_total; ?></p>
            <p id="r-d-total-price"><?php echo addCurrency($plcCurrency, 0)?></p>
          </div>
        </div>
      </div>
      <div id="book-footer">
        <button id="book-footer-btn" class="btn btn-big btn-prim" onclick="bookSummaryModal('show')"><?php echo $wrd_continue; ?></button>
      </div>
    </div>
  </div>
</div>
<div class="modal-cover" id="modal-cover-book-summary">
  <div class="modal-block" id="modal-cover-book-summary-blck">
    <button class="cancel-btn" id="book-summary-cancel-btn" onclick="bookSummaryModal('hide')"></button>
    <div id="book-wrp">
      <div id="book-summary-content">
        <p class="book-modal-title"><?php echo $wrd_summary; ?></p>
        <div id="book-summary-data-scroll">
          <div id="book-summary-data-wrp">
            <div class="book-summary-line" id="book-summary-line-less-than-48-hours">
              <p id="book-summary-line-less-than-48-hours-txt"><?php echo $wrd_theDateOfTheFirstDayOfBookingIsLessThan48HoursThereforeAfterClickingOnTheBindingBookingButtonContactTheHostByPhoneToConfirmYourStay; ?></p>
            </div>
            <div class="book-summary-line">
              <div class="book-summary-line-about">
                <p class="book-summary-line-title"><?php echo $wrd_name; ?></p>
                <textarea class="book-summary-line-textarea" id="book-summary-line-textarea-name" oninput="bookSummaryTextarea(this)" onkeyup="bookSummaryTextarea(this)" placeholder="<?php echo $wrd_fullname; ?>"></textarea>
              </div>
              <div class="book-summary-line-error-wrp">
                <p class="book-summary-line-error" id="book-summary-line-error-name-1"><?php echo $wrd_fillInput; ?></p>
                <p class="book-summary-line-error" id="book-summary-line-error-name-2"><?php echo $wrd_realData; ?></p>
              </div>
            </div>
            <div class="book-summary-line">
              <div class="book-summary-line-about">
                <p class="book-summary-line-title"><?php echo $wrd_email; ?></p>
                <textarea class="book-summary-line-textarea" id="book-summary-line-textarea-email" oninput="bookSummaryTextarea(this)" onkeyup="bookSummaryTextarea(this)" placeholder="email@email.com"></textarea>
              </div>
              <div class="book-summary-line-error-wrp">
                <p class="book-summary-line-error" id="book-summary-line-error-email-1"><?php echo $wrd_fillInput; ?></p>
                <p class="book-summary-line-error" id="book-summary-line-error-email-2"><?php echo $wrd_realData; ?></p>
              </div>
            </div>
            <div class="book-summary-line">
              <div class="book-summary-line-about">
                <p class="book-summary-line-title"><?php echo $wrd_phoneNumber; ?></p>
                <textarea class="book-summary-line-textarea" id="book-summary-line-textarea-phone" oninput="bookSummaryTextarea(this)" onkeyup="bookSummaryTextarea(this)" placeholder="<?php echo '+'.$wrd_code.' 000 000 000'; ?>"></textarea>
              </div>
              <div class="book-summary-line-error-wrp">
                <p class="book-summary-line-error" id="book-summary-line-error-phone-1"><?php echo $wrd_fillInput; ?></p>
                <p class="book-summary-line-error" id="book-summary-line-error-phone-2"><?php echo $wrd_realData; ?></p>
              </div>
            </div>
            <div class="book-summary-line">
              <div class="book-summary-line-about">
                <p class="book-summary-line-title"><?php echo $wrd_guestNum; ?></p>
                <input type="number" class="book-summary-line-textarea" id="book-summary-line-textarea-guests" min="1" max="<?php echo $plcGuests; ?>" oninput="bookSummaryTextarea(this); bookSummaryNumOfGuestsUpdate(this)" onkeyup="bookSummaryTextarea(this); bookSummaryNumOfGuestsUpdate(this)">
              </div>
              <div class="book-summary-line-error-wrp">
                <p class="book-summary-line-error" id="book-summary-line-error-guests-1"><?php echo $wrd_fillInput; ?></p>
                <p class="book-summary-line-error" id="book-summary-line-error-guests-2"><?php echo $wrd_realData; ?></p>
                <p class="book-summary-line-error" id="book-summary-line-error-guests-3"><?php echo $wrd_numberGuestsNotMatchRange; ?></p>
              </div>
            </div>
            <div class="book-summary-line">
              <div class="book-summary-line-about">
                <p class="book-summary-line-title"><?php echo $wrd_from; ?></p>
                <div class="book-summary-line-details-wrp book-summary-line-details-wrp-column">
                  <p class="book-summary-line-details-txt" id="book-summary-from"></p>
                  <div class="book-summary-line-details-txt-more-wrp">
                    <div class="book-summary-line-details-txt-more-btn-layout">
                      <p class="book-summary-line-details-txt-more-price-increase" id="book-summary-line-details-txt-more-price-increase-from-work"><?php echo "(".$wrd_surcharge." ".addCurrency($plcCurrency, $plcPriceWork).")"; ?></p>
                      <p class="book-summary-line-details-txt-more-price-increase" id="book-summary-line-details-txt-more-price-increase-from-week"><?php echo "(".$wrd_surcharge." ".addCurrency($plcCurrency, $plcPriceWeek).")"; ?></p>
                      <button type="button" class="book-summary-line-details-txt-more-btn" id="book-summary-line-details-txt-more-btn-from" value="show" onclick="bookSummaryDetailsMoreDropdown(this.value, this, 'book-summary-line-details-txt-more-dropdown-wrp-from')">
                        <p class="book-summary-line-details-txt-more-btn-txt b-s-l-d-t-m-b-t-show" id="book-summary-line-details-txt-more-btn-txt-from-time"><?php echo $wrd_from." 14:00"; ?></p>
                        <p class="book-summary-line-details-txt-more-btn-txt" id="book-summary-line-details-txt-more-btn-txt-from-whole-day"><?php echo $wrd_theWholeDay; ?></p>
                        <div class="book-summary-line-details-txt-more-btn-arrow" id="book-summary-line-details-txt-more-btn-arrow-from"></div>
                      </button>
                    </div>
                    <div class="book-summary-line-details-txt-more-dropdown-wrp" id="book-summary-line-details-txt-more-dropdown-wrp-from">
                      <label class="book-summary-line-details-txt-more-dropdown-radio-container"><?php echo $wrd_from." 14:00"; ?>
                        <input type="radio" checked="checked" name="book-summary-line-details-txt-more-dropdown-radio-from" id="book-summary-line-details-txt-more-dropdown-radio-from-time" onchange="bookSummaryDetailsMoreRadioOnchange('from', 1)">
                        <span class="book-summary-line-details-txt-more-dropdown-radio-checkmark"></span>
                      </label>
                      <label class="book-summary-line-details-txt-more-dropdown-radio-container"><?php echo $wrd_theWholeDay; ?>
                        <input type="radio" name="book-summary-line-details-txt-more-dropdown-radio-from" id="book-summary-line-details-txt-more-dropdown-radio-from-whole-day"  onchange="bookSummaryDetailsMoreRadioOnchange('from', 2)">
                        <span class="book-summary-line-details-txt-more-dropdown-radio-checkmark"></span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="book-summary-line">
              <div class="book-summary-line-about">
                <p class="book-summary-line-title"><?php echo $wrd_to; ?></p>
                <div class="book-summary-line-details-wrp book-summary-line-details-wrp-column">
                  <p class="book-summary-line-details-txt" id="book-summary-to"></p>
                  <div class="book-summary-line-details-txt-more-wrp">
                    <div class="book-summary-line-details-txt-more-btn-layout">
                      <p class="book-summary-line-details-txt-more-price-increase" id="book-summary-line-details-txt-more-price-increase-to-work"><?php echo "(".$wrd_surcharge." ".addCurrency($plcCurrency, $plcPriceWork).")"; ?></p>
                      <p class="book-summary-line-details-txt-more-price-increase" id="book-summary-line-details-txt-more-price-increase-to-week"><?php echo "(".$wrd_surcharge." ".addCurrency($plcCurrency, $plcPriceWeek).")"; ?></p>
                      <button type="button" class="book-summary-line-details-txt-more-btn" id="book-summary-line-details-txt-more-btn-to" value="show" onclick="bookSummaryDetailsMoreDropdown(this.value, this, 'book-summary-line-details-txt-more-dropdown-wrp-to')">
                        <p class="book-summary-line-details-txt-more-btn-txt b-s-l-d-t-m-b-t-show" id="book-summary-line-details-txt-more-btn-txt-to-time"><?php echo $wrd_to." 11:00"; ?></p>
                        <p class="book-summary-line-details-txt-more-btn-txt" id="book-summary-line-details-txt-more-btn-txt-to-whole-day"><?php echo $wrd_theWholeDay; ?></p>
                        <div class="book-summary-line-details-txt-more-btn-arrow" id="book-summary-line-details-txt-more-btn-arrow-to"></div>
                      </button>
                    </div>
                    <div class="book-summary-line-details-txt-more-dropdown-wrp" id="book-summary-line-details-txt-more-dropdown-wrp-to">
                      <label class="book-summary-line-details-txt-more-dropdown-radio-container"><?php echo $wrd_to." 11:00"; ?>
                        <input type="radio" checked="checked" name="book-summary-line-details-txt-more-dropdown-radio-to" id="book-summary-line-details-txt-more-dropdown-radio-to-time" onchange="bookSummaryDetailsMoreRadioOnchange('to', 1)">
                        <span class="book-summary-line-details-txt-more-dropdown-radio-checkmark"></span>
                      </label>
                      <label class="book-summary-line-details-txt-more-dropdown-radio-container"><?php echo $wrd_theWholeDay; ?>
                        <input type="radio" name="book-summary-line-details-txt-more-dropdown-radio-to" id="book-summary-line-details-txt-more-dropdown-radio-to-whole-day" onchange="bookSummaryDetailsMoreRadioOnchange('to', 2)">
                        <span class="book-summary-line-details-txt-more-dropdown-radio-checkmark"></span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="book-summary-line" id="book-summary-line-total">
              <div class="book-summary-line-about">
                <p class="book-summary-line-title"><?php echo $wrd_totalPrice; ?></p>
                <div class="book-summary-line-details-wrp">
                  <p class="book-summary-line-details-txt" id="book-summary-total"><p>
                </div>
              </div>
            </div>
            <div class="book-summary-line" id="book-summary-line-accept">
              <div class="book-summary-line-about" id="book-summary-line-about-accept">
                <div class="book-summary-line-title" id="book-summary-line-title-accept">
                  <?php echo $wrd_agreeProcessingData; ?>
                  <a href="../conditions/?file=booking-conditions" target="_blank"><?php echo $wrd_contractTermsInflection; ?></a>
                </div>
                <label class="checkbox-label-inpt" id="checkbox-label-inpt-book-summary">
                  <input type="checkbox" id="checkmark-inpt-book-summary">
                  <span class="checkmark-inpt"></span>
                </label>
              </div>
              <div class="book-summary-line-error-wrp">
                <p class="book-summary-line-error" id="book-summary-line-error-accept-1"><?php echo $wrd_confirmAgreementTermsToContinue; ?></p>
              </div>
            </div>
            <div class="book-summary-line" id="book-summary-line-accept-host-conditions">
              <div class="book-summary-line-about" id="book-summary-line-about-accept-host-conditions">
                <div class="book-summary-line-title" id="book-summary-line-title-accept-host-conditions">
                  <?php echo $wrd_iAgreeWithTheConditionsOfTheHost1; ?>
                  <a onclick="modCover('show', 'modal-cover-conditions-of-the-host');"><?php echo $wrd_iAgreeWithTheConditionsOfTheHost2; ?></a>
                </div>
                <label class="checkbox-label-inpt" id="checkbox-label-inpt-book-summary-host-conditions">
                  <input type="checkbox" id="checkmark-inpt-book-summary-host-conditions">
                  <span class="checkmark-inpt"></span>
                </label>
              </div>
              <div class="book-summary-line-error-wrp">
                <p class="book-summary-line-error" id="book-summary-line-error-accept-host-conditions-1"><?php echo $wrd_confirmAgreementTermsToContinue; ?></p>
                <p class="book-summary-line-error" id="book-summary-line-error-accept-host-conditions-2"><?php echo $wrd_automaticProcessingOfHostConditionsFailed." (no-conditions-found)"; ?></p>
                <p class="book-summary-line-error" id="book-summary-line-error-accept-host-conditions-3"><?php echo $wrd_automaticProcessingOfHostConditionsFailed." (conditions-txt-not-found)"; ?></p>
              </div>
            </div>
          </div>
        </div>
        <div id="book-summary-footer">
          <button class="btn btn-mid btn-prim" id="book-summary-binding-booking" onclick="bindingBooking()"><?php echo $wrd_bindingBooking; ?></button>
        </div>
      </div>
      <div class="book-summary-covers" id="book-summary-loader-wrp">
        <img alt="" src="../uni/gifs/loader-tail.svg" id="book-summary-loader">
      </div>
      <div class="book-summary-covers" id="book-summary-error-wrp">
        <div class="book-summary-error-blck" id="book-summary-error-blck-1">
          <p class="book-summary-error-txt"><?php echo $wrd_scriptError." ".$wrd_clickResetOrComeBack; ?></p>
          <div class="book-summary-error-btn-wrp">
            <button class="btn btn-mid btn-prim" onclick="bookSummaryData();"><?php echo $wrd_reset; ?></button>
          </div>
        </div>
        <div class="book-summary-error-blck" id="book-summary-error-blck-2">
          <p class="book-summary-error-txt"><?php echo $wrd_IDInRequestNotMatchAnyInDatabase; ?></p>
          <div class="book-summary-error-btn-wrp">
            <button class="btn btn-mid btn-prim" onclick="location.reload();"><?php echo $wrd_refresh; ?></button>
          </div>
        </div>
        <div class="book-summary-error-blck" id="book-summary-error-blck-3">
          <p class="book-summary-error-txt"><?php echo $wrd_dateNoLongerAvailable; ?></p>
          <div class="book-summary-error-btn-wrp">
            <button class="btn btn-mid btn-sec" onclick="bookSummaryDatesUnavailable();"><?php echo $wrd_close; ?></button>
          </div>
        </div>
        <div class="book-summary-error-blck" id="book-summary-error-blck-4">
          <p class="book-summary-error-txt"><?php echo $wrd_selectedDatesWrongOrder; ?></p>
          <div class="book-summary-error-btn-wrp">
            <button class="btn btn-mid btn-prim" onclick="location.reload();"><?php echo $wrd_refresh; ?></button>
          </div>
        </div>
        <div class="book-summary-error-blck" id="book-summary-error-blck-5">
          <p class="book-summary-error-txt"><?php echo $wrd_plcIDNotExist; ?></p>
          <div class="book-summary-error-btn-wrp">
            <button class="btn btn-mid btn-prim" onclick="location.reload();"><?php echo $wrd_refresh; ?></button>
          </div>
        </div>
        <div class="book-summary-error-blck" id="book-summary-error-blck-6">
          <p class="book-summary-error-txt"><?php echo $wrd_numberGuestsNotMatchRange; ?></p>
          <div class="book-summary-error-btn-wrp">
            <button class="btn btn-mid btn-sec" onclick="bookSummaryModal('hide');"><?php echo $wrd_close; ?></button>
          </div>
        </div>
        <div class="book-summary-error-blck" id="book-summary-error-blck-7">
          <p class="book-summary-error-txt"><?php echo $wrd_scriptError." ".$wrd_importantDataMissing; ?></p>
          <div class="book-summary-error-btn-wrp">
            <button class="btn btn-mid btn-sec" onclick="bookSummaryModal('hide');"><?php echo $wrd_close; ?></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal-cover" id="modal-cover-booking-password">
  <div class="modal-block" id="modal-cover-booking-password-blck">
    <button class="cancel-btn" id="booking-password-cancel-btn" onclick="bindingBookingPasswordModal('hide');"></button>
    <div id="booking-password-wrp">
      <div id="booking-password-head">
        <div id="booking-password-head-img"></div>
        <h3 id="booking-password-head-title"><?php echo $wrd_emailAlreadyRegistered; ?></h3>
      </div>
      <div id="booking-password-inpt-wrp">
        <input type="password" name="password" value="" placeholder="<?php echo $wrd_password; ?>" id="booking-password-inpt" onfocus="bindingBookingPasswordError('reset')" onkeyup="bindingBookingPasswordError('reset')">
        <div id="booking-password-inpt-err-wrp">
          <p class="booking-password-inpt-err" id="booking-password-inpt-err-1"><?php echo $wrd_enterPassword; ?></p>
          <p class="booking-password-inpt-err" id="booking-password-inpt-err-2"><?php echo $wrd_wrongPass; ?></p>
        </div>
        <button id="booking-password-help-btn" onclick="window.open('../forgotten-password/', '_blank');">
          <div id="booking-password-help-img"></div>
          <p id="booking-password-help-txt"><?php echo $wrd_forgot; ?>?</p>
        </button>
      </div>
      <div id="booking-password-submit-btn-wrp">
        <button class="btn btn-mid btn-prim" id="booking-password-submit-btn" onclick="bindingBooking()"><?php echo $wrd_bindingBooking; ?></button>
      </div>
    </div>
  </div>
</div>
<div class="modal-cover" id="modal-cover-binding-booking-error">
  <div class="modal-block" id="modal-cover-binding-booking-error-blck">
    <button class="cancel-btn" onclick="bindingBookingErrorModal();"></button>
    <div id="modal-binding-booking-error">
      <h3 id="modal-binding-booking-error-title"><?php echo $wrd_error; ?></h3>
      <p class="modal-binding-booking-error-txt" id="modal-binding-booking-error-txt-1"><?php echo $wrd_scriptError; ?></p>
      <p class="modal-binding-booking-error-txt" id="modal-binding-booking-error-txt-2"><?php echo $wrd_dataError; ?></p>
      <p class="modal-binding-booking-error-txt" id="modal-binding-booking-error-txt-3"><?php echo $wrd_dateNoLongerAvailable; ?></p>
      <p class="modal-binding-booking-error-txt" id="modal-binding-booking-error-txt-5"><?php echo $wrd_multipleErrorsEncountered; ?></p>
      <p class="modal-binding-booking-error-txt" id="modal-binding-booking-error-txt-6"><?php echo $wrd_failedContactHost; ?></p>
      <p class="modal-binding-booking-error-txt" id="modal-binding-booking-error-txt-7"><?php echo $wrd_failedEmailGuest; ?></p>
      <p class="modal-binding-booking-error-txt" id="modal-binding-booking-error-txt-8"><?php echo $wrd_reservationCouldNotBeCanceled; ?></p>
      <p class="modal-binding-booking-error-txt" id="modal-binding-booking-error-txt-9"><?php echo $wrd_unknownError; ?></p>
      <div id="modal-binding-booking-error-code-wrp">
        <p id="modal-binding-booking-error-code-title"><?php echo $wrd_errorCode.":"; ?></p>
        <div id="modal-binding-booking-error-code-txt-wrp">
          <p id="modal-binding-booking-error-code-txt-cancel-error"></p>
          <p id="modal-binding-booking-error-code-txt"></p>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal-cover" id="modal-cover-thanks-for-booking">
  <div class="modal-block" id="modal-cover-thanks-for-booking-blck">
    <button class="cancel-btn" onclick="bookingThanksModal('hide');"></button>
    <div id="thanks-for-booking-wrp">
      <div id="thanks-for-booking-icon-wrp">
        <div id="thanks-for-booking-icon"></div>
      </div>
      <div id="thanks-for-booking-txt-wrp">
        <p id="thanks-for-booking-txt"><?php echo $wrd_thanksForYourReservation; ?></p>
      </div>
      <div id="thanks-for-booking-call-alert-wrp">
        <p id="thanks-for-booking-call-alert"><?php echo $wrd_dontForgetToCallYourHostToConfirmTheDate1." <b>(".$plcAccContactPhone.")</b>".$wrd_dontForgetToCallYourHostToConfirmTheDate2; ?></p>
      </div>
      <div id="thanks-for-booking-footer">
        <button class="btn btn-mid btn-sec" onclick="bookingThanksModal('hide');"><?php echo $wrd_close; ?></button>
      </div>
    </div>
  </div>
</div>


<div class="modal-cover" id="modal-cover-conditions-of-the-host">
  <div class="modal-block" id="modal-cover-conditions-of-the-host-blck">
    <button class="cancel-btn" onclick="modCover('hide', 'modal-cover-conditions-of-the-host');"></button>
    <div id="place-conditions-of-the-host-wrp">
      <h1 id="place-conditions-of-the-host-title"><?php echo $plcName; ?></h1>
      <p id="place-conditions-of-the-host-txt"><?php echo $plcCondTxt; ?></p>
    </div>
  </div>
</div>
