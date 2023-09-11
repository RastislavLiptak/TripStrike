var featureNamesList = ["add-cottage", "edit-cottage", "add-comment", "add-rating", "no-fees"];
featureNamesList = featureNamesList.sort();

var wrd_newFeature = "New feature";
function addFeatureCodeDictionary(newFeature) {
  wrd_newFeature = newFeature;
}

function addFeatureCodeModal(tsk) {
  document.getElementById("b-d-add-feature-code-scroll-wrp").scrollTop = 0;
  document.getElementById("b-d-add-feature-code-content-input-code-checkbox-input").checked = false;
  document.getElementById("b-d-add-feature-code-content-input-code").value = "";
  addFeatureCodeInputLength(document.getElementById("b-d-add-feature-code-content-input-code"));
  addFeatureCodeInputCheckAvailability();
  document.getElementById("b-d-add-feature-code-content-input-user").value = "";
  document.getElementById("b-d-add-feature-code-content-input-code-user-select").innerHTML = "";
  document.getElementById("b-d-add-feature-code-content-input-num-of-codes").value = "1";
  document.getElementById("b-d-add-feature-code-content-features-list").innerHTML = "";
  backDoorBtnManager("def", "b-d-add-feature-code-btn-save");
  if (tsk == "show") {
    addFeatureCodeAddFeature();
  }
  modCover(tsk, 'modal-cover-add-feature-code');
}

function addFeatureCodeInputLength(inp) {
  if (inp.value.length > 0) {
    document.getElementById("b-d-add-feature-code-content-input-code-checkbox-input").checked = false;
  }
  document.getElementById("b-d-add-feature-code-content-input-code-details-txt-characters-num").innerHTML = inp.value.length +" / 11";
}

function addFeatureCodeCheckboxOnclick() {
  document.getElementById("b-d-add-feature-code-content-input-code").value = "";
  addFeatureCodeInputLength(document.getElementById("b-d-add-feature-code-content-input-code"));
  addFeatureCodeInputCheckAvailability();
}

var xhrAddFeatureCodeInputCheckAvailability, addFeatureCodeInputValue;
function addFeatureCodeInputCheckAvailability() {
  addFeatureCodeInputCheckAvailabilityCancel();
  document.getElementById("b-d-add-feature-code-content-input-code-details-txt-characters-num").style.display = "";
  document.getElementById("b-d-add-feature-code-content-input-code-details-txt-error").style.display = "";
  addFeatureCodeInputValue = document.getElementById("b-d-add-feature-code-content-input-code").value;
  xhrAddFeatureCodeInputCheckAvailability = new XMLHttpRequest();
  xhrAddFeatureCodeInputCheckAvailability.onreadystatechange = function() {
    if (xhrAddFeatureCodeInputCheckAvailability.readyState == 4 && xhrAddFeatureCodeInputCheckAvailability.status == 200) {
      if (testJSON(xhrAddFeatureCodeInputCheckAvailability.response)) {
        var json = JSON.parse(xhrAddFeatureCodeInputCheckAvailability.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "used") {
              document.getElementById("b-d-add-feature-code-content-input-code-details-txt-characters-num").style.display = "none";
              document.getElementById("b-d-add-feature-code-content-input-code-details-txt-error").style.display = "block";
            } else if (json[key]["type"] == "error") {
              alert(json[key]["error"]);
            }
          }
        }
      } else {
        alert(xhrAddFeatureCodeInputCheckAvailability.response);
      }
    }
  }
  xhrAddFeatureCodeInputCheckAvailability.open("POST", "php-backend/check-feature-code-availability.php");
  xhrAddFeatureCodeInputCheckAvailability.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrAddFeatureCodeInputCheckAvailability.send("code="+ addFeatureCodeInputValue);
}

function addFeatureCodeInputCheckAvailabilityCancel() {
  if (xhrAddFeatureCodeInputCheckAvailability != null) {
    xhrAddFeatureCodeInputCheckAvailability.abort();
  }
}

