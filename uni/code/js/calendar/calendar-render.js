function calendarRenderMainWrpAndAboutWrp(wrpId, type) {
  if (document.getElementById(wrpId)) {
    if (document.getElementById("cal-main-wrp-"+ wrpId)) {
      document.getElementById("cal-main-wrp-"+ wrpId).parentNode.removeChild(document.getElementById("cal-main-wrp-"+ wrpId));
    }
    var mainWrp = document.createElement("div");
    mainWrp.setAttribute("class", "cal-main-wrp");
    mainWrp.setAttribute("id", "cal-main-wrp-"+ wrpId);
    var aboutCalWrp = document.createElement("div");
    aboutCalWrp.setAttribute("class", "cal-about-calendar-wrp");
    // ID txt
    var aboutCalID = document.createElement("p");
    aboutCalID.setAttribute("class", "cal-about-calendar");
    aboutCalID.classList.add("cal-about-calendar-id");
    aboutCalID.setAttribute("id", "cal-about-calendar-id-"+ wrpId);
    aboutCalID.innerHTML = wrpId;
    // Type txt
    var aboutCalType = document.createElement("p");
    aboutCalType.setAttribute("class", "cal-about-calendar");
    aboutCalType.classList.add("cal-about-calendar-type");
    aboutCalType.setAttribute("id", "cal-about-calendar-type-"+ wrpId);
    aboutCalType.innerHTML = type;
    // This month txt
    var aboutCalThisMonth = document.createElement("p");
    aboutCalThisMonth.setAttribute("class", "cal-about-calendar");
    aboutCalThisMonth.classList.add("cal-about-calendar-this-month");
    aboutCalThisMonth.setAttribute("id", "cal-about-calendar-this-month-"+ wrpId);
    aboutCalThisMonth.innerHTML = new Date().getMonth() + 1;
    // This year txt
    var aboutCalThisYear = document.createElement("p");
    aboutCalThisYear.setAttribute("class", "cal-about-calendar");
    aboutCalThisYear.classList.add("cal-about-calendar-this-year");
    aboutCalThisYear.setAttribute("id", "cal-about-calendar-this-year-"+ wrpId);
    aboutCalThisYear.innerHTML = new Date().getFullYear();
    // calendar wrap
    var calendarWrp = document.createElement("div");
    calendarWrp.setAttribute("class", "cal-content-wrp");
    calendarWrp.setAttribute("id", "cal-content-wrp-"+ wrpId);
    mainWrp.appendChild(aboutCalWrp);
    aboutCalWrp.appendChild(aboutCalID);
    aboutCalWrp.appendChild(aboutCalType);
    aboutCalWrp.appendChild(aboutCalThisMonth);
    aboutCalWrp.appendChild(aboutCalThisYear);
    mainWrp.appendChild(calendarWrp);
    document.getElementById(wrpId).appendChild(mainWrp);
  }
}

function calendarRenderLoaderWrps(wrpId) {
  if (document.getElementById("cal-content-wrp-"+ wrpId)) {
    var calendarLoaderWrp = document.createElement("div");
    calendarLoaderWrp.setAttribute("class", "cal-main-loader-wrp");
    calendarLoaderWrp.classList.add("cal-cont-blocks");
    calendarLoaderWrp.setAttribute("id", "cal-main-loader-wrp-"+ wrpId);
    var calendarLoader = document.createElement("div");
    calendarLoader.setAttribute("class", "cal-main-loader");
    calendarLoaderWrp.appendChild(calendarLoader);
    document.getElementById("cal-content-wrp-"+ wrpId).appendChild(calendarLoaderWrp);
  }
}

