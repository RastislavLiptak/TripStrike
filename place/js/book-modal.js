var url_string = window.location.href;
var url = new URL(url_string);

function instantBookingCalendarHandler() {
  setTimeout(function() {
    bookModal("show");
  }, 1000);
}

function bookModal(tsk) {
  if (tsk == "show") {
    if (document.getElementById("book-calendar-default-loader-wrp")) {
      document.getElementById("book-calendar-default-loader-wrp").parentNode.removeChild(document.getElementById("book-calendar-default-loader-wrp"));
    }
    calendarRender('book-calendar-wrp', url.searchParams.get("id"), 'guest-view');
    bookTerms('book-calendar-wrp');
    modCover("show", "modal-cover-book");
  } else {
    modCover("hide", "modal-cover-book");
    document.getElementById("r-d-guests-btn").value = 1;
    document.getElementById("r-d-guests-btn-txt").innerHTML = 1;
  }
}

var plc_prc_mode, plc_prc_work, plc_prc_week, plc_prc_currency;
function bookData(prc_mode, prc_work, prc_week, prc_currency) {
  plc_prc_mode = prc_mode;
  plc_prc_work = prc_work;
  plc_prc_week = prc_week;
  plc_prc_currency = prc_currency;
}

var bookDropSts = "up";
function bookGuestsNumDropdown(tsk) {
  if (tsk == "show") {
    document.getElementById("r-d-guests-dropdown").style.display = "flex";
    bookDropSts = "down";
  } else if (tsk == "hide") {
    document.getElementById("r-d-guests-dropdown").style.display = "";
    bookDropSts = "up";
  } else if (tsk == "toggle") {
    if (bookDropSts == "up") {
      bookGuestsNumDropdown("show");
    } else {
      bookGuestsNumDropdown("hide");
    }
  }
}

function bookGuestsNumSelect(num) {
  bookGuestsNumDropdown("hide");
  document.getElementById("r-d-guests-btn-txt").innerHTML = num;
  document.getElementById("r-d-guests-btn").value = num;
}

var term_from_y, term_from_m, term_from_d, term_to_y, term_to_m, term_to_d;
function bookTerms(wrpId) {
  term_from_y = fromToObject[wrpId]['from_y'];
  term_from_m = fromToObject[wrpId]['from_m'];
  term_from_d = fromToObject[wrpId]['from_d'];
  term_to_y = fromToObject[wrpId]['to_y'];
  term_to_m = fromToObject[wrpId]['to_m'];
  term_to_d = fromToObject[wrpId]['to_d'];
  bookTermsTxt();
  bookTermsCalc();
  bookContinueHandler();
}

function bookTermsTxt() {
  if (term_from_y != "" && term_from_y != null) {
    if (term_to_y != "" && term_to_y != null) {
      document.getElementById("r-d-from-to-row-1").style.display = "none";
      document.getElementById("r-d-from-to-row-2").style.display = "";
      document.getElementById("r-d-from-to-txt-3-from").innerHTML = term_from_d +". "+ term_from_m +". "+ term_from_y;
      document.getElementById("r-d-from-to-txt-3-to").innerHTML = term_to_d +". "+ term_to_m +". "+ term_to_y;
      document.getElementById("r-d-from-to-row-3").style.display = "flex";
    } else {
      document.getElementById("r-d-from-to-row-1").style.display = "none";
      document.getElementById("r-d-from-to-txt-2-from").innerHTML = term_from_d +". "+ term_from_m +". "+ term_from_y;
      document.getElementById("r-d-from-to-row-2").style.display = "flex";
      document.getElementById("r-d-from-to-row-3").style.display = "";
    }
  } else {
    document.getElementById("r-d-from-to-row-1").style.display = "";
    document.getElementById("r-d-from-to-row-2").style.display = "";
    document.getElementById("r-d-from-to-row-3").style.display = "";
  }
}

var times_work, times_week, termCalcDone, calc_y, calc_m, calc_d;
function bookTermsCalc() {
  times_work = 0;
  times_week = 0;
  termCalcDone = false;
  if (term_from_y != "" && term_from_y != null && term_to_y != "" && term_to_y != null) {
    calc_y = term_from_y;
    calc_m = term_from_m;
    calc_d = term_from_d;
    while (!termCalcDone) {
      if (calc_y == term_to_y && calc_m == term_to_m && calc_d == term_to_d) {
        termCalcDone = true;
      } else {
        if (new Date(calc_y +"-"+ calc_m +"-"+ calc_d).getDay() <= 4) {
          ++times_work;
        } else {
          ++times_week;
        }
        ++calc_d;
        if (calc_d > new Date(calc_y, calc_m, 0).getDate()) {
          calc_d = 1;
          ++calc_m;
          if (calc_m > 12) {
            calc_m = 1;
            ++calc_y;
          }
        }
      }
    }
  }
  bookPrice(times_work, times_week);
}

