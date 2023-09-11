function calendarWrpResize(wrpId) {
  new ResizeSensor(document.getElementById(wrpId), function() {
    calendarSetSizes(wrpId);
  });
}

function calendarSetSizes(wrpId) {
  calendarSetSizeMainError(wrpId);
  calendarSetSizeSlider(wrpId);
  calendarSetSizeOneSlide(wrpId);
  calendarSliderScroll(wrpId, false);
  calendarTodaysRedDot(wrpId);
}

function calendarSetSizesWidth(wrpId) {
  if (calendarCheckIfSingleOrDouble(wrpId) == "dbl") {
    return (document.getElementById("cal-slider-"+ wrpId).offsetWidth / 2) - (7 + 7 / 2);
  } else {
    return document.getElementById("cal-slider-"+ wrpId).offsetWidth;
  }
}

function calendarSetSizeMainError(wrpId) {
  if (document.getElementById("cal-main-error-txt-"+ wrpId) && document.getElementById("cal-main-error-vertical-size-wrp-"+ wrpId) && document.getElementById("cal-content-wrp-"+ wrpId)) {
    document.getElementById("cal-main-error-txt-"+ wrpId).style.display = "none";
    calendarSetMonthSlideErrorToggle(wrpId, "none");
    document.getElementById("cal-main-error-vertical-size-wrp-"+ wrpId).style.maxHeight = (document.getElementById("cal-content-wrp-"+ wrpId).offsetHeight - 2 * 7) + "px";
    calendarSetMonthSlideErrorToggle(wrpId, "");
    document.getElementById("cal-main-error-txt-"+ wrpId).style.display = "";
  }
}

function calendarSetSizeSlider(wrpId) {
  if (document.getElementById("cal-slider-content-"+ wrpId) && document.getElementById("cal-slider-"+ wrpId)) {
    document.getElementById("cal-slider-content-"+ wrpId).style.display = "none";
    document.getElementById("cal-slider-"+ wrpId).style.maxWidth = "";
    document.getElementById("cal-slider-"+ wrpId).style.maxWidth = document.getElementById("cal-slider-"+ wrpId).offsetWidth + "px";
    document.getElementById("cal-slider-content-"+ wrpId).style.display = "";
  }
}

function calendarSetSizeOneSlide(wrpId) {
  calendarSetSizeArrowBtns(wrpId);
  calendarSetSizeOneMonth(wrpId);
  calendarSetSizeMonthSlideError(wrpId);
  calendarSetSizeDaysGrid(wrpId);
}

function calendarSetSizeArrowBtns(wrpId) {
  if (document.getElementById("cal-slider-arrow-btns-wrp-"+ wrpId) && document.getElementsByClassName("cal-o-m-month-year-wrp-"+ wrpId).length > 0) {
    document.getElementById("cal-slider-arrow-btns-wrp-"+ wrpId).style.height = document.getElementsByClassName("cal-o-m-month-year-wrp-"+ wrpId)[0].offsetHeight + "px";
  }
}

function calendarSetSizeOneMonth(wrpId) {
  if (document.getElementsByClassName("cal-o-m-size-"+ wrpId).length > 0 && document.getElementById("cal-slider-"+ wrpId)) {
    for (var b = 0; b < document.getElementsByClassName("cal-o-m-size-"+ wrpId).length; b++) {
      document.getElementsByClassName("cal-o-m-size-"+ wrpId)[b].style.width = calendarSetSizesWidth(wrpId) + "px";
    }
  }
}

function calendarSetSizeMonthSlideError(wrpId) {
  if (document.getElementsByClassName("cal-error-txt-"+ wrpId)[0] && document.getElementsByClassName("cal-o-m-error-vertical-size-"+ wrpId)[0] && document.getElementsByClassName("cal-o-m-content-wrp-"+ wrpId)[0]) {
    calendarSetMonthSlideErrorToggle(wrpId, "none");
    for (var eSV = 0; eSV < document.getElementsByClassName("cal-o-m-error-vertical-size-"+ wrpId).length; eSV++) {
      document.getElementsByClassName("cal-o-m-error-vertical-size-"+ wrpId)[eSV].style.maxHeight = (document.getElementsByClassName("cal-o-m-content-wrp-"+ wrpId)[0].offsetHeight - 7) + "px";
    }
    calendarSetMonthSlideErrorToggle(wrpId, "");
  }
}

function calendarSetMonthSlideErrorToggle(wrpId, displayVal) {
  for (var eS = 0; eS < document.getElementsByClassName("cal-error-txt-"+ wrpId).length; eS++) {
    document.getElementsByClassName("cal-error-txt-"+ wrpId)[eS].style.display = displayVal;
  }
}

function calendarSetSizeDaysGrid(wrpId) {
  if (document.getElementsByClassName("cal-o-m-grid-"+ wrpId).length > 0 && document.getElementById("cal-slider-"+ wrpId)) {
    for (var g = 0; g < document.getElementsByClassName("cal-o-m-grid-"+ wrpId).length; g++) {
      document.getElementsByClassName("cal-o-m-grid-"+ wrpId)[g].style.width = calendarSetSizesWidth(wrpId) + "px";
      document.getElementsByClassName("cal-o-m-grid-"+ wrpId)[g].style.height = (calendarSetSizesWidth(wrpId) / 7 * 6) + "px";
    }
  }
}

function calendarTodaysRedDot(wrpId) {
  if (document.getElementById(wrpId)) {
    if (document.getElementById(wrpId).offsetWidth >= 275 && (document.getElementById(wrpId).offsetWidth < 480 || document.getElementById(wrpId).offsetWidth > 560)) {
      document.getElementById(wrpId).classList.remove("cal-is-small");
    } else {
      document.getElementById(wrpId).classList.add("cal-is-small");
    }
  }
}