function calendarRenderErrorWrps(wrpId) {
  if (document.getElementById("cal-content-wrp-"+ wrpId)) {
    var calendarErrorWrp = document.createElement("div");
    calendarErrorWrp.setAttribute("class", "cal-main-error-wrp");
    calendarErrorWrp.classList.add("cal-cont-blocks");
    calendarErrorWrp.setAttribute("id", "cal-main-error-wrp-"+ wrpId);
    var calendarErrorVerticalSizeWrp = document.createElement("div");
    calendarErrorVerticalSizeWrp.setAttribute("class", "cal-main-error-vertical-size-wrp");
    calendarErrorVerticalSizeWrp.setAttribute("id", "cal-main-error-vertical-size-wrp-"+ wrpId);
    var calendarErrorHorizontalSizeWrp = document.createElement("div");
    calendarErrorHorizontalSizeWrp.setAttribute("class", "cal-main-error-horizontal-size-wrp");
    var calendarErrorTxt = document.createElement("p");
    calendarErrorTxt.setAttribute("class", "cal-main-error-txt");
    calendarErrorTxt.classList.add("cal-error-txt");
    calendarErrorTxt.classList.add("cal-error-txt-"+ wrpId);
    calendarErrorTxt.setAttribute("id", "cal-main-error-txt-"+ wrpId);
    calendarErrorWrp.appendChild(calendarErrorVerticalSizeWrp);
    calendarErrorVerticalSizeWrp.appendChild(calendarErrorHorizontalSizeWrp);
    calendarErrorHorizontalSizeWrp.appendChild(calendarErrorTxt);
    document.getElementById("cal-content-wrp-"+ wrpId).appendChild(calendarErrorWrp);
  }
}

function calendarRenderSlider(wrpId, id) {
  if (document.getElementById("cal-content-wrp-"+ wrpId)) {
    var sliderWrp = document.createElement("div");
    sliderWrp.setAttribute("class", "cal-slider-wrp");
    sliderWrp.classList.add("cal-cont-blocks");
    sliderWrp.setAttribute("id", "cal-slider-wrp-"+ wrpId);
    var sliderSize = document.createElement("div");
    sliderSize.setAttribute("class", "cal-slider-size");
    var sliderArrowBtnsWrp = document.createElement("div");
    sliderArrowBtnsWrp.setAttribute("class", "cal-slider-arrow-btns-wrp");
    sliderArrowBtnsWrp.setAttribute("id", "cal-slider-arrow-btns-wrp-"+ wrpId);
    var sliderArrowBtnLeftWrp = document.createElement("div");
    sliderArrowBtnLeftWrp.setAttribute("class", "cal-slider-arrow-btn-site-wrp");
    sliderArrowBtnLeftWrp.classList.add("cal-slider-arrow-btn-site-wrp-"+ wrpId);
    sliderArrowBtnLeftWrp.classList.add("cal-slider-arrow-btn-left-wrp");
    var sliderArrowBtnLeft = document.createElement("button");
    sliderArrowBtnLeft.setAttribute("type", "button");
    sliderArrowBtnLeft.setAttribute("aria-label", "month before");
    sliderArrowBtnLeft.setAttribute("onclick", "calendarArrowBtnManager('"+ wrpId +"', '"+ id +"', 'previous')");
    sliderArrowBtnLeft.setAttribute("class", "cal-slider-arrow-btn");
    sliderArrowBtnLeft.classList.add("cal-slider-arrow-btn-left");
    var sliderArrowBtnRightWrp = document.createElement("div");
    sliderArrowBtnRightWrp.setAttribute("class", "cal-slider-arrow-btn-site-wrp");
    sliderArrowBtnRightWrp.classList.add("cal-slider-arrow-btn-site-wrp-"+ wrpId);
    sliderArrowBtnRightWrp.classList.add("cal-slider-arrow-btn-right-wrp");
    var sliderArrowBtnRight = document.createElement("button");
    sliderArrowBtnRight.setAttribute("type", "button");
    sliderArrowBtnRight.setAttribute("aria-label", "month next");
    sliderArrowBtnRight.setAttribute("onclick", "calendarArrowBtnManager('"+ wrpId +"', '"+ id +"', 'next')");
    sliderArrowBtnRight.setAttribute("class", "cal-slider-arrow-btn");
    sliderArrowBtnRight.classList.add("cal-slider-arrow-btn-right");
    var slider = document.createElement("div");
    slider.setAttribute("class", "cal-slider");
    slider.setAttribute("id", "cal-slider-"+ wrpId);
    var sliderContent = document.createElement("div");
    sliderContent.setAttribute("class", "cal-slider-content");
    sliderContent.setAttribute("id", "cal-slider-content-"+ wrpId);
    sliderWrp.appendChild(sliderSize);
    sliderSize.appendChild(sliderArrowBtnsWrp);
    sliderArrowBtnsWrp.appendChild(sliderArrowBtnLeftWrp);
    sliderArrowBtnLeftWrp.appendChild(sliderArrowBtnLeft);
    sliderArrowBtnsWrp.appendChild(sliderArrowBtnRightWrp);
    sliderArrowBtnRightWrp.appendChild(sliderArrowBtnRight);
    sliderSize.appendChild(slider);
    slider.appendChild(sliderContent);
    document.getElementById("cal-content-wrp-"+ wrpId).appendChild(sliderWrp);
  }
}

