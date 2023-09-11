function ncCalendarSyncRenderForm(code, title, logo, defUrl, icalLinkTxt) {
  var ncCSWrp = document.createElement("div");
  ncCSWrp.setAttribute("class", "n-c-calendar-sync-wrp");
  ncCSWrp.setAttribute("id", "n-c-calendar-sync-wrp-"+ code);
  var ncCSAboutWrp = document.createElement("div");
  ncCSAboutWrp.setAttribute("class", "n-c-calendar-sync-about-wrp");
  var ncCSAboutCode = document.createElement("p");
  ncCSAboutCode.setAttribute("class", "n-c-calendar-sync-about-code");
  ncCSAboutCode.innerHTML = code;
  var ncCSBlck = document.createElement("div");
  ncCSBlck.setAttribute("class", "n-c-calendar-sync-blck");
  var ncCSLogoWrp = document.createElement("div");
  ncCSLogoWrp.setAttribute("class", "n-c-calendar-sync-logo-wrp");
  var ncCSLogoImg = document.createElement("img");
  ncCSLogoImg.setAttribute("class", "n-c-calendar-sync-logo-img");
  ncCSLogoImg.setAttribute("alt", title);
  ncCSLogoImg.setAttribute("src", "../uni/icons/logos/"+ logo);
  var ncCSInputWrp = document.createElement("div");
  ncCSInputWrp.setAttribute("class", "n-c-calendar-sync-input-wrp");
  var ncCSInput = document.createElement("input");
  ncCSInput.setAttribute("type", "text");
  ncCSInput.setAttribute("placeholder", icalLinkTxt +": "+ defUrl);
  ncCSInput.setAttribute("class", "n-c-calendar-sync-input");
  ncCSInput.setAttribute("id", "n-c-calendar-sync-input-"+ code);
  var ncCSDeleteWrp = document.createElement("div");
  ncCSDeleteWrp.setAttribute("class", "n-c-calendar-sync-header-delete-wrp");
  var ncCSDeleteBtn = document.createElement("button");
  ncCSDeleteBtn.setAttribute("type", "button");
  ncCSDeleteBtn.setAttribute("class", "n-c-calendar-sync-header-delete-btn");
  ncCSDeleteBtn.setAttribute("onclick", "ncCalendarSyncDeleteBlock('"+ code +"')");
  ncCSWrp.appendChild(ncCSAboutWrp);
  ncCSAboutWrp.appendChild(ncCSAboutCode);
  ncCSWrp.appendChild(ncCSBlck);
  ncCSBlck.appendChild(ncCSLogoWrp);
  ncCSLogoWrp.appendChild(ncCSLogoImg);
  ncCSBlck.appendChild(ncCSInputWrp);
  ncCSInputWrp.appendChild(ncCSInput);
  ncCSBlck.appendChild(ncCSDeleteWrp);
  ncCSDeleteWrp.appendChild(ncCSDeleteBtn);
  document.getElementById("n-c-calendar-sync-content").appendChild(ncCSWrp);
}

function ncCalendarSyncDeleteBlock(code) {
  document.getElementById("n-c-calendar-sync-wrp-"+ code).parentNode.removeChild(document.getElementById("n-c-calendar-sync-wrp-"+ code));
}

var numCalendarSyncWrp;
function ncCalendarSyncReset() {
  numCalendarSyncWrp = document.getElementsByClassName("n-c-calendar-sync-wrp").length;
  for (var cSR = 0; cSR < numCalendarSyncWrp; cSR++) {
    document.getElementsByClassName("n-c-calendar-sync-wrp")[0].parentNode.removeChild(document.getElementsByClassName("n-c-calendar-sync-wrp")[0]);
  }
}
