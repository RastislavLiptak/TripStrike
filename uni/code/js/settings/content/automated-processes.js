var saveAutomatedProcessesSettingsReady = true;
var saveAutomatedProcessesTimer, autProcPayOrYourBookingWillBeCanceled, autProcCancelUnpaidBookings, autProcPayFullAmountAlert, autProcUnpaidFullAmountCall;
function saveAutomatedProcessesSettings() {
  if (saveAutomatedProcessesSettingsReady) {
    clearTimeout(saveAutomatedProcessesTimer);
    saveAutomatedProcessesSettingsReady = false;
    autProcPayOrYourBookingWillBeCanceled = document.getElementById("settings-checkbox-input-pay-or-your-booking-will-be-canceled").checked;
    autProcCancelUnpaidBookings = document.getElementById("settings-checkbox-input-cancel-unpaid-bookings").checked;
    autProcPayFullAmountAlert = document.getElementById("settings-checkbox-input-pay-full-amount-alert").checked;
    autProcUnpaidFullAmountCall = document.getElementById("settings-checkbox-input-unpaid-full-amount-call").checked;
    document.getElementById("settings-error-txt-automated-processes").innerHTML = "";
    setSaveBtn("load", "settings-save-btn-automated-processes");
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        saveAutomatedProcessesSettingsReady = true;
        if (testJSON(xhr.response)) {
          var json = JSON.parse(xhr.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                setSaveBtn("success", "settings-save-btn-automated-processes");
                saveAutomatedProcessesTimer = setTimeout(function(){
                  setSaveBtn("def", "settings-save-btn-automated-processes");
                }, 1000);
              } else {
                setSaveBtn("def", "settings-save-btn-automated-processes");
                document.getElementById("settings-content-scroll").scrollTop = 0;
                document.getElementById("settings-error-txt-automated-processes").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          setSaveBtn("def", "settings-save-btn-automated-processes");
          document.getElementById("settings-content-scroll").scrollTop = 0;
          document.getElementById("settings-error-txt-automated-processes").innerHTML = xhr.response;
        }
      }
    }
    xhr.open("POST", "../uni/code/php-backend/settings/automated-processes.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("payOrYourBookingWillBeCanceled="+ autProcPayOrYourBookingWillBeCanceled +"&cancelUnpaidBookings="+ autProcCancelUnpaidBookings +"&payFullAmountAlert="+ autProcPayFullAmountAlert +"&unpaidFullAmountCall="+ autProcUnpaidFullAmountCall);
  }
}
