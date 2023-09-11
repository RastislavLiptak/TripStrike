function calendarRender(wrpId, id, type) {
  calendarWrpResize(wrpId);
  calendarRenderMainWrpAndAboutWrp(wrpId, type);
  calendarRenderLoaderWrps(wrpId);
  calendarRenderErrorWrps(wrpId);
  calendarRenderSlider(wrpId, id);
  calendarRenderMonthSlide(wrpId, 0, 0);
  calendarSetSizes(wrpId);
  calendarDictionaryLoader(wrpId, id, type);
  calendarFromTo(wrpId);
}

function calendarCheckIfSingleOrDouble(wrpId) {
  if (document.getElementById(wrpId).offsetWidth >= 480) {
    return "dbl";
  } else {
    return "sngl";
  }
}

function calendarMainContentHandler(wrpId, cont) {
  if (cont == "content") {
    document.getElementById("cal-main-loader-wrp-"+ wrpId).style.display = "none";
    document.getElementById("cal-main-error-wrp-"+ wrpId).style.display = "";
    document.getElementById("cal-slider-wrp-"+ wrpId).style.visibility = "visible";
  } else if (cont == "error") {
    document.getElementById("cal-main-loader-wrp-"+ wrpId).style.display = "none";
    document.getElementById("cal-main-error-wrp-"+ wrpId).style.display = "flex";
    document.getElementById("cal-slider-wrp-"+ wrpId).style.visibility = "";
  } else {
    document.getElementById("cal-main-loader-wrp-"+ wrpId).style.display = "";
    document.getElementById("cal-main-error-wrp-"+ wrpId).style.display = "";
    document.getElementById("cal-slider-wrp-"+ wrpId).style.visibility = "";
  }
}

function calendarSlideContentHandler(wrpId, y, m, cont) {
  if (cont == "content") {
    document.getElementById("cal-o-m-content-loader-"+ wrpId +"-"+ m +"-"+ y).style.display = "none";
    document.getElementById("cal-o-m-content-error-"+ wrpId +"-"+ m +"-"+ y).style.display = "";
    document.getElementById("cal-o-m-content-days-"+ wrpId +"-"+ m +"-"+ y).style.visibility = "visible";
  } else if (cont == "error") {
    document.getElementById("cal-o-m-content-loader-"+ wrpId +"-"+ m +"-"+ y).style.display = "none";
    document.getElementById("cal-o-m-content-error-"+ wrpId +"-"+ m +"-"+ y).style.display = "flex";
    document.getElementById("cal-o-m-content-days-"+ wrpId +"-"+ m +"-"+ y).style.visibility = "";
  } else {
    document.getElementById("cal-o-m-content-loader-"+ wrpId +"-"+ m +"-"+ y).style.display = "";
    document.getElementById("cal-o-m-content-error-"+ wrpId +"-"+ m +"-"+ y).style.display = "";
    document.getElementById("cal-o-m-content-days-"+ wrpId +"-"+ m +"-"+ y).style.visibility = "";
  }
}

function calendarDeleteSlide(wrpId, y, m) {
  document.getElementById("cal-o-m-blck-"+ wrpId +"-"+ m +"-"+ y).parentNode.removeChild(document.getElementById("cal-o-m-blck-"+ wrpId +"-"+ m +"-"+ y));
}