var total;
function bookPrice(timesWorkday, timesWeekday) {
  if (document.getElementById("r-d-times-all-days")) {
    timesWorkday = timesWorkday + timesWeekday;
    document.getElementById("r-d-times-all-days").innerHTML = timesWorkday +"x";
    total = plc_prc_work * timesWorkday;
  } else {
    document.getElementById("r-d-times-work").innerHTML = timesWorkday +"x";
    document.getElementById("r-d-times-week").innerHTML = timesWeekday +"x";
    total = plc_prc_work * timesWorkday + plc_prc_week * timesWeekday;
  }
  if (plc_prc_mode == "guests") {
    total = total * document.getElementById("r-d-guests-btn").value;
  }
  document.getElementById("r-d-total-price").innerHTML = addCurrency(plc_prc_currency, total);
}

var continueBtnTime;
var continueBtnReady = true;
var continueBtnRepete = false;
function bookContinueHandler() {
  if (continueBtnReady) {
    continueBtnReady = false;
    continueBtnRepete = false;
    clearTimeout(continueBtnTime);
    if (term_from_y != "" && term_from_y != null && term_to_y != "" && term_to_y != null) {
      document.getElementById("book-footer-btn").style.display = "table";
      continueBtnTime = setTimeout(function(){
        document.getElementById("book-footer-btn").style.opacity = "1";
        continueBtnReady = true;
        if (continueBtnRepete) {
          bookContinueHandler();
        }
      }, 10);
    } else {
      document.getElementById("book-footer-btn").style.opacity = "";
      continueBtnTime = setTimeout(function(){
        document.getElementById("book-footer-btn").style.display = "";
        continueBtnReady = true;
        if (continueBtnRepete) {
          bookContinueHandler();
        }
      }, 160);
    }
  } else {
    continueBtnRepete = true;
  }
}

function bookSummaryModal(tsk) {
  bindingBookingErrorReset();
  if (tsk == "show") {
    if (term_from_y != "" && term_from_y != null && term_to_y != "" && term_to_y != null) {
      modCover("show", "modal-cover-book-summary");
      bookSummaryData("modal", term_from_d, term_from_m, term_from_y, term_to_d, term_to_m, term_to_y);
    } else {
      bookContinueHandler();
    }
  } else {
    bindingBookingCancel();
    bookSummaryDataCancel();
    bookSummaryContentSwitcher("loader");
    modCover("hide", "modal-cover-book-summary");
  }
}

var id, guestsNum, xhrSummCheck, summ_term_from_d, summ_term_from_m, summ_term_from_y, summ_term_to_d, summ_term_to_m, summ_term_to_y;
function bookSummaryData(dataInputForm, termFromD, termFromM, termFromY, termToD, termToM, termToY) {
  bookSummaryContentSwitcher("loader");
  id = url.searchParams.get("id");
  if (dataInputForm == "modal") {
    guestsNum = document.getElementById("r-d-guests-btn").value;
  } else {
    guestsNum = 1;
  }
  summ_term_from_d = termFromD;
  summ_term_from_m = termFromM;
  summ_term_from_y = termFromY;
  summ_term_to_d = termToD;
  summ_term_to_m = termToM;
  summ_term_to_y = termToY;
  xhrSummCheck = new XMLHttpRequest();
  xhrSummCheck.onreadystatechange = function() {
    if (xhrSummCheck.readyState == 4 && xhrSummCheck.status == 200) {
      if (testJSON(xhrSummCheck.response)) {
        var json = JSON.parse(xhrSummCheck.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "data") {
              bookSummaryPushData(json[key]["name"], json[key]["email"], json[key]["phone"], json[key]["guests"], json[key]["from"], json[key]["availability_from"], json[key]["to"], json[key]["availability_to"], json[key]["price"], json[key]["checked"], json[key]["conditionsOfTheHost"], json[key]["lessThan48h"]);
              bookSummaryContentSwitcher("data");
            } else if (json[key]["type"] == "error") {
              bookSummaryDataError(json[key]["error"]);
            }
          }
        }
      } else {
        bookSummaryDataError("json-error");
      }
    }
  }
  xhrSummCheck.open("POST", "../place/php-backend/book-summary-check.php");
  xhrSummCheck.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrSummCheck.send("id="+ id +"&guests="+ guestsNum +"&fromY="+ summ_term_from_y +"&fromM="+ summ_term_from_m +"&fromD="+ summ_term_from_d +"&toY="+ summ_term_to_y +"&toM="+ summ_term_to_m +"&toD="+ summ_term_to_d);
}

