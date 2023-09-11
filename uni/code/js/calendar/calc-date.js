function calcNextDay(d, m, y, plusD) {
  var dayCalcDone = false;
  var dInM, nextD;
  while (!dayCalcDone) {
    nextD = d + plusD;
    dInM = new Date(y, m, 0).getDate();
    if (nextD > dInM) {
      plusD = plusD - (dInM - d + 1);
      d = 1;
      m = calcNextMonth(m, 1);
      if (m == 1) {
        ++y;
      }
    } else {
      d = nextD;
      dayCalcDone = true;
    }
  }
  return d;
}

function calcPreviousDay(d, m, y, minusD) {
  var dayCalcDone = false;
  var dInM, prevD;
  while (!dayCalcDone) {
    prevD = d - minusD;
    if (prevD <= 0) {
      minusD = minusD - d;
      m = calcPreviousMonth(m, 1);
      if (m == 12) {
        --y;
      }
      d = new Date(y, m, 0).getDate();
    } else {
      d = prevD;
      dayCalcDone = true;
    }
  }
  return d;
}

function calcNextMonth(m, plusM) {
  var monthsLeft = 12 - m;
  if (plusM > monthsLeft) {
    m = plusM - monthsLeft;
    while (m > 12) {
      m = m - 12;
    }
  } else {
    m = m + plusM;
  }
  return m;
}

function calcNextMonthByDays(d, m, y, plusD) {
  var dayCalcDone = false;
  var dInM, nextD;
  while (!dayCalcDone) {
    nextD = d + plusD;
    dInM = new Date(y, m, 0).getDate();
    if (nextD > dInM) {
      plusD = plusD - (dInM - d + 1);
      d = 1;
      m = calcNextMonth(m, 1);
      if (m == 1) {
        ++y;
      }
    } else {
      d = nextD;
      dayCalcDone = true;
    }
  }
  return m;
}

function calcPreviousMonth(m, minusM) {
  if (m <= minusM) {
    x = m - minusM;
    if (x != 0) {
      var calcDone = false;
      while (!calcDone) {
        x = Math.abs(x);
        x = 12 - x;
        if (x > 0) {
          m = x;
          calcDone = true;
        } else if (x == 0){
          m = 12;
          calcDone = true;
        }
      }
    } else {
      m = 12;
    }
  } else {
    m = m - minusM;
  }
  return m;
}

function calcPreviousMonthByDays(d, m, y, minusD) {
  var dayCalcDone = false;
  var dInM, prevD;
  while (!dayCalcDone) {
    prevD = d - minusD;
    if (prevD <= 0) {
      minusD = minusD - d;
      m = calcPreviousMonth(m, 1);
      if (m == 12) {
        --y;
      }
      d = new Date(y, m, 0).getDate();
    } else {
      d = prevD;
      dayCalcDone = true;
    }
  }
  return m;
}

function calcNextYearByMonth(m, y, plusM) {
  m = m + plusM;
  var plusY = Math.floor(m / 12);
  if (m > 12) {
    y = y + plusY;
  }
  return y;
}

function calcNextYearByDays(d, m, y, plusD) {
  var dayCalcDone = false;
  var dInM, nextD;
  while (!dayCalcDone) {
    nextD = d + plusD;
    dInM = new Date(y, m, 0).getDate();
    if (nextD > dInM) {
      plusD = plusD - (dInM - d + 1);
      d = 1;
      m = calcNextMonth(m, 1);
      if (m == 1) {
        ++y;
      }
    } else {
      d = nextD;
      dayCalcDone = true;
    }
  }
  return y;
}

function calcPreviousYearByMonth(m, y, minusM) {
  if (m <= minusM) {
    var diff = (minusM - m) / 12;
    if (Number.isInteger(diff)) {
      var zeroY = 1;
    } else {
      var zeroY = 0;
    }
    y = y - Math.ceil(diff) - zeroY;
  }
  return y;
}

function calcPreviousYearByDays(d, m, y, minusD) {
  var dayCalcDone = false;
  var dInM, prevD;
  while (!dayCalcDone) {
    prevD = d - minusD;
    if (prevD <= 0) {
      minusD = minusD - d;
      m = calcPreviousMonth(m, 1);
      if (m == 12) {
        --y;
      }
      d = new Date(y, m, 0).getDate();
    } else {
      d = prevD;
      dayCalcDone = true;
    }
  }
  return y;
}
