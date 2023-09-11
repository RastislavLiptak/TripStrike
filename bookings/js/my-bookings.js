var searchBookingReady = false;
var myBookingsLoadMoreReady = true;
var searchValue, lastID;
function myBookingsLoadMore() {
  if (myBookingsLoadMoreReady) {
    myBookingsLoadMoreReady = false;
    myBookingsLoadMoreBtn("load", "bookings-list-tools-load-more-btn-my-bookings");
    if (searchBookingReady) {
      searchValue = document.getElementById("bookings-list-search-bar-input-my-bookings").value;
    } else {
      searchValue = "";
    }
    if (document.getElementsByClassName("my-bookings-show-more").length > 0) {
      lastID = new URL(document.getElementsByClassName("my-bookings-show-more")[document.getElementsByClassName("my-bookings-show-more").length -1].href).searchParams.get("id");
    } else {
      lastID = "";
    }
    loadMyBookings(searchValue, lastID, false);
  }
}

function myBookingsLoadMoreBtn(task, id) {
  if (task == "def") {
    document.getElementById(id).style.color = "#fff";
    document.getElementById(id).style.backgroundImage = "unset";
    document.getElementById(id).style.backgroundSize = "unset";
  } else if (task == "load") {
    document.getElementById(id).style.color = "rgba(0,0,0,0)";
    document.getElementById(id).style.backgroundImage = "url('../uni/gifs/loader-tail.svg')";
    document.getElementById(id).style.backgroundSize = "auto 63%";
  }
}

var xhrLoadMyBookings, tableRowsNum;
function loadMyBookings(searchValue, lastID, resetTable) {
  if (resetTable) {
    tableRowsNum = document.getElementsByClassName("my-bookings-list-row").length;
    for (var rT = 0; rT < tableRowsNum; rT++) {
      document.getElementsByClassName("my-bookings-list-row")[0].parentNode.removeChild(document.getElementsByClassName("my-bookings-list-row")[0]);
    }
    document.getElementById("bookings-list-tools-load-more-wrp-my-bookings").style.display = "";
    document.getElementById("bookings-list-tools-loader-wrp-my-bookings").style.display = "flex";
  } else {
    document.getElementById("bookings-list-tools-load-more-wrp-my-bookings").style.display = "flex";
    document.getElementById("bookings-list-tools-loader-wrp-my-bookings").style.display = "";
  }
  document.getElementById("bookings-list-tools-errors-wrp-my-bookings").style.display = "";
  document.getElementById("bookings-list-tools-errors-txt-my-bookings").innerHTML = "";
  xhrLoadMyBookings = new XMLHttpRequest();
  xhrLoadMyBookings.onreadystatechange = function() {
    if (xhrLoadMyBookings.readyState == 4 && xhrLoadMyBookings.status == 200) {
      myBookingsLoadMoreReady = true;
      document.getElementById("bookings-list-tools-loader-wrp-my-bookings").style.display = "";
      myBookingsLoadMoreBtn("def", "bookings-list-tools-load-more-btn-my-bookings");
      if (testJSON(xhrLoadMyBookings.response)) {
        var json = JSON.parse(xhrLoadMyBookings.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "booking") {
              renderBookingRow(
                json[key]["status"],
                json[key]["bookingID"],
                json[key]["plcSts"],
                json[key]["plcID"],
                json[key]["plcName"],
                json[key]["fromD"],
                json[key]["fromM"],
                json[key]["fromY"],
                json[key]["toD"],
                json[key]["toM"],
                json[key]["toY"],
                json[key]["numOfGuests"],
                json[key]["wShowMore"]
              );
            } else if (json[key]["type"] == "load-amount") {
              if (json[key]["remain"] > 0) {
                document.getElementById("bookings-list-tools-load-more-wrp-my-bookings").style.display = "flex";
              } else {
                document.getElementById("bookings-list-tools-load-more-wrp-my-bookings").style.display = "";
              }
              if (json[key]["all-bookings"] > 0) {
                document.getElementById("bookings-list-tools-no-bookings-wrp-my-bookings").style.display = "";
              } else {
                document.getElementById("bookings-list-tools-no-bookings-wrp-my-bookings").style.display = "flex";
              }
            } else if (json[key]["type"] == "error") {
              document.getElementById("bookings-list-tools-errors-wrp-my-bookings").style.display = "flex";
              document.getElementById("bookings-list-tools-errors-txt-my-bookings").innerHTML = json[key]["error"];
            }
          }
        }
      } else {
        document.getElementById("bookings-list-tools-errors-wrp-my-bookings").style.display = "flex";
        document.getElementById("bookings-list-tools-errors-txt-my-bookings").innerHTML = xhrLoadMyBookings.response;
      }
    }
  }
  xhrLoadMyBookings.open("POST", "php-backend/get-list-of-my-bookings-manager.php");
  xhrLoadMyBookings.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrLoadMyBookings.send("lastID="+ lastID +"&search="+ searchValue);
}

