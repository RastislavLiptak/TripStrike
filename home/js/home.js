window.addEventListener('load', function() {
  homeSliderControlsManager();
}, false);

window.addEventListener('resize', function() {
  homeSliderControlsManager();
}, false);


function homeSliderControlsManager() {
  for (var s = 0; s < document.getElementsByClassName("home-links-row-slider").length; s++) {
    homeSliderScrollCheck(document.getElementsByClassName("home-links-row-slider-btn-left")[s].value);
  }
}

function homeSliderScrollCheck(id) {
  if (document.getElementById("home-links-row-slider-"+ id).scrollWidth > document.getElementById("home-links-row-slider-"+ id).offsetWidth) {
    if (document.getElementById("home-links-row-slider-"+ id).scrollLeft <= 0) {
      homeSliderControlsBtnHandler(id, "left", "hide");
      homeSliderControlsBtnHandler(id, "right", "show");
    } else if (document.getElementById("home-links-row-slider-"+ id).scrollLeft >= document.getElementById("home-links-row-slider-"+ id).scrollWidth - document.getElementById("home-links-row-slider-"+ id).offsetWidth) {
      homeSliderControlsBtnHandler(id, "left", "show");
      homeSliderControlsBtnHandler(id, "right", "hide");
    } else {
      homeSliderControlsBtnHandler(id, "left", "show");
      homeSliderControlsBtnHandler(id, "right", "show");
    }
  } else {
    homeSliderControlsBtnHandler(id, "left", "hide");
    homeSliderControlsBtnHandler(id, "right", "hide");
  }
}

function homeSliderControlsBtnHandler(id, side, tsk) {
  if (tsk == "show") {
    document.getElementById("home-links-row-slider-btn-wrp-"+ side +"-"+ id).style.display = "flex";
  } else {
    document.getElementById("home-links-row-slider-btn-wrp-"+ side +"-"+ id).style.display = "none";
  }
}

function homeNewBooking(e, id) {
  e.preventDefault();
  location.href = '../place/?id='+ id +'&instantBooking=true';
}

function homeEditor(e, id) {
  e.preventDefault();
  location.href = '../editor/?id='+ id;
}