var wrd_loading = "Loading";
var wrd_monShrt = "Mon";
var wrd_tueShrt = "Tue";
var wrd_wedShrt = "Wed";
var wrd_thuShrt = "Thu";
var wrd_friShrt = "Fri";
var wrd_satShrt = "Sat";
var wrd_sunShrt = "Sun";
function calendarMonthSlideDictionaryRender(loading, monShrt, tueShrt, wedShrt, thuShrt, friShrt, satShrt, sunShrt) {
  wrd_loading = loading;
  wrd_monShrt = monShrt;
  wrd_tueShrt = tueShrt;
  wrd_wedShrt = wedShrt;
  wrd_thuShrt = thuShrt;
  wrd_friShrt = friShrt;
  wrd_satShrt = satShrt;
  wrd_sunShrt = sunShrt;
}

function calendarRenderMonthSlide(wrpId, month, year) {
  if (document.getElementById("cal-slider-content-"+ wrpId)) {
    if (document.getElementById("cal-o-m-blck-"+ wrpId +"-"+ month +"-"+ year)) {
      document.getElementById("cal-o-m-blck-"+ wrpId +"-"+ month +"-"+ year).parentNode.removeChild(document.getElementById("cal-o-m-blck-"+ wrpId +"-"+ month +"-"+ year));
    }
    var oneMonthBlck = document.createElement("div");
    oneMonthBlck.setAttribute("class", "cal-o-m-blck");
    oneMonthBlck.classList.add("cal-o-m-blck-"+ wrpId);
    oneMonthBlck.setAttribute("id", "cal-o-m-blck-"+ wrpId +"-"+ month +"-"+ year);
    var aboutSlideWrp = document.createElement("div");
    aboutSlideWrp.setAttribute("class", "cal-o-m-about-wrp");
    // Year txt
    var aboutSlideY = document.createElement("p");
    aboutSlideY.setAttribute("class", "cal-o-m-about-slide");
    aboutSlideY.classList.add("cal-o-m-about-slide-year-"+ wrpId);
    aboutSlideY.innerHTML = year;
    // Month txt
    var aboutSlideM = document.createElement("p");
    aboutSlideM.setAttribute("class", "cal-o-m-about-slide");
    aboutSlideM.classList.add("cal-o-m-about-slide-month-"+ wrpId);
    aboutSlideM.innerHTML = month;
    // Slide order num
    var aboutSlideOrder = document.createElement("p");
    aboutSlideOrder.setAttribute("class", "cal-o-m-about-slide");
    aboutSlideOrder.classList.add("cal-o-m-about-slide-order-"+ wrpId);
    aboutSlideOrder.innerHTML = year +""+ ("0" + month).slice(-2);

    var oneMonthSize = document.createElement("div");
    oneMonthSize.setAttribute("class", "cal-o-m-size");
    oneMonthSize.classList.add("cal-o-m-size-"+ wrpId);
    var monthYearWrp = document.createElement("div");
    monthYearWrp.setAttribute("class", "cal-o-m-month-year-wrp");
    monthYearWrp.classList.add("cal-o-m-month-year-wrp-"+ wrpId);
    var monthYearLayout = document.createElement("div");
    monthYearLayout.setAttribute("class", "cal-o-m-month-year-layout");
    var monthTxt = document.createElement("span");
    monthTxt.setAttribute("class", "cal-o-m-month-txt");
    monthTxt.setAttribute("id", "cal-o-m-month-txt-"+ wrpId +"-"+ month +"-"+ year);
    monthTxt.innerHTML = wrd_loading +"..";
    var yearTxt = document.createElement("span");
    yearTxt.setAttribute("class", "cal-o-m-year-txt");
    yearTxt.setAttribute("id", "cal-o-m-year-txt-"+ wrpId +"-"+ month +"-"+ year);
    var contentWrp = document.createElement("div");
    contentWrp.setAttribute("class", "cal-o-m-content-wrp");
    contentWrp.classList.add("cal-o-m-content-wrp-"+ wrpId);
    var contentLoader = document.createElement("div");
    contentLoader.setAttribute("class", "cal-o-m-content-blcks");
    contentLoader.classList.add("cal-o-m-content-loader");
    contentLoader.setAttribute("id", "cal-o-m-content-loader-"+ wrpId +"-"+ month +"-"+ year);
    var loader = document.createElement("div");
    loader.setAttribute("class", "cal-o-m-loader");
    var contentError = document.createElement("div");
    contentError.setAttribute("class", "cal-o-m-content-blcks");
    contentError.classList.add("cal-o-m-content-error");
    contentError.setAttribute("id", "cal-o-m-content-error-"+ wrpId +"-"+ month +"-"+ year);
    var errorVertical = document.createElement("div");
    errorVertical.setAttribute("class", "cal-o-m-error-vertical-size");
    errorVertical.classList.add("cal-o-m-error-vertical-size-"+ wrpId);
    errorVertical.setAttribute("id", "cal-o-m-error-vertical-size-"+ wrpId +"-"+ month +"-"+ year);
    var errorHorizontal = document.createElement("div");
    errorHorizontal.setAttribute("class", "cal-o-m-error-horizontal-size");
    var errorTxt = document.createElement("p");
    errorTxt.setAttribute("class", "cal-o-m-error-txt");
    errorTxt.classList.add("cal-error-txt");
    errorTxt.classList.add("cal-error-txt-"+ wrpId);
    errorTxt.setAttribute("id", "cal-o-m-error-txt-"+ wrpId +"-"+ month +"-"+ year);
    var contentDays = document.createElement("div");
    contentDays.setAttribute("class", "cal-o-m-content-blcks");
    contentDays.classList.add("cal-o-m-content-days");
    contentDays.setAttribute("id", "cal-o-m-content-days-"+ wrpId +"-"+ month +"-"+ year);
    var daysWrp = document.createElement("div");
    daysWrp.setAttribute("class", "cal-o-m-days-wrp");
    var daysShrtWrp = document.createElement("div");
    daysShrtWrp.setAttribute("class", "cal-o-m-days-shrt-wrp");
    contentDays.classList.add("cal-o-m-days-shrt-wrp-"+ wrpId);
    var daysShrtMon = document.createElement("p");
    daysShrtMon.setAttribute("class", "cal-o-m-days-name");
    daysShrtMon.classList.add("cal-o-m-days-name-"+ wrpId);
    daysShrtMon.classList.add("cal-o-m-days-shrt-mon-"+ wrpId +"-"+ month +"-"+ year);
    daysShrtMon.innerHTML = wrd_monShrt;
    var daysShrtTue = document.createElement("p");
    daysShrtTue.setAttribute("class", "cal-o-m-days-name");
    daysShrtTue.classList.add("cal-o-m-days-name-"+ wrpId);
    daysShrtTue.classList.add("cal-o-m-days-shrt-tue-"+ wrpId +"-"+ month +"-"+ year);
    daysShrtTue.innerHTML = wrd_tueShrt;
    var daysShrtWed = document.createElement("p");
    daysShrtWed.setAttribute("class", "cal-o-m-days-name");
    daysShrtWed.classList.add("cal-o-m-days-name-"+ wrpId);
    daysShrtWed.classList.add("cal-o-m-days-shrt-wed-"+ wrpId +"-"+ month +"-"+ year);
    daysShrtWed.innerHTML = wrd_wedShrt;
    var daysShrtThu = document.createElement("p");
    daysShrtThu.setAttribute("class", "cal-o-m-days-name");
    daysShrtThu.classList.add("cal-o-m-days-name-"+ wrpId);
    daysShrtThu.classList.add("cal-o-m-days-shrt-thu-"+ wrpId +"-"+ month +"-"+ year);
    daysShrtThu.innerHTML = wrd_thuShrt;
    var daysShrtFri = document.createElement("p");
    daysShrtFri.setAttribute("class", "cal-o-m-days-name");
    daysShrtFri.classList.add("cal-o-m-days-name-"+ wrpId);
    daysShrtFri.classList.add("cal-o-m-days-shrt-fri-"+ wrpId +"-"+ month +"-"+ year);
    daysShrtFri.innerHTML = wrd_friShrt;
    var daysShrtSat = document.createElement("p");
    daysShrtSat.setAttribute("class", "cal-o-m-days-name");
    daysShrtSat.classList.add("cal-o-m-days-name-"+ wrpId);
    daysShrtSat.classList.add("cal-o-m-days-shrt-sat-"+ wrpId +"-"+ month +"-"+ year);
    daysShrtSat.innerHTML = wrd_satShrt;
    var daysShrtSun = document.createElement("p");
    daysShrtSun.setAttribute("class", "cal-o-m-days-name");
    daysShrtSun.classList.add("cal-o-m-days-name-"+ wrpId);
    daysShrtSun.classList.add("cal-o-m-days-shrt-sun-"+ wrpId +"-"+ month +"-"+ year);
    daysShrtSun.innerHTML = wrd_sunShrt;
    var gridWrp = document.createElement("div");
    gridWrp.setAttribute("class", "cal-o-m-grid-wrp");
    var grid = document.createElement("div");
    grid.setAttribute("class", "cal-o-m-grid");
    grid.classList.add("cal-o-m-grid-"+ wrpId);
    grid.setAttribute("id", "cal-o-m-grid-"+ wrpId +"-"+ month +"-"+ year);
    oneMonthBlck.appendChild(aboutSlideWrp);
    aboutSlideWrp.appendChild(aboutSlideY);
    aboutSlideWrp.appendChild(aboutSlideM);
    aboutSlideWrp.appendChild(aboutSlideOrder);
    oneMonthBlck.appendChild(oneMonthSize);
    oneMonthSize.appendChild(monthYearWrp);
    monthYearWrp.appendChild(monthYearLayout);
    monthYearLayout.appendChild(monthTxt);
    monthYearLayout.appendChild(yearTxt);
    oneMonthSize.appendChild(contentWrp);
    contentWrp.appendChild(contentLoader);
    contentLoader.appendChild(loader);
    contentWrp.appendChild(contentError);
    contentError.appendChild(errorVertical);
    errorVertical.appendChild(errorHorizontal);
    errorHorizontal.appendChild(errorTxt);
    contentWrp.appendChild(contentDays);
    contentDays.appendChild(daysWrp);
    daysWrp.appendChild(daysShrtWrp);
    daysShrtWrp.appendChild(daysShrtMon);
    daysShrtWrp.appendChild(daysShrtTue);
    daysShrtWrp.appendChild(daysShrtWed);
    daysShrtWrp.appendChild(daysShrtThu);
    daysShrtWrp.appendChild(daysShrtFri);
    daysShrtWrp.appendChild(daysShrtSat);
    daysShrtWrp.appendChild(daysShrtSun);
    contentDays.appendChild(gridWrp);
    gridWrp.appendChild(grid);
    calendarSlideOrder(oneMonthBlck, wrpId, month, year);
  }
}

