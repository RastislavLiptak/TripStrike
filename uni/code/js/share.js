var url = window.location.href;
function share(tsk) {
  document.getElementById("share-copy-inpt").value = url;
  if (tsk == "show") {
    modCover("show", "modal-cover-share");
  } else {
    modCover("hide", "modal-cover-share");
  }
}

function shareInpt(el) {
  el.value = url;
}

function shareCopy() {
  var copyText = document.getElementById("share-copy-inpt");
  copyText.select();
  copyText.setSelectionRange(0, 99999)
  document.execCommand("copy");
}
