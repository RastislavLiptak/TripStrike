function plcEquipModal(tsk, sect) {
  if (tsk == "show") {
    plcEquipToolReset();
    if (sect == "new") {
      plcEquipToolsNewCheck();
    } else if (sect == "editor") {
      plcEquipToolsEditCheck();
    }
    document.getElementById("plc-tool-equipment-submit-btn").value = sect;
    document.getElementById("plc-tool-equipment-inpt").value = "";
    plcEquipToolAddKeyUp(document.getElementById("plc-tool-equipment-inpt"));
    modCover('show', 'modal-cover-plc-tool-equipment');
  } else {
    plcEquipToolReset();
    document.getElementById("plc-tool-equipment-inpt").value = "";
    plcEquipToolAddKeyUp(document.getElementById("plc-tool-equipment-inpt"));
    modCover('hide', 'modal-cover-plc-tool-equipment');
  }
}

var customEquiNum, checkedEquiNum;
function plcEquipToolReset() {
  document.getElementById("plc-tool-equipment-wrp").scrollTop = 0;
  customEquiNum = document.getElementsByClassName("plc-tool-equipment-btn-custom").length;
  for (var c = 0; c < customEquiNum; c++) {
    document.getElementsByClassName("plc-tool-equipment-btn-custom")[0].parentNode.removeChild(document.getElementsByClassName("plc-tool-equipment-btn-custom")[0]);
  }
  checkedEquiNum = document.getElementsByClassName("plc-tool-equipment-btn-check").length;
  for (var ch = 0; ch < checkedEquiNum; ch++) {
    document.getElementsByClassName("plc-tool-equipment-btn-check")[0].classList.remove("plc-tool-equipment-btn-check");
  }
  for (var chB = 0; chB < document.getElementsByClassName("plc-tool-equipment-checkbox").length; chB++) {
    document.getElementsByClassName("plc-tool-equipment-checkbox")[chB].checked = false;
  }
}

var plcEquipImgSrc, plcEquipCheckDone;
function plcEquipToolsNewCheck() {
  for (var i = 0; i < document.getElementsByClassName("n-c-equi-block-img-txt").length; i++) {
    plcEquipCheckDone = false;
    plcEquipImgSrc = document.getElementsByClassName("n-c-equi-block-img-txt")[i].textContent;
    for (var o = 0; o < document.getElementsByClassName("plc-tool-equipment-img").length; o++) {
      if (document.getElementsByClassName("plc-tool-equipment-img")[o].hasAttribute("src")) {
        if (plcEquipImgSrc == document.getElementsByClassName("plc-tool-equipment-img")[o].src.replace(/^.*[\\\/]/, '') && plcEquipImgSrc != "add7.svg") {
          plcEquipToolCheckBtn(o * 1 +1);
          plcEquipCheckDone = true;
        }
      }
    }
    if (!plcEquipCheckDone) {
      plcEquipToolAddNewRender(document.getElementsByClassName("n-c-equi-block-p")[i].textContent);
    }
  }
}

function plcEquipToolsEditCheck() {
  for (var i = 0; i < document.getElementsByClassName("editor-details-equipment-block-img-txt").length; i++) {
    plcEquipCheckDone = false;
    plcEquipImgSrc = document.getElementsByClassName("editor-details-equipment-block-img-txt")[i].textContent;
    for (var o = 0; o < document.getElementsByClassName("plc-tool-equipment-img").length; o++) {
      if (document.getElementsByClassName("plc-tool-equipment-img")[o].hasAttribute("src")) {
        if (plcEquipImgSrc == document.getElementsByClassName("plc-tool-equipment-img")[o].src.replace(/^.*[\\\/]/, '') && plcEquipImgSrc != "add7.svg") {
          plcEquipToolCheckBtn(o * 1 +1);
          plcEquipCheckDone = true;
        }
      }
    }
    if (!plcEquipCheckDone) {
      plcEquipToolAddNewRender(document.getElementsByClassName("editor-details-equipment-block-p")[i].textContent);
    }
  }
}

var pEChckbox;
function plcEquipToolCheckBtn(n) {
  pEChckbox = document.getElementById("plc-tool-equipment-checkbox-" +n);
  if (pEChckbox.checked == true){
    pEChckbox.checked = false;
  } else {
    pEChckbox.checked = true;
  }
  plcEquipToolCheckClick(n);
}

function plcEquipToolCheckClick(n) {
  pEChckbox = document.getElementById("plc-tool-equipment-checkbox-" +n);
  if (pEChckbox.checked == true){
    document.getElementById("plc-tool-equipment-btn-" +n).className = "plc-tool-equipment-btn plc-tool-equipment-btn-check";
  } else {
    document.getElementById("plc-tool-equipment-btn-" +n).className = "plc-tool-equipment-btn";
  }
}

function plcEquipToolAddKeyUp(e) {
  var addImg = document.getElementById("plc-tool-equipment-img-add");
  var addBtn = document.getElementById("plc-tool-equipment-add-btn");
  if (e.value == "") {
    addBtn.style.opacity = "";
    addBtn.style.cursor = "";
    addImg.style.backgroundImage = "";
  } else {
    addBtn.style.opacity = "1";
    addBtn.style.cursor = "pointer";
    addImg.style.backgroundImage = "url('../uni/icons/add4.svg')";
  }
}