function loadMyBookingsCancel() {
  myBookingsLoadMoreReady = true;
  if (xhrLoadMyBookings != null) {
    xhrLoadMyBookings.abort();
  }
}

function renderBookingRow(bookingStatus, bookingID, plcSts, plcID, plcName, fromD, fromM, fromY, toD, toM, toY, numOfGuests, wShowMore) {
  var tableRow = document.createElement("tr");
  tableRow.setAttribute("class", "my-bookings-list-row");
  var tdStatus = document.createElement("td");
  tdStatus.innerHTML = bookingStatus;
  var tdPlace = document.createElement("td");
  if (plcSts == "active") {
    var aPlace = document.createElement("a");
    aPlace.setAttribute("href", "../place/?id="+ plcID);
    aPlace.setAttribute("target", "_blank");
    aPlace.innerHTML = plcName;
    tdPlace.appendChild(aPlace);
  } else {
    tdPlace.innerHTML = plcName;
  }
  var tdDates = document.createElement("td");
  tdDates.innerHTML = fromD +"."+ fromM +"."+ fromY +" - "+ toD +"."+ toM +"."+ toY;
  var tdNumOfGuests = document.createElement("td");
  tdNumOfGuests.innerHTML = numOfGuests;
  var tdShowMore = document.createElement("td");
  var aShowMore = document.createElement("a");
  aShowMore.setAttribute("class", "table-row-link-blue");
  aShowMore.classList.add("my-bookings-show-more");
  aShowMore.setAttribute("href", "about-my-booking.php?id="+ bookingID);
  aShowMore.innerHTML = wShowMore;
  tableRow.appendChild(tdStatus);
  tableRow.appendChild(tdPlace);
  tableRow.appendChild(tdDates);
  tableRow.appendChild(tdNumOfGuests);
  tableRow.appendChild(tdShowMore);
  tdShowMore.appendChild(aShowMore);
  document.getElementById("bookings-list-table-my-bookings").appendChild(tableRow);
}

var searchText;
var lastNumOfLetters = 0;
var searchBtnActive = false;
function myBookingsSearchType(inpt) {
  searchText = inpt.value;
  if (searchText.length >= 3 || searchBtnActive) {
    if (searchText == "") {
      searchBookingReady = false;
      searchBtnActive = false;
    } else {
      searchBookingReady = true;
      searchBtnActive = true;
    }
    loadMyBookings(searchText, "", true);
  } else {
    if (lastNumOfLetters > 2) {
      searchBookingReady = false;
      searchBtnActive = false;
      loadMyBookings("", "", true);
    }
  }
  lastNumOfLetters = searchText.length;
}

function myBookingsSearchCancel() {
  document.getElementById("bookings-list-search-bar-input-my-bookings").value = "";
  searchBookingReady = false;
  searchBtnActive = false;
  loadMyBookings("", "", true);
}

function myBookingsSearchBtn() {
  searchText = document.getElementById("bookings-list-search-bar-input-my-bookings").value;
  if (searchText != "") {
    searchBtnActive = true;
    searchBookingReady = true;
    loadMyBookings(searchText, "", true);
  }
}
