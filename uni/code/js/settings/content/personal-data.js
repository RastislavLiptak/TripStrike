var savePersonalDataSettingsReady = true;
var persDEmail, persDEmailSts, persDPhone, persDPhoneSts, persDBankAccount, persDIBAN, persDBICSWIFT, persDBirthD, persDBirthM, persDBirthY, persDPassword, savePersonalDataTimer, aboutBookingBankAccountText, aboutBookingIBANText, aboutBookingBicSwiftText;
function savePersonalDataSettings(settingsPasswordUsageSts) {
  if (savePersonalDataSettingsReady) {
    clearTimeout(savePersonalDataTimer);
    savePersonalDataSettingsReady = false;
    persDEmail = document.getElementById("settings-input-email").value;
    persDEmailSts = document.getElementById("settings-data-sync-checkbox-email").checked;
    persDPhone = document.getElementById("settings-input-phone").value;
    persDPhone = persDPhone.replace(/\+/g, "plus");
    persDPhoneSts = document.getElementById("settings-data-sync-checkbox-phone").checked;
    persDBankAccount = document.getElementById("settings-input-bank-account-number").value;
    persDIBAN = document.getElementById("settings-input-iban").value;
    persDBICSWIFT = document.getElementById("settings-input-bicswift").value;
    persDBirthD = document.getElementById("settings-input-birth-day").value;
    persDBirthM = document.getElementById("settings-input-birth-month").value;
    persDBirthY = document.getElementById("settings-input-birth-year").value;
    persDPassword = document.getElementById("change-sign-data-inpt").value;
    document.getElementById("settings-error-txt-personal-data").innerHTML = "";
    document.getElementById("change-sign-data-inpt").value = "";
    modCover('hide', 'modal-cover-sign-password');
    settingsPasswordErrorHide();
    document.getElementById("settings-pass-w-txt-email").innerHTML = "";
    document.getElementById("settings-pass-w-txt-email").style.display = "";
    document.getElementById("settings-pass-w-txt-tel").innerHTML = "";
    document.getElementById("settings-pass-w-txt-tel").style.display = "";
    setSaveBtn("load", "settings-save-btn-personal-data");
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        savePersonalDataSettingsReady = true;
        if (testJSON(xhr.response)) {
          var json = JSON.parse(xhr.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                setSaveBtn("success", "settings-save-btn-personal-data");
                if (document.getElementById("my-profile")) {
                  updateProfileData(json[key]["firstname"], json[key]["lastname"], json[key]["contactemail"], json[key]["contactphonenum"]);
                  document.title = document.title.replace(json[key]["oldFirstname"], json[key]["firstname"]);
                  document.title = document.title.replace(json[key]["oldLastname"], json[key]["lastname"]);
                }
                if (document.getElementById("my-place-username")) {
                  document.getElementById("my-place-username").innerHTML = json[key]["firstname"];
                  document.getElementById("place-space-txt-name").innerHTML = json[key]["firstname"];
                }
                if (document.getElementById("p-d-a-comments-wrp")) {
                  for (var i = 0; i < document.getElementsByClassName("my-plc-comm-user").length; i++) {
                    document.getElementsByClassName("my-plc-comm-user")[i].innerHTML = json[key]["firstname"] +" "+ json[key]["lastname"];
                  }
                }
                if (document.getElementById("about-booking-facility-card-i-am-the-host")) {
                  if (document.getElementById("about-booking-facility-host-txt-wrp-bank-details-title")) {
                    aboutBookingBankAccountText = document.getElementById("about-booking-facility-host-txt-bold-bank-account-number").innerHTML;
                    aboutBookingIBANText = "IBAN";
                    aboutBookingBicSwiftText = "BIC/SWIFT";
                    document.getElementById("about-booking-facility-host-txt-wrp-bank-account-number").parentNode.removeChild(document.getElementById("about-booking-facility-host-txt-wrp-bank-account-number"));
                    document.getElementById("about-booking-facility-host-txt-wrp-iban").parentNode.removeChild(document.getElementById("about-booking-facility-host-txt-wrp-iban"));
                    document.getElementById("about-booking-facility-host-txt-wrp-bicswift").parentNode.removeChild(document.getElementById("about-booking-facility-host-txt-wrp-bicswift"));
                    if (persDBankAccount != "" && persDBankAccount != "-") {
                      var txtWrpBankAccountNum = document.createElement("div");
                      txtWrpBankAccountNum.setAttribute("class", "about-booking-facility-host-txt-wrp");
                      txtWrpBankAccountNum.setAttribute("id", "about-booking-facility-host-txt-wrp-bank-account-number");
                      var txtBankAccountNum = document.createElement("p");
                      txtBankAccountNum.setAttribute("class", "about-booking-facility-host-txt");
                      txtBankAccountNum.innerHTML = "<b id='about-booking-facility-host-txt-bold-bank-account-number'>"+ aboutBookingBankAccountText +"</b> "+ persDBankAccount;
                      txtWrpBankAccountNum.appendChild(txtBankAccountNum);
                      document.getElementById("about-booking-facility-host-txt-layout").appendChild(txtWrpBankAccountNum);
                    }
                    if (persDIBAN != "" && persDIBAN != "-") {
                      var txtWrpIBAN = document.createElement("div");
                      txtWrpIBAN.setAttribute("class", "about-booking-facility-host-txt-wrp");
                      txtWrpIBAN.setAttribute("id", "about-booking-facility-host-txt-wrp-iban");
                      var txtIBAN = document.createElement("p");
                      txtIBAN.setAttribute("class", "about-booking-facility-host-txt");
                      txtIBAN.innerHTML = "<b id='about-booking-facility-host-txt-bold-iban'>"+ aboutBookingIBANText +"</b> "+ persDIBAN;
                      txtWrpIBAN.appendChild(txtIBAN);
                      document.getElementById("about-booking-facility-host-txt-layout").appendChild(txtWrpIBAN);
                    }
                    if (persDBICSWIFT != "" && persDBICSWIFT != "-") {
                      var txtWrpBicSwift = document.createElement("div");
                      txtWrpBicSwift.setAttribute("class", "about-booking-facility-host-txt-wrp");
                      txtWrpBicSwift.setAttribute("id", "about-booking-facility-host-txt-wrp-bicswift");
                      var txtBicSwift = document.createElement("p");
                      txtBicSwift.setAttribute("class", "about-booking-facility-host-txt");
                      txtBicSwift.innerHTML = "<b id='about-booking-facility-host-txt-bold-bicswift'>"+ aboutBookingBicSwiftText +"</b> "+ persDBICSWIFT;
                      txtWrpBicSwift.appendChild(txtBicSwift);
                      document.getElementById("about-booking-facility-host-txt-layout").appendChild(txtWrpBicSwift);
                    }
                  }
                }
                document.getElementById("settings-input-contact-email").value = json[key]["contactemail"];
                document.getElementById("settings-input-contact-phone").value = json[key]["contactphonenum"];
                if (json[key]["syncemailsts"] == "1") {
                  document.getElementById("settings-data-sync-checkbox-contact-email").checked = true;
                } else {
                  document.getElementById("settings-data-sync-checkbox-contact-email").checked = false;
                }
                if (json[key]["syncnumsts"] == "1") {
                  document.getElementById("settings-data-sync-checkbox-contact-phone").checked = true;
                } else {
                  document.getElementById("settings-data-sync-checkbox-contact-phone").checked = false;
                }
                document.getElementById("settings-cancel-account-data-email").innerHTML = json[key]["email"];
                document.getElementById("settings-cancel-account-data-phone").innerHTML = json[key]["phonenum"];
                savePersonalDataTimer = setTimeout(function(){
                  setSaveBtn("def", "settings-save-btn-personal-data");
                }, 1000);
              } else if (json[key]["type"] == "password-needed") {
                setSaveBtn("def", "settings-save-btn-personal-data");
                if (json[key]["chenged"] == "email") {
                  document.getElementById("settings-pass-w-txt-email").innerHTML = json[key]["txt"];
                  document.getElementById("settings-pass-w-txt-email").style.display = "block";
                } else {
                  document.getElementById("settings-pass-w-txt-tel").innerHTML = json[key]["txt"];
                  document.getElementById("settings-pass-w-txt-tel").style.display = "block";
                }
              } else if (json[key]["type"] == "password-error") {
                setSaveBtn("def", "settings-save-btn-personal-data");
                if (settingsPasswordUsageSts) {
                  document.getElementById("settings-pass-w-txt-help-btn").style.display = "none";
                  document.getElementById("sign-data-change-pass-alert-err").style.display = "block";
                  document.getElementById("sign-data-change-pass-alert-err").innerHTML = json[key]["error"];
                }
                document.getElementById("sett-main-save-pass-btn").value = "personal-data";
                modCover('show', 'modal-cover-sign-password');
              } else {
                setSaveBtn("def", "settings-save-btn-personal-data");
                document.getElementById("settings-content-scroll").scrollTop = 0;
                document.getElementById("settings-error-txt-personal-data").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          setSaveBtn("def", "settings-save-btn-personal-data");
          document.getElementById("settings-content-scroll").scrollTop = 0;
          document.getElementById("settings-error-txt-personal-data").innerHTML = xhr.response;
        }
      }
    }
    xhr.open("POST", "../uni/code/php-backend/settings/personal-data.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("email="+ persDEmail +"&emailSts="+ persDEmailSts +"&phone="+ persDPhone +"&phoneSts="+ persDPhoneSts +"&bankAccount="+ persDBankAccount +"&iban="+ persDIBAN +"&bicswift="+ persDBICSWIFT +"&birthD="+ persDBirthD +"&birthM="+ persDBirthM +"&birthY="+ persDBirthY +"&password="+ persDPassword);
  }
}
