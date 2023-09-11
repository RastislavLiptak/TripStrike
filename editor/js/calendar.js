function editorCalendarDaySelect(wrpId) {
  editorCalendarDateDataHandler(
    fromToObject[wrpId]['from_d'],
    fromToObject[wrpId]['from_m'],
    fromToObject[wrpId]['from_y'],
    fromToObject[wrpId]['to_d'],
    fromToObject[wrpId]['to_m'],
    fromToObject[wrpId]['to_y']
  );
  editorCalendarManagerBtns();
}

var from_d = "";
var from_m = "";
var from_y = "";
var to_d = "";
var to_m = "";
var to_y = "";
function editorCalendarDateDataHandler(fromD, fromM, fromY, toD, toM, toY) {
  if (fromY != null && fromM != null && fromD != null) {
    from_d = fromD;
    from_m = fromM;
    from_y = fromY;
  } else {
    from_d = "";
    from_m = "";
    from_y = "";
  }
  if (toY != null && toM != null && toD != null) {
    to_d = toD;
    to_m = toM;
    to_y = toY;
  } else {
    to_d = "";
    to_m = "";
    to_y = "";
  }
}

function editorCalendarManagerBtns() {
  if (to_y != "" && to_m != "" && to_d != "") {
    document.getElementById("editor-content-calendar-btn-technical-shutdown").disabled = false;
    document.getElementById("editor-content-calendar-btn-add-booking").disabled = false;
    document.getElementById("editor-content-calendar-btn-date-details").disabled = true;
  } else if (from_y != "" && from_m != "" && from_d != "") {
    document.getElementById("editor-content-calendar-btn-technical-shutdown").disabled = false;
    document.getElementById("editor-content-calendar-btn-add-booking").disabled = false;
    document.getElementById("editor-content-calendar-btn-date-details").disabled = false;
  } else {
    document.getElementById("editor-content-calendar-btn-technical-shutdown").disabled = true;
    document.getElementById("editor-content-calendar-btn-add-booking").disabled = true;
    document.getElementById("editor-content-calendar-btn-date-details").disabled = true;
  }
}

var editTechnicalShutdownReady = true;
function editorCalendarTechnicalShutdownModal(tsk) {
  if (editTechnicalShutdownReady) {
    if (from_d != "" && from_m != "" && from_y != "") {
      document.getElementById("m-e-c-f-error-wrp-technical-shutdown").style.display = "";
      document.getElementById("m-e-c-f-error-txt-technical-shutdown").innerHTML = "";
      document.getElementById("m-e-c-f-input-from-technical-shutdown").value = from_y +"-"+ ("0"+ from_m).slice(-2) +"-"+ ("0"+ from_d).slice(-2);
      if (to_d != "" && to_m != "" && to_y != "") {
        document.getElementById("m-e-c-f-input-to-technical-shutdown").value = to_y +"-"+ ("0"+ to_m).slice(-2) +"-"+ ("0"+ to_d).slice(-2);
      } else {
        document.getElementById("m-e-c-f-input-to-technical-shutdown").value = "";
      }
      editorInputDateDropdownSelectOnchange('from', 1, 'technical-shutdown');
      editorInputDateDropdownSelectOnchange('to', 1, 'technical-shutdown');
      document.getElementById('m-e-c-f-select-technical-shutdown').value = document.getElementsByClassName("m-e-c-f-select-option-technical-shutdown")[0].value;
      document.getElementById("m-e-c-f-textarea-notes-technical-shutdown").value = "";
      editorBtnHandler("def", "modal-editor-content-form-footer-btn-technical-shutdown");
      document.getElementById("modal-editor-content-scroll-technical-shutdown").scrollTop = 0;
      modCover(tsk, 'modal-cover-editor-calendar-technical-shutdown');
    } else {
      modCover("hide", 'modal-cover-editor-calendar-technical-shutdown');
    }
  }
}

var xhrAddTechnicalShutdownCheck;
var aTSCategory, aTSNotes, aTSF_d, aTSF_m, aTSF_y, aTST_d, aTST_m, aTST_y, aTSFromAvailability, aTSToAvailability, addTechnicalShutdownPermissionNeeded, editorCalendarAddTechnicalShutdownWChangesErrorTxt, editorAddTechnicalShutdownOutputTime, editorCalendarAddTechnicalShutdownWChangesDoneSts, editorCalendarAddTechnicalShutdownWChangesErrorSts;
function editorCalendarAddTechnicalShutdownCheck() {
  if (editTechnicalShutdownReady) {
    aTSCategory = document.getElementById("m-e-c-f-select-technical-shutdown").value;
    aTSNotes = document.getElementById("m-e-c-f-textarea-notes-technical-shutdown").value;
    aTSF_d = new Date(document.getElementById("m-e-c-f-input-from-technical-shutdown").value).getDate();
    aTSF_m = new Date(document.getElementById("m-e-c-f-input-from-technical-shutdown").value).getMonth() +1;
    aTSF_y = new Date(document.getElementById("m-e-c-f-input-from-technical-shutdown").value).getFullYear();
    aTST_d = new Date(document.getElementById("m-e-c-f-input-to-technical-shutdown").value).getDate();
    aTST_m = new Date(document.getElementById("m-e-c-f-input-to-technical-shutdown").value).getMonth() +1;
    aTST_y = new Date(document.getElementById("m-e-c-f-input-to-technical-shutdown").value).getFullYear();
    if (document.getElementById("m-e-c-f-dropdown-radio-from-1-technical-shutdown").checked) {
      aTSFromAvailability = "half";
    } else {
      aTSFromAvailability = "whole";
    }
    if (document.getElementById("m-e-c-f-dropdown-radio-to-1-technical-shutdown").checked) {
      aTSToAvailability = "half";
    } else {
      aTSToAvailability = "whole";
    }
    if (Number.isInteger(aTSF_d) && Number.isInteger(aTSF_m) && Number.isInteger(aTSF_y) && Number.isInteger(aTST_d) && Number.isInteger(aTST_m) && Number.isInteger(aTST_y)) {
      window.onbeforeunload = function(event) {
        event.returnValue = "Your changes may not be saved.";
      };
      editTechnicalShutdownReady = false;
      editorBtnHandler("load", "modal-editor-content-form-footer-btn-technical-shutdown");
      document.getElementById("m-e-c-f-error-wrp-technical-shutdown").style.display = "";
      document.getElementById("m-e-c-f-error-txt-technical-shutdown").innerHTML = "";
      document.getElementById("permission-needed-to-change-list").innerHTML = "";
      addTechnicalShutdownPermissionNeeded = false;
      editorCalendarAddTechnicalShutdownWChangesErrorTxt = "";
      editorCalendarAddTechnicalShutdownWChangesDoneSts = false;
      editorCalendarAddTechnicalShutdownWChangesErrorSts = false;
      xhrAddTechnicalShutdownCheck = new XMLHttpRequest();
      xhrAddTechnicalShutdownCheck.onreadystatechange = function() {
        if (xhrAddTechnicalShutdownCheck.readyState == 4 && xhrAddTechnicalShutdownCheck.status == 200) {
          window.onbeforeunload = null;
          editTechnicalShutdownReady = true;
          if (testJSON(xhrAddTechnicalShutdownCheck.response)) {
            var json = JSON.parse(xhrAddTechnicalShutdownCheck.response);
            for (var key in json) {
              if (json.hasOwnProperty(key)) {
                if (json[key]["type"] == "done") {
                  editorCalendarAddTechnicalShutdownWChangesDoneSts = true;
                } else if (json[key]["type"] == "permission-needed") {
                  if (json[key]["data"] == "booking") {
                    permissionNeededBookingRender(json[key]["task"], json[key]["name"], json[key]["email"], json[key]["phone"], json[key]["from"], json[key]["to"]);
                  } else {
                    permissionNeededTechnicalShutdownRender(json[key]["task"], json[key]["category"], json[key]["notes"], json[key]["from"], json[key]["to"]);
                  }
                  editorBtnHandler("def", "modal-editor-content-form-footer-btn-technical-shutdown");
                  addTechnicalShutdownPermissionNeeded = true;
                } else if (json[key]["type"] == "error") {
                  editorCalendarAddTechnicalShutdownWChangesErrorSts = true;
                  editorCalendarAddTechnicalShutdownWChangesErrorTxt = editorCalendarAddTechnicalShutdownWChangesErrorTxt +""+ json[key]["error"] +"<br>";
                }
              }
            }
            if (addTechnicalShutdownPermissionNeeded) {
              permissionNeededData("", "", "", "", "", "", "", "", "", "", "", aTSNotes, aTSF_d, aTSF_m, aTSF_y, aTST_d, aTST_m, aTST_y, aTSFromAvailability, aTSToAvailability, "", "", aTSCategory, "add-technical-shutdown");
              permissionNeededOnclick("");
              modCover("show", "modal-cover-permission-needed");
            }
            if (editorCalendarAddTechnicalShutdownWChangesErrorSts) {
              if (editorCalendarAddTechnicalShutdownWChangesDoneSts) {
                editorBtnHandler("def", "modal-editor-content-form-footer-btn-technical-shutdown");
                editorCalendarTechnicalShutdownModal('hide');
                calendarRender('editor-content-calendar-blck', plc_id, 'host-view');
                editorCalendarDateDataHandler(null, null, null, null, null, null);
                editorCalendarManagerBtns();
                alert(editorCalendarAddTechnicalShutdownWChangesErrorTxt);
              } else {
                editorBtnHandler("def", "modal-editor-content-form-footer-btn-technical-shutdown");
                document.getElementById("m-e-c-f-error-wrp-technical-shutdown").style.display = "table";
                document.getElementById("m-e-c-f-error-txt-technical-shutdown").innerHTML = editorCalendarAddTechnicalShutdownWChangesErrorTxt;
                document.getElementById("modal-editor-content-scroll-technical-shutdown").scrollTop = 0;
              }
            }
            if (editorCalendarAddTechnicalShutdownWChangesDoneSts && !editorCalendarAddTechnicalShutdownWChangesErrorSts) {
              editorBtnHandler("success", "modal-editor-content-form-footer-btn-technical-shutdown");
              clearTimeout(editorAddTechnicalShutdownOutputTime);
              editorAddTechnicalShutdownOutputTime = setTimeout(function(){
                editorBtnHandler("def", "modal-editor-content-form-footer-btn-technical-shutdown");
                editorCalendarTechnicalShutdownModal('hide');
                calendarRender('editor-content-calendar-blck', plc_id, 'host-view');
                editorCalendarDateDataHandler(null, null, null, null, null, null);
                editorCalendarManagerBtns();
              }, 750);
            }
          } else {
            editorBtnHandler("def", "modal-editor-content-form-footer-btn-technical-shutdown");
            document.getElementById("m-e-c-f-error-wrp-technical-shutdown").style.display = "table";
            document.getElementById("m-e-c-f-error-txt-technical-shutdown").innerHTML = xhrAddTechnicalShutdownCheck.response;
            document.getElementById("modal-editor-content-scroll-technical-shutdown").scrollTop = 0;
          }
        }
      }
      xhrAddTechnicalShutdownCheck.open("POST", "php-backend/add-technical-shutdown-manager.php");
      xhrAddTechnicalShutdownCheck.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhrAddTechnicalShutdownCheck.send("verificated_to_make_changes=no&plc_id="+ plc_id +"&category="+ aTSCategory +"&notes="+ aTSNotes +"&f_d="+ aTSF_d +"&f_m="+ aTSF_m +"&f_y="+ aTSF_y +"&fromAvailability="+ aTSFromAvailability +"&t_d="+ aTST_d +"&t_m="+ aTST_m +"&t_y="+ aTST_y +"&toAvailability="+ aTSToAvailability);
    } else {
      document.getElementById("m-e-c-f-error-wrp-technical-shutdown").style.display = "table";
      document.getElementById("m-e-c-f-error-txt-technical-shutdown").innerHTML = "You have to select both starting and ending day";
      document.getElementById("modal-editor-content-scroll-technical-shutdown").scrollTop = 0;
    }
  }
}

var xhrAddTechnicalShutdown;
function editorCalendarAddTechnicalShutdownWChanges() {
  if (editTechnicalShutdownReady) {
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    aTSCategory = document.getElementById("permission-needed-to-change-accept-category").textContent;
    aTSNotes = document.getElementById("permission-needed-to-change-accept-notes").textContent;
    aTSF_d = document.getElementById("permission-needed-to-change-accept-f-d").textContent;
    aTSF_m = document.getElementById("permission-needed-to-change-accept-f-m").textContent;
    aTSF_y = document.getElementById("permission-needed-to-change-accept-f-y").textContent;
    aTST_d = document.getElementById("permission-needed-to-change-accept-t-d").textContent;
    aTST_m = document.getElementById("permission-needed-to-change-accept-t-m").textContent;
    aTST_y = document.getElementById("permission-needed-to-change-accept-t-y").textContent;
    aTSFromAvailability = document.getElementById("permission-needed-to-change-accept-firstday").textContent;
    aTSToAvailabilityy = document.getElementById("permission-needed-to-change-accept-lastday").textContent;
    modCover("hide", "modal-cover-permission-needed");
    editTechnicalShutdownReady = false;
    editorBtnHandler("load", "modal-editor-content-form-footer-btn-technical-shutdown");
    document.getElementById("m-e-c-f-error-wrp-technical-shutdown").style.display = "";
    document.getElementById("m-e-c-f-error-txt-technical-shutdown").innerHTML = "";
    editorCalendarAddTechnicalShutdownWChangesErrorTxt = "";
    editorCalendarAddTechnicalShutdownWChangesDoneSts = false;
    editorCalendarAddTechnicalShutdownWChangesErrorSts = false;
    xhrAddTechnicalShutdown = new XMLHttpRequest();
    xhrAddTechnicalShutdown.onreadystatechange = function() {
      if (xhrAddTechnicalShutdown.readyState == 4 && xhrAddTechnicalShutdown.status == 200) {
        window.onbeforeunload = null;
        editTechnicalShutdownReady = true;
        if (testJSON(xhrAddTechnicalShutdown.response)) {
          var json = JSON.parse(xhrAddTechnicalShutdown.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                editorCalendarAddTechnicalShutdownWChangesDoneSts = true;
              } else if (json[key]["type"] == "error") {
                editorCalendarAddTechnicalShutdownWChangesErrorSts = true;
                editorCalendarAddTechnicalShutdownWChangesErrorTxt = editorCalendarAddTechnicalShutdownWChangesErrorTxt +""+ json[key]["error"] +"<br>";
              }
            }
          }
          if (editorCalendarAddTechnicalShutdownWChangesErrorSts) {
            if (editorCalendarAddTechnicalShutdownWChangesDoneSts) {
              editorBtnHandler("def", "modal-editor-content-form-footer-btn-technical-shutdown");
              editorCalendarTechnicalShutdownModal('hide');
              calendarRender('editor-content-calendar-blck', plc_id, 'host-view');
              editorCalendarDateDataHandler(null, null, null, null, null, null);
              editorCalendarManagerBtns();
              alert(editorCalendarAddTechnicalShutdownWChangesErrorTxt);
            } else {
              editorBtnHandler("def", "modal-editor-content-form-footer-btn-technical-shutdown");
              document.getElementById("m-e-c-f-error-wrp-technical-shutdown").style.display = "table";
              document.getElementById("m-e-c-f-error-txt-technical-shutdown").innerHTML = editorCalendarAddTechnicalShutdownWChangesErrorTxt;
              document.getElementById("modal-editor-content-scroll-technical-shutdown").scrollTop = 0;
            }
          }
          if (editorCalendarAddTechnicalShutdownWChangesDoneSts && !editorCalendarAddTechnicalShutdownWChangesErrorSts) {
            editorBtnHandler("success", "modal-editor-content-form-footer-btn-technical-shutdown");
            clearTimeout(editorAddTechnicalShutdownOutputTime);
            editorAddTechnicalShutdownOutputTime = setTimeout(function(){
              editorBtnHandler("def", "modal-editor-content-form-footer-btn-technical-shutdown");
              editorCalendarTechnicalShutdownModal('hide');
              calendarRender('editor-content-calendar-blck', plc_id, 'host-view');
              editorCalendarDateDataHandler(null, null, null, null, null, null);
              editorCalendarManagerBtns();
            }, 750);
          }
        } else {
          editorBtnHandler("def", "modal-editor-content-form-footer-btn-technical-shutdown");
          document.getElementById("m-e-c-f-error-wrp-technical-shutdown").style.display = "table";
          document.getElementById("m-e-c-f-error-txt-technical-shutdown").innerHTML = xhrAddTechnicalShutdown.response;
          document.getElementById("modal-editor-content-scroll-technical-shutdown").scrollTop = 0;
        }
      }
    }
    xhrAddTechnicalShutdown.open("POST", "php-backend/add-technical-shutdown-manager.php");
    xhrAddTechnicalShutdown.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrAddTechnicalShutdown.send("verificated_to_make_changes=yes&plc_id="+ plc_id +"&category="+ aTSCategory +"&notes="+ aTSNotes +"&f_d="+ aTSF_d +"&f_m="+ aTSF_m +"&f_y="+ aTSF_y +"&fromAvailability="+ aTSFromAvailability +"&t_d="+ aTST_d +"&t_m="+ aTST_m +"&t_y="+ aTST_y +"&toAvailability="+ aTSToAvailability);
  }
}

