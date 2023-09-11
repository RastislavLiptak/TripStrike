var aboutBookingsUpdatesReady = true;
function aboutBookingsChangeDatesModal(tsk) {
  if (aboutBookingsUpdatesReady) {
    document.getElementById("change-data-input-from-date").value = document.getElementById("change-data-content-about-txt-d-from-y").innerHTML +"-"+ ("0"+ document.getElementById("change-data-content-about-txt-d-from-m").innerHTML).slice(-2) +"-"+ ("0"+ document.getElementById("change-data-content-about-txt-d-from-d").innerHTML).slice(-2);
    document.getElementById("change-data-input-to-date").value = document.getElementById("change-data-content-about-txt-d-to-y").innerHTML +"-"+ ("0"+ document.getElementById("change-data-content-about-txt-d-to-m").innerHTML).slice(-2) +"-"+ ("0"+ document.getElementById("change-data-content-about-txt-d-to-d").innerHTML).slice(-2);
    document.getElementById("change-data-select-firstday").value = document.getElementById("change-data-content-about-txt-d-firstday").innerHTML;
    document.getElementById("change-data-select-lastday").value = document.getElementById("change-data-content-about-txt-d-lastday").innerHTML;
    document.getElementById("change-data-input-row-new-price-dates").style.display = "";
    document.getElementById("change-data-new-price-txt-dates").innerHTML = "";
    document.getElementById("change-data-input-row-new-price-dates-diff").style.display = "";
    document.getElementById("change-data-new-price-txt-dates-diff").innerHTML = "";
    aboutBookingsBtnManager("def", "change-data-btn-dates-save");
  }
  modCover(tsk, 'modal-cover-change-data-dates');
}

function aboutBookingsChangeGuestsNumModal(tsk) {
  if (aboutBookingsUpdatesReady) {
    document.getElementById("change-data-input-number-of-guests").value = document.getElementById("change-data-content-about-txt-g-num-of-guests").innerHTML;
    if (document.getElementById("change-data-input-row-new-price-guests")) {
      document.getElementById("change-data-input-row-new-price-guests").style.display = "";
      document.getElementById("change-data-new-price-txt-guests").innerHTML = "";
      document.getElementById("change-data-input-row-new-price-guests-diff").style.display = "";
      document.getElementById("change-data-new-price-txt-guests-diff").innerHTML = "";
    }
    aboutBookingsBtnManager("def", "change-data-btn-guests-save");
  }
  modCover(tsk, 'modal-cover-change-data-number-of-guests');
}

