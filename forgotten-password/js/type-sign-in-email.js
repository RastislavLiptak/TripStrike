var forgottenPasswordTypeSignInEmailContinueReady = true;
var xhrFPTypeSignInEmailCheck, signInData;
function forgottenPasswordTypeSignInEmailContinue() {
  if (forgottenPasswordTypeSignInEmailContinueReady) {
    forgottenPasswordTypeSignInEmailContinueReady = false;
    document.getElementById("set-up-step-by-step-content-error-txt-type-sign-in-email").innerHTML = "";
    signInData = document.getElementById("set-up-step-by-step-content-input-type-sign-in-email").value;
    forgottenPasswordBtnHandler("load", "set-up-step-by-step-content-footer-btn-type-sign-in-email-continue");
    xhrFPTypeSignInEmailCheck = new XMLHttpRequest();
    xhrFPTypeSignInEmailCheck.onreadystatechange = function() {
      if (xhrFPTypeSignInEmailCheck.readyState == 4 && xhrFPTypeSignInEmailCheck.status == 200) {
        forgottenPasswordTypeSignInEmailContinueReady = true;
        forgottenPasswordBtnHandler("def", "set-up-step-by-step-content-footer-btn-type-sign-in-email-continue");
        if (testJSON(xhrFPTypeSignInEmailCheck.response)) {
          var json = JSON.parse(xhrFPTypeSignInEmailCheck.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                document.getElementById("set-up-step-by-step-content-title-h2-code-verification-email").innerHTML = json[key]["email"];
                forgottenPasswordCountdownStart(json[key]["date"]);
                document.getElementById("forgotten-password-code-verification-user-id").innerHTML = json[key]["id"];
                forgottenPasswordContentSwitcher("code-verification");
              } else {
                document.getElementById("set-up-step-by-step-content-error-txt-type-sign-in-email").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          document.getElementById("set-up-step-by-step-content-error-txt-type-sign-in-email").innerHTML = xhrFPTypeSignInEmailCheck.response;
        }
      }
    }
    xhrFPTypeSignInEmailCheck.open("POST", "php-backend/find-user-by-sign-in-data.php");
    xhrFPTypeSignInEmailCheck.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrFPTypeSignInEmailCheck.send("data="+ signInData);
  }
}