function calendarSlideOrder(oneMonthBlck, wrpId, month, year) {
  var slideN = 0;
  var slideOrderTask;
  if (document.getElementsByClassName("cal-o-m-blck-"+ wrpId).length > 0) {
    var orderDone = false;
    var thisOrderNum = year +""+ ("0" + month).slice(-2);
    while (!orderDone) {
      if (document.getElementsByClassName("cal-o-m-blck-"+ wrpId)[slideN]) {
        if (thisOrderNum < parseInt(document.getElementsByClassName("cal-o-m-about-slide-order-"+ wrpId)[slideN].textContent)) {
          slideOrderTask = "prepend";
          orderDone = true;
        } else {
          ++slideN;
        }
      } else {
        if (!document.getElementsByClassName("cal-o-m-blck-"+ wrpId)[slideN]) {
          slideOrderTask = "append";
          orderDone = true;
        } else {
          --slideN;
          slideOrderTask = "prepend";
          orderDone = true;
        }
      }
    }
  } else {
    slideOrderTask = "append";
  }
  if (slideOrderTask == "prepend") {
    document.getElementsByClassName("cal-o-m-blck-"+ wrpId)[slideN].parentNode.insertBefore(oneMonthBlck, document.getElementsByClassName("cal-o-m-blck-"+ wrpId)[slideN]);
  } else {
    document.getElementById("cal-slider-content-"+ wrpId).appendChild(oneMonthBlck);
  }
  calendarSetSizeOneSlide(wrpId);
  calendarBlankDayRender(wrpId, month, year);
}

