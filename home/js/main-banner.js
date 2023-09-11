window.addEventListener('load', function(event) {
  homeBannerAuto();
}, false);

var prevSlideN;
function homeBannerPrev(btn) {
  prevSlideN = btn.value -1;
  if (prevSlideN < 0) {
    prevSlideN = document.getElementsByClassName("h-banner-one-slide").length -1;
  }
  homeBannerPerformSlide(prevSlideN, "prev");
}

var nextSlideN;
function homeBannerNext(btn) {
  if (btn) {
    nextSlideN = btn.value * 1 + 1;
    if (nextSlideN > document.getElementsByClassName("h-banner-one-slide").length-1) {
      nextSlideN = 0;
    }
    homeBannerPerformSlide(nextSlideN, "next");
  }
}

var homeBannerSlideTime;
var homeBannerSlideReady = true;
function homeBannerPerformSlide(newNum, tsk) {
  if (document.getElementsByClassName("h-banner-one-slide").length > 0) {
    if (homeBannerSlideReady) {
      homeBannerSlideReady = false;
      homeBannerAuto();
      var oldNum = document.getElementsByClassName("h-banner-controls-btn")[0].value;
      if (tsk == "auto") {
        if (newNum > oldNum) {
          tsk = "next";
        } else {
          tsk = "prev";
        }
      }
      if (tsk == "next") {
        document.getElementById("h-banner-one-slide-"+ oldNum).classList.add("h-banner-one-slide-left");
        document.getElementById("h-banner-one-slide-"+ newNum).classList.add("h-banner-one-slide-right");
      } else {
        document.getElementById("h-banner-one-slide-"+ oldNum).classList.add("h-banner-one-slide-right");
        document.getElementById("h-banner-one-slide-"+ newNum).classList.add("h-banner-one-slide-left");
      }
      document.getElementById("h-banner-one-slide-"+ oldNum).style.width = "0%";
      document.getElementById("h-banner-one-slide-"+ newNum).style.width = "100%";
      document.getElementById("h-banner-controls-dot-"+ oldNum).classList.remove("h-banner-controls-dot-selected");
      document.getElementById("h-banner-controls-dot-"+ newNum).classList.add("h-banner-controls-dot-selected");
      homeBannerSlideTime = setTimeout(function(){
        document.getElementById("h-banner-one-slide-"+ oldNum).classList.remove("h-banner-one-slide-selected");
        document.getElementById("h-banner-one-slide-"+ newNum).classList.add("h-banner-one-slide-selected");
        document.getElementById("h-banner-one-slide-"+ oldNum).style.width = "";
        document.getElementById("h-banner-one-slide-"+ newNum).style.width = "";
        if (tsk == "next") {
          document.getElementById("h-banner-one-slide-"+ oldNum).classList.remove("h-banner-one-slide-left");
          document.getElementById("h-banner-one-slide-"+ newNum).classList.remove("h-banner-one-slide-right");
        } else {
          document.getElementById("h-banner-one-slide-"+ oldNum).classList.remove("h-banner-one-slide-right");
          document.getElementById("h-banner-one-slide-"+ newNum).classList.remove("h-banner-one-slide-left");
        }
        document.getElementsByClassName("h-banner-controls-btn")[0].value = newNum;
        document.getElementsByClassName("h-banner-controls-btn")[1].value = newNum;
        homeBannerSlideReady = true;
      }, 505);
    }
  }
}

var homeBannerAutoInterval;
function homeBannerAuto() {
  clearInterval(homeBannerAutoInterval);
  homeBannerAutoInterval = setInterval(function() {
    if (document.getElementById("h-banner-controls-btn-left")) {
      homeBannerNext(document.getElementById("h-banner-controls-btn-left"));
    }
  }, 15000);
}
