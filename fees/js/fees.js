var wrd_searchByReferenceCode = "Search by reference code or host";
var wrd_searchForBookingByPlaceNameOrDate = "Search for a booking by place name or date";
function feesDictionary(
  searchByReferenceCode,
  searchForBookingByPlaceNameOrDate
) {
  wrd_searchByReferenceCode = searchByReferenceCode;
  wrd_searchForBookingByPlaceNameOrDate = searchForBookingByPlaceNameOrDate;
}

var searchBookingReady = false;
function feesSwitchTableContent(cont) {
  searchBookingReady = false;
  loadFeesContent(document.getElementById("fees-list-search-bar-input").value, "", true);
  if (cont == "bookings") {
    document.getElementById("fees-list-search-bar-input").placeholder = wrd_searchForBookingByPlaceNameOrDate;
    document.getElementById("fees-list-table-payment-reference-wrp").classList.remove("fees-list-table-wrp-selected");
    document.getElementById("fees-list-table-bookings-wrp").classList.add("fees-list-table-wrp-selected");
  } else {
    document.getElementById("fees-list-search-bar-input").placeholder = wrd_searchByReferenceCode;
    document.getElementById("fees-list-table-payment-reference-wrp").classList.add("fees-list-table-wrp-selected");
    document.getElementById("fees-list-table-bookings-wrp").classList.remove("fees-list-table-wrp-selected");
  }
}

var feesContentLoadMoreReady = true;
var searchValue, lastID;
function feesContentLoadMore() {
  if (feesContentLoadMoreReady) {
    feesContentLoadMoreReady = false;
    feesListBtnManager("load", "fees-list-tools-load-more-btn");
    if (searchBookingReady) {
      searchValue = document.getElementById("fees-list-search-bar-input").value;
    } else {
      searchValue = "";
    }
    if (document.getElementsByClassName("fees-list-table-row").length > 0) {
      lastID = document.getElementById("fees-list-table-about-last-id").innerHTML;
    } else {
      lastID = "";
    }
    loadFeesContent(searchValue, lastID, false);
  }
}

function loadFeesContent(searchValue, lastID, resetTable) {
  loadFeesPaymentReferenceCancel();
  loadFeesBookingsCancel();
  if (document.getElementById("fees-list-table-filter-switch-content").value == "bookings") {
    loadFeesBookings(searchValue, lastID, resetTable);
  } else {
    loadFeesPaymentReference(searchValue, lastID, resetTable);
  }
}

var xhrFeesLoadPaymentReference, tableRowsNum, outputLastID;
function loadFeesPaymentReference(searchValue, lastID, resetTable) {
  if (resetTable) {
    tableRowsNum = document.getElementsByClassName("fees-list-table-row").length;
    for (var rT = 0; rT < tableRowsNum; rT++) {
      document.getElementsByClassName("fees-list-table-row")[0].parentNode.removeChild(document.getElementsByClassName("fees-list-table-row")[0]);
    }
    document.getElementById("fees-list-tools-load-more-wrp").style.display = "";
    document.getElementById("fees-list-tools-loader-wrp").style.display = "flex";
  } else {
    document.getElementById("fees-list-tools-load-more-wrp").style.display = "flex";
    document.getElementById("fees-list-tools-loader-wrp").style.display = "";
  }
  document.getElementById("fees-list-tools-no-content-wrp").style.display = "";
  document.getElementById("fees-list-tools-errors-wrp").style.display = "";
  document.getElementById("fees-list-tools-errors-txt").innerHTML = "";
  outputLastID = "";
  xhrFeesLoadPaymentReference = new XMLHttpRequest();
  xhrFeesLoadPaymentReference.onreadystatechange = function() {
    if (xhrFeesLoadPaymentReference.readyState == 4 && xhrFeesLoadPaymentReference.status == 200) {
      feesContentLoadMoreReady = true;
      document.getElementById("fees-list-tools-loader-wrp").style.display = "";
      feesListBtnManager("def", "fees-list-tools-load-more-btn");
      if (testJSON(xhrFeesLoadPaymentReference.response)) {
        var json = JSON.parse(xhrFeesLoadPaymentReference.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "payment-reference") {
              renderPaymentReferenceRow(
                json[key]["status"],
                json[key]["payment-reference"],
                json[key]["numOfBookings"],
                json[key]["currency"],
                json[key]["totalFee"],
                json[key]["wShowMore"]
              );
              outputLastID = json[key]["payment-reference"];
            } else if (json[key]["type"] == "load-amount") {
              if (json[key]["remain"] > 0) {
                document.getElementById("fees-list-tools-load-more-wrp").style.display = "flex";
              } else {
                document.getElementById("fees-list-tools-load-more-wrp").style.display = "";
              }
              if (json[key]["all-fees"] > 0) {
                document.getElementById("fees-list-tools-no-content-wrp").style.display = "";
              } else {
                document.getElementById("fees-list-tools-no-content-wrp").style.display = "flex";
              }
            } else if (json[key]["type"] == "error") {
              document.getElementById("fees-list-tools-errors-wrp").style.display = "flex";
              document.getElementById("fees-list-tools-errors-txt").innerHTML = json[key]["error"];
            }
          }
        }
        document.getElementById("fees-list-table-about-last-id").innerHTML = outputLastID;
      } else {
        document.getElementById("fees-list-tools-errors-wrp").style.display = "flex";
        document.getElementById("fees-list-tools-errors-txt").innerHTML = xhrFeesLoadPaymentReference.response;
      }
    }
  }
  xhrFeesLoadPaymentReference.open("POST", "php-backend/get-list-of-payment-references-fees-manager.php");
  xhrFeesLoadPaymentReference.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrFeesLoadPaymentReference.send("paymentReference="+ lastID +"&search="+ searchValue);
}

