var usersLoadMoreReady = true;
var searchUsersReady = false;
var searchValue, lastID;
function usersLoadMore() {
  if (usersLoadMoreReady) {
    usersLoadMoreReady = false;
    backDoorBtnManager("load", "b-d-users-table-tools-load-more-btn");
    if (searchUsersReady) {
      searchValue = document.getElementById("table-filter-search-bar-input-users").value;
    } else {
      searchValue = "";
    }
    if (document.getElementsByClassName("b-d-users-table-row").length > 0) {
      lastID = document.getElementById("b-d-users-table-about-last-id").innerHTML;
    } else {
      lastID = "";
    }
    loadUsers(searchValue, lastID, false);
  }
}

var searchText;
var lastNumOfLetters = 0;
var searchBtnActive = false;
function usersSearchType(inpt) {
  searchText = inpt.value;
  if (searchText.length >= 3 || searchBtnActive) {
    if (searchText == "") {
      searchUsersReady = false;
      searchBtnActive = false;
    } else {
      searchUsersReady = true;
      searchBtnActive = true;
    }
    loadUsers(searchText, "", true);
  } else {
    if (lastNumOfLetters > 2) {
      searchUsersReady = false;
      searchBtnActive = false;
      loadUsers("", "", true);
    }
  }
  lastNumOfLetters = searchText.length;
}

function usersSearchCancel() {
  document.getElementById("table-filter-search-bar-input-users").value = "";
  searchUsersReady = false;
  searchBtnActive = false;
  loadUsers("", "", true);
}

function usersSearchBtn() {
  searchText = document.getElementById("table-filter-search-bar-input-users").value;
  if (searchText != "") {
    searchBtnActive = true;
    searchUsersReady = true;
    loadUsers(searchText, "", true);
  }
}

var xhrLoadUsers;
function loadUsers(searchValue, lastID, resetTable) {
  loadUsersCancel();
  if (resetTable) {
    tableRowsNum = document.getElementsByClassName("b-d-users-table-row").length;
    for (var rT = 0; rT < tableRowsNum; rT++) {
      document.getElementsByClassName("b-d-users-table-row")[0].parentNode.removeChild(document.getElementsByClassName("b-d-users-table-row")[0]);
    }
    document.getElementById("b-d-users-table-tools-load-more-wrp").style.display = "";
    document.getElementById("b-d-users-table-tools-loader-wrp").style.display = "flex";
  } else {
    document.getElementById("b-d-users-table-tools-load-more-wrp").style.display = "flex";
    document.getElementById("b-d-users-table-tools-loader-wrp").style.display = "";
  }
  document.getElementById("b-d-users-table-tools-no-content-wrp").style.display = "";
  document.getElementById("b-d-users-table-tools-errors-wrp").style.display = "";
  document.getElementById("b-d-users-table-tools-errors-txt").innerHTML = "";
  outputLastID = "";
  xhrLoadUsers = new XMLHttpRequest();
  xhrLoadUsers.onreadystatechange = function() {
    if (xhrLoadUsers.readyState == 4 && xhrLoadUsers.status == 200) {
      usersLoadMoreReady = true;
      document.getElementById("b-d-users-table-tools-loader-wrp").style.display = "";
      backDoorBtnManager("def", "b-d-users-table-tools-load-more-btn");
      if (testJSON(xhrLoadUsers.response)) {
        var json = JSON.parse(xhrLoadUsers.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "user") {
              renderUserRow(
                json[key]["status"],
                json[key]["userID"],
                json[key]["firstname"],
                json[key]["lastname"],
                json[key]["contactEmail"],
                json[key]["wShowMore"]
              );
              outputLastID = json[key]["userID"];
            } else if (json[key]["type"] == "load-amount") {
              if (json[key]["remain"] > 0) {
                document.getElementById("b-d-users-table-tools-load-more-wrp").style.display = "flex";
              } else {
                document.getElementById("b-d-users-table-tools-load-more-wrp").style.display = "";
              }
              if (json[key]["all-users"] > 0) {
                document.getElementById("b-d-users-table-tools-no-content-wrp").style.display = "";
              } else {
                document.getElementById("b-d-users-table-tools-no-content-wrp").style.display = "flex";
              }
            } else if (json[key]["type"] == "error") {
              document.getElementById("b-d-users-table-tools-errors-wrp").style.display = "flex";
              document.getElementById("b-d-users-table-tools-errors-txt").innerHTML = json[key]["error"];
            }
          }
        }
        document.getElementById("b-d-users-table-about-last-id").innerHTML = outputLastID;
      } else {
        document.getElementById("b-d-users-table-tools-errors-wrp").style.display = "flex";
        document.getElementById("b-d-users-table-tools-errors-txt").innerHTML = xhrLoadUsers.response;
      }
    }
  }
  xhrLoadUsers.open("POST", "php-backend/get-list-of-users-manager.php");
  xhrLoadUsers.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrLoadUsers.send("lastID="+ lastID +"&search="+ searchValue);
}

function loadUsersCancel() {
  usersLoadMoreReady = true;
  if (xhrLoadUsers != null) {
    xhrLoadUsers.abort();
  }
}

function renderUserRow(userStatus, userID, firstname, lastname, contactEmail, wShowMore) {
  var tableRow = document.createElement("tr");
  tableRow.setAttribute("class", "b-d-users-table-row");
  var tdStatus = document.createElement("td");
  tdStatus.innerHTML = userStatus;
  var tdID = document.createElement("td");
  tdID.innerHTML = userID;
  var tdFirstname = document.createElement("td");
  tdFirstname.innerHTML = firstname;
  var tdLastname = document.createElement("td");
  tdLastname.innerHTML = lastname;
  var tdContactEmail = document.createElement("td");
  tdContactEmail.innerHTML = contactEmail;
  var tdShowMore = document.createElement("td");
  var aShowMore = document.createElement("a");
  aShowMore.setAttribute("class", "table-row-link-blue");
  aShowMore.setAttribute("href", "../user/?section=users&nav=about&id="+ userID +"&m=&y=&paymentreference=");
  aShowMore.innerHTML = wShowMore;
  tableRow.appendChild(tdStatus);
  tableRow.appendChild(tdID);
  tableRow.appendChild(tdFirstname);
  tableRow.appendChild(tdLastname);
  tableRow.appendChild(tdContactEmail);
  tableRow.appendChild(tdShowMore);
  tdShowMore.appendChild(aShowMore);
  document.getElementById("b-d-users-table").appendChild(tableRow);
}
