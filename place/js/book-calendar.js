function plcDetailsCalendar(id) {
  if (document.getElementById("p-d-a-block-calendar")) {
    document.getElementById("p-d-a-block-calendar").style.display = "flex";
    calendarRender("plc-calendar-wrp", id, "guest-view");
  }
}

var plc_prc_work, plc_prc_week, plc_prc_currency;
function plcBookData(prc_work, prc_week, prc_currency) {
  plc_prc_work = prc_work;
  plc_prc_week = prc_week;
  plc_prc_currency = prc_currency;
}

var plc_term_from_y, plc_term_from_m, plc_term_from_d, plc_term_to_y, plc_term_to_m, plc_term_to_d;
function plcBookTerms(wrpId) {
  plc_term_from_y = fromToObject[wrpId]['from_y'];
  plc_term_from_m = fromToObject[wrpId]['from_m'];
  plc_term_from_d = fromToObject[wrpId]['from_d'];
  plc_term_to_y = fromToObject[wrpId]['to_y'];
  plc_term_to_m = fromToObject[wrpId]['to_m'];
  plc_term_to_d = fromToObject[wrpId]['to_d'];
  plcBookTermsCalc();
}

var plc_times_work, plc_times_week, plc_termCalcDone, plc_calc_y, plc_calc_m, plc_calc_d;
function plcBookTermsCalc() {
  plc_times_work = 0;
  plc_times_week = 0;
  plc_termCalcDone = false;
  if (plc_term_from_y != "" && plc_term_from_y != null && plc_term_to_y != "" && plc_term_to_y != null) {
    plc_calc_y = plc_term_from_y;
    plc_calc_m = plc_term_from_m;
    plc_calc_d = plc_term_from_d;
    while (!plc_termCalcDone) {
      if (plc_calc_y == plc_term_to_y && plc_calc_m == plc_term_to_m && plc_calc_d == plc_term_to_d) {
        plc_termCalcDone = true;
      } else {
        if (new Date(plc_calc_y +"-"+ plc_calc_m +"-"+ plc_calc_d).getDay() <= 4) {
          ++plc_times_work;
        } else {
          ++plc_times_week;
        }
        ++plc_calc_d;
        if (plc_calc_d > new Date(plc_calc_y, plc_calc_m, 0).getDate()) {
          plc_calc_d = 1;
          ++plc_calc_m;
          if (plc_calc_m > 12) {
            plc_calc_m = 1;
            ++plc_calc_y;
          }
        }
      }
    }
  }
  plcBookPrice(plc_times_work, plc_times_week);
}

var plc_total;
function plcBookPrice(plc_times_work, plc_times_week) {
  plcBookPriceHide();
  if (plc_term_from_y != "" && plc_term_from_y != null && plc_term_to_y != "" && plc_term_to_y != null) {
    document.getElementById("plc-calendar-footer").style.display = "table";
    if (document.getElementById("plc-calendar-footer-calc-all-days-times")) {
      plc_times_work = plc_times_work + plc_times_week;
      document.getElementById("plc-calendar-footer-calc-all-days-times").innerHTML = plc_times_work +"x";
      plc_total = plc_prc_work * plc_times_work;
    } else {
      if (plc_times_work != 0) {
        document.getElementById("plc-calendar-footer-calc-section-work").style.display = "flex";
        document.getElementById("plc-calendar-footer-calc-work-times").innerHTML = plc_times_work +"x";
      }
      if (plc_times_week != 0) {
        document.getElementById("plc-calendar-footer-calc-section-week").style.display = "flex";
        document.getElementById("plc-calendar-footer-calc-week-times").innerHTML = plc_times_week +"x";
      }
      if (plc_times_work != 0 && plc_times_week != 0) {
        document.getElementById("plc-calendar-footer-calc-marks-plus").style.display = "block";
      }
      plc_total = plc_prc_work * plc_times_work + plc_prc_week * plc_times_week;
    }
    document.getElementById("plc-calendar-footer-calc-total").innerHTML = addCurrency(plc_prc_currency, plc_total);
    if (document.getElementById("plc-calendar-operation")) {
      document.getElementById("plc-calendar-operation").style.marginTop = "10px";
    }
  }
}

function plcBookPriceHide() {
  document.getElementById("plc-calendar-footer").style.display = "";
  if (document.getElementById("plc-calendar-footer-calc-marks-plus")) {
    document.getElementById("plc-calendar-footer-calc-marks-plus").style.display = "";
  }
  if (document.getElementById("plc-calendar-footer-calc-section-work")) {
    document.getElementById("plc-calendar-footer-calc-section-work").style.display = "";
  }
  if (document.getElementById("plc-calendar-footer-calc-section-week")) {
    document.getElementById("plc-calendar-footer-calc-section-week").style.display = "";
  }
  if (document.getElementById("plc-calendar-operation")) {
    document.getElementById("plc-calendar-operation").style.marginTop = "";
  }
}

function plcBookSummaryModal(tsk) {
  bindingBookingErrorReset();
  if (tsk == "show") {
    if (plc_term_from_y != "" && plc_term_from_y != null && plc_term_to_y != "" && plc_term_to_y != null) {
      bookTerms("plc-calendar-wrp");
      modCover("show", "modal-cover-book-summary");
      bookSummaryData("place-calendar", plc_term_from_d, plc_term_from_m, plc_term_from_y, plc_term_to_d, plc_term_to_m, plc_term_to_y);
    } else {
      plcBookContinueHandler();
    }
  } else {
    bookSummaryModal('hide');
  }
}

var plcContinueBtnReady = true;
var plcContinueBtnRepete = false;
function plcBookContinueHandler() {
  if (plcContinueBtnReady) {
    plcContinueBtnReady = false;
    plcContinueBtnRepete = false;
    if (plc_term_from_y != "" && plc_term_from_y != null && plc_term_to_y != "" && plc_term_to_y != null) {
      plcBookTermsCalc();
      if (plcContinueBtnRepete) {
        plcBookContinueHandler();
      }
    } else {
      plcBookPriceHide()
      if (plcContinueBtnRepete) {
        plcBookContinueHandler();
      }
    }
  } else {
    plcContinueBtnRepete = true;
  }
}
