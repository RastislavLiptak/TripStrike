function signUpModal(tsk) {
  document.getElementById("sign-in-error-txt-up").innerHTML = "";
  document.getElementById("sign-up-input-firstname").value = "";
  document.getElementById("sign-up-input-lastname").value = "";
  document.getElementById("sign-up-input-birth-day").value = "";
  document.getElementById("sign-up-input-birth-month").value = "";
  document.getElementById("sign-up-input-birth-year").value = "";
  document.getElementById("sign-up-input-email").value = "";
  document.getElementById("sign-up-input-contact-email").value = "";
  document.getElementById("sign-up-input-phone-number").value = "";
  document.getElementById("sign-up-input-contact-phone-number").value = "";
  document.getElementById("sign-up-input-password").value = "";
  document.getElementById("sign-up-input-password-verification").value = "";
  document.getElementById("sign-up-checkbox-conditions").checked = false;
  signUpAboutYouFulfillmentSts();
  signUpEmailAndPhoneFulfillmentSts();
  signUpPasswordFulfillmentSts();
  signUpConditionsFulfillmentSts();
  signUpContinueAndSubmitBtnManager();
  signUpContentSwitch("about-you");
  modCover(tsk, 'modal-cover-sign-up');
}

var signUpNavSelectedNum, signUpContentSelectedNum;
function signUpContentSwitch(id) {
  signUpNavSelectedNum = document.getElementsByClassName("sign-up-nav-btn-selected").length;
  for (var nS = 0; nS < signUpNavSelectedNum; nS++) {
    document.getElementsByClassName("sign-up-nav-btn-selected")[0].classList.remove("sign-up-nav-btn-selected");
  }
  signUpContentSelectedNum = document.getElementsByClassName("sign-up-form-wrp-selected").length;
  for (var nC = 0; nC < signUpContentSelectedNum; nC++) {
    document.getElementsByClassName("sign-up-form-wrp-selected")[0].classList.remove("sign-up-form-wrp-selected");
  }
  document.getElementById("sign-in-form-body-up").scrollTop = 0;
  document.getElementById("sign-up-nav-btn-"+ id).classList.add("sign-up-nav-btn-selected");
  document.getElementById("sign-up-form-wrp-"+ id).classList.add("sign-up-form-wrp-selected");
}

var contStartSts, unfinishedForm, contNextID;
function signUpContinue() {
  contStartSts = false;
  unfinishedForm = false;
  contNextID = "";
  for (var fW = 0; fW < document.getElementsByClassName("sign-up-form-wrp").length; fW++) {
    if (contStartSts) {
      if (contNextID == "") {
        contNextID = document.getElementsByClassName("sign-up-form-about-id")[fW].textContent;
      }
    } else {
      if (document.getElementsByClassName("sign-up-form-wrp")[fW].classList.contains("sign-up-form-wrp-selected")) {
        if (fW +1 == document.getElementsByClassName("sign-up-form-wrp").length) {
          unfinishedForm = true;
        } else {
          contStartSts = true;
        }
      }
    }
  }
  if (contNextID == "about-you") {
    if (signUpAboutYouFulfillmentSts()) {
      contNextID = "email-and-phone";
    }
  }
  if (contNextID == "email-and-phone") {
    if (signUpEmailAndPhoneFulfillmentSts()) {
      contNextID = "password";
    }
  }
  if (contNextID == "password") {
    if (signUpPasswordFulfillmentSts()) {
      contNextID = "conditions";
    }
  }
  if (contNextID == "conditions") {
    if (signUpConditionsFulfillmentSts()) {
      unfinishedForm = true;
    }
  }
  if (unfinishedForm) {
    if (!signUpAboutYouFulfillmentSts()) {
      signUpContentSwitch("about-you");
    } else if (!signUpEmailAndPhoneFulfillmentSts()) {
      signUpContentSwitch("email-and-phone");
    } else if (!signUpPasswordFulfillmentSts()) {
      signUpContentSwitch("password");
    } else if (!signUpConditionsFulfillmentSts()) {
      signUpContentSwitch("conditions");
    } else {
      signUpContinueAndSubmitBtnManager();
      signUp("");
    }
  } else {
    signUpContentSwitch(contNextID);
  }
}

function signUpAboutYouFulfillmentSts() {
  if (
    document.getElementById("sign-up-input-firstname").value != "" &&
    document.getElementById("sign-up-input-lastname").value != "" &&
    document.getElementById("sign-up-input-birth-day").value != "" &&
    document.getElementById("sign-up-input-birth-month").value != "" &&
    document.getElementById("sign-up-input-birth-year").value != ""
  ) {
    document.getElementById("sign-up-nav-btn-about-you").classList.add("sign-up-nav-btn-done");
    return true;
  } else {
    document.getElementById("sign-up-nav-btn-about-you").classList.remove("sign-up-nav-btn-done");
    return false;
  }
}