var editNewBookingReady = true;
function editorCalendarNewBookingModal(tsk) {
  if (editNewBookingReady) {
    if (from_d != "" && from_m != "" && from_y != "") {
      document.getElementById("m-e-c-f-error-wrp-new-booking").style.display = "";
      document.getElementById("m-e-c-f-error-txt-new-booking").innerHTML = "";
      document.getElementById("m-e-c-f-input-name-new-booking").value = "";
      document.getElementById("m-e-c-f-input-email-new-booking").value = "";
      document.getElementById("m-e-c-f-input-phone-new-booking").value = "";
      document.getElementById("m-e-c-f-input-guest-num-new-booking").value = "1";
      document.getElementById("m-e-c-f-input-from-new-booking").value = from_y +"-"+ ("0"+ from_m).slice(-2) +"-"+ ("0"+ from_d).slice(-2);
      if (to_d != "" && to_m != "" && to_y != "") {
        document.getElementById("m-e-c-f-input-to-new-booking").value = to_y +"-"+ ("0"+ to_m).slice(-2) +"-"+ ("0"+ to_d).slice(-2);
      } else {
        document.getElementById("m-e-c-f-input-to-new-booking").value = "";
      }
      editorInputDateDropdownSelectOnchange('from', 1, 'new-booking');
      editorInputDateDropdownSelectOnchange('to', 1, 'new-booking');
      document.getElementById("m-e-c-f-checkbox-label-deposit-new-booking").checked = false;
      document.getElementById("m-e-c-f-checkbox-label-full-amount-new-booking").checked = false;
      document.getElementById("m-e-c-f-textarea-notes-new-booking").value = "";
      editorBtnHandler("def", "modal-editor-content-form-footer-btn-new-booking");
      document.getElementById("modal-editor-content-scroll-new-booking").scrollTop = 0;
      modCover(tsk, 'modal-cover-editor-calendar-new-booking');
    } else {
      modCover("hide", 'modal-cover-editor-calendar-new-booking');
    }
  }
}

function editorCalendarFullAmountChanged(id) {
  if (document.getElementById("m-e-c-f-checkbox-label-full-amount-"+ id).checked) {
    document.getElementById("m-e-c-f-checkbox-label-deposit-"+ id).checked = true;
  }
}

function editorCalendarDepositChanged(id) {
  if (!document.getElementById("m-e-c-f-checkbox-label-deposit-"+ id).checked) {
    document.getElementById("m-e-c-f-checkbox-label-full-amount-"+ id).checked = false;
  }
}

var xhrNewBookingCheck;
var nBName, nBEmail, nBPhone, nBGuest, nBNotes, nBF_d, nBF_m, nBF_y, nBT_d, nBT_m, nBT_y, fromAvailability, toAvailability, editorAddBookingOutputTime, newBookingPermissionNeeded, editorCalendarNewBookingWChangesErrorTxt, editorCalendarNewBookingWChangesDoneSts, editorCalendarNewBookingWChangesErrorSts, nBDepositSts, nBFullAmountSts;
function editorCalendarNewBookingCheck() {
  if (editNewBookingReady) {
    nBName = document.getElementById("m-e-c-f-input-name-new-booking").value;
    nBEmail = document.getElementById("m-e-c-f-input-email-new-booking").value;
    nBPhone = document.getElementById("m-e-c-f-input-phone-new-booking").value.replace(/\+/g, "plus");
    nBGuest = document.getElementById("m-e-c-f-input-guest-num-new-booking").value;
    nBNotes = document.getElementById("m-e-c-f-textarea-notes-new-booking").value;
    nBF_d = new Date(document.getElementById("m-e-c-f-input-from-new-booking").value).getDate();
    nBF_m = new Date(document.getElementById("m-e-c-f-input-from-new-booking").value).getMonth() +1;
    nBF_y = new Date(document.getElementById("m-e-c-f-input-from-new-booking").value).getFullYear();
    nBT_d = new Date(document.getElementById("m-e-c-f-input-to-new-booking").value).getDate();
    nBT_m = new Date(document.getElementById("m-e-c-f-input-to-new-booking").value).getMonth() +1;
    nBT_y = new Date(document.getElementById("m-e-c-f-input-to-new-booking").value).getFullYear();
    if (document.getElementById("m-e-c-f-dropdown-radio-from-1-new-booking").checked) {
      fromAvailability = "half";
    } else {
      fromAvailability = "whole";
    }
    if (document.getElementById("m-e-c-f-dropdown-radio-to-1-new-booking").checked) {
      toAvailability = "half";
    } else {
      toAvailability = "whole";
    }
    if (document.getElementById("m-e-c-f-checkbox-label-deposit-new-booking").checked) {
      nBDepositSts = 1;
    } else {
      nBDepositSts = 0;
    }
    if (document.getElementById("m-e-c-f-checkbox-label-full-amount-new-booking").checked) {
      nBFullAmountSts = 1;
    } else {
      nBFullAmountSts = 0;
    }
    if (nBF_d != null && nBF_m != null && nBF_y != null && nBT_d != null && nBT_m != null && nBT_y != null) {
      window.onbeforeunload = function(event) {
        event.returnValue = "Your changes may not be saved.";
      };
      editNewBookingReady = false;
      editorBtnHandler("load", "modal-editor-content-form-footer-btn-new-booking");
      document.getElementById("m-e-c-f-error-wrp-new-booking").style.display = "";
      document.getElementById("m-e-c-f-error-txt-new-booking").innerHTML = "";
      document.getElementById("permission-needed-to-change-list").innerHTML = "";
      newBookingPermissionNeeded = false;
      editorCalendarNewBookingWChangesErrorTxt = "";
      editorCalendarNewBookingWChangesDoneSts = false;
      editorCalendarNewBookingWChangesErrorSts = false;
      xhrNewBookingCheck = new XMLHttpRequest();
      xhrNewBookingCheck.onreadystatechange = function() {
        if (xhrNewBookingCheck.readyState == 4 && xhrNewBookingCheck.status == 200) {
          window.onbeforeunload = null;
          editNewBookingReady = true;
          if (testJSON(xhrNewBookingCheck.response)) {
            var json = JSON.parse(xhrNewBookingCheck.response);
            for (var key in json) {
              if (json.hasOwnProperty(key)) {
                if (json[key]["type"] == "done") {
                  editorCalendarNewBookingWChangesDoneSts = true;
                } else if (json[key]["type"] == "permission-needed") {
                  if (json[key]["data"] == "booking") {
                    permissionNeededBookingRender(json[key]["task"], json[key]["name"], json[key]["email"], json[key]["phone"], json[key]["from"], json[key]["to"]);
                  } else {
                    permissionNeededTechnicalShutdownRender(json[key]["task"], json[key]["category"], json[key]["notes"], json[key]["from"], json[key]["to"]);
                  }
                  editorBtnHandler("def", "modal-editor-content-form-footer-btn-new-booking");
                  newBookingPermissionNeeded = true;
                } else if (json[key]["type"] == "error") {
                  editorCalendarNewBookingWChangesErrorSts = true;
                  editorCalendarNewBookingWChangesErrorTxt = editorCalendarNewBookingWChangesErrorTxt +""+ json[key]["error"] +"<br>";
                }
              }
            }
            if (newBookingPermissionNeeded) {
              permissionNeededData("", "", "", "", "", "", "", nBName, nBEmail, nBPhone, nBGuest, nBNotes, nBF_d, nBF_m, nBF_y, nBT_d, nBT_m, nBT_y, fromAvailability, toAvailability, nBDepositSts, nBFullAmountSts, "", "new-booking");
              permissionNeededOnclick("");
              modCover("show", "modal-cover-permission-needed");
            }
            if (editorCalendarNewBookingWChangesErrorSts) {
              if (editorCalendarNewBookingWChangesDoneSts) {
                editorBtnHandler("def", "modal-editor-content-form-footer-btn-new-booking");
                editorCalendarNewBookingModal('hide');
                calendarRender('editor-content-calendar-blck', plc_id, 'host-view');
                editorCalendarDateDataHandler(null, null, null, null, null, null);
                editorCalendarManagerBtns();
                alert(editorCalendarNewBookingWChangesErrorTxt);
              } else {
                editorBtnHandler("def", "modal-editor-content-form-footer-btn-new-booking");
                document.getElementById("m-e-c-f-error-wrp-new-booking").style.display = "table";
                document.getElementById("m-e-c-f-error-txt-new-booking").innerHTML = editorCalendarNewBookingWChangesErrorTxt;
                document.getElementById("modal-editor-content-scroll-new-booking").scrollTop = 0;
              }
            }
            if (editorCalendarNewBookingWChangesDoneSts && !editorCalendarNewBookingWChangesErrorSts) {
              editorBtnHandler("success", "modal-editor-content-form-footer-btn-new-booking");
              clearTimeout(editorAddBookingOutputTime);
              editorAddBookingOutputTime = setTimeout(function(){
                editorBtnHandler("def", "modal-editor-content-form-footer-btn-new-booking");
                editorCalendarNewBookingModal('hide');
                calendarRender('editor-content-calendar-blck', plc_id, 'host-view');
                editorCalendarDateDataHandler(null, null, null, null, null, null);
                editorCalendarManagerBtns();
              }, 750);
            }
          } else {
            editorBtnHandler("def", "modal-editor-content-form-footer-btn-new-booking");
            document.getElementById("m-e-c-f-error-wrp-new-booking").style.display = "table";
            document.getElementById("m-e-c-f-error-txt-new-booking").innerHTML = xhrNewBookingCheck.response;
            document.getElementById("modal-editor-content-scroll-new-booking").scrollTop = 0;
          }
        }
      }
      xhrNewBookingCheck.open("POST", "php-backend/add-booking-manager.php");
      xhrNewBookingCheck.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhrNewBookingCheck.send("verificated_to_make_changes=no&plc_id="+ plc_id +"&name="+ nBName +"&email="+ nBEmail +"&phone="+ nBPhone +"&guest="+ nBGuest +"&notes="+ nBNotes +"&f_d="+ nBF_d +"&f_m="+ nBF_m +"&f_y="+ nBF_y +"&fromAvailability="+ fromAvailability +"&t_d="+ nBT_d +"&t_m="+ nBT_m +"&t_y="+ nBT_y +"&toAvailability="+ toAvailability +"&deposit="+ nBDepositSts +"&fullAmount="+ nBFullAmountSts);
    } else {
      document.getElementById("m-e-c-f-error-wrp-new-booking").style.display = "table";
      document.getElementById("m-e-c-f-error-txt-new-booking").innerHTML = "You have to select starting and ending day of the booking";
      document.getElementById("modal-editor-content-scroll-new-booking").scrollTop = 0;
    }
  }
}

var xhrNewBooking;
function editorCalendarNewBookingWChanges() {
  if (editNewBookingReady) {
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    nBName = document.getElementById("permission-needed-to-change-accept-name").textContent;
    nBEmail = document.getElementById("permission-needed-to-change-accept-email").textContent;
    nBPhone = document.getElementById("permission-needed-to-change-accept-phone").textContent;
    nBGuest = document.getElementById("permission-needed-to-change-accept-guests").textContent;
    nBNotes = document.getElementById("permission-needed-to-change-accept-notes").textContent;
    nBF_d = document.getElementById("permission-needed-to-change-accept-f-d").textContent;
    nBF_m = document.getElementById("permission-needed-to-change-accept-f-m").textContent;
    nBF_y = document.getElementById("permission-needed-to-change-accept-f-y").textContent;
    nBT_d = document.getElementById("permission-needed-to-change-accept-t-d").textContent;
    nBT_m = document.getElementById("permission-needed-to-change-accept-t-m").textContent;
    nBT_y = document.getElementById("permission-needed-to-change-accept-t-y").textContent;
    fromAvailability = document.getElementById("permission-needed-to-change-accept-firstday").textContent;
    toAvailability = document.getElementById("permission-needed-to-change-accept-lastday").textContent;
    nBDepositSts = document.getElementById("permission-needed-to-change-accept-deposit").textContent;
    nBFullAmountSts = document.getElementById("permission-needed-to-change-accept-full-amount").textContent;
    modCover("hide", "modal-cover-permission-needed");
    editNewBookingReady = false;
    editorBtnHandler("load", "modal-editor-content-form-footer-btn-new-booking");
    document.getElementById("m-e-c-f-error-wrp-new-booking").style.display = "";
    document.getElementById("m-e-c-f-error-txt-new-booking").innerHTML = "";
    editorCalendarNewBookingWChangesErrorTxt = "";
    editorCalendarNewBookingWChangesDoneSts = false;
    editorCalendarNewBookingWChangesErrorSts = false;
    xhrNewBooking = new XMLHttpRequest();
    xhrNewBooking.onreadystatechange = function() {
      if (xhrNewBooking.readyState == 4 && xhrNewBooking.status == 200) {
        window.onbeforeunload = null;
        editNewBookingReady = true;
        if (testJSON(xhrNewBooking.response)) {
          var json = JSON.parse(xhrNewBooking.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                editorCalendarNewBookingWChangesDoneSts = true;
              } else if (json[key]["type"] == "error") {
                editorCalendarNewBookingWChangesErrorSts = true;
                editorCalendarNewBookingWChangesErrorTxt = editorCalendarNewBookingWChangesErrorTxt +""+ json[key]["error"] +"<br>";
              }
            }
          }
          if (editorCalendarNewBookingWChangesErrorSts) {
            if (editorCalendarNewBookingWChangesDoneSts) {
              editorBtnHandler("def", "modal-editor-content-form-footer-btn-new-booking");
              editorCalendarNewBookingModal('hide');
              calendarRender('editor-content-calendar-blck', plc_id, 'host-view');
              editorCalendarDateDataHandler(null, null, null, null, null, null);
              editorCalendarManagerBtns();
              alert(editorCalendarNewBookingWChangesErrorTxt);
            } else {
              editorBtnHandler("def", "modal-editor-content-form-footer-btn-new-booking");
              document.getElementById("m-e-c-f-error-wrp-new-booking").style.display = "table";
              document.getElementById("m-e-c-f-error-txt-new-booking").innerHTML = editorCalendarNewBookingWChangesErrorTxt;
              document.getElementById("modal-editor-content-scroll-new-booking").scrollTop = 0;
            }
          }
          if (editorCalendarNewBookingWChangesDoneSts && !editorCalendarNewBookingWChangesErrorSts) {
            editorBtnHandler("success", "modal-editor-content-form-footer-btn-new-booking");
            clearTimeout(editorAddBookingOutputTime);
            editorAddBookingOutputTime = setTimeout(function(){
              editorBtnHandler("def", "modal-editor-content-form-footer-btn-new-booking");
              editorCalendarNewBookingModal('hide');
              calendarRender('editor-content-calendar-blck', plc_id, 'host-view');
              editorCalendarDateDataHandler(null, null, null, null, null, null);
              editorCalendarManagerBtns();
            }, 750);
          }
        } else {
          editorBtnHandler("def", "modal-editor-content-form-footer-btn-new-booking");
          document.getElementById("m-e-c-f-error-wrp-new-booking").style.display = "table";
          document.getElementById("m-e-c-f-error-txt-new-booking").innerHTML = xhrNewBooking.response;
          document.getElementById("modal-editor-content-scroll-new-booking").scrollTop = 0;
        }
      }
    }
    xhrNewBooking.open("POST", "php-backend/add-booking-manager.php");
    xhrNewBooking.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrNewBooking.send("verificated_to_make_changes=yes&plc_id="+ plc_id +"&name="+ nBName +"&email="+ nBEmail +"&phone="+ nBPhone +"&guest="+ nBGuest +"&notes="+ nBNotes +"&f_d="+ nBF_d +"&f_m="+ nBF_m +"&f_y="+ nBF_y +"&fromAvailability="+ fromAvailability +"&t_d="+ nBT_d +"&t_m="+ nBT_m +"&t_y="+ nBT_y +"&toAvailability="+ toAvailability +"&deposit="+ nBDepositSts +"&fullAmount="+ nBFullAmountSts);
  }
}

var editDayDetailsReady = true;
function editorCalendarDayDetailsModal(tsk) {
  if (editDayDetailsReady) {
    if (from_d != "" && from_m != "" && from_y != "") {
      document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "";
      document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = "";
      document.getElementById("modal-editor-content-header-title-day-details").innerHTML = from_d +". "+ from_m +". "+ from_y;
      document.getElementById("editor-calendar-day-details-content").innerHTML = "";
      editorCalendarDayDetailsRenderLoader();
      document.getElementById("modal-editor-content-scroll-day-details").scrollTop = 0;
      modCover(tsk, 'modal-cover-editor-calendar-day-details');
      getEditorCalendarDetails(from_y, from_m, from_d);
    } else {
      modCover("hide", 'modal-cover-editor-calendar-day-details');
    }
  }
}

