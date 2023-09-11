window.addEventListener("resize", function(){
  aboutBookingsProgressFormTextareaAdjust();
});

function aboutBookingsProgressFormTextareaAdjust() {
  for (var aBPFTA = 0; aBPFTA < document.getElementsByClassName("about-this-booking-form-textarea").length; aBPFTA++) {
    aboutBookingsProgressFormTextarea(document.getElementsByClassName("about-this-booking-form-textarea")[aBPFTA]);
  }
}

function aboutBookingsProgressFormTextarea(el) {
  if (el.value != "") {
    el.classList.remove("about-this-booking-form-textarea-empty");
    el.style.height = "";
    var newH = el.scrollHeight +1; //1px is for border width
    el.style.height = newH +"px";
  } else {
    el.classList.add("about-this-booking-form-textarea-empty");
  }
}

var lastSectionReady, bPT, totalWidth;
function aboutBookingsProgressSetScroll() {
  bPT = 0;
  lastSectionReady = false;
  if (document.getElementsByClassName("about-booking-progress-text-wrp").length > 0) {
    while (!lastSectionReady && bPT < document.getElementsByClassName("about-booking-progress-text-wrp").length) {
      if (document.getElementsByClassName("about-booking-progress-text-wrp")[bPT].classList.contains("about-booking-progress-section-done-last")) {
        lastSectionReady = true;
      }
      ++bPT;
    }
    if (bPT == 1) {
      totalWidth = 0;
    } else if (bPT == 2) {
      totalWidth = document.getElementsByClassName("about-booking-progress-text-wrp")[0].offsetWidth / 2;
    } else if (bPT == 3) {
      totalWidth = document.getElementsByClassName("about-booking-progress-text-wrp")[0].offsetWidth + document.getElementsByClassName("about-booking-progress-line-wrp")[0].offsetWidth + (document.getElementsByClassName("about-booking-progress-text-wrp")[1].offsetWidth / 2);
    } else if (bPT == 4) {
      totalWidth = document.getElementsByClassName("about-booking-progress-text-wrp")[0].offsetWidth + document.getElementsByClassName("about-booking-progress-line-wrp")[0].offsetWidth + document.getElementsByClassName("about-booking-progress-text-wrp")[1].offsetWidth + document.getElementsByClassName("about-booking-progress-line-wrp")[1].offsetWidth + (document.getElementsByClassName("about-booking-progress-text-wrp")[2].offsetWidth / 2);
    } else if (bPT == 5) {
      totalWidth = document.getElementsByClassName("about-booking-progress-text-wrp")[0].offsetWidth + document.getElementsByClassName("about-booking-progress-line-wrp")[0].offsetWidth + document.getElementsByClassName("about-booking-progress-text-wrp")[1].offsetWidth + document.getElementsByClassName("about-booking-progress-line-wrp")[1].offsetWidth + document.getElementsByClassName("about-booking-progress-text-wrp")[2].offsetWidth + document.getElementsByClassName("about-booking-progress-line-wrp")[2].offsetWidth + (document.getElementsByClassName("about-booking-progress-text-wrp")[3].offsetWidth / 2);
    }
    document.getElementById("about-booking-progress-slider").scrollLeft = totalWidth;
  }
}

function aboutBookingsBtnManager(task, id) {
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

var times_work, times_week, termCalcDone, calc_y, calc_m, calc_d, tempTotalPrice;
function aboutBookingChangeDataPriceCalc(priceMode, priceWork, priceWeek, fromD, fromM, fromY, toD, toM, toY, firstDay, lastDay, numOfGuests) {
  times_work = 0;
  times_week = 0;
  termCalcDone = false;
  if (
    fromD != "" &&
    fromM != "" &&
    fromY != "" &&
    toD != "" &&
    toM != "" &&
    toY != "" &&
    fromD != null &&
    fromM != null &&
    fromY != null &&
    toD != null &&
    toM != null &&
    toY != null
  ) {
    if (new Date(toY +"-"+ ("0"+ toM).slice(-2) +"-"+ ("0"+ toD).slice(-2)) > new Date(fromY +"-"+ ("0"+ fromM).slice(-2) +"-"+ ("0"+ fromD).slice(-2))) {
      if (firstDay != "half") {
        firstDay == "whole";
      }
      if (lastDay != "half") {
        lastDay == "whole";
      }
      calc_y = fromY;
      calc_m = fromM;
      calc_d = fromD;
      while (!termCalcDone) {
        if (calc_y == toY && calc_m == toM && calc_d == toD) {
          termCalcDone = true;
        } else {
          if (new Date(calc_y +"-"+ calc_m +"-"+ calc_d).getDay() <= 4) {
            ++times_work;
          } else {
            ++times_week;
          }
          ++calc_d;
          if (calc_d > new Date(calc_y, calc_m, 0).getDate()) {
            calc_d = 1;
            ++calc_m;
            if (calc_m > 12) {
              calc_m = 1;
              ++calc_y;
            }
          }
        }
      }
      if (firstDay == "whole") {
        if (new Date(fromY +"-"+ ("0"+ fromM).slice(-2) +"-"+ ("0"+ fromD).slice(-2)).getDay() == 0 || new Date(fromY +"-"+ ("0"+ fromM).slice(-2) +"-"+ ("0"+ fromD).slice(-2)).getDay() == 6) {
          ++times_week;
        } else {
          ++times_work
        }
      }
      if (lastDay == "whole") {
        if (new Date(toY +"-"+ ("0"+ toM).slice(-2) +"-"+ ("0"+ toD).slice(-2)).getDay() == 5 || new Date(toY +"-"+ ("0"+ toM).slice(-2) +"-"+ ("0"+ toD).slice(-2)).getDay() == 6) {
          ++times_week;
        } else {
          ++times_work
        }
      }
      tempTotalPrice = priceWork * times_work + priceWeek * times_week;
      if (priceMode == "guests") {
        tempTotalPrice = tempTotalPrice * numOfGuests;
      }
      return tempTotalPrice;
    } else {
      return "wrong-order";
    }
  } else {
    return "unset-values";
  }
}