function addFeatureCodeUserOnfocus() {
  document.getElementById("b-d-add-feature-code-content-input-code-user-select").style.display = "flex";
}

var focusoutTime;
function addFeatureCodeUserOnfocusout() {
  clearTimeout(focusoutTime);
  focusoutTime = setTimeout(function(){
    document.getElementById("b-d-add-feature-code-content-input-code-user-select").style.display = "";
  }, 250);
}

var xhrAddFeatureCodeUserSearch;
function addFeatureCodeUserSearch(inp) {
  addFeatureCodeUserSearchCancel();
  addFeatureCodeUserSearchOptionsReset();
  if (inp.value.length >= 3) {
    xhrAddFeatureCodeUserSearch = new XMLHttpRequest();
    xhrAddFeatureCodeUserSearch.onreadystatechange = function() {
      if (xhrAddFeatureCodeUserSearch.readyState == 4 && xhrAddFeatureCodeUserSearch.status == 200) {
        if (testJSON(xhrAddFeatureCodeUserSearch.response)) {
          var json = JSON.parse(xhrAddFeatureCodeUserSearch.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "user") {
                addFeatureCodeUserRender(json[key]["id"], json[key]["username"]);
              } else if (json[key]["type"] == "error") {
                alert(json[key]["error"]);
              }
            }
          }
        } else {
          alert(xhrAddFeatureCodeUserSearch.response);
        }
      }
    }
    xhrAddFeatureCodeUserSearch.open("POST", "php-backend/search-for-users-manager.php");
    xhrAddFeatureCodeUserSearch.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrAddFeatureCodeUserSearch.send("search="+ inp.value);
  }
}

function addFeatureCodeUserSearchCancel() {
  if (xhrAddFeatureCodeUserSearch != null) {
    xhrAddFeatureCodeUserSearch.abort();
  }
}

var numOfOptions;
function addFeatureCodeUserSearchOptionsReset() {
  numOfOptions = document.getElementsByClassName("b-d-add-feature-code-content-input-code-user-option").length;
  for (var o = 0; o < numOfOptions; o++) {
    if (document.getElementsByClassName("b-d-add-feature-code-content-input-code-user-option")[0]) {
      document.getElementsByClassName("b-d-add-feature-code-content-input-code-user-option")[0].parentElement.removeChild(document.getElementsByClassName("b-d-add-feature-code-content-input-code-user-option")[0]);
    }
  }
}

function addFeatureCodeUserRender(usrID, usrName) {
  var optionBlck = document.createElement("div");
  optionBlck.setAttribute("class", "b-d-add-feature-code-content-input-code-user-option");
  var optionBtn = document.createElement("button");
  optionBtn.setAttribute("type", "button");
  optionBtn.setAttribute("value", usrName +" ("+ usrID +")");
  optionBtn.setAttribute("class", "b-d-add-feature-code-content-input-code-user-option-btn");
  optionBtn.setAttribute("onclick", "addFeatureCodeUserOption(this);");
  var optionWrp = document.createElement("div");
  optionWrp.setAttribute("class", "b-d-add-feature-code-content-input-code-user-option-txt-wrp");
  var optionTxt = document.createElement("p");
  optionTxt.setAttribute("class", "b-d-add-feature-code-content-input-code-user-option-txt");
  optionTxt.innerHTML = usrName +" ("+ usrID +")";
  optionBlck.appendChild(optionBtn);
  optionBtn.appendChild(optionWrp);
  optionWrp.appendChild(optionTxt);
  document.getElementById("b-d-add-feature-code-content-input-code-user-select").appendChild(optionBlck);
}

function addFeatureCodeUserOption(btn) {
  addFeatureCodeUserSearchOptionsReset();
  document.getElementById("b-d-add-feature-code-content-input-user").value = btn.value;
}