function calendarBlankDayRender(wrpId, month, year) {
  if (document.getElementById("cal-o-m-grid-"+ wrpId +"-"+ month +"-"+ year)) {
    for (var bD = 0; bD < new Date(year, month -1, 0).getDay(); bD++) {
      var blankDay = calendarAboutDayRender(wrpId, "blank", month, year, "blank");
      document.getElementById("cal-o-m-grid-"+ wrpId +"-"+ month +"-"+ year).appendChild(blankDay);
    }
  }
}

function calendarDayRender(wrpId, category, day, month, year, sts, busyDay, paymentSts) {
  if (document.getElementById("cal-o-m-grid-"+ wrpId +"-"+ month +"-"+ year)) {
    if (document.getElementById("cal-o-d-btn-"+ wrpId +"-"+ day +"-"+ month +"-"+ year)) {
      document.getElementById("cal-o-d-btn-"+ wrpId +"-"+ day +"-"+ month +"-"+ year).parentNode.removeChild(document.getElementById("cal-o-d-btn-"+ wrpId +"-"+ day +"-"+ month +"-"+ year));
    }
    var oneDayBlck = calendarAboutDayRender(wrpId, day, month, year, sts);
    var oneDayBtn = document.createElement("button");
    oneDayBtn.setAttribute("type", "button");
    oneDayBtn.setAttribute("class", "cal-o-d-btn");
    oneDayBtn.classList.add("cal-o-d-btn-"+ wrpId);
    if (busyDay == "no") {
      oneDayBtn.classList.add("cal-o-d-btn-"+ sts);
    } else {
      if (sts == "reserved") {
        oneDayBtn.classList.add("cal-o-d-btn-reserved-firsthalf-only");
        oneDayBtn.classList.add("cal-o-d-btn-reserved-secondhalf-only");
      } else {
        oneDayBtn.classList.add("cal-o-d-btn-"+ sts);
      }
    }
    oneDayBtn.setAttribute("id", "cal-o-d-btn-"+ wrpId +"-"+ day +"-"+ month +"-"+ year);
    if (sts == "reserved" || sts == "reserved-firstday" || sts == "reserved-lastday" || sts == "reserved-firsthalf-only" || sts == "reserved-secondhalf-only" || sts == "not-reserved") {
      if (new Date(year +"/"+ month +"/"+ day).setHours(0,0,0,0) < new Date().setHours(0,0,0,0)) {
        oneDayBtn.classList.add("cal-o-d-btn-host-mode-past");
      }
    }
    if (sts == "limited-firsthalf" || sts == "limited-secondhalf") {
      oneDayBtn.classList.add("cal-o-d-btn-"+ sts +"-"+ wrpId);
    }
    if (calendarGetFromToValue(wrpId, "from_d") == day && calendarGetFromToValue(wrpId, "from_m") == month && calendarGetFromToValue(wrpId, "from_y") == year) {
      oneDayBtn.classList.add("cal-o-d-btn-from");
      if (calendarGetFromToValue(wrpId, "to_d") != null && calendarGetFromToValue(wrpId, "to_m") != null && calendarGetFromToValue(wrpId, "to_y") != null) {
        oneDayBtn.classList.add("cal-o-d-btn-day-between-from");
      }
    }
    if (calendarGetFromToValue(wrpId, "to_d") == day && calendarGetFromToValue(wrpId, "to_m") == month && calendarGetFromToValue(wrpId, "to_y") == year) {
      oneDayBtn.classList.add("cal-o-d-btn-to");
      if (calendarGetFromToValue(wrpId, "from_d") != null && calendarGetFromToValue(wrpId, "from_m") != null && calendarGetFromToValue(wrpId, "from_y") != null) {
        oneDayBtn.classList.add("cal-o-d-btn-day-between-to");
      }
    }
    if (calendarCheckIfDayIsBetween(wrpId, year, month, day)) {
      oneDayBtn.classList.add("cal-o-d-btn-day-between");
    }
    if (sts != "blank" && sts != "unavailable") {
      oneDayBtn.setAttribute("onclick", "calendarDayOnclick('"+ wrpId +"', "+ year +", "+ month +", "+ day +")");
      oneDayBtn.setAttribute("onmouseover", "calendarDayHover('"+ wrpId +"', "+ year +", "+ month +", "+ day +", 'over')");
      oneDayBtn.setAttribute("onmouseleave", "calendarDayHover('"+ wrpId +"', "+ year +", "+ month +", "+ day +", 'leave')");
    }
    var oneDayVerticalCenter = document.createElement("div");
    oneDayVerticalCenter.setAttribute("class", "cal-o-d-vertical-center");
    var oneDaySizeHeight = document.createElement("div");
    oneDaySizeHeight.setAttribute("class", "cal-o-d-size-height");
    // day track - 2 parts
    var oneDayTrack2Parts = document.createElement("div");
    oneDayTrack2Parts.setAttribute("class", "cal-o-d-track-wrp");
    oneDayTrack2Parts.classList.add("cal-o-d-track-two-parts-wrp");
    var oneDayTrack2Parts_part1 = document.createElement("div");
    oneDayTrack2Parts_part1.setAttribute("class", "cal-o-d-track-parts");
    oneDayTrack2Parts_part1.classList.add("cal-o-d-track-parts-two-first");
    if (sts == "reserved" || sts == "reserved-firsthalf-only") {
      oneDayTrack2Parts_part1.classList.add("cal-o-d-track-part-"+ wrpId);
      if (busyDay == "no") {
        oneDayTrack2Parts_part1.classList.add("cal-o-d-track-part-"+ paymentSts +"-"+ wrpId);
      } else {
        if (paymentSts == "fullAmount-fullAmount" || paymentSts == "deposit-fullAmount" || paymentSts == "none-fullAmount") {
          oneDayTrack2Parts_part1.classList.add("cal-o-d-track-part-fullAmount-"+ wrpId);
        } else if (paymentSts == "fullAmount-deposit" || paymentSts == "deposit-deposit" || paymentSts == "none-deposit") {
          oneDayTrack2Parts_part1.classList.add("cal-o-d-track-part-deposit-"+ wrpId);
        } else {
          oneDayTrack2Parts_part1.classList.add("cal-o-d-track-part-none-"+ wrpId);
        }
      }
      if (sts == "reserved-firsthalf-only" || busyDay == "yes") {
        oneDayTrack2Parts_part1.classList.add("cal-o-d-track-part-rightside-rounded");
      }
    }
    var oneDayTrack2Parts_part2 = document.createElement("div");
    oneDayTrack2Parts_part2.setAttribute("class", "cal-o-d-track-parts");
    oneDayTrack2Parts_part2.classList.add("cal-o-d-track-parts-two-second");
    if (sts == "reserved" || sts == "reserved-secondhalf-only") {
      oneDayTrack2Parts_part2.classList.add("cal-o-d-track-part-"+ wrpId);
      if (busyDay == "no") {
        oneDayTrack2Parts_part2.classList.add("cal-o-d-track-part-"+ paymentSts +"-"+ wrpId);
      } else {
        if (paymentSts == "fullAmount-fullAmount" || paymentSts == "fullAmount-deposit" || paymentSts == "fullAmount-none") {
          oneDayTrack2Parts_part2.classList.add("cal-o-d-track-part-fullAmount-"+ wrpId);
        } else if (paymentSts == "deposit-fullAmount" || paymentSts == "deposit-deposit" || paymentSts == "deposit-none") {
          oneDayTrack2Parts_part2.classList.add("cal-o-d-track-part-deposit-"+ wrpId);
        } else {
          oneDayTrack2Parts_part2.classList.add("cal-o-d-track-part-none-"+ wrpId);
        }
      }
      if (sts == "reserved-secondhalf-only" || busyDay == "yes") {
        oneDayTrack2Parts_part2.classList.add("cal-o-d-track-part-leftside-rounded");
      }
    }
    // day track - 1 part
    var oneDayTrack1Parts = document.createElement("div");
    oneDayTrack1Parts.setAttribute("class", "cal-o-d-track-wrp");
    var oneDayTrack1Parts_part1 = document.createElement("div");
    oneDayTrack1Parts_part1.setAttribute("class", "cal-o-d-track-parts");
    oneDayTrack1Parts_part1.classList.add("cal-o-d-track-parts-one");
    if (sts == "reserved" || sts == "reserved-firstday" || sts == "reserved-lastday") {
      oneDayTrack1Parts_part1.classList.add("cal-o-d-track-part-"+ wrpId);
      oneDayTrack1Parts_part1.classList.add("cal-o-d-track-part-"+ paymentSts +"-"+ wrpId);
      if (sts == "reserved-firstday") {
        oneDayTrack1Parts_part1.classList.add("cal-o-d-track-part-leftside-rounded");
      } else if (sts == "reserved-lastday") {
        oneDayTrack1Parts_part1.classList.add("cal-o-d-track-part-rightside-rounded");
      }
    }
    var oneDayCircleWrp = document.createElement("div");
    oneDayCircleWrp.setAttribute("class", "cal-o-d-circle-wrp");
    var oneDayCircle = document.createElement("div");
    oneDayCircle.setAttribute("class", "cal-o-d-circle");
    oneDayCircle.classList.add("cal-o-d-circle-"+ wrpId +"-"+ sts);
    oneDayCircle.classList.add("cal-o-d-circle-"+ wrpId);
    var oneDayNumWrp = document.createElement("div");
    oneDayNumWrp.setAttribute("class", "cal-o-d-num-wrp");
    if (day == new Date().getDate() && month == new Date().getMonth() + 1 && year == new Date().getFullYear()) {
      if (sts == "reserved" || sts == "reserved-firstday" || sts == "reserved-lastday") {
        oneDayNumWrp.classList.add("cal-o-d-num-wrp-reserved");
      }
    }
    var oneDayNum = document.createElement("p");
    oneDayNum.setAttribute("class", "cal-o-d-num");
    oneDayNum.setAttribute("id", "cal-o-d-num-"+ wrpId +"-"+ day +"-"+ month +"-"+ year);
    oneDayNum.innerHTML = day;
    if (day == new Date().getDate() && month == new Date().getMonth() + 1 && year == new Date().getFullYear()) {
      oneDayNum.classList.add("cal-o-d-num-today");
      var redDot = document.createElement("div");
      redDot.setAttribute("class", "cal-o-d-red-dot");
      redDot.setAttribute("id", "cal-o-d-red-dot-"+ wrpId +"-"+ day +"-"+ month +"-"+ year);
    }
    oneDayBlck.appendChild(oneDayBtn);
    oneDayBtn.appendChild(oneDayVerticalCenter);
    oneDayVerticalCenter.appendChild(oneDaySizeHeight);
    oneDaySizeHeight.appendChild(oneDayTrack2Parts);
    oneDayTrack2Parts.appendChild(oneDayTrack2Parts_part1);
    oneDayTrack2Parts.appendChild(oneDayTrack2Parts_part2);
    oneDaySizeHeight.appendChild(oneDayTrack1Parts);
    oneDayTrack1Parts.appendChild(oneDayTrack1Parts_part1);
    oneDaySizeHeight.appendChild(oneDayCircleWrp);
    oneDayCircleWrp.appendChild(oneDayCircle);
    oneDayCircle.appendChild(oneDayNumWrp);
    oneDayNumWrp.appendChild(oneDayNum);
    if (day == new Date().getDate() && month == new Date().getMonth() + 1 && year == new Date().getFullYear()) {
      oneDayNumWrp.appendChild(redDot);
    }
    document.getElementById("cal-o-m-grid-"+ wrpId +"-"+ month +"-"+ year).appendChild(oneDayBlck);
    calendardayStatus(wrpId, year, month, day, sts);
  }
}

