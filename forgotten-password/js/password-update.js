var forgottenPasswordPasswordUpdateContinueReady = true;
var xhrFPPasswordUpdate, userID, code, pass, passVerify;
function forgottenPasswordSave() {
  if (forgottenPasswordPasswordUpdateContinueReady) {
    forgottenPasswordPasswordUpdateContinueReady = false;
    document.getElementById("set-up-step-by-step-content-error-txt-password-update").innerHTML = "";
    userID = document.getElementById("forgotten-password-password-update-user-id").innerHTML;
    code = document.getElementById("forgotten-password-password-update-code").innerHTML;
    pass = document.getElementById("forgotten-password-new-pass").value;
    passVerify = document.getElementById("forgotten-password-new-pass-verify").value;
  forgottenPasswordBtnHandler("load", "set-up-step-by-step-content-footer-btn-password-update-continue");
    xhrFPPasswordUpdate = new XMLHttpRequest();
    xhrFPPasswordUpdate.onreadystatechange = function() {
      if (xhrFPPasswordUpdate.readyState == 4 && xhrFPPasswordUpdate.status == 200) {
        forgottenPasswordPasswordUpdateContinueReady = true;
        forgottenPasswordBtnHandler("def", "set-up-step-by-step-content-footer-btn-password-update-continue");
        if (testJSON(xhrFPPasswordUpdate.response)) {
          var json = JSON.parse(xhrFPPasswordUpdate.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                forgottenPasswordContentSwitcher("success");
              } else {
                document.getElementById("set-up-step-by-step-content-error-txt-password-update").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          document.getElementById("set-up-step-by-step-content-error-txt-password-update").innerHTML = xhrFPPasswordUpdate.response;
        }
      }
    }
    xhrFPPasswordUpdate.open("POST", "php-backend/password-update.php");
    xhrFPPasswordUpdate.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrFPPasswordUpdate.send("userID="+ userID +"&code="+ code +"&pass="+ pass +"&passVerify="+ passVerify);
  }
}
