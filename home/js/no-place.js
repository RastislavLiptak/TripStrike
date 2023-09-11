window.addEventListener('load', function(e) {
  noPlaceOptimizeTitle();
});

window.addEventListener('resize', function(e) {
  noPlaceOptimizeTitle();
});

var titleHeight, windowHeight, percentageSize, overSize, titleFontSize;
function noPlaceOptimizeTitle() {
  document.getElementById("home-no-place-title").style.fontSize = "";
  titleHeight = document.getElementById("home-no-place-title").offsetHeight;
  windowHeight = window.innerHeight;
  percentageSize = titleHeight * 100 / windowHeight;
  overSize = percentageSize - 30;
  if (overSize > 0) {
    if (overSize > 50) {
      overSize = 50;
    }
    overSize = 60 * overSize / 100;
    titleFontSize = 60 - overSize;
    document.getElementById("home-no-place-title").style.fontSize = titleFontSize +"px";
  }
}
