<div class="modal-cover" id="modal-cover-editor-delete">
  <div class="modal-block" id="modal-cover-editor-delete-blck">
    <button class="cancel-btn" onclick="editorPlaceDeleteModal('hide');"></button>
    <div id="e-delete-wrp">
      <p id="e-delete-txt"><?php echo $wrd_areYouSureYouWantToDeleteCottage; ?></p>
      <p id="e-delete-desc"><?php echo $wrd_advertisementWillBeDeletedBookingOffersRejectedActiveBookingsRemainUnchanged; ?></p>
      <div id="e-delete-error-wrp">
        <p id="e-delete-error-txt"></p>
      </div>
      <div id="e-delete-footer">
        <button class="btn btn-mid btn-sec e-delete-footer-btn" onclick="editorPlaceDeleteModal('hide');"><?php echo $wrd_close; ?></button>
        <button class="btn btn-mid btn-fth e-delete-footer-btn" id="e-delete-btn" onclick="placeDelete();"><?php echo $wrd_delete; ?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal-cover" id="modal-cover-plc-image-editor">
  <div class="modal-block" id="modal-cover-plc-image-editor-blck">
    <button class="cancel-btn" onclick="editorImagesModalToggle('hide')"></button>
    <div id="plc-image-editor-content">
      <div id="plc-image-editor-header-wrp">
        <div id="plc-image-editor-header-txt-wrp">
          <h1 id="plc-image-editor-header-txt-title"><?php echo $wrd_editImages; ?></h1>
        </div>
      </div>
      <div id="plc-image-editor-grid-error-wrp">
        <p id="plc-image-editor-grid-error"></p>
      </div>
      <div id="plc-image-editor-grid">
      </div>
      <div id="plc-image-editor-error-wrp">
        <h2 id="plc-image-editor-error-title"><?php echo $wrd_error; ?></h2>
        <p id="plc-image-editor-error-code"></p>
        <div id="plc-image-editor-error-reset-btn-wrp">
          <button class="btn btn-mid btn-prim" onclick="loadEditImages()"><?php echo $wrd_reset; ?></button>
        </div>
      </div>
      <div id="plc-image-editor-loader-wrp">
        <div id="plc-image-editor-loader"></div>
      </div>
    </div>
  </div>
</div>




<div class="modal-cover modal-cover-up-2" id="modal-cover-editor-content-map-search">
  <div id="modal-editor-content-map-blck">
    <div id="modal-editor-content-map-wrp">
      <button type="button" class="modal-editor-content-map-btn" id="modal-editor-content-map-btn-search"></button>
      <button type="button" class="modal-editor-content-map-btn" id="modal-editor-content-map-btn-cancel" onclick="editorModSearchClose('click', 'map')"></button>
      <input type="text" class="form-control" id="modal-editor-content-map-search-inpt" placeholder="<?php echo $wrd_searchForAPlaceGM; ?>">
    </div>
  </div>
</div>

