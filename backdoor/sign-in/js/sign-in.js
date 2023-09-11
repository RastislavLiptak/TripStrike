var backDoorSignInSubmitReady = true;
var xhrBackDoorSignIn, signInPassword;
function backDoorSignInSubmit(e) {
  e.preventDefault();
  if (backDoorSignInSubmitReady) {
    backDoorSignInSubmitReady = false;
    backDoorBtnManager("load", "b-d-sign-in-submit-btn");
    document.getElementById("b-d-sign-in-error").innerHTML = "";
    signInPassword = document.getElementById("b-d-sign-in-input").value
    xhrBackDoorSignIn = new XMLHttpRequest();
    xhrBackDoorSignIn.onreadystatechange = function() {
      if (xhrBackDoorSignIn.readyState == 4 && xhrBackDoorSignIn.status == 200) {
        backDoorSignInSubmitReady = true;
        if (testJSON(xhrBackDoorSignIn.response)) {
          var json = JSON.parse(xhrBackDoorSignIn.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                backDoorBtnManager("success", "b-d-sign-in-submit-btn");
                location.reload();
              } else if (json[key]["type"] == "error") {
                backDoorBtnManager("def", "b-d-sign-in-submit-btn");
                document.getElementById("b-d-sign-in-error").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          backDoorBtnManager("def", "b-d-sign-in-submit-btn");
          document.getElementById("b-d-sign-in-error").innerHTML = xhrBackDoorSignIn.response;
        }
      }
    }
    xhrBackDoorSignIn.open("POST", "php-backend/sign-in.php");
    xhrBackDoorSignIn.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrBackDoorSignIn.send("password="+ signInPassword);
  }
}
