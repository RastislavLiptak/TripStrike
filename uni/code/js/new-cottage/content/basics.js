window.addEventListener('resize', function() {
  nCBasicsPlcTitleResize(document.getElementById("n-c-input-text-title"));
}, false);

function nCBasicsTitleWidth() {
  if (document.getElementsByClassName("n-c-basics-row-title-wrp")[0].offsetWidth <= document.getElementsByClassName("n-c-basics-row-title-wrp")[1].offsetWidth) {
    document.getElementsByClassName("n-c-basics-row-title-wrp")[0].style.width = document.getElementsByClassName("n-c-basics-row-title-wrp")[1].offsetWidth +"px";
  } else if (document.getElementsByClassName("n-c-basics-row-title-wrp")[0].offsetWidth >= document.getElementsByClassName("n-c-basics-row-title-wrp")[1].offsetWidth) {
    document.getElementsByClassName("n-c-basics-row-title-wrp")[1].style.width = document.getElementsByClassName("n-c-basics-row-title-wrp")[0].offsetWidth +"px";
  }
  nCBasicsTitleInputResize();
}

function nCBasicsPlcTitleResize(i) {
  i.style.width = "";
  i.style.height = "";
  document.getElementById("n-c-input-width-resize-txt-title").innerHTML = i.value;
  var newW = document.getElementById("n-c-input-width-resize-wrp-title").offsetWidth +1; //1px is random number
  if (newW > document.getElementById("n-c-textarea-description").offsetWidth) {
    newW = document.getElementById("n-c-textarea-description").offsetWidth;
  }
  i.style.width = newW +"px";
  var newH = i.scrollHeight +1; //1px is for border width
  i.style.height = newH +"px";
  nCBasicsTitleInputResize();
}

function nCBasicsTitleInputResize() {
  document.getElementById("n-c-input-text-title").style.minWidth = "";
  if (document.getElementById("n-c-input-text-title").offsetWidth > document.getElementById("n-c-textarea-description").offsetWidth) {
    document.getElementById("n-c-input-text-title").style.minWidth = document.getElementById("n-c-textarea-description").offsetWidth +"px";
    document.getElementById("n-c-input-text-title").style.maxWidth = document.getElementById("n-c-textarea-description").offsetWidth +"px";
  }
}

function ncBasicsReset() {
  document.getElementById("n-c-input-text-title").value = "";
  document.getElementById("n-c-textarea-description").value = "";
  nCBasicsPlcTitleResize(document.getElementById("n-c-input-text-title"));
}
