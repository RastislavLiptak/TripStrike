var savePasswordChangeSettingsReady = true;
var pCOldPass, pCNewPass, pCNewPassVerification, savePasswordChangeTimer;
function savePasswordChangeSettings() {
  if (savePasswordChangeSettingsReady) {
    clearTimeout(savePasswordChangeTimer);
    savePasswordChangeSettingsReady = false;
    pCOldPass = document.getElementById("settings-input-old-password").value;
    pCNewPass = document.getElementById("settings-input-new-password").value;
    pCNewPassVerification = document.getElementById("settings-input-new-password-verification").value;
    document.getElementById("settings-error-txt-password-change").innerHTML = "";
    setSaveBtn("load", "settings-save-btn-password-change");
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        document.getElementById("settings-input-old-password").value = "";
        document.getElementById("settings-input-new-password").value = "";
        document.getElementById("settings-input-new-password-verification").value = "";
        savePasswordChangeSettingsReady = true;
        if (testJSON(xhr.response)) {
          var json = JSON.parse(xhr.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                setSaveBtn("success", "settings-save-btn-password-change");
                savePasswordChangeTimer = setTimeout(function(){
                  setSaveBtn("def", "settings-save-btn-password-change");
                }, 1000);
              } else {
                setSaveBtn("def", "settings-save-btn-password-change");
                document.getElementById("settings-content-scroll").scrollTop = 0;
                document.getElementById("settings-error-txt-password-change").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          setSaveBtn("def", "settings-save-btn-password-change");
          document.getElementById("settings-content-scroll").scrollTop = 0;
          document.getElementById("settings-error-txt-password-change").innerHTML = xhr.response;
        }
      }
    }
    xhr.open("POST", "../uni/code/php-backend/settings/password-change.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("oldPass="+ pCOldPass +"&newPass="+ pCNewPass +"&newPassVerification="+ pCNewPassVerification);
  }
}