var featureNum;
var featureNumDone = false;
function addFeatureCodeAddFeature() {
  featureNumDone = false;
  while (!featureNumDone) {
    featureNum = Math.random();
    if (!document.getElementById("b-d-add-feature-code-content-one-feature-wrp-"+ featureNum)) {
      featureNumDone = true;
    }
  }
  var ftrWrp = document.createElement("div");
  ftrWrp.setAttribute("class", "b-d-add-feature-code-content-one-feature-wrp");
  ftrWrp.setAttribute("id", "b-d-add-feature-code-content-one-feature-wrp-"+ featureNum);
  var ftrLayout = document.createElement("div");
  ftrLayout.setAttribute("class", "b-d-add-feature-code-content-one-feature-layout");
  var ftrSelectWrp = document.createElement("div");
  ftrSelectWrp.setAttribute("class", "b-d-add-feature-code-content-one-feature-select-wrp");
  var ftrSelect = document.createElement("select");
  ftrSelect.setAttribute("class", "b-d-add-feature-code-content-one-feature-select");
  ftrSelect.setAttribute("id", "b-d-add-feature-code-content-one-feature-select-"+ featureNum);
  ftrSelect.setAttribute("onchange", "addFeatureCodeOneFeatureSelect("+ featureNum +");");
  var ftrBlankOption = document.createElement("option");
  ftrBlankOption.setAttribute("value", "");
  ftrBlankOption.innerHTML = "-";
  ftrSelect.appendChild(ftrBlankOption);
  for (var f = 0; f < featureNamesList.length; f++) {
    var ftrOption = document.createElement("option");
    ftrOption.setAttribute("value", featureNamesList[f]);
    ftrOption.innerHTML = featureNamesList[f];
    ftrSelect.appendChild(ftrOption);
  }
  var ftrSlashWrp = document.createElement("div");
  ftrSlashWrp.setAttribute("class", "b-d-add-feature-code-content-input-slash-wrp");
  var ftrSlash = document.createElement("p");
  ftrSlash.setAttribute("class", "b-d-add-feature-code-content-input-slash");
  ftrSlash.innerHTML = "/";
  var ftrInputWrp = document.createElement("div");
  ftrInputWrp.setAttribute("class", "b-d-add-feature-code-content-one-feature-input-wrp");
  var ftrInput = document.createElement("input");
  ftrInput.setAttribute("type", "text");
  ftrInput.setAttribute("class", "b-d-add-feature-code-content-input-text");
  ftrInput.classList.add("b-d-add-feature-code-content-one-feature-input");
  ftrInput.setAttribute("id", "b-d-add-feature-code-content-one-feature-input-"+ featureNum);
  ftrInput.setAttribute("placeholder", wrd_newFeature);
  ftrInput.setAttribute("oninput", "addFeatureCodeOneFeatureInput("+ featureNum +");");
  var ftrBtnWrp = document.createElement("div");
  ftrBtnWrp.setAttribute("class", "b-d-add-feature-code-content-one-feature-delete-btn-wrp");
  var ftrBtn = document.createElement("button");
  ftrBtn.setAttribute("type", "button");
  ftrBtn.setAttribute("class", "b-d-add-feature-code-content-one-feature-delete-btn");
  ftrBtn.setAttribute("onclick", "addFeatureCodeOneFeatureRemove("+ featureNum +");");
  ftrWrp.appendChild(ftrLayout);
  ftrLayout.appendChild(ftrSelectWrp);
  ftrSelectWrp.appendChild(ftrSelect);
  ftrLayout.appendChild(ftrSlashWrp);
  ftrSlashWrp.appendChild(ftrSlash);
  ftrLayout.appendChild(ftrInputWrp);
  ftrInputWrp.appendChild(ftrInput);
  ftrWrp.appendChild(ftrBtnWrp);
  ftrBtnWrp.appendChild(ftrBtn);
  document.getElementById("b-d-add-feature-code-content-features-list").appendChild(ftrWrp);
}

