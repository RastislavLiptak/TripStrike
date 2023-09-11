var daysStatusObject = {};
function calendardayStatus(wrpId, y, m, d, sts) {
  if (wrpId in daysStatusObject) {
    daysStatusObject[wrpId][d +"-"+ m +"-"+ y] = sts;
  } else {
    daysStatusObject[wrpId] = {
      [d +"-"+ m +"-"+ y]: sts
    };
  }
}

var fromToObject = {};
function calendarFromTo(wrpId) {
  if (wrpId in fromToObject) {
    fromToObject[wrpId]['from_d'] = null;
    fromToObject[wrpId]['from_m'] = null;
    fromToObject[wrpId]['from_y'] = null;
    fromToObject[wrpId]['to_d'] = null;
    fromToObject[wrpId]['to_m'] = null;
    fromToObject[wrpId]['to_y'] = null;
  } else {
    fromToObject[wrpId] = {
      from_d: null,
      from_m: null,
      from_y: null,
      to_d: null,
      to_m: null,
      to_y: null
    };
  }
}

function calendarGetFromToValue(wrpId, get) {
  if (get == "from_d") {
    return fromToObject[wrpId]['from_d'];
  } else if (get == "from_m") {
    return fromToObject[wrpId]['from_m'];
  } else if (get == "from_y") {
    return fromToObject[wrpId]['from_y'];
  } else if (get == "to_d") {
    return fromToObject[wrpId]['to_d'];
  } else if (get == "to_m") {
    return fromToObject[wrpId]['to_m'];
  } else if (get == "to_y") {
    return fromToObject[wrpId]['to_y'];
  }
}

var f_d, f_m, f_y, t_d, t_m, t_y, makeChanges;
var dayDblReady = false;
var dayDblClick, dayDblDate, dayDblClickTime;
function calendarDayOnclick(wrpId, y, m, d) {
  dayDblClick = false;
  if (document.getElementById("cal-o-d-about-block-status-"+ wrpId +"-"+ d +"-"+ m +"-"+ y).textContent != "unavailable") {
    if (dayDblReady && dayDblDate == d +"-"+ m +"-"+ y) {
      dayDblClick = true;
      dayDblReady = false;
      dayDblDate = null;
      clearTimeout(dayDblClickTime);
    } else {
      dayDblReady = true;
      dayDblDate = d +"-"+ m +"-"+ y;
      clearTimeout(dayDblClickTime);
      dayDblClickTime = setTimeout(function(){
        dayDblReady = false;
        dayDblDate = null;
      }, 350);
    }
    if (document.getElementById("cal-about-calendar-type-"+ wrpId).textContent == "host-view-limited-select") {
      f_d = d;
      f_m = m;
      f_y = y;
      t_d = null;
      t_m = null;
      t_y = null;
      makeChanges = true;
    } else {
      f_d = fromToObject[wrpId]['from_d'];
      f_m = fromToObject[wrpId]['from_m'];
      f_y = fromToObject[wrpId]['from_y'];
      t_d = fromToObject[wrpId]['to_d'];
      t_m = fromToObject[wrpId]['to_m'];
      t_y = fromToObject[wrpId]['to_y'];
      if (f_d == d && f_m == m && f_y == y) {
        f_d = null;
        f_m = null;
        f_y = null;
      } else if ((t_d == d && t_m == m && t_y == y) || (f_d == null && f_m == null && f_y == null) || (new Date(f_y +"/"+ f_m +"/"+ f_d).setHours(0,0,0,0) > new Date(y +"/"+ m +"/"+ d).setHours(0,0,0,0))) {
        f_d = d;
        f_m = m;
        f_y = y;
      } else {
        t_d = d;
        t_m = m;
        t_y = y;
      }
      calendarFromToManager(wrpId);
      makeChanges = calendarCheckDaysBetweenFromTo(wrpId);
      if (!makeChanges && dayDblClick) {
        f_d = d;
        f_m = m;
        f_y = y;
        t_d = null;
        t_m = null;
        t_y = null;
        makeChanges = true;
      }
    }
    if (makeChanges) {
      fromToObject[wrpId]['from_d'] = f_d;
      fromToObject[wrpId]['from_m'] = f_m;
      fromToObject[wrpId]['from_y'] = f_y;
      fromToObject[wrpId]['to_d'] = t_d;
      fromToObject[wrpId]['to_m'] = t_m;
      fromToObject[wrpId]['to_y'] = t_y;
      calendarMarkFromTo(wrpId);
      calendarMarkDaysBetween(wrpId);
    }
    calendarOneDayOnclickOutput(wrpId);
  }
}

