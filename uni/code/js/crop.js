window.addEventListener('resize', function(event) {
  cropCoverResize(2);
}, false);

var xhrInpt, xhrCrop;
function dropFile(event, id) {
  event.preventDefault();
  event.stopPropagation();
  document.getElementById("file" +id).files = event.dataTransfer.files;
}

function inputFile(input, id) {
  if (document.getElementById("file-" +id).value != "") {
    if (id == 2) {
      modCover("show", "modal-cover-prof-img");
    }
    cropLoader("loader", id);
    var file = input.files[0];
    if (file.size < 2000000) {
      var formData = new FormData();
      formData.append("file", file);
      formData.append("ratio", "1:1");
      xhrInpt = new XMLHttpRequest();
      xhrInpt.onreadystatechange = function() {
        if (xhrInpt.readyState == 4 && xhrInpt.status == 200) {
          if (testJSON(xhrInpt.response)) {
            var json = JSON.parse(xhrInpt.response);
            for (var key in json) {
              if (json.hasOwnProperty(key)) {
                var sts = json[key]["sts"];
                var msg = json[key]["msg"];
                if (sts == 1) {
                  document.getElementById("crop-img-" +id).setAttribute("src", "../media/crop/"+ msg +".jpeg");
                  cropLoader("content-wrp", id);
                } else {
                  inputFileError(msg, id);
                }
              }
            }
          } else {
            console.log("input-file-json-faild");
            inputFileError("json-error", id);
          }
        }
      }
      xhrInpt.open("POST", "../uni/code/php-backend/save-img-file.php");
      xhrInpt.send(formData);
    } else {
      inputFileError("upl-err-out-size", id);
    }
  } else {
    cropLoader("content", id);
  }
}

var cropTime;
var cropTime2;
var cropToolResetT;
function cropLoader(show, id) {
  document.getElementById("crop-final-" +id).removeAttribute("src");
  var err = document.getElementsByClassName("crop-error-" +id);
  for (var i = 0; i < err.length; i++) {
    err[i].style.display = "none";
  }
  clearTimeout(cropToolResetT);
  cropToolResetT = setTimeout(function(){
    cropCoverResize(id);
  }, 210);
  cropBtns(show, id);
  if (show == "loader") {
    document.getElementById("crop-loader-" +id).style.display = "table";
    clearTimeout(cropTime);
    cropTime = setTimeout(function(){
      document.getElementById("crop-loader-" +id).style.opacity = "1";
      document.getElementById("crop-content-" +id).style.opacity = "0";
    }, 10);
    clearTimeout(cropTime2);
    cropTime2 = setTimeout(function(){
      document.getElementById("crop-content-" +id).style.display = "none";
    }, 210);
  } else if (show == "content-def" || show == "content-wrp" || show == "content-crop") {
    document.getElementById("file-" +id).value = "";
    if (show == "content-def") {
      document.getElementById("crop-def-" +id).style.display = "flex";
      document.getElementById("crop-tool-wrap-" +id).style.display = "none";
      document.getElementById("crop-final-wrap-" +id).style.display = "none";
    } else if (show == "content-wrp") {
      document.getElementById("crop-def-" +id).style.display = "none";
      document.getElementById("crop-tool-wrap-" +id).style.display = "table";
      document.getElementById("crop-final-wrap-" +id).style.display = "none";
    } else if (show == "content-crop") {
      document.getElementById("crop-def-" +id).style.display = "none";
      document.getElementById("crop-tool-wrap-" +id).style.display = "none";
      document.getElementById("crop-final-wrap-" +id).style.display = "table";
    }
    document.getElementById("crop-content-" +id).style.display = "table";
    clearTimeout(cropTime);
    cropTime = setTimeout(function(){
      document.getElementById("crop-loader-" +id).style.opacity = "0";
      document.getElementById("crop-content-" +id).style.opacity = "1";
    }, 10);
    clearTimeout(cropTime2);
    cropTime2 = setTimeout(function(){
      document.getElementById("crop-loader-" +id).style.display = "none";
    }, 210);
  }
}

