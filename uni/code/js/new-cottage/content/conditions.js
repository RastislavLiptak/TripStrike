window.addEventListener("resize", function(){
  ncConditionsOfStayLangListControlBtnsManager();
});

function conditionsOfStayLangWrap(tsk) {
  if (tsk == "loader") {
    document.getElementById("n-c-conditions-of-stay-loader-wrp").style.display = "";
    document.getElementById("n-c-conditions-of-stay-content-wrp").style.display = "";
  } else if (tsk == "content") {
    document.getElementById("n-c-conditions-of-stay-loader-wrp").style.display = "none";
    document.getElementById("n-c-conditions-of-stay-content-wrp").style.display = "table";
  }
}

function ncConditionsOfStayLangListControlBtnsManager() {
  if (document.getElementById("n-c-conditions-of-stay-lang-slider").scrollLeft <= 3) {
    document.getElementById("n-c-conditions-of-stay-lang-slider-btn-left").style.display = "";
  } else {
    document.getElementById("n-c-conditions-of-stay-lang-slider-btn-left").style.display = "table";
  }
  if (document.getElementById("n-c-conditions-of-stay-lang-slider").scrollLeft >= document.getElementById("n-c-conditions-of-stay-lang-slider").scrollWidth - document.getElementById("n-c-conditions-of-stay-lang-slider").clientWidth - 3) {
    document.getElementById("n-c-conditions-of-stay-lang-slider-btn-right").style.display = "";
  } else {
    document.getElementById("n-c-conditions-of-stay-lang-slider-btn-right").style.display = "table";
  }
}

var ncConditionsOfStaySlideTo;
function ncConditionsOfStayLangListControlBtnsScroll(tsk) {
  if (tsk == "left") {
    ncConditionsOfStaySlideTo = document.getElementById("n-c-conditions-of-stay-lang-slider").scrollLeft - (document.getElementById("n-c-conditions-of-stay-lang-slider").offsetWidth / 2);
  } else {
    ncConditionsOfStaySlideTo = document.getElementById("n-c-conditions-of-stay-lang-slider").scrollLeft + (document.getElementById("n-c-conditions-of-stay-lang-slider").offsetWidth / 2);
  }
  scrollTo(document.getElementById("n-c-conditions-of-stay-lang-slider"), ncConditionsOfStaySlideTo, 250);
}

var numOfConditionsLang;
function ncConditionsOfStayReset() {
  document.getElementById("n-c-conditions-of-stay-textarea").value = "";
  document.getElementById("n-c-conditions-of-stay-textarea").disabled = true;
  document.getElementById("n-c-conditions-of-stay-lang-slider").scrollLeft = 0;
  numOfConditionsLang = document.getElementsByClassName("conditions-of-stay-lang-select-wrp-new-cottage").length;
  for (var l = 0; l < numOfConditionsLang; l++) {
    document.getElementsByClassName("conditions-of-stay-lang-select-wrp-new-cottage")[0].parentNode.removeChild(document.getElementsByClassName("conditions-of-stay-lang-select-wrp-new-cottage")[0]);
  }
}

function ncConditionsReset() {
  loadConditionOfStayData('new-cottage', '');
}