function bookSummaryDataCancel() {
  xhrSummCheck.abort();
}

function bookSummaryPushData(name, email, phone, guests, from, availability_from, to, availability_to, price, checked, conditionsOfTheHostSts, lessThan48h) {
  if (lessThan48h == "yes") {
    document.getElementById("book-summary-line-less-than-48-hours").style.display = "table";
    document.getElementById("thanks-for-booking-call-alert-wrp").style.display = "table";
  } else {
    document.getElementById("book-summary-line-less-than-48-hours").style.display = "";
    document.getElementById("thanks-for-booking-call-alert-wrp").style.display = "";
  }
  document.getElementById("book-summary-line-textarea-name").value = name;
  bookSummaryTextarea(document.getElementById("book-summary-line-textarea-name"));
  if (name != "") {
    document.getElementById("book-summary-line-textarea-name").classList.remove("book-summary-line-textarea-empty");
  } else {
    document.getElementById("book-summary-line-textarea-name").classList.add("book-summary-line-textarea-empty");
  }
  document.getElementById("book-summary-line-textarea-email").value = email;
  bookSummaryTextarea(document.getElementById("book-summary-line-textarea-email"));
  if (email != "") {
    document.getElementById("book-summary-line-textarea-email").classList.remove("book-summary-line-textarea-empty");
  } else {
    document.getElementById("book-summary-line-textarea-email").classList.add("book-summary-line-textarea-empty");
  }
  document.getElementById("book-summary-line-textarea-phone").value = phone;
  bookSummaryTextarea(document.getElementById("book-summary-line-textarea-phone"));
  if (phone != "") {
    document.getElementById("book-summary-line-textarea-phone").classList.remove("book-summary-line-textarea-empty");
  } else {
    document.getElementById("book-summary-line-textarea-phone").classList.add("book-summary-line-textarea-empty");
  }
  document.getElementById("book-summary-line-textarea-guests").value = guests;
  bookSummaryTextarea(document.getElementById("book-summary-line-textarea-guests"));
  if (phone != "") {
    document.getElementById("book-summary-line-textarea-guests").classList.remove("book-summary-line-textarea-empty");
  } else {
    document.getElementById("book-summary-line-textarea-guests").classList.add("book-summary-line-textarea-empty");
  }
  document.getElementById("book-summary-from").innerHTML = from;
  document.getElementById("book-summary-line-details-txt-more-dropdown-radio-from-time").checked = true;
  document.getElementById("book-summary-line-details-txt-more-btn-txt-from-time").classList.add("b-s-l-d-t-m-b-t-show");
  document.getElementById("book-summary-line-details-txt-more-btn-txt-from-whole-day").classList.remove("b-s-l-d-t-m-b-t-show");
  if (availability_from == "reserved") {
    document.getElementById("book-summary-line-details-txt-more-btn-arrow-from").style.display = "none";
    document.getElementById("book-summary-line-details-txt-more-btn-from").value = "unavailable";
  } else {
    document.getElementById("book-summary-line-details-txt-more-btn-arrow-from").style.display = "";
    document.getElementById("book-summary-line-details-txt-more-btn-from").value = "show";
  }
  document.getElementById("book-summary-to").innerHTML = to;
  document.getElementById("book-summary-line-details-txt-more-dropdown-radio-to-time").checked = true;
  document.getElementById("book-summary-line-details-txt-more-btn-txt-to-time").classList.add("b-s-l-d-t-m-b-t-show");
  document.getElementById("book-summary-line-details-txt-more-btn-txt-to-whole-day").classList.remove("b-s-l-d-t-m-b-t-show");
  if (availability_to == "reserved") {
    document.getElementById("book-summary-line-details-txt-more-btn-arrow-to").style.display = "none";
    document.getElementById("book-summary-line-details-txt-more-btn-to").value = "unavailable";
  } else {
    document.getElementById("book-summary-line-details-txt-more-btn-arrow-to").style.display = "";
    document.getElementById("book-summary-line-details-txt-more-btn-to").value = "show";
  }
  for (var bSLDTMPI = 0; bSLDTMPI < document.getElementsByClassName("book-summary-line-details-txt-more-price-increase").length; bSLDTMPI++) {
    document.getElementsByClassName("book-summary-line-details-txt-more-price-increase")[bSLDTMPI].style.display = "";
  }
  document.getElementById("book-summary-total").innerHTML = price;
  if (checked == "yes") {
    document.getElementById("checkmark-inpt-book-summary").checked = true;
    document.getElementById("book-summary-line-accept").classList.add("book-summary-line-accept-hide");
  } else {
    document.getElementById("checkmark-inpt-book-summary").checked = false;
    document.getElementById("book-summary-line-accept").classList.remove("book-summary-line-accept-hide");
  }
  if (conditionsOfTheHostSts == "show") {
    document.getElementById("checkmark-inpt-book-summary-host-conditions").checked = false;
    document.getElementById("book-summary-line-accept-host-conditions").classList.remove("book-summary-line-accept-hide");
  } else if (conditionsOfTheHostSts == "hide") {
    document.getElementById("checkmark-inpt-book-summary-host-conditions").checked = true;
    document.getElementById("book-summary-line-accept-host-conditions").classList.add("book-summary-line-accept-hide");
  } else {
    if (conditionsOfTheHostSts == "no-conditions-found") {
      document.getElementById("book-summary-line-error-accept-host-conditions-2").style.display = "table";
    } else if (conditionsOfTheHostSts == "conditions-txt-not-found") {
      document.getElementById("book-summary-line-error-accept-host-conditions-3").style.display = "table";
    }
    document.getElementById("checkmark-inpt-book-summary-host-conditions").checked = false;
    document.getElementById("book-summary-line-accept-host-conditions").classList.remove("book-summary-line-accept-hide");
    document.getElementById("book-summary-data-scroll").scrollTop = 9999999999;
  }
}

