var setUpStepByStepSliderDragSts = false;
var setUpStepByStepSliderDragID, setUpStepByStepSliderDragTime;
function setUpStepByStepSliderFncMouseDown(id, event) {
  setUpStepByStepSliderDragSts = true;
  setUpStepByStepSliderDragID = id;
  clearTimeout(setUpStepByStepSliderDragTime);
  setUpStepByStepSliderDragTime = setTimeout(function(){
    setUpStepByStepSliderFncSetProgress(id, setUpStepByStepSliderFncCalc(id, event.pageX));
  }, 150);
}

window.addEventListener('load', function() {
  window.addEventListener('mousemove', function(event) {
    if (setUpStepByStepSliderDragSts) {
      setUpStepByStepSliderFncSetProgress(setUpStepByStepSliderDragID, setUpStepByStepSliderFncCalc(setUpStepByStepSliderDragID, event.pageX));
    }
  }, false);
  window.addEventListener('mouseup', function(event) {
    if (setUpStepByStepSliderDragSts) {
      setUpStepByStepSliderDragSts = false;
      clearTimeout(setUpStepByStepSliderDragTime);
      setUpStepByStepSliderFncCorrect(setUpStepByStepSliderDragID, event.pageX);
      setUpStepByStepSliderDragID = "";
    }
  }, false);
}, false);

function setUpStepByStepSliderFncCalc(id, pgX) {
  var frLeft = pgX - document.getElementById("set-up-step-by-step-content-slider-bar-"+ id).offsetLeft;
  var newPerc = (frLeft / document.getElementById("set-up-step-by-step-content-slider-bar-"+ id).offsetWidth) * 100;
  if (newPerc > 100) {
    newPerc = 100;
  }
  if (newPerc < 0) {
    newPerc = 0
  }
  return newPerc;
}

function setUpStepByStepSliderFncCorrect(id, pgX) {
  var tempPerc = setUpStepByStepSliderFncCalc(id, pgX);
  if (tempPerc < 12.5) {
    tempPerc = 0;
  } else if (tempPerc < 37.5) {
    tempPerc = 25;
  } else if (tempPerc < 63.5) {
    tempPerc = 50;
  } else if (tempPerc < 88.5) {
    tempPerc = 75;
  } else {
    tempPerc = 100;
  }
  setUpStepByStepSliderFncSetProgress(id, tempPerc);
}

function setUpStepByStepSliderFncHappySadClick(id, tsk) {
  var currPerc = parseInt(document.getElementById("set-up-step-by-step-content-slider-about-"+ id +"-progress").innerHTML);
  if (tsk == "happy") {
    currPerc = currPerc + 25;
  } else {
    currPerc = currPerc - 25;
  }
  if (currPerc > 100) {
    currPerc = 100;
  }
  if (currPerc < 0) {
    currPerc = 0
  }
  setUpStepByStepSliderFncSetProgress(id, currPerc);
}

function setUpStepByStepSliderFncSetProgress(id, perc) {
  document.getElementById("set-up-step-by-step-content-slider-about-"+ id +"-progress").innerHTML = perc;
  document.getElementById("set-up-step-by-step-content-slider-progress-"+ id).style.width = perc +"%";
  for (var rT = 0; rT < document.getElementsByClassName("set-up-step-by-step-content-slider-txt-"+ id).length; rT++) {
    if (document.getElementsByClassName("set-up-step-by-step-content-slider-txt-"+ id)[rT].classList.contains("set-up-step-by-step-content-slider-txt-selected")) {
      document.getElementsByClassName("set-up-step-by-step-content-slider-txt-"+ id)[rT].classList.remove("set-up-step-by-step-content-slider-txt-selected");
    }
  }
  if (perc < 12.5) {
    document.getElementsByClassName("set-up-step-by-step-content-slider-txt-"+ id)[0].classList.add("set-up-step-by-step-content-slider-txt-selected");
  } else if (perc < 37.5) {
    document.getElementsByClassName("set-up-step-by-step-content-slider-txt-"+ id)[1].classList.add("set-up-step-by-step-content-slider-txt-selected");
  } else if (perc < 63.5) {
    document.getElementsByClassName("set-up-step-by-step-content-slider-txt-"+ id)[2].classList.add("set-up-step-by-step-content-slider-txt-selected");
  } else if (perc < 88.5) {
    document.getElementsByClassName("set-up-step-by-step-content-slider-txt-"+ id)[3].classList.add("set-up-step-by-step-content-slider-txt-selected");
  } else {
    document.getElementsByClassName("set-up-step-by-step-content-slider-txt-"+ id)[4].classList.add("set-up-step-by-step-content-slider-txt-selected");
  }
}

function setUpStepByStepSliderFncReset() {
  while (document.getElementsByClassName("set-up-step-by-step-content-selected")[0]) {
    document.getElementsByClassName("set-up-step-by-step-content-selected")[0].classList.remove("set-up-step-by-step-content-selected");
  }
}
