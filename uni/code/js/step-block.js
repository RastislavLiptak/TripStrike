function stepChange(id) {
  var stpBlock = document.getElementsByClassName("step-btn");
  for (var i = 0; i < stpBlock.length; ++i) {
    stpBlock[i].classList.remove("step-select");
  }
  var elmnt = document.getElementById(id);
  clss = "step-select";
  var arr = elmnt.className.split(" ");
  if (arr.indexOf(clss) == -1) {
    elmnt.className += " " + clss;
  }
}