var calendarSlideNum;
function calendarMonthLoaderSlidesManager(wrpId, m, y, scrollTsk) {
  calendarRenderMonthSlide(wrpId, m, y);
  calendarSlideNum = 0
  while (document.getElementsByClassName("cal-o-m-blck-"+ wrpId).length > calendarSlideNum) {
    if (
      (parseInt(document.getElementsByClassName("cal-o-m-about-slide-year-"+ wrpId)[calendarSlideNum].textContent) == calcPreviousYearByMonth(parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent), parseInt(document.getElementById("cal-about-calendar-this-year-"+ wrpId).textContent), 3) && parseInt(document.getElementsByClassName("cal-o-m-about-slide-month-"+ wrpId)[calendarSlideNum].textContent) == calcPreviousMonth(parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent), 3)) ||
      (parseInt(document.getElementsByClassName("cal-o-m-about-slide-year-"+ wrpId)[calendarSlideNum].textContent) == calcPreviousYearByMonth(parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent), parseInt(document.getElementById("cal-about-calendar-this-year-"+ wrpId).textContent), 2) && parseInt(document.getElementsByClassName("cal-o-m-about-slide-month-"+ wrpId)[calendarSlideNum].textContent) == calcPreviousMonth(parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent), 2)) ||
      (parseInt(document.getElementsByClassName("cal-o-m-about-slide-year-"+ wrpId)[calendarSlideNum].textContent) == calcPreviousYearByMonth(parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent), parseInt(document.getElementById("cal-about-calendar-this-year-"+ wrpId).textContent), 1) && parseInt(document.getElementsByClassName("cal-o-m-about-slide-month-"+ wrpId)[calendarSlideNum].textContent) == calcPreviousMonth(parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent), 1)) ||
      (parseInt(document.getElementsByClassName("cal-o-m-about-slide-year-"+ wrpId)[calendarSlideNum].textContent) == parseInt(document.getElementById("cal-about-calendar-this-year-"+ wrpId).textContent) && parseInt(document.getElementsByClassName("cal-o-m-about-slide-month-"+ wrpId)[calendarSlideNum].textContent) == parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent)) ||
      (parseInt(document.getElementsByClassName("cal-o-m-about-slide-year-"+ wrpId)[calendarSlideNum].textContent) == calcNextYearByMonth(parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent), parseInt(document.getElementById("cal-about-calendar-this-year-"+ wrpId).textContent), 1) && parseInt(document.getElementsByClassName("cal-o-m-about-slide-month-"+ wrpId)[calendarSlideNum].textContent) == calcNextMonth(parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent), 1)) ||
      (parseInt(document.getElementsByClassName("cal-o-m-about-slide-year-"+ wrpId)[calendarSlideNum].textContent) == calcNextYearByMonth(parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent), parseInt(document.getElementById("cal-about-calendar-this-year-"+ wrpId).textContent), 2) && parseInt(document.getElementsByClassName("cal-o-m-about-slide-month-"+ wrpId)[calendarSlideNum].textContent) == calcNextMonth(parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent), 2)) ||
      (parseInt(document.getElementsByClassName("cal-o-m-about-slide-year-"+ wrpId)[calendarSlideNum].textContent) == calcNextYearByMonth(parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent), parseInt(document.getElementById("cal-about-calendar-this-year-"+ wrpId).textContent), 3) && parseInt(document.getElementsByClassName("cal-o-m-about-slide-month-"+ wrpId)[calendarSlideNum].textContent) == calcNextMonth(parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent), 3))
    ) {
      ++calendarSlideNum;
    } else {
      calendarDeleteSlide(wrpId, parseInt(document.getElementsByClassName("cal-o-m-about-slide-year-"+ wrpId)[calendarSlideNum].textContent), parseInt(document.getElementsByClassName("cal-o-m-about-slide-month-"+ wrpId)[calendarSlideNum].textContent));
    }
  }
  calendarSliderScroll(wrpId, false);
  if (scrollTsk == "previous") {
    document.getElementById("cal-about-calendar-this-year-"+ wrpId).innerHTML = calcPreviousYearByMonth(parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent), parseInt(document.getElementById("cal-about-calendar-this-year-"+ wrpId).textContent), 1);
    document.getElementById("cal-about-calendar-this-month-"+ wrpId).innerHTML = calcPreviousMonth(parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent), 1);
  } else if (scrollTsk == "next") {
    document.getElementById("cal-about-calendar-this-year-"+ wrpId).innerHTML = calcNextYearByMonth(parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent), parseInt(document.getElementById("cal-about-calendar-this-year-"+ wrpId).textContent), 1);
    document.getElementById("cal-about-calendar-this-month-"+ wrpId).innerHTML = calcNextMonth(parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent), 1);
  }
  if (scrollTsk != "none") {
    calendarSliderScroll(wrpId, true);
  }
}

var scrolledSliderYear, scrolledSliderMonth, calendarSlidesBefore, calendarSlidesBeforeCalcDone, calendarScrollTo;
function calendarSliderScroll(wrpId, wAnimation) {
  scrolledSliderYear = document.getElementById("cal-about-calendar-this-year-"+ wrpId).textContent;
  scrolledSliderMonth = document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent;
  calendarSlidesBefore = 0;
  calendarSlidesBeforeCalcDone = false;
  while (!calendarSlidesBeforeCalcDone && document.getElementsByClassName("cal-o-m-blck-"+ wrpId).length > calendarSlidesBefore) {
    if (scrolledSliderYear == document.getElementsByClassName("cal-o-m-about-slide-year-"+ wrpId)[calendarSlidesBefore].textContent && scrolledSliderMonth == document.getElementsByClassName("cal-o-m-about-slide-month-"+ wrpId)[calendarSlidesBefore].textContent) {
      calendarSlidesBeforeCalcDone = true;
    } else {
      ++calendarSlidesBefore;
    }
  }
  if (document.getElementsByClassName("cal-o-m-blck-"+ wrpId).length == calendarSlidesBefore) {
    calendarSlidesBefore = 0;
  }
  calendarScrollTo = document.getElementsByClassName("cal-o-m-blck-"+ wrpId)[0].offsetWidth * calendarSlidesBefore;
  calendarScrollTo = calendarScrollTo + ((document.getElementsByClassName("cal-o-m-blck-"+ wrpId)[0].offsetWidth - document.getElementsByClassName("cal-o-m-size-"+ wrpId)[0].offsetWidth) / 2);
  if (wAnimation) {
    scrollTo(document.getElementById("cal-slider-"+ wrpId), calendarScrollTo, 250);
  } else {
    document.getElementById("cal-slider-"+ wrpId).scrollLeft = calendarScrollTo;
  }
  calendarSliderSlidesOpacityManager(wrpId);
}