function loadFeesPaymentReferenceCancel() {
  feesContentLoadMoreReady = true;
  if (xhrFeesLoadPaymentReference != null) {
    xhrFeesLoadPaymentReference.abort();
  }
}

function renderPaymentReferenceRow(bookingStatus, paymentReferenceCode, numOfBookings, currency, totalFee, wShowMore) {
  var tableRow = document.createElement("tr");
  tableRow.setAttribute("class", "fees-list-table-row");
  var tdStatus = document.createElement("td");
  tdStatus.setAttribute("id", "fees-list-table-row-status-"+ paymentReferenceCode);
  tdStatus.innerHTML = bookingStatus;
  var tdPaymentReference = document.createElement("td");
  tdPaymentReference.innerHTML = paymentReferenceCode;
  var tdNumOfBookings = document.createElement("td");
  tdNumOfBookings.innerHTML = numOfBookings;
  var tdTotalFee = document.createElement("td");
  tdTotalFee.innerHTML = addCurrency(currency, totalFee);
  var tdShowMore = document.createElement("td");
  var aShowMore = document.createElement("a");
  aShowMore.setAttribute("class", "table-row-link-blue");
  aShowMore.setAttribute("href", "fee-details.php?paymentreference="+ paymentReferenceCode);
  aShowMore.innerHTML = wShowMore;
  tableRow.appendChild(tdStatus);
  tableRow.appendChild(tdPaymentReference);
  tableRow.appendChild(tdNumOfBookings);
  tableRow.appendChild(tdTotalFee);
  tableRow.appendChild(tdShowMore);
  tdShowMore.appendChild(aShowMore);
  document.getElementById("fees-list-table-payment-reference").appendChild(tableRow);
}

var xhrFeesLoadBookings;
function loadFeesBookings(searchValue, lastID, resetTable) {
  if (resetTable) {
    tableRowsNum = document.getElementsByClassName("fees-list-table-row").length;
    for (var rT = 0; rT < tableRowsNum; rT++) {
      document.getElementsByClassName("fees-list-table-row")[0].parentNode.removeChild(document.getElementsByClassName("fees-list-table-row")[0]);
    }
    document.getElementById("fees-list-tools-load-more-wrp").style.display = "";
    document.getElementById("fees-list-tools-loader-wrp").style.display = "flex";
  } else {
    document.getElementById("fees-list-tools-load-more-wrp").style.display = "flex";
    document.getElementById("fees-list-tools-loader-wrp").style.display = "";
  }
  document.getElementById("fees-list-tools-no-content-wrp").style.display = "";
  document.getElementById("fees-list-tools-errors-wrp").style.display = "";
  document.getElementById("fees-list-tools-errors-txt").innerHTML = "";
  outputLastID = "0";
  xhrFeesLoadBookings = new XMLHttpRequest();
  xhrFeesLoadBookings.onreadystatechange = function() {
    if (xhrFeesLoadBookings.readyState == 4 && xhrFeesLoadBookings.status == 200) {
      feesContentLoadMoreReady = true;
      document.getElementById("fees-list-tools-loader-wrp").style.display = "";
      feesListBtnManager("def", "fees-list-tools-load-more-btn");
      if (testJSON(xhrFeesLoadBookings.response)) {
        var json = JSON.parse(xhrFeesLoadBookings.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "booking") {
              renderBookingsRow(
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
                json[key]["currency"],
                json[key]["fee"],
                json[key]["percentageFee"],
                json[key]["wShowMore"]
              );
              outputLastID = json[key]["bookingID"];
            } else if (json[key]["type"] == "load-amount") {
              if (json[key]["remain"] > 0) {
                document.getElementById("fees-list-tools-load-more-wrp").style.display = "flex";
              } else {
                document.getElementById("fees-list-tools-load-more-wrp").style.display = "";
              }
              if (json[key]["all-bookings"] > 0) {
                document.getElementById("fees-list-tools-no-content-wrp").style.display = "";
              } else {
                document.getElementById("fees-list-tools-no-content-wrp").style.display = "flex";
              }
            } else if (json[key]["type"] == "error") {
              document.getElementById("fees-list-tools-errors-wrp").style.display = "flex";
              document.getElementById("fees-list-tools-errors-txt").innerHTML = json[key]["error"];
            }
          }
        }
        document.getElementById("fees-list-table-about-last-id").innerHTML = outputLastID;
      } else {
        document.getElementById("fees-list-tools-errors-wrp").style.display = "flex";
        document.getElementById("fees-list-tools-errors-txt").innerHTML = xhrFeesLoadBookings.response;
      }
    }
  }
  xhrFeesLoadBookings.open("POST", "php-backend/get-list-of-bookings-fees-manager.php");
  xhrFeesLoadBookings.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrFeesLoadBookings.send("lastID="+ lastID +"&search="+ searchValue);
}

