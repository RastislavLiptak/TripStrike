var ncPhotosUploadReady = true;
var xhrNcPhotosUpload, ncPhotosUploadLoaderT;
function ncPhotosUpload(inpt) {
  if (ncPhotosReadyCheck() && inpt.files.length != 0) {
    ncPhotosUploadReady = false;
    ncPhotosUploadLoader("loading");
    document.getElementById("n-c-error-txt-photos").innerHTML = "";
    inpt.disabled = true;
    var ncPhotosFormData = new FormData();
    for (var f = 0; f < inpt.files.length; f++) {
      ncPhotosFormData.append("file[]", inpt.files[f]);
    }
    xhrNcPhotosUpload = new XMLHttpRequest();
    xhrNcPhotosUpload.onreadystatechange = function() {
      if (xhrNcPhotosUpload.readyState == 4 && xhrNcPhotosUpload.status == 200) {
        ncPhotosUploadReady = true;
        inpt.value = "";
        inpt.disabled = false;
        if (testJSON(xhrNcPhotosUpload.response)) {
          var json = JSON.parse(xhrNcPhotosUpload.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "image") {
                ncPhotosRender(json[key]["org"], json[key]["sml"]);
                ncPhotosUploadLoader("ok");
                clearTimeout(ncPhotosUploadLoaderT);
                ncPhotosUploadLoaderT = setTimeout(function(){
                  ncPhotosUploadLoader("def");
                }, 1000);
              } else if (json[key]["type"] == "error") {
                ncPhotosUploadLoader("def");
                document.getElementById("n-c-error-txt-photos").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          ncPhotosUploadLoader("def");
          document.getElementById("n-c-error-txt-photos").innerHTML = xhrNcPhotosUpload.response;
        }
      }
    }
    xhrNcPhotosUpload.open("POST", "../uni/code/php-backend/new-cottage/new-cottage-temp-thumbnail.php");
    xhrNcPhotosUpload.send(ncPhotosFormData);
  }
}

function ncPhotosUploadCancel() {
  if (xhrNcPhotosUpload != null) {
    xhrNcPhotosUpload.abort();
  }
}

function ncPhotosReadyCheck() {
  return ncPhotosUploadReady;
}

function ncPhotosUploadLoader(tsk) {
  if (tsk == "def") {
    document.getElementById("n-c-photos-input-file-icon").style.backgroundImage = "";
    document.getElementById("n-c-photos-input-file-icon").style.backgroundSize = "";
  } else if (tsk == "loading") {
    document.getElementById("n-c-photos-input-file-icon").style.backgroundImage = "url('../uni/gifs/loader-tail.svg')";
    document.getElementById("n-c-photos-input-file-icon").style.backgroundSize = "80%";
  } else if (tsk == "ok") {
    document.getElementById("n-c-photos-input-file-icon").style.backgroundImage = "url('../uni/icons/ok2.svg')";
    document.getElementById("n-c-photos-input-file-icon").style.backgroundSize = "55%";
  }
}

var ncPhotosID, ncPhotosIDDone;
function ncPhotosRender(orgImgName, smlImgSrc) {
  ncPhotosIDDone = false;
  while (!ncPhotosIDDone) {
    ncPhotosID = Math.floor(Math.random() * 101);
    if (!document.getElementById("n-c-photo-wrp-"+ ncPhotosID)) {
      ncPhotosIDDone = true;
    }
  }
  var wrp = document.createElement("div");
  wrp.setAttribute("class", "n-c-photo-wrp");
  wrp.setAttribute("id", "n-c-photo-wrp-"+ ncPhotosID);
  var size = document.createElement("div");
  size.setAttribute("class", "n-c-photo-size");
  var content = document.createElement("div");
  content.setAttribute("class", "n-c-photo-content");
  var details = document.createElement("div");
  details.setAttribute("class", "n-c-photo-details");
  var pID = document.createElement("p");
  pID.setAttribute("class", "n-c-photo-details-block-id");
  pID.setAttribute("id", "n-c-photo-details-block-id-"+ ncPhotosID);
  pID.innerHTML = ncPhotosID;
  var pOrg = document.createElement("p");
  pOrg.setAttribute("class", "n-c-photo-details-org-id");
  pOrg.setAttribute("id", "n-c-photo-details-org-id-"+ ncPhotosID);
  pOrg.innerHTML = orgImgName;
  var layout = document.createElement("div");
  layout.setAttribute("class", "n-c-photo-layout");
  var img = document.createElement("img");
  img.setAttribute("class", "n-c-photo-img");
  img.setAttribute("id", "n-c-photo-img-"+ ncPhotosID);
  img.setAttribute("src", "../"+ smlImgSrc);
  img.setAttribute("alt", "thumnail image "+ ncPhotosID);
  var cover = document.createElement("div");
  cover.setAttribute("class", "n-c-photo-img-cover");
  var tools = document.createElement("div");
  tools.setAttribute("class", "n-c-photo-tools-wrp");
  var deletePhto = document.createElement("button");
  deletePhto.setAttribute("class", "n-c-photo-tools-delete");
  deletePhto.setAttribute("type", "button");
  deletePhto.setAttribute("onclick", "ncPhotoDelete('"+ ncPhotosID +"')");
  var radioWrp = document.createElement("div");
  radioWrp.setAttribute("class", "n-c-photo-tools-radio-wrp");
  var radioLabel = document.createElement("label");
  radioLabel.setAttribute("class", "n-c-photo-tools-radio-label");
  var radioInpt = document.createElement("input");
  radioInpt.setAttribute("type", "radio");
  radioInpt.setAttribute("name", "n-c-photo-tools-radio");
  radioInpt.setAttribute("value", ncPhotosID);
  radioInpt.setAttribute("onchange", "ncPhotoSelect('"+ ncPhotosID +"')");
  radioInpt.setAttribute("class", "n-c-photo-tools-radio-input");
  radioInpt.setAttribute("id", "n-c-photo-tools-radio-input-"+ ncPhotosID);
  var radioSpan = document.createElement("span");
  radioSpan.setAttribute("class", "n-c-photo-tools-radio-checkmark");
  wrp.appendChild(size);
  size.appendChild(content);
  content.appendChild(details);
  details.appendChild(pID);
  details.appendChild(pOrg);
  content.appendChild(layout);
  layout.appendChild(img);
  layout.appendChild(cover);
  layout.appendChild(tools);
  tools.appendChild(deletePhto);
  tools.appendChild(radioWrp);
  radioWrp.appendChild(radioLabel);
  radioLabel.appendChild(radioInpt);
  radioLabel.appendChild(radioSpan);
  document.getElementById("n-c-photos-grid").appendChild(wrp);
  ncPhotosReorder(ncPhotosID);
  ncPhotosAutoSelect();
}

var photoWrpLength
function ncPhotosReorder(ncPhotosID) {
  photoWrpLength = document.getElementsByClassName("n-c-photo-wrp").length;
  if (photoWrpLength > 2) {
    document.getElementById("n-c-photos-grid").insertBefore(document.getElementById("n-c-photo-wrp-"+ ncPhotosID), document.getElementsByClassName("n-c-photo-wrp")[1]);
  }
}

function ncPhotoDelete(ncPhotosID) {
  document.getElementById("n-c-photo-wrp-"+ ncPhotosID).parentNode.removeChild(document.getElementById("n-c-photo-wrp-"+ ncPhotosID));
  ncPhotosAutoSelect();
}

var ncPhotosAllRadios, ncPhotosSelected;
function ncPhotosAutoSelect() {
  ncPhotosSelected = 0;
  ncPhotosAllRadios = document.getElementsByClassName("n-c-photo-tools-radio-input");
  for (var c = 0 ; c < ncPhotosAllRadios.length; c++) {
    if (ncPhotosAllRadios[c].checked == true){
      ncPhotosSelected++;
    }
  }
  if (ncPhotosSelected == 0 && ncPhotosAllRadios.length != 0) {
    ncPhotosAllRadios[0].setAttribute("checked", "checked");
    ncPhotoSelect(ncPhotosAllRadios[0].value);
  }
}

var ncSelectedPhotosNum;
function ncPhotoSelect(ncPhotosID) {
  ncSelectedPhotosNum = document.getElementsByClassName("n-c-photo-wrp-selected").length;
  for (var sP = 0; sP < ncSelectedPhotosNum; sP++) {
    document.getElementsByClassName("n-c-photo-wrp-selected")[0].classList.remove("n-c-photo-wrp-selected");
  }
  document.getElementById("n-c-photo-wrp-"+ ncPhotosID).classList.add("n-c-photo-wrp-selected");
}

var ncPhotosNumOfImages;
function ncPhotosReset() {
  ncPhotosUploadCancel();
  ncPhotosNumOfImages = document.getElementsByClassName("n-c-photo-wrp").length;
  for (var p = 1; p < ncPhotosNumOfImages; p++) {
    document.getElementsByClassName("n-c-photo-wrp")[1].parentNode.removeChild(document.getElementsByClassName("n-c-photo-wrp")[1]);
  }
}
