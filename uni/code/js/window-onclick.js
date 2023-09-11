window.addEventListener('click', function(event) {
  if (!event.target.matches(".menu-btn") && !event.target.matches("#menu-wrap") && !event.target.matches("#menu-cancel-wrp") && !event.target.matches("#menu-content-wrp") && !event.target.matches(".menu-elmnt") && !event.target.matches(".menu-elmnt img") && !event.target.matches(".menu-elmnt p")) {
    if (document.getElementById("main-menu-btn").value != "none") {
      menu();
    }
  }
  if (!event.target.matches(".option-wrp") && !event.target.matches(".select-option-search")) {
    selectDrop(event, "unset");
  }
  if (document.getElementById("account-drop-wrp")) {
    if (!event.target.matches("#account-drop-btn") && !event.target.matches("#account-details-wrp") && !event.target.matches("#account-img") && !event.target.matches("#account-txt") && !event.target.matches("#account-drop-wrp") && !event.target.matches("#account-drop-blck") && !event.target.matches(".account-drop-btn")) {
      accountDrop("hide");
    }
  }
  if (document.getElementById("user-menu-btn")) {
    if (!event.target.matches("#user-menu-btn") && !event.target.matches("#user-menu-dropdown-wrp") && !event.target.matches(".user-menu-dropdown-btn")) {
      userMemu("hide");
    }
  }
  if (document.getElementById("r-d-guests-dropdown")) {
    if (!event.target.matches("#r-d-guests-btn") && !event.target.matches("#r-d-guests-btn-txt") && !event.target.matches("#r-d-guests-btn-icn") && !event.target.matches("#r-d-guests-dropdown") && !event.target.matches(".r-d-guests-dropdown-select") && !event.target.matches(".r-d-guests-dropdown-select-border")) {
      bookGuestsNumDropdown("hide");
    }
  }
  if (document.getElementById("modal-cover-book-summary")) {
    if (!event.target.matches(".book-summary-line-details-txt-more-btn") && !event.target.matches(".book-summary-line-details-txt-more-btn-txt") && !event.target.matches(".book-summary-line-details-txt-more-btn-arrow")) {
      bookSummaryDetailsMoreDropdown("hide", "", "");
    }
  }
  if (document.getElementById("editor-size")) {
    if (!event.target.matches(".m-e-c-f-dropdown-btn") && !event.target.matches(".m-e-c-f-dropdown-btn-txt") && !event.target.matches(".m-e-c-f-dropdown-btn-arrow")) {
      editorInputDateDropdownToggle("hide", "", "");
    }
  }
}, false);