var currentSlideY, currentSlideM, nextSlideY, nextSlideM;
function calendarSliderSlidesOpacityManager(wrpId) {
  document.getElementsByClassName("cal-o-m-blck-"+ wrpId)
  for (var sO = 0; sO < document.getElementsByClassName("cal-o-m-blck-"+ wrpId).length; sO++) {
    document.getElementsByClassName("cal-o-m-blck-"+ wrpId)[sO].style.opacity = "";
  }
  currentSlideY = parseInt(document.getElementById("cal-about-calendar-this-year-"+ wrpId).textContent);
  currentSlideM = parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent);
  if (document.getElementById("cal-o-m-blck-"+ wrpId +"-"+ currentSlideM +"-"+ currentSlideY)) {
    document.getElementById("cal-o-m-blck-"+ wrpId +"-"+ currentSlideM +"-"+ currentSlideY).style.opacity = "1";
  }
  if (calendarCheckIfSingleOrDouble(wrpId) == "dbl") {
    nextSlideY = calcNextYearByMonth(currentSlideM, currentSlideY, 1)
    nextSlideM = calcNextMonth(currentSlideM, 1);
    if (document.getElementById("cal-o-m-blck-"+ wrpId +"-"+ nextSlideM +"-"+ nextSlideY)) {
      document.getElementById("cal-o-m-blck-"+ wrpId +"-"+ nextSlideM +"-"+ nextSlideY).style.opacity = "1";
    }
  }
}

var calendarAddMonth, calendarAddYear;
function calendarArrowBtnManager(wrpId, id, tsk) {
  if (tsk == "previous") {
    calendarAddMonth = calcPreviousMonth(parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent), 3);
    calendarAddYear = calcPreviousYearByMonth(parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent), parseInt(document.getElementById("cal-about-calendar-this-year-"+ wrpId).textContent), 3);
  } else {
    calendarAddMonth = calcNextMonth(parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent), 3);
    calendarAddYear = calcNextYearByMonth(parseInt(document.getElementById("cal-about-calendar-this-month-"+ wrpId).textContent), parseInt(document.getElementById("cal-about-calendar-this-year-"+ wrpId).textContent), 3);
  }
  calendarMonthDataLoader(wrpId, id, calendarAddMonth, calendarAddYear, document.getElementById("cal-about-calendar-type-"+ wrpId).textContent, tsk);
}

var wrd_error = "Error";
var wrd_january = "January";
var wrd_february = "February";
var wrd_march = "March";
var wrd_april = "April";
var wrd_may = "May";
var wrd_june = "June";
var wrd_july = "July";
var wrd_august = "August";
var wrd_september = "September";
var wrd_october = "October";
var wrd_november = "November";
var wrd_december = "December";
function calendarMonthSlideDictionaryUpdate(error, january, february, march, april, may, june, july, august, september, october, november, december) {
  wrd_error = error;
  wrd_january = january;
  wrd_february = february;
  wrd_march = march;
  wrd_april = april;
  wrd_may = may;
  wrd_june = june;
  wrd_july = july;
  wrd_august = august;
  wrd_september = september;
  wrd_october = october;
  wrd_november = november;
  wrd_december = december;
}

var slideHeaderTxt;
function calendarMonthSlideHeaderTxt(wrpId, year, month, txt) {
  if (txt == "error") {
    slideHeaderTxt = wrd_error;
  } else if (txt == 1) {
    slideHeaderTxt = wrd_january;
  } else if (txt == 2) {
    slideHeaderTxt = wrd_february;
  } else if (txt == 3) {
    slideHeaderTxt = wrd_march;
  } else if (txt == 4) {
    slideHeaderTxt = wrd_april;
  } else if (txt == 5) {
    slideHeaderTxt = wrd_may;
  } else if (txt == 6) {
    slideHeaderTxt = wrd_june;
  } else if (txt == 7) {
    slideHeaderTxt = wrd_july;
  } else if (txt == 8) {
    slideHeaderTxt = wrd_august;
  } else if (txt == 9) {
    slideHeaderTxt = wrd_september;
  } else if (txt == 10) {
    slideHeaderTxt = wrd_october;
  } else if (txt == 11) {
    slideHeaderTxt = wrd_november;
  } else if (txt == 12) {
    slideHeaderTxt = wrd_december;
  }
  if (document.getElementById("cal-o-m-month-txt-"+ wrpId +"-"+ month +"-"+ year)) {
    document.getElementById("cal-o-m-month-txt-"+ wrpId +"-"+ month +"-"+ year).innerHTML = slideHeaderTxt;
  }
}

function calendarOneDayOnclickOutput(wrpId) {
  if (wrpId == "book-calendar-wrp") {
    bookTerms(wrpId);
  } else if (wrpId == "plc-calendar-wrp") {
    plcBookTerms(wrpId);
  } else if (wrpId == "editor-content-calendar-blck") {
    editorCalendarDaySelect(wrpId);
  } else if (wrpId == "editor-calendar") {
    editorSelectedDate(wrpId);
  } else if (wrpId == "c-d-b-add-new-calendar") {
    editorAddBookingSelectDate(wrpId);
  } else if (wrpId == "e-c-b-c-m-calendar-technical-shutdown") {
    editorTechnicalShutdownCalChange(wrpId);
  }
}
