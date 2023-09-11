var url_string = window.location.href;
var url = new URL(url_string);
var plc_id = url.searchParams.get("plc");
var bookingUpdateBtnsOnclickReadyArr = {};
var xhrBookingUpdateReject;
function bookingUpdateReject(reqId) {
  if (!(reqId in bookingUpdateBtnsOnclickReadyArr)) {
    bookingUpdateBtnsOnclickReadyTrue(reqId);
  }
  if (bookingUpdateBtnsOnclickReadyArr[reqId]['status']) {
    bookingUpdateBtnsOnclickReadyFalse(reqId);
    bookingUpdateBtnManager("load", "booking-update-btn-reject-"+ reqId);
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    document.getElementById("booking-update-error-wrp-"+ reqId).style.display = "";
    document.getElementById("booking-update-error-txt-"+ reqId).innerHTML = "";
    xhrBookingUpdateReject = new XMLHttpRequest();
    xhrBookingUpdateReject.onreadystatechange = function() {
      if (xhrBookingUpdateReject.readyState == 4 && xhrBookingUpdateReject.status == 200) {
        window.onbeforeunload = null;
        bookingUpdateBtnsOnclickReadyTrue(reqId);
        if (testJSON(xhrBookingUpdateReject.response)) {
          var json = JSON.parse(xhrBookingUpdateReject.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                bookingUpdateBtnManager("success", "booking-update-btn-reject-"+ reqId);
                location.reload();
              } else if (json[key]["type"] == "error") {
                bookingUpdateBtnManager("def", "booking-update-btn-reject-"+ reqId);
                document.getElementById("booking-update-error-wrp-"+ reqId).style.display = "table";
                document.getElementById("booking-update-error-txt-"+ reqId).innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          bookingUpdateBtnManager("def", "booking-update-btn-reject-"+ reqId);
          document.getElementById("booking-update-error-wrp-"+ reqId).style.display = "table";
          document.getElementById("booking-update-error-txt-"+ reqId).innerHTML = xhrBookingUpdateReject.response;
        }
      }
    }
    xhrBookingUpdateReject.open("POST", "php-backend/reject-booking-update-manager.php");
    xhrBookingUpdateReject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrBookingUpdateReject.send("plc_id="+ plc_id +"&req_id="+ reqId);
  }
}

var xhrBookingUpdateConfirm;
var bookingUpdateWChangesDoneSts, bookingUpdatePermissionNeeded, bookingUpdateWChangesErrorSts, bookingUpdateWChangesErrorTxt;
function bookingUpdateConfirmCheck(reqId) {
  if (!(reqId in bookingUpdateBtnsOnclickReadyArr)) {
    bookingUpdateBtnsOnclickReadyTrue(reqId);
  }
  if (bookingUpdateBtnsOnclickReadyArr[reqId]['status']) {
    bookingUpdateBtnsOnclickReadyFalse(reqId);
    bookingUpdateBtnManager("load", "booking-update-btn-confirm-"+ reqId);
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    modCover("hide", "modal-cover-permission-needed");
    document.getElementById("booking-update-error-wrp-"+ reqId).style.display = "";
    document.getElementById("booking-update-error-txt-"+ reqId).innerHTML = "";
    document.getElementById("permission-needed-to-change-list").innerHTML = "";
    bookingUpdatePermissionNeeded = false;
    bookingUpdateWChangesErrorTxt = "";
    bookingUpdateWChangesDoneSts = false;
    bookingUpdateWChangesErrorSts = false;
    xhrBookingUpdateConfirm = new XMLHttpRequest();
    xhrBookingUpdateConfirm.onreadystatechange = function() {
      if (xhrBookingUpdateConfirm.readyState == 4 && xhrBookingUpdateConfirm.status == 200) {
        window.onbeforeunload = null;
        bookingUpdateBtnsOnclickReadyTrue(reqId);
        if (testJSON(xhrBookingUpdateConfirm.response)) {
          var json = JSON.parse(xhrBookingUpdateConfirm.response);
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
            bookingUpdateBtnManager("def", "booking-update-btn-confirm-"+ reqId);
            permissionNeededData(reqId, "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "booking-update-request");
            permissionNeededOnclick("");
            modCover("show", "modal-cover-permission-needed");
          }
          if (bookingUpdateWChangesErrorSts) {
            bookingUpdateBtnManager("def", "booking-update-btn-confirm-"+ reqId);
            document.getElementById("booking-update-error-wrp-"+ reqId).style.display = "table";
            document.getElementById("booking-update-error-txt-"+ reqId).innerHTML = json[key]["error"];
          }
          if (bookingUpdateWChangesDoneSts) {
            bookingUpdateBtnManager("success", "booking-update-btn-confirm-"+ reqId);
            location.reload();
          }
        } else {
          bookingUpdateBtnManager("def", "booking-update-btn-confirm-"+ reqId);
          document.getElementById("booking-update-error-wrp-"+ reqId).style.display = "table";
          document.getElementById("booking-update-error-txt-"+ reqId).innerHTML = xhrBookingUpdateConfirm.response;
        }
      }
    }
    xhrBookingUpdateConfirm.open("POST", "php-backend/confirm-booking-update-manager.php");
    xhrBookingUpdateConfirm.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrBookingUpdateConfirm.send("plc_id="+ plc_id +"&req_id="+ reqId +"&verificated_to_make_changes=no");
  }
}

function bookingUpdateConfirmWChanges(reqId) {
  if (!(reqId in bookingUpdateBtnsOnclickReadyArr)) {
    bookingUpdateBtnsOnclickReadyTrue(reqId);
  }
  if (bookingUpdateBtnsOnclickReadyArr[reqId]['status']) {
    bookingUpdateBtnsOnclickReadyFalse(reqId);
    bookingUpdateBtnManager("load", "booking-update-btn-confirm-"+ reqId);
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    modCover("hide", "modal-cover-permission-needed");
    document.getElementById("booking-update-error-wrp-"+ reqId).style.display = "";
    document.getElementById("booking-update-error-txt-"+ reqId).innerHTML = "";
    document.getElementById("permission-needed-to-change-list").innerHTML = "";
    bookingUpdatePermissionNeeded = false;
    bookingUpdateWChangesErrorTxt = "";
    bookingUpdateWChangesDoneSts = false;
    bookingUpdateWChangesErrorSts = false;
    xhrBookingUpdateConfirm = new XMLHttpRequest();
    xhrBookingUpdateConfirm.onreadystatechange = function() {
      if (xhrBookingUpdateConfirm.readyState == 4 && xhrBookingUpdateConfirm.status == 200) {
        window.onbeforeunload = null;
        bookingUpdateBtnsOnclickReadyTrue(reqId);
        if (testJSON(xhrBookingUpdateConfirm.response)) {
          var json = JSON.parse(xhrBookingUpdateConfirm.response);
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
            bookingUpdateBtnManager("def", "booking-update-btn-confirm-"+ reqId);
            permissionNeededData(reqId, "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "booking-update");
            permissionNeededOnclick("");
            modCover("show", "modal-cover-permission-needed");
          }
          if (bookingUpdateWChangesErrorSts) {
            bookingUpdateBtnManager("def", "booking-update-btn-confirm-"+ reqId);
            document.getElementById("booking-update-error-wrp-"+ reqId).style.display = "table";
            document.getElementById("booking-update-error-txt-"+ reqId).innerHTML = json[key]["error"];
          }
          if (bookingUpdateWChangesDoneSts) {
            bookingUpdateBtnManager("success", "booking-update-btn-confirm-"+ reqId);
            location.reload();
          }
        } else {
          bookingUpdateBtnManager("def", "booking-update-btn-confirm-"+ reqId);
          document.getElementById("booking-update-error-wrp-"+ reqId).style.display = "table";
          document.getElementById("booking-update-error-txt-"+ reqId).innerHTML = xhrBookingUpdateConfirm.response;
        }
      }
    }
    xhrBookingUpdateConfirm.open("POST", "php-backend/confirm-booking-update-manager.php");
    xhrBookingUpdateConfirm.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrBookingUpdateConfirm.send("plc_id="+ plc_id +"&req_id="+ reqId +"&verificated_to_make_changes=yes");
  }
}

function bookingUpdateBtnManager(task, id) {
  if (task == "def") {
    document.getElementById(id).style.color = "#fff";
    document.getElementById(id).style.backgroundImage = "unset";
    document.getElementById(id).style.backgroundSize = "unset";
  } else if (task == "load") {
    document.getElementById(id).style.color = "rgba(0,0,0,0)";
    document.getElementById(id).style.backgroundImage = "url('../uni/gifs/loader-tail.svg')";
    document.getElementById(id).style.backgroundSize = "auto 63%";
  } else if (task == "success") {
    document.getElementById(id).style.color = "rgba(0,0,0,0)";
    document.getElementById(id).style.backgroundImage = "url('../uni/icons/ok2.svg')";
    document.getElementById(id).style.backgroundSize = "auto 47%";
  }
}

function bookingUpdateBtnsOnclickReadyTrue(reqId) {
  if (reqId in bookingUpdateBtnsOnclickReadyArr) {
    bookingUpdateBtnsOnclickReadyArr[reqId]['status'] = true;
  } else {
    bookingUpdateBtnsOnclickReadyArr[reqId] = {
      status: true
    };
  }
}

function bookingUpdateBtnsOnclickReadyFalse(reqId) {
  if (reqId in bookingUpdateBtnsOnclickReadyArr) {
    bookingUpdateBtnsOnclickReadyArr[reqId]['status'] = false;
  } else {
    bookingUpdateBtnsOnclickReadyArr[reqId] = {
      status: false
    };
  }
}