function cropCoverResize(id) {
  document.getElementById("crop-"+ id).style.height = "";
  document.getElementById("crop-"+ id).style.width = "";
  document.getElementById("crop-cover-top-" +id).style.height = "";
  document.getElementById("crop-cover-left-" +id).style.height = "";
  document.getElementById("crop-cover-left-" +id).style.width = "";
  document.getElementById("crop-cover-right-" +id).style.height = "";
  document.getElementById("crop-cover-right-" +id).style.width = "";
  document.getElementById("crop-cover-bottom-" +id).style.height = "";
}

var errId;
function inputFileError(code, id) {
  errId = "";
  if (code == "json-error") {
    errId = 1.1;
  } else if (code == "upl-err-type") {
    errId = 3;
  } else if (code == "upl-err-out-size") {
    errId = 4;
  } else if (code == "upl-err-sql-1" || code == "upl-err-sql-2" || code == "upl-err-sql-3" || code == "upl-err-sql-4" || code == "upl-err-sql-5" || code == "upl-err-move") {
    errId = 2.1;
  } else if (code.indexOf("file-error: ") !== -1) {
    errId = 6;
  } else if (code == "upl-err-proccess-copy-resampled" || code == "upl-err-proccess-fill" || code == "upl-err-proccess-copy" || code == "upl-err-proccess-final") {
    errId = 5.1;
  }
  cropErrorShow(id, errId);
}

var crpMoveSts = false;
var crpResizeSts = false;
window.addEventListener('mouseup', function(event) {
  cropMove("up");
  cropResize("none");
}, false);

var startX = "none";
var startY = "none";
var id;
function cropMove(direct, el) {
  if (direct == "down") {
    id = el;
    crpMoveSts = true;
  } else {
    startX = "none";
    startY = "none";
    id = "none";
    crpMoveSts = false;
  }
}

var crpResizeDirect, crpRatio;
function cropResize(direct, ratio, el) {
  if (direct == "1" || direct == "2" || direct == "3" || direct == "4") {
    crpResizeSts = true;
    crpResizeDirect = direct;
    crpRatio = ratio;
    id = el;
  } else {
    crpResizeSts = false;
    crpRatio = "none";
    id = "none";
  }
}

