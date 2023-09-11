var featuresCodesLoadMoreReady = true;
var searchFeaturesCodesReady = false;
var searchValue, lastCode, lastFeature, availabilityFilter;
function featuresCodesLoadMore() {
  if (featuresCodesLoadMoreReady) {
    featuresCodesLoadMoreReady = false;
    backDoorBtnManager("load", "b-d-features-codes-table-tools-load-more-btn");
    if (searchFeaturesCodesReady) {
      searchValue = document.getElementById("table-filter-search-bar-input-features-codes").value;
    } else {
      searchValue = "";
    }
    if (document.getElementsByClassName("b-d-features-codes-table-row").length > 0) {
      lastCode = document.getElementById("b-d-features-codes-table-about-last-code").innerHTML;
      lastFeature = document.getElementById("b-d-features-codes-table-about-last-feature").innerHTML;
    } else {
      lastCode = "";
      lastFeature = "";
    }
    featuresCodesAvailabilityFilter(searchValue, lastCode, lastFeature, false);
  }
}

var searchText;
var lastNumOfLetters = 0;
var searchBtnActive = false;
function featuresCodesSearchType(inpt) {
  searchText = inpt.value;
  if (searchText.length >= 3 || searchBtnActive) {
    if (searchText == "") {
      searchFeaturesCodesReady = false;
      searchBtnActive = false;
    } else {
      searchFeaturesCodesReady = true;
      searchBtnActive = true;
    }
    featuresCodesAvailabilityFilter(searchText, "", "", true);
  } else {
    if (lastNumOfLetters > 2) {
      searchFeaturesCodesReady = false;
      searchBtnActive = false;
      featuresCodesAvailabilityFilter("", "", "", true);
    }
  }
  lastNumOfLetters = searchText.length;
}

function featuresCodesSearchCancel() {
  document.getElementById("table-filter-search-bar-input-features-codes").value = "";
  searchFeaturesCodesReady = false;
  searchBtnActive = false;
  featuresCodesAvailabilityFilter("", "", "", true);
}

function featuresCodesSearchBtn() {
  searchText = document.getElementById("table-filter-search-bar-input-features-codes").value;
  if (searchText != "") {
    searchBtnActive = true;
    searchFeaturesCodesReady = true;
    featuresCodesAvailabilityFilter(searchText, "", "", true);
  }
}

function featuresCodesAvailabilityFilterOnchange() {
  searchText = document.getElementById("table-filter-search-bar-input-features-codes").value;
  featuresCodesAvailabilityFilter(searchText, "", "", true);
}

function featuresCodesAvailabilityFilter(searchValue, lastCode, lastFeature, resetTable) {
  availabilityFilter = document.getElementById("b-d-features-codes-table-filter-availability").value;
  loadFeaturesCodes(searchValue, lastCode, lastFeature, availabilityFilter, resetTable);
}