function editorCalendarDayDetailsRenderLoader() {
  var loaderWrp = document.createElement("div");
  loaderWrp.setAttribute("id", "editor-calendar-day-details-loader-wrp");
  var loaderImg = document.createElement("img");
  loaderImg.setAttribute("id", "editor-calendar-day-details-loader-img");
  loaderImg.setAttribute("alt", "Loader gif");
  loaderImg.setAttribute("src", "../uni/gifs/loader-tail.svg");
  loaderWrp.appendChild(loaderImg)
  document.getElementById("editor-calendar-day-details-content").appendChild(loaderWrp);
}

var xhrCalDetails, editorDetailsContentSts;
function getEditorCalendarDetails(y, m, d) {
  if (xhrCalDetails != null) {
    xhrCalDetails.abort();
  }
  xhrCalDetails = new XMLHttpRequest();
  xhrCalDetails.onreadystatechange = function() {
    if (xhrCalDetails.readyState == 4 && xhrCalDetails.status == 200) {
      document.getElementById("editor-calendar-day-details-loader-wrp").parentNode.removeChild(document.getElementById("editor-calendar-day-details-loader-wrp"));
      if (testJSON(xhrCalDetails.response)) {
        var json = JSON.parse(xhrCalDetails.response);
        editorDetailsContentSts = "none";
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "booking") {
              editorDetailsContentSts = "booking";
              editCalendarRenderBookingDetails(json[key]["bookingID"], json[key]["status"], json[key]["name"], json[key]["email"], json[key]["phone"], json[key]["guest"], json[key]["max_guest"], json[key]["notes"], json[key]["f_d"], json[key]["f_m"], json[key]["f_y"], json[key]["firstday"], json[key]["t_d"], json[key]["t_m"], json[key]["t_y"], json[key]["lastday"], json[key]["currency"], json[key]["totalprice"], json[key]["fee"], json[key]["feeperc"], json[key]["deposit"], json[key]["fullAmount"], json[key]["lessthan48h"], json[key]["tillCancel"], json[key]["countdownBanner"]);
            } else if (json[key]["type"] == "technical-shutdown") {
              editorDetailsContentSts = "technical-shutdown";
              editCalendarRenderTechnicalShutdownDetails(json[key]["category"], json[key]["notes"], json[key]["f_d"], json[key]["f_m"], json[key]["f_y"], json[key]["firstday"], json[key]["t_d"], json[key]["t_m"], json[key]["t_y"], json[key]["lastday"]);
            } else if (json[key]["type"] == "error") {
              editorDetailsContentSts = "error";
              document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "table";
              document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = json[key]["error"];
            }
          }
        }
        if (editorDetailsContentSts == "none") {
          editCalendarRenderEmptyDate();
        } else {
          if (document.getElementsByClassName("m-e-c-f-border-wrp").length != 0) {
            document.getElementsByClassName("m-e-c-f-border-wrp")[document.getElementsByClassName("m-e-c-f-border-wrp").length -1].parentNode.removeChild(document.getElementsByClassName("m-e-c-f-border-wrp")[document.getElementsByClassName("m-e-c-f-border-wrp").length -1]);
          }
        }
      } else {
        document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "table";
        document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = xhrCalDetails.response;
      }
    }
  }
  xhrCalDetails.open("POST", "php-backend/get-calendar-day-details.php");
  xhrCalDetails.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrCalDetails.send("plc_id="+ plc_id +"&y="+ y +"&m="+ m +"&d="+ d);
}

var wrd_updateChanges = "Update changes";
var wrd_cancelBooking = "Cancel booking";
var wrd_name = "Name";
var wrd_email = "Email";
var wrd_phoneNumber = "Phone number";
var wrd_guestNum = "Number of guests";
var wrd_from = "From";
var wrd_to = "To";
var wrd_theWholeDay = "Whole day";
var wrd_theDepositIsPaid = "The deposit is paid";
var wrd_theFullAmountIsPaid = "The guest paid the full amount for the accommodation";
var wrd_theReservationWasMadeLessThan48HoursBeforeItsStartTheGuestHasBeenInformedToReportByPhone = "The reservation was made less than 48 hours before its start. The guest has been informed to report by phone";
var wrd_notes = "Notes";
var wrd_type = "Type here...";
var wrd_hour = "hour";
var wrd_hours1 = "hours";
var wrd_hours2 = wrd_hours1;
var wrd_thereAre1 = "There are";
var wrd_thereAre2 = wrd_thereAre1;
var wrd_untilThisReservationIsAutomaticallyCanceled = "until this reservation is automatically canceled";
var wrd_thisReservationWillBeAutomaticallyCanceledAtAnyTimeIfThisHasNotAlreadyBeenDoneToPreventThisYouMustCheckTheBoxForTheDepositPaidOrTheFullAmount = "This reservation will be automatically canceled at any time if this has not already been done. To prevent this, you must check the box for the deposit paid or the full amount.";
var wrd_ifTheGuestDoesNotPayTheDepositOrTheFullAmountWithin48HoursOfMakingTheReservationItWillBeAutomaticallyCanceled = "If the guest does not pay the deposit or the full amount within 48 hours of making the reservation, it will be automatically canceled";
var wrd_totalPrice = "Total price";
var wrd_cleaning = "Cleaning";
var wrd_maintenance = "Maintenance";
var wrd_reconstruction = "Reconstruction";
var wrd_other = "Other";
var wrd_category = "Category";
var wrd_cancelTechnicalShutdown = "Cancel technical shutdown";
var wrd_reject = "Reject";
var wrd_confirm = "Confirm";
var wrd_bookingOffer = "Booking offer";
var wrd_byConfirmingTheBookingYouWillBeProvidedWithAllInformationAboutThisBookingSuchAsTheNameAndContactDetailsOfTheGuestAtTheSameTimeTheGuestWillReceivePaymentInstructionsRejectionWillCancelTheBooking = "By confirming the booking, you will be provided with all information about this booking, such as the name and contact details of the guest. At the same time, the guest will receive payment instructions. Rejection will cancel the booking.";
var wrd_requestsForChangesInTheBooking = "Requests for changes in the booking";
var wrd_fee = "Fee";
var wrd_thereAreNoBookingsForThisDate = "There are no bookings for this date";
function editoCalendarManagerDictionary(updateChanges, cancelBooking, wName, wEmail, wPhoneNumber, wGuestNum, wFrom, wTo, theWholeDay, theDepositIsPaid, theFullAmountIsPaid, theReservationWasMadeLessThan48HoursBeforeItsStartTheGuestHasBeenInformedToReportByPhone, notes, type, hour, hours1, hours2, thereAre1, thereAre2, untilThisReservationIsAutomaticallyCanceled, thisReservationWillBeAutomaticallyCanceledAtAnyTimeIfThisHasNotAlreadyBeenDoneToPreventThisYouMustCheckTheBoxForTheDepositPaidOrTheFullAmount, ifTheGuestDoesNotPayTheDepositOrTheFullAmountWithin48HoursOfMakingTheReservationItWillBeAutomaticallyCanceled, wTotalPrice, wCleaning, wMaintenance, wReconstruction, wOther, wCategory, cancelTechnicalShutdown, wReject, wConfirm, wBookingOffer, byConfirmingTheBookingYouWillBeProvidedWithAllInformationAboutThisBookingSuchAsTheNameAndContactDetailsOfTheGuestAtTheSameTimeTheGuestWillReceivePaymentInstructionsRejectionWillCancelTheBooking, requestsForChangesInTheBooking, wFee, thereAreNoBookingsForThisDate) {
  wrd_updateChanges = updateChanges;
  wrd_cancelBooking = cancelBooking;
  wrd_name = wName;
  wrd_email = wEmail;
  wrd_phoneNumber = wPhoneNumber;
  wrd_guestNum = wGuestNum;
  wrd_from = wFrom;
  wrd_to = wTo;
  wrd_theWholeDay = theWholeDay;
  wrd_theDepositIsPaid = theDepositIsPaid;
  wrd_theFullAmountIsPaid = theFullAmountIsPaid;
  wrd_theReservationWasMadeLessThan48HoursBeforeItsStartTheGuestHasBeenInformedToReportByPhone = theReservationWasMadeLessThan48HoursBeforeItsStartTheGuestHasBeenInformedToReportByPhone;
  wrd_notes = notes;
  wrd_type = type;
  wrd_hour = hour;
  wrd_hours1 = hours1;
  wrd_hours2 = hours2;
  wrd_thereAre1 = thereAre1;
  wrd_thereAre2 = thereAre2;
  wrd_untilThisReservationIsAutomaticallyCanceled = untilThisReservationIsAutomaticallyCanceled;
  wrd_thisReservationWillBeAutomaticallyCanceledAtAnyTimeIfThisHasNotAlreadyBeenDoneToPreventThisYouMustCheckTheBoxForTheDepositPaidOrTheFullAmount = thisReservationWillBeAutomaticallyCanceledAtAnyTimeIfThisHasNotAlreadyBeenDoneToPreventThisYouMustCheckTheBoxForTheDepositPaidOrTheFullAmount;
  wrd_ifTheGuestDoesNotPayTheDepositOrTheFullAmountWithin48HoursOfMakingTheReservationItWillBeAutomaticallyCanceled = ifTheGuestDoesNotPayTheDepositOrTheFullAmountWithin48HoursOfMakingTheReservationItWillBeAutomaticallyCanceled;
  wrd_totalPrice = wTotalPrice;
  wrd_cleaning = wCleaning;
  wrd_maintenance = wMaintenance;
  wrd_reconstruction = wReconstruction;
  wrd_other = wOther;
  wrd_category = wCategory;
  wrd_cancelTechnicalShutdown = cancelTechnicalShutdown;
  wrd_reject = wReject;
  wrd_confirm = wConfirm;
  wrd_bookingOffer = wBookingOffer;
  wrd_byConfirmingTheBookingYouWillBeProvidedWithAllInformationAboutThisBookingSuchAsTheNameAndContactDetailsOfTheGuestAtTheSameTimeTheGuestWillReceivePaymentInstructionsRejectionWillCancelTheBooking = byConfirmingTheBookingYouWillBeProvidedWithAllInformationAboutThisBookingSuchAsTheNameAndContactDetailsOfTheGuestAtTheSameTimeTheGuestWillReceivePaymentInstructionsRejectionWillCancelTheBooking;
  wrd_requestsForChangesInTheBooking = requestsForChangesInTheBooking;
  wrd_fee = wFee;
  wrd_thereAreNoBookingsForThisDate = thereAreNoBookingsForThisDate;
}

