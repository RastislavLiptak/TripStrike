var xhrDict;
function calendarDictionaryLoader(wrpId, id, type) {
  if (xhrDict != null) {
    xhrDict.abort();
  }
  xhrDict = new XMLHttpRequest();
  xhrDict.onreadystatechange = function() {
    if (xhrDict.readyState == 4 && xhrDict.status == 200) {
      if (testJSON(xhrDict.response)) {
        var json = JSON.parse(xhrDict.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            calendarMonthSlideDictionaryRender(json[key]["loading"], json[key]["mon"], json[key]["tue"], json[key]["wed"], json[key]["thu"], json[key]["fri"], json[key]["sat"], json[key]["sun"]);
            calendarMonthSlideDictionaryUpdate(json[key]["error"], json[key]["january"], json[key]["february"], json[key]["march"], json[key]["april"], json[key]["may"], json[key]["june"], json[key]["july"], json[key]["august"], json[key]["september"], json[key]["october"], json[key]["november"], json[key]["december"]);
            calendarMonthDataLoader(wrpId, id, new Date().getMonth() + 1, new Date().getFullYear(), type, "none");
            calendarMonthDataLoader(wrpId, id, calcNextMonth(new Date().getMonth() + 1, 1), calcNextYearByMonth(new Date().getMonth() + 1, new Date().getFullYear(), 1), type, "none");
            calendarMonthDataLoader(wrpId, id, calcPreviousMonth(new Date().getMonth() + 1, 1), calcPreviousYearByMonth(new Date().getMonth() + 1, new Date().getFullYear(), 1), type, "none");
            calendarMonthDataLoader(wrpId, id, calcNextMonth(new Date().getMonth() + 1, 2), calcNextYearByMonth(new Date().getMonth() + 1, new Date().getFullYear(), 2), type, "none");
            calendarMonthDataLoader(wrpId, id, calcPreviousMonth(new Date().getMonth() + 1, 2), calcPreviousYearByMonth(new Date().getMonth() + 1, new Date().getFullYear(), 2), type, "none");
            calendarMonthDataLoader(wrpId, id, calcNextMonth(new Date().getMonth() + 1, 3), calcNextYearByMonth(new Date().getMonth() + 1, new Date().getFullYear(), 3), type, "none");
            calendarMainContentHandler(wrpId, "content");
          }
        }
      } else {
        document.getElementById("cal-main-error-txt-"+ wrpId).innerHTML = xhrDict.response;
        calendarSetSizeMainError(wrpId);
        calendarMainContentHandler(wrpId, "error");
      }
    }
  }
  xhrDict.open("POST", "../uni/code/php-backend/calendar/calendar-dictionary.php");
  xhrDict.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrDict.send();
}

function calendarMonthDataLoader(wrpId, id, m, y, type, scrollTsk) {
  calendarMonthLoaderSlidesManager(wrpId, m, y, scrollTsk);
  if (type == "guest-view") {
    calendarMonthDataLoaderGuestMode(wrpId, id, m, y);
  } else {
    calendarMonthDataLoaderHostMode(wrpId, id, m, y);
  }
}

function calendarMonthDataLoaderGuestMode(wrpId, id, m, y) {
  var xhrCalDataLoader = new XMLHttpRequest();
  xhrCalDataLoader.onreadystatechange = function() {
    if (xhrCalDataLoader.readyState == 4 && xhrCalDataLoader.status == 200) {
      if (testJSON(xhrCalDataLoader.response)) {
        var json = JSON.parse(xhrCalDataLoader.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            calendarDayRender(wrpId, "booking", json[key]["d"], json[key]["m"], json[key]["y"], json[key]["sts"], json[key]["busy"], "none");
            calendarMonthSlideHeaderTxt(wrpId, y, m, m);
            document.getElementById("cal-o-m-year-txt-"+ wrpId +"-"+ m +"-"+ y).innerHTML = y;
            calendarSlideContentHandler(wrpId, y, m, "content");
          }
        }
        calendarTodaysRedDot(wrpId);
        calendarMarkFromTo(wrpId);
      } else {
        document.getElementById("cal-o-m-error-txt-"+ wrpId +"-"+ m +"-"+ y).innerHTML = xhrCalDataLoader.response;
        calendarMonthSlideHeaderTxt(wrpId, y, m, "error");
        calendarSetSizeMonthSlideError(wrpId);
        calendarSlideContentHandler(wrpId, y, m, "error");
      }
    }
  }
  xhrCalDataLoader.open("POST", "../uni/code/php-backend/calendar/get-calendar-data-guest-mode.php");
  xhrCalDataLoader.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrCalDataLoader.send("id="+ id +"&month="+ m +"&year="+ y);
}

function calendarMonthDataLoaderHostMode(wrpId, id, m, y) {
  var xhrCalDataLoader = new XMLHttpRequest();
  xhrCalDataLoader.onreadystatechange = function() {
    if (xhrCalDataLoader.readyState == 4 && xhrCalDataLoader.status == 200) {
      if (testJSON(xhrCalDataLoader.response)) {
        var json = JSON.parse(xhrCalDataLoader.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            calendarDayRender(wrpId, json[key]["category"], json[key]["d"], json[key]["m"], json[key]["y"], json[key]["sts"], json[key]["busy"], json[key]["payment"]);
            calendarMonthSlideHeaderTxt(wrpId, y, m, m);
            document.getElementById("cal-o-m-year-txt-"+ wrpId +"-"+ m +"-"+ y).innerHTML = y;
            calendarSlideContentHandler(wrpId, y, m, "content");
          }
        }
        calendarTodaysRedDot(wrpId);
        calendarMarkFromTo(wrpId);
      } else {
        document.getElementById("cal-o-m-error-txt-"+ wrpId +"-"+ m +"-"+ y).innerHTML = xhrCalDataLoader.response;
        calendarMonthSlideHeaderTxt(wrpId, y, m, "error");
        calendarSetSizeMonthSlideError(wrpId);
        calendarSlideContentHandler(wrpId, y, m, "error");
      }
    }
  }
  xhrCalDataLoader.open("POST", "../uni/code/php-backend/calendar/get-calendar-data-host-mode.php");
  xhrCalDataLoader.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrCalDataLoader.send("id="+ id +"&month="+ m +"&year="+ y);
}
