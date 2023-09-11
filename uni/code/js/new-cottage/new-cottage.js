window.addEventListener('load', function(event) {
  nCNavOnSliderHandler();
}, false);

window.addEventListener('resize', function(event) {
  nCNavOnSliderHandler();
}, false);

var nc_call_map = false;
function newCottageModal(tsk) {
  if (tsk == "show") {
    accountDrop('hide');
    modCover('show', 'modal-cover-new-cottage');
    if (document.getElementById("main-menu-btn").value != "none") {
      menu();
    }
    nCBasicsTitleWidth();
    nCNavOnSliderHandler();
    if (nc_call_map) {
      ncClearMapMarkers();
    } else {
      mapManager("new-cottage", 0, 0);
      nc_call_map = true;
    }
    loadConditionOfStayData('new-cottage', '');
    if (document.getElementById("settings-input-iban").value == "") {
      ncBankAccountModal("show");
    }
  } else {
    modCover('hide', 'modal-cover-new-cottage');
    document.getElementById("n-c-nav-slider-wrp").scrollLeft = 0;
    nCSlide('basics');
    nCBasicsTitleWidth();
    ncResetData();
  }
}

var nCSBtnNumWidth = 0;
var nCSBtnSts;
function nCSlide(id) {
  document.getElementById("n-c-size").scrollTop = 0;
  for (var nCS = 0; nCS < document.getElementsByClassName("n-c-layout").length; nCS++) {
    document.getElementsByClassName("n-c-layout")[nCS].classList.remove("n-c-layout-selected");
  }
  document.getElementById("n-c-layout-"+ id).classList.add("n-c-layout-selected");
  document.getElementById("n-c-nav-slider-btn-"+ id).classList.add("n-c-nav-slider-btn-selected");
  for (var nCSB = 0; nCSB < document.getElementsByClassName("n-c-nav-slider-btn").length; nCSB++) {
    document.getElementsByClassName("n-c-nav-slider-btn")[nCSB].classList.remove("n-c-nav-slider-btn-selected");
  }
  document.getElementById("n-c-nav-slider-btn-"+ id).classList.add("n-c-nav-slider-btn-selected");
  nCSBtnNumWidth = -40;
  nCSBtnSts = true;
  for (var nCSBW = 0; nCSBW < document.getElementsByClassName("n-c-nav-slider-btn-wrp").length; nCSBW++) {
    if (!document.getElementsByClassName("n-c-nav-slider-btn")[nCSBW].classList.contains("n-c-nav-slider-btn-selected") && nCSBtnSts) {
      nCSBtnNumWidth = nCSBtnNumWidth + document.getElementsByClassName("n-c-nav-slider-btn-wrp")[nCSBW].offsetWidth;
    } else {
      nCSBtnSts = false;
    }
  }
  nCBasicsTitleWidth();
  ncConditionsOfStayLangListControlBtnsManager();
  if (id != "calendar-sync") {
    document.getElementById("n-c-nav-btn-continue").style.display = "";
    document.getElementById("n-c-nav-btn-save").style.display = "";
  } else {
    document.getElementById("n-c-nav-btn-continue").style.display = "none";
    document.getElementById("n-c-nav-btn-save").style.display = "table";
  }
  scrollTo(document.getElementById("n-c-nav-slider-wrp"), nCSBtnNumWidth, 300);
}

function nCNavOnSliderHandler() {
  if (document.getElementById("n-c-nav-slider-wrp").scrollLeft <= 10) {
    document.getElementById("n-c-nav-slider-cover-wrp-left").style.display = "";
  } else {
    document.getElementById("n-c-nav-slider-cover-wrp-left").style.display = "block";
  }
  if (document.getElementById("n-c-nav-slider-wrp").scrollLeft >= document.getElementById("n-c-nav-slider-wrp").scrollWidth - document.getElementById("n-c-nav-slider-wrp").clientWidth - 10) {
    document.getElementById("n-c-nav-slider-cover-wrp-right").style.display = "";
  } else {
    document.getElementById("n-c-nav-slider-cover-wrp-right").style.display = "block";
  }
}

