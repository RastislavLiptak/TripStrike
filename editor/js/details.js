function editorDetailsOperationOnchange(s) {
  editorDetailsOperationSelectReset();
  if (s.value == "summer") {
    document.getElementById("editor-details-options-grid-cell-operation-seasons-summer").style.display = "flex";
    document.getElementById("editor-details-options-grid-cell-operation-seasons-winter").style.display = "";
  } else if (s.value == "winter") {
    document.getElementById("editor-details-options-grid-cell-operation-seasons-summer").style.display = "";
    document.getElementById("editor-details-options-grid-cell-operation-seasons-winter").style.display = "flex";
  } else {
    document.getElementById("editor-details-options-grid-cell-operation-seasons-summer").style.display = "";
    document.getElementById("editor-details-options-grid-cell-operation-seasons-winter").style.display = "";
  }
}

function editorDetailsOperationSelectReset() {
  document.getElementById('editor-details-options-grid-cell-select-summer-from').value = document.getElementsByClassName("editor-details-options-grid-cell-option-summer-from")[2].value;
  document.getElementById('editor-details-options-grid-cell-select-summer-to').value = document.getElementsByClassName("editor-details-options-grid-cell-option-summer-to")[6].value;
  document.getElementById('editor-details-options-grid-cell-select-winter-from').value = document.getElementsByClassName("editor-details-options-grid-cell-option-winter-from")[6].value;
  document.getElementById('editor-details-options-grid-cell-select-winter-to').value = document.getElementsByClassName("editor-details-options-grid-cell-option-winter-to")[1].value;
}

function editorDetailsEquipmentRender(eqID, eqName, eqImg) {
  var wrp = document.createElement("div");
  wrp.setAttribute("class", "editor-details-equipment-block");
  wrp.setAttribute("id", "editor-details-equipment-block-"+ eqID);
  var remove = document.createElement("button");
  remove.setAttribute("class", "editor-details-equipment-block-remove");
  remove.setAttribute("value", eqID);
  remove.setAttribute("aria-label", "remove equipment");
  remove.setAttribute("onclick", "editorDetailsEquipmentRemoveBlck("+ eqID +")");
  var contWrp = document.createElement("div");
  contWrp.setAttribute("class", "editor-details-equipment-block-content");
  var img = document.createElement("div");
  img.setAttribute("class", "editor-details-equipment-block-img");
  img.setAttribute("id", "editor-details-equipment-block-img-"+ eqID);
  img.style.backgroundImage = "url('"+ eqImg +"')";
  var imgTxt = document.createElement("p");
  imgTxt.setAttribute("class", "editor-details-equipment-block-img-txt");
  imgTxt.setAttribute("id", "editor-details-equipment-block-img-txt-"+ eqID);
  imgTxt.innerHTML = eqImg.replace(/^.*[\\\/]/, '');
  var p = document.createElement("p");
  p.setAttribute("class", "editor-details-equipment-block-p");
  p.setAttribute("id", "editor-details-equipment-block-p-"+ eqID);
  p.innerHTML = eqName;
  wrp.appendChild(remove);
  wrp.appendChild(contWrp);
  contWrp.appendChild(img);
  img.appendChild(imgTxt);
  contWrp.appendChild(p);
  document.getElementById("editor-details-equipment-list").prepend(wrp);
}

var blcks, i, blckArr;
function editorDetailsEquipmentRemoveAllBlcks() {
  blckArr = [];
  blcks = document.getElementsByClassName("editor-details-equipment-block-remove");
  for (i = 0; i < blcks.length; i++) {
    blckArr.push(blcks[i].value);
  }
  if (typeof blckArr !== "undefined" && blckArr.length > 0) {
    for (a = 0; a < blckArr.length; a++) {
      editorDetailsEquipmentRemoveBlck(blckArr[a]);
    }
  }
}

function editorDetailsEquipmentRemoveBlck(num) {
  document.getElementById("editor-details-equipment-block-"+ num).parentNode.removeChild(document.getElementById("editor-details-equipment-block-"+ num));
}
