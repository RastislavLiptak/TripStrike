var savePublicDataSettingsReady = true;
var pDFirstName, pDLastName, pDEmail, pDEmailSts, pDPhone, pDPhoneSts, pDPassword, savePublicDataTimer;
function savePublicDataSettings(settingsPasswordUsageSts) {
  if (savePublicDataSettingsReady) {
    clearTimeout(savePublicDataTimer);
    savePublicDataSettingsReady = false;
    pDFirstName = document.getElementById("settings-input-firstname").value;
    pDLastName = document.getElementById("settings-input-lastname").value;
    pDEmail = document.getElementById("settings-input-contact-email").value;
    pDEmailSts = document.getElementById("settings-data-sync-checkbox-contact-email").checked;
    pDPhone = document.getElementById("settings-input-contact-phone").value;
    pDPhone = pDPhone.replace(/\+/g, "plus");
    pDPhoneSts = document.getElementById("settings-data-sync-checkbox-contact-phone").checked;
    pDPassword = document.getElementById("change-sign-data-inpt").value;
    document.getElementById("settings-error-txt-public-data").innerHTML = "";
    document.getElementById("change-sign-data-inpt").value = "";
    modCover('hide', 'modal-cover-sign-password');
    settingsPasswordErrorHide();
    document.getElementById("settings-pass-w-txt-email").innerHTML = "";
    document.getElementById("settings-pass-w-txt-email").style.display = "";
    document.getElementById("settings-pass-w-txt-tel").innerHTML = "";
    document.getElementById("settings-pass-w-txt-tel").style.display = "";
    setSaveBtn("load", "settings-save-btn-public-data");
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        savePublicDataSettingsReady = true;
        if (testJSON(xhr.response)) {
          var json = JSON.parse(xhr.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                setSaveBtn("success", "settings-save-btn-public-data");
                document.getElementById("account-txt").innerHTML = json[key]["firstname"];
                if (document.getElementById("my-profile")) {
                  updateProfileData(json[key]["firstname"], json[key]["lastname"], json[key]["contactemail"], json[key]["contactphonenum"]);
                  document.title = document.title.replace(json[key]["oldFirstname"], json[key]["firstname"]);
                  document.title = document.title.replace(json[key]["oldLastname"], json[key]["lastname"]);
                  document.getElementById("subtitle-h1").innerHTML = json[key]["firstname"] +" "+ json[key]["lastname"];
                }
                if (document.getElementById("my-place-username")) {
                  document.getElementById("my-place-username").innerHTML = json[key]["firstname"];
                  document.getElementById("place-space-txt-name").innerHTML = json[key]["firstname"];
                  document.getElementById("t-f-b-txt-username").innerHTML = json[key]["firstname"] +" "+ json[key]["lastname"];
                }
                if (document.getElementById("p-d-a-comments-wrp")) {
                  for (var i = 0; i < document.getElementsByClassName("my-plc-comm-user").length; i++) {
                    document.getElementsByClassName("my-plc-comm-user")[i].innerHTML = json[key]["firstname"] +" "+ json[key]["lastname"];
                  }
                }
                if (document.getElementById("about-booking-facility-card-i-am-the-host")) {
                  document.getElementById("about-booking-facility-host-name").innerHTML = json[key]["firstname"] +" "+ json[key]["lastname"];
                  document.getElementById("about-booking-facility-host-txt-email").innerHTML = json[key]["contactemail"];
                  document.getElementById("about-booking-facility-host-txt-phone").innerHTML = json[key]["contactphonenum"];
                }
                if (document.getElementById("about-booking-facility-card-i-am-the-guest")) {
                  document.getElementById("about-booking-facility-host-name").innerHTML = json[key]["firstname"] +" "+ json[key]["lastname"];
                }
                document.getElementById("settings-input-email").value = json[key]["email"];
                document.getElementById("settings-input-phone").value = json[key]["phonenum"];
                if (json[key]["syncemailsts"] == "1") {
                  document.getElementById("settings-data-sync-checkbox-email").checked = true;
                } else {
                  document.getElementById("settings-data-sync-checkbox-email").checked = false;
                }
                if (json[key]["syncnumsts"] == "1") {
                  document.getElementById("settings-data-sync-checkbox-phone").checked = true;
                } else {
                  document.getElementById("settings-data-sync-checkbox-phone").checked = false;
                }
                document.getElementById("settings-cancel-account-data-email").innerHTML = json[key]["email"];
                document.getElementById("settings-cancel-account-data-phone").innerHTML = json[key]["phonenum"];
                savePublicDataTimer = setTimeout(function(){
                  setSaveBtn("def", "settings-save-btn-public-data");
                }, 1000);
              } else if (json[key]["type"] == "password-needed") {
                setSaveBtn("def", "settings-save-btn-public-data");
                if (json[key]["chenged"] == "email") {
                  document.getElementById("settings-pass-w-txt-email").innerHTML = json[key]["txt"];
                  document.getElementById("settings-pass-w-txt-email").style.display = "block";
                } else {
                  document.getElementById("settings-pass-w-txt-tel").innerHTML = json[key]["txt"];
                  document.getElementById("settings-pass-w-txt-tel").style.display = "block";
                }
              } else if (json[key]["type"] == "password-error") {
                setSaveBtn("def", "settings-save-btn-public-data");
                if (settingsPasswordUsageSts) {
                  document.getElementById("settings-pass-w-txt-help-btn").style.display = "none";
                  document.getElementById("sign-data-change-pass-alert-err").style.display = "block";
                  document.getElementById("sign-data-change-pass-alert-err").innerHTML = json[key]["error"];
                }
                document.getElementById("sett-main-save-pass-btn").value = "public-data";
                modCover('show', 'modal-cover-sign-password');
              } else {
                setSaveBtn("def", "settings-save-btn-public-data");
                document.getElementById("settings-content-scroll").scrollTop = 0;
                document.getElementById("settings-error-txt-public-data").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          setSaveBtn("def", "settings-save-btn-public-data");
          document.getElementById("settings-content-scroll").scrollTop = 0;
          document.getElementById("settings-error-txt-public-data").innerHTML = xhr.response;
        }
      }
    }
    xhr.open("POST", "../uni/code/php-backend/settings/public-data.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("firstname="+ pDFirstName +"&lastname="+ pDLastName +"&contEmail="+ pDEmail +"&contEmailSts="+ pDEmailSts +"&contPhone="+ pDPhone +"&contPhoneSts="+ pDPhoneSts +"&password="+ pDPassword);
  }
}