var idNumSts, idNum, valNoSpace;
function plcEquipToolAddNew() {
  var inpt = document.getElementById("plc-tool-equipment-inpt");
  if (inpt.value == "") {
    inpt.focus();
  } else {
    valNoSpace = inpt.value.replace(/\s/g, "");
    if (valNoSpace != "") {
      plcEquipToolAddNewRender(inpt.value);
      inpt.value = "";
      plcEquipToolAddKeyUp(inpt);
    } else {
      inpt.focus();
    }
  }
}

function plcEquipToolAddNewRender(equiTxt) {
  idNumSts = false;
  while (!idNumSts) {
    idNum = Math.floor(Math.random() * 1001);
    if (!document.getElementById("plc-tool-equipment-btn-" +idNum)) {
      idNumSts = true;
    }
  }
  var btn = document.createElement("button");
  btn.setAttribute("class", "plc-tool-equipment-btn plc-tool-equipment-btn-custom plc-tool-equipment-btn-check ");
  btn.setAttribute("id", "plc-tool-equipment-btn-" +idNum);
  btn.setAttribute("value", equiTxt);
  btn.setAttribute("onclick", "plcEquipToolCheckBtn("+ idNum +")");
  var imgWrp = document.createElement("div");
  imgWrp.setAttribute("class", "plc-tool-equipment-img-wrp");
  var img = document.createElement("img");
  img.setAttribute("class", "plc-tool-equipment-img");
  img.setAttribute("id", "plc-tool-equipment-img-"+ idNum);
  img.setAttribute("alt", "new cottage benefit icon");
  img.setAttribute("src", "../uni/icons/add7.svg");
  var txtWrp = document.createElement("div");
  txtWrp.setAttribute("class", "plc-tool-equipment-txt-wrp");
  var txt = document.createElement("p");
  txt.setAttribute("class", "plc-tool-equipment-txt");
  txt.innerHTML = equiTxt;
  var chckWrp = document.createElement("div");
  chckWrp.setAttribute("class", "plc-tool-equipment-check-wrp");
  var label = document.createElement("label");
  label.setAttribute("class", "plc-tool-equipment-label");
  var checkbox = document.createElement("input");
  checkbox.setAttribute("type", "checkbox");
  checkbox.setAttribute("checked", "checked");
  checkbox.setAttribute("value", idNum);
  checkbox.setAttribute("class", "plc-tool-equipment-checkbox");
  checkbox.setAttribute("id", "plc-tool-equipment-checkbox-" +idNum);
  checkbox.setAttribute("onclick", "plcEquipToolCheckClick("+ idNum +")");
  var span = document.createElement("span");
  span.setAttribute("class", "plc-tool-equipment-checkmark");
  btn.appendChild(imgWrp);
  imgWrp.appendChild(img);
  btn.appendChild(txtWrp);
  txtWrp.appendChild(txt);
  btn.appendChild(chckWrp);
  chckWrp.appendChild(label);
  label.appendChild(checkbox);
  label.appendChild(span);
  document.getElementById("plc-tool-equipment-list").appendChild(btn);
  var addEquipBtnClone = document.getElementsByClassName("plc-tool-equipment-add")[0].cloneNode(true);
  document.getElementsByClassName("plc-tool-equipment-add")[0].parentNode.removeChild(document.getElementsByClassName("plc-tool-equipment-add")[0]);
  document.getElementById("plc-tool-equipment-list").appendChild(addEquipBtnClone);
  document.getElementById("plc-tool-equipment-inpt").value = "";
}

var equiListArr;
function plcEquipToolSubmit() {
  equiListArr = [];
  for (var chL = 0; chL < document.getElementsByClassName("plc-tool-equipment-checkbox").length; chL++) {
    if (document.getElementsByClassName("plc-tool-equipment-checkbox")[chL].checked == true) {
      equiListArr.push(document.getElementsByClassName("plc-tool-equipment-checkbox")[chL].value);
    }
  }
  if (document.getElementById("plc-tool-equipment-submit-btn").value == "new") {
    ncEquipRemoveAllBlcks();
    for (eA = 0; eA < equiListArr.length; eA++) {
      ncEquipRender(equiListArr[eA], document.getElementById("plc-tool-equipment-btn-"+ equiListArr[eA]).value, document.getElementById("plc-tool-equipment-img-"+ equiListArr[eA]).src);
    }
  } else if (document.getElementById("plc-tool-equipment-submit-btn").value == "editor") {
    editorDetailsEquipmentRemoveAllBlcks();
    for (eA = 0; eA < equiListArr.length; eA++) {
      editorDetailsEquipmentRender(equiListArr[eA], document.getElementById("plc-tool-equipment-btn-"+ equiListArr[eA]).value, document.getElementById("plc-tool-equipment-img-"+ equiListArr[eA]).src);
    }
  }
  plcEquipModal('hide', '');
}
