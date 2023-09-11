<div class="modal-cover" id="modal-cover-sign-up">
  <div class="modal-block" id="modal-cover-sign-up-blck">
    <button class="cancel-btn" onclick="signUpModal('hide')"></button>
    <div class="sign-in-size">
      <form class="sign-in-form" onsubmit="signUp(event)">
        <div class="sign-in-form-body" id="sign-in-form-body-up">
          <p class="sign-in-title"><?php echo $wrd_signUp; ?></p>
          <div class="sign-in-error-wrp">
            <p class="sign-in-error-txt" id="sign-in-error-txt-up"></p>
          </div>
          <div class="sign-in-form-content">
            <div class="sign-in-form-row sign-up-form-row">
              <div id="sign-up-nav-wrp">
                <button type="button" class="sign-up-nav-btn sign-up-nav-btn-selected" id="sign-up-nav-btn-about-you" onclick="signUpContentSwitch('about-you')">
                  <p class="sign-up-nav-btn-txt"><?php echo $wrd_aboutYou; ?></p>
                  <div class="sign-up-nav-btn-icon"></div>
                </button>
                <button type="button" class="sign-up-nav-btn" id="sign-up-nav-btn-email-and-phone" onclick="signUpContentSwitch('email-and-phone')">
                  <p class="sign-up-nav-btn-txt"><?php echo $wrd_emailAndPhoneNumber; ?></p>
                  <div class="sign-up-nav-btn-icon"></div>
                </button>
                <button type="button" class="sign-up-nav-btn" id="sign-up-nav-btn-password" onclick="signUpContentSwitch('password')">
                  <p class="sign-up-nav-btn-txt"><?php echo $wrd_password; ?></p>
                  <div class="sign-up-nav-btn-icon"></div>
                </button>
                <button type="button" class="sign-up-nav-btn" id="sign-up-nav-btn-conditions" onclick="signUpContentSwitch('conditions')">
                  <p class="sign-up-nav-btn-txt"><?php echo $wrd_conditions; ?></p>
                </button>
              </div>
            </div>
            <div id="sign-up-form-content">
              <div class="sign-up-form-wrp sign-up-form-wrp-selected" id="sign-up-form-wrp-about-you">
                <div class="sign-up-form-about-wrp">
                  <p class="sign-up-form-about-id">about-you</p>
                </div>
                <div class="sign-in-form-row sign-up-form-row">
                  <p class="sign-up-row-title"><?php echo $wrd_firstName; ?>*</p>
                  <input type="text" name="firstname" class="sign-up-input" id="sign-up-input-firstname" onfocusout="signUpAboutYouFulfillmentSts();signUpContinueAndSubmitBtnManager()" onkeyup="signUpContinueAndSubmitBtnManager()" onchange="signUpContinueAndSubmitBtnManager()">
                </div>
                <div class="sign-in-form-row sign-up-form-row">
                  <p class="sign-up-row-title"><?php echo $wrd_lastName; ?>*</p>
                  <input type="text" name="lastname" class="sign-up-input" id="sign-up-input-lastname" onfocusout="signUpAboutYouFulfillmentSts();signUpContinueAndSubmitBtnManager()" onkeyup="signUpContinueAndSubmitBtnManager()" onchange="signUpContinueAndSubmitBtnManager()">
                </div>
                <div class="sign-in-form-row sign-up-form-row">
                  <p class="sign-up-row-title"><?php echo $wrd_birth; ?>*</p>
                  <div id="sign-up-birth-wrp">
                    <input type="number" name="birth day" min="1" max="31" placeholder="DD" name="lastname" class="sign-up-input sign-up-birth-input-small" id="sign-up-input-birth-day" onfocusout="signUpAboutYouFulfillmentSts();signUpContinueAndSubmitBtnManager()" onkeyup="signUpContinueAndSubmitBtnManager()" onchange="signUpContinueAndSubmitBtnManager()">
                    <input type="number" name="birth month" min="1" max="12" placeholder="MM" name="lastname" class="sign-up-input sign-up-birth-input-small" id="sign-up-input-birth-month" onfocusout="signUpAboutYouFulfillmentSts();signUpContinueAndSubmitBtnManager()" onkeyup="signUpContinueAndSubmitBtnManager()" onchange="signUpContinueAndSubmitBtnManager()">
                    <input type="number" name="birth year" name="lastname" placeholder="YYYY" class="sign-up-input sign-up-birth-input-big" id="sign-up-input-birth-year" onfocusout="signUpAboutYouFulfillmentSts();signUpContinueAndSubmitBtnManager()" onkeyup="signUpContinueAndSubmitBtnManager()" onchange="signUpContinueAndSubmitBtnManager()">
                  </div>
                </div>
              </div>
              <div class="sign-up-form-wrp" id="sign-up-form-wrp-email-and-phone">
                <div class="sign-up-form-about-wrp">
                  <p class="sign-up-form-about-id">email-and-phone</p>
                </div>
                <div class="sign-in-form-row sign-up-form-row">
                  <p class="sign-up-row-title"><?php echo $wrd_email; ?>*</p>
                  <input type="email" name="email" class="sign-up-input" id="sign-up-input-email" onfocusout="signUpEmailAndPhoneFulfillmentSts();signUpContinueAndSubmitBtnManager()" onkeyup="signUpContinueAndSubmitBtnManager()" onchange="signUpContinueAndSubmitBtnManager()">
                </div>
                <div class="sign-in-form-row sign-up-form-row">
                  <p class="sign-up-row-title"><?php echo $wrd_contactEmail; ?></p>
                  <input type="email" name="contact email" class="sign-up-input" id="sign-up-input-contact-email">
                  <p class="sign-up-row-desc"><?php echo $wrd_itWillNotBePossibleToLogInToYourAccountByContactEmailItOnlyServesAsASecurityForLoginDataWhichWillNotBeUsedByTheSiteAsContactsForYouIfYouLeaveThisFieldBlankItWillBeAutomaticallyCompletedWithLoginData; ?></p>
                </div>
                <div class="sign-in-form-row sign-up-form-row">
                  <p class="sign-up-row-title"><?php echo $wrd_phoneNumber; ?>*</p>
                  <input type="tel" name="phone number" class="sign-up-input" id="sign-up-input-phone-number" onfocusout="signUpEmailAndPhoneFulfillmentSts();signUpContinueAndSubmitBtnManager()" onkeyup="signUpContinueAndSubmitBtnManager()" onchange="signUpContinueAndSubmitBtnManager()">
                </div>
                <div class="sign-in-form-row sign-up-form-row">
                  <p class="sign-up-row-title"><?php echo $wrd_contactPhoneNumber; ?></p>
                  <input type="tel" name="contact phone number" class="sign-up-input" id="sign-up-input-contact-phone-number">
                  <p class="sign-up-row-desc"><?php echo $wrd_itWillNotBePossibleToLogInToYourAccountByContactPhoneNumberItOnlyServesAsASecurityForLoginDataWhichWillNotBeUsedByTheSiteAsContactsForYouIfYouLeaveThisFieldBlankItWillBeAutomaticallyCompletedWithLoginData; ?></p>
                </div>
              </div>
              <div class="sign-up-form-wrp" id="sign-up-form-wrp-password">
                <div class="sign-up-form-about-wrp">
                  <p class="sign-up-form-about-id">password</p>
                </div>
                <div class="sign-in-form-row sign-up-form-row">
                  <p class="sign-up-row-title"><?php echo $wrd_password; ?>*</p>
                  <input type="password" name="password" class="sign-up-input" id="sign-up-input-password" onfocusout="signUpPasswordFulfillmentSts();signUpContinueAndSubmitBtnManager()" onkeyup="signUpContinueAndSubmitBtnManager()" onchange="signUpContinueAndSubmitBtnManager()">
                </div>
                <div class="sign-in-form-row sign-up-form-row">
                  <p class="sign-up-row-title"><?php echo $wrd_passwordVerification; ?>*</p>
                  <input type="password" name="password verification" class="sign-up-input" id="sign-up-input-password-verification" onfocusout="signUpPasswordFulfillmentSts();signUpContinueAndSubmitBtnManager()" onkeyup="signUpContinueAndSubmitBtnManager()" onchange="signUpContinueAndSubmitBtnManager()">
                </div>
              </div>
              <div class="sign-up-form-wrp" id="sign-up-form-wrp-conditions">
                <div class="sign-up-form-about-wrp">
                  <p class="sign-up-form-about-id">conditions</p>
                </div>
                <div class="sign-in-form-row sign-up-form-row">
                  <div id="sign-up-contitions-wrp">
                    <p id="sign-up-contitions-txt">
                      <?php
                        $terms_and_conditions_file = fopen("../conditions/texts/terms-and-conditions/".$wrd_shrt."_terms-and-conditions.txt", "r") or die("Unable to open file! (terms-and-conditions.txt)");
                        echo nl2br(fread($terms_and_conditions_file, filesize("../conditions/texts/terms-and-conditions/".$wrd_shrt."_terms-and-conditions.txt")));
                        fclose($terms_and_conditions_file);
                      ?>
                      <br>
                      <?php
                        $booking_conditions_file = fopen("../conditions/texts/booking-conditions/".$wrd_shrt."_booking-conditions.txt", "r") or die("Unable to open file! (booking-conditions.txt)");
                        echo nl2br(fread($booking_conditions_file, filesize("../conditions/texts/booking-conditions/".$wrd_shrt."_booking-conditions.txt")));
                        fclose($booking_conditions_file);
                      ?>
                    </p>
                  </div>
                </div>
                <div class="sign-in-form-row">
                  <label class="checkbox-label"><?php echo $wrd_accept; ?>*
                    <input type="checkbox" id="sign-up-checkbox-conditions" onclick="signUpConditionsFulfillmentSts();signUpContinueAndSubmitBtnManager()" onkeyup="signUpContinueAndSubmitBtnManager()">
                    <span class="checkmark"></span>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="sign-in-footer">
          <button type="button" class="btn btn-mid sign-in-footer-switch-btn" onclick="signSwitch('sign-in')"><?php echo $wrd_signIn; ?></button>
          <button type="button" class="btn btn-mid btn-prim" onclick="signUpContinue()" id="sign-up-btn-continue"><?php echo $wrd_continue; ?></button>
          <button type="submit" class="btn btn-mid btn-prim sign-in-footer-submit-btn" id="sign-up-btn"><?php echo $wrd_signUp; ?></button>
        </div>
      </form>
    </div>
  </div>
</div>