function bookSummaryDataError(code) {
  bookSummaryDataErrorReset();
  bookSummaryContentSwitcher("error");
  if (code == "json-error") {
    document.getElementById("book-summary-error-blck-1").style.display = "flex";
  } else if (code == "id-n-exist") {
    document.getElementById("book-summary-error-blck-2").style.display = "flex";
  } else if (code == "dates-unavailable") {
    document.getElementById("book-summary-error-blck-3").style.display = "flex";
  } else if (code == "dates-wrong-order") {
    document.getElementById("book-summary-error-blck-4").style.display = "flex";
  } else if (code == "place-n-found") {
    document.getElementById("book-summary-error-blck-5").style.display = "flex";
  } else if (code == "wrong-guests-number") {
    document.getElementById("book-summary-error-blck-6").style.display = "flex";
  } else if (code == "missing-data") {
    document.getElementById("book-summary-error-blck-7").style.display = "flex";
  }
}

function bookSummaryDataErrorReset() {
  for (var e = 0; e < document.getElementsByClassName("book-summary-error-blck").length; e++) {
    document.getElementsByClassName("book-summary-error-blck")[e].style.display = "";
  }
}

function bookSummaryDatesUnavailable() {
  bookSummaryModal("hide");
  calendarRender('book-calendar-wrp', url.searchParams.get("id"), 'guest-view');
  bookTerms('book-calendar-wrp');
}

var bookSummarySwitcherT1, bookSummarySwitcherT2;
function bookSummaryContentSwitcher(tsk) {
  clearTimeout(bookSummarySwitcherT1);
  clearTimeout(bookSummarySwitcherT2);
  if (tsk == "loader") {
    document.getElementById("book-summary-content").style.opacity = "";
    document.getElementById("book-summary-error-wrp").style.opacity = "";
    document.getElementById("book-summary-loader-wrp").style.display = "";
    bookSummarySwitcherT1 = setTimeout(function(){
      document.getElementById("book-summary-loader-wrp").style.opacity = "";
    }, 10);
    bookSummarySwitcherT2 = setTimeout(function(){
      document.getElementById("book-summary-error-wrp").style.display = "";
    }, 210);
  } else if (tsk == "error") {
    document.getElementById("book-summary-content").style.opacity = "";
    document.getElementById("book-summary-loader-wrp").style.opacity = "0";
    document.getElementById("book-summary-error-wrp").style.display = "flex";
    bookSummarySwitcherT1 = setTimeout(function(){
      document.getElementById("book-summary-error-wrp").style.opacity = "1";
    }, 10);
    bookSummarySwitcherT2 = setTimeout(function(){
      document.getElementById("book-summary-loader-wrp").style.display = "none";
    }, 210);
  } else if (tsk == "data") {
    document.getElementById("book-summary-loader-wrp").style.opacity = "0";
    document.getElementById("book-summary-error-wrp").style.opacity = "";
    bookSummarySwitcherT1 = setTimeout(function(){
      document.getElementById("book-summary-content").style.opacity = "1";
    }, 10);
    bookSummarySwitcherT2 = setTimeout(function(){
      document.getElementById("book-summary-loader-wrp").style.display = "none";
      document.getElementById("book-summary-error-wrp").style.display = "";
    }, 210);
  }
}

