function settingsOnloadData(dtbLangString) {
  if (document.getElementById("set-form-inpt-firstname") && document.getElementById("set-form-inpt-firstname").value != "") {
    document.getElementById("settings-profile-form").style.display = "block";
    document.getElementById("settings-pass-form").style.display = "block";
    document.getElementById("settings-crop-wrp").style.display = "flex";
  }
}

var settWrapTime;
function toggSet(task, sect) {
  accountDrop("hide");
  modCover(task, "modal-cover-settings");
  if (document.getElementById("main-menu-btn").value != "none") {
    menu();
  }
  if (task == "show") {
    clearTimeout(settWrapTime);
    settWrapTime = setTimeout(function(){
      settNav(sect);
    }, 10);
  }
}

var settNavPassSelected = false;
function settNav(sect) {
  settDafault();
  if (sect != "pass") {
    settNavPassSelected = false;
  } else if (!settNavPassSelected) {
    settNavPassSelected = true;
  }
  if (sect == "none") {
    sect = "public-data";
    document.getElementById("settings-content-wrp").className = "settings-mode-nav";
  } else {
    document.getElementById("settings-content-wrp").className = "settings-mode-content";
  }
  document.getElementById("settings-content-scroll").scrollTop = 0;
  settContClass("set-btn-" +sect, "set-nav-btn-select");
  settContClass("settings-" +sect, "sett-cont-show");
}

function settDafault() {
  var navEl = document.getElementsByClassName("set-nav-btn");
  for (var i = 0; i < navEl.length; ++i) {
    navEl[i].classList.remove("set-nav-btn-select");
  }
  var setCont = document.getElementsByClassName("settings-cont");
  for (var i2 = 0; i2 < setCont.length; ++i2) {
    setCont[i2].classList.remove("sett-cont-show");
  }
}

function settingsContentBack() {
  document.getElementById("settings-content-wrp").className = "settings-mode-nav";
}

function settContClass(id, clss) {
  var elmnt = document.getElementById(id);
  var arr = elmnt.className.split(" ");
  if (arr.indexOf(clss) == -1) {
    elmnt.className += " " + clss;
  }
}

function setSaveBtn(task, id) {
  if (task == "def") {
    document.getElementById(id).style.color = "#fff";
    document.getElementById(id).style.backgroundImage = "unset";
    document.getElementById(id).style.backgroundSize = "unset";
  } else if (task == "load") {
    document.getElementById(id).style.color = "rgba(0,0,0,0)";
    document.getElementById(id).style.backgroundImage = "url('../uni/gifs/loader-tail.svg')";
    document.getElementById(id).style.backgroundSize = "auto 63%";
  } else if (task == "success") {
    document.getElementById(id).style.color = "rgba(0,0,0,0)";
    document.getElementById(id).style.backgroundImage = "url('../uni/icons/ok2.svg')";
    document.getElementById(id).style.backgroundSize = "auto 47%";
  }
}

function settingsPasswordSubmitManager(tsk) {
  if (tsk == "public-data") {
    savePublicDataSettings(true);
  } else if (tsk == "personal-data") {
    savePersonalDataSettings(true);
  }
}

function settingsPasswordErrorHide() {
  document.getElementById("settings-pass-w-txt-help-btn").style.display = "";
  document.getElementById("sign-data-change-pass-alert-err").style.display = "";
}