var ncContinueDone;
function ncContinue() {
  ncContinueDone = false;
  for (var cS = 0; cS < document.getElementsByClassName("n-c-nav-slider-btn").length; cS++) {
    if (!ncContinueDone) {
      if (document.getElementsByClassName("n-c-nav-slider-btn")[cS].classList.contains("n-c-nav-slider-btn-selected")) {
        if (document.getElementsByClassName("n-c-nav-slider-btn")[cS +1]) {
          nCSlide(document.getElementsByClassName("n-c-nav-slider-btn")[cS +1].value);
          ncContinueDone = true;
        }
      }
    }
  }
}

var ncNavScrollTo;
function ncNavSliderScroll(tsk) {
  if (tsk == "left") {
    ncNavScrollTo = document.getElementById("n-c-nav-slider-wrp").scrollLeft - (document.getElementById("n-c-nav-slider-wrp").offsetWidth / 2);
  } else {
    ncNavScrollTo = document.getElementById("n-c-nav-slider-wrp").scrollLeft + (document.getElementById("n-c-nav-slider-wrp").offsetWidth / 2);
  }
  scrollTo(document.getElementById("n-c-nav-slider-wrp"), ncNavScrollTo, 250);
}

function ncResetData() {
  ncResetErrors();
  ncBasicsReset();
  ncDetailsReset();
  ncPriceReset();
  ncPhotosReset();
  ncMapReset();
  ncPhotosReset();
  ncVideosReset();
  ncCalendarSyncReset();
  ncCoverHandler("none");
  ncPreviewReset();
  ncSaveCancel();
  ncBankAccountReset();
}

function ncResetErrors() {
  document.getElementById("n-c-error-txt-basics").innerHTML = "";
  document.getElementById("n-c-error-txt-details").innerHTML = "";
  document.getElementById("n-c-error-txt-price").innerHTML = "";
  document.getElementById("n-c-error-txt-photos").innerHTML = "";
  document.getElementById("n-c-error-txt-videos").innerHTML = "";
  document.getElementById("n-c-error-txt-map").innerHTML = "";
  document.getElementById("n-c-error-txt-conditions").innerHTML = "";
}

