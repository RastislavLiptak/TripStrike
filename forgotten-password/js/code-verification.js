var forgottenPasswordCodeVerificationReady = true;
var userID, code, xhrFPCodeVerificationContinue;
function forgottenPasswordCodeVerificationContinue() {
  if (forgottenPasswordCodeVerificationReady) {
    forgottenPasswordCodeVerificationReady = false;
    document.getElementById("set-up-step-by-step-content-error-txt-code-verification").innerHTML = "";
    userID = document.getElementById("forgotten-password-code-verification-user-id").innerHTML;
    code = document.getElementById("set-up-step-by-step-content-input-forgotten-password-code").value;
    forgottenPasswordBtnHandler("load", "set-up-step-by-step-content-footer-btn-code-verification-continue");
    xhrFPCodeVerificationContinue = new XMLHttpRequest();
    xhrFPCodeVerificationContinue.onreadystatechange = function() {
      if (xhrFPCodeVerificationContinue.readyState == 4 && xhrFPCodeVerificationContinue.status == 200) {
        forgottenPasswordCodeVerificationReady = true;
        forgottenPasswordBtnHandler("def", "set-up-step-by-step-content-footer-btn-code-verification-continue");
        if (testJSON(xhrFPCodeVerificationContinue.response)) {
          var json = JSON.parse(xhrFPCodeVerificationContinue.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                document.getElementById("forgotten-password-password-update-user-id").innerHTML = userID;
                document.getElementById("forgotten-password-password-update-code").innerHTML = code;
                forgottenPasswordContentSwitcher("password-update");
              } else {
                document.getElementById("set-up-step-by-step-content-error-txt-code-verification").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          document.getElementById("set-up-step-by-step-content-error-txt-code-verification").innerHTML = xhrFPCodeVerificationContinue.response;
        }
      }
    }
    xhrFPCodeVerificationContinue.open("POST", "php-backend/check-verification-code.php");
    xhrFPCodeVerificationContinue.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrFPCodeVerificationContinue.send("userID="+ userID +"&code="+ code);
  }
}

var xhrFPCodeVerificationSendAgain;
function forgottenPasswordCodeVerificationSendAgain() {
  if (forgottenPasswordCodeVerificationReady) {
    forgottenPasswordCodeVerificationReady = false;
    document.getElementById("set-up-step-by-step-content-error-txt-code-verification").innerHTML = "";
    userID = document.getElementById("forgotten-password-code-verification-user-id").innerHTML;
    forgottenPasswordBtnHandler("load", "set-up-step-by-step-content-footer-btn-code-verification-send-again");
    xhrFPCodeVerificationSendAgain = new XMLHttpRequest();
    xhrFPCodeVerificationSendAgain.onreadystatechange = function() {
      if (xhrFPCodeVerificationSendAgain.readyState == 4 && xhrFPCodeVerificationSendAgain.status == 200) {
        forgottenPasswordCodeVerificationReady = true;
        forgottenPasswordBtnHandler("def", "set-up-step-by-step-content-footer-btn-code-verification-send-again");
        if (testJSON(xhrFPCodeVerificationSendAgain.response)) {
          var json = JSON.parse(xhrFPCodeVerificationSendAgain.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                document.getElementById("set-up-step-by-step-content-title-h2-code-verification-email").innerHTML = json[key]["email"];
                forgottenPasswordCountdownStart(json[key]["date"]);
                document.getElementById("forgotten-password-code-verification-user-id").innerHTML = json[key]["id"];
              } else {
                document.getElementById("set-up-step-by-step-content-error-txt-code-verification").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          document.getElementById("set-up-step-by-step-content-error-txt-code-verification").innerHTML = xhrFPCodeVerificationSendAgain.response;
        }
      }
    }
    xhrFPCodeVerificationSendAgain.open("POST", "php-backend/send-verification-code-again.php");
    xhrFPCodeVerificationSendAgain.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrFPCodeVerificationSendAgain.send("userID="+ userID);
  }
}

var forgPassCountdownInterval;
function forgottenPasswordCountdownStart(fromDate) {
  forgottenPasswordCountdownOutput(forgottenPasswordCountdownCalc(fromDate));
  clearInterval(forgPassCountdownInterval);
  forgPassCountdownInterval = setInterval(function() {
    forgottenPasswordCountdownOutput(forgottenPasswordCountdownCalc(fromDate));
  }, 1000);
}

function forgottenPasswordCountdownCalc(fromDate) {
  fromDate = fromDate.split("-");
  var dateHours = fromDate[2].split(" ");
  var time = dateHours[1].split(":");
  var diff = new Date().getTime() - new Date(fromDate[0],fromDate[1]-1,dateHours[0], time[0], time[1], time[2]).getTime();
  var seconds = Math.ceil(diff / 1000);
  seconds = 300 - seconds;
  return seconds;
}

function forgottenPasswordCountdownOutput(seconds) {
  if (seconds <= 0) {
    clearInterval(forgPassCountdownInterval);
    document.getElementById("forgotten-password-under-input-txt-countdown").innerHTML = "00:00";
  } else {
    var minutes = Math.floor(seconds / 60);
    seconds = seconds - minutes * 60;
    var timeFormat = ("0" + minutes).slice(-2) +":"+ ("0" + seconds).slice(-2);
    document.getElementById("forgotten-password-under-input-txt-countdown").innerHTML = timeFormat;
  }
}