function signUpEmailAndPhoneFulfillmentSts() {
  if (
    document.getElementById("sign-up-input-email").value != "" &&
    document.getElementById("sign-up-input-phone-number").value != ""
  ) {
    document.getElementById("sign-up-nav-btn-email-and-phone").classList.add("sign-up-nav-btn-done");
    return true;
  } else {
    document.getElementById("sign-up-nav-btn-email-and-phone").classList.remove("sign-up-nav-btn-done");
    return false;
  }
}

function signUpPasswordFulfillmentSts() {
  if (
    document.getElementById("sign-up-input-password").value != "" &&
    document.getElementById("sign-up-input-password-verification").value != ""
  ) {
    document.getElementById("sign-up-nav-btn-password").classList.add("sign-up-nav-btn-done");
    return true;
  } else {
    document.getElementById("sign-up-nav-btn-password").classList.remove("sign-up-nav-btn-done");
    return false;
  }
}

function signUpConditionsFulfillmentSts() {
  if (document.getElementById("sign-up-checkbox-conditions").checked) {
    document.getElementById("sign-up-nav-btn-conditions").classList.add("sign-up-nav-btn-done");
    return true;
  } else {
    document.getElementById("sign-up-nav-btn-conditions").classList.remove("sign-up-nav-btn-done");
    return false;
  }
}

function signUpContinueAndSubmitBtnManager() {
  if (signUpAboutYouFulfillmentSts() && signUpEmailAndPhoneFulfillmentSts() && signUpPasswordFulfillmentSts() && signUpConditionsFulfillmentSts()) {
    document.getElementById("sign-up-btn-continue").style.display = "none";
    document.getElementById("sign-up-btn").style.display = "table";
  } else {
    document.getElementById("sign-up-btn-continue").style.display = "";
    document.getElementById("sign-up-btn").style.display = "";
  }
}

var signUpReady = true;
var signUpFirstname, signUpLastname, signUpBirthD, signUpBirthM, signUpBirthY, signUpEmail, signUpContactEmail, signUpPhone, signUpContactPhone, signUpPassword, signUpPasswordVerification, signUpConditions;
function signUp(e) {
  if (e != "") {
    e.preventDefault();
  }
  if (signUpAboutYouFulfillmentSts() && signUpEmailAndPhoneFulfillmentSts() && signUpPasswordFulfillmentSts() && signUpConditionsFulfillmentSts()) {
    if (signUpReady) {
      window.onbeforeunload = function(event) {
        event.returnValue = "Your changes may not be saved.";
      };
      signUpReady = false;
      signUpFirstname = document.getElementById("sign-up-input-firstname").value;
      signUpLastname = document.getElementById("sign-up-input-lastname").value;
      signUpBirthD = document.getElementById("sign-up-input-birth-day").value;
      signUpBirthM = document.getElementById("sign-up-input-birth-month").value;
      signUpBirthY = document.getElementById("sign-up-input-birth-year").value;
      signUpEmail = document.getElementById("sign-up-input-email").value;
      signUpContactEmail = document.getElementById("sign-up-input-contact-email").value;
      signUpPhone = document.getElementById("sign-up-input-phone-number").value.replace(/\+/g, "plus");
      signUpContactPhone = document.getElementById("sign-up-input-contact-phone-number").value.replace(/\+/g, "plus");
      signUpPassword = document.getElementById("sign-up-input-password").value;
      signUpPasswordVerification = document.getElementById("sign-up-input-password-verification").value;
      if (document.getElementById("sign-up-checkbox-conditions").checked) {
        signUpConditions = 1;
      } else {
        signUpConditions = 0;
      }
      document.getElementById("sign-in-error-txt-up").innerHTML = "";
      signSubmitBtn("load", "sign-up-btn");
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
          signUpReady = true;
          window.onbeforeunload = null;
          if (testJSON(xhr.response)) {
            var json = JSON.parse(xhr.response);
            for (var key in json) {
              if (json.hasOwnProperty(key)) {
                if (json[key]["type"] == "done") {
                  signSubmitBtn("success", "sign-up-btn");
                  location.reload();
                } else {
                  signSubmitBtn("def", "sign-up-btn");
                  document.getElementById("sign-in-error-txt-up").innerHTML = json[key]["error"];
                }
              }
            }
          } else {
            signSubmitBtn("def", "sign-up-btn");
            document.getElementById("sign-in-error-txt-up").innerHTML = xhr.response;
          }
        }
      }
      xhr.open("POST", "../uni/code/php-backend/sign-in/sign-up/new-account.php");
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.send("firstname="+ signUpFirstname +"&lastname="+ signUpLastname +"&birthd="+ signUpBirthD +"&birthm="+ signUpBirthM +"&birthy="+ signUpBirthY +"&email="+ signUpEmail +"&contactemail="+ signUpContactEmail +"&phone="+ signUpPhone +"&contactphone="+ signUpContactPhone +"&password="+ signUpPassword +"&passwordverification="+ signUpPasswordVerification +"&conditions="+ signUpConditions);
    }
  } else {
    signUpContinueAndSubmitBtnManager();
  }
}
