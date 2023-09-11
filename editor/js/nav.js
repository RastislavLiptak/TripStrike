window.addEventListener('load', function(event) {
  editorNavSliderBtnHandler();
}, false);

window.addEventListener('resize', function(event) {
  editorNavSliderBtnHandler();
}, false);

function editorNavSliderBtnHandler() {
  if (document.getElementById("editor-nav-scroll").scrollLeft <= 10) {
    document.getElementById("editor-nav-slider-button-wrp-left").style.display = "";
  } else {
    document.getElementById("editor-nav-slider-button-wrp-left").style.display = "block";
  }
  if (document.getElementById("editor-nav-scroll").scrollLeft >= document.getElementById("editor-nav-scroll").scrollWidth - document.getElementById("editor-nav-scroll").clientWidth - 10) {
    document.getElementById("editor-nav-slider-button-wrp-right").style.display = "";
  } else {
    document.getElementById("editor-nav-slider-button-wrp-right").style.display = "block";
  }
}

var editorNavScrollTo;
function editorNavSliderScroll(tsk) {
  if (tsk == "left") {
    editorNavScrollTo = document.getElementById("editor-nav-scroll").scrollLeft - (document.getElementById("editor-nav-scroll").offsetWidth / 2);
  } else {
    editorNavScrollTo = document.getElementById("editor-nav-scroll").scrollLeft + (document.getElementById("editor-nav-scroll").offsetWidth / 2);
  }
  scrollTo(document.getElementById("editor-nav-scroll"), editorNavScrollTo, 250);
}

var listOfSelectedNav = 0;
var listOfSelectedSect = 0;
function editorNavContent(cont) {
  if (document.getElementById("editor-content-"+ cont +"-wrp")) {
    listOfSelectedNav = document.getElementsByClassName("editor-nav-content-block-btn-selected").length;
    for (var sN = 0; sN < listOfSelectedNav; sN++) {
      document.getElementsByClassName("editor-nav-content-block-btn-selected")[0].classList.remove("editor-nav-content-block-btn-selected")
    }
    listOfSelectedSect = document.getElementsByClassName("editor-content-section-wrp-selected").length;
    for (var sS = 0; sS < listOfSelectedSect; sS++) {
      document.getElementsByClassName("editor-content-section-wrp-selected")[0].classList.remove("editor-content-section-wrp-selected")
    }
    document.getElementById("editor-nav-content-block-btn-"+ cont).classList.add("editor-nav-content-block-btn-selected");
    document.getElementById("editor-content-"+ cont +"-wrp").classList.add("editor-content-section-wrp-selected");
    optimizeAllTextboxToContent();
    editorConditionsLangListControlBtnsManager();
    if (document.getElementById("editor-footer-blck-btn-wrp")) {
      if (cont == "calendar") {
        document.getElementById("editor-footer-blck-btn-wrp").style.display = "none";
      } else {
        document.getElementById("editor-footer-blck-btn-wrp").style.display = "";
      }
    }
  }
}