var crpH, crpW, cvrTopH, cvrLftH, cvrLftW, cvrRghtH, cvrRghtW, cvrBttmH, maxHeight, maxWidth, moveX, moveY, newTopH, newBttmH, wrpH, wrpW, newLftW, newRghtW, newLftH, newRghtH, curCrpH, curCrpW, newCrpH, newCrpW, restH, restW;
window.addEventListener('mousemove', function(event) {
  if (crpMoveSts || crpResizeSts) {
    var x = event.clientX;
    var y = event.clientY;
    if (startX == "none" || startY == "none") {
      startX = x;
      startY = y;
      crpH = document.getElementById("crop-"+ id).offsetHeight;
      crpW = document.getElementById("crop-"+ id).offsetWidth;
      cvrTopH = document.getElementById("crop-cover-top-" +id).offsetHeight;
      cvrLftW = document.getElementById("crop-cover-left-" +id).offsetWidth;
      cvrLftH = document.getElementById("crop-cover-left-" +id).offsetHeight;
      cvrRghtW = document.getElementById("crop-cover-right-" +id).offsetWidth;
      cvrRghtH = document.getElementById("crop-cover-right-" +id).offsetHeight;
      cvrBttmH = document.getElementById("crop-cover-bottom-" +id).offsetHeight;
      wrpH = document.getElementById("crop-block-wrap-" +id).offsetHeight;
      wrpW = document.getElementById("crop-block-wrap-" +id).offsetWidth;
      maxHeight = wrpH - crpH;
      maxWidth = wrpW - crpW;
    }
    moveX = x - startX;
    moveY = y - startY;
  }
  if (crpMoveSts) {
    newTopH = cvrTopH + moveY;
    newBttmH = cvrBttmH - moveY;
    newLftW = cvrLftW + moveX;
    newRghtW = cvrRghtW - moveX;
    if (newTopH > maxHeight) {
      newTopH = maxHeight;
    } else if (newTopH < 0) {
      newTopH = 0;
    }
    if (newBttmH > maxHeight) {
      newBttmH = maxHeight;
    } else if (newBttmH < 0) {
      newBttmH = 0;
    }
    if (newLftW > maxWidth) {
      newLftW = maxWidth;
    } else if (newLftW < 0) {
      newLftW = 0;
    }
    if (newRghtW > maxWidth) {
      newRghtW = maxWidth;
    } else if (newRghtW < 0) {
      newRghtW = 0;
    }
    document.getElementById("crop-cover-top-" +id).style.height = newTopH +"px";
    document.getElementById("crop-cover-bottom-" +id).style.height = newBttmH +"px";
    document.getElementById("crop-cover-left-" +id).style.width = newLftW +"px";
    document.getElementById("crop-cover-right-" +id).style.width = newRghtW +"px";
  } else if (crpResizeSts) {
    //current width and height by cursor position
    if (crpResizeDirect == 1) {
      curCrpH = crpH - moveY;
      curCrpW = crpW - moveX;
    } else if (crpResizeDirect == 2) {
      curCrpH = crpH - moveY;
      curCrpW = crpW + moveX;
    } else if (crpResizeDirect == 3) {
      curCrpH = crpH + moveY;
      curCrpW = crpW - moveX;
    } else if (crpResizeDirect == 4) {
      curCrpH = crpH + moveY;
      curCrpW = crpW + moveX;
    }
    //select ratio
    if (crpRatio == "1:1") {
      if (curCrpH < 50) {
        curCrpH = 50;
      }
      if (curCrpW < 50) {
        curCrpW = 50;
      }
      if (curCrpH > curCrpW) {
        newCrpH = curCrpH;
        newCrpW = curCrpH;
      } else {
        newCrpH = curCrpW;
        newCrpW = curCrpW;
      }
    } else {
      if (curCrpH < 1) {
        curCrpH = 1;
      }
      if (curCrpW < 1) {
        curCrpW = 1;
      }
    }
    //check if crop tool is not out of the border
    if (crpResizeDirect == 1) {
      restH = wrpH - newCrpH - cvrBttmH;
      restW = wrpW - newCrpW - cvrRghtW;
      if (restH < 0) {
        newCrpH = wrpH - cvrBttmH;
        newCrpW = wrpH - cvrBttmH;
      }
      if (restW < 0) {
        newCrpH = wrpW - cvrRghtW;
        newCrpW = wrpW - cvrRghtW;
      }
    } else if (crpResizeDirect == 2) {
      restH = wrpH - newCrpH - cvrBttmH;
      restW = wrpW - newCrpW - cvrLftW;
      if (restH < 0) {
        newCrpH = wrpH - cvrBttmH;
        newCrpW = wrpH - cvrBttmH;
      }
      if (restW < 0) {
        newCrpH = wrpW - cvrLftW;
        newCrpW = wrpW - cvrLftW;
      }
    } else if (crpResizeDirect == 3) {
      restH = wrpH - newCrpH - cvrTopH;
      restW = wrpW - newCrpW - cvrRghtW;
      if (restH < 0) {
        newCrpH = wrpH - cvrTopH;
        newCrpW = wrpH - cvrTopH;
      }
      if (restW < 0) {
        newCrpH = wrpW - cvrRghtW;
        newCrpW = wrpW - cvrRghtW;
      }
    } else if (crpResizeDirect == 4) {
      restH = wrpH - newCrpH - cvrTopH;
      restW = wrpW - newCrpW - cvrLftW;
      if (restH < 0) {
        newCrpH = wrpH - cvrTopH;
        newCrpW = wrpH - cvrTopH;
      }
      if (restW < 0) {
        newCrpH = wrpW - cvrLftW;
        newCrpW = wrpW - cvrLftW;
      }
    }
    //set height and width of cover elements
    if (crpResizeDirect == 1) {
      newBttmH = cvrBttmH;
      newRghtW = cvrRghtW;
      newTopH = wrpH - newBttmH - newCrpH;
      newLftW = wrpW - newRghtW - newCrpW;
    } else if (crpResizeDirect == 2) {
      newBttmH = cvrBttmH;
      newLftW = cvrLftW;
      newTopH = wrpH - newBttmH - newCrpH;
      newRghtW = wrpW - newLftW - newCrpW;
    } else if (crpResizeDirect == 3) {
      newTopH = cvrTopH;
      newRghtW = cvrRghtW;
      newBttmH = wrpH - newTopH - newCrpH;
      newLftW = wrpW - newRghtW - newCrpW;
    } else if (crpResizeDirect == 4) {
      newTopH = cvrTopH;
      newLftW = cvrLftW;
      newBttmH = wrpH - newTopH - newCrpH;
      newRghtW = wrpW - newLftW - newCrpW;
    }
    newLftH = newCrpH;
    newRghtH = newCrpH;
    newCrpH = newCrpH;
    newCrpW = newCrpW;
    document.getElementById("crop-"+ id).style.height = newCrpH +"px";
    document.getElementById("crop-"+ id).style.width = newCrpW +"px";
    document.getElementById("crop-cover-top-" +id).style.height = newTopH +"px";
    document.getElementById("crop-cover-bottom-" +id).style.height = newBttmH +"px";
    document.getElementById("crop-cover-left-" +id).style.width = newLftW +"px";
    document.getElementById("crop-cover-left-" +id).style.height = newLftH +"px";
    document.getElementById("crop-cover-right-" +id).style.width = newRghtW +"px";
    document.getElementById("crop-cover-right-" +id).style.height = newRghtH +"px";
  }
}, false);

