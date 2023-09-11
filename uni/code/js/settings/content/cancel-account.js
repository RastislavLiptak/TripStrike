function cancelAccountAlert(event) {
  event.preventDefault();
  modCover("show", "modal-cover-cancel-account-alert");
}

function cancelAccountSubmit() {
  modCover("show", "modal-cover-password-1");
  document.getElementById("cancel-account-inpt").focus();
  modCover("hide", "modal-cover-cancel-account-alert");
}

var cancelReady = true;
var cancAccEmail, cancAccPhone;
function cancelMyAccount() {
  if (cancelReady) {
    cancelReady = false;
    document.getElementById("cancel-pass-help").style.display = "";
    document.getElementById("cancel-account-pass-inpt-err").style.display = "";
    document.getElementById("cancel-account-pass-inpt-err").innerHTML = "";
    modCover('hide', 'modal-cover-password-1');
    cancelLoader("show");
    cancAccEmail = document.getElementById("settings-cancel-account-data-email").textContent;
    cancAccPhone = document.getElementById("settings-cancel-account-data-phone").textContent;
    cancAccPhone = cancAccPhone.replace(/\+/g, "plus");
    var cancPass = document.getElementById("cancel-account-inpt").value;
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        cancelReady = true;
        if (testJSON(xhr.response)) {
          var json = JSON.parse(xhr.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["sts"] == 1) {
                if (json[key]["msg"] == "done") {
                  location.href = "../home/";
                } else {
                  cancelLoader("hide");
                  modCover('show', 'modal-cover-cancel-account-partial-errors');
                  document.getElementById("cancel-profile-partial-errors-modal-err").innerHTML = json[key]["msg"];
                }
              } else {
                cancelLoader("hide");
                cancelAccountSubmit();
                document.getElementById("cancel-pass-help").style.display = "none";
                document.getElementById("cancel-account-pass-inpt-err").style.display = "block";
                document.getElementById("cancel-account-pass-inpt-err").innerHTML = json[key]["msg"];
              }
            }
          }
        } else {
          cancelLoader("hide");
          cancelAccountSubmit();
          document.getElementById("cancel-pass-help").style.display = "none";
          document.getElementById("cancel-account-pass-inpt-err").style.display = "block";
          document.getElementById("cancel-account-pass-inpt-err").innerHTML = xhr.response;
        }
      }
    }
    xhr.open("POST", "../uni/code/php-backend/settings/cancel-account.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("id=none&email="+ cancAccEmail +"&phonenum="+ cancAccPhone +"&pass="+ cancPass);
  }
}

var cancelAccBtnTime;
function cancelAccInput() {
  clearTimeout(cancelAccBtnTime);
  var val = document.getElementById("cancel-account-inpt").value;
  if (val != "") {
    document.getElementById("set-cancel-my-account-btn").style.display = "table";
    cancelAccBtnTime = setTimeout(function(){
      document.getElementById("set-cancel-my-account-btn").style.opacity = "1";
    }, 10);
  } else {
    document.getElementById("set-cancel-my-account-btn").style.opacity = "0";
    cancelAccBtnTime = setTimeout(function(){
      document.getElementById("set-cancel-my-account-btn").style.display = "none";
    }, 160);
  }
}

var cancLoadModT;
function cancelLoader(task) {
  if (task == "show") {
    document.getElementById("modal-cover-password-2").style.display = "table";
    document.getElementById("modal-cover-cancel-account-loader-wrp").style.display = "flex";
    clearTimeout(cancLoadModT);
    cancLoadModT = setTimeout(function(){
      document.getElementById("modal-cover-cancel-account-loader-wrp").style.opacity = "1";
      document.getElementById("modal-cover-password-2").style.backgroundColor = "rgba(0,0,0,0.65)";
    }, 30);
  } else {
    clearTimeout(cancLoadModT);
    cancLoadModT = setTimeout(function(){
      document.getElementById("modal-cover-cancel-account-loader-wrp").style.display = "none";
      document.getElementById("modal-cover-password-2").style.display = "none";
    }, 300);
    document.getElementById("modal-cover-cancel-account-loader-wrp").style.opacity = "0";
    document.getElementById("modal-cover-password-2").style.backgroundColor = "rgba(0,0,0,0)";
  }
}

function cancelAccEmpty() {
  document.getElementById("cancel-account-inpt").value = "";
  cancelAccInput();
}
