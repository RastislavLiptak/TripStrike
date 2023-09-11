function ncEquipRender(eqID, eqName, eqImg) {
  var wrp = document.createElement("div");
  wrp.setAttribute("class", "n-c-equi-block");
  wrp.setAttribute("id", "n-c-equi-block-"+ eqID);
  var remove = document.createElement("button");
  remove.setAttribute("class", "n-c-block-remove n-c-equi-block-remove");
  remove.setAttribute("value", eqID);
  remove.setAttribute("aria-label", "remove equipment");
  remove.setAttribute("onclick", "ncEquipRemoveBlck("+ eqID +")");
  var contWrp = document.createElement("div");
  contWrp.setAttribute("class", "n-c-equi-block-content");
  var img = document.createElement("div");
  img.setAttribute("class", "n-c-equi-block-img");
  img.setAttribute("id", "n-c-equi-block-img-"+ eqID);
  img.style.backgroundImage = "url('"+ eqImg +"')";
  var imgTxt = document.createElement("p");
  imgTxt.setAttribute("class", "n-c-equi-block-img-txt");
  imgTxt.setAttribute("id", "n-c-equi-block-img-txt-"+ eqID);
  imgTxt.innerHTML = eqImg.replace(/^.*[\\\/]/, '');
  var p = document.createElement("p");
  p.setAttribute("class", "n-c-equi-block-p");
  p.setAttribute("id", "n-c-equi-block-p-"+ eqID);
  p.innerHTML = eqName;
  wrp.appendChild(remove);
  wrp.appendChild(contWrp);
  contWrp.appendChild(img);
  img.appendChild(imgTxt);
  contWrp.appendChild(p);
  document.getElementById("n-c-equipment-list").prepend(wrp);
}

var blcks, i, blckArr;
function ncEquipRemoveAllBlcks() {
  blckArr = [];
  blcks = document.getElementsByClassName("n-c-equi-block-remove");
  for (i = 0; i < blcks.length; i++) {
    blckArr.push(blcks[i].value);
  }
  if (typeof blckArr !== "undefined" && blckArr.length > 0) {
    for (a = 0; a < blckArr.length; a++) {
      ncEquipRemoveBlck(blckArr[a]);
    }
  }
}

function ncEquipRemoveBlck(num) {
  document.getElementById("n-c-equi-block-"+ num).parentNode.removeChild(document.getElementById("n-c-equi-block-"+ num));
}

function ncDetailsIdCorrection() {
  document.getElementById("n-c-url-id-input").value = document.getElementById("n-c-url-id-input").value.replace(/[^a-z0-9]/gi, "");
}

var xhrNcDetailsID;
function ncDetailsGetID() {
  if (xhrNcDetailsID != null) {
    xhrNcDetailsID.abort();
  }
  xhrNcDetailsID = new XMLHttpRequest();
  xhrNcDetailsID.onreadystatechange = function() {
    if (xhrNcDetailsID.readyState == 4 && xhrNcDetailsID.status == 200) {
      if (testJSON(xhrNcDetailsID.response)) {
        var json = JSON.parse(xhrNcDetailsID.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "done") {
              document.getElementById("n-c-url-id-input").value = json[key]["id"];
            }
          }
        }
      } else {
        document.getElementById("n-c-error-txt-details").innerHTML = xhrNcDetailsID.response;
      }
    }
  }
  xhrNcDetailsID.open("POST", "../uni/code/php-backend/new-cottage/get-place-id.php");
  xhrNcDetailsID.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrNcDetailsID.send();
}

function ncDetailsOperationOnchange(s) {
  ncDetailsOperationSelectReset();
  if (s.value == "summer") {
    document.getElementById("n-c-select-blck-operation-season-select-from-summer").style.display = "flex";
    document.getElementById("n-c-select-blck-operation-season-select-to-summer").style.display = "flex";
    document.getElementById("n-c-select-blck-operation-season-select-from-winter").style.display = "";
    document.getElementById("n-c-select-blck-operation-season-select-to-winter").style.display = "";
  } else if (s.value == "winter") {
    document.getElementById("n-c-select-blck-operation-season-select-from-summer").style.display = "";
    document.getElementById("n-c-select-blck-operation-season-select-to-summer").style.display = "";
    document.getElementById("n-c-select-blck-operation-season-select-from-winter").style.display = "flex";
    document.getElementById("n-c-select-blck-operation-season-select-to-winter").style.display = "flex";
  } else {
    document.getElementById("n-c-select-blck-operation-season-select-from-summer").style.display = "";
    document.getElementById("n-c-select-blck-operation-season-select-to-summer").style.display = "";
    document.getElementById("n-c-select-blck-operation-season-select-from-winter").style.display = "";
    document.getElementById("n-c-select-blck-operation-season-select-to-winter").style.display = "";
  }
}

var ncDetailsNumOfEquip;
function ncDetailsReset() {
  document.getElementById('n-c-select-type').value = document.getElementsByClassName("n-c-select-option-type")[0].value;
  document.getElementById("n-c-input-number-details-bedrooms").value = "1";
  document.getElementById("n-c-input-number-details-guests").value = "1";
  document.getElementById("n-c-input-number-details-bathrooms").value = "1";
  document.getElementById("n-c-input-number-details-distance-from-the-water").value = "";
  document.getElementById('n-c-select-operation').value = document.getElementsByClassName("n-c-select-option-operation")[0].value;
  ncDetailsOperationSelectReset();
  ncDetailsNumOfEquip = document.getElementsByClassName("n-c-equi-block").length;
  for (var eD = 0; eD < ncDetailsNumOfEquip; eD++) {
    document.getElementsByClassName("n-c-equi-block")[0].parentNode.removeChild(document.getElementsByClassName("n-c-equi-block")[0]);
  }
  ncDetailsGetID();
}

var ncDetailsNumOperationSelect;
function ncDetailsOperationSelectReset() {
  document.getElementById("n-c-select-blck-operation-season-select-from-summer").style.display = "";
  document.getElementById("n-c-select-blck-operation-season-select-to-summer").style.display = "";
  document.getElementById("n-c-select-blck-operation-season-select-from-winter").style.display = "";
  document.getElementById("n-c-select-blck-operation-season-select-to-winter").style.display = "";
  document.getElementById('n-c-select-operation-from-summer').value = document.getElementsByClassName("n-c-select-option-operation-from-summer")[2].value;
  document.getElementById('n-c-select-operation-to-summer').value = document.getElementsByClassName("n-c-select-option-operation-to-summer")[6].value;
  document.getElementById('n-c-select-operation-from-winter').value = document.getElementsByClassName("n-c-select-option-operation-from-winter")[6].value;
  document.getElementById('n-c-select-operation-to-winter').value = document.getElementsByClassName("n-c-select-option-operation-to-winter")[1].value;
  ncDetailsNumOperationSelect = document.getElementsByClassName("n-c-select-blck-operation-season-select").length;
  for (var oD = 0; oD < ncDetailsNumOperationSelect; oD++) {
    document.getElementsByClassName("n-c-select-blck-operation-season-select")[oD].style.display = "";
  }
}
