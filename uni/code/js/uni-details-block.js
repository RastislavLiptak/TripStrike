window.addEventListener('scroll', function(e) {
  detailsBlckScroll();
});

window.addEventListener('load', function(e) {
  detailsBlckScroll();
});

function detailsBlckScroll() {
  var top = (window.pageYOffset || document.scrollTop)  - (document.clientTop || 0);
  var h = document.getElementById("header").offsetHeight;
  var a = top * 0.0001 / h;
  if (isNaN(a)) {
    a = 1;
  } else if (a <= 0.95) {
    a = 0.95;
  }
  var blcks = document.getElementsByClassName("uni-details-block-style");
  for (var i = 0; i < blcks.length; i++) {
    blcks[i].style.backgroundColor = "rgba(41, 40, 46,"+ a +")";
  }
}
