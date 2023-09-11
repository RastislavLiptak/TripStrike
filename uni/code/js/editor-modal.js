function toggEditorModal(tsk) {
  if (tsk == "show") {
    accountDrop('hide');
    modCover('show', 'modal-cover-editor-modal');
    if (document.getElementById("main-menu-btn").value != "none") {
      menu();
    }
    loadEditModalContentData();
  } else {
    loadEditModalContentDataCancel();
    editModalContentReset();
    modCover('hide', 'modal-cover-editor-modal');
    document.getElementById("e-main-no-content").style.display = "";
  }
}

var xhrEditModal, lastId;
var editModalDataReady = true;
var arrEditLinksIds = [];
function loadEditModalContentData() {
  if (editModalDataReady && editModalDataReady != "no-more") {
    editModalDataReady = false;
    if (typeof arrEditLinksIds !== 'undefined' && arrEditLinksIds.length > 0) {
      lastId = arrEditLinksIds[arrEditLinksIds.length -1];
      editModalLoadMoreStatus("loading");
    } else {
      lastId = "";
      editModalMainLoaderHandler("show");
    }
    xhrEditModal = new XMLHttpRequest();
    xhrEditModal.onreadystatechange = function() {
      if (xhrEditModal.readyState == 4 && xhrEditModal.status == 200) {
        if (testJSON(xhrEditModal.response)) {
          var json = JSON.parse(xhrEditModal.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "link") {
                arrEditLinksIdsHandler(json[key]["id"]);
                plcBlckRender(json[key]["wrdEdit"], json[key]["id"], json[key]["name"], json[key]["src"]);
                moveEditModalLoadMoreBtn();
              } else if (json[key]["type"] == "load-next-sts") {
                editModalLoadMoreStatus("show");
                if (json[key]["all"] == 0) {
                  document.getElementById("e-main-no-content").style.display = "flex";
                  editModalDataReady = "no-more";
                  editModalLoadMoreStatus("hide");
                } else if (json[key]["progress"] == 0 || json[key]["remain"] == 0) {
                  editModalDataReady = "no-more";
                  editModalLoadMoreStatus("hide");
                }
              } else {
                editModalError(json[key]["error"], "");
                editModalLoadMoreStatus("auto");
              }
            }
          }
          if (editModalDataReady != "no-more") {
            editModalDataReady = true;
          }
        } else {
          if (editModalDataReady != "no-more") {
            editModalDataReady = true;
          }
          editModalError("json-error", xhrEditModal.response);
          editModalLoadMoreStatus("auto");
        }
        editModalMainLoaderHandler("hide");
      }
    }
    xhrEditModal.open("POST", "../uni/code/php-backend/editor-places-list.php");
    xhrEditModal.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrEditModal.send("lastId="+ lastId);
  }
}

function loadEditModalContentDataCancel() {
  if (editModalDataReady != "no-more") {
    editModalDataReady = true;
  }
  xhrEditModal.abort();
}

var editLinksArr, editLinksArrLength;
function editModalContentReset() {
  arrEditLinksIds = [];
  editLinksArr = document.getElementsByClassName("e-plc-blck");
  editLinksArrLength = editLinksArr.length;
  for (var eL = 0; eL < editLinksArrLength; eL++) {
    editLinksArr[0].parentNode.removeChild(editLinksArr[0]);
  }
  editModalDataReady = true;
}

function arrEditLinksIdsHandler(id) {
  arrEditLinksIds.push(id);
}

function editModalMainLoaderHandler(tsk) {
  if (tsk == "hide") {
    document.getElementById("e-main-loader-wrp").style.display = "none";
  } else {
    document.getElementById("e-main-loader-wrp").style.display = "";
  }
}

var editModalErrorNextStep;
function editModalError(code, val) {
  editModalErrorReset();
  modCover('show', 'modal-cover-editor-modal-error');
  if (code == "json-error") {
    editModalErrorNextStep = "code-error";
    document.getElementById("e-m-error-txt-1").style.display = "block";
    document.getElementById("e-m-error-code-txt").innerHTML = code +"<br>"+ val;
  } else if (code == "last-cottage-n-found" || code == "not-signed-in" || code == "id-n-found") {
    editModalErrorNextStep = "data-error";
    document.getElementById("e-m-error-txt-2").style.display = "block";
    document.getElementById("e-m-error-code-txt").innerHTML = code;
  } else {
    editModalErrorNextStep = "unknown-error";
    document.getElementById("e-m-error-txt-3").style.display = "block";
    document.getElementById("e-m-error-code-txt").innerHTML = code;
  }
}

