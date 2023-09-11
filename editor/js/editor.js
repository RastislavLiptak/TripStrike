var url_string = window.location.href;
var url = new URL(url_string);
var plc_id = url.searchParams.get("id");

window.addEventListener("load", function(){
  optimizeAllTextboxToContent();
});

window.addEventListener("resize", function(){
  optimizeAllTextboxToContent();
});

function optimizeAllTextboxToContent() {
  editorInputVerticalResize('editor-content-textarea-title');
  editorInputVerticalResize('editor-content-textarea-desc');
  editorInputVerticalResize('editor-content-textarea-conditions');
}

function editorInputVerticalResize(id) {
  if (document.getElementById(id)) {
    document.getElementById(id).style.height = "";
    document.getElementById(id).style.height = document.getElementById(id).scrollHeight +"px";
  }
}

function editorInputDateDropdownSelectOnchange(fromTo, txtNum, section) {
  if (txtNum == 1) {
    document.getElementById("m-e-c-f-dropdown-btn-txt-"+ fromTo +"-1-"+ section).classList.add("m-e-c-f-dropdown-btn-txt-show");
    document.getElementById("m-e-c-f-dropdown-btn-txt-"+ fromTo +"-2-"+ section).classList.remove("m-e-c-f-dropdown-btn-txt-show");
    document.getElementById("m-e-c-f-dropdown-radio-"+ fromTo +"-1-"+ section).checked = true;
    document.getElementById("m-e-c-f-dropdown-radio-"+ fromTo +"-2-"+ section).checked = false;
  } else {
    document.getElementById("m-e-c-f-dropdown-btn-txt-"+ fromTo +"-1-"+ section).classList.remove("m-e-c-f-dropdown-btn-txt-show");
    document.getElementById("m-e-c-f-dropdown-btn-txt-"+ fromTo +"-2-"+ section).classList.add("m-e-c-f-dropdown-btn-txt-show");
    document.getElementById("m-e-c-f-dropdown-radio-"+ fromTo +"-1-"+ section).checked = false;
    document.getElementById("m-e-c-f-dropdown-radio-"+ fromTo +"-2-"+ section).checked = true;
  }
}

function editorInputDateDropdownToggle(tsk, btnEl, dropdownId) {
  for (var b = 0; b < document.getElementsByClassName("m-e-c-f-dropdown-btn").length; b++) {
    if (document.getElementsByClassName("m-e-c-f-dropdown")[b]) {
      document.getElementsByClassName("m-e-c-f-dropdown")[b].style.display = "";
      document.getElementsByClassName("m-e-c-f-dropdown-btn")[b].value = "show";
    }
  }
  if (tsk == "show") {
    if (document.getElementById(dropdownId)) {
      document.getElementById(dropdownId).style.display = "table";
      btnEl.value = "hide";
    }
  }
}