var file, cropImgSetT;
var cropReady = true;
function crop(type, id) {
  if (cropReady) {
    cropReady = false;
    if (type == "profImg") {
      file = "prof-img-crop.php";
    }
    var imgName = document.getElementById("crop-img-" +id).src;
    imgName = imgName.replace(/^.*[\\\/]/, "");
    imgName = imgName.split(".").slice(0, -1).join(".");
    var crpLftStrt = document.getElementById("crop-cover-left-" +id).offsetWidth;
    var crpTopStrt = document.getElementById("crop-cover-top-" +id).offsetHeight;
    var crpWrpWidth = document.getElementById("crop-block-wrap-" +id).offsetWidth;
    var crpHeight = document.getElementById("crop-"+ id).offsetHeight;
    var crpWidth = document.getElementById("crop-"+ id).offsetWidth;
    xhrCrop = new XMLHttpRequest();
    xhrCrop.onreadystatechange = function() {
      if (xhrCrop.readyState == 4 && xhrCrop.status == 200) {
        if (testJSON(xhrCrop.response)) {
          var json = JSON.parse(xhrCrop.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              var sts = json[key]["sts"];
              var msg = json[key]["msg"];
              if (sts == 1) {
                if (id == 1) {
                  clearTimeout(cropImgSetT);
                  cropImgSetT = setTimeout(function(){
                    document.getElementById("crop-final-" +id).setAttribute("src", "../"+ msg);
                  }, 10);
                  cropLoader("content-crop", id);
                } else if (id == 2) {
                  setNewProfileImg(msg);
                }
              } else {
                cropErrorOutput(msg, id);
              }
              cropReady = true;
            }
          }
        } else {
          cropReady = true;
          console.log("crop-json-faild");
          cropErrorOutput("json-error", id);
        }
      }
    }
    xhrCrop.open("POST", "../uni/code/php-backend/img-crop/"+ file, true);
    xhrCrop.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrCrop.send("name="+ imgName +"&lftStrt="+ crpLftStrt +"&topStrt="+ crpTopStrt +"&wrpWidth="+ crpWrpWidth +"&cropH="+ crpHeight +"&cropW="+ crpWidth);
    cropLoader("loader", id);
  }
}

