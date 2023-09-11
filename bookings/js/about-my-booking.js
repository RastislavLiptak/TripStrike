var url_string = window.location.href;
var url = new URL(url_string);
var booking_id = url.searchParams.get("id");

var aboutBookingsChangesReady = true;
var xhrBookingDataSave, aBookingSaveTimer, aBookingName, aBookingEmail, aBookingPhone;
function aboutBookingsSave() {
  if (aboutBookingsChangesReady) {
    aboutBookingsChangesReady = false;
    clearTimeout(aBookingSaveTimer);
    aboutBookingsBtnManager("load", "about-this-booking-btn-save");
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    aboutBookingsResetErrors();
    aBookingName = document.getElementById("about-this-booking-form-textarea-name").value;
    aBookingEmail = document.getElementById("about-this-booking-form-textarea-email").value;
    aBookingPhone = document.getElementById("about-this-booking-form-textarea-phone").value.replace(/\+/g, "plus");
    xhrBookingDataSave = new XMLHttpRequest();
    xhrBookingDataSave.onreadystatechange = function() {
      if (xhrBookingDataSave.readyState == 4 && xhrBookingDataSave.status == 200) {
        aboutBookingsChangesReady = true;
        window.onbeforeunload = null;
        if (testJSON(xhrBookingDataSave.response)) {
          var json = JSON.parse(xhrBookingDataSave.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                aboutBookingsBtnManager("success", "about-this-booking-btn-save");
                aBookingSaveTimer = setTimeout(function(){
                  aboutBookingsBtnManager("def", "about-this-booking-btn-save");
                }, 1000);
              } else if (json[key]["type"] == "error") {
                aboutBookingsBtnManager("def", "about-this-booking-btn-save");
                if (json[key]["section"] == "name") {
                  document.getElementById("about-this-booking-form-error-txt-name").style.display = "table";
                  document.getElementById("about-this-booking-form-error-txt-name").innerHTML = json[key]["error"];
                } else if (json[key]["section"] == "email") {
                  document.getElementById("about-this-booking-form-error-txt-email").style.display = "table";
                  document.getElementById("about-this-booking-form-error-txt-email").innerHTML = json[key]["error"];
                } else if (json[key]["section"] == "phone") {
                  document.getElementById("about-this-booking-form-error-txt-phone").style.display = "table";
                  document.getElementById("about-this-booking-form-error-txt-phone").innerHTML = json[key]["error"];
                } else {
                  document.getElementById("about-this-booking-error-wrp").style.display = "table";
                  document.getElementById("about-this-booking-error-txt").innerHTML = json[key]["error"];
                }
              }
            }
          }
        } else {
          aboutBookingsBtnManager("def", "about-this-booking-btn-save");
          document.getElementById("about-this-booking-error-wrp").style.display = "table";
          document.getElementById("about-this-booking-error-txt").innerHTML = xhrBookingDataSave.response;
        }
      }
    }
    xhrBookingDataSave.open("POST", "php-backend/update-my-booking-data.php");
    xhrBookingDataSave.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrBookingDataSave.send("urlId="+ booking_id +"&name="+ aBookingName +"&email="+ aBookingEmail +"&phone="+ aBookingPhone);
  }
}