var ncSaveReady = true;
var ncsEquipmentArr = [];
var ncsPhotoArr = [];
var ncsVideosArr = [];
var ncsCalendarSyncArr = [];
var xhrNcSave, ncsName, ncsDesc, ncsType, ncsNumOfBedrooms, ncsNumOfGuests, ncsNumOfBathrooms, ncsDistanceFromTheWater, ncsOperation, ncsEquipmentRemoveBtns, ncsEquipTitle, ncsEquipSrc , ncsEquipObj, ncsEquipmentJSON, ncsID, ncsPriceMode, ncsPriceWork, ncsPriceWeek, ncsPhotoID, ncsPhotoSrc, ncsPhotoOrg, ncsPhotoSts, ncsPhotoObj, ncsPhotoJSON, ncsVideosList, ncsVideoID, ncsVideosObj, ncsVideosJSON, ncsMapLat, ncsMapLng, ncsConditionsLang, ncsConditionsTxt, ncsCalendarSyncAboutCode, ncsCalendarSyncCode, ncsCalendarSyncURL , ncsCalendarSyncObj, ncsCalendarSyncJSON;
var ncOutputDoneSts = false;
var ncOutputErrorSts = false;
var ncOutputErrorSection = "";
var ncOutputErrorTxt = "";
function ncSave() {
  if (ncSaveReady) {
    if (ncPhotosReadyCheck()) {
      ncSaveReady = false;
      window.onbeforeunload = function(event) {
        event.returnValue = "Your changes may not be saved.";
      };
      ncResetErrors();
      ncCoverHandler("processing");
      ncOutputErrorSection = "";
      ncOutputErrorTxt = "";
      ncsName = document.getElementById("n-c-input-text-title").value;
      ncsDesc = document.getElementById("n-c-textarea-description").value;
      ncsType = document.getElementById('n-c-select-type').value;
      ncsNumOfBedrooms = document.getElementById("n-c-input-number-details-bedrooms").value;
      ncsNumOfGuests = document.getElementById("n-c-input-number-details-guests").value;
      ncsNumOfBathrooms = document.getElementById("n-c-input-number-details-bathrooms").value;
      ncsDistanceFromTheWater = document.getElementById("n-c-input-number-details-distance-from-the-water").value;
      ncsOperation = document.getElementById('n-c-select-operation').value;
      ncsOperationSummerFrom = document.getElementById('n-c-select-operation-from-summer').value;
      ncsOperationSummerTo = document.getElementById('n-c-select-operation-to-summer').value;
      ncsOperationWinterFrom = document.getElementById('n-c-select-operation-from-winter').value;
      ncsOperationWinterTo = document.getElementById('n-c-select-operation-to-winter').value;
      ncsEquipmentArr = [];
      ncsEquipmentRemoveBtns = document.getElementsByClassName("n-c-equi-block-remove");
      for (var ncsE = 0; ncsE < ncsEquipmentRemoveBtns.length; ncsE++) {
         ncsEquipTitle = document.getElementById("n-c-equi-block-p-"+ ncsEquipmentRemoveBtns[ncsE].value).textContent;
         ncsEquipSrc = document.getElementById("n-c-equi-block-img-txt-"+ ncsEquipmentRemoveBtns[ncsE].value).innerHTML.split('/').pop();
         ncsEquipObj = {title:ncsEquipTitle, src:ncsEquipSrc};
         ncsEquipmentArr.push(ncsEquipObj);
      }
      ncsEquipmentJSON = JSON.stringify(ncsEquipmentArr);
      ncsID = document.getElementById("n-c-url-id-input").value;
      ncsPriceMode = document.getElementById("n-c-select-price-mode").value;
      ncsPriceWork = document.getElementById("n-c-price-input-work").value;
      ncsPriceWeek = document.getElementById("n-c-price-input-week").value;
      ncsPhotoArr = [];
      ncsPhotoID = document.getElementsByClassName("n-c-photo-details-block-id");
      for (var i = 0; i < ncsPhotoID.length; i++) {
         ncsPhotoSrc = document.getElementById("n-c-photo-img-"+ ncsPhotoID[i].textContent).src.split('/').pop();
         ncsPhotoOrg = document.getElementById("n-c-photo-details-org-id-"+ ncsPhotoID[i].textContent).textContent;
         if (document.getElementById("n-c-photo-tools-radio-input-"+ ncsPhotoID[i].textContent).checked) {
           ncsPhotoSts = "main";
         } else {
           ncsPhotoSts = "common";
         }
         ncsPhotoObj = {src:ncsPhotoSrc, org:ncsPhotoOrg, sts:ncsPhotoSts};
         ncsPhotoArr.push(ncsPhotoObj);
      }
      ncsPhotoJSON = JSON.stringify(ncsPhotoArr);
      ncsVideosArr = [];
      ncsVideosList = document.getElementsByClassName("n-c-videos-about-txt");
      for (var ncsV = 0; ncsV < ncsVideosList.length; ncsV++) {
         ncsVideoID = ncsVideosList[ncsV].textContent;
         ncsVideosObj = {id:ncsVideoID};
         ncsVideosArr.push(ncsVideosObj);
      }
      ncsVideosJSON = JSON.stringify(ncsVideosArr);
      ncsMapLat = document.getElementById("n-c-map-lat").textContent;
      ncsMapLng = document.getElementById("n-c-map-lng").textContent;
      ncsConditionsLang = document.getElementsByClassName("conditions-of-stay-lang-select-btn-new-cottage-selected")[0].value;
      ncsConditionsTxt = document.getElementById("n-c-conditions-of-stay-textarea").value;
      ncsCalendarSyncArr = [];
      ncsCalendarSyncAboutCode = document.getElementsByClassName("n-c-calendar-sync-about-code");
      for (var ncsCS = 0; ncsCS < ncsCalendarSyncAboutCode.length; ncsCS++) {
         ncsCalendarSyncCode = ncsCalendarSyncAboutCode[ncsCS].innerHTML;
         ncsCalendarSyncURL = document.getElementById("n-c-calendar-sync-input-"+ ncsCalendarSyncAboutCode[ncsCS].innerHTML).value;
         ncsCalendarSyncObj = {code:ncsCalendarSyncCode, url:ncsCalendarSyncURL};
         ncsCalendarSyncArr.push(ncsCalendarSyncObj);
      }
      ncsCalendarSyncJSON = JSON.stringify(ncsCalendarSyncArr);
      xhrNcSave = new XMLHttpRequest();
      xhrNcSave.onreadystatechange = function() {
        if (xhrNcSave.readyState == 4 && xhrNcSave.status == 200) {
          ncSaveReady = true;
          ncOutputDoneSts = false;
          ncOutputErrorSts = false;
          if (testJSON(xhrNcSave.response)) {
            var json = JSON.parse(xhrNcSave.response);
            for (var key in json) {
              if (json.hasOwnProperty(key)) {
                if (json[key]["type"] == "done") {
                  ncOutputDoneSts = true;
                } else {
                  ncOutputErrorSts = true;
                  if (json[key]["section"] == "uni") {
                    ncOutputErrorSection = "conditions";
                  } else {
                    ncOutputErrorSection = json[key]["section"];
                  }
                  ncOutputErrorTxt = json[key]["error"];
                }
              }
            }
            if (ncOutputDoneSts) {
              ncActivate(ncsID, ncOutputErrorTxt);
            } else if (ncOutputErrorSts) {
              window.onbeforeunload = null;
              ncCoverHandler("none");
              if (ncOutputErrorSection == "bank-account") {
                ncBankAccountModal('show');
              } else {
                nCSlide(ncOutputErrorSection);
                document.getElementById("n-c-error-txt-" +ncOutputErrorSection).innerHTML = ncOutputErrorTxt;
              }
            }
          } else {
            window.onbeforeunload = null;
            ncCoverHandler("none");
            document.getElementById("n-c-error-txt-conditions").innerHTML = xhrNcSave.response;
            nCSlide('conditions');
          }
        }
      }
      xhrNcSave.open("POST", "../uni/code/php-backend/new-cottage/new-cottange.php");
      xhrNcSave.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhrNcSave.send("name="+ ncsName +"&desc="+ ncsDesc +"&type="+ ncsType +"&bedNum="+ ncsNumOfBedrooms +"&guestNum="+ ncsNumOfGuests +"&bathNum="+ ncsNumOfBathrooms +"&distanceFromTheWater="+ ncsDistanceFromTheWater +"&operation="+ ncsOperation +"&operationSummerFrom="+ ncsOperationSummerFrom +"&operationSummerTo="+ ncsOperationSummerTo +"&operationWinterFrom="+ ncsOperationWinterFrom +"&operationWinterTo="+ ncsOperationWinterTo +"&equipment="+ ncsEquipmentJSON +"&id="+ ncsID +"&priceMode="+ ncsPriceMode +"&priceWork="+ ncsPriceWork +"&priceWeek="+ ncsPriceWeek +"&imgs="+ ncsPhotoJSON +"&videos="+ ncsVideosJSON +"&lat="+ ncsMapLat +"&lng="+ ncsMapLng +"&conditionsLang="+ ncsConditionsLang +"&conditions="+ ncsConditionsTxt +"&calendarSync="+ ncsCalendarSyncJSON);
    } else {
      nCSlide('photos');
    }
  }
}

