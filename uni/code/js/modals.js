var modTime, modDelayResetTime;
var modDelay = false;
var showModalsArr = [];
var hideModalsArr = [];
var showModalsArrSize, hideModalsArrSize;
function modCover(task, id) {
  if (!modDelay) {
    modDelay = true;
    var blockExist = false;
    if (document.getElementById(id +"-blck")) {
      blockExist = true;
    }
    if (task == "show") {
      pageScrollBlock(true, id);
      document.getElementById(id).style.display = "table";
      if (blockExist) {
        document.getElementById(id +"-blck").style.display = "table";
      }
      clearTimeout(modTime);
      modTime = setTimeout(function(){
        document.getElementById(id).style.backgroundColor = "rgba(0,0,0,0.65)";
        if (blockExist) {
          document.getElementById(id +"-blck").style.opacity = "1";
        }
      }, 10);
    } else {
      pageScrollBlock(false, id);
      clearTimeout(modTime);
      modTime = setTimeout(function(){
        document.getElementById(id).style.display = "none";
        if (blockExist) {
          document.getElementById(id +"-blck").style.display = "none";
        }
      }, 160);
      document.getElementById(id).style.backgroundColor = "rgba(0,0,0,0)";
      if (blockExist) {
        document.getElementById(id +"-blck").style.opacity = "0";
      }
    }
    clearTimeout(modDelayResetTime);
    modDelayResetTime = setTimeout(function(){
      modCoverArrayHandler(task, id, "remove");
      modDelay = false;
      showModalsArrSize = showModalsArr.length;
      hideModalsArrSize = hideModalsArr.length;
      if (showModalsArrSize > 0 || hideModalsArrSize > 0) {
        if (showModalsArrSize < hideModalsArrSize) {
          modCover("hide", hideModalsArr[0]);
        } else {
          modCover("show", showModalsArr[0]);
        }
      }
    }, 160);
  } else {
    modCoverArrayHandler(task, id, "add");
  }
}

var modal_index;
function modCoverArrayHandler(arr, id, tsk) {
  if (tsk == "add") {
    if (arr == "show") {
      showModalsArr.push(id);
    } else {
      hideModalsArr.push(id);
    }
  } else if (tsk == "remove") {
    if (arr == "show") {
      modal_index = showModalsArr.indexOf(id);
      if (modal_index > -1) {
        showModalsArr.splice(modal_index, 1);
      }
    } else {
      modal_index = hideModalsArr.indexOf(id);
      if (modal_index > -1) {
        hideModalsArr.splice(modal_index, 1);
      }
    }
  }
}