var xhrBookingCancel;
function aboutCancelBooking() {
  if (aboutBookingsChangesReady) {
    aboutBookingsChangesReady = false;
    aboutBookingsBtnManager("load", "about-this-booking-btn-cancel-booking");
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    modCover('hide', 'modal-cover-cancel-booking');
    aboutBookingsResetErrors();
    xhrBookingCancel = new XMLHttpRequest();
    xhrBookingCancel.onreadystatechange = function() {
      if (xhrBookingCancel.readyState == 4 && xhrBookingCancel.status == 200) {
        aboutBookingsChangesReady = true;
        window.onbeforeunload = null;
        if (testJSON(xhrBookingCancel.response)) {
          var json = JSON.parse(xhrBookingCancel.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                aboutBookingsBtnManager("success", "about-this-booking-btn-cancel-booking");
                location.reload();
              } else if (json[key]["type"] == "error") {
                aboutBookingsBtnManager("def", "about-this-booking-btn-cancel-booking");
                document.getElementById("about-this-booking-error-wrp").style.display = "table";
                document.getElementById("about-this-booking-error-txt").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          aboutBookingsBtnManager("def", "about-this-booking-btn-cancel-booking");
          document.getElementById("about-this-booking-error-wrp").style.display = "table";
          document.getElementById("about-this-booking-error-txt").innerHTML = xhrBookingCancel.response;
        }
      }
    }
    xhrBookingCancel.open("POST", "php-backend/cancel-my-booking.php");
    xhrBookingCancel.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrBookingCancel.send("urlId="+ booking_id);
  }
}

function aboutBookingsResetErrors() {
  document.getElementById("about-this-booking-error-wrp").style.display = "";
  document.getElementById("about-this-booking-error-txt").innerHTML = "";
  for (var aBE = 0; aBE < document.getElementsByClassName("about-this-booking-form-error-txt").length; aBE++) {
    document.getElementsByClassName("about-this-booking-form-error-txt")[aBE].style.display = "";
    document.getElementsByClassName("about-this-booking-form-error-txt")[aBE].innerHTML = "";
  }
}

var aboutBookingsAskForChangeReady = true;
function aboutBookingsChangeDatesModal(tsk) {
  if (aboutBookingsAskForChangeReady) {
    document.getElementById("change-data-input-from-date").value = document.getElementById("change-data-content-about-txt-d-from-y").innerHTML +"-"+ ("0"+ document.getElementById("change-data-content-about-txt-d-from-m").innerHTML).slice(-2) +"-"+ ("0"+ document.getElementById("change-data-content-about-txt-d-from-d").innerHTML).slice(-2);
    document.getElementById("change-data-input-to-date").value = document.getElementById("change-data-content-about-txt-d-to-y").innerHTML +"-"+ ("0"+ document.getElementById("change-data-content-about-txt-d-to-m").innerHTML).slice(-2) +"-"+ ("0"+ document.getElementById("change-data-content-about-txt-d-to-d").innerHTML).slice(-2);
    document.getElementById("change-data-select-firstday").value = document.getElementById("change-data-content-about-txt-d-firstday").innerHTML;
    document.getElementById("change-data-select-lastday").value = document.getElementById("change-data-content-about-txt-d-lastday").innerHTML;
    document.getElementById("change-data-input-row-new-price-dates").style.display = "";
    document.getElementById("change-data-new-price-txt-dates").innerHTML = "";
    document.getElementById("change-data-input-row-new-price-dates-diff").style.display = "";
    document.getElementById("change-data-new-price-txt-dates-diff").innerHTML = "";
    document.getElementById("change-data-error-txt-dates").innerHTML = "";
    aboutBookingsBtnManager("def", "change-data-btn-dates-save");
  }
  modCover(tsk, 'modal-cover-change-data-dates');
}

function aboutBookingsChangeGuestsNumModal(tsk) {
  if (aboutBookingsAskForChangeReady) {
    document.getElementById("change-data-input-number-of-guests").value = document.getElementById("change-data-content-about-txt-g-num-of-guests").innerHTML;
    if (document.getElementById("change-data-input-row-new-price-guests")) {
      document.getElementById("change-data-input-row-new-price-guests").style.display = "";
      document.getElementById("change-data-new-price-txt-guests").innerHTML = "";
      document.getElementById("change-data-input-row-new-price-guests-diff").style.display = "";
      document.getElementById("change-data-new-price-txt-guests-diff").innerHTML = "";
    }
    document.getElementById("change-data-error-txt-guests").innerHTML = "";
    aboutBookingsBtnManager("def", "change-data-btn-guests-save");
  }
  modCover(tsk, 'modal-cover-change-data-number-of-guests');
}

var newPrice = 0;
function aboutMyBookingChangeDataDatesPrice() {
  newPrice = aboutBookingChangeDataPriceCalc(
    document.getElementById("change-data-content-about-txt-d-price-mode").innerHTML,
    document.getElementById("change-data-content-about-txt-d-work-price").innerHTML,
    document.getElementById("change-data-content-about-txt-d-week-price").innerHTML,
    new Date(document.getElementById("change-data-input-from-date").value).getDate(),
    new Date(document.getElementById("change-data-input-from-date").value).getMonth()+1,
    new Date(document.getElementById("change-data-input-from-date").value).getFullYear(),
    new Date(document.getElementById("change-data-input-to-date").value).getDate(),
    new Date(document.getElementById("change-data-input-to-date").value).getMonth()+1,
    new Date(document.getElementById("change-data-input-to-date").value).getFullYear(),
    document.getElementById("change-data-select-firstday").value,
    document.getElementById("change-data-select-lastday").value,
    document.getElementById("change-data-content-about-txt-d-num-of-guests").innerHTML
  );
  if (newPrice != document.getElementById("change-data-content-about-txt-d-total").innerHTML && newPrice != "unset-values" && newPrice != "wrong-order") {
    document.getElementById("change-data-input-row-new-price-dates").style.display = "flex";
    document.getElementById("change-data-new-price-txt-dates").innerHTML = addCurrency(document.getElementById("change-data-content-about-txt-d-price-currency").innerHTML, newPrice);
    document.getElementById("change-data-input-row-new-price-dates-diff").style.display = "flex";
    if (newPrice > document.getElementById("change-data-content-about-txt-d-total").innerHTML) {
      document.getElementById("change-data-new-price-txt-dates-diff").innerHTML = "+"+ addCurrency(document.getElementById("change-data-content-about-txt-d-price-currency").innerHTML, newPrice - document.getElementById("change-data-content-about-txt-d-total").innerHTML);
    } else {
      document.getElementById("change-data-new-price-txt-dates-diff").innerHTML = "-"+ addCurrency(document.getElementById("change-data-content-about-txt-d-price-currency").innerHTML, document.getElementById("change-data-content-about-txt-d-total").innerHTML - newPrice);
    }
  } else {
    document.getElementById("change-data-input-row-new-price-dates").style.display = "";
    document.getElementById("change-data-new-price-txt-dates").innerHTML = "";
    document.getElementById("change-data-input-row-new-price-dates-diff").style.display = "";
    document.getElementById("change-data-new-price-txt-dates-diff").innerHTML = "";
  }
}

function aboutMyBookingChangeDataNumOfGuestsPrice() {
  if (document.getElementById("change-data-input-row-new-price-guests")) {
    newPrice = aboutBookingChangeDataPriceCalc(
      document.getElementById("change-data-content-about-txt-g-price-mode").innerHTML,
      document.getElementById("change-data-content-about-txt-g-work-price").innerHTML,
      document.getElementById("change-data-content-about-txt-g-week-price").innerHTML,
      document.getElementById("change-data-content-about-txt-g-from-d").innerHTML,
      document.getElementById("change-data-content-about-txt-g-from-m").innerHTML,
      document.getElementById("change-data-content-about-txt-g-from-y").innerHTML,
      document.getElementById("change-data-content-about-txt-g-to-d").innerHTML,
      document.getElementById("change-data-content-about-txt-g-to-m").innerHTML,
      document.getElementById("change-data-content-about-txt-g-to-y").innerHTML,
      document.getElementById("change-data-content-about-txt-g-firstday").innerHTML,
      document.getElementById("change-data-content-about-txt-g-lastday").innerHTML,
      document.getElementById("change-data-input-number-of-guests").value
    );
    if (newPrice != document.getElementById("change-data-content-about-txt-g-total").innerHTML && newPrice != "unset-values" && newPrice != "wrong-order") {
      document.getElementById("change-data-input-row-new-price-guests").style.display = "flex";
      document.getElementById("change-data-new-price-txt-guests").innerHTML = addCurrency(document.getElementById("change-data-content-about-txt-g-price-currency").innerHTML, newPrice);
      document.getElementById("change-data-input-row-new-price-guests-diff").style.display = "flex";
      if (newPrice > document.getElementById("change-data-content-about-txt-g-total").innerHTML) {
        document.getElementById("change-data-new-price-txt-guests-diff").innerHTML = "+"+ addCurrency(document.getElementById("change-data-content-about-txt-g-price-currency").innerHTML, newPrice - document.getElementById("change-data-content-about-txt-g-total").innerHTML);
      } else {
        document.getElementById("change-data-new-price-txt-guests-diff").innerHTML = "-"+ addCurrency(document.getElementById("change-data-content-about-txt-g-price-currency").innerHTML, document.getElementById("change-data-content-about-txt-g-total").innerHTML - newPrice);
      }
    } else {
      document.getElementById("change-data-input-row-new-price-guests").style.display = "";
      document.getElementById("change-data-new-price-txt-guests").innerHTML = "";
      document.getElementById("change-data-input-row-new-price-guests-diff").style.display = "";
      document.getElementById("change-data-new-price-txt-guests-diff").innerHTML = "";
    }
  }
}

var xhrAskForBookingChangeSave, aMAskForBookingChangeTimer;
var aBookingFromD, aBookingFromM, aBookingFromY, aBookingToD, aBookingToM, aBookingToY, aBookingFirstDay, aBookingLastday;
function aboutMyBookingChangeDataDatesSave() {
  if (aboutBookingsAskForChangeReady) {
    aboutBookingsAskForChangeReady = false;
    clearTimeout(aMAskForBookingChangeTimer);
    aboutBookingsBtnManager("load", "change-data-btn-dates-save");
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    document.getElementById("change-data-error-txt-dates").innerHTML = "";
    aBookingFromD = new Date(document.getElementById("change-data-input-from-date").value).getDate();
    aBookingFromM = new Date(document.getElementById("change-data-input-from-date").value).getMonth()+1;
    aBookingFromY = new Date(document.getElementById("change-data-input-from-date").value).getFullYear();
    aBookingToD = new Date(document.getElementById("change-data-input-to-date").value).getDate();
    aBookingToM = new Date(document.getElementById("change-data-input-to-date").value).getMonth()+1;
    aBookingToY = new Date(document.getElementById("change-data-input-to-date").value).getFullYear();
    aBookingFirstDay = document.getElementById("change-data-select-firstday").value;
    aBookingLastday = document.getElementById("change-data-select-lastday").value;
    xhrAskForBookingChangeSave = new XMLHttpRequest();
    xhrAskForBookingChangeSave.onreadystatechange = function() {
      if (xhrAskForBookingChangeSave.readyState == 4 && xhrAskForBookingChangeSave.status == 200) {
        aboutBookingsAskForChangeReady = true;
        window.onbeforeunload = null;
        if (testJSON(xhrAskForBookingChangeSave.response)) {
          var json = JSON.parse(xhrAskForBookingChangeSave.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                aboutBookingsBtnManager("success", "change-data-btn-dates-save");
                aboutMyBookingChangeDataDatesRequestNote(json[key]["fromD"], json[key]["fromM"], json[key]["fromY"], json[key]["firstDaySts"], json[key]["firstDayTxt"], json[key]["toD"], json[key]["toM"], json[key]["toY"], json[key]["lastDaySts"], json[key]["lastDayTxt"]);
                aboutMyBookingChangeDataPriceNote(json[key]["currency"], json[key]["newTotal"], json[key]["priceDiff"]);
                aboutBookingsChangeDatesModal('hide');
                aMAskForBookingChangeTimer = setTimeout(function(){
                  aboutBookingsBtnManager("def", "change-data-btn-dates-save");
                }, 1000);
              } else if (json[key]["type"] == "error") {
                aboutBookingsBtnManager("def", "change-data-btn-dates-save");
                document.getElementById("change-data-error-txt-dates").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          aboutBookingsBtnManager("def", "change-data-btn-dates-save");
          document.getElementById("change-data-error-txt-dates").innerHTML = xhrAskForBookingChangeSave.response;
        }
      }
    }
    xhrAskForBookingChangeSave.open("POST", "php-backend/ask-for-change-date.php");
    xhrAskForBookingChangeSave.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrAskForBookingChangeSave.send("urlId="+ booking_id +"&fromD="+ aBookingFromD +"&fromM="+ aBookingFromM +"&fromY="+ aBookingFromY +"&toD="+ aBookingToD +"&toM="+ aBookingToM +"&toY="+ aBookingToY +"&firstDay="+ aBookingFirstDay +"&lastDay="+ aBookingLastday);
  }
}

function aboutMyBookingChangeDataDatesRequestNote(fromD, fromM, fromY, firstDaySts, firstDayTxt, toD, toM, toY, lastDaySts, lastDayTxt) {
  if (
    (
      document.getElementById("change-data-content-about-txt-d-from-d").innerHTML == fromD &&
      document.getElementById("change-data-content-about-txt-d-from-m").innerHTML == fromM &&
      document.getElementById("change-data-content-about-txt-d-from-y").innerHTML == fromY &&
      document.getElementById("change-data-content-about-txt-d-firstday").innerHTML == firstDaySts
    ) || (
      fromD == "org" &&
      fromM == "org" &&
      fromY == "org" &&
      firstDaySts == "org"
    )
  ) {
    document.getElementById("about-this-booking-form-request-change-wrp-from-date").style.display = "";
    document.getElementById("about-this-booking-form-request-change-txt-bold-from-date").innerHTML = "";
  } else {
    document.getElementById("about-this-booking-form-request-change-wrp-from-date").style.display = "table";
    document.getElementById("about-this-booking-form-request-change-txt-bold-from-date").innerHTML = fromD +"."+ fromM +"."+ fromY +" ("+ firstDayTxt +")";
  }
  if (
    (
      document.getElementById("change-data-content-about-txt-d-to-d").innerHTML == toD &&
      document.getElementById("change-data-content-about-txt-d-to-m").innerHTML == toM &&
      document.getElementById("change-data-content-about-txt-d-to-y").innerHTML == toY &&
      document.getElementById("change-data-content-about-txt-d-lastday").innerHTML == lastDaySts
    ) || (
      toD == "org" &&
      toM == "org" &&
      toY == "org" &&
      lastDaySts == "org"
    )
  ) {
    document.getElementById("about-this-booking-form-request-change-wrp-to-date").style.display = "";
    document.getElementById("about-this-booking-form-request-change-txt-bold-to-date").innerHTML = "";
  } else {
    document.getElementById("about-this-booking-form-request-change-wrp-to-date").style.display = "table";
    document.getElementById("about-this-booking-form-request-change-txt-bold-to-date").innerHTML = toD +"."+ toM +"."+ toY +" ("+ lastDayTxt +")";
  }
}

var aBookingNumOfGuests;
function aboutMyBookingChangeDataNumOfGuestsSave() {
  if (aboutBookingsAskForChangeReady) {
    aboutBookingsAskForChangeReady = false;
    clearTimeout(aMAskForBookingChangeTimer);
    aboutBookingsBtnManager("load", "change-data-btn-guests-save");
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    document.getElementById("change-data-error-txt-guests").innerHTML = "";
    aBookingNumOfGuests = document.getElementById("change-data-input-number-of-guests").value;
    xhrAskForBookingChangeSave = new XMLHttpRequest();
    xhrAskForBookingChangeSave.onreadystatechange = function() {
      if (xhrAskForBookingChangeSave.readyState == 4 && xhrAskForBookingChangeSave.status == 200) {
        aboutBookingsAskForChangeReady = true;
        window.onbeforeunload = null;
        if (testJSON(xhrAskForBookingChangeSave.response)) {
          var json = JSON.parse(xhrAskForBookingChangeSave.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                aboutBookingsBtnManager("success", "change-data-btn-guests-save");
                aboutMyBookingChangeDataNumOfGuestsRequestNote(json[key]["request"]);
                aboutMyBookingChangeDataPriceNote(json[key]["currency"], json[key]["newTotal"], json[key]["priceDiff"]);
                aboutBookingsChangeGuestsNumModal('hide');
                aMAskForBookingChangeTimer = setTimeout(function(){
                  aboutBookingsBtnManager("def", "change-data-btn-guests-save");
                }, 1000);
              } else if (json[key]["type"] == "error") {
                aboutBookingsBtnManager("def", "change-data-btn-guests-save");
                document.getElementById("change-data-error-txt-guests").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          aboutBookingsBtnManager("def", "change-data-btn-guests-save");
          document.getElementById("change-data-error-txt-guests").innerHTML = xhrAskForBookingChangeSave.response;
        }
      }
    }
    xhrAskForBookingChangeSave.open("POST", "php-backend/ask-for-change-number-of-guests.php");
    xhrAskForBookingChangeSave.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrAskForBookingChangeSave.send("urlId="+ booking_id +"&guests="+ aBookingNumOfGuests);
  }
}

function aboutMyBookingChangeDataNumOfGuestsRequestNote(requestValue) {
  if (requestValue != "org") {
    document.getElementById("about-this-booking-form-request-change-wrp-guests-num").style.display = "table";
    document.getElementById("about-this-booking-form-request-change-txt-bold-guests-num").innerHTML = requestValue;
  } else {
    document.getElementById("about-this-booking-form-request-change-wrp-guests-num").style.display = "";
    document.getElementById("about-this-booking-form-request-change-txt-bold-guests-num").innerHTML = "";
  }
}

function aboutMyBookingChangeDataPriceNote(prcCurrency, newTotal, prcDiff) {
  var newDeposit = 10 * newTotal / 100;
  if (prcDiff != 0) {
    if (document.getElementById("about-this-booking-form-request-change-wrp-deposit") && newDeposit >= 5) {
      document.getElementById("about-this-booking-form-request-change-wrp-deposit").style.display = "table";
      document.getElementById("about-this-booking-form-request-change-txt-bold-deposit").innerHTML = addCurrency(prcCurrency, newDeposit);
    }
    document.getElementById("about-this-booking-form-request-change-wrp-total").style.display = "table";
    document.getElementById("about-this-booking-form-request-change-txt-bold-total").innerHTML = addCurrency(prcCurrency, newTotal);
  } else {
    if (document.getElementById("about-this-booking-form-request-change-wrp-deposit")) {
      document.getElementById("about-this-booking-form-request-change-wrp-deposit").style.display = "";
      document.getElementById("about-this-booking-form-request-change-txt-bold-deposit").innerHTML = "";
    }
    document.getElementById("about-this-booking-form-request-change-wrp-total").style.display = "";
    document.getElementById("about-this-booking-form-request-change-txt-bold-total").innerHTML = "";
  }
}