var xhrLoadFeaturesCodes;
function loadFeaturesCodes(searchValue, lastCode, lastFeature, availabilityFilter, resetTable) {
  loadFeaturesCodesCancel();
  if (resetTable) {
    tableRowsNum = document.getElementsByClassName("b-d-features-codes-table-row").length;
    for (var rT = 0; rT < tableRowsNum; rT++) {
      document.getElementsByClassName("b-d-features-codes-table-row")[0].parentNode.removeChild(document.getElementsByClassName("b-d-features-codes-table-row")[0]);
    }
    document.getElementById("b-d-features-codes-table-tools-load-more-wrp").style.display = "";
    document.getElementById("b-d-features-codes-table-tools-loader-wrp").style.display = "flex";
  } else {
    document.getElementById("b-d-features-codes-table-tools-load-more-wrp").style.display = "flex";
    document.getElementById("b-d-features-codes-table-tools-loader-wrp").style.display = "";
  }
  document.getElementById("b-d-features-codes-table-tools-no-content-wrp").style.display = "";
  document.getElementById("b-d-features-codes-table-tools-errors-wrp").style.display = "";
  document.getElementById("b-d-features-codes-table-tools-errors-txt").innerHTML = "";
  outputLastCode = "";
  outputLastFeature = "";
  xhrLoadFeaturesCodes = new XMLHttpRequest();
  xhrLoadFeaturesCodes.onreadystatechange = function() {
    if (xhrLoadFeaturesCodes.readyState == 4 && xhrLoadFeaturesCodes.status == 200) {
      featuresCodesLoadMoreReady = true;
      document.getElementById("b-d-features-codes-table-tools-loader-wrp").style.display = "";
      backDoorBtnManager("def", "b-d-features-codes-table-tools-load-more-btn");
      if (testJSON(xhrLoadFeaturesCodes.response)) {
        var json = JSON.parse(xhrLoadFeaturesCodes.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "code") {
              renderCodesRow(
                json[key]["status"],
                json[key]["code"],
                json[key]["feature"],
                json[key]["user"],
                json[key]["numOfFeatures"]
              );
              outputLastCode = json[key]["code"];
              outputLastFeature = json[key]["feature"];
            } else if (json[key]["type"] == "load-amount") {
              if (json[key]["remain"] > 0) {
                document.getElementById("b-d-features-codes-table-tools-load-more-wrp").style.display = "flex";
              } else {
                document.getElementById("b-d-features-codes-table-tools-load-more-wrp").style.display = "";
              }
              if (json[key]["all-codes"] > 0) {
                document.getElementById("b-d-features-codes-table-tools-no-content-wrp").style.display = "";
              } else {
                document.getElementById("b-d-features-codes-table-tools-no-content-wrp").style.display = "flex";
              }
            } else if (json[key]["type"] == "error") {
              document.getElementById("b-d-features-codes-table-tools-errors-wrp").style.display = "flex";
              document.getElementById("b-d-features-codes-table-tools-errors-txt").innerHTML = json[key]["error"];
            }
          }
        }
        document.getElementById("b-d-features-codes-table-about-last-code").innerHTML = outputLastCode;
        document.getElementById("b-d-features-codes-table-about-last-feature").innerHTML = outputLastFeature;
      } else {
        document.getElementById("b-d-features-codes-table-tools-errors-wrp").style.display = "flex";
        document.getElementById("b-d-features-codes-table-tools-errors-txt").innerHTML = xhrLoadFeaturesCodes.response;
      }
    }
  }
  xhrLoadFeaturesCodes.open("POST", "php-backend/get-list-of-features-codes-manager.php");
  xhrLoadFeaturesCodes.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrLoadFeaturesCodes.send("lastCode="+ lastCode +"&lastFeature="+ lastFeature +"&availabilityFilter="+ availabilityFilter +"&search="+ searchValue);
}

function loadFeaturesCodesCancel() {
  featuresCodesLoadMoreReady = true;
  if (xhrLoadFeaturesCodes != null) {
    xhrLoadFeaturesCodes.abort();
  }
}

function renderCodesRow(codeStatus, code, feature, user, numOfFeatures) {
  var tableRow = document.createElement("tr");
  tableRow.setAttribute("class", "b-d-features-codes-table-row");
  var tdStatus = document.createElement("td");
  tdStatus.innerHTML = codeStatus;
  var tdCode = document.createElement("td");
  tdCode.innerHTML = code;
  var tdFeature = document.createElement("td");
  tdFeature.innerHTML = feature;
  var tdUser = document.createElement("td");
  tdUser.innerHTML = user;
  var tdNumOfFeatures = document.createElement("td");
  tdNumOfFeatures.innerHTML = numOfFeatures;
  tableRow.appendChild(tdStatus);
  tableRow.appendChild(tdCode);
  tableRow.appendChild(tdFeature);
  tableRow.appendChild(tdUser);
  tableRow.appendChild(tdNumOfFeatures);
  document.getElementById("b-d-features-codes-table").appendChild(tableRow);
}