function cropErrorOutput(code, id) {
  errId = "";
  if (code == "json-error") {
    errId = 1.2;
  } else if (code == "upl-err-missing") {
    errId = 7;
  } else if (code == "upl-err-sql-1" || code == "upl-err-sql-2") {
    errId = 2.2;
  } else if (code == "upl-err-crop" || code == "upl-err-imagejpeg") {
    errId = 5.2;
  }
  cropErrorShow(id, errId);
}

function cropErrorShow(id, errId) {
  if (errId == 1.1 || errId == 3 || errId == 4 || errId == 2.1 || errId == 6 || errId == 5.1) {
    cropLoader("content-def", id);
    cropBtns("content-def", id);
  } else if (errId == 1.2 || errId == 7 || errId == 2.2 || errId == 5.2 || errId == 1.3 || errId == 8 || errId == 9 || errId == 10) {
    cropLoader("content-wrp", id);
    cropBtns("content-crop", id);
  }
  if (errId == 1.1 || errId == 1.2 || errId == 1.3) {
    errId = 1;
  } else if (errId == 2.1 || errId == 2.2) {
    errId = 2;
  } else if (errId == 5.1 || errId == 5.2) {
    errId = 2;
  }
  var elmnt = document.getElementsByClassName("crop-error-" +id);
  for (var i = 0; i < elmnt.length; i++) {
    elmnt[i].style.display = "none";
  }
  document.getElementById("crop-err-" +id+ "-" +errId).style.display = "block";
}

var cropBtnsT;
function cropBtns(show, id) {
  if (id == 1) {
    cropActive();
  }
  if (show == "loader" || show == "content-def" || show == "content-crop") {
    document.getElementById("crop-btn-" +id).style.opacity = "0";
    document.getElementById("crop-btn-" +id).style.margin = "0";
    document.getElementById("crop-btn-" +id).style.maxWidth = "0px";
    document.getElementById("crop-btn-" +id).style.padding = "0px";
    document.getElementById("crop-btn-" +id).style.borderWidth = "0px";
    document.getElementById("crop-btn-" +id).value = "off";
    if (show == "content-def" || show == "content-crop") {
      disableChangeCrop(false, id);
    } else if (show == "loader") {
      disableChangeCrop(true, id);
    }
    clearTimeout(cropBtnsT);
    cropBtnsT = setTimeout(function(){
      document.getElementById("crop-btn-" +id).style.display = "none";
    }, 170);
  } else if (show == "content-wrp") {
    document.getElementById("crop-btn-" +id).style.display = "table";
    clearTimeout(cropBtnsT);
    cropBtnsT = setTimeout(function(){
      document.getElementById("crop-btn-" +id).style.opacity = "1";
      document.getElementById("crop-btn-" +id).style.margin = "0 4px";
      document.getElementById("crop-btn-" +id).style.maxWidth = "300px";
      document.getElementById("crop-btn-" +id).style.padding = "5px 7px";
      document.getElementById("crop-btn-" +id).style.borderWidth = "1px";
      document.getElementById("crop-btn-" +id).value = "on";
      disableChangeCrop(false, id);
    }, 10);
  }
}

function disableChangeCrop(dis, id) {
  var elmnt = document.getElementById("crop-change-btn-" +id);
  var clss = "crop-change-btn-dis-" +id;
  if (dis) {
    document.getElementById("file-" +id).disabled = true;
    var arr = elmnt.className.split(" ");
    if (arr.indexOf(clss) == -1) {
      elmnt.className += " " + clss;
    }
  } else {
    document.getElementById("file-" +id).disabled = false;
    elmnt.classList.remove(clss);
  }
}

function resetCrop(id) {
  cropLoader("content-def", id);
  document.getElementById("crop-def-" +id).style.display = "none";
  cropBtns("content-def", id);
  if (xhrInpt != null) {
    xhrInpt.abort();
  }
  if (xhrCrop != null) {
    xhrCrop.abort();
  }
  if (id == 2) {
    setNewProfileImgReset();
  }
}