function calendarFromToManager(wrpId) {
  if (f_d != null && f_m != null && f_y != null && t_d != null && t_m != null && t_y != null) {
    if (new Date(f_y +"/"+ f_m +"/"+ f_d).setHours(0,0,0,0) > new Date(t_y +"/"+ t_m +"/"+ t_d).setHours(0,0,0,0)) {
      f_d = [t_d, t_d = f_d][0];
      f_m = [t_m, t_m = f_m][0];
      f_y = [t_y, t_y = f_y][0];
    } else if (f_d +"-"+ f_m +"-"+ f_y == t_d +"-"+ t_m +"-"+ t_y) {
      t_d = null;
      t_m = null;
      t_y = null;
    }
  } else {
    if (f_d == null && f_m == null && f_y == null && t_d != null && t_m != null && t_y != null) {
      f_d = t_d;
      f_m = t_m;
      f_y = t_y;
      t_d = null;
      t_m = null;
      t_y = null;
    }
  }
}

function calendarSetFrom(wrpId, y, m, d) {
  fromToObject[wrpId]['from_d'] = parseInt(d);
  fromToObject[wrpId]['from_m'] = parseInt(m);
  fromToObject[wrpId]['from_y'] = parseInt(y);
}

function calendarSetTo(wrpId, y, m, d) {
  fromToObject[wrpId]['to_d'] = parseInt(d);
  fromToObject[wrpId]['to_m'] = parseInt(m);
  fromToObject[wrpId]['to_y'] = parseInt(y);
}

function calendarMarkFromTo(wrpId) {
  calendarDayRemoveFrom(wrpId);
  calendarDayRemoveTo(wrpId);
  if (fromToObject[wrpId]['from_d'] != null && fromToObject[wrpId]['from_m'] != null && fromToObject[wrpId]['from_y'] != null) {
    if (document.getElementById("cal-o-d-btn-"+ wrpId +"-"+ fromToObject[wrpId]['from_d'] +"-"+ fromToObject[wrpId]['from_m'] +"-"+ fromToObject[wrpId]['from_y'])) {
      document.getElementById("cal-o-d-btn-"+ wrpId +"-"+ fromToObject[wrpId]['from_d'] +"-"+ fromToObject[wrpId]['from_m'] +"-"+ fromToObject[wrpId]['from_y']).classList.add("cal-o-d-btn-from");
    }
  }
  if (fromToObject[wrpId]['to_d'] != null && fromToObject[wrpId]['to_m'] != null && fromToObject[wrpId]['to_y'] != null) {
    if (document.getElementById("cal-o-d-btn-"+ wrpId +"-"+ fromToObject[wrpId]['to_d'] +"-"+ fromToObject[wrpId]['to_m'] +"-"+ fromToObject[wrpId]['to_y'])) {
      document.getElementById("cal-o-d-btn-"+ wrpId +"-"+ fromToObject[wrpId]['to_d'] +"-"+ fromToObject[wrpId]['to_m'] +"-"+ fromToObject[wrpId]['to_y']).classList.add("cal-o-d-btn-to");
    }
  }
}