function bookSummaryTextarea(el) {
  bindingBookingErrorReset();
  if (el.value != "") {
    el.classList.remove("book-summary-line-textarea-empty");
    el.style.height = "";
    var newH = el.scrollHeight +1; //1px is for border width
    el.style.height = newH +"px";
  } else {
    el.classList.add("book-summary-line-textarea-empty");
  }
}

function bookSummaryDetailsMoreDropdown(tsk, btnEl, dropdownId) {
  for (var b = 0; b < document.getElementsByClassName("book-summary-line-details-txt-more-btn").length; b++) {
    document.getElementsByClassName("book-summary-line-details-txt-more-dropdown-wrp")[b].style.display = "";
    if (document.getElementsByClassName("book-summary-line-details-txt-more-btn")[b].value != "unavailable") {
      document.getElementsByClassName("book-summary-line-details-txt-more-btn")[b].value = "show";
    }
  }
  if (tsk == "show") {
    document.getElementById(dropdownId).style.display = "table";
    btnEl.value = "hide";
  }
}

function bookSummaryDetailsMoreRadioOnchange(section, txtNum) {
  if (section == "from") {
    if (txtNum == 1) {
      document.getElementById("book-summary-line-details-txt-more-btn-txt-from-time").classList.add("b-s-l-d-t-m-b-t-show");
      document.getElementById("book-summary-line-details-txt-more-btn-txt-from-whole-day").classList.remove("b-s-l-d-t-m-b-t-show");
      document.getElementById("book-summary-line-details-txt-more-price-increase-from-work").style.display = "";
      document.getElementById("book-summary-line-details-txt-more-price-increase-from-week").style.display = "";
    } else {
      document.getElementById("book-summary-line-details-txt-more-btn-txt-from-time").classList.remove("b-s-l-d-t-m-b-t-show");
      document.getElementById("book-summary-line-details-txt-more-btn-txt-from-whole-day").classList.add("b-s-l-d-t-m-b-t-show");
      if (new Date(term_from_y +"-"+ term_from_m +"-"+ term_from_d).getDay() == 0 || new Date(term_from_y +"-"+ term_from_m +"-"+ term_from_d).getDay() == 6) {
        document.getElementById("book-summary-line-details-txt-more-price-increase-from-work").style.display = "";
        document.getElementById("book-summary-line-details-txt-more-price-increase-from-week").style.display = "block";
      } else {
        document.getElementById("book-summary-line-details-txt-more-price-increase-from-work").style.display = "block";
        document.getElementById("book-summary-line-details-txt-more-price-increase-from-week").style.display = "";
      }
    }
  } else {
    if (txtNum == 1) {
      document.getElementById("book-summary-line-details-txt-more-btn-txt-to-time").classList.add("b-s-l-d-t-m-b-t-show");
      document.getElementById("book-summary-line-details-txt-more-btn-txt-to-whole-day").classList.remove("b-s-l-d-t-m-b-t-show");
      document.getElementById("book-summary-line-details-txt-more-price-increase-to-work").style.display = "";
      document.getElementById("book-summary-line-details-txt-more-price-increase-to-week").style.display = "";
    } else {
      document.getElementById("book-summary-line-details-txt-more-btn-txt-to-time").classList.remove("b-s-l-d-t-m-b-t-show");
      document.getElementById("book-summary-line-details-txt-more-btn-txt-to-whole-day").classList.add("b-s-l-d-t-m-b-t-show");
      if (new Date(term_to_y +"-"+ term_to_m +"-"+ term_to_d).getDay() == 5 || new Date(term_to_y +"-"+ term_to_m +"-"+ term_to_d).getDay() == 6) {
        document.getElementById("book-summary-line-details-txt-more-price-increase-to-work").style.display = "";
        document.getElementById("book-summary-line-details-txt-more-price-increase-to-week").style.display = "block";
      } else {
        document.getElementById("book-summary-line-details-txt-more-price-increase-to-work").style.display = "block";
        document.getElementById("book-summary-line-details-txt-more-price-increase-to-week").style.display = "";
      }
    }
  }
}

