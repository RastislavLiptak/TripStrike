function signInModal(tsk) {
  document.getElementById("sign-in-input-account-id").value = "";
  document.getElementById("sign-in-input-password").value = "";
  document.getElementById("sign-in-error-txt-in").innerHTML = "";
  modCover(tsk, 'modal-cover-sign-in');
}

var signInAccountID, signInPass;
var signInReady = true;
function signIn(e) {
  e.preventDefault();
  if (signInReady) {
    signInReady = false;
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    signInAccountID = document.getElementById("sign-in-input-account-id").value.replace(/\+/g, "plus");
    signInPass = document.getElementById("sign-in-input-password").value;
    document.getElementById("sign-in-error-txt-in").value = "";
    signSubmitBtn("load", "sign-in-btn");
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        signInReady = true;
        window.onbeforeunload = null;
        if (testJSON(xhr.response)) {
          var json = JSON.parse(xhr.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                signSubmitBtn("success", "sign-in-btn");
                location.reload();
              } else {
                signSubmitBtn("def", "sign-in-btn");
                document.getElementById("sign-in-input-password").value = "";
                document.getElementById("sign-in-error-txt-in").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          signSubmitBtn("def", "sign-in-btn");
          document.getElementById("sign-in-input-password").value = "";
          document.getElementById("sign-in-error-txt-in").innerHTML = xhr.response;
        }
      }
    }
    xhr.open("POST", "../uni/code/php-backend/sign-in/sign-in/sign-in.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("user="+ signInAccountID +"&pass="+ signInPass);
  }
}