function ncSaveCancel() {
  ncSaveReady = true;
  window.onbeforeunload = null;
  if (xhrNcSave != null) {
    xhrNcSave.abort();
  }
}

var xhrNcActivate;
var lastLinkDelete, lastLinkAdd, lastLinkAddClone, lastLinkRating;
function ncActivate(id, saveError) {
  if (xhrNcActivate != null) {
    xhrNcActivate.abort();
  }
  xhrNcActivate = new XMLHttpRequest();
  xhrNcActivate.onreadystatechange = function() {
    if (xhrNcActivate.readyState == 4 && xhrNcActivate.status == 200) {
      window.onbeforeunload = null;
      if (testJSON(xhrNcActivate.response)) {
        var json = JSON.parse(xhrNcActivate.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "done") {
              if (saveError != "") {
                document.getElementById("nc-preview-error-wrp").style.display = "block";
                document.getElementById("nc-preview-error-txt").innerHTML = saveError;
              }
              document.getElementById("nc-preview-link").href = "../place/?id="+ id;
              if (json[key]["img"] != "none") {
                document.getElementById("nc-preview-image").src = "../"+ json[key]["img"];
              } else {
                document.getElementById("nc-preview-image-wrp").classList.add("nc-preview-image-wrp");
                document.getElementById("nc-preview-image").src = "../uni/icons/home4.svg";
              }
              document.getElementById("nc-preview-details-title").innerHTML = json[key]["name"];
              document.getElementById("nc-preview-details-description").innerHTML = json[key]["desc"];
              ncCoverHandler("preview");
              if (document.getElementById("my-profile")) {
                addNewCottageToUserList(json[key]["name"], id, json[key]["desc"], json[key]["img"]);
                if (document.getElementById("u-a-links-list")) {
                  if (document.getElementsByClassName("link-img-blck").length >= 6) {
                    if (document.getElementsByClassName("link-img-blck-date").length > 0) {
                      lastLinkDelete = document.getElementsByClassName("link-img-blck-date")[document.getElementsByClassName("link-img-blck-date").length -1];
                      lastLinkDelete.parentNode.removeChild(lastLinkDelete);
                    }
                  }
                  loadCottageDataErrorHandler("hide");
                  loadCottageDataLoaderHandler("hide");
                  imgCottLinkRender("new", "u-a-links-list", "link-img-blck-date", id, json[key]["name"], json[key]["img"], json[key]["priceWork"], json[key]["priceWeek"], "eur", "none");
                  if (document.getElementsByClassName("link-img-blck-rating").length > 0) {
                    lastLinkAdd = document.getElementsByClassName("link-img-blck")[0];
                    lastLinkRating = document.getElementsByClassName("link-img-blck-rating")[document.getElementsByClassName("link-img-blck-rating").length -1];
                    lastLinkAddClone = lastLinkAdd.cloneNode(true);
                    lastLinkAdd.parentNode.removeChild(lastLinkAdd);
                    lastLinkRating.parentNode.insertBefore(lastLinkAddClone, lastLinkRating.nextSibling);
                  }
                }
                if (document.getElementById("user-cottage-grid")) {
                  document.getElementById("u-c-no-cottage-txt").style.display = "none";
                  imgCottLinkRender("new", "user-cottage-grid", "", id, json[key]["name"], json[key]["img"], json[key]["priceWork"], json[key]["priceWeek"], "eur", "none");
                }
              }
              if (document.getElementById("editor-wrp")) {
                loadConditionOfStayData('editor', new URL(window.location.href).searchParams.get("id"));
              }
            } else if (json[key]["type"] == "error") {
              ncCoverHandler("none");
              nCSlide('conditions');
              if (saveError == "") {
                saveError = json[key]["error"];
              } else {
                saveError = saveError +"<br>"+ json[key]["error"];
              }
              document.getElementById("n-c-error-txt-" +ncOutputErrorSection).innerHTML = saveError;
            }
          }
        }
      } else {
        ncCoverHandler("none");
        if (saveError == "") {
          document.getElementById("n-c-error-txt-conditions").innerHTML = xhrNcActivate.response;
        } else {
          document.getElementById("n-c-error-txt-conditions").innerHTML = saveError +"<br>"+ xhrNcActivate.response;
        }
        nCSlide('conditions');
      }
    }
  }
  xhrNcActivate.open("POST", "../uni/code/php-backend/new-cottage/new-cottange-activate.php");
  xhrNcActivate.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrNcActivate.send("plcID="+ id);
}