function loadFeesBookingsCancel() {
  feesContentLoadMoreReady = true;
  if (xhrFeesLoadBookings != null) {
    xhrFeesLoadBookings.abort();
  }
}

function renderBookingsRow(bookingStatus, bookingID, plcSts, plcID, plcName, fromD, fromM, fromY, toD, toM, toY, currency, totalFee, percentageFee, wShowMore) {
  var tableRow = document.createElement("tr");
  tableRow.setAttribute("class", "fees-list-table-row");
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
  var tdTotalFee = document.createElement("td");
  tdTotalFee.innerHTML = addCurrency(currency, totalFee);
  var tdFeePercentage = document.createElement("td");
  tdFeePercentage.innerHTML = parseFloat(percentageFee) +"%";
  var tdShowMore = document.createElement("td");
  var aShowMore = document.createElement("a");
  aShowMore.setAttribute("class", "table-row-link-blue");
  aShowMore.setAttribute("href", "../bookings/about-guest-booking.php?id="+ bookingID);
  aShowMore.innerHTML = wShowMore;
  tableRow.appendChild(tdStatus);
  tableRow.appendChild(tdPlace);
  tableRow.appendChild(tdDates);
  tableRow.appendChild(tdTotalFee);
  tableRow.appendChild(tdFeePercentage);
  tableRow.appendChild(tdShowMore);
  tdShowMore.appendChild(aShowMore);
  document.getElementById("fees-list-table-bookings").appendChild(tableRow);
}

var searchText;
var lastNumOfLetters = 0;
var searchBtnActive = false;
function feesContentSearchType(inpt) {
  searchText = inpt.value;
  if (searchText.length >= 3 || searchBtnActive) {
    if (searchText == "") {
      searchBookingReady = false;
      searchBtnActive = false;
    } else {
      searchBookingReady = true;
      searchBtnActive = true;
    }
    loadFeesContent(searchText, "", true);
  } else {
    if (lastNumOfLetters > 2) {
      searchBookingReady = false;
      searchBtnActive = false;
      loadFeesContent("", "", true);
    }
  }
  lastNumOfLetters = searchText.length;
}

function feesContentSearchCancel() {
  document.getElementById("fees-list-search-bar-input").value = "";
  searchBookingReady = false;
  searchBtnActive = false;
  loadFeesContent("", "", true);
}

function feesContentSearchBtn() {
  searchText = document.getElementById("fees-list-search-bar-input").value;
  if (searchText != "") {
    searchBtnActive = true;
    searchBookingReady = true;
    loadFeesContent(searchText, "", true);
  }
}

function feesListBtnManager(task, id) {
  if (task == "def") {
    document.getElementById(id).style.color = "";
    document.getElementById(id).style.backgroundImage = "";
    document.getElementById(id).style.backgroundSize = "";
  } else if (task == "load") {
    document.getElementById(id).style.color = "rgba(0,0,0,0)";
    document.getElementById(id).style.backgroundImage = "url('../uni/gifs/loader-tail.svg')";
    document.getElementById(id).style.backgroundSize = "auto 63%";
  } else if (task == "success") {
    document.getElementById(id).style.color = "rgba(0,0,0,0)";
    document.getElementById(id).style.backgroundImage = "url('../uni/icons/ok2.svg')";
    document.getElementById(id).style.backgroundSize = "auto 47%";
  }
}
