var calendarSyncModalReady = true;
var numOfCheckmarks = 0;
var presetCodes;
function calendarSyncModal(tsk, sect) {
  if (calendarSyncModalReady) {
    numOfCheckmarks = document.getElementsByClassName("calendar-sync-modal-grid-checkbox").length;
    for (var cM = 0; cM < numOfCheckmarks; cM++) {
      document.getElementsByClassName("calendar-sync-modal-grid-checkbox")[cM].checked = false;
    }
    if (sect == "editor") {
      for (var aC = 0; aC < document.getElementsByClassName("c-s-about-code").length; aC++) {
        presetCodes = document.getElementsByClassName("c-s-about-code")[aC].innerHTML;
        document.getElementById("calendar-sync-modal-grid-checkbox-"+ presetCodes).checked = true;
      }
    } else {
      for (var aC = 0; aC < document.getElementsByClassName("n-c-calendar-sync-about-code").length; aC++) {
        presetCodes = document.getElementsByClassName("n-c-calendar-sync-about-code")[aC].innerHTML;
        document.getElementById("calendar-sync-modal-grid-checkbox-"+ presetCodes).checked = true;
      }
    }
    document.getElementById("calendar-sync-add-btn").value = sect;
    modCover(tsk, 'modal-cover-calendar-sync');
  }
}

function calendarSyncBlckBtnToggle(code) {
  if (document.getElementById("calendar-sync-modal-grid-checkbox-"+ code).checked) {
    document.getElementById("calendar-sync-modal-grid-checkbox-"+ code).checked = false;
  } else {
    document.getElementById("calendar-sync-modal-grid-checkbox-"+ code).checked = true;
  }
}

var calendarSyncTitleCode = "";
function calendarSyncModalAdd(b) {
  for (var gCheck = 0; gCheck < document.getElementsByClassName("calendar-sync-modal-grid-checkbox").length; gCheck++) {
    if (document.getElementsByClassName("calendar-sync-modal-grid-checkbox")[gCheck].checked) {
      calendarSyncTitleCode = document.getElementsByClassName("calendar-sync-modal-grid-checkbox")[gCheck].value;
      if (b.value == "editor") {
        if (!document.getElementById("c-s-wrp-"+ calendarSyncTitleCode)) {
          editorCalendarSyncRenderForm(
            calendarSyncTitleCode,
            document.getElementById("calendar-sync-modal-grid-blck-about-title-"+ calendarSyncTitleCode).innerHTML,
            document.getElementById("calendar-sync-modal-grid-blck-about-logo-"+ calendarSyncTitleCode).innerHTML,
            document.getElementById("calendar-sync-modal-grid-blck-about-url-"+ calendarSyncTitleCode).innerHTML,
            document.getElementById("calendar-sync-modal-grid-blck-about-ical-link-txt-"+ calendarSyncTitleCode).innerHTML
          );
        }
      } else {
        if (!document.getElementById("n-c-calendar-sync-wrp-"+ calendarSyncTitleCode)) {
          ncCalendarSyncRenderForm(
            calendarSyncTitleCode,
            document.getElementById("calendar-sync-modal-grid-blck-about-title-"+ calendarSyncTitleCode).innerHTML,
            document.getElementById("calendar-sync-modal-grid-blck-about-logo-"+ calendarSyncTitleCode).innerHTML,
            document.getElementById("calendar-sync-modal-grid-blck-about-url-"+ calendarSyncTitleCode).innerHTML,
            document.getElementById("calendar-sync-modal-grid-blck-about-ical-link-txt-"+ calendarSyncTitleCode).innerHTML
          );
        }
      }
    }
  }
  calendarSyncModal("hide", "");
}
