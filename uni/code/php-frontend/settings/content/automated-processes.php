<div class="settings-errors-wrp">
  <p class="settings-error-txt" id="settings-error-txt-automated-processes"></p>
</div>
<h1 class="settings-content-title"><?php echo $wrd_automatedProcessesShort; ?></h1>
<form class="settings-form-wrp">
  <div class="settings-form-row">
    <div class="settings-input-wrp">
      <p class="settings-txt-title"><?php echo $wrd_notificationOfGuestAboutAutomaticCancellationOfStay; ?></p>
      <div class="settings-checkbox-input-wrp">
        <label class="checkbox-label-mid">
          <?php
            if ($sched_payOrYourBookingWillBeCanceled == "1") {
          ?>
            <input type="checkbox" id="settings-checkbox-input-pay-or-your-booking-will-be-canceled" checked>
          <?php
            } else {
          ?>
            <input type="checkbox" id="settings-checkbox-input-pay-or-your-booking-will-be-canceled">
          <?php
            }
          ?>
          <span class="checkmark-inpt-mid"></span>
        </label>
      </div>
    </div>
    <div class="settings-desc-wrp">
      <p class="settings-desc-txt"><?php echo $wrd_notificationOfGuestAboutAutomaticCancellationOfStayDesc; ?></p>
    </div>
  </div>
  <div class="settings-form-row">
    <div class="settings-input-wrp">
      <p class="settings-txt-title"><?php echo $wrd_cancellationOfUnpaidBooking; ?></p>
      <div class="settings-checkbox-input-wrp">
        <label class="checkbox-label-mid">
          <?php
            if ($sched_cancelUnpaidBookings == "1") {
          ?>
            <input type="checkbox" id="settings-checkbox-input-cancel-unpaid-bookings" checked>
          <?php
            } else {
          ?>
            <input type="checkbox" id="settings-checkbox-input-cancel-unpaid-bookings">
          <?php
            }
          ?>
          <span class="checkmark-inpt-mid"></span>
        </label>
      </div>
    </div>
    <div class="settings-desc-wrp">
      <p class="settings-desc-txt"><?php echo $wrd_cancellationOfUnpaidBookingDesc; ?></p>
    </div>
  </div>
  <div class="settings-form-row">
    <div class="settings-input-wrp">
      <p class="settings-txt-title"><?php echo $wrd_lastCallToPayFullAmountForBooking; ?></p>
      <div class="settings-checkbox-input-wrp">
        <label class="checkbox-label-mid">
          <?php
            if ($sched_payFullAmountAlert == "1") {
          ?>
            <input type="checkbox" id="settings-checkbox-input-pay-full-amount-alert" checked>
          <?php
            } else {
          ?>
            <input type="checkbox" id="settings-checkbox-input-pay-full-amount-alert">
          <?php
            }
          ?>
          <span class="checkmark-inpt-mid"></span>
        </label>
      </div>
    </div>
    <div class="settings-desc-wrp">
      <p class="settings-desc-txt"><?php echo $wrd_lastCallToPayFullAmountForBookingDesc; ?></p>
    </div>
  </div>
  <div class="settings-form-row">
    <div class="settings-input-wrp">
      <p class="settings-txt-title"><?php echo $wrd_callForAgreementOnAlternativePaymentOfFullAmount; ?></p>
      <div class="settings-checkbox-input-wrp">
        <label class="checkbox-label-mid">
          <?php
            if ($sched_unpaidFullAmountCall == "1") {
          ?>
            <input type="checkbox" id="settings-checkbox-input-unpaid-full-amount-call" checked>
          <?php
            } else {
          ?>
            <input type="checkbox" id="settings-checkbox-input-unpaid-full-amount-call">
          <?php
            }
          ?>
          <span class="checkmark-inpt-mid"></span>
        </label>
      </div>
    </div>
    <div class="settings-desc-wrp">
      <p class="settings-desc-txt"><?php echo $wrd_callForAgreementOnAlternativePaymentOfFullAmountDesc; ?></p>
    </div>
  </div>
</form>
<div class="settings-btns-wrap">
  <button class="btn btn-mid btn-prim" id="settings-save-btn-automated-processes" onclick="saveAutomatedProcessesSettings()"><?php echo $wrd_save; ?></button>
</div>