function calendarAboutDayRender(wrpId, day, month, year, sts) {
  var oDBlck = document.createElement("div");
  oDBlck.setAttribute("class", "cal-o-d-blck");
  oDBlck.classList.add("cal-o-d-blck-blank-"+ wrpId +"-"+ month +"-"+ year);
  var oDAboutWrp = document.createElement("div");
  oDAboutWrp.setAttribute("class", "cal-o-d-about-wrp");
  // Year txt
  var oDAboutY = document.createElement("p");
  oDAboutY.setAttribute("class", "cal-o-d-about-block");
  oDAboutY.classList.add("cal-o-d-about-block-year-"+ wrpId);
  oDAboutY.innerHTML = year;
  // Month txt
  var oDAboutM = document.createElement("p");
  oDAboutM.setAttribute("class", "cal-o-d-about-block");
  oDAboutM.classList.add("cal-o-d-about-block-month-"+ wrpId);
  oDAboutM.innerHTML = month;
  // Day txt
  var oDAboutD = document.createElement("p");
  oDAboutD.setAttribute("class", "cal-o-d-about-block");
  oDAboutD.classList.add("cal-o-d-about-block-day-"+ wrpId);
  oDAboutD.innerHTML = day;
  // Status txt
  var oDAboutSts = document.createElement("p");
  oDAboutSts.setAttribute("class", "cal-o-d-about-block");
  oDAboutSts.classList.add("cal-o-d-about-block-status-"+ wrpId);
  oDAboutSts.setAttribute("id", "cal-o-d-about-block-status-"+ wrpId +"-"+ day +"-"+ month +"-"+ year);
  oDAboutSts.innerHTML = sts;

  oDBlck.appendChild(oDAboutWrp);
  oDAboutWrp.appendChild(oDAboutY);
  oDAboutWrp.appendChild(oDAboutM);
  oDAboutWrp.appendChild(oDAboutD);
  oDAboutWrp.appendChild(oDAboutSts);
  return oDBlck;
}