function bookSummaryNumOfGuestsUpdate(el) {
  bookGuestsNumSelect(el.value);
  bookTermsCalc();
  document.getElementById("book-summary-total").innerHTML = document.getElementById("r-d-total-price").innerHTML;
}

var bindingBookingReady = true;
var booking_xhr, saveAnimation, guestName, guestEmail, guestPhone, fromAvailability, toAvailability, accept, accPass, hostConditions;
function bindingBooking() {
  if (bindingBookingReady) {
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    bookingModalCancelDisable(true);
    bindingBookingReady = false;
    bindingBookingBtn("loader");
    bindingBookingErrorReset();
    id = url.searchParams.get("id");
    guestName = document.getElementById("book-summary-line-textarea-name").value;
    guestEmail = document.getElementById("book-summary-line-textarea-email").value;
    guestPhone = document.getElementById("book-summary-line-textarea-phone").value.replace(/\+/g, "plus");
    if (document.getElementById("book-summary-line-details-txt-more-dropdown-radio-from-time").checked) {
      fromAvailability = "half";
    } else {
      fromAvailability = "whole";
    }
    if (document.getElementById("book-summary-line-details-txt-more-dropdown-radio-to-time").checked) {
      toAvailability = "half";
    } else {
      toAvailability = "whole";
    }
    accPass = document.getElementById("booking-password-inpt").value;
    accept = document.getElementById("checkmark-inpt-book-summary").checked;
    hostConditions = document.getElementById("checkmark-inpt-book-summary-host-conditions").checked;
    guestsNum = document.getElementById("book-summary-line-textarea-guests").value;
    booking_xhr = new XMLHttpRequest();
    booking_xhr.onreadystatechange = function() {
      if (booking_xhr.readyState == 4 && booking_xhr.status == 200) {
        window.onbeforeunload = null;
        if (testJSON(booking_xhr.response)) {
          var json = JSON.parse(booking_xhr.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                bindingBookingBtn("done");
                getNearestAvailableDate();
                plcBookPriceHide();
                calendarRender('plc-calendar-wrp', id, 'guest-view');
                clearTimeout(saveAnimation);
                saveAnimation = setTimeout(function(){
                  bookingModalCancelDisable(false);
                  bindingBookingBtn("txt");
                  bookingThanksModal("show");
                  bindingBookingReady = true;
                }, 750);
              } else if (json[key]["type"] == "error") {
                bindingBookingError(json[key]["error"], "");
                bindingBookingReady = true;
              } else if (json[key]["type"] == "value-error") {
                bindingBookingError(json[key]["error"], json[key]["value"]);
                bindingBookingReady = true;
              }
            }
          }
        } else {
          bindingBookingError("json-error", booking_xhr.response);
          bindingBookingReady = true;
        }
      }
    }
    booking_xhr.open("POST", "php-backend/booking.php");
    booking_xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    booking_xhr.send("id="+ id +"&name="+ guestName +"&email="+ guestEmail +"&phone="+ guestPhone +"&pass="+ accPass +"&accept="+ accept +"&hostConditions="+ hostConditions +"&guests="+ guestsNum +"&fromY="+ summ_term_from_y +"&fromM="+ summ_term_from_m +"&fromD="+ summ_term_from_d +"&fromAvailability="+ fromAvailability +"&toY="+ summ_term_to_y +"&toM="+ summ_term_to_m +"&toD="+ summ_term_to_d +"&toAvailability="+ toAvailability);
  }
}

function bookingModalCancelDisable(sts) {
  if (sts) {
    document.getElementById("book-summary-cancel-btn").disabled = true;
    document.getElementById("booking-password-cancel-btn").disabled = true;
    document.getElementById("book-summary-cancel-btn").style.cursor = "default";
    document.getElementById("booking-password-cancel-btn").style.cursor = "default";
    document.getElementById("book-summary-cancel-btn").style.opacity = "0.25";
    document.getElementById("booking-password-cancel-btn").style.opacity = "0.25";
  } else {
    document.getElementById("book-summary-cancel-btn").disabled = false;
    document.getElementById("booking-password-cancel-btn").disabled = false;
    document.getElementById("book-summary-cancel-btn").style.cursor = "";
    document.getElementById("booking-password-cancel-btn").style.cursor = "";
    document.getElementById("book-summary-cancel-btn").style.opacity = "";
    document.getElementById("booking-password-cancel-btn").style.opacity = "";
  }
}

function bindingBookingCancel() {
  bindingBookingReady = true;
  window.onbeforeunload = null;
  if (booking_xhr != null){
    booking_xhr.abort();
  }
  bindingBookingBtn("txt");
  bindingBookingReady = true;
}