<div class="modal-cover" id="modal-cover-editor-calendar-technical-shutdown">
  <div class="modal-block modal-blck-editor" id="modal-cover-editor-calendar-technical-shutdown-blck">
    <button class="cancel-btn" onclick="editorCalendarTechnicalShutdownModal('hide');"></button>
    <div class="modal-editor-content-layout">
      <div class="modal-editor-content-header">
        <p class="modal-editor-content-header-title"><?php echo $wrd_technicalShutdown; ?></p>
      </div>
      <div class="modal-editor-content-scroll" id="modal-editor-content-scroll-technical-shutdown">
        <div class="modal-editor-content-size">
          <form class="modal-editor-content-form">
            <div class="m-e-c-f-error-wrp" id="m-e-c-f-error-wrp-technical-shutdown">
              <p class="m-e-c-f-error-txt" id="m-e-c-f-error-txt-technical-shutdown"></p>
            </div>
            <div class="m-e-c-f-row">
              <div class="m-e-c-f-select-layout" id="m-e-c-f-select-layout-technical-shutdown">
                <p class="m-e-c-f-title-2"><?php echo $wrd_category.":"; ?></p>
                <select class="m-e-c-f-select" id="m-e-c-f-select-technical-shutdown">
                  <option class="m-e-c-f-select-option-technical-shutdown" value="cleaning"><?php echo $wrd_cleaning; ?></option>
                  <option class="m-e-c-f-select-option-technical-shutdown" value="maintenance"><?php echo $wrd_maintenance; ?></option>
                  <option class="m-e-c-f-select-option-technical-shutdown" value="reconstruction"><?php echo $wrd_reconstruction; ?></option>
                  <option class="m-e-c-f-select-option-technical-shutdown" value="other"><?php echo $wrd_other; ?></option>
                </select>
              </div>
            </div>
            <div class="m-e-c-f-row">
              <p class="m-e-c-f-title"><?php echo $wrd_from; ?></p>
              <div class="m-e-c-f-input-wrp">
                <input type="date" value="" class="m-e-c-f-input" id="m-e-c-f-input-from-technical-shutdown">
                <div class="m-e-c-f-dropdown-wrp">
                  <button type="button" value="show" class="m-e-c-f-dropdown-btn" onclick="editorInputDateDropdownToggle(this.value, this, 'm-e-c-f-dropdown-from-technical-shutdown')">
                    <p class="m-e-c-f-dropdown-btn-txt m-e-c-f-dropdown-btn-txt-show" id="m-e-c-f-dropdown-btn-txt-from-1-technical-shutdown"><?php echo $wrd_from." 14:00"; ?></p>
                    <p class="m-e-c-f-dropdown-btn-txt" id="m-e-c-f-dropdown-btn-txt-from-2-technical-shutdown"><?php echo $wrd_theWholeDay; ?></p>
                    <div class="m-e-c-f-dropdown-btn-arrow"></div>
                  </button>
                  <div class="m-e-c-f-dropdown" id="m-e-c-f-dropdown-from-technical-shutdown">
                    <label class="m-e-c-f-dropdown-radio-container"><?php echo $wrd_from." 14:00"; ?>
                      <input type="radio" onchange="editorInputDateDropdownSelectOnchange('from', 1, 'technical-shutdown')" name="m-e-c-f-dropdown-radio-from-technical-shutdown" id="m-e-c-f-dropdown-radio-from-1-technical-shutdown" checked>
                      <span class="m-e-c-f-dropdown-radio-checkmark"></span>
                    </label>
                    <label class="m-e-c-f-dropdown-radio-container"><?php echo $wrd_theWholeDay; ?>
                      <input type="radio" onchange="editorInputDateDropdownSelectOnchange('from', 2, 'technical-shutdown')" name="m-e-c-f-dropdown-radio-from-technical-shutdown" id="m-e-c-f-dropdown-radio-from-2-technical-shutdown">
                      <span class="m-e-c-f-dropdown-radio-checkmark"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="m-e-c-f-row">
              <p class="m-e-c-f-title"><?php echo $wrd_to; ?></p>
              <div class="m-e-c-f-input-wrp">
                <input type="date" value="" class="m-e-c-f-input" id="m-e-c-f-input-to-technical-shutdown">
                <div class="m-e-c-f-dropdown-wrp">
                  <button type="button" value="show" class="m-e-c-f-dropdown-btn" onclick="editorInputDateDropdownToggle(this.value, this, 'm-e-c-f-dropdown-to-technical-shutdown')">
                    <p class="m-e-c-f-dropdown-btn-txt m-e-c-f-dropdown-btn-txt-show" id="m-e-c-f-dropdown-btn-txt-to-1-technical-shutdown"><?php echo $wrd_to." 11:00"; ?></p>
                    <p class="m-e-c-f-dropdown-btn-txt" id="m-e-c-f-dropdown-btn-txt-to-2-technical-shutdown"><?php echo $wrd_theWholeDay; ?></p>
                    <div class="m-e-c-f-dropdown-btn-arrow"></div>
                  </button>
                  <div class="m-e-c-f-dropdown" id="m-e-c-f-dropdown-to-technical-shutdown">
                    <label class="m-e-c-f-dropdown-radio-container"><?php echo $wrd_to." 11:00"; ?>
                      <input type="radio" onchange="editorInputDateDropdownSelectOnchange('to', 1, 'technical-shutdown')" name="m-e-c-f-dropdown-radio-to-technical-shutdown" id="m-e-c-f-dropdown-radio-to-1-technical-shutdown" checked>
                      <span class="m-e-c-f-dropdown-radio-checkmark">
                      </span>
                    </label>
                    <label class="m-e-c-f-dropdown-radio-container"><?php echo $wrd_theWholeDay; ?>
                      <input type="radio" onchange="editorInputDateDropdownSelectOnchange('to', 2, 'technical-shutdown')" name="m-e-c-f-dropdown-radio-to-technical-shutdown" id="m-e-c-f-dropdown-radio-to-2-technical-shutdown">
                      <span class="m-e-c-f-dropdown-radio-checkmark">
                      </span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="m-e-c-f-row">
              <p class="m-e-c-f-title"><?php echo $wrd_notes; ?></p>
              <div class="m-e-c-f-input-wrp">
                <textarea class="m-e-c-f-textarea" id="m-e-c-f-textarea-notes-technical-shutdown" placeholder="<?php echo $wrd_type; ?>"></textarea>
              </div>
            </div>
            <div class="modal-editor-content-form-footer">
              <button type="button" class="btn btn-mid btn-prim modal-editor-content-form-footer-btn" id="modal-editor-content-form-footer-btn-technical-shutdown" onclick="editorCalendarAddTechnicalShutdownCheck();"><?php echo $wrd_save ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal-cover" id="modal-cover-editor-calendar-new-booking">
  <div class="modal-block modal-blck-editor" id="modal-cover-editor-calendar-new-booking-blck">
    <button class="cancel-btn" onclick="editorCalendarNewBookingModal('hide');"></button>
    <div class="modal-editor-content-layout">
      <div class="modal-editor-content-header">
        <p class="modal-editor-content-header-title"><?php echo $wrd_addBooking; ?></p>
      </div>
      <div class="modal-editor-content-scroll" id="modal-editor-content-scroll-new-booking">
        <div class="modal-editor-content-size">
          <form class="modal-editor-content-form">
            <div class="m-e-c-f-error-wrp" id="m-e-c-f-error-wrp-new-booking">
              <p class="m-e-c-f-error-txt" id="m-e-c-f-error-txt-new-booking"></p>
            </div>
            <div class="m-e-c-f-row">
              <p class="m-e-c-f-title"><?php echo $wrd_name; ?></p>
              <div class="m-e-c-f-input-wrp">
                <input type="text" value="" class="m-e-c-f-input" id="m-e-c-f-input-name-new-booking">
              </div>
            </div>
            <div class="m-e-c-f-row">
              <p class="m-e-c-f-title"><?php echo $wrd_email; ?></p>
              <div class="m-e-c-f-input-wrp">
                <input type="email" value="" class="m-e-c-f-input" id="m-e-c-f-input-email-new-booking">
              </div>
            </div>
            <div class="m-e-c-f-row">
              <p class="m-e-c-f-title"><?php echo $wrd_phoneNumber; ?></p>
              <div class="m-e-c-f-input-wrp">
                <input type="tel" value="" class="m-e-c-f-input" id="m-e-c-f-input-phone-new-booking">
              </div>
            </div>
            <div class="m-e-c-f-row">
              <p class="m-e-c-f-title"><?php echo $wrd_guestNum; ?></p>
              <div class="m-e-c-f-input-wrp">
                <input type="number" value="" min="1" max="<?php echo $plcGuestMaxNum; ?>" class="m-e-c-f-input" id="m-e-c-f-input-guest-num-new-booking">
              </div>
            </div>
            <div class="m-e-c-f-row">
              <p class="m-e-c-f-title"><?php echo $wrd_from; ?></p>
              <div class="m-e-c-f-input-wrp">
                <input type="date" value="" class="m-e-c-f-input" id="m-e-c-f-input-from-new-booking">
                <div class="m-e-c-f-dropdown-wrp">
                  <button type="button" value="show" class="m-e-c-f-dropdown-btn" onclick="editorInputDateDropdownToggle(this.value, this, 'm-e-c-f-dropdown-from-new-booking')">
                    <p class="m-e-c-f-dropdown-btn-txt m-e-c-f-dropdown-btn-txt-show" id="m-e-c-f-dropdown-btn-txt-from-1-new-booking"><?php echo $wrd_from." 14:00"; ?></p>
                    <p class="m-e-c-f-dropdown-btn-txt" id="m-e-c-f-dropdown-btn-txt-from-2-new-booking"><?php echo $wrd_theWholeDay; ?></p>
                    <div class="m-e-c-f-dropdown-btn-arrow"></div>
                  </button>
                  <div class="m-e-c-f-dropdown" id="m-e-c-f-dropdown-from-new-booking">
                    <label class="m-e-c-f-dropdown-radio-container"><?php echo $wrd_from." 14:00"; ?>
                      <input type="radio" onchange="editorInputDateDropdownSelectOnchange('from', 1, 'new-booking')" name="m-e-c-f-dropdown-radio-from-new-booking" id="m-e-c-f-dropdown-radio-from-1-new-booking" checked>
                      <span class="m-e-c-f-dropdown-radio-checkmark"></span>
                    </label>
                    <label class="m-e-c-f-dropdown-radio-container"><?php echo $wrd_theWholeDay; ?>
                      <input type="radio" onchange="editorInputDateDropdownSelectOnchange('from', 2, 'new-booking')" name="m-e-c-f-dropdown-radio-from-new-booking" id="m-e-c-f-dropdown-radio-from-2-new-booking">
                      <span class="m-e-c-f-dropdown-radio-checkmark"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="m-e-c-f-row">
              <p class="m-e-c-f-title"><?php echo $wrd_to; ?></p>
              <div class="m-e-c-f-input-wrp">
                <input type="date" value="" class="m-e-c-f-input" id="m-e-c-f-input-to-new-booking">
                <div class="m-e-c-f-dropdown-wrp">
                  <button type="button" value="show" class="m-e-c-f-dropdown-btn" onclick="editorInputDateDropdownToggle(this.value, this, 'm-e-c-f-dropdown-to-new-booking')">
                    <p class="m-e-c-f-dropdown-btn-txt m-e-c-f-dropdown-btn-txt-show" id="m-e-c-f-dropdown-btn-txt-to-1-new-booking"><?php echo $wrd_to." 11:00"; ?></p>
                    <p class="m-e-c-f-dropdown-btn-txt" id="m-e-c-f-dropdown-btn-txt-to-2-new-booking"><?php echo $wrd_theWholeDay; ?></p>
                    <div class="m-e-c-f-dropdown-btn-arrow"></div>
                  </button>
                  <div class="m-e-c-f-dropdown" id="m-e-c-f-dropdown-to-new-booking">
                    <label class="m-e-c-f-dropdown-radio-container"><?php echo $wrd_to." 11:00"; ?>
                      <input type="radio" onchange="editorInputDateDropdownSelectOnchange('to', 1, 'new-booking')" name="m-e-c-f-dropdown-radio-to-new-booking" id="m-e-c-f-dropdown-radio-to-1-new-booking" checked>
                      <span class="m-e-c-f-dropdown-radio-checkmark">
                      </span>
                    </label>
                    <label class="m-e-c-f-dropdown-radio-container"><?php echo $wrd_theWholeDay; ?>
                      <input type="radio" onchange="editorInputDateDropdownSelectOnchange('to', 2, 'new-booking')" name="m-e-c-f-dropdown-radio-to-new-booking" id="m-e-c-f-dropdown-radio-to-2-new-booking">
                      <span class="m-e-c-f-dropdown-radio-checkmark">
                      </span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="m-e-c-f-row">
              <div class="m-e-c-f-checkbox-size">
                <label class="m-e-c-f-checkbox-label"><?php echo $wrd_theDepositIsPaid; ?>
                  <input type="checkbox" id="m-e-c-f-checkbox-label-deposit-new-booking" onchange="editorCalendarDepositChanged('new-booking')">
                  <span class="m-e-c-f-checkbox-checkmark">
                  </span>
                </label>
              </div>
            </div>
            <div class="m-e-c-f-row">
              <div class="m-e-c-f-checkbox-size">
                <label class="m-e-c-f-checkbox-label"><?php echo $wrd_theFullAmountIsPaid; ?>
                  <input type="checkbox" id="m-e-c-f-checkbox-label-full-amount-new-booking" onchange="editorCalendarFullAmountChanged('new-booking')">
                  <span class="m-e-c-f-checkbox-checkmark">
                  </span>
                </label>
              </div>
            </div>
            <div class="m-e-c-f-row">
              <p class="m-e-c-f-title"><?php echo $wrd_notes; ?></p>
              <div class="m-e-c-f-input-wrp">
                <textarea class="m-e-c-f-textarea" id="m-e-c-f-textarea-notes-new-booking" placeholder="<?php echo $wrd_type; ?>"></textarea>
              </div>
            </div>
            <div class="modal-editor-content-form-footer">
              <button type="button" class="btn btn-mid btn-prim modal-editor-content-form-footer-btn" id="modal-editor-content-form-footer-btn-new-booking" onclick="editorCalendarNewBookingCheck();"><?php echo $wrd_save ?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal-cover" id="modal-cover-editor-calendar-day-details">
  <div class="modal-block modal-blck-editor" id="modal-cover-editor-calendar-day-details-blck">
    <button class="cancel-btn" onclick="editorCalendarDayDetailsModal('hide');"></button>
    <div class="modal-editor-content-layout">
      <div class="modal-editor-content-header">
        <p class="modal-editor-content-header-title" id="modal-editor-content-header-title-day-details"></p>
      </div>
      <div class="modal-editor-content-scroll" id="modal-editor-content-scroll-day-details">
        <div class="modal-editor-content-size">
          <div class="modal-editor-content-form">
            <div class="m-e-c-f-error-wrp" id="m-e-c-f-error-wrp-day-details">
              <p class="m-e-c-f-error-txt" id="m-e-c-f-error-txt-day-details"></p>
            </div>
            <div id="editor-calendar-day-details-content"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal-cover" id="modal-cover-editor-calendar-cancel-technical-shutdown">
  <div class="modal-block alert-error-block-w-btn editor-calendar-booking-change-modal" id="modal-cover-editor-calendar-cancel-technical-shutdown-blck">
    <button class="cancel-btn" onclick="editorCalendarCancelTechnicalShutdownModal('hide', '', '', '', '', '', '', '');"></button>
    <div class="editor-calendar-booking-change-modal-scroll-wrp">
      <div class="editor-calendar-booking-change-modal-content-wrp" id="editor-calendar-technical-shutdown-change-modal-content-wrp-cancel">
        <p class="editor-calendar-booking-change-modal-txt"><?php echo $wrd_areYouSureYouWantToCancelThisTechnicalShutdown; ?> <b id="editor-calendar-technical-shutdown-change-modal-txt-cancel-term"></b> (<b id="editor-calendar-technical-shutdown-change-modal-txt-cancel-ctgr"></b>)?</p>
        <div class="editor-calendar-booking-change-modal-btn-wrp">
          <button type="button" class="btn btn-mid btn-fth" id="editor-calendar-technical-shutdown-change-modal-btn-cancel"><?php echo $wrd_cancelTechnicalShutdown; ?></button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal-cover" id="modal-cover-editor-calendar-cancel-booking">
  <div class="modal-block" id="modal-cover-editor-calendar-cancel-booking-blck">
    <button class="cancel-btn" onclick="modCover('hide', 'modal-cover-editor-calendar-cancel-booking');"></button>
    <div id="editor-calendar-cancel-booking-ask-wrp">
      <div id="editor-calendar-cancel-booking-about-wrp">
        <p id="editor-calendar-cancel-booking-about-txt-from-d"></p>
        <p id="editor-calendar-cancel-booking-about-txt-from-m"></p>
        <p id="editor-calendar-cancel-booking-about-txt-from-y"></p>
        <p id="editor-calendar-cancel-booking-about-txt-to-d"></p>
        <p id="editor-calendar-cancel-booking-about-txt-to-m"></p>
        <p id="editor-calendar-cancel-booking-about-txt-to-y"></p>
      </div>
      <div id="editor-calendar-cancel-booking-ask-layout">
        <div id="editor-calendar-cancel-booking-ask-txt-wrp">
          <p id="editor-calendar-cancel-booking-ask-txt-title"><?php echo $wrd_areYouSureYouWantToCancelThisBooking; ?></p>
          <p id="editor-calendar-cancel-booking-ask-txt-desc"><?php echo $wrd_ifYouClickYesThisBookingWillBeCanceledAndguestWillBeNotified; ?></p>
        </div>
        <div id="editor-calendar-cancel-booking-ask-btn-wrp">
          <button type="button" class="btn btn-mid btn-sec editor-calendar-cancel-booking-ask-btn" onclick="modCover('hide', 'modal-cover-editor-calendar-cancel-booking');"><?php echo $wrd_no; ?></button>
          <button type="button" class="btn btn-mid btn-fth editor-calendar-cancel-booking-ask-btn" onclick="editorCalendarCancelBookingRequest();"><?php echo $wrd_yes; ?></button>
        </div>
      </div>
    </div>
  </div>
</div>