function editModalErrorReset() {
  editModalErrorNextStep = "";
  document.getElementById("e-m-error-code-txt").innerHTML = "";
  for (var e = 0; e < document.getElementsByClassName("e-error-txt").length; e++) {
    document.getElementsByClassName("e-error-txt")[e].style.display = "";
  }
}

function editorModalHide() {
  editModalErrorReset();
  modCover('hide', 'modal-cover-editor-modal-error');
  if (document.getElementsByClassName("e-plc-blck").length == 0) {
    toggEditorModal("hide");
  }
}

var btnClone, moreBtn;
function moveEditModalLoadMoreBtn() {
  moreBtn = document.getElementById("e-load-more-btn-wrp");
  btnClone = moreBtn.cloneNode(true);
  moreBtn.parentNode.removeChild(moreBtn);
  document.getElementById("e-grid").appendChild(btnClone);
}

var editModalMoreBtnSts = false;
function editModalLoadMoreStatus(sts) {
  var lMWrp = document.getElementById("e-load-more-btn-wrp");
  var lMBtn = document.getElementById("e-load-more-btn");
  if (sts == "loading") {
    lMWrp.style.display = "flex";
    lMBtn.style.cursor = "default";
    lMBtn.style.backgroundImage = "url('../uni/gifs/loader-tail.svg')";
    lMBtn.style.backgroundSize = "70%";
  } else if (sts == "show") {
    editModalMoreBtnSts = true;
    lMWrp.style.display = "flex";
    lMBtn.style.cursor = "";
    lMBtn.style.backgroundImage = "";
    lMBtn.style.backgroundSize = "";
  } else if (sts == "hide") {
    editModalMoreBtnSts = false;
    lMWrp.style.display = "";
    lMBtn.style.cursor = "";
    lMBtn.style.backgroundImage = "";
    lMBtn.style.backgroundSize = "";
  } else if (sts == "auto") {
    if (editModalMoreBtnSts) {
      editModalLoadMoreStatus("show");
    } else {
      editModalLoadMoreStatus("hide");
    }
  }
}

function editorModalReset() {
  editModalErrorReset();
  modCover('hide', 'modal-cover-editor-modal-error');
  if (editModalErrorNextStep == "data-error") {
    editModalContentReset();
  }
  loadEditModalContentData();
}

function plcBlckRender(wrd_edit, id, name, src) {
  var blck = document.createElement("div");
  blck.setAttribute("class", "e-plc-blck");
  var btn = document.createElement("button");
  btn.setAttribute("class", "e-plc-btn");
  btn.setAttribute("onclick", "editorClick('"+ id +"')");
  var wrp = document.createElement("div");
  wrp.setAttribute("class", "e-plc-wrp");
  var imgWrp = document.createElement("div");
  imgWrp.setAttribute("class", "e-plc-img-wrp");
  var img = document.createElement("div");
  img.setAttribute("class", "e-plc-img");
  if (src != "#") {
    img.style.backgroundImage = "url(../"+ src +")";
  }
  var txtWrp = document.createElement("div");
  txtWrp.setAttribute("class", "e-plc-txt-wrp");
  var titleWrp = document.createElement("div");
  titleWrp.setAttribute("class", "e-plc-txt-title-wrp");
  var title = document.createElement("p");
  title.setAttribute("class", "e-plc-txt-title");
  title.innerHTML = name;
  var editWrp = document.createElement("div");
  editWrp.setAttribute("class", "e-plc-edit-wrp");
  var editTxt = document.createElement("div");
  editTxt.setAttribute("class", "e-plc-edit-txt");
  editTxt.innerHTML = wrd_edit;
  var editArrow = document.createElement("div");
  editArrow.setAttribute("class", "e-plc-edit-arrow");
  blck.appendChild(btn);
  btn.appendChild(wrp);
  wrp.appendChild(imgWrp);
  imgWrp.appendChild(img);
  wrp.appendChild(txtWrp);
  txtWrp.appendChild(titleWrp);
  titleWrp.appendChild(title);
  txtWrp.appendChild(editWrp);
  editWrp.appendChild(editTxt);
  editWrp.appendChild(editArrow);
  document.getElementById("e-grid").appendChild(blck);
}

function editorClick(id) {
  location.href = "../editor/?id="+ id;
}