function ncCoverHandler(tsk) {
  if (tsk == "none") {
    document.getElementById("n-c-content-cover").style.display = "";
    document.getElementById("nc-data-processing-wrp").style.display = "";
    document.getElementById("nc-preview-wrp").style.display = "";
    document.getElementById("n-c-size").style.overflow = "";
  } else if (tsk == "processing") {
    document.getElementById("n-c-content-cover").style.display = "block";
    document.getElementById("nc-data-processing-wrp").style.display = "flex";
    document.getElementById("nc-preview-wrp").style.display = "";
    document.getElementById("n-c-size").style.overflow = "hidden";
    document.getElementById("n-c-size").scrollTop = 0;
  } else if (tsk == "preview") {
    document.getElementById("n-c-content-cover").style.display = "block";
    document.getElementById("nc-data-processing-wrp").style.display = "";
    document.getElementById("nc-preview-wrp").style.display = "flex";
    document.getElementById("n-c-size").style.overflow = "hidden";
    document.getElementById("n-c-size").scrollTop = 0;
  }
  ncCovernNavHandler(tsk);
}

function ncCovernNavHandler(tsk) {
  if (tsk == "none") {
    for (var nB = 0; nB < document.getElementsByClassName("n-c-nav-slider-btn").length; nB++) {
      document.getElementsByClassName("n-c-nav-slider-btn")[nB].disabled = false;
      document.getElementsByClassName("n-c-nav-slider-btn")[nB].style.opacity = "";
      document.getElementsByClassName("n-c-nav-slider-btn")[nB].style.cursor = "";
    }
  } else if (tsk == "processing") {
    for (var nB = 0; nB < document.getElementsByClassName("n-c-nav-slider-btn").length; nB++) {
      document.getElementsByClassName("n-c-nav-slider-btn")[nB].disabled = true;
      document.getElementsByClassName("n-c-nav-slider-btn")[nB].style.opacity = "0.35";
      document.getElementsByClassName("n-c-nav-slider-btn")[nB].style.cursor = "default";
    }
  } else if (tsk == "preview") {
    for (var nB = 0; nB < document.getElementsByClassName("n-c-nav-slider-btn").length; nB++) {
      document.getElementsByClassName("n-c-nav-slider-btn")[nB].disabled = true;
      document.getElementsByClassName("n-c-nav-slider-btn")[nB].style.opacity = "0.35";
      document.getElementsByClassName("n-c-nav-slider-btn")[nB].style.cursor = "default";
    }
  }
  ncCoverNavBtnHandler(tsk);
}

function ncCoverNavBtnHandler(tsk) {
  if (tsk == "none") {
    document.getElementById("n-c-nav-btn-prim").style.display = "";
    document.getElementById("n-c-nav-btn-cover").style.display = "";
  } else if (tsk == "processing") {
    document.getElementById("n-c-nav-btn-prim").style.display = "none";
    document.getElementById("n-c-nav-btn-cover").style.display = "table";
    document.getElementById("n-c-nav-btn-cancel").style.display = "table";
    document.getElementById("n-c-nav-btn-add-another").style.display = "";
  } else if (tsk == "preview") {
    document.getElementById("n-c-nav-btn-prim").style.display = "none";
    document.getElementById("n-c-nav-btn-cover").style.display = "table";
    document.getElementById("n-c-nav-btn-cancel").style.display = "";
    document.getElementById("n-c-nav-btn-add-another").style.display = "table";
  }
}

function ncCoverNavBtnOnclick() {
  ncResetData();
  nCSlide('basics');
  loadConditionOfStayData('new-cottage', '');
}