var newPrice = 0;
function aboutGuestBookingChangeDataDatesPrice() {
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

function aboutGuestBookingChangeDataNumOfGuestsPrice() {
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

function aboutBookingsPriceCheckboxHandler(inpt) {
  if (document.getElementById("about-this-booking-form-checkbox-deposit")) {
    if (inpt == "deposit") {
      if (!document.getElementById("about-this-booking-form-checkbox-deposit").checked) {
        document.getElementById("about-this-booking-form-checkbox-total-price").checked = false;
      }
    } else {
      if (document.getElementById("about-this-booking-form-checkbox-total-price").checked) {
        document.getElementById("about-this-booking-form-checkbox-deposit").checked = true;
      }
    }
  }
}

var plc_id = "-";
function aboutGuestBookingsPlcID(plcID) {
  plc_id = plcID;
}

var bookingUpdateRequestBtnsOnclickReadyArr = {};
var xhrbookingUpdateRequestReject;
function bookingUpdateRequestReject(reqId) {
  if (!(reqId in bookingUpdateRequestBtnsOnclickReadyArr)) {
    bookingUpdateRequestBtnsOnclickReadyTrue(reqId);
  }
  if (bookingUpdateRequestBtnsOnclickReadyArr[reqId]['status'] && aboutBookingsUpdatesReady) {
    bookingUpdateRequestBtnsOnclickReadyFalse(reqId);
    aboutBookingsBtnManager("load", "about-this-booking-about-update-request-footer-btn-reject-"+ reqId);
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    document.getElementById("about-this-booking-about-update-request-error-wrp-"+ reqId).style.display = "";
    document.getElementById("about-this-booking-about-update-request-error-txt-"+ reqId).innerHTML = "";
    xhrbookingUpdateRequestReject = new XMLHttpRequest();
    xhrbookingUpdateRequestReject.onreadystatechange = function() {
      if (xhrbookingUpdateRequestReject.readyState == 4 && xhrbookingUpdateRequestReject.status == 200) {
        window.onbeforeunload = null;
        bookingUpdateRequestBtnsOnclickReadyTrue(reqId);
        if (testJSON(xhrbookingUpdateRequestReject.response)) {
          var json = JSON.parse(xhrbookingUpdateRequestReject.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                aboutBookingsBtnManager("success", "about-this-booking-about-update-request-footer-btn-reject-"+ reqId);
                location.reload();
              } else if (json[key]["type"] == "error") {
                aboutBookingsBtnManager("def", "about-this-booking-about-update-request-footer-btn-reject-"+ reqId);
                document.getElementById("about-this-booking-about-update-request-error-wrp-"+ reqId).style.display = "table";
                document.getElementById("about-this-booking-about-update-request-error-txt-"+ reqId).innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          aboutBookingsBtnManager("def", "about-this-booking-about-update-request-footer-btn-reject-"+ reqId);
          document.getElementById("about-this-booking-about-update-request-error-wrp-"+ reqId).style.display = "table";
          document.getElementById("about-this-booking-about-update-request-error-txt-"+ reqId).innerHTML = xhrbookingUpdateRequestReject.response;
        }
      }
    }
    xhrbookingUpdateRequestReject.open("POST", "php-backend/reject-booking-update-manager.php");
    xhrbookingUpdateRequestReject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrbookingUpdateRequestReject.send("plc_id="+ plc_id +"&req_id="+ reqId);
  }
}

var xhrBookingUpdateRequestConfirm;
var bookingUpdateWChangesDoneSts, bookingUpdatePermissionNeeded, bookingUpdateWChangesErrorSts, bookingUpdateWChangesErrorTxt;
function bookingUpdateRequestConfirmCheck(reqId) {
  if (!(reqId in bookingUpdateRequestBtnsOnclickReadyArr)) {
    bookingUpdateRequestBtnsOnclickReadyTrue(reqId);
  }
  if (bookingUpdateRequestBtnsOnclickReadyArr[reqId]['status'] && aboutBookingsUpdatesReady) {
    bookingUpdateRequestBtnsOnclickReadyFalse(reqId);
    aboutBookingsBtnManager("load", "about-this-booking-about-update-request-footer-btn-confirm-"+ reqId);
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    modCover("hide", "modal-cover-permission-needed");
    document.getElementById("about-this-booking-about-update-request-error-wrp-"+ reqId).style.display = "";
    document.getElementById("about-this-booking-about-update-request-error-txt-"+ reqId).innerHTML = "";
    document.getElementById("permission-needed-to-change-list").innerHTML = "";
    bookingUpdatePermissionNeeded = false;
    bookingUpdateWChangesErrorTxt = "";
    bookingUpdateWChangesDoneSts = false;
    bookingUpdateWChangesErrorSts = false;
    xhrBookingUpdateRequestConfirm = new XMLHttpRequest();
    xhrBookingUpdateRequestConfirm.onreadystatechange = function() {
      if (xhrBookingUpdateRequestConfirm.readyState == 4 && xhrBookingUpdateRequestConfirm.status == 200) {
        window.onbeforeunload = null;
        bookingUpdateRequestBtnsOnclickReadyTrue(reqId);
        if (testJSON(xhrBookingUpdateRequestConfirm.response)) {
          var json = JSON.parse(xhrBookingUpdateRequestConfirm.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                bookingUpdateWChangesDoneSts = true;
              } else if (json[key]["type"] == "permission-needed") {
                if (json[key]["data"] == "booking") {
                  permissionNeededBookingRender(json[key]["task"], json[key]["name"], json[key]["email"], json[key]["phone"], json[key]["from"], json[key]["to"]);
                } else {
                  permissionNeededTechnicalShutdownRender(json[key]["task"], json[key]["category"], json[key]["notes"], json[key]["from"], json[key]["to"]);
                }
                bookingUpdatePermissionNeeded = true;
              } else if (json[key]["type"] == "error") {
                bookingUpdateWChangesErrorSts = true;
                bookingUpdateWChangesErrorTxt = bookingUpdateWChangesErrorTxt +""+ json[key]["error"] +"<br>";
              }
            }
          }
          if (bookingUpdatePermissionNeeded) {
            aboutBookingsBtnManager("def", "about-this-booking-about-update-request-footer-btn-confirm-"+ reqId);
            permissionNeededData(reqId, "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "booking-update-request");
            permissionNeededOnclick("");
            modCover("show", "modal-cover-permission-needed");
          }
          if (bookingUpdateWChangesErrorSts) {
            aboutBookingsBtnManager("def", "about-this-booking-about-update-request-footer-btn-confirm-"+ reqId);
            document.getElementById("about-this-booking-about-update-request-error-wrp-"+ reqId).style.display = "table";
            document.getElementById("about-this-booking-about-update-request-error-txt-"+ reqId).innerHTML = json[key]["error"];
          }
          if (bookingUpdateWChangesDoneSts) {
            aboutBookingsBtnManager("success", "about-this-booking-about-update-request-footer-btn-confirm-"+ reqId);
            location.reload();
          }
        } else {
          aboutBookingsBtnManager("def", "about-this-booking-about-update-request-footer-btn-confirm-"+ reqId);
          document.getElementById("about-this-booking-about-update-request-error-wrp-"+ reqId).style.display = "table";
          document.getElementById("about-this-booking-about-update-request-error-txt-"+ reqId).innerHTML = xhrBookingUpdateRequestConfirm.response;
        }
      }
    }
    xhrBookingUpdateRequestConfirm.open("POST", "php-backend/confirm-booking-update-manager.php");
    xhrBookingUpdateRequestConfirm.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrBookingUpdateRequestConfirm.send("plc_id="+ plc_id +"&req_id="+ reqId +"&verificated_to_make_changes=no");
  }
}

function bookingUpdateConfirmWChanges(reqId) {
  if (!(reqId in bookingUpdateRequestBtnsOnclickReadyArr)) {
    bookingUpdateRequestBtnsOnclickReadyTrue(reqId);
  }
  if (bookingUpdateRequestBtnsOnclickReadyArr[reqId]['status'] && aboutBookingsUpdatesReady) {
    bookingUpdateRequestBtnsOnclickReadyFalse(reqId);
    aboutBookingsBtnManager("load", "about-this-booking-about-update-request-footer-btn-confirm-"+ reqId);
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    modCover("hide", "modal-cover-permission-needed");
    document.getElementById("about-this-booking-about-update-request-error-wrp-"+ reqId).style.display = "";
    document.getElementById("about-this-booking-about-update-request-error-txt-"+ reqId).innerHTML = "";
    document.getElementById("permission-needed-to-change-list").innerHTML = "";
    bookingUpdatePermissionNeeded = false;
    bookingUpdateWChangesErrorTxt = "";
    bookingUpdateWChangesDoneSts = false;
    bookingUpdateWChangesErrorSts = false;
    xhrBookingUpdateRequestConfirm = new XMLHttpRequest();
    xhrBookingUpdateRequestConfirm.onreadystatechange = function() {
      if (xhrBookingUpdateRequestConfirm.readyState == 4 && xhrBookingUpdateRequestConfirm.status == 200) {
        window.onbeforeunload = null;
        bookingUpdateRequestBtnsOnclickReadyTrue(reqId);
        if (testJSON(xhrBookingUpdateRequestConfirm.response)) {
          var json = JSON.parse(xhrBookingUpdateRequestConfirm.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                bookingUpdateWChangesDoneSts = true;
              } else if (json[key]["type"] == "permission-needed") {
                if (json[key]["data"] == "booking") {
                  permissionNeededBookingRender(json[key]["task"], json[key]["name"], json[key]["email"], json[key]["phone"], json[key]["from"], json[key]["to"]);
                } else {
                  permissionNeededTechnicalShutdownRender(json[key]["task"], json[key]["category"], json[key]["notes"], json[key]["from"], json[key]["to"]);
                }
                bookingUpdatePermissionNeeded = true;
              } else if (json[key]["type"] == "error") {
                bookingUpdateWChangesErrorSts = true;
                bookingUpdateWChangesErrorTxt = bookingUpdateWChangesErrorTxt +""+ json[key]["error"] +"<br>";
              }
            }
          }
          if (bookingUpdatePermissionNeeded) {
            aboutBookingsBtnManager("def", "about-this-booking-about-update-request-footer-btn-confirm-"+ reqId);
            permissionNeededData(reqId, "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "booking-update");
            permissionNeededOnclick("");
            modCover("show", "modal-cover-permission-needed");
          }
          if (bookingUpdateWChangesErrorSts) {
            aboutBookingsBtnManager("def", "about-this-booking-about-update-request-footer-btn-confirm-"+ reqId);
            document.getElementById("about-this-booking-about-update-request-error-wrp-"+ reqId).style.display = "table";
            document.getElementById("about-this-booking-about-update-request-error-txt-"+ reqId).innerHTML = json[key]["error"];
          }
          if (bookingUpdateWChangesDoneSts) {
            aboutBookingsBtnManager("success", "about-this-booking-about-update-request-footer-btn-confirm-"+ reqId);
            location.reload();
          }
        } else {
          aboutBookingsBtnManager("def", "about-this-booking-about-update-request-footer-btn-confirm-"+ reqId);
          document.getElementById("about-this-booking-about-update-request-error-wrp-"+ reqId).style.display = "table";
          document.getElementById("about-this-booking-about-update-request-error-txt-"+ reqId).innerHTML = xhrBookingUpdateRequestConfirm.response;
        }
      }
    }
    xhrBookingUpdateRequestConfirm.open("POST", "php-backend/confirm-booking-update-manager.php");
    xhrBookingUpdateRequestConfirm.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrBookingUpdateRequestConfirm.send("plc_id="+ plc_id +"&req_id="+ reqId +"&verificated_to_make_changes=yes");
  }
}

function bookingUpdateRequestBtnsOnclickReadyTrue(reqId) {
  if (reqId in bookingUpdateRequestBtnsOnclickReadyArr) {
    bookingUpdateRequestBtnsOnclickReadyArr[reqId]['status'] = true;
  } else {
    bookingUpdateRequestBtnsOnclickReadyArr[reqId] = {
      status: true
    };
  }
}

function bookingUpdateRequestBtnsOnclickReadyFalse(reqId) {
  if (reqId in bookingUpdateRequestBtnsOnclickReadyArr) {
    bookingUpdateRequestBtnsOnclickReadyArr[reqId]['status'] = false;
  } else {
    bookingUpdateRequestBtnsOnclickReadyArr[reqId] = {
      status: false
    };
  }
}

var xhrBookingCancel;
function aboutCancelBooking(fromd, fromm, fromy, tod, tom, toy) {
  if (aboutBookingsUpdatesReady) {
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    modCover('hide', 'modal-cover-cancel-booking');
    aboutBookingsUpdatesReady = false;
    aboutBookingsBtnManager("load", "about-this-booking-btn-cancel-booking");
    document.getElementById("about-this-booking-error-wrp").style.display = "";
    document.getElementById("about-this-booking-error-txt").innerHTML = "";
    xhrBookingCancel = new XMLHttpRequest();
    xhrBookingCancel.onreadystatechange = function() {
      if (xhrBookingCancel.readyState == 4 && xhrBookingCancel.status == 200) {
        window.onbeforeunload = null;
        aboutBookingsUpdatesReady = true;
        if (testJSON(xhrBookingCancel.response)) {
          var json = JSON.parse(xhrBookingCancel.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                console.log("test");
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
    xhrBookingCancel.open("POST", "../editor/php-backend/cancel-booking-manager.php");
    xhrBookingCancel.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrBookingCancel.send("plc_id="+ plc_id +"&f_d="+ fromd +"&f_m="+ fromm +"&f_y="+ fromy +"&t_d="+ tod +"&t_m="+ tom +"&t_y="+ toy);
  }
}

var xhrSaveBookingUpdateCheck;
var f_d, f_m, f_y, t_d, t_m, t_y, uBName, uBEmail, uBPhone, uBGuest, uBNotes, uBF_d, uBF_m, uBF_y, uBT_d, uBT_m, uBT_y, uFromAvailability, uToAvailability, aboutBookingUpdateOutputTime, updateBookingPermissionNeeded, aboutBookingUpdateWChangesErrorTxt, aboutBookingUpdateWChangesDoneSts, aboutBookingUpdateWChangesErrorSts;
function aboutSaveBooking() {
  if (aboutBookingsUpdatesReady) {
    uBName = document.getElementById("about-this-booking-form-textarea-name").value;
    uBEmail = document.getElementById("about-this-booking-form-textarea-email").value;
    uBPhone = document.getElementById("about-this-booking-form-textarea-phone").value.replace(/\+/g, "plus");
    uBGuest = document.getElementById("change-data-input-number-of-guests").value;
    uBNotes = document.getElementById("about-booking-facility-textarea-notes").value;
    uBF_d = new Date(document.getElementById("change-data-input-from-date").value).getDate();
    uBF_m = new Date(document.getElementById("change-data-input-from-date").value).getMonth() +1;
    uBF_y = new Date(document.getElementById("change-data-input-from-date").value).getFullYear();
    uBT_d = new Date(document.getElementById("change-data-input-to-date").value).getDate();
    uBT_m = new Date(document.getElementById("change-data-input-to-date").value).getMonth() +1;
    uBT_y = new Date(document.getElementById("change-data-input-to-date").value).getFullYear();
    f_d = document.getElementById("change-data-content-about-txt-d-from-d").innerHTML;
    f_m = document.getElementById("change-data-content-about-txt-d-from-m").innerHTML;
    f_y = document.getElementById("change-data-content-about-txt-d-from-y").innerHTML;
    t_d = document.getElementById("change-data-content-about-txt-d-to-d").innerHTML;
    t_m = document.getElementById("change-data-content-about-txt-d-to-m").innerHTML;
    t_y = document.getElementById("change-data-content-about-txt-d-to-y").innerHTML;
    if (document.getElementById("change-data-select-firstday").value == "half") {
      uFromAvailability = "half";
    } else {
      uFromAvailability = "whole";
    }
    if (document.getElementById("change-data-select-lastday").value == "half") {
      uToAvailability = "half";
    } else {
      uToAvailability = "whole";
    }
    if (document.getElementById("about-this-booking-form-checkbox-deposit")) {
      if (document.getElementById("about-this-booking-form-checkbox-deposit").checked) {
        uDepositSts = 1;
      } else {
        uDepositSts = 0;
      }
    } else {
      if (document.getElementById("about-this-booking-form-checkbox-total-price").checked) {
        uDepositSts = 1;
      } else {
        uDepositSts = 0;
      }
    }
    if (document.getElementById("about-this-booking-form-checkbox-total-price").checked) {
      uFullAmountSts = 1;
    } else {
      uFullAmountSts = 0;
    }
    if (Number.isInteger(uBF_d) && Number.isInteger(uBF_m) && Number.isInteger(uBF_y) && Number.isInteger(uBT_d) && Number.isInteger(uBT_m) && Number.isInteger(uBT_y)) {
      window.onbeforeunload = function(event) {
        event.returnValue = "Your changes may not be saved.";
      };
      aboutBookingsUpdatesReady = false;
      aboutBookingsBtnManager("load", "about-this-booking-btn-save");
      document.getElementById("about-this-booking-error-wrp").style.display = "";
      document.getElementById("about-this-booking-error-txt").innerHTML = "";
      document.getElementById("permission-needed-to-change-list").innerHTML = "";
      updateBookingPermissionNeeded = false;
      aboutBookingUpdateWChangesErrorTxt = "";
      aboutBookingUpdateWChangesDoneSts = false;
      aboutBookingUpdateWChangesErrorSts = false;
      xhrSaveBookingUpdateCheck = new XMLHttpRequest();
      xhrSaveBookingUpdateCheck.onreadystatechange = function() {
        if (xhrSaveBookingUpdateCheck.readyState == 4 && xhrSaveBookingUpdateCheck.status == 200) {
          window.onbeforeunload = null;
          aboutBookingsUpdatesReady = true;
          if (testJSON(xhrSaveBookingUpdateCheck.response)) {
            var json = JSON.parse(xhrSaveBookingUpdateCheck.response);
            for (var key in json) {
              if (json.hasOwnProperty(key)) {
                if (json[key]["type"] == "done") {
                  aboutBookingUpdateWChangesDoneSts = true;
                } else if (json[key]["type"] == "permission-needed") {
                  if (json[key]["data"] == "booking") {
                    permissionNeededBookingRender(json[key]["task"], json[key]["name"], json[key]["email"], json[key]["phone"], json[key]["from"], json[key]["to"]);
                  } else {
                    permissionNeededTechnicalShutdownRender(json[key]["task"], json[key]["category"], json[key]["notes"], json[key]["from"], json[key]["to"]);
                  }
                  aboutBookingsBtnManager("def", "about-this-booking-btn-save");
                  updateBookingPermissionNeeded = true;
                } else if (json[key]["type"] == "error") {
                  aboutBookingUpdateWChangesErrorSts = true;
                  aboutBookingUpdateWChangesErrorTxt = aboutBookingUpdateWChangesErrorTxt +""+ json[key]["error"] +"<br>";
                }
              }
            }
            if (updateBookingPermissionNeeded) {
              permissionNeededData("", f_d, f_m, f_y, t_d, t_m, t_y, uBName, uBEmail, uBPhone, uBGuest, uBNotes, uBF_d, uBF_m, uBF_y, uBT_d, uBT_m, uBT_y, uFromAvailability, uToAvailability, uDepositSts, uFullAmountSts, "", "update-booking");
              permissionNeededOnclick("");
              modCover("show", "modal-cover-permission-needed");
            }
            if (aboutBookingUpdateWChangesErrorSts) {
              if (aboutBookingUpdateWChangesDoneSts) {
                document.getElementById("about-this-booking-error-wrp").style.display = "table";
                document.getElementById("about-this-booking-error-txt").innerHTML = "Task is done <br>"+ aboutBookingUpdateWChangesErrorTxt;
              } else {
                aboutBookingsBtnManager("def", "about-this-booking-btn-save");
                document.getElementById("about-this-booking-error-wrp").style.display = "table";
                document.getElementById("about-this-booking-error-txt").innerHTML = aboutBookingUpdateWChangesErrorTxt;
              }
            }
            if (aboutBookingUpdateWChangesDoneSts) {
              aboutBookingsBtnManager("success", "about-this-booking-btn-save");
              location.reload();
              clearTimeout(aboutBookingUpdateOutputTime);
              aboutBookingUpdateOutputTime = setTimeout(function(){
                aboutBookingsBtnManager("def", "about-this-booking-btn-save");
              }, 1000);
            }
          } else {
            aboutBookingsBtnManager("def", "about-this-booking-btn-save");
            document.getElementById("about-this-booking-error-wrp").style.display = "table";
            document.getElementById("about-this-booking-error-txt").innerHTML = xhrSaveBookingUpdateCheck.response;
          }
        }
      }
      xhrSaveBookingUpdateCheck.open("POST", "../editor/php-backend/update-booking-manager.php");
      xhrSaveBookingUpdateCheck.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhrSaveBookingUpdateCheck.send("verificated_to_make_changes=no&plc_id="+ plc_id +"&name="+ uBName +"&email="+ uBEmail +"&phone="+ uBPhone +"&guest="+ uBGuest +"&notes="+ uBNotes +"&org_f_d="+ f_d +"&org_f_m="+ f_m +"&org_f_y="+ f_y +"&org_t_d="+ t_d +"&org_t_m="+ t_m +"&org_t_y="+ t_y +"&f_d="+ uBF_d +"&f_m="+ uBF_m +"&f_y="+ uBF_y +"&fromAvailability="+ uFromAvailability +"&t_d="+ uBT_d +"&t_m="+ uBT_m +"&t_y="+ uBT_y +"&toAvailability="+ uToAvailability +"&deposit="+ uDepositSts +"&fullAmount="+ uFullAmountSts);
    } else {
      document.getElementById("about-this-booking-error-wrp").style.display = "table";
      document.getElementById("about-this-booking-error-txt").innerHTML = "You have to select starting and ending day of the booking";
    }
  }
}

var xhrSaveBookingUpdate;
function editorCalendarUpdateBookingWChanges(f_d, f_m, f_y, t_d, t_m, t_y) {
  if (aboutBookingsUpdatesReady) {
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    uBName = document.getElementById("permission-needed-to-change-accept-name").textContent;
    uBEmail = document.getElementById("permission-needed-to-change-accept-email").textContent;
    uBPhone = document.getElementById("permission-needed-to-change-accept-phone").textContent;
    uBGuest = document.getElementById("permission-needed-to-change-accept-guests").textContent;
    uBNotes = document.getElementById("permission-needed-to-change-accept-notes").textContent;
    uBF_d = document.getElementById("permission-needed-to-change-accept-f-d").textContent;
    uBF_m = document.getElementById("permission-needed-to-change-accept-f-m").textContent;
    uBF_y = document.getElementById("permission-needed-to-change-accept-f-y").textContent;
    uBT_d = document.getElementById("permission-needed-to-change-accept-t-d").textContent;
    uBT_m = document.getElementById("permission-needed-to-change-accept-t-m").textContent;
    uBT_y = document.getElementById("permission-needed-to-change-accept-t-y").textContent;
    uFromAvailability = document.getElementById("permission-needed-to-change-accept-firstday").textContent;
    uToAvailability = document.getElementById("permission-needed-to-change-accept-lastday").textContent;
    uDepositSts = document.getElementById("permission-needed-to-change-accept-deposit").textContent;
    uFullAmountSts = document.getElementById("permission-needed-to-change-accept-full-amount").textContent;
    f_d = document.getElementById("change-data-content-about-txt-d-from-d").innerHTML;
    f_m = document.getElementById("change-data-content-about-txt-d-from-m").innerHTML;
    f_y = document.getElementById("change-data-content-about-txt-d-from-y").innerHTML;
    t_d = document.getElementById("change-data-content-about-txt-d-to-d").innerHTML;
    t_m = document.getElementById("change-data-content-about-txt-d-to-m").innerHTML;
    t_y = document.getElementById("change-data-content-about-txt-d-to-y").innerHTML;
    modCover("hide", "modal-cover-permission-needed");
    aboutBookingsUpdatesReady = false;
    aboutBookingsBtnManager("load", "about-this-booking-btn-save");
    document.getElementById("about-this-booking-error-wrp").style.display = "";
    document.getElementById("about-this-booking-error-txt").innerHTML = "";
    aboutBookingUpdateWChangesErrorTxt = "";
    aboutBookingUpdateWChangesDoneSts = false;
    aboutBookingUpdateWChangesErrorSts = false;
    xhrSaveBookingUpdate = new XMLHttpRequest();
    xhrSaveBookingUpdate.onreadystatechange = function() {
      if (xhrSaveBookingUpdate.readyState == 4 && xhrSaveBookingUpdate.status == 200) {
        window.onbeforeunload = null;
        aboutBookingsUpdatesReady = true;
        if (testJSON(xhrSaveBookingUpdate.response)) {
          var json = JSON.parse(xhrSaveBookingUpdate.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                aboutBookingUpdateWChangesDoneSts = true;
              } else if (json[key]["type"] == "error") {
                aboutBookingUpdateWChangesErrorSts = true;
                aboutBookingUpdateWChangesErrorTxt = aboutBookingUpdateWChangesErrorTxt +""+ json[key]["error"] +"<br>";
              }
            }
          }
          if (aboutBookingUpdateWChangesErrorSts) {
            if (aboutBookingUpdateWChangesDoneSts) {
              document.getElementById("about-this-booking-error-wrp").style.display = "table";
              document.getElementById("about-this-booking-error-txt").innerHTML = "Task is done <br>"+ aboutBookingUpdateWChangesErrorTxt;
            } else {
              aboutBookingsBtnManager("def", "about-this-booking-btn-save");
              document.getElementById("about-this-booking-error-wrp").style.display = "table";
              document.getElementById("about-this-booking-error-txt").innerHTML = aboutBookingUpdateWChangesErrorTxt;
            }
          }
          if (aboutBookingUpdateWChangesDoneSts) {
            aboutBookingsBtnManager("success", "about-this-booking-btn-save");
            location.reload();
            clearTimeout(aboutBookingUpdateOutputTime);
            aboutBookingUpdateOutputTime = setTimeout(function(){
              aboutBookingsBtnManager("def", "about-this-booking-btn-save");
            }, 1000);
          }
        } else {
          aboutBookingsBtnManager("def", "about-this-booking-btn-save");
          document.getElementById("about-this-booking-error-wrp").style.display = "table";
          document.getElementById("about-this-booking-error-txt").innerHTML = xhrSaveBookingUpdate.response;
        }
      }
    }
    xhrSaveBookingUpdate.open("POST", "../editor/php-backend/update-booking-manager.php");
    xhrSaveBookingUpdate.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrSaveBookingUpdate.send("verificated_to_make_changes=yes&plc_id="+ plc_id +"&name="+ uBName +"&email="+ uBEmail +"&phone="+ uBPhone +"&guest="+ uBGuest +"&notes="+ uBNotes +"&org_f_d="+ f_d +"&org_f_m="+ f_m +"&org_f_y="+ f_y +"&org_t_d="+ t_d +"&org_t_m="+ t_m +"&org_t_y="+ t_y +"&f_d="+ uBF_d +"&f_m="+ uBF_m +"&f_y="+ uBF_y +"&fromAvailability="+ uFromAvailability +"&t_d="+ uBT_d +"&t_m="+ uBT_m +"&t_y="+ uBT_y +"&toAvailability="+ uToAvailability +"&deposit="+ uDepositSts +"&fullAmount="+ uFullAmountSts);
  }
}










var xhrRejectBooking;
function aboutRejectBooking(f_d, f_m, f_y, t_d, t_m, t_y) {
  if (aboutBookingsUpdatesReady) {
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    aboutBookingsUpdatesReady = false;
    aboutBookingsBtnManager("load", "about-this-booking-btn-reject");
    document.getElementById("about-this-booking-error-wrp").style.display = "";
    document.getElementById("about-this-booking-error-txt").innerHTML = "";
    xhrRejectBooking = new XMLHttpRequest();
    xhrRejectBooking.onreadystatechange = function() {
      if (xhrRejectBooking.readyState == 4 && xhrRejectBooking.status == 200) {
        window.onbeforeunload = null;
        aboutBookingsUpdatesReady = true;
        if (testJSON(xhrRejectBooking.response)) {
          var json = JSON.parse(xhrRejectBooking.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                aboutBookingsBtnManager("success", "about-this-booking-btn-reject");
                location.reload();
              } else if (json[key]["type"] == "error") {
                aboutBookingsBtnManager("def", "about-this-booking-btn-reject");
                document.getElementById("about-this-booking-error-wrp").style.display = "table";
                document.getElementById("about-this-booking-error-txt").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          aboutBookingsBtnManager("def", "about-this-booking-btn-reject");
          document.getElementById("about-this-booking-error-wrp").style.display = "table";
          document.getElementById("about-this-booking-error-txt").innerHTML = xhrRejectBooking.response;
        }
      }
    }
    xhrRejectBooking.open("POST", "php-backend/reject-booking-manager.php");
    xhrRejectBooking.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrRejectBooking.send("plc_id="+ plc_id +"&f_d="+ f_d +"&f_m="+ f_m +"&f_y="+ f_y +"&t_d="+ t_d +"&t_m="+ t_m +"&t_y="+ t_y);
  }
}

var xhrConfirmBooking;
function aboutConfirmBooking(f_d, f_m, f_y, t_d, t_m, t_y) {
  if (aboutBookingsUpdatesReady) {
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    aboutBookingsUpdatesReady = false;
    aboutBookingsBtnManager("load", "about-this-booking-btn-confirm");
    document.getElementById("about-this-booking-error-wrp").style.display = "";
    document.getElementById("about-this-booking-error-txt").innerHTML = "";
    xhrConfirmBooking = new XMLHttpRequest();
    xhrConfirmBooking.onreadystatechange = function() {
      if (xhrConfirmBooking.readyState == 4 && xhrConfirmBooking.status == 200) {
        window.onbeforeunload = null;
        aboutBookingsUpdatesReady = true;
        if (testJSON(xhrConfirmBooking.response)) {
          var json = JSON.parse(xhrConfirmBooking.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                aboutBookingsBtnManager("success", "about-this-booking-btn-confirm");
                location.reload();
              } else if (json[key]["type"] == "error") {
                aboutBookingsBtnManager("def", "about-this-booking-btn-confirm");
                document.getElementById("about-this-booking-error-wrp").style.display = "table";
                document.getElementById("about-this-booking-error-txt").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          aboutBookingsBtnManager("def", "about-this-booking-btn-confirm");
          document.getElementById("about-this-booking-error-wrp").style.display = "table";
          document.getElementById("about-this-booking-error-txt").innerHTML = xhrConfirmBooking.response;
        }
      }
    }
    xhrConfirmBooking.open("POST", "php-backend/confirm-booking-manager.php");
    xhrConfirmBooking.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrConfirmBooking.send("plc_id="+ plc_id +"&f_d="+ f_d +"&f_m="+ f_m +"&f_y="+ f_y +"&t_d="+ t_d +"&t_m="+ t_m +"&t_y="+ t_y);
  }
}