function addFeatureCodeOneFeatureRemove(n) {
  document.getElementById("b-d-add-feature-code-content-one-feature-wrp-"+ n).parentElement.removeChild(document.getElementById("b-d-add-feature-code-content-one-feature-wrp-"+ n));
}

function addFeatureCodeOneFeatureSelect(n) {
  if (document.getElementById("b-d-add-feature-code-content-one-feature-select-" +n).value != "") {
    document.getElementById("b-d-add-feature-code-content-one-feature-input-" +n).value = "";
  }
}

function addFeatureCodeOneFeatureInput(n) {
  if (document.getElementById("b-d-add-feature-code-content-one-feature-input-" +n).value != "") {
    document.getElementById("b-d-add-feature-code-content-one-feature-select-" +n).value = "";
  }
}

var addFeatureCodeSaveReady = true;
var aFCodeName, aFCodeCheckbox, aFUser, aFCodeNumOfCodes, aFFeaturesArr, aFFRquipObj, aFFeaturesJSON;
function addFeatureCodeSave() {
  if (addFeatureCodeSaveReady) {
    addFeatureCodeSaveReady = false;
    aFCodeName = document.getElementById("b-d-add-feature-code-content-input-code").value;
    if (document.getElementById("b-d-add-feature-code-content-input-code-checkbox-input").checked) {
      aFCodeCheckbox = 1;
    } else {
      aFCodeCheckbox = 0;
    }
    aFUser = document.getElementById("b-d-add-feature-code-content-input-user").value;
    aFCodeNumOfCodes = document.getElementById("b-d-add-feature-code-content-input-num-of-codes").value;
    aFFeaturesArr = [];
    aFFRquipObj = [];
    aFFeatureRow = document.getElementsByClassName("b-d-add-feature-code-content-one-feature-wrp");
    for (var aFFR = 0; aFFR < aFFeatureRow.length; aFFR++) {
       aFFRquipObj = {
        select: document.getElementsByClassName("b-d-add-feature-code-content-one-feature-select")[aFFR].value,
        value: document.getElementsByClassName("b-d-add-feature-code-content-input-text b-d-add-feature-code-content-one-feature-input")[aFFR].value
      };
      aFFeaturesArr.push(aFFRquipObj);
    }
    aFFeaturesJSON = JSON.stringify(aFFeaturesArr);
    backDoorBtnManager("load", "b-d-add-feature-code-btn-save");
    var xhrAddFeatureCodeSave = new XMLHttpRequest();
    xhrAddFeatureCodeSave.onreadystatechange = function() {
      if (xhrAddFeatureCodeSave.readyState == 4 && xhrAddFeatureCodeSave.status == 200) {
        addFeatureCodeSaveReady = true;
        backDoorBtnManager("def", "b-d-add-feature-code-btn-save");
        if (testJSON(xhrAddFeatureCodeSave.response)) {
          var json = JSON.parse(xhrAddFeatureCodeSave.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                addFeatureCodeModal("hide");
                featuresCodesAvailabilityFilter("", "", "", true);
              } else if (json[key]["type"] == "error") {
                backDoorBtnManager("def", "b-d-add-feature-code-btn-save");
                alert(json[key]["error"]);
              }
            }
          }
        } else {
          backDoorBtnManager("def", "b-d-features-codes-table-tools-load-more-btn");
          alert(xhrAddFeatureCodeSave.response);
        }
      }
    }
    xhrAddFeatureCodeSave.open("POST", "php-backend/add-feature-code.php");
    xhrAddFeatureCodeSave.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrAddFeatureCodeSave.send("codeName="+ aFCodeName +"&codeCheckbox="+ aFCodeCheckbox +"&user="+ aFUser +"&codeNum="+ aFCodeNumOfCodes +"&features="+ aFFeaturesJSON);

  }
}