var mBetween_d, mBetween_m, mBetween_y;
function calendarMarkDaysBetween(wrpId) {
  calendarDayRemoveFromBetween(wrpId);
  calendarDayRemoveToBetween(wrpId);
  calendarDayRemoveBetween(wrpId);
  if (fromToObject[wrpId]['from_d'] != null && fromToObject[wrpId]['from_m'] != null && fromToObject[wrpId]['from_y'] != null && fromToObject[wrpId]['to_d'] != null && fromToObject[wrpId]['to_m'] != null && fromToObject[wrpId]['to_y'] != null) {
    if (document.getElementById("cal-o-d-btn-"+ wrpId +"-"+ fromToObject[wrpId]['from_d'] +"-"+ fromToObject[wrpId]['from_m'] +"-"+ fromToObject[wrpId]['from_y'])) {
      document.getElementById("cal-o-d-btn-"+ wrpId +"-"+ fromToObject[wrpId]['from_d'] +"-"+ fromToObject[wrpId]['from_m'] +"-"+ fromToObject[wrpId]['from_y']).classList.add("cal-o-d-btn-day-between-from");
    }
    if (fromToObject[wrpId]['to_d'] != null && fromToObject[wrpId]['to_m'] != null && fromToObject[wrpId]['to_y'] != null) {
      if (document.getElementById("cal-o-d-btn-"+ wrpId +"-"+ fromToObject[wrpId]['to_d'] +"-"+ fromToObject[wrpId]['to_m'] +"-"+ fromToObject[wrpId]['to_y'])) {
        document.getElementById("cal-o-d-btn-"+ wrpId +"-"+ fromToObject[wrpId]['to_d'] +"-"+ fromToObject[wrpId]['to_m'] +"-"+ fromToObject[wrpId]['to_y']).classList.add("cal-o-d-btn-day-between-to");
      }
    }
    mBetween_d = fromToObject[wrpId]['from_d'];
    mBetween_m = fromToObject[wrpId]['from_m'];
    mBetween_y = fromToObject[wrpId]['from_y'];
    while (mBetween_d +"-"+ mBetween_m +"-"+ mBetween_y != fromToObject[wrpId]['to_d'] +"-"+ fromToObject[wrpId]['to_m'] +"-"+ fromToObject[wrpId]['to_y']) {
      mBetweenLoop_d = mBetween_d;
      mBetweenLoop_m = mBetween_m;
      mBetweenLoop_y = mBetween_y;
      if (calendarCheckIfDayIsBetween(wrpId, mBetween_y, mBetween_m, mBetween_d)) {
        if (document.getElementById("cal-o-d-btn-"+ wrpId +"-"+ mBetweenLoop_d +"-"+ mBetweenLoop_m +"-"+ mBetweenLoop_y)) {
          document.getElementById("cal-o-d-btn-"+ wrpId +"-"+ mBetweenLoop_d +"-"+ mBetweenLoop_m +"-"+ mBetweenLoop_y).classList.add("cal-o-d-btn-day-between");
        }
      }
      mBetween_d = calcNextDay(mBetweenLoop_d, mBetweenLoop_m, mBetweenLoop_y, 1);
      mBetween_m = calcNextMonthByDays(mBetweenLoop_d, mBetweenLoop_m, mBetweenLoop_y, 1);
      mBetween_y = calcNextYearByDays(mBetweenLoop_d, mBetweenLoop_m, mBetweenLoop_y, 1);
    }
  }
}

function calendarDayRemoveFrom(wrpId) {
  for (var f = 0; f < document.getElementsByClassName("cal-o-d-btn-"+ wrpId).length; f++) {
    document.getElementsByClassName("cal-o-d-btn-"+ wrpId)[f].classList.remove("cal-o-d-btn-from");
  }
}

function calendarDayRemoveFromBetween(wrpId) {
  for (var fB = 0; fB < document.getElementsByClassName("cal-o-d-btn-"+ wrpId).length; fB++) {
    document.getElementsByClassName("cal-o-d-btn-"+ wrpId)[fB].classList.remove("cal-o-d-btn-day-between-from");
  }
}