function bindingBookingError(code, val) {
  bookingModalCancelDisable(false);
  bindingBookingBtn("txt");
  if (code == "wrong-pass") {
    bindingBookingPasswordModal("show");
    bindingBookingPasswordError(2);
  } else if (code == "ask-f-pass") {
    bindingBookingPasswordModal("show");
  } else {
    if (code == "name-empty") {
      document.getElementById("book-summary-line-error-name-1").style.display = "table";
    } else if (code == "name-w-num") {
      document.getElementById("book-summary-line-error-name-2").style.display = "table";
    } else if (code == "email-empty") {
      document.getElementById("book-summary-line-error-email-1").style.display = "table";
    } else if (code == "not-email") {
      document.getElementById("book-summary-line-error-email-2").style.display = "table";
    } else if (code == "phone-empty") {
      document.getElementById("book-summary-line-error-phone-1").style.display = "table";
    } else if (code == "phone-n-number") {
      document.getElementById("book-summary-line-error-phone-2").style.display = "table";
    } else if (code == "not-phone") {
      document.getElementById("book-summary-line-error-phone-2").style.display = "table";
    } else if (code == "guests-empty") {
      document.getElementById("book-summary-line-error-guests-1").style.display = "table";
    } else if (code == "guests-n-number") {
      document.getElementById("book-summary-line-error-guests-2").style.display = "table";
    } else if (code == "wrong-guests-number") {
      document.getElementById("book-summary-line-error-guests-3").style.display = "table";
    } else if (code == "accept-terms") {
      document.getElementById("book-summary-line-error-accept-1").style.display = "table";
      document.getElementById("book-summary-line-accept").classList.remove("book-summary-line-accept-hide");
    } else if (code == "accept-host-conditions") {
      document.getElementById("book-summary-line-error-accept-host-conditions-1").style.display = "table";
      document.getElementById("book-summary-line-accept-host-conditions").classList.remove("book-summary-line-accept-hide");
      document.getElementById("book-summary-data-scroll").scrollTop = 9999999999;
    } else {
      modCover("show", "modal-cover-binding-booking-error");
      bindingBookingTaskAfterModalCancel(code);
      if (code == "cancel-days-list-faild" || code == "cancel-booking-faild") {
        document.getElementById("modal-binding-booking-error-txt-8").style.display = "table";
        document.getElementById("modal-binding-booking-error-code-txt-cancel-error").innerHTML = code;
      } else if (code == "json-error") {
        document.getElementById("modal-binding-booking-error-txt-1").style.display = "table";
        document.getElementById("modal-binding-booking-error-code-txt").innerHTML = code +"<br>"+ val;
      } else if (code == "day-sql-faild" || code == "day-unavailable") {
        document.getElementById("modal-binding-booking-error-txt-5").style.display = "table";
        document.getElementById("modal-binding-booking-error-code-txt").innerHTML = code +" ("+ val +")";
      } else {
        document.getElementById("modal-binding-booking-error-code-txt").innerHTML = code;
        if (code == "dates-are-same" && code == "wrong-dates-order" || code == "booking-sql-faild" || code == "too-many-accounts-w-id") {
          document.getElementById("modal-binding-booking-error-txt-1").style.display = "table";
        } else if (code == "missing-data" || code == "id-n-found" || code == "place-n-found" || code == "no-account-w-id") {
          document.getElementById("modal-binding-booking-error-txt-2").style.display = "table";
        } else if (code == "dates-unavailable") {
          document.getElementById("modal-binding-booking-error-txt-3").style.display = "table";
        } else if (code == "email-to-host-faild") {
          document.getElementById("modal-binding-booking-error-txt-6").style.display = "table";
        } else if (code == "email-to-guest-faild") {
          document.getElementById("modal-binding-booking-error-txt-7").style.display = "table";
        } else {
          document.getElementById("modal-binding-booking-error-txt-9").style.display = "table";
        }
      }
    }
  }
}

var afterCancelErrorModal = "none";
function bindingBookingTaskAfterModalCancel(code) {
  if (code == "email-to-guest-faild") {
    afterCancelErrorModal = "thanksModal";
  } else if (code != "wrong-pass" && code != "ask-f-pass" && code != "name-empty" && code != "name-w-num" && code != "email-empty" && code != "not-email" && code != "phone-empty" && code != "phone-n-number" && code != "not-phone" && code != "accept-terms" && code != "missing-data" && code != "wrong-guests-number") {
    afterCancelErrorModal = "reset";
  } else {
    afterCancelErrorModal = "none";
  }
}

