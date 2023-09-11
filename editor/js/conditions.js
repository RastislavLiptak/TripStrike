window.addEventListener("resize", function(){
  editorConditionsLangListControlBtnsManager();
});

function editorConditionsOfStayLangListLoaderHandler(display) {
  document.getElementById("editor-content-conditions-lang-slider-loader").style.display = display;
}

var langListSelectedSts, langListScroll;
function editorConditionsLangListScrollToSelected() {
  langListSelectedSts = false;
  langListScroll = 0;
  for (var b = 0; b < document.getElementsByClassName("conditions-of-stay-lang-select-btn").length; b++) {
    if (langListSelectedSts) {
      langListScroll = langListScroll + document.getElementsByClassName("conditions-of-stay-lang-select-btn")[b].offsetWidth;
    }
    if (document.getElementsByClassName("conditions-of-stay-lang-select-btn")[b].classList.contains("conditions-of-stay-lang-select-btn-editor-selected")) {
      langListSelectedSts = true;
    }
  }
  document.getElementById("editor-content-conditions-lang-slider").scrollLeft = langListScroll * (-1);
}

var condiLangBtnsShown = false;
function editorConditionsLangListControlBtnsManager() {
  condiLangBtnsShown = false;
  if (document.getElementById("editor-content-conditions-lang-slider")) {
    if (document.getElementById("editor-content-conditions-lang-slider").scrollLeft <= (document.getElementById("editor-content-conditions-lang-slider").scrollWidth - document.getElementById("editor-content-conditions-lang-slider").clientWidth - 3) * (-1)) {
      document.getElementById("editor-content-conditions-lang-slider-btn-left").style.display = "";
    } else {
      condiLangBtnsShown = true;
      document.getElementById("editor-content-conditions-lang-slider-btn-left").style.display = "table";
    }
    if (document.getElementById("editor-content-conditions-lang-slider").scrollLeft >= -3) {
      document.getElementById("editor-content-conditions-lang-slider-btn-right").style.display = "";
    } else {
      condiLangBtnsShown = true;
      document.getElementById("editor-content-conditions-lang-slider-btn-right").style.display = "table";
    }
    if (condiLangBtnsShown) {
      document.getElementById("editor-content-conditions-lang-slider").style.justifyContent = "unset";
    } else {
      document.getElementById("editor-content-conditions-lang-slider").style.justifyContent = "";
    }
  }
}

var editorConditionsOfStaySlideTo;
function editorConditionsLangListControlBtnsScroll(tsk) {
  if (tsk == "left") {
    editorConditionsOfStaySlideTo = document.getElementById("editor-content-conditions-lang-slider").scrollLeft - (document.getElementById("editor-content-conditions-lang-slider").offsetWidth / 2);
  } else {
    editorConditionsOfStaySlideTo = document.getElementById("editor-content-conditions-lang-slider").scrollLeft + (document.getElementById("editor-content-conditions-lang-slider").offsetWidth / 2);
  }
  scrollTo(document.getElementById("editor-content-conditions-lang-slider"), editorConditionsOfStaySlideTo, 250);
}