function calendarDayRemoveTo(wrpId) {
  for (var t = 0; t < document.getElementsByClassName("cal-o-d-btn-"+ wrpId).length; t++) {
    document.getElementsByClassName("cal-o-d-btn-"+ wrpId)[t].classList.remove("cal-o-d-btn-to");
  }
}

function calendarDayRemoveToBetween(wrpId) {
  for (var tB = 0; tB < document.getElementsByClassName("cal-o-d-btn-"+ wrpId).length; tB++) {
    document.getElementsByClassName("cal-o-d-btn-"+ wrpId)[tB].classList.remove("cal-o-d-btn-day-between-to");
  }
}

function calendarDayRemoveBetween(wrpId) {
  for (var b = 0; b < document.getElementsByClassName("cal-o-d-btn-"+ wrpId).length; b++) {
    document.getElementsByClassName("cal-o-d-btn-"+ wrpId)[b].classList.remove("cal-o-d-btn-day-between");
  }
}

var checkB_d, checkB_m, checkB_y, loopB_d, loopB_m, loopB_y, allDaysBetweenAreGood, firstDayInDaysBetween;
function calendarCheckDaysBetweenFromTo(wrpId) {
  allDaysBetweenAreGood = true;
  firstDayInDaysBetween = false;
  if (f_d != null && f_m != null && f_y != null && t_d != null && t_m != null && t_y != null) {
    checkB_d = f_d;
    checkB_m = f_m;
    checkB_y = f_y;
    while (checkB_d +"-"+ checkB_m +"-"+ checkB_y != calcNextDay(t_d, t_m, t_y, 1) +"-"+ calcNextMonthByDays(t_d, t_m, t_y, 1) +"-"+ calcNextYearByDays(t_d, t_m, t_y, 1)) {
      loopB_d = checkB_d;
      loopB_m = checkB_m;
      loopB_y = checkB_y;
      if (daysStatusObject[wrpId][checkB_d +"-"+ checkB_m +"-"+ checkB_y] == "unavailable" || firstDayInDaysBetween) {
        allDaysBetweenAreGood = false;
      } else if (daysStatusObject[wrpId][checkB_d +"-"+ checkB_m +"-"+ checkB_y] == "limited-secondhalf") {
        firstDayInDaysBetween = true;
      }
      checkB_d = calcNextDay(loopB_d, loopB_m, loopB_y, 1);
      checkB_m = calcNextMonthByDays(loopB_d, loopB_m, loopB_y, 1);
      checkB_y = calcNextYearByDays(loopB_d, loopB_m, loopB_y, 1);
    }
  }
  return allDaysBetweenAreGood;
}

function calendarCheckIfDayIsBetween(wrpId, y, m, d) {
  if (fromToObject[wrpId]['from_d'] != null && fromToObject[wrpId]['from_m'] != null && fromToObject[wrpId]['from_y'] != null && fromToObject[wrpId]['to_d'] != null && fromToObject[wrpId]['to_m'] != null && fromToObject[wrpId]['to_y'] != null) {
    if (new Date(y +"/"+ m +"/"+ d).setHours(0,0,0,0) > new Date(fromToObject[wrpId]['from_y'] +"/"+ fromToObject[wrpId]['from_m'] +"/"+ fromToObject[wrpId]['from_d']).setHours(0,0,0,0) && new Date(y +"/"+ m +"/"+ d).setHours(0,0,0,0) < new Date(fromToObject[wrpId]['to_y'] +"/"+ fromToObject[wrpId]['to_m'] +"/"+ fromToObject[wrpId]['to_d']).setHours(0,0,0,0)) {
      return true;
    } else {
      return false;
    }
  }
}

function calendarDayHover(wrpId, y, m, d, tsk) {
  if (tsk == "over") {
    document.getElementById("cal-o-d-btn-"+ wrpId +"-"+ d +"-"+ m +"-"+ y).classList.add("cal-o-d-btn-hover");
  } else {
    document.getElementById("cal-o-d-btn-"+ wrpId +"-"+ d +"-"+ m +"-"+ y).classList.remove("cal-o-d-btn-hover");
  }
}