function bindingBookingErrorReset() {
  for (var e = 0; e < document.getElementsByClassName("book-summary-line-error").length; e++) {
    document.getElementsByClassName("book-summary-line-error")[e].style.display = "";
  }
  for (var m = 0; m < document.getElementsByClassName("modal-binding-booking-error-txt").length; m++) {
    document.getElementsByClassName("modal-binding-booking-error-txt")[m].style.display = "";
  }
  document.getElementById("modal-binding-booking-error-code-txt").innerHTML = "";
  bindingBookingPasswordError("reset");
}

function bindingBookingBtn(tsk) {
  if (tsk == "txt") {
    document.getElementById("book-summary-binding-booking").style.color = "";
    document.getElementById("book-summary-binding-booking").style.backgroundImage = "";
    document.getElementById("book-summary-binding-booking").style.backgroundSize = "";
    document.getElementById("booking-password-submit-btn").style.color = "";
    document.getElementById("booking-password-submit-btn").style.backgroundImage = "";
    document.getElementById("booking-password-submit-btn").style.backgroundSize = "";
  } else if (tsk == "done") {
    document.getElementById("book-summary-binding-booking").style.color = "rgba(0,0,0,0)";
    document.getElementById("book-summary-binding-booking").style.backgroundImage = "url('../uni/icons/ok2.svg')";
    document.getElementById("book-summary-binding-booking").style.backgroundSize = "auto 47%";
    document.getElementById("booking-password-submit-btn").style.color = "rgba(0,0,0,0)";
    document.getElementById("booking-password-submit-btn").style.backgroundImage = "url('../uni/icons/ok2.svg')";
    document.getElementById("booking-password-submit-btn").style.backgroundSize = "auto 47%";
  } else if (tsk == "loader") {
    document.getElementById("book-summary-binding-booking").style.color = "rgba(0,0,0,0)";
    document.getElementById("book-summary-binding-booking").style.backgroundImage = "url('../uni/gifs/loader-tail.svg')";
    document.getElementById("book-summary-binding-booking").style.backgroundSize = "auto 63%";
    document.getElementById("booking-password-submit-btn").style.color = "rgba(0,0,0,0)";
    document.getElementById("booking-password-submit-btn").style.backgroundImage = "url('../uni/gifs/loader-tail.svg')";
    document.getElementById("booking-password-submit-btn").style.backgroundSize = "auto 63%";
  }
}

function bindingBookingPasswordModal(tsk) {
  bindingBookingPasswordError("reset");
  document.getElementById("booking-password-inpt").value = "";
  if (tsk == "show") {
    modCover("show", "modal-cover-booking-password");
  } else {
    bindingBookingCancel();
    modCover("hide", "modal-cover-booking-password");
  }
}

function bindingBookingPasswordError(n) {
  if (n == "reset") {
    bindingBookingPasswordErrorReset();
    document.getElementById("booking-password-help-btn").style.display = "";
  } else {
    document.getElementById("booking-password-help-btn").style.display = "none";
    document.getElementById("booking-password-inpt-err-"+ n).style.display = "block";
  }
}

function bindingBookingPasswordErrorReset() {
  for (var p = 0; p < document.getElementsByClassName("booking-password-inpt-err").length; p++) {
    document.getElementsByClassName("booking-password-inpt-err")[p].style.display = "";
  }
}

function bindingBookingErrorModal() {
  if (afterCancelErrorModal == "none") {
    modCover("hide", "modal-cover-binding-booking-error");
    bindingBookingErrorReset();
  } else if (afterCancelErrorModal == "reset") {
    afterCancelErrorModal = "none";
    calendarRender('book-calendar-wrp', url.searchParams.get("id"), 'guest-view');
    bookTerms('book-calendar-wrp');
    modCover("hide", "modal-cover-binding-booking-error");
    bindingBookingPasswordModal('hide');
    bookSummaryModal('hide');
  } else if (afterCancelErrorModal = "thanksModal") {
    afterCancelErrorModal = "none";
    modCover("hide", "modal-cover-binding-booking-error");
    bookingThanksModal("show");
  }
}

function bookingThanksModal(tsk) {
  if (tsk == "show") {
    modCover('show', 'modal-cover-thanks-for-booking');
    modCover("hide", "modal-cover-binding-booking-error");
    bindingBookingPasswordModal('hide');
    bookSummaryModal('hide');
    bookModal("hide");
  } else {
    modCover('hide', 'modal-cover-thanks-for-booking');
  }
}
