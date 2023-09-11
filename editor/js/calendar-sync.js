function editorCalendarSyncRenderForm(code, title, logo, defUrl, icalLinkTxt) {
  var cSWrp = document.createElement("div");
  cSWrp.setAttribute("class", "c-s-wrp");
  cSWrp.setAttribute("id", "c-s-wrp-"+ code);
  var cSAboutWrp = document.createElement("div");
  cSAboutWrp.setAttribute("class", "c-s-about-wrp");
  var cSAboutCode = document.createElement("p");
  cSAboutCode.setAttribute("class", "c-s-about-code");
  cSAboutCode.innerHTML = code;
  var cSBlck = document.createElement("div");
  cSBlck.setAttribute("class", "c-s-blck");
  var cSContentLayout = document.createElement("div");
  cSContentLayout.setAttribute("class", "c-s-content-layout");
  var cSLogoWrp = document.createElement("div");
  cSLogoWrp.setAttribute("class", "c-s-logo-wrp");
  var cSLogoImg = document.createElement("img");
  cSLogoImg.setAttribute("class", "c-s-logo-img");
  cSLogoImg.setAttribute("alt", title);
  cSLogoImg.setAttribute("src", "../uni/icons/logos/"+ logo);
  var cSInputWrp = document.createElement("div");
  cSInputWrp.setAttribute("class", "c-s-input-wrp");
  var cSInput = document.createElement("input");
  cSInput.setAttribute("type", "text");
  cSInput.setAttribute("placeholder", icalLinkTxt +": "+ defUrl);
  cSInput.setAttribute("class", "c-s-input");
  cSInput.setAttribute("id", "c-s-input-"+ code);
  var cSDeleteWrp = document.createElement("div");
  cSDeleteWrp.setAttribute("class", "c-s-delete-wrp");
  var cSDeleteBtn = document.createElement("button");
  cSDeleteBtn.setAttribute("type", "button");
  cSDeleteBtn.setAttribute("class", "c-s-delete-btn");
  cSDeleteBtn.setAttribute("onclick", "editorCalendarSyncDeleteBlock('"+ code +"')");
  var cSErrorWrp = document.createElement("div");
  cSErrorWrp.setAttribute("class", "c-s-error-wrp");
  var cSErrorTxtWrp = document.createElement("div");
  cSErrorTxtWrp.setAttribute("class", "c-s-error-txt-wrp");
  var cSErrorTxt = document.createElement("p");
  cSErrorTxt.setAttribute("class", "c-s-error-txt");
  cSWrp.appendChild(cSAboutWrp);
  cSAboutWrp.appendChild(cSAboutCode);
  cSWrp.appendChild(cSBlck);
  cSBlck.appendChild(cSContentLayout);
  cSContentLayout.appendChild(cSLogoWrp);
  cSLogoWrp.appendChild(cSLogoImg);
  cSContentLayout.appendChild(cSInputWrp);
  cSInputWrp.appendChild(cSInput);
  cSContentLayout.appendChild(cSDeleteWrp);
  cSDeleteWrp.appendChild(cSDeleteBtn);
  cSBlck.appendChild(cSErrorWrp);
  cSErrorWrp.appendChild(cSErrorTxtWrp);
  cSErrorTxtWrp.appendChild(cSErrorTxt);
  document.getElementById("editor-content-calendar-sync-import-form-layout").appendChild(cSWrp);
}

function editorCalendarSyncDeleteBlock(code) {
  document.getElementById("c-s-wrp-"+ code).parentNode.removeChild(document.getElementById("c-s-wrp-"+ code));
}

function editorCalendarSyncCopyExport() {
  var copyText = document.getElementById("c-s-input-sync-export");
  copyText.select();
  copyText.setSelectionRange(0, 99999)
  document.execCommand("copy");
}