function editCalendarRenderBookingDetails(booking_id, bookingSts, name, email, phonenum, guestNum, maxGuestNum, notes, fromd, fromm, fromy, firstDSts, tod, tom, toy, lastDSts, totalcurrency, totalprice, fee, feePerc, depositSts, fullAmountSts, lessThan48h, hoursTillCancel, countdownBanner) {
  var bBlckWrp = document.createElement("div");
  bBlckWrp.setAttribute("class", "m-e-c-f-blck-wrp");
  if (bookingSts == "booked") {
    if (lessThan48h == "1") {
      // less than 48h
      var bLessThan48hWrp = document.createElement("div");
      bLessThan48hWrp.setAttribute("class", "m-e-c-f-row");
      var bLessThan48hBackground = document.createElement("div");
      bLessThan48hBackground.setAttribute("class", "m-e-c-f-alert-blck-background");
      var bLessThan48hTxt = document.createElement("p");
      bLessThan48hTxt.setAttribute("class", "m-e-c-f-alert-blck-txt");
      bLessThan48hTxt.innerHTML = wrd_theReservationWasMadeLessThan48HoursBeforeItsStartTheGuestHasBeenInformedToReportByPhone;
    }
    if (countdownBanner == "1") {
      if (depositSts == 0 && fullAmountSts == 0 && lessThan48h != "1") {
        // time till cancel
        var bTimeTillCancelWrp = document.createElement("div");
        bTimeTillCancelWrp.setAttribute("class", "m-e-c-f-row");
        var bTimeTillCancelBackground = document.createElement("div");
        bTimeTillCancelBackground.setAttribute("class", "m-e-c-f-notification-blck-background");
        var bTimeTillCancelHeader = document.createElement("div");
        bTimeTillCancelHeader.setAttribute("class", "m-e-c-f-notification-blck-header");
        var bTimeTillCancelTxt = document.createElement("p");
        bTimeTillCancelTxt.setAttribute("class", "m-e-c-f-notification-blck-txt");
        if (hoursTillCancel <= 0) {
          bTimeTillCancelTxt.innerHTML = wrd_thisReservationWillBeAutomaticallyCanceledAtAnyTimeIfThisHasNotAlreadyBeenDoneToPreventThisYouMustCheckTheBoxForTheDepositPaidOrTheFullAmount;
        } else if (hoursTillCancel == 1) {
          bTimeTillCancelTxt.innerHTML = wrd_thereAre1 +" "+ hoursTillCancel +" "+ wrd_hour +" "+ wrd_untilThisReservationIsAutomaticallyCanceled;
        } else if (hoursTillCancel >= 2 && hoursTillCancel <= 4) {
          bTimeTillCancelTxt.innerHTML = wrd_thereAre2 +" "+ hoursTillCancel +" "+ wrd_hours1 +" "+ wrd_untilThisReservationIsAutomaticallyCanceled;
        } else {
          bTimeTillCancelTxt.innerHTML = wrd_thereAre1 +" "+ hoursTillCancel +" "+ wrd_hours2 +" "+ wrd_untilThisReservationIsAutomaticallyCanceled;
        }
        var bTimeTillCancelHeaderBtnWrp = document.createElement("div");
        bTimeTillCancelHeaderBtnWrp.setAttribute("class", "m-e-c-f-notification-blck-header-btn-wrp");
        var bTimeTillCancelHeaderBtn = document.createElement("button");
        bTimeTillCancelHeaderBtn.setAttribute("class", "m-e-c-f-notification-blck-header-btn");
        bTimeTillCancelHeaderBtn.setAttribute("type", "button");
        bTimeTillCancelHeaderBtn.setAttribute("value", "show");
        bTimeTillCancelHeaderBtn.setAttribute("onclick", "editorModalNotificationAlertDescToggle(this, '"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy +"')");
        var bTimeTillCancelDescWrp = document.createElement("div");
        bTimeTillCancelDescWrp.setAttribute("class", "m-e-c-f-notification-blck-desc-wrp");
        bTimeTillCancelDescWrp.setAttribute("id", "m-e-c-f-notification-blck-desc-wrp-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
        var bTimeTillCancelDescTxt = document.createElement("p");
        bTimeTillCancelDescTxt.setAttribute("class", "m-e-c-f-notification-blck-txt");
        bTimeTillCancelDescTxt.innerHTML = wrd_ifTheGuestDoesNotPayTheDepositOrTheFullAmountWithin48HoursOfMakingTheReservationItWillBeAutomaticallyCanceled;
      }
    }
  } else {
    // booking offer
    var bBookingOfferWrp = document.createElement("div");
    bBookingOfferWrp.setAttribute("class", "m-e-c-f-row");
    var bBookingOfferBackground = document.createElement("div");
    bBookingOfferBackground.setAttribute("class", "m-e-c-f-notification-blck-background");
    var bBookingOfferHeader = document.createElement("div");
    bBookingOfferHeader.setAttribute("class", "m-e-c-f-notification-blck-header");
    var bBookingOfferTxt = document.createElement("p");
    bBookingOfferTxt.setAttribute("class", "m-e-c-f-notification-blck-txt");
    bBookingOfferTxt.innerHTML = wrd_bookingOffer;
    var bBookingOfferHeaderBtnWrp = document.createElement("div");
    bBookingOfferHeaderBtnWrp.setAttribute("class", "m-e-c-f-notification-blck-header-btn-wrp");
    var bBookingOfferHeaderBtn = document.createElement("button");
    bBookingOfferHeaderBtn.setAttribute("class", "m-e-c-f-notification-blck-header-btn");
    bBookingOfferHeaderBtn.setAttribute("type", "button");
    bBookingOfferHeaderBtn.setAttribute("value", "show");
    bBookingOfferHeaderBtn.setAttribute("onclick", "editorModalNotificationAlertDescToggle(this, '"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy +"')");
    var bBookingOfferDescWrp = document.createElement("div");
    bBookingOfferDescWrp.setAttribute("class", "m-e-c-f-notification-blck-desc-wrp");
    bBookingOfferDescWrp.setAttribute("id", "m-e-c-f-notification-blck-desc-wrp-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    var bBookingOfferDescTxt = document.createElement("p");
    bBookingOfferDescTxt.setAttribute("class", "m-e-c-f-notification-blck-txt");
    bBookingOfferDescTxt.innerHTML = wrd_byConfirmingTheBookingYouWillBeProvidedWithAllInformationAboutThisBookingSuchAsTheNameAndContactDetailsOfTheGuestAtTheSameTimeTheGuestWillReceivePaymentInstructionsRejectionWillCancelTheBooking;
  }
  if (bookingSts == "booked") {
    // name
    var bNameWrp = document.createElement("div");
    bNameWrp.setAttribute("class", "m-e-c-f-row");
    var bNameTitle = document.createElement("p");
    bNameTitle.setAttribute("class", "m-e-c-f-title");
    bNameTitle.innerHTML = wrd_name;
    var bNameInputWrp = document.createElement("div");
    bNameInputWrp.setAttribute("class", "m-e-c-f-input-wrp");
    var bNameInput = document.createElement("input");
    bNameInput.setAttribute("type", "text");
    bNameInput.setAttribute("value", name);
    bNameInput.setAttribute("class", "m-e-c-f-input");
    bNameInput.setAttribute("id", "m-e-c-f-input-name-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    // email
    var bEmailWrp = document.createElement("div");
    bEmailWrp.setAttribute("class", "m-e-c-f-row");
    var bEmailTitle = document.createElement("p");
    bEmailTitle.setAttribute("class", "m-e-c-f-title");
    bEmailTitle.innerHTML = wrd_email;
    var bEmailInputWrp = document.createElement("div");
    bEmailInputWrp.setAttribute("class", "m-e-c-f-input-wrp");
    var bEmailInput = document.createElement("input");
    bEmailInput.setAttribute("type", "email");
    bEmailInput.setAttribute("value", email);
    bEmailInput.setAttribute("class", "m-e-c-f-input");
    bEmailInput.setAttribute("id", "m-e-c-f-input-email-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    // phone
    var bPhoneWrp = document.createElement("div");
    bPhoneWrp.setAttribute("class", "m-e-c-f-row");
    var bPhoneTitle = document.createElement("p");
    bPhoneTitle.setAttribute("class", "m-e-c-f-title");
    bPhoneTitle.innerHTML = wrd_phoneNumber;
    var bPhoneInputWrp = document.createElement("div");
    bPhoneInputWrp.setAttribute("class", "m-e-c-f-input-wrp");
    var bPhoneInput = document.createElement("input");
    bPhoneInput.setAttribute("type", "tel");
    bPhoneInput.setAttribute("value", phonenum);
    bPhoneInput.setAttribute("class", "m-e-c-f-input");
    bPhoneInput.setAttribute("id", "m-e-c-f-input-phone-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  }
  // guest
  var bGuestWrp = document.createElement("div");
  bGuestWrp.setAttribute("class", "m-e-c-f-row");
  var bGuestTitle = document.createElement("p");
  bGuestTitle.setAttribute("class", "m-e-c-f-title");
  bGuestTitle.innerHTML = wrd_guestNum;
  var bGuestInputWrp = document.createElement("div");
  bGuestInputWrp.setAttribute("class", "m-e-c-f-input-wrp");
  var bGuestInput = document.createElement("input");
  bGuestInput.setAttribute("type", "number");
  bGuestInput.setAttribute("value", guestNum);
  bGuestInput.setAttribute("min", "1");
  bGuestInput.setAttribute("max", maxGuestNum);
  bGuestInput.setAttribute("class", "m-e-c-f-input");
  bGuestInput.setAttribute("id", "m-e-c-f-input-guest-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  if (bookingSts != "booked") {
    bGuestInput.disabled = true;
  }
  // from
  var bFromWrp = document.createElement("div");
  bFromWrp.setAttribute("class", "m-e-c-f-row");
  var bFromTitle = document.createElement("p");
  bFromTitle.setAttribute("class", "m-e-c-f-title");
  bFromTitle.innerHTML = wrd_from;
  var bFromInputWrp = document.createElement("div");
  bFromInputWrp.setAttribute("class", "m-e-c-f-input-wrp");
  var bFromInput = document.createElement("input");
  bFromInput.setAttribute("type", "date");
  bFromInput.setAttribute("value", fromy +"-"+ ("0"+ fromm).slice(-2) +"-"+ ("0"+ fromd).slice(-2));
  bFromInput.setAttribute("class", "m-e-c-f-input");
  bFromInput.setAttribute("id", "m-e-c-f-input-from-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  if (bookingSts != "booked") {
    bFromInput.disabled = true;
  }
  var bFromDropdownWrp = document.createElement("div");
  bFromDropdownWrp.setAttribute("class", "m-e-c-f-dropdown-wrp");
  var bFromDropdownBtn = document.createElement("button");
  bFromDropdownBtn.setAttribute("type", "button");
  bFromDropdownBtn.setAttribute("value", "show");
  bFromDropdownBtn.setAttribute("class", "m-e-c-f-dropdown-btn");
  bFromDropdownBtn.setAttribute("onclick", "editorInputDateDropdownToggle(this.value, this, 'm-e-c-f-dropdown-from-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy +"')");
  var bFromDropdownBtnTxtTime = document.createElement("p");
  bFromDropdownBtnTxtTime.setAttribute("class", "m-e-c-f-dropdown-btn-txt");
  bFromDropdownBtnTxtTime.setAttribute("id", "m-e-c-f-dropdown-btn-txt-from-1-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  if (firstDSts == "half") {
    bFromDropdownBtnTxtTime.classList.add("m-e-c-f-dropdown-btn-txt-show");
  }
  bFromDropdownBtnTxtTime.innerHTML = wrd_from +" 14:00";
  var bFromDropdownBtnTxtWhole = document.createElement("p");
  bFromDropdownBtnTxtWhole.setAttribute("class", "m-e-c-f-dropdown-btn-txt");
  bFromDropdownBtnTxtWhole.setAttribute("id", "m-e-c-f-dropdown-btn-txt-from-2-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  if (firstDSts == "whole") {
    bFromDropdownBtnTxtWhole.classList.add("m-e-c-f-dropdown-btn-txt-show");
  }
  bFromDropdownBtnTxtWhole.innerHTML = wrd_theWholeDay;
  if (bookingSts == "booked") {
    var bFromDropdownBtnArrow = document.createElement("div");
    bFromDropdownBtnArrow.setAttribute("class", "m-e-c-f-dropdown-btn-arrow");
    var bFromDropdown = document.createElement("div");
    bFromDropdown.setAttribute("class", "m-e-c-f-dropdown");
    bFromDropdown.setAttribute("id", "m-e-c-f-dropdown-from-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    var bFromDropdownLabelTime = document.createElement("label");
    bFromDropdownLabelTime.setAttribute("class", "m-e-c-f-dropdown-radio-container");
    bFromDropdownLabelTime.innerHTML = wrd_from +" 14:00";
    var bFromDropdownInputTime = document.createElement("input");
    bFromDropdownInputTime.setAttribute("type", "radio");
    bFromDropdownInputTime.setAttribute("onchange", "editorInputDateDropdownSelectOnchange('from', 1, '"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy +"')");
    bFromDropdownInputTime.setAttribute("name", "m-e-c-f-dropdown-radio-from-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    bFromDropdownInputTime.setAttribute("id", "m-e-c-f-dropdown-radio-from-1-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    if (firstDSts == "half") {
      bFromDropdownInputTime.checked = true;
    }
    var bFromDropdownSpanTime = document.createElement("span");
    bFromDropdownSpanTime.setAttribute("class", "m-e-c-f-dropdown-radio-checkmark");
    var bFromDropdownLabelWhole = document.createElement("label");
    bFromDropdownLabelWhole.setAttribute("class", "m-e-c-f-dropdown-radio-container");
    bFromDropdownLabelWhole.innerHTML = wrd_theWholeDay;
    var bFromDropdownInputWhole = document.createElement("input");
    bFromDropdownInputWhole.setAttribute("type", "radio");
    bFromDropdownInputWhole.setAttribute("onchange", "editorInputDateDropdownSelectOnchange('from', 2, '"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy +"')");
    bFromDropdownInputWhole.setAttribute("name", "m-e-c-f-dropdown-radio-from-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    bFromDropdownInputWhole.setAttribute("id", "m-e-c-f-dropdown-radio-from-2-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    if (firstDSts == "whole") {
      bFromDropdownInputWhole.checked = true;
    }
    var bFromDropdownSpanWhole = document.createElement("span");
    bFromDropdownSpanWhole.setAttribute("class", "m-e-c-f-dropdown-radio-checkmark");
  }
  // to
  var bToWrp = document.createElement("div");
  bToWrp.setAttribute("class", "m-e-c-f-row");
  var bToTitle = document.createElement("p");
  bToTitle.setAttribute("class", "m-e-c-f-title");
  bToTitle.innerHTML = wrd_to;
  var bToInputWrp = document.createElement("div");
  bToInputWrp.setAttribute("class", "m-e-c-f-input-wrp");
  var bToInput = document.createElement("input");
  bToInput.setAttribute("type", "date");
  bToInput.setAttribute("value", toy +"-"+ ("0"+ tom).slice(-2) +"-"+ ("0"+ tod).slice(-2));
  bToInput.setAttribute("class", "m-e-c-f-input");
  bToInput.setAttribute("id", "m-e-c-f-input-to-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  if (bookingSts != "booked") {
    bToInput.disabled = true;
  }
  var bToDropdownWrp = document.createElement("div");
  bToDropdownWrp.setAttribute("class", "m-e-c-f-dropdown-wrp");
  var bToDropdownBtn = document.createElement("button");
  bToDropdownBtn.setAttribute("type", "button");
  bToDropdownBtn.setAttribute("value", "show");
  bToDropdownBtn.setAttribute("class", "m-e-c-f-dropdown-btn");
  bToDropdownBtn.setAttribute("onclick", "editorInputDateDropdownToggle(this.value, this, 'm-e-c-f-dropdown-to-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy +"')");
  var bToDropdownBtnTxtTime = document.createElement("p");
  bToDropdownBtnTxtTime.setAttribute("class", "m-e-c-f-dropdown-btn-txt");
  bToDropdownBtnTxtTime.setAttribute("id", "m-e-c-f-dropdown-btn-txt-to-1-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  if (lastDSts == "half") {
    bToDropdownBtnTxtTime.classList.add("m-e-c-f-dropdown-btn-txt-show");
  }
  bToDropdownBtnTxtTime.innerHTML = wrd_to +" 11:00";
  var bToDropdownBtnTxtWhole = document.createElement("p");
  bToDropdownBtnTxtWhole.setAttribute("class", "m-e-c-f-dropdown-btn-txt");
  bToDropdownBtnTxtWhole.setAttribute("id", "m-e-c-f-dropdown-btn-txt-to-2-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  if (lastDSts == "whole") {
    bToDropdownBtnTxtWhole.classList.add("m-e-c-f-dropdown-btn-txt-show");
  }
  bToDropdownBtnTxtWhole.innerHTML = wrd_theWholeDay;
  var bToDropdownBtnArrow = document.createElement("div");
  if (bookingSts == "booked") {
    bToDropdownBtnArrow.setAttribute("class", "m-e-c-f-dropdown-btn-arrow");
    var bToDropdown = document.createElement("div");
    bToDropdown.setAttribute("class", "m-e-c-f-dropdown");
    bToDropdown.setAttribute("id", "m-e-c-f-dropdown-to-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    var bToDropdownLabelTime = document.createElement("label");
    bToDropdownLabelTime.setAttribute("class", "m-e-c-f-dropdown-radio-container");
    bToDropdownLabelTime.innerHTML = wrd_to +" 11:00";
    var bToDropdownInputTime = document.createElement("input");
    bToDropdownInputTime.setAttribute("type", "radio");
    bToDropdownInputTime.setAttribute("onchange", "editorInputDateDropdownSelectOnchange('to', 1, '"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy +"')");
    bToDropdownInputTime.setAttribute("name", "m-e-c-f-dropdown-radio-to-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    bToDropdownInputTime.setAttribute("id", "m-e-c-f-dropdown-radio-to-1-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    if (lastDSts == "half") {
      bToDropdownInputTime.checked = true;
    }
    var bToDropdownSpanTime = document.createElement("span");
    bToDropdownSpanTime.setAttribute("class", "m-e-c-f-dropdown-radio-checkmark");
    var bToDropdownLabelWhole = document.createElement("label");
    bToDropdownLabelWhole.setAttribute("class", "m-e-c-f-dropdown-radio-container");
    bToDropdownLabelWhole.innerHTML = wrd_theWholeDay;
    var bToDropdownInputWhole = document.createElement("input");
    bToDropdownInputWhole.setAttribute("type", "radio");
    bToDropdownInputWhole.setAttribute("onchange", "editorInputDateDropdownSelectOnchange('to', 2, '"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy +"')");
    bToDropdownInputWhole.setAttribute("name", "m-e-c-f-dropdown-radio-to-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    bToDropdownInputWhole.setAttribute("id", "m-e-c-f-dropdown-radio-to-2-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    if (lastDSts == "whole") {
      bToDropdownInputWhole.checked = true;
    }
    var bToDropdownSpanWhole = document.createElement("span");
    bToDropdownSpanWhole.setAttribute("class", "m-e-c-f-dropdown-radio-checkmark");
  }
  if (bookingSts == "booked") {
    // update requests
    var bUpdateRequestsWrp = document.createElement("div");
    bUpdateRequestsWrp.setAttribute("class", "m-e-c-f-row");
    var bUpdateRequestsBtn = document.createElement("button");
    bUpdateRequestsBtn.setAttribute("class", "btn");
    bUpdateRequestsBtn.classList.add("btn-mid");
    bUpdateRequestsBtn.classList.add("btn-sec");
    bUpdateRequestsBtn.innerHTML = wrd_requestsForChangesInTheBooking;
    bUpdateRequestsBtn.setAttribute("onclick", "window.open('../bookings/booking-update.php?plc="+ plc_id +"&booking="+ booking_id +"', '_blank').focus();");
    // notes
    var bNotesWrp = document.createElement("div");
    bNotesWrp.setAttribute("class", "m-e-c-f-row");
    var bNotesTitle = document.createElement("p");
    bNotesTitle.setAttribute("class", "m-e-c-f-title");
    bNotesTitle.innerHTML = wrd_notes;
    var bNotesTextareaWrp = document.createElement("div");
    bNotesTextareaWrp.setAttribute("class", "m-e-c-f-input-wrp");
    var bNotesTextarea = document.createElement("textarea");
    bNotesTextarea.setAttribute("class", "m-e-c-f-textarea");
    bNotesTextarea.setAttribute("placeholder", wrd_type);
    bNotesTextarea.innerHTML = notes;
    bNotesTextarea.setAttribute("id", "m-e-c-f-textarea-notes-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    // deposit
    var bDepositWrp = document.createElement("div");
    bDepositWrp.setAttribute("class", "m-e-c-f-row");
    var bDepositSize = document.createElement("div");
    bDepositSize.setAttribute("class", "m-e-c-f-checkbox-size");
    var bDepositCheckboxLabel = document.createElement("label");
    bDepositCheckboxLabel.setAttribute("class", "m-e-c-f-checkbox-label");
    bDepositCheckboxLabel.innerHTML = wrd_theDepositIsPaid;
    var bDepositCheckboxInput = document.createElement("input");
    bDepositCheckboxInput.setAttribute("type", "checkbox");
    bDepositCheckboxInput.setAttribute("id", "m-e-c-f-checkbox-label-deposit-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    bDepositCheckboxInput.setAttribute("onchange", "editorCalendarDepositChanged('"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy +"')");
    if (depositSts == "1") {
      bDepositCheckboxInput.checked = true;
    }
    var bDepositCheckboxSpan = document.createElement("span");
    bDepositCheckboxSpan.setAttribute("class", "m-e-c-f-checkbox-checkmark");
    // full amount
    var bFullAmountWrp = document.createElement("div");
    bFullAmountWrp.setAttribute("class", "m-e-c-f-row");
    var bFullAmountSize = document.createElement("div");
    bFullAmountSize.setAttribute("class", "m-e-c-f-checkbox-size");
    var bFullAmountCheckboxLabel = document.createElement("label");
    bFullAmountCheckboxLabel.setAttribute("class", "m-e-c-f-checkbox-label");
    bFullAmountCheckboxLabel.innerHTML = wrd_theFullAmountIsPaid;
    var bFullAmountCheckboxInput = document.createElement("input");
    bFullAmountCheckboxInput.setAttribute("type", "checkbox");
    bFullAmountCheckboxInput.setAttribute("id", "m-e-c-f-checkbox-label-full-amount-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    bFullAmountCheckboxInput.setAttribute("onchange", "editorCalendarFullAmountChanged('"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy +"')");
    if (fullAmountSts == "1") {
      bFullAmountCheckboxInput.checked = true;
    }
    var bFullAmountCheckboxSpan = document.createElement("span");
    bFullAmountCheckboxSpan.setAttribute("class", "m-e-c-f-checkbox-checkmark");
  }
  // total price
  var bTotalPriceWrp = document.createElement("div");
  bTotalPriceWrp.setAttribute("class", "m-e-c-f-row");
  bTotalPriceWrp.classList.add("m-e-c-f-price");
  var bTotalPriceLayout = document.createElement("div");
  bTotalPriceLayout.setAttribute("class", "m-e-c-f-price-layout");
  var bTotalPriceTitle = document.createElement("p");
  bTotalPriceTitle.setAttribute("class", "m-e-c-f-price-title");
  bTotalPriceTitle.innerHTML = wrd_totalPrice;
  var bTotalPriceTxt = document.createElement("p");
  bTotalPriceTxt.setAttribute("class", "m-e-c-f-price-txt");
  bTotalPriceTxt.innerHTML = addCurrency(totalcurrency, totalprice);
  // fee
  var bFeeWrp = document.createElement("div");
  bFeeWrp.setAttribute("class", "m-e-c-f-row");
  bFeeWrp.classList.add("m-e-c-f-price");
  var bFeeLayout = document.createElement("div");
  bFeeLayout.setAttribute("class", "m-e-c-f-price-layout");
  var bFeeTitle = document.createElement("p");
  bFeeTitle.setAttribute("class", "m-e-c-f-price-title");
  bFeeTitle.innerHTML = wrd_fee;
  var bFeeTxt = document.createElement("p");
  bFeeTxt.setAttribute("class", "m-e-c-f-price-txt");
  bFeeTxt.innerHTML = addCurrency(totalcurrency, fee) +" ("+ parseFloat(feePerc) +"%)";
  // btn wrp
  var bBtnWrp = document.createElement("div");
  bBtnWrp.setAttribute("class", "m-e-c-f-btn-wrp");
  bBtnWrp.classList.add("m-e-c-f-row");
  if (bookingSts == "booked") {
    var bBtnCancel = document.createElement("button");
    bBtnCancel.setAttribute("type", "button");
    bBtnCancel.setAttribute("id", "m-e-c-f-btn-cancel-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    bBtnCancel.setAttribute("class", "btn");
    bBtnCancel.classList.add("btn-mid");
    bBtnCancel.classList.add("btn-fth");
    bBtnCancel.classList.add("m-e-c-f-btn");
    bBtnCancel.classList.add("m-e-c-f-btn-margin");
    bBtnCancel.setAttribute("onclick", "editorCalendarCancelBookingModal('show', '"+ fromd +"', '"+ fromm +"', '"+ fromy +"', '"+ tod +"', '"+ tom +"', '"+ toy +"')");
    bBtnCancel.innerHTML = wrd_cancelBooking;
    var bBtnUpdate = document.createElement("button");
    bBtnUpdate.setAttribute("type", "button");
    bBtnUpdate.setAttribute("id", "m-e-c-f-btn-update-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    bBtnUpdate.setAttribute("class", "btn");
    bBtnUpdate.classList.add("btn-mid");
    bBtnUpdate.classList.add("btn-prim");
    bBtnUpdate.classList.add("m-e-c-f-btn");
    bBtnUpdate.classList.add("m-e-c-f-btn-margin");
    bBtnUpdate.setAttribute("onclick", "editorCalendarUpdateBookingCheck('"+ fromd +"', '"+ fromm +"', '"+ fromy +"', '"+ tod +"', '"+ tom +"', '"+ toy +"')");
    bBtnUpdate.innerHTML = wrd_updateChanges;
  } else {
    var bBtnReject = document.createElement("button");
    bBtnReject.setAttribute("type", "button");
    bBtnReject.setAttribute("id", "m-e-c-f-btn-reject-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    bBtnReject.setAttribute("class", "btn");
    bBtnReject.classList.add("btn-mid");
    bBtnReject.classList.add("btn-fth");
    bBtnReject.classList.add("m-e-c-f-btn");
    bBtnReject.classList.add("m-e-c-f-btn-margin");
    bBtnReject.setAttribute("onclick", "editorCalendarRejectBooking('"+ fromd +"', '"+ fromm +"', '"+ fromy +"', '"+ tod +"', '"+ tom +"', '"+ toy +"')");
    bBtnReject.innerHTML = wrd_reject;
    var bBtnConfirm = document.createElement("button");
    bBtnConfirm.setAttribute("type", "button");
    bBtnConfirm.setAttribute("id", "m-e-c-f-btn-confirm-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    bBtnConfirm.setAttribute("class", "btn");
    bBtnConfirm.classList.add("btn-mid");
    bBtnConfirm.classList.add("btn-prim");
    bBtnConfirm.classList.add("m-e-c-f-btn");
    bBtnConfirm.classList.add("m-e-c-f-btn-margin");
    bBtnConfirm.setAttribute("onclick", "editorCalendarConfirmBooking('"+ fromd +"', '"+ fromm +"', '"+ fromy +"', '"+ tod +"', '"+ tom +"', '"+ toy +"')");
    bBtnConfirm.innerHTML = wrd_confirm;
  }
  if (bookingSts == "booked") {
    if (lessThan48h == "1") {
      // less than 48h
      bBlckWrp.appendChild(bLessThan48hWrp);
      bLessThan48hWrp.appendChild(bLessThan48hBackground);
      bLessThan48hBackground.appendChild(bLessThan48hTxt);
    }
    if (countdownBanner == "1") {
      if (depositSts == 0 && fullAmountSts == 0 && lessThan48h != "1") {
        // time till cancel
        bBlckWrp.appendChild(bTimeTillCancelWrp);
        bTimeTillCancelWrp.appendChild(bTimeTillCancelBackground);
        bTimeTillCancelBackground.appendChild(bTimeTillCancelHeader);
        bTimeTillCancelHeader.appendChild(bTimeTillCancelTxt);
        bTimeTillCancelHeader.appendChild(bTimeTillCancelHeaderBtnWrp);
        bTimeTillCancelHeaderBtnWrp.appendChild(bTimeTillCancelHeaderBtn);
        bTimeTillCancelBackground.appendChild(bTimeTillCancelDescWrp);
        bTimeTillCancelDescWrp.appendChild(bTimeTillCancelDescTxt);
      }
    }
  } else {
    // booking offer
    bBlckWrp.appendChild(bBookingOfferWrp);
    bBookingOfferWrp.appendChild(bBookingOfferBackground);
    bBookingOfferBackground.appendChild(bBookingOfferHeader);
    bBookingOfferHeader.appendChild(bBookingOfferTxt);
    bBookingOfferHeader.appendChild(bBookingOfferHeaderBtnWrp);
    bBookingOfferHeaderBtnWrp.appendChild(bBookingOfferHeaderBtn);
    bBookingOfferBackground.appendChild(bBookingOfferDescWrp);
    bBookingOfferDescWrp.appendChild(bBookingOfferDescTxt);
  }
  if (bookingSts == "booked") {
    // name
    bBlckWrp.appendChild(bNameWrp);
    bNameWrp.appendChild(bNameTitle);
    bNameWrp.appendChild(bNameInputWrp);
    bNameInputWrp.appendChild(bNameInput);
    // email
    bBlckWrp.appendChild(bEmailWrp);
    bEmailWrp.appendChild(bEmailTitle);
    bEmailWrp.appendChild(bEmailInputWrp);
    bEmailInputWrp.appendChild(bEmailInput);
    // phone
    bBlckWrp.appendChild(bPhoneWrp);
    bPhoneWrp.appendChild(bPhoneTitle);
    bPhoneWrp.appendChild(bPhoneInputWrp);
    bPhoneInputWrp.appendChild(bPhoneInput);
  }
  // guest
  bBlckWrp.appendChild(bGuestWrp);
  bGuestWrp.appendChild(bGuestTitle);
  bGuestWrp.appendChild(bGuestInputWrp);
  bGuestInputWrp.appendChild(bGuestInput);
  // from
  bBlckWrp.appendChild(bFromWrp);
  bFromWrp.appendChild(bFromTitle);
  bFromWrp.appendChild(bFromInputWrp);
  bFromInputWrp.appendChild(bFromInput);
  bFromInputWrp.appendChild(bFromDropdownWrp);
  bFromDropdownWrp.appendChild(bFromDropdownBtn);
  bFromDropdownBtn.appendChild(bFromDropdownBtnTxtTime);
  bFromDropdownBtn.appendChild(bFromDropdownBtnTxtWhole);
  if (bookingSts == "booked") {
    bFromDropdownBtn.appendChild(bFromDropdownBtnArrow);
    bFromDropdownWrp.appendChild(bFromDropdown);
    bFromDropdown.appendChild(bFromDropdownLabelTime);
    bFromDropdownLabelTime.appendChild(bFromDropdownInputTime);
    bFromDropdownLabelTime.appendChild(bFromDropdownSpanTime);
    bFromDropdown.appendChild(bFromDropdownLabelWhole);
    bFromDropdownLabelWhole.appendChild(bFromDropdownInputWhole);
    bFromDropdownLabelWhole.appendChild(bFromDropdownSpanWhole);
  }
  // to
  bBlckWrp.appendChild(bToWrp);
  bToWrp.appendChild(bToTitle);
  bToWrp.appendChild(bToInputWrp);
  bToInputWrp.appendChild(bToInput);
  bToInputWrp.appendChild(bToDropdownWrp);
  bToDropdownWrp.appendChild(bToDropdownBtn);
  bToDropdownBtn.appendChild(bToDropdownBtnTxtTime);
  bToDropdownBtn.appendChild(bToDropdownBtnTxtWhole);
  if (bookingSts == "booked") {
    bToDropdownBtn.appendChild(bToDropdownBtnArrow);
    bToDropdownWrp.appendChild(bToDropdown);
    bToDropdown.appendChild(bToDropdownLabelTime);
    bToDropdownLabelTime.appendChild(bToDropdownInputTime);
    bToDropdownLabelTime.appendChild(bToDropdownSpanTime);
    bToDropdown.appendChild(bToDropdownLabelWhole);
    bToDropdownLabelWhole.appendChild(bToDropdownInputWhole);
    bToDropdownLabelWhole.appendChild(bToDropdownSpanWhole);
  }
  if (bookingSts == "booked") {
    // update requests
    bBlckWrp.appendChild(bUpdateRequestsWrp);
    bUpdateRequestsWrp.appendChild(bUpdateRequestsBtn);
    // notes
    bBlckWrp.appendChild(bNotesWrp);
    bNotesWrp.appendChild(bNotesTitle);
    bNotesWrp.appendChild(bNotesTextareaWrp);
    bNotesTextareaWrp.appendChild(bNotesTextarea);
    // deposit
    bBlckWrp.appendChild(bDepositWrp);
    bDepositWrp.appendChild(bDepositSize);
    bDepositSize.appendChild(bDepositCheckboxLabel);
    bDepositCheckboxLabel.appendChild(bDepositCheckboxInput);
    bDepositCheckboxLabel.appendChild(bDepositCheckboxSpan);
    // full amount
    bBlckWrp.appendChild(bFullAmountWrp);
    bFullAmountWrp.appendChild(bFullAmountSize);
    bFullAmountSize.appendChild(bFullAmountCheckboxLabel);
    bFullAmountCheckboxLabel.appendChild(bFullAmountCheckboxInput);
    bFullAmountCheckboxLabel.appendChild(bFullAmountCheckboxSpan);
  }
  // total price
  bBlckWrp.appendChild(bTotalPriceWrp);
  bTotalPriceWrp.appendChild(bTotalPriceLayout);
  bTotalPriceLayout.appendChild(bTotalPriceTitle);
  bTotalPriceLayout.appendChild(bTotalPriceTxt);
  // fee
  bBlckWrp.appendChild(bFeeWrp);
  bFeeWrp.appendChild(bFeeLayout);
  bFeeLayout.appendChild(bFeeTitle);
  bFeeLayout.appendChild(bFeeTxt);
  // btn wrp
  bBlckWrp.appendChild(bBtnWrp);
  if (bookingSts == "booked") {
    bBtnWrp.appendChild(bBtnCancel);
    bBtnWrp.appendChild(bBtnUpdate);
  } else {
    bBtnWrp.appendChild(bBtnReject);
    bBtnWrp.appendChild(bBtnConfirm);
  }
  document.getElementById("editor-calendar-day-details-content").appendChild(bBlckWrp);
  editCalendarRenderBookingDetailsBorder();
}

function editCalendarRenderTechnicalShutdownDetails(ctgr, notes, fromd, fromm, fromy, firstDSts, tod, tom, toy, lastDSts) {
  var tsBlckWrp = document.createElement("div");
  tsBlckWrp.setAttribute("class", "m-e-c-f-blck-wrp");
  // category
  var tsCategoryWrp = document.createElement("div");
  tsCategoryWrp.setAttribute("class", "m-e-c-f-row");
  var tsCategoryLayout = document.createElement("div");
  tsCategoryLayout.setAttribute("class", "m-e-c-f-select-layout");
  var tsCategoryTitle = document.createElement("p");
  tsCategoryTitle.setAttribute("class", "m-e-c-f-title-2");
  tsCategoryTitle.innerHTML = wrd_category;
  var tsCategorySelect = document.createElement("select");
  tsCategorySelect.setAttribute("class", "m-e-c-f-select");
  tsCategorySelect.setAttribute("id", "m-e-c-f-select-category-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  var tsCategorySelectOption1 = document.createElement("option");
  tsCategorySelectOption1.setAttribute("class", "m-e-c-f-select-option-technical-shutdown");
  tsCategorySelectOption1.setAttribute("value", "cleaning");
  if (ctgr == "cleaning") {
    tsCategorySelectOption1.setAttribute("selected", "");
  }
  tsCategorySelectOption1.innerHTML = wrd_cleaning;
  var tsCategorySelectOption2 = document.createElement("option");
  tsCategorySelectOption2.setAttribute("class", "m-e-c-f-select-option-technical-shutdown");
  tsCategorySelectOption2.setAttribute("value", "maintenance");
  if (ctgr == "maintenance") {
    tsCategorySelectOption2.setAttribute("selected", "");
  }
  tsCategorySelectOption2.innerHTML = wrd_maintenance;
  var tsCategorySelectOption3 = document.createElement("option");
  tsCategorySelectOption3.setAttribute("class", "m-e-c-f-select-option-technical-shutdown");
  tsCategorySelectOption3.setAttribute("value", "reconstruction");
  if (ctgr == "reconstruction") {
    tsCategorySelectOption3.setAttribute("selected", "");
  }
  tsCategorySelectOption3.innerHTML = wrd_reconstruction;
  var tsCategorySelectOption4 = document.createElement("option");
  tsCategorySelectOption4.setAttribute("class", "m-e-c-f-select-option-technical-shutdown");
  tsCategorySelectOption4.setAttribute("value", "other");
  if (ctgr != "cleaning" && ctgr != "maintenance" && ctgr != "reconstruction") {
    tsCategorySelectOption4.setAttribute("selected", "");
  }
  tsCategorySelectOption4.innerHTML = wrd_other;
  // from
  var tsFromWrp = document.createElement("div");
  tsFromWrp.setAttribute("class", "m-e-c-f-row");
  var tsFromTitle = document.createElement("p");
  tsFromTitle.setAttribute("class", "m-e-c-f-title");
  tsFromTitle.innerHTML = wrd_from;
  var tsFromInputWrp = document.createElement("div");
  tsFromInputWrp.setAttribute("class", "m-e-c-f-input-wrp");
  var tsFromInput = document.createElement("input");
  tsFromInput.setAttribute("type", "date");
  tsFromInput.setAttribute("value", fromy +"-"+ ("0"+ fromm).slice(-2) +"-"+ ("0"+ fromd).slice(-2));
  tsFromInput.setAttribute("class", "m-e-c-f-input");
  tsFromInput.setAttribute("id", "m-e-c-f-input-from-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  var tsFromDropdownWrp = document.createElement("div");
  tsFromDropdownWrp.setAttribute("class", "m-e-c-f-dropdown-wrp");
  var tsFromDropdownBtn = document.createElement("button");
  tsFromDropdownBtn.setAttribute("type", "button");
  tsFromDropdownBtn.setAttribute("value", "show");
  tsFromDropdownBtn.setAttribute("class", "m-e-c-f-dropdown-btn");
  tsFromDropdownBtn.setAttribute("onclick", "editorInputDateDropdownToggle(this.value, this, 'm-e-c-f-dropdown-from-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy +"')");
  var tsFromDropdownBtnTxtTime = document.createElement("p");
  tsFromDropdownBtnTxtTime.setAttribute("class", "m-e-c-f-dropdown-btn-txt");
  tsFromDropdownBtnTxtTime.setAttribute("id", "m-e-c-f-dropdown-btn-txt-from-1-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  if (firstDSts == "half") {
    tsFromDropdownBtnTxtTime.classList.add("m-e-c-f-dropdown-btn-txt-show");
  }
  tsFromDropdownBtnTxtTime.innerHTML = wrd_from +" 14:00";
  var tsFromDropdownBtnTxtWhole = document.createElement("p");
  tsFromDropdownBtnTxtWhole.setAttribute("class", "m-e-c-f-dropdown-btn-txt");
  tsFromDropdownBtnTxtWhole.setAttribute("id", "m-e-c-f-dropdown-btn-txt-from-2-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  if (firstDSts == "whole") {
    tsFromDropdownBtnTxtWhole.classList.add("m-e-c-f-dropdown-btn-txt-show");
  }
  tsFromDropdownBtnTxtWhole.innerHTML = wrd_theWholeDay;
  var tsFromDropdownBtnArrow = document.createElement("div");
  tsFromDropdownBtnArrow.setAttribute("class", "m-e-c-f-dropdown-btn-arrow");
  var tsFromDropdown = document.createElement("div");
  tsFromDropdown.setAttribute("class", "m-e-c-f-dropdown");
  tsFromDropdown.setAttribute("id", "m-e-c-f-dropdown-from-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  var tsFromDropdownLabelTime = document.createElement("label");
  tsFromDropdownLabelTime.setAttribute("class", "m-e-c-f-dropdown-radio-container");
  tsFromDropdownLabelTime.innerHTML = wrd_from +" 14:00";
  var tsFromDropdownInputTime = document.createElement("input");
  tsFromDropdownInputTime.setAttribute("type", "radio");
  tsFromDropdownInputTime.setAttribute("onchange", "editorInputDateDropdownSelectOnchange('from', 1, '"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy +"')");
  tsFromDropdownInputTime.setAttribute("name", "m-e-c-f-dropdown-radio-from-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  tsFromDropdownInputTime.setAttribute("id", "m-e-c-f-dropdown-radio-from-1-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  if (firstDSts == "half") {
    tsFromDropdownInputTime.checked = true;
  }
  var tsFromDropdownSpanTime = document.createElement("span");
  tsFromDropdownSpanTime.setAttribute("class", "m-e-c-f-dropdown-radio-checkmark");
  var tsFromDropdownLabelWhole = document.createElement("label");
  tsFromDropdownLabelWhole.setAttribute("class", "m-e-c-f-dropdown-radio-container");
  tsFromDropdownLabelWhole.innerHTML = wrd_theWholeDay;
  var tsFromDropdownInputWhole = document.createElement("input");
  tsFromDropdownInputWhole.setAttribute("type", "radio");
  tsFromDropdownInputWhole.setAttribute("onchange", "editorInputDateDropdownSelectOnchange('from', 2, '"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy +"')");
  tsFromDropdownInputWhole.setAttribute("name", "m-e-c-f-dropdown-radio-from-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  tsFromDropdownInputWhole.setAttribute("id", "m-e-c-f-dropdown-radio-from-2-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  if (firstDSts == "whole") {
    tsFromDropdownInputWhole.checked = true;
  }
  var tsFromDropdownSpanWhole = document.createElement("span");
  tsFromDropdownSpanWhole.setAttribute("class", "m-e-c-f-dropdown-radio-checkmark");
  // to
  var tsToWrp = document.createElement("div");
  tsToWrp.setAttribute("class", "m-e-c-f-row");
  var tsToTitle = document.createElement("p");
  tsToTitle.setAttribute("class", "m-e-c-f-title");
  tsToTitle.innerHTML = wrd_to;
  var tsToInputWrp = document.createElement("div");
  tsToInputWrp.setAttribute("class", "m-e-c-f-input-wrp");
  var tsToInput = document.createElement("input");
  tsToInput.setAttribute("type", "date");
  tsToInput.setAttribute("value", toy +"-"+ ("0"+ tom).slice(-2) +"-"+ ("0"+ tod).slice(-2));
  tsToInput.setAttribute("class", "m-e-c-f-input");
  tsToInput.setAttribute("id", "m-e-c-f-input-to-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  var tsToDropdownWrp = document.createElement("div");
  tsToDropdownWrp.setAttribute("class", "m-e-c-f-dropdown-wrp");
  var tsToDropdownBtn = document.createElement("button");
  tsToDropdownBtn.setAttribute("type", "button");
  tsToDropdownBtn.setAttribute("value", "show");
  tsToDropdownBtn.setAttribute("class", "m-e-c-f-dropdown-btn");
  tsToDropdownBtn.setAttribute("onclick", "editorInputDateDropdownToggle(this.value, this, 'm-e-c-f-dropdown-to-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy +"')");
  var tsToDropdownBtnTxtTime = document.createElement("p");
  tsToDropdownBtnTxtTime.setAttribute("class", "m-e-c-f-dropdown-btn-txt");
  tsToDropdownBtnTxtTime.setAttribute("id", "m-e-c-f-dropdown-btn-txt-to-1-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  if (lastDSts == "half") {
    tsToDropdownBtnTxtTime.classList.add("m-e-c-f-dropdown-btn-txt-show");
  }
  tsToDropdownBtnTxtTime.innerHTML = wrd_to +" 11:00";
  var tsToDropdownBtnTxtWhole = document.createElement("p");
  tsToDropdownBtnTxtWhole.setAttribute("class", "m-e-c-f-dropdown-btn-txt");
  tsToDropdownBtnTxtWhole.setAttribute("id", "m-e-c-f-dropdown-btn-txt-to-2-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  if (lastDSts == "whole") {
    tsToDropdownBtnTxtWhole.classList.add("m-e-c-f-dropdown-btn-txt-show");
  }
  tsToDropdownBtnTxtWhole.innerHTML = wrd_theWholeDay;
  var tsToDropdownBtnArrow = document.createElement("div");
  tsToDropdownBtnArrow.setAttribute("class", "m-e-c-f-dropdown-btn-arrow");
  var tsToDropdown = document.createElement("div");
  tsToDropdown.setAttribute("class", "m-e-c-f-dropdown");
  tsToDropdown.setAttribute("id", "m-e-c-f-dropdown-to-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  var tsToDropdownLabelTime = document.createElement("label");
  tsToDropdownLabelTime.setAttribute("class", "m-e-c-f-dropdown-radio-container");
  tsToDropdownLabelTime.innerHTML = wrd_to +" 11:00";
  var tsToDropdownInputTime = document.createElement("input");
  tsToDropdownInputTime.setAttribute("type", "radio");
  tsToDropdownInputTime.setAttribute("onchange", "editorInputDateDropdownSelectOnchange('to', 1, '"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy +"')");
  tsToDropdownInputTime.setAttribute("name", "m-e-c-f-dropdown-radio-to-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  tsToDropdownInputTime.setAttribute("id", "m-e-c-f-dropdown-radio-to-1-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  if (lastDSts == "half") {
    tsToDropdownInputTime.checked = true;
  }
  var tsToDropdownSpanTime = document.createElement("span");
  tsToDropdownSpanTime.setAttribute("class", "m-e-c-f-dropdown-radio-checkmark");
  var tsToDropdownLabelWhole = document.createElement("label");
  tsToDropdownLabelWhole.setAttribute("class", "m-e-c-f-dropdown-radio-container");
  tsToDropdownLabelWhole.innerHTML = wrd_theWholeDay;
  var tsToDropdownInputWhole = document.createElement("input");
  tsToDropdownInputWhole.setAttribute("type", "radio");
  tsToDropdownInputWhole.setAttribute("onchange", "editorInputDateDropdownSelectOnchange('to', 2, '"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy +"')");
  tsToDropdownInputWhole.setAttribute("name", "m-e-c-f-dropdown-radio-to-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  tsToDropdownInputWhole.setAttribute("id", "m-e-c-f-dropdown-radio-to-2-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  if (lastDSts == "whole") {
    tsToDropdownInputWhole.checked = true;
  }
  var tsToDropdownSpanWhole = document.createElement("span");
  tsToDropdownSpanWhole.setAttribute("class", "m-e-c-f-dropdown-radio-checkmark");
  // notes
  var tsNotesWrp = document.createElement("div");
  tsNotesWrp.setAttribute("class", "m-e-c-f-row");
  var tsNotesTitle = document.createElement("p");
  tsNotesTitle.setAttribute("class", "m-e-c-f-title");
  tsNotesTitle.innerHTML = wrd_notes;
  var tsNotesTextareaWrp = document.createElement("div");
  tsNotesTextareaWrp.setAttribute("class", "m-e-c-f-input-wrp");
  var tsNotesTextarea = document.createElement("textarea");
  tsNotesTextarea.setAttribute("class", "m-e-c-f-textarea");
  tsNotesTextarea.setAttribute("placeholder", wrd_type);
  tsNotesTextarea.innerHTML = notes;
  tsNotesTextarea.setAttribute("id", "m-e-c-f-textarea-notes-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  // btn wrp
  var tsBtnWrp = document.createElement("div");
  tsBtnWrp.setAttribute("class", "m-e-c-f-btn-wrp");
  tsBtnWrp.classList.add("m-e-c-f-btn-wrp-technical-shutdown");
  tsBtnWrp.classList.add("m-e-c-f-row");
  var tsBtnCancel = document.createElement("button");
  tsBtnCancel.setAttribute("type", "button");
  tsBtnCancel.setAttribute("id", "m-e-c-f-btn-cancel-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  tsBtnCancel.setAttribute("class", "btn");
  tsBtnCancel.classList.add("btn-mid");
  tsBtnCancel.classList.add("btn-fth");
  tsBtnCancel.classList.add("m-e-c-f-btn");
  tsBtnCancel.classList.add("m-e-c-f-btn-margin");
  tsBtnCancel.setAttribute("onclick", "editorCalendarCancelTechnicalShutdownModal('show', '"+ fromd +"', '"+ fromm +"', '"+ fromy +"', '"+ tod +"', '"+ tom +"', '"+ toy +"', '"+ ctgr +"')");
  tsBtnCancel.innerHTML = wrd_cancelTechnicalShutdown;
  var tsBtnUpdate = document.createElement("button");
  tsBtnUpdate.setAttribute("type", "button");
  tsBtnUpdate.setAttribute("id", "m-e-c-f-btn-update-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
  tsBtnUpdate.setAttribute("class", "btn");
  tsBtnUpdate.classList.add("btn-mid");
  tsBtnUpdate.classList.add("btn-prim");
  tsBtnUpdate.classList.add("m-e-c-f-btn");
  tsBtnUpdate.classList.add("m-e-c-f-btn-margin");
  tsBtnUpdate.setAttribute("onclick", "editorCalendarUpdateTechnicalShutdownCheck('"+ fromd +"', '"+ fromm +"', '"+ fromy +"', '"+ tod +"', '"+ tom +"', '"+ toy +"')");
  tsBtnUpdate.innerHTML = wrd_updateChanges;
  // category
  tsBlckWrp.appendChild(tsCategoryWrp);
  tsCategoryWrp.appendChild(tsCategoryLayout);
  tsCategoryLayout.appendChild(tsCategoryTitle);
  tsCategoryLayout.appendChild(tsCategorySelect);
  tsCategorySelect.appendChild(tsCategorySelectOption1);
  tsCategorySelect.appendChild(tsCategorySelectOption2);
  tsCategorySelect.appendChild(tsCategorySelectOption3);
  tsCategorySelect.appendChild(tsCategorySelectOption4);
  // from
  tsBlckWrp.appendChild(tsFromWrp);
  tsFromWrp.appendChild(tsFromTitle);
  tsFromWrp.appendChild(tsFromInputWrp);
  tsFromInputWrp.appendChild(tsFromInput);
  tsFromInputWrp.appendChild(tsFromDropdownWrp);
  tsFromDropdownWrp.appendChild(tsFromDropdownBtn);
  tsFromDropdownBtn.appendChild(tsFromDropdownBtnTxtTime);
  tsFromDropdownBtn.appendChild(tsFromDropdownBtnTxtWhole);
  tsFromDropdownBtn.appendChild(tsFromDropdownBtnArrow);
  tsFromDropdownWrp.appendChild(tsFromDropdown);
  tsFromDropdown.appendChild(tsFromDropdownLabelTime);
  tsFromDropdownLabelTime.appendChild(tsFromDropdownInputTime);
  tsFromDropdownLabelTime.appendChild(tsFromDropdownSpanTime);
  tsFromDropdown.appendChild(tsFromDropdownLabelWhole);
  tsFromDropdownLabelWhole.appendChild(tsFromDropdownInputWhole);
  tsFromDropdownLabelWhole.appendChild(tsFromDropdownSpanWhole);
  // to
  tsBlckWrp.appendChild(tsToWrp);
  tsToWrp.appendChild(tsToTitle);
  tsToWrp.appendChild(tsToInputWrp);
  tsToInputWrp.appendChild(tsToInput);
  tsToInputWrp.appendChild(tsToDropdownWrp);
  tsToDropdownWrp.appendChild(tsToDropdownBtn);
  tsToDropdownBtn.appendChild(tsToDropdownBtnTxtTime);
  tsToDropdownBtn.appendChild(tsToDropdownBtnTxtWhole);
  tsToDropdownBtn.appendChild(tsToDropdownBtnArrow);
  tsToDropdownBtn.appendChild(tsToDropdownBtnArrow);
  tsToDropdownWrp.appendChild(tsToDropdown);
  tsToDropdown.appendChild(tsToDropdownLabelTime);
  tsToDropdownLabelTime.appendChild(tsToDropdownInputTime);
  tsToDropdownLabelTime.appendChild(tsToDropdownSpanTime);
  tsToDropdown.appendChild(tsToDropdownLabelWhole);
  tsToDropdownLabelWhole.appendChild(tsToDropdownInputWhole);
  tsToDropdownLabelWhole.appendChild(tsToDropdownSpanWhole);
  // notes
  tsBlckWrp.appendChild(tsNotesWrp);
  tsNotesWrp.appendChild(tsNotesTitle);
  tsNotesWrp.appendChild(tsNotesTextareaWrp);
  tsNotesTextareaWrp.appendChild(tsNotesTextarea);
  // btn wrp
  tsBlckWrp.appendChild(tsBtnWrp);
  tsBtnWrp.appendChild(tsBtnCancel);
  tsBtnWrp.appendChild(tsBtnUpdate);
  document.getElementById("editor-calendar-day-details-content").appendChild(tsBlckWrp);
  editCalendarRenderBookingDetailsBorder();
}

function editCalendarRenderBookingDetailsBorder() {
  var bBorderWrp = document.createElement("div");
  bBorderWrp.setAttribute("class", "m-e-c-f-border-wrp");
  var bBorder = document.createElement("div");
  bBorder.setAttribute("class", "m-e-c-f-border");
  bBorderWrp.appendChild(bBorder);
  document.getElementById("editor-calendar-day-details-content").appendChild(bBorderWrp);
}

function editCalendarRenderEmptyDate() {
  var emptyDateWrp = document.createElement("div");
  emptyDateWrp.setAttribute("id", "editor-calendar-empty-date-wrp");
  var eDTxtWrp = document.createElement("div");
  eDTxtWrp.setAttribute("id", "editor-calendar-empty-date-txt-wrp");
  var eDTxt = document.createElement("p");
  eDTxt.setAttribute("id", "editor-calendar-empty-date-txt");
  eDTxt.innerHTML = wrd_thereAreNoBookingsForThisDate;
  emptyDateWrp.appendChild(eDTxtWrp)
  eDTxtWrp.appendChild(eDTxt)
  document.getElementById("editor-calendar-day-details-content").appendChild(emptyDateWrp);
}

function editorCalendarCancelTechnicalShutdownModal(tsk, fromd, fromm, fromy, tod, tom, toy, ctgr) {
  if (fromd != "" && fromm != "" && fromy != "" && tod != "" && tom != "" && toy != "") {
    if (editDayDetailsReady) {
      if (tsk == "show") {
        document.getElementById("editor-calendar-technical-shutdown-change-modal-txt-cancel-term").innerHTML = fromd +"."+ fromm +"."+ fromy +" - "+ tod +"."+ tom +"."+ toy;
        if (ctgr == "cleaning") {
          document.getElementById("editor-calendar-technical-shutdown-change-modal-txt-cancel-ctgr").innerHTML = wrd_cleaning;
        } else if (ctgr == "maintenance") {
          document.getElementById("editor-calendar-technical-shutdown-change-modal-txt-cancel-ctgr").innerHTML = wrd_maintenance;
        } else if (ctgr == "reconstruction") {
          document.getElementById("editor-calendar-technical-shutdown-change-modal-txt-cancel-ctgr").innerHTML = wrd_reconstruction;
        } else {
          document.getElementById("editor-calendar-technical-shutdown-change-modal-txt-cancel-ctgr").innerHTML = wrd_other;
        }
        document.getElementById("editor-calendar-technical-shutdown-change-modal-btn-cancel").setAttribute("onclick", "editorCalendarCancelTechnicalShutdownRequest('"+ fromd +"', '"+ fromm +"', '"+ fromy +"', '"+ tod +"', '"+ tom +"', '"+ toy +"')");
        document.getElementById("editor-calendar-technical-shutdown-change-modal-btn-cancel").onclick = function() {editorCalendarCancelTechnicalShutdownRequest(fromd, fromm, fromy, tod, tom, toy);};
      }
      modCover(tsk, 'modal-cover-editor-calendar-cancel-technical-shutdown');
    }
  } else {
    modCover("hide", 'modal-cover-editor-calendar-cancel-technical-shutdown');
  }
}

var xhrTechnicalShutdownCancel;
function editorCalendarCancelTechnicalShutdownRequest(fromd, fromm, fromy, tod, tom, toy) {
  if (editDayDetailsReady) {
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    editorCalendarCancelTechnicalShutdownModal("hide", "", "", "", "", "", "", "");
    editDayDetailsReady = false;
    editorBtnHandler("load", "m-e-c-f-btn-cancel-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "";
    document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = "";
    xhrTechnicalShutdownCancel = new XMLHttpRequest();
    xhrTechnicalShutdownCancel.onreadystatechange = function() {
      if (xhrTechnicalShutdownCancel.readyState == 4 && xhrTechnicalShutdownCancel.status == 200) {
        window.onbeforeunload = null;
        editDayDetailsReady = true;
        if (testJSON(xhrTechnicalShutdownCancel.response)) {
          var json = JSON.parse(xhrTechnicalShutdownCancel.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                editorBtnHandler("success", "m-e-c-f-btn-cancel-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
                editorCalendarDayDetailsModal('hide');
                calendarRender('editor-content-calendar-blck', plc_id, 'host-view');
                editorCalendarDateDataHandler(null, null, null, null, null, null);
                editorCalendarManagerBtns();
              } else if (json[key]["type"] == "error") {
                editorBtnHandler("def", "m-e-c-f-btn-cancel-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
                document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "table";
                document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = json[key]["error"];
                document.getElementById("modal-editor-content-scroll-day-details").scrollTop = 0;
              }
            }
          }
        } else {
          editorBtnHandler("def", "m-e-c-f-btn-cancel-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
          document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "table";
          document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = xhrTechnicalShutdownCancel.response;
          document.getElementById("modal-editor-content-scroll-day-details").scrollTop = 0;
        }
      }
    }
    xhrTechnicalShutdownCancel.open("POST", "php-backend/cancel-technical-shutdown-manager.php");
    xhrTechnicalShutdownCancel.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrTechnicalShutdownCancel.send("plc_id="+ plc_id +"&f_d="+ fromd +"&f_m="+ fromm +"&f_y="+ fromy +"&t_d="+ tod +"&t_m="+ tom +"&t_y="+ toy);
  }
}

var xhrUpdateTechnicalShutdownCheck;
var uTSCategory, uTSNotes, uTSF_d, uTSF_m, uTSF_y, uTST_d, uTST_m, uTST_y, uTSFromAvailability, uTSToAvailability, editorUpdateTechnicalShutdownOutputTime, updateTechnicalShutdownPermissionNeeded, editorCalendarUpdateTechnicalShutdownWChangesErrorTxt, editorCalendarUpdateTechnicalShutdownWChangesDoneSts, editorCalendarUpdateTechnicalShutdownWChangesErrorSts;
function editorCalendarUpdateTechnicalShutdownCheck(f_d, f_m, f_y, t_d, t_m, t_y) {
  if (editDayDetailsReady) {
    uTSCategory = document.getElementById("m-e-c-f-select-category-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).value;
    uTSNotes = document.getElementById("m-e-c-f-textarea-notes-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).value;
    uTSF_d = new Date(document.getElementById("m-e-c-f-input-from-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).value).getDate();
    uTSF_m = new Date(document.getElementById("m-e-c-f-input-from-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).value).getMonth() +1;
    uTSF_y = new Date(document.getElementById("m-e-c-f-input-from-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).value).getFullYear();
    uTST_d = new Date(document.getElementById("m-e-c-f-input-to-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).value).getDate();
    uTST_m = new Date(document.getElementById("m-e-c-f-input-to-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).value).getMonth() +1;
    uTST_y = new Date(document.getElementById("m-e-c-f-input-to-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).value).getFullYear();
    if (document.getElementById("m-e-c-f-dropdown-radio-from-1-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).checked) {
      uTSFromAvailability = "half";
    } else {
      uTSFromAvailability = "whole";
    }
    if (document.getElementById("m-e-c-f-dropdown-radio-to-1-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).checked) {
      uTSToAvailability = "half";
    } else {
      uTSToAvailability = "whole";
    }
    if (Number.isInteger(uTSF_d) && Number.isInteger(uTSF_m) && Number.isInteger(uTSF_y) && Number.isInteger(uTST_d) && Number.isInteger(uTST_m) && Number.isInteger(uTST_y)) {
      window.onbeforeunload = function(event) {
        event.returnValue = "Your changes may not be saved.";
      };
      editDayDetailsReady = false;
      editorBtnHandler("load", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
      document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "";
      document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = "";
      document.getElementById("permission-needed-to-change-list").innerHTML = "";
      updateTechnicalShutdownPermissionNeeded = false;
      editorCalendarUpdateTechnicalShutdownWChangesErrorTxt = "";
      editorCalendarUpdateTechnicalShutdownWChangesDoneSts = false;
      editorCalendarUpdateTechnicalShutdownWChangesErrorSts = false;
      xhrUpdateTechnicalShutdownCheck = new XMLHttpRequest();
      xhrUpdateTechnicalShutdownCheck.onreadystatechange = function() {
        if (xhrUpdateTechnicalShutdownCheck.readyState == 4 && xhrUpdateTechnicalShutdownCheck.status == 200) {
          window.onbeforeunload = null;
          editDayDetailsReady = true;
          if (testJSON(xhrUpdateTechnicalShutdownCheck.response)) {
            var json = JSON.parse(xhrUpdateTechnicalShutdownCheck.response);
            for (var key in json) {
              if (json.hasOwnProperty(key)) {
                if (json[key]["type"] == "done") {
                  editorCalendarUpdateTechnicalShutdownWChangesDoneSts = true;
                } else if (json[key]["type"] == "permission-needed") {
                  if (json[key]["data"] == "booking") {
                    permissionNeededBookingRender(json[key]["task"], json[key]["name"], json[key]["email"], json[key]["phone"], json[key]["from"], json[key]["to"]);
                  } else {
                    permissionNeededTechnicalShutdownRender(json[key]["task"], json[key]["category"], json[key]["notes"], json[key]["from"], json[key]["to"]);
                  }
                  editorBtnHandler("def", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
                  updateTechnicalShutdownPermissionNeeded = true;
                } else if (json[key]["type"] == "error") {
                  editorCalendarUpdateTechnicalShutdownWChangesErrorSts = true;
                  editorCalendarUpdateTechnicalShutdownWChangesErrorTxt = editorCalendarUpdateTechnicalShutdownWChangesErrorTxt +""+ json[key]["error"] +"<br>";
                }
              }
            }
            if (updateTechnicalShutdownPermissionNeeded) {
              permissionNeededData("", f_d, f_m, f_y, t_d, t_m, t_y, "", "", "", "", uTSNotes, uTSF_d, uTSF_m, uTSF_y, uTST_d, uTST_m, uTST_y, uTSFromAvailability, uTSToAvailability, "", "", uTSCategory, "update-technical-shutdown");
              permissionNeededOnclick("");
              modCover("show", "modal-cover-permission-needed");
            }
            if (editorCalendarUpdateTechnicalShutdownWChangesErrorSts) {
              if (editorCalendarUpdateTechnicalShutdownWChangesDoneSts) {
                editorBtnHandler("def", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
                editorCalendarDayDetailsModal('hide');
                calendarRender('editor-content-calendar-blck', plc_id, 'host-view');
                editorCalendarDateDataHandler(null, null, null, null, null, null);
                editorCalendarManagerBtns();
                alert(editorCalendarUpdateTechnicalShutdownWChangesErrorTxt);
              } else {
                editorBtnHandler("def", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
                document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "table";
                document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = editorCalendarUpdateTechnicalShutdownWChangesErrorTxt;
                document.getElementById("modal-editor-content-scroll-day-details").scrollTop = 0;
              }
            }
            if (editorCalendarUpdateTechnicalShutdownWChangesDoneSts && !editorCalendarUpdateTechnicalShutdownWChangesErrorSts) {
              editorBtnHandler("success", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
              clearTimeout(editorUpdateTechnicalShutdownOutputTime);
              editorUpdateTechnicalShutdownOutputTime = setTimeout(function(){
                editorBtnHandler("def", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
                editorCalendarDayDetailsModal('hide');
                calendarRender('editor-content-calendar-blck', plc_id, 'host-view');
                editorCalendarDateDataHandler(null, null, null, null, null, null);
                editorCalendarManagerBtns();
              }, 750);
            }
          } else {
            document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "table";
            document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = xhrUpdateTechnicalShutdownCheck.response;
            document.getElementById("modal-editor-content-scroll-day-details").scrollTop = 0;
          }
        }
      }
      xhrUpdateTechnicalShutdownCheck.open("POST", "php-backend/update-technical-shutdown-manager.php");
      xhrUpdateTechnicalShutdownCheck.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhrUpdateTechnicalShutdownCheck.send("verificated_to_make_changes=no&plc_id="+ plc_id +"&category="+ uTSCategory +"&notes="+ uTSNotes +"&org_f_d="+ f_d +"&org_f_m="+ f_m +"&org_f_y="+ f_y +"&org_t_d="+ t_d +"&org_t_m="+ t_m +"&org_t_y="+ t_y +"&f_d="+ uTSF_d +"&f_m="+ uTSF_m +"&f_y="+ uTSF_y +"&fromAvailability="+ uTSFromAvailability +"&t_d="+ uTST_d +"&t_m="+ uTST_m +"&t_y="+ uTST_y +"&toAvailability="+ uTSToAvailability);
    } else {
      document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "table";
      document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = "You have to select starting and ending day of the technical shutdown";
      document.getElementById("modal-editor-content-scroll-day-details").scrollTop = 0;
    }
  }
}

var xhrUpdateTechnicalShutdown;
function editorCalendarUpdateTechnicalShutdownWChanges(f_d, f_m, f_y, t_d, t_m, t_y) {
  if (editDayDetailsReady) {
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    uTSCategory = document.getElementById("permission-needed-to-change-accept-category").textContent;
    uTSNotes = document.getElementById("permission-needed-to-change-accept-notes").textContent;
    uTSF_d = document.getElementById("permission-needed-to-change-accept-f-d").textContent;
    uTSF_m = document.getElementById("permission-needed-to-change-accept-f-m").textContent;
    uTSF_y = document.getElementById("permission-needed-to-change-accept-f-y").textContent;
    uTST_d = document.getElementById("permission-needed-to-change-accept-t-d").textContent;
    uTST_m = document.getElementById("permission-needed-to-change-accept-t-m").textContent;
    uTST_y = document.getElementById("permission-needed-to-change-accept-t-y").textContent;
    uTSFromAvailability = document.getElementById("permission-needed-to-change-accept-firstday").textContent;
    uTSToAvailabilityy = document.getElementById("permission-needed-to-change-accept-lastday").textContent;
    modCover("hide", "modal-cover-permission-needed");
    editDayDetailsReady = false;
    editorBtnHandler("load", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
    document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "";
    document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = "";
    editorCalendarUpdateTechnicalShutdownWChangesErrorTxt = "";
    editorCalendarUpdateTechnicalShutdownWChangesDoneSts = false;
    editorCalendarUpdateTechnicalShutdownWChangesErrorSts = false;
    xhrUpdateTechnicalShutdown = new XMLHttpRequest();
    xhrUpdateTechnicalShutdown.onreadystatechange = function() {
      if (xhrUpdateTechnicalShutdown.readyState == 4 && xhrUpdateTechnicalShutdown.status == 200) {
        window.onbeforeunload = null;
        editDayDetailsReady = true;
        if (testJSON(xhrUpdateTechnicalShutdown.response)) {
          var json = JSON.parse(xhrUpdateTechnicalShutdown.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                editorCalendarUpdateTechnicalShutdownWChangesDoneSts = true;
              } else if (json[key]["type"] == "error") {
                editorCalendarUpdateTechnicalShutdownWChangesErrorSts = true;
                editorCalendarUpdateTechnicalShutdownWChangesErrorTxt = editorCalendarUpdateTechnicalShutdownWChangesErrorTxt +""+ json[key]["error"] +"<br>";
              }
            }
          }
          if (editorCalendarUpdateTechnicalShutdownWChangesErrorSts) {
            if (editorCalendarUpdateTechnicalShutdownWChangesDoneSts) {
              editorBtnHandler("def", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
              editorCalendarDayDetailsModal('hide');
              calendarRender('editor-content-calendar-blck', plc_id, 'host-view');
              editorCalendarDateDataHandler(null, null, null, null, null, null);
              editorCalendarManagerBtns();
              alert(editorCalendarUpdateTechnicalShutdownWChangesErrorTxt);
            } else {
              editorBtnHandler("def", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
              document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "table";
              document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = editorCalendarUpdateTechnicalShutdownWChangesErrorTxt;
              document.getElementById("modal-editor-content-scroll-day-details").scrollTop = 0;
            }
          }
          if (editorCalendarUpdateTechnicalShutdownWChangesDoneSts && !editorCalendarUpdateTechnicalShutdownWChangesErrorSts) {
            editorBtnHandler("success", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
            clearTimeout(editorUpdateTechnicalShutdownOutputTime);
            editorUpdateTechnicalShutdownOutputTime = setTimeout(function(){
              editorBtnHandler("def", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
              editorCalendarDayDetailsModal('hide');
              calendarRender('editor-content-calendar-blck', plc_id, 'host-view');
              editorCalendarDateDataHandler(null, null, null, null, null, null);
              editorCalendarManagerBtns();
            }, 750);
          }
        } else {
          editorBtnHandler("def", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
          document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "table";
          document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = xhrUpdateTechnicalShutdown.response;
          document.getElementById("modal-editor-content-scroll-day-details").scrollTop = 0;
        }
      }
    }
    xhrUpdateTechnicalShutdown.open("POST", "php-backend/update-technical-shutdown-manager.php");
    xhrUpdateTechnicalShutdown.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrUpdateTechnicalShutdown.send("verificated_to_make_changes=yes&plc_id="+ plc_id +"&category="+ uTSCategory +"&notes="+ uTSNotes +"&org_f_d="+ f_d +"&org_f_m="+ f_m +"&org_f_y="+ f_y +"&org_t_d="+ t_d +"&org_t_m="+ t_m +"&org_t_y="+ t_y +"&f_d="+ uTSF_d +"&f_m="+ uTSF_m +"&f_y="+ uTSF_y +"&fromAvailability="+ uTSFromAvailability +"&t_d="+ uTST_d +"&t_m="+ uTST_m +"&t_y="+ uTST_y +"&toAvailability="+ uTSToAvailability);
  }
}

function editorCalendarCancelBookingModal(tsk, fromd, fromm, fromy, tod, tom, toy) {
  if (editDayDetailsReady) {
    document.getElementById("editor-calendar-cancel-booking-about-txt-from-d").innerHTML = fromd;
    document.getElementById("editor-calendar-cancel-booking-about-txt-from-m").innerHTML = fromm;
    document.getElementById("editor-calendar-cancel-booking-about-txt-from-y").innerHTML = fromy;
    document.getElementById("editor-calendar-cancel-booking-about-txt-to-d").innerHTML = tod;
    document.getElementById("editor-calendar-cancel-booking-about-txt-to-m").innerHTML = tom;
    document.getElementById("editor-calendar-cancel-booking-about-txt-to-y").innerHTML = toy;
    modCover(tsk, 'modal-cover-editor-calendar-cancel-booking');
  }
}

var xhrBookingCancel, editorCancelBookingOutputTime;
function editorCalendarCancelBookingRequest(fromd, fromm, fromy, tod, tom, toy) {
  if (editDayDetailsReady) {
    fromd = document.getElementById("editor-calendar-cancel-booking-about-txt-from-d").innerHTML;
    fromm = document.getElementById("editor-calendar-cancel-booking-about-txt-from-m").innerHTML;
    fromy = document.getElementById("editor-calendar-cancel-booking-about-txt-from-y").innerHTML;
    tod = document.getElementById("editor-calendar-cancel-booking-about-txt-to-d").innerHTML;
    tom = document.getElementById("editor-calendar-cancel-booking-about-txt-to-m").innerHTML;
    toy = document.getElementById("editor-calendar-cancel-booking-about-txt-to-y").innerHTML;
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    editorCalendarCancelBookingModal("hide", "", "", "", "", "", "");
    editDayDetailsReady = false;
    editorBtnHandler("load", "m-e-c-f-btn-cancel-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
    document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "";
    document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = "";
    xhrBookingCancel = new XMLHttpRequest();
    xhrBookingCancel.onreadystatechange = function() {
      if (xhrBookingCancel.readyState == 4 && xhrBookingCancel.status == 200) {
        window.onbeforeunload = null;
        editDayDetailsReady = true;
        if (testJSON(xhrBookingCancel.response)) {
          var json = JSON.parse(xhrBookingCancel.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                editorBtnHandler("success", "m-e-c-f-btn-cancel-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
                clearTimeout(editorCancelBookingOutputTime);
                editorCancelBookingOutputTime = setTimeout(function(){
                  editorBtnHandler("def", "m-e-c-f-btn-cancel-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
                  editorCalendarDayDetailsModal('hide');
                  calendarRender('editor-content-calendar-blck', plc_id, 'host-view');
                  editorCalendarDateDataHandler(null, null, null, null, null, null);
                  editorCalendarManagerBtns();
                }, 750);
              } else if (json[key]["type"] == "error") {
                editorBtnHandler("def", "m-e-c-f-btn-cancel-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
                document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "table";
                document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = json[key]["error"];
                document.getElementById("modal-editor-content-scroll-day-details").scrollTop = 0;
              }
            }
          }
        } else {
          editorBtnHandler("def", "m-e-c-f-btn-cancel-"+ fromd +"-"+ fromm +"-"+ fromy +"-"+ tod +"-"+ tom +"-"+ toy);
          document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "table";
          document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = xhrBookingCancel.response;
          document.getElementById("modal-editor-content-scroll-day-details").scrollTop = 0;
        }
      }
    }
    xhrBookingCancel.open("POST", "php-backend/cancel-booking-manager.php");
    xhrBookingCancel.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrBookingCancel.send("plc_id="+ plc_id +"&f_d="+ fromd +"&f_m="+ fromm +"&f_y="+ fromy +"&t_d="+ tod +"&t_m="+ tom +"&t_y="+ toy);
  }
}

var xhrUpdateBookingCheck;
var uBName, uBEmail, uBPhone, uBGuest, uBNotes, uBF_d, uBF_m, uBF_y, uBT_d, uBT_m, uBT_y, uFromAvailability, uToAvailability, editorUpdateBookingOutputTime, updateBookingPermissionNeeded, editorCalendarUpdateBookingWChangesErrorTxt, editorCalendarUpdateBookingWChangesDoneSts, editorCalendarUpdateBookingWChangesErrorSts;
function editorCalendarUpdateBookingCheck(f_d, f_m, f_y, t_d, t_m, t_y) {
  if (editDayDetailsReady) {
    uBName = document.getElementById("m-e-c-f-input-name-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).value;
    uBEmail = document.getElementById("m-e-c-f-input-email-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).value;
    uBPhone = document.getElementById("m-e-c-f-input-phone-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).value.replace(/\+/g, "plus");
    uBGuest = document.getElementById("m-e-c-f-input-guest-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).value;
    uBNotes = document.getElementById("m-e-c-f-textarea-notes-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).value;
    uBF_d = new Date(document.getElementById("m-e-c-f-input-from-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).value).getDate();
    uBF_m = new Date(document.getElementById("m-e-c-f-input-from-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).value).getMonth() +1;
    uBF_y = new Date(document.getElementById("m-e-c-f-input-from-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).value).getFullYear();
    uBT_d = new Date(document.getElementById("m-e-c-f-input-to-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).value).getDate();
    uBT_m = new Date(document.getElementById("m-e-c-f-input-to-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).value).getMonth() +1;
    uBT_y = new Date(document.getElementById("m-e-c-f-input-to-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).value).getFullYear();
    if (document.getElementById("m-e-c-f-dropdown-radio-from-1-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).checked) {
      uFromAvailability = "half";
    } else {
      uFromAvailability = "whole";
    }
    if (document.getElementById("m-e-c-f-dropdown-radio-to-1-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).checked) {
      uToAvailability = "half";
    } else {
      uToAvailability = "whole";
    }
    if (document.getElementById("m-e-c-f-checkbox-label-deposit-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).checked) {
      uDepositSts = 1;
    } else {
      uDepositSts = 0;
    }
    if (document.getElementById("m-e-c-f-checkbox-label-full-amount-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y).checked) {
      uFullAmountSts = 1;
    } else {
      uFullAmountSts = 0;
    }
    if (Number.isInteger(uBF_d) && Number.isInteger(uBF_m) && Number.isInteger(uBF_y) && Number.isInteger(uBT_d) && Number.isInteger(uBT_m) && Number.isInteger(uBT_y)) {
      window.onbeforeunload = function(event) {
        event.returnValue = "Your changes may not be saved.";
      };
      editDayDetailsReady = false;
      editorBtnHandler("load", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
      document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "";
      document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = "";
      document.getElementById("permission-needed-to-change-list").innerHTML = "";
      updateBookingPermissionNeeded = false;
      editorCalendarUpdateBookingWChangesErrorTxt = "";
      editorCalendarUpdateBookingWChangesDoneSts = false;
      editorCalendarUpdateBookingWChangesErrorSts = false;
      xhrUpdateBookingCheck = new XMLHttpRequest();
      xhrUpdateBookingCheck.onreadystatechange = function() {
        if (xhrUpdateBookingCheck.readyState == 4 && xhrUpdateBookingCheck.status == 200) {
          window.onbeforeunload = null;
          editDayDetailsReady = true;
          if (testJSON(xhrUpdateBookingCheck.response)) {
            var json = JSON.parse(xhrUpdateBookingCheck.response);
            for (var key in json) {
              if (json.hasOwnProperty(key)) {
                if (json[key]["type"] == "done") {
                  editorCalendarUpdateBookingWChangesDoneSts = true;
                } else if (json[key]["type"] == "permission-needed") {
                  if (json[key]["data"] == "booking") {
                    permissionNeededBookingRender(json[key]["task"], json[key]["name"], json[key]["email"], json[key]["phone"], json[key]["from"], json[key]["to"]);
                  } else {
                    permissionNeededTechnicalShutdownRender(json[key]["task"], json[key]["category"], json[key]["notes"], json[key]["from"], json[key]["to"]);
                  }
                  editorBtnHandler("def", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
                  updateBookingPermissionNeeded = true;
                } else if (json[key]["type"] == "error") {
                  editorCalendarUpdateBookingWChangesErrorSts = true;
                  editorCalendarUpdateBookingWChangesErrorTxt = editorCalendarUpdateBookingWChangesErrorTxt +""+ json[key]["error"] +"<br>";
                }
              }
            }
            if (updateBookingPermissionNeeded) {
              permissionNeededData("", f_d, f_m, f_y, t_d, t_m, t_y, uBName, uBEmail, uBPhone, uBGuest, uBNotes, uBF_d, uBF_m, uBF_y, uBT_d, uBT_m, uBT_y, uFromAvailability, uToAvailability, uDepositSts, uFullAmountSts, "", "update-booking");
              permissionNeededOnclick("");
              modCover("show", "modal-cover-permission-needed");
            }
            if (editorCalendarUpdateBookingWChangesErrorSts) {
              if (editorCalendarUpdateBookingWChangesDoneSts) {
                editorBtnHandler("def", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
                editorCalendarDayDetailsModal('hide');
                calendarRender('editor-content-calendar-blck', plc_id, 'host-view');
                editorCalendarDateDataHandler(null, null, null, null, null, null);
                editorCalendarManagerBtns();
                alert(editorCalendarUpdateBookingWChangesErrorTxt);
              } else {
                editorBtnHandler("def", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
                document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "table";
                document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = editorCalendarUpdateBookingWChangesErrorTxt;
                document.getElementById("modal-editor-content-scroll-day-details").scrollTop = 0;
              }
            }
            if (editorCalendarUpdateBookingWChangesDoneSts && !editorCalendarUpdateBookingWChangesErrorSts) {
              editorBtnHandler("success", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
              clearTimeout(editorUpdateBookingOutputTime);
              editorUpdateBookingOutputTime = setTimeout(function(){
                editorBtnHandler("def", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
                editorCalendarDayDetailsModal('hide');
                calendarRender('editor-content-calendar-blck', plc_id, 'host-view');
                editorCalendarDateDataHandler(null, null, null, null, null, null);
                editorCalendarManagerBtns();
              }, 750);
            }
          } else {
            editorBtnHandler("def", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
            document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "table";
            document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = xhrUpdateBookingCheck.response;
            document.getElementById("modal-editor-content-scroll-day-details").scrollTop = 0;
          }
        }
      }
      xhrUpdateBookingCheck.open("POST", "php-backend/update-booking-manager.php");
      xhrUpdateBookingCheck.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhrUpdateBookingCheck.send("verificated_to_make_changes=no&plc_id="+ plc_id +"&name="+ uBName +"&email="+ uBEmail +"&phone="+ uBPhone +"&guest="+ uBGuest +"&notes="+ uBNotes +"&org_f_d="+ f_d +"&org_f_m="+ f_m +"&org_f_y="+ f_y +"&org_t_d="+ t_d +"&org_t_m="+ t_m +"&org_t_y="+ t_y +"&f_d="+ uBF_d +"&f_m="+ uBF_m +"&f_y="+ uBF_y +"&fromAvailability="+ uFromAvailability +"&t_d="+ uBT_d +"&t_m="+ uBT_m +"&t_y="+ uBT_y +"&toAvailability="+ uToAvailability +"&deposit="+ uDepositSts +"&fullAmount="+ uFullAmountSts);
    } else {
      document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "table";
      document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = "You have to select starting and ending day of the booking";
      document.getElementById("modal-editor-content-scroll-day-details").scrollTop = 0;
    }
  }
}

var xhrUpdateBooking;
function editorCalendarUpdateBookingWChanges(f_d, f_m, f_y, t_d, t_m, t_y) {
  if (editDayDetailsReady) {
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
    modCover("hide", "modal-cover-permission-needed");
    editDayDetailsReady = false;
    editorBtnHandler("load", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
    document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "";
    document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = "";
    editorCalendarUpdateBookingWChangesErrorTxt = "";
    editorCalendarUpdateBookingWChangesDoneSts = false;
    editorCalendarUpdateBookingWChangesErrorSts = false;
    xhrUpdateBooking = new XMLHttpRequest();
    xhrUpdateBooking.onreadystatechange = function() {
      if (xhrUpdateBooking.readyState == 4 && xhrUpdateBooking.status == 200) {
        window.onbeforeunload = null;
        editDayDetailsReady = true;
        if (testJSON(xhrUpdateBooking.response)) {
          var json = JSON.parse(xhrUpdateBooking.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                editorCalendarUpdateBookingWChangesDoneSts = true;
              } else if (json[key]["type"] == "error") {
                editorCalendarUpdateBookingWChangesErrorSts = true;
                editorCalendarUpdateBookingWChangesErrorTxt = editorCalendarUpdateBookingWChangesErrorTxt +""+ json[key]["error"] +"<br>";
              }
            }
          }
          if (editorCalendarUpdateBookingWChangesErrorSts) {
            if (editorCalendarUpdateBookingWChangesDoneSts) {
              editorBtnHandler("def", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
              editorCalendarDayDetailsModal('hide');
              calendarRender('editor-content-calendar-blck', plc_id, 'host-view');
              editorCalendarDateDataHandler(null, null, null, null, null, null);
              editorCalendarManagerBtns();
              alert(editorCalendarUpdateBookingWChangesErrorTxt);
            } else {
              editorBtnHandler("def", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
              document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "table";
              document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = editorCalendarUpdateBookingWChangesErrorTxt;
              document.getElementById("modal-editor-content-scroll-day-details").scrollTop = 0;
            }
          }
          if (editorCalendarUpdateBookingWChangesDoneSts && !editorCalendarUpdateBookingWChangesErrorSts) {
            editorBtnHandler("success", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
            clearTimeout(editorUpdateBookingOutputTime);
            editorUpdateBookingOutputTime = setTimeout(function(){
              editorBtnHandler("def", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
              editorCalendarDayDetailsModal('hide');
              calendarRender('editor-content-calendar-blck', plc_id, 'host-view');
              editorCalendarDateDataHandler(null, null, null, null, null, null);
              editorCalendarManagerBtns();
            }, 750);
          }
        } else {
          editorBtnHandler("def", "m-e-c-f-btn-update-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
          document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "table";
          document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = xhrUpdateBooking.response;
          document.getElementById("modal-editor-content-scroll-day-details").scrollTop = 0;
        }
      }
    }
    xhrUpdateBooking.open("POST", "php-backend/update-booking-manager.php");
    xhrUpdateBooking.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrUpdateBooking.send("verificated_to_make_changes=yes&plc_id="+ plc_id +"&name="+ uBName +"&email="+ uBEmail +"&phone="+ uBPhone +"&guest="+ uBGuest +"&notes="+ uBNotes +"&org_f_d="+ f_d +"&org_f_m="+ f_m +"&org_f_y="+ f_y +"&org_t_d="+ t_d +"&org_t_m="+ t_m +"&org_t_y="+ t_y +"&f_d="+ uBF_d +"&f_m="+ uBF_m +"&f_y="+ uBF_y +"&fromAvailability="+ uFromAvailability +"&t_d="+ uBT_d +"&t_m="+ uBT_m +"&t_y="+ uBT_y +"&toAvailability="+ uToAvailability +"&deposit="+ uDepositSts +"&fullAmount="+ uFullAmountSts);
  }
}

var xhrRejectBooking, editorRejectBookingOutputTime;
function editorCalendarRejectBooking(f_d, f_m, f_y, t_d, t_m, t_y) {
  if (editDayDetailsReady) {
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    editDayDetailsReady = false;
    editorBtnHandler("load", "m-e-c-f-btn-reject-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
    document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "";
    document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = "";
    xhrRejectBooking = new XMLHttpRequest();
    xhrRejectBooking.onreadystatechange = function() {
      if (xhrRejectBooking.readyState == 4 && xhrRejectBooking.status == 200) {
        window.onbeforeunload = null;
        editDayDetailsReady = true;
        if (testJSON(xhrRejectBooking.response)) {
          var json = JSON.parse(xhrRejectBooking.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                editorBtnHandler("success", "m-e-c-f-btn-reject-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
                clearTimeout(editorRejectBookingOutputTime);
                editorRejectBookingOutputTime = setTimeout(function(){
                  editorBtnHandler("def", "m-e-c-f-btn-reject-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
                  editorCalendarDayDetailsModal('hide');
                  calendarRender('editor-content-calendar-blck', plc_id, 'host-view');
                  editorCalendarDateDataHandler(null, null, null, null, null, null);
                  editorCalendarManagerBtns();
                }, 750);
              } else if (json[key]["type"] == "error") {
                editorBtnHandler("def", "m-e-c-f-btn-reject-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
                document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "table";
                document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = json[key]["error"];
                document.getElementById("modal-editor-content-scroll-day-details").scrollTop = 0;
              }
            }
          }
        } else {
          editorBtnHandler("def", "m-e-c-f-btn-reject-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
          document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "table";
          document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = xhrRejectBooking.response;
          document.getElementById("modal-editor-content-scroll-day-details").scrollTop = 0;
        }
      }
    }
    xhrRejectBooking.open("POST", "../bookings/php-backend/reject-booking-manager.php");
    xhrRejectBooking.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrRejectBooking.send("plc_id="+ plc_id +"&f_d="+ f_d +"&f_m="+ f_m +"&f_y="+ f_y +"&t_d="+ t_d +"&t_m="+ t_m +"&t_y="+ t_y);
  }
}

var xhrConfirmBooking, editorConfirmBookingOutputTime;
function editorCalendarConfirmBooking(f_d, f_m, f_y, t_d, t_m, t_y) {
  if (editDayDetailsReady) {
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    editDayDetailsReady = false;
    editorBtnHandler("load", "m-e-c-f-btn-confirm-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
    document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "";
    document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = "";
    xhrConfirmBooking = new XMLHttpRequest();
    xhrConfirmBooking.onreadystatechange = function() {
      if (xhrConfirmBooking.readyState == 4 && xhrConfirmBooking.status == 200) {
        window.onbeforeunload = null;
        editDayDetailsReady = true;
        if (testJSON(xhrConfirmBooking.response)) {
          var json = JSON.parse(xhrConfirmBooking.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                editorBtnHandler("success", "m-e-c-f-btn-confirm-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
                clearTimeout(editorConfirmBookingOutputTime);
                editorConfirmBookingOutputTime = setTimeout(function(){
                  editorBtnHandler("def", "m-e-c-f-btn-confirm-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
                  editorCalendarDayDetailsModal('hide');
                  calendarRender('editor-content-calendar-blck', plc_id, 'host-view');
                  editorCalendarDateDataHandler(null, null, null, null, null, null);
                  editorCalendarManagerBtns();
                }, 750);
              } else if (json[key]["type"] == "error") {
                editorBtnHandler("def", "m-e-c-f-btn-confirm-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
                document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "table";
                document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = json[key]["error"];
                document.getElementById("modal-editor-content-scroll-day-details").scrollTop = 0;
              }
            }
          }
        } else {
          editorBtnHandler("def", "m-e-c-f-btn-confirm-"+ f_d +"-"+ f_m +"-"+ f_y +"-"+ t_d +"-"+ t_m +"-"+ t_y);
          document.getElementById("m-e-c-f-error-wrp-day-details").style.display = "table";
          document.getElementById("m-e-c-f-error-txt-day-details").innerHTML = xhrConfirmBooking.response;
          document.getElementById("modal-editor-content-scroll-day-details").scrollTop = 0;
        }
      }
    }
    xhrConfirmBooking.open("POST", "../bookings/php-backend/confirm-booking-manager.php");
    xhrConfirmBooking.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrConfirmBooking.send("plc_id="+ plc_id +"&f_d="+ f_d +"&f_m="+ f_m +"&f_y="+ f_y +"&t_d="+ t_d +"&t_m="+ t_m +"&t_y="+ t_y);
  }
}
