var searchBookingReady = false;
var guestBookingsLoadMoreReady = true;
var searchValue, lastID;
function guestBookingsLoadMore() {
  if (guestBookingsLoadMoreReady) {
    guestBookingsLoadMoreReady = false;
    guestBookingsLoadMoreBtn("load", "bookings-list-tools-no-bookings-wrp-guest-bookings");
    if (searchBookingReady) {
      searchValue = document.getElementById("bookings-list-search-bar-input-guest-bookings").value;
    } else {
      searchValue = "";
    }
    if (document.getElementsByClassName("guest-bookings-show-more").length > 0) {
      lastID = new URL(document.getElementsByClassName("guest-bookings-show-more")[document.getElementsByClassName("guest-bookings-show-more").length -1].href).searchParams.get("id");
    } else {
      lastID = "";
    }
    loadGuestBookings(searchValue, lastID, false);
  }
}

function guestBookingsLoadMoreBtn(task, id) {
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

var xhrLoadGuestBookings, tableRowsNum;
function loadGuestBookings(searchValue, lastID, resetTable) {
  if (resetTable) {
    tableRowsNum = document.getElementsByClassName("guest-bookings-list-row").length;
    for (var rT = 0; rT < tableRowsNum; rT++) {
      document.getElementsByClassName("guest-bookings-list-row")[0].parentNode.removeChild(document.getElementsByClassName("guest-bookings-list-row")[0]);
    }
    document.getElementById("bookings-list-tools-load-more-wrp-guest-bookings").style.display = "";
    document.getElementById("bookings-list-tools-loader-wrp-guest-bookings").style.display = "flex";
  } else {
    document.getElementById("bookings-list-tools-load-more-wrp-guest-bookings").style.display = "flex";
    document.getElementById("bookings-list-tools-loader-wrp-guest-bookings").style.display = "";
  }
  document.getElementById("bookings-list-tools-errors-wrp-guest-bookings").style.display = "";
  document.getElementById("bookings-list-tools-errors-txt-guest-bookings").innerHTML = "";
  xhrLoadGuestBookings = new XMLHttpRequest();
  xhrLoadGuestBookings.onreadystatechange = function() {
    if (xhrLoadGuestBookings.readyState == 4 && xhrLoadGuestBookings.status == 200) {
      guestBookingsLoadMoreReady = true;
      document.getElementById("bookings-list-tools-loader-wrp-guest-bookings").style.display = "";
      guestBookingsLoadMoreBtn("def", "bookings-list-tools-no-bookings-wrp-guest-bookings");
      if (testJSON(xhrLoadGuestBookings.response)) {
        var json = JSON.parse(xhrLoadGuestBookings.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "booking") {
              renderBookingRow(
                json[key]["status"],
                json[key]["bookingID"],
                json[key]["guestSts"],
                json[key]["guestID"],
                json[key]["guestName"],
                json[key]["plcSts"],
                json[key]["plcID"],
                json[key]["plcName"],
                json[key]["fromD"],
                json[key]["fromM"],
                json[key]["fromY"],
                json[key]["toD"],
                json[key]["toM"],
                json[key]["toY"],
                json[key]["wShowMore"]
              );
            } else if (json[key]["type"] == "load-amount") {
              if (json[key]["remain"] > 0) {
                document.getElementById("bookings-list-tools-load-more-wrp-guest-bookings").style.display = "flex";
              } else {
                document.getElementById("bookings-list-tools-load-more-wrp-guest-bookings").style.display = "";
              }
              if (json[key]["all-bookings"] > 0) {
                document.getElementById("bookings-list-tools-no-bookings-wrp-guest-bookings").style.display = "";
              } else {
                document.getElementById("bookings-list-tools-no-bookings-wrp-guest-bookings").style.display = "flex";
              }
            } else if (json[key]["type"] == "error") {
              document.getElementById("bookings-list-tools-errors-wrp-guest-bookings").style.display = "flex";
              document.getElementById("bookings-list-tools-errors-txt-guest-bookings").innerHTML = json[key]["error"];
            }
          }
        }
      } else {
        document.getElementById("bookings-list-tools-errors-wrp-guest-bookings").style.display = "flex";
        document.getElementById("bookings-list-tools-errors-txt-guest-bookings").innerHTML = xhrLoadGuestBookings.response;
      }
    }
  }
  xhrLoadGuestBookings.open("POST", "php-backend/get-list-of-guest-bookings-manager.php");
  xhrLoadGuestBookings.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrLoadGuestBookings.send("lastID="+ lastID +"&search="+ searchValue);
}

function loadGuestBookingsCancel() {
  guestBookingsLoadMoreReady = true;
  if (xhrLoadGuestBookings != null) {
    xhrLoadGuestBookings.abort();
  }
}

function renderBookingRow(bookingStatus, bookingID, guestSts, guestID, guestName, plcSts, plcID, plcName, fromD, fromM, fromY, toD, toM, toY, wShowMore) {
  var tableRow = document.createElement("tr");
  tableRow.setAttribute("class", "guest-bookings-list-row");
  var tdStatus = document.createElement("td");
  tdStatus.innerHTML = bookingStatus;
  var tdGuest = document.createElement("td");
  if (guestSts == "signed-user") {
    var aGuest = document.createElement("a");
    aGuest.setAttribute("href", "../user/?id="+ guestID +"&section=about");
    aGuest.setAttribute("target", "_blank");
    aGuest.innerHTML = guestName;
    tdGuest.appendChild(aGuest);
  } else {
    tdGuest.innerHTML = guestName;
  }
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
  var tdShowMore = document.createElement("td");
  var aShowMore = document.createElement("a");
  aShowMore.setAttribute("class", "table-row-link-blue");
  aShowMore.classList.add("guest-bookings-show-more");
  aShowMore.setAttribute("href", "about-guest-booking.php?id="+ bookingID);
  aShowMore.innerHTML = wShowMore;
  tableRow.appendChild(tdStatus);
  tableRow.appendChild(tdGuest);
  tableRow.appendChild(tdPlace);
  tableRow.appendChild(tdDates);
  tableRow.appendChild(tdShowMore);
  tdShowMore.appendChild(aShowMore);
  document.getElementById("bookings-list-table-guest-list").appendChild(tableRow);
}

var searchText;
var lastNumOfLetters = 0;
var searchBtnActive = false;
function guestBookingsSearchType(inpt) {
  searchText = inpt.value;
  if (searchText.length >= 3 || searchBtnActive) {
    if (searchText == "") {
      searchBookingReady = false;
      searchBtnActive = false;
    } else {
      searchBookingReady = true;
      searchBtnActive = true;
    }
    loadGuestBookings(searchText, "", true);
  } else {
    if (lastNumOfLetters > 2) {
      searchBookingReady = false;
      searchBtnActive = false;
      loadGuestBookings("", "", true);
    }
  }
  lastNumOfLetters = searchText.length;
}

function guestBookingsSearchCancel() {
  document.getElementById("bookings-list-search-bar-input-guest-bookings").value = "";
  searchBookingReady = false;
  searchBtnActive = false;
  loadGuestBookings("", "", true);
}

function guestBookingsSearchBtn() {
  searchText = document.getElementById("bookings-list-search-bar-input-guest-bookings").value;
  if (searchText != "") {
    searchBtnActive = true;
    searchBookingReady = true;
    loadGuestBookings(searchText, "", true);
  }
}