function editorBtnHandler(task, id) {
  if (document.getElementById(id)) {
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
}

function editorModalNotificationAlertDescToggle(b, id) {
  if (b.value == "show") {
    document.getElementById("m-e-c-f-notification-blck-desc-wrp-"+ id).style.display = "table";
    b.value = "hide";
  } else {
    document.getElementById("m-e-c-f-notification-blck-desc-wrp-"+ id).style.display = "";
    b.value = "show";
  }
}

var eSaveLat, eSaveLng;
function editorSaveMapLatLng(sLat, sLng) {
  eSaveLat = sLat;
  eSaveLng = sLng;
}

var xhrEditorSave, eTitle, eDesc, eEquipObj, eEquipArr, eEquipJSON, equipRemoveClassEl, eEquiTtl, eEquiSrc, eType, eBeds, eGuests, eBathrooms, eDistanceFromTheWater, eOperation, ePriceMode, ePriceWork, ePriceWeek, eID, conditionsLngShrt, conditionsTxt, vidAboutIDTxt, eVidID, eVidObj, eVidJSON, eVidArr, eOutputDone, eOutputErrorTxt, editorSaveOutputTime, eCalendarSyncObj, eCalendarSyncArr, eCalendarSyncJSON, eCalendarSyncAboutClassEl, eCalendarSyncCode, eCalendarSyncURL;
var editorSaveReady = true;
function editorSave() {
  if (editorSaveReady) {
    editorSaveReady = false;
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    editorBtnHandler("load", "editor-save-btn");
    document.getElementById("editor-footer-error-wrp").style.display = "";
    document.getElementById("editor-footer-error-txt").innerHTML = "";
    eTitle = document.getElementById("editor-content-textarea-title").value;
    eDesc = document.getElementById("editor-content-textarea-desc").value;
    eEquipArr = [];
    equipRemoveClassEl = document.getElementsByClassName("editor-details-equipment-block-remove");
    for (var e = 0; e < equipRemoveClassEl.length; e++) {
      eEquiTtl = document.getElementById("editor-details-equipment-block-p-"+ equipRemoveClassEl[e].value).innerHTML;
      eEquiSrc = document.getElementById("editor-details-equipment-block-img-txt-"+ equipRemoveClassEl[e].value).innerHTML.split('/').pop();
      eEquipObj = {title:eEquiTtl, src:eEquiSrc};
      eEquipArr.push(eEquipObj);
    }
    eEquipJSON = JSON.stringify(eEquipArr);
    eType = document.getElementById("editor-input-type-of-accommodation").value;
    eBeds = document.getElementById("editor-input-num-of-bedrooms").value;
    eGuests = document.getElementById("editor-input-num-of-guest").value;
    eBathrooms = document.getElementById("editor-input-num-of-bathrooms").value;
    eDistanceFromTheWater = document.getElementById("editor-input-distance-from-the-water").value;
    eOperation = document.getElementById("editor-input-operation").value;
    eVidArr = [];
    vidAboutIDTxt = document.getElementsByClassName("editor-content-video-about-txt");
    for (var v = 0; v < vidAboutIDTxt.length; v++) {
      eVidID = vidAboutIDTxt[v].innerHTML;
      eVidObj = {id:eVidID};
      eVidArr.push(eVidObj);
    }
    eVidJSON = JSON.stringify(eVidArr);
    eCalendarSyncArr = [];
    eCalendarSyncAboutClassEl = document.getElementsByClassName("c-s-about-code");
    for (var c = 0; c < eCalendarSyncAboutClassEl.length; c++) {
      eCalendarSyncCode = eCalendarSyncAboutClassEl[c].innerHTML;
      eCalendarSyncURL = document.getElementById("c-s-input-"+ eCalendarSyncAboutClassEl[c].innerHTML).value;
      eCalendarSyncObj = {code:eCalendarSyncCode, url:eCalendarSyncURL};
      eCalendarSyncArr.push(eCalendarSyncObj);
    }
    eCalendarSyncJSON = JSON.stringify(eCalendarSyncArr);
    eOperationSummerFrom = document.getElementById("editor-details-options-grid-cell-select-summer-from").value;
    eOperationSummerTo = document.getElementById("editor-details-options-grid-cell-select-summer-to").value;
    eOperationWinterFrom = document.getElementById("editor-details-options-grid-cell-select-winter-from").value;
    eOperationWinterTo = document.getElementById("editor-details-options-grid-cell-select-winter-to").value;
    ePriceMode = document.getElementById("editor-select-price-mode").value;
    ePriceWork = document.getElementById("editor-input-price-work").value;
    ePriceWeek = document.getElementById("editor-input-price-week").value;
    eID = document.getElementById("editor-details-place-id-input").value;
    conditionsLngShrt = document.getElementsByClassName("conditions-of-stay-lang-select-btn-editor-selected")[0].value;
    conditionsTxt = document.getElementById("editor-content-textarea-conditions").value;
    xhrEditorSave = new XMLHttpRequest();
    xhrEditorSave.onreadystatechange = function() {
      if (xhrEditorSave.readyState == 4 && xhrEditorSave.status == 200) {
        editorSaveReady = true;
        window.onbeforeunload = null;
        eOutputDone = true;
        eOutputErrorTxt = "";
        if (testJSON(xhrEditorSave.response)) {
          var json = JSON.parse(xhrEditorSave.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "error") {
                eOutputDone = false;
                eOutputErrorTxt = eOutputErrorTxt +""+ json[key]["error"] +"<br>";
              }
            }
          }
          if (eOutputDone) {
            editorBtnHandler("success", "editor-save-btn");
            loadConditionOfStayData('editor', plc_id);
            document.getElementById("editor-content-conditions-lang-slider-content").innerHTML = "";
            clearTimeout(editorSaveOutputTime);
            editorSaveOutputTime = setTimeout(function(){
              editorBtnHandler("def", "editor-save-btn");
            }, 750);
          } else {
            document.getElementById("editor-footer-error-wrp").style.display = "table";
            document.getElementById("editor-footer-error-txt").innerHTML = eOutputErrorTxt;
            editorBtnHandler("def", "editor-save-btn");
          }
        } else {
          document.getElementById("editor-footer-error-wrp").style.display = "table";
          document.getElementById("editor-footer-error-txt").innerHTML = xhrEditorSave.response;
        }
      }
    }
    xhrEditorSave.open("POST", "php-backend/place-data-update.php");
    xhrEditorSave.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrEditorSave.send("urlId="+ plc_id +"&name="+ eTitle +"&id="+ eID +"&desc="+ eDesc +"&type="+ eType +"&bedNum="+ eBeds +"&guestNum="+ eGuests +"&bathNum="+ eBathrooms +"&distanceFromTheWater="+ eDistanceFromTheWater +"&operation="+ eOperation +"&operationSummerFrom="+ eOperationSummerFrom +"&operationSummerTo="+ eOperationSummerTo +"&operationWinterFrom="+ eOperationWinterFrom +"&operationWinterTo="+ eOperationWinterTo +"&priceMode="+ ePriceMode +"&priceWork="+ ePriceWork +"&priceWeek="+ ePriceWeek +"&lat="+ eSaveLat +"&lng="+ eSaveLng +"&equipment="+ eEquipJSON +"&conditionsLangShrt="+ conditionsLngShrt +"&conditionsTxt="+ conditionsTxt +"&videos="+ eVidJSON +"&calendarSync="+ eCalendarSyncJSON);
  }
}
