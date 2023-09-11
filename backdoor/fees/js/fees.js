var wrd_searchByReferenceCodeOrHost = "Search by reference code or host";
var wrd_searchForHostByNameOrPeriod = "Search for a host by name or period";
var wrd_searchForBookingByPlaceHostDate = "Search for a booking by place, host or date";
function feesDictionary(
  searchByReferenceCodeOrHost,
  searchForHostByNameOrPeriod,
  searchForBookingByPlaceHostDate
) {
  wrd_searchByReferenceCodeOrHost = searchByReferenceCodeOrHost;
  wrd_searchForHostByNameOrPeriod = searchForHostByNameOrPeriod;
  wrd_searchForBookingByPlaceHostDate = searchForBookingByPlaceHostDate;
}

var searchBookingReady = false;
function backDoorFeesSwitchContent(cont) {
  searchBookingReady = false;
  loadFeesContent(document.getElementById("table-filter-search-bar-input-fees").value, "", 0, 0, true);
  if (cont == "hosts") {
    document.getElementById("table-filter-search-bar-input-fees").placeholder = wrd_searchForHostByNameOrPeriod;
    document.getElementById("b-d-fees-table-payment-reference-wrp").classList.remove("b-d-fees-table-selected");
    document.getElementById("b-d-fees-table-hosts-wrp").classList.add("b-d-fees-table-selected");
    document.getElementById("b-d-fees-table-bookings-wrp").classList.remove("b-d-fees-table-selected");
  } else if (cont == "bookings") {
    document.getElementById("table-filter-search-bar-input-fees").placeholder = wrd_searchForBookingByPlaceHostDate;
    document.getElementById("b-d-fees-table-payment-reference-wrp").classList.remove("b-d-fees-table-selected");
    document.getElementById("b-d-fees-table-hosts-wrp").classList.remove("b-d-fees-table-selected");
    document.getElementById("b-d-fees-table-bookings-wrp").classList.add("b-d-fees-table-selected");
  } else {
    document.getElementById("table-filter-search-bar-input-fees").placeholder = wrd_searchByReferenceCodeOrHost;
    document.getElementById("b-d-fees-table-payment-reference-wrp").classList.add("b-d-fees-table-selected");
    document.getElementById("b-d-fees-table-hosts-wrp").classList.remove("b-d-fees-table-selected");
    document.getElementById("b-d-fees-table-bookings-wrp").classList.remove("b-d-fees-table-selected");
  }
}

var feesContentLoadMoreReady = true;
var searchValue, lastID, lastY, lastM;
function feesContentLoadMore() {
  if (feesContentLoadMoreReady) {
    feesContentLoadMoreReady = false;
    backDoorBtnManager("load", "b-d-fees-table-tools-load-more-btn");
    if (searchBookingReady) {
      searchValue = document.getElementById("table-filter-search-bar-input-fees").value;
    } else {
      searchValue = "";
    }
    if (document.getElementsByClassName("b-d-fees-table-row").length > 0) {
      lastID = document.getElementById("b-d-fees-table-about-last-id").innerHTML;
      lastY = document.getElementById("b-d-fees-table-about-last-y").innerHTML;
      lastM = document.getElementById("b-d-fees-table-about-last-m").innerHTML;
    } else {
      lastID = "";
      lastY = 0;
      lastM = 0;
    }
    loadFeesContent(searchValue, lastID, lastY, lastM, false);
  }
}

function loadFeesContent(searchValue, lastID, lastY, lastM, resetTable) {
  loadFeesPaymentReferenceCancel();
  loadFeesBookingsCancel();
  loadFeesHostsCancel();
  if (document.getElementById("b-d-fees-table-filter-switch-content").value == "hosts") {
    loadFeesHosts(searchValue, lastID, lastY, lastM, resetTable);
  } else if (document.getElementById("b-d-fees-table-filter-switch-content").value == "bookings") {
    loadFeesBookings(searchValue, lastID, resetTable);
  } else {
    loadFeesPaymentReference(searchValue, lastID, resetTable);
  }
}

var xhrFeesLoadPaymentReference, tableRowsNum, outputLastID, outputLastY, outputLastM;
function loadFeesPaymentReference(searchValue, lastID, resetTable) {
  if (resetTable) {
    tableRowsNum = document.getElementsByClassName("b-d-fees-table-row").length;
    for (var rT = 0; rT < tableRowsNum; rT++) {
      document.getElementsByClassName("b-d-fees-table-row")[0].parentNode.removeChild(document.getElementsByClassName("b-d-fees-table-row")[0]);
    }
    document.getElementById("b-d-fees-table-tools-load-more-wrp").style.display = "";
    document.getElementById("b-d-fees-table-tools-loader-wrp").style.display = "flex";
  } else {
    document.getElementById("b-d-fees-table-tools-load-more-wrp").style.display = "flex";
    document.getElementById("b-d-fees-table-tools-loader-wrp").style.display = "";
  }
  document.getElementById("b-d-fees-table-tools-no-content-wrp").style.display = "";
  document.getElementById("b-d-fees-table-tools-errors-wrp").style.display = "";
  document.getElementById("b-d-fees-table-tools-errors-txt").innerHTML = "";
  outputLastID = "";
  xhrFeesLoadPaymentReference = new XMLHttpRequest();
  xhrFeesLoadPaymentReference.onreadystatechange = function() {
    if (xhrFeesLoadPaymentReference.readyState == 4 && xhrFeesLoadPaymentReference.status == 200) {
      feesContentLoadMoreReady = true;
      document.getElementById("b-d-fees-table-tools-loader-wrp").style.display = "";
      backDoorBtnManager("def", "b-d-fees-table-tools-load-more-btn");
      if (testJSON(xhrFeesLoadPaymentReference.response)) {
        var json = JSON.parse(xhrFeesLoadPaymentReference.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "payment-reference") {
              renderPaymentReferenceRow(
                json[key]["status"],
                json[key]["hostID"],
                json[key]["hostName"],
                json[key]["payment-reference"],
                json[key]["numOfBookings"],
                json[key]["currency"],
                json[key]["totalFee"],
                json[key]["paymentStatus"],
                json[key]["paymentBtnTxt"],
                json[key]["wShowMore"]
              );
              outputLastID = json[key]["payment-reference"];
            } else if (json[key]["type"] == "load-amount") {
              if (json[key]["remain"] > 0) {
                document.getElementById("b-d-fees-table-tools-load-more-wrp").style.display = "flex";
              } else {
                document.getElementById("b-d-fees-table-tools-load-more-wrp").style.display = "";
              }
              if (json[key]["all-bookings"] > 0) {
                document.getElementById("b-d-fees-table-tools-no-content-wrp").style.display = "";
              } else {
                document.getElementById("b-d-fees-table-tools-no-content-wrp").style.display = "flex";
              }
            } else if (json[key]["type"] == "error") {
              document.getElementById("b-d-fees-table-tools-errors-wrp").style.display = "flex";
              document.getElementById("b-d-fees-table-tools-errors-txt").innerHTML = json[key]["error"];
            }
          }
        }
        document.getElementById("b-d-fees-table-about-last-id").innerHTML = outputLastID;
      } else {
        document.getElementById("b-d-fees-table-tools-errors-wrp").style.display = "flex";
        document.getElementById("b-d-fees-table-tools-errors-txt").innerHTML = xhrFeesLoadPaymentReference.response;
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

function renderPaymentReferenceRow(bookingStatus, hostID, hostName, paymentReferenceCode, numOfBookings, currency, totalFee, paymentStatus, paymentBtnTxt, wShowMore) {
  var tableRow = document.createElement("tr");
  tableRow.setAttribute("class", "b-d-fees-table-row");
  var tdStatus = document.createElement("td");
  tdStatus.setAttribute("id", "b-d-fees-table-row-status-"+ paymentReferenceCode);
  tdStatus.innerHTML = bookingStatus;
  var tdHost = document.createElement("td");
  var aHost = document.createElement("a");
  aHost.setAttribute("href", "../user/?section=users&nav=about&id="+ hostID +"&m=&y=&paymentreference=");
  aHost.setAttribute("target", "_blank");
  aHost.innerHTML = hostName;
  var tdPaymentReference = document.createElement("td");
  tdPaymentReference.innerHTML = paymentReferenceCode;
  var tdNumOfBookings = document.createElement("td");
  tdNumOfBookings.innerHTML = numOfBookings;
  var tdTotalFee = document.createElement("td");
  tdTotalFee.innerHTML = addCurrency(currency, totalFee);
  var tdPaymentStatus = document.createElement("td");
  var btnPaymentStatus = document.createElement("button");
  btnPaymentStatus.setAttribute("id", "b-d-fees-table-payment-btn-"+ paymentReferenceCode);
  btnPaymentStatus.setAttribute("class", "btn");
  btnPaymentStatus.classList.add("btn-mid");
  btnPaymentStatus.classList.add("btn-sec");
  btnPaymentStatus.classList.add("b-d-fees-table-payment-btn");
  btnPaymentStatus.innerHTML = paymentBtnTxt;
  btnPaymentStatus.setAttribute("onclick", "togglePaymentReferenceFeesPaymentStatus('"+ paymentReferenceCode +"')");
  btnPaymentStatus.setAttribute("value", "ready");
  if (paymentStatus != "unpaid" && paymentStatus != "paid") {
    btnPaymentStatus.style.display = "none";
  }
  var tdShowMore = document.createElement("td");
  var aShowMore = document.createElement("a");
  aShowMore.setAttribute("class", "table-row-link-blue");
  aShowMore.setAttribute("href", "../user/?section=fees&nav=hostbookings&id="+ hostID +"&m=&y=&paymentreference="+ paymentReferenceCode);
  aShowMore.innerHTML = wShowMore;
  tableRow.appendChild(tdStatus);
  tableRow.appendChild(tdHost);
  tdHost.appendChild(aHost);
  tableRow.appendChild(tdPaymentReference);
  tableRow.appendChild(tdNumOfBookings);
  tableRow.appendChild(tdTotalFee);
  tableRow.appendChild(tdPaymentStatus);
  tdPaymentStatus.appendChild(btnPaymentStatus);
  tableRow.appendChild(tdShowMore);
  tdShowMore.appendChild(aShowMore);
  document.getElementById("b-d-fees-table-payment-reference").appendChild(tableRow);
}

var xhrFeesLoadHosts;
function loadFeesHosts(searchValue, lastID, lastY, lastM, resetTable) {
  if (resetTable) {
    tableRowsNum = document.getElementsByClassName("b-d-fees-table-row").length;
    for (var rT = 0; rT < tableRowsNum; rT++) {
      document.getElementsByClassName("b-d-fees-table-row")[0].parentNode.removeChild(document.getElementsByClassName("b-d-fees-table-row")[0]);
    }
    document.getElementById("b-d-fees-table-tools-load-more-wrp").style.display = "";
    document.getElementById("b-d-fees-table-tools-loader-wrp").style.display = "flex";
  } else {
    document.getElementById("b-d-fees-table-tools-load-more-wrp").style.display = "flex";
    document.getElementById("b-d-fees-table-tools-loader-wrp").style.display = "";
  }
  document.getElementById("b-d-fees-table-tools-no-content-wrp").style.display = "";
  document.getElementById("b-d-fees-table-tools-errors-wrp").style.display = "";
  document.getElementById("b-d-fees-table-tools-errors-txt").innerHTML = "";
  outputLastID = "";
  outputLastY = "0";
  outputLastM = "0";
  xhrFeesLoadHosts = new XMLHttpRequest();
  xhrFeesLoadHosts.onreadystatechange = function() {
    if (xhrFeesLoadHosts.readyState == 4 && xhrFeesLoadHosts.status == 200) {
      feesContentLoadMoreReady = true;
      document.getElementById("b-d-fees-table-tools-loader-wrp").style.display = "";
      backDoorBtnManager("def", "b-d-fees-table-tools-load-more-btn");
      if (testJSON(xhrFeesLoadHosts.response)) {
        var json = JSON.parse(xhrFeesLoadHosts.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "hosts") {
              renderHostsRow(
                json[key]["status"],
                json[key]["id"],
                json[key]["username"],
                json[key]["periodM"],
                json[key]["periodY"],
                json[key]["numOfBookings"],
                json[key]["currency"],
                json[key]["totalPrice"],
                json[key]["totalFee"],
                json[key]["paymentStatus"],
                json[key]["paymentBtnTxt"],
                json[key]["wShowMore"]
              );
              outputLastID = json[key]["id"];
              outputLastY = json[key]["periodY"];
              outputLastM = json[key]["periodM"];
            } else if (json[key]["type"] == "load-amount") {
              if (json[key]["remain"] > 0) {
                document.getElementById("b-d-fees-table-tools-load-more-wrp").style.display = "flex";
              } else {
                document.getElementById("b-d-fees-table-tools-load-more-wrp").style.display = "";
              }
              if (json[key]["all-bookings"] > 0) {
                document.getElementById("b-d-fees-table-tools-no-content-wrp").style.display = "";
              } else {
                document.getElementById("b-d-fees-table-tools-no-content-wrp").style.display = "flex";
              }
            } else if (json[key]["type"] == "error") {
              document.getElementById("b-d-fees-table-tools-errors-wrp").style.display = "flex";
              document.getElementById("b-d-fees-table-tools-errors-txt").innerHTML = json[key]["error"];
            }
          }
        }
        document.getElementById("b-d-fees-table-about-last-id").innerHTML = outputLastID;
        document.getElementById("b-d-fees-table-about-last-y").innerHTML = outputLastY;
        document.getElementById("b-d-fees-table-about-last-m").innerHTML = outputLastM;
      } else {
        document.getElementById("b-d-fees-table-tools-errors-wrp").style.display = "flex";
        document.getElementById("b-d-fees-table-tools-errors-txt").innerHTML = xhrFeesLoadHosts.response;
      }
    }
  }
  xhrFeesLoadHosts.open("POST", "php-backend/get-list-of-hosts-fees-manager.php");
  xhrFeesLoadHosts.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrFeesLoadHosts.send("lastID="+ lastID +"&lastY="+ lastY +"&lastM="+ lastM +"&search="+ searchValue);
}

function loadFeesHostsCancel() {
  feesContentLoadMoreReady = true;
  if (xhrFeesLoadHosts != null) {
    xhrFeesLoadHosts.abort();
  }
}

function renderHostsRow(bookingStatus, hostID, username, periodM, periodY, numOfBookings, currency, totalPrice, totalFee, paymentStatus, paymentBtnTxt, wShowMore) {
  var tableRow = document.createElement("tr");
  tableRow.setAttribute("class", "b-d-fees-table-row");
  var tdStatus = document.createElement("td");
  tdStatus.setAttribute("id", "b-d-fees-table-row-status-"+ hostID +"-"+ periodM +"-"+ periodY);
  tdStatus.innerHTML = bookingStatus;
  var tdUsername = document.createElement("td");
  var aUsername = document.createElement("a");
  aUsername.setAttribute("href", "../user/?section=users&nav=about&id="+ hostID +"&m=&y=&paymentreference=");
  aUsername.setAttribute("target", "_blank");
  aUsername.innerHTML = username;
  var tdPeriod = document.createElement("td");
  tdPeriod.innerHTML = periodM +". "+ periodY;
  var tdNumOfBookings = document.createElement("td");
  tdNumOfBookings.innerHTML = numOfBookings;
  var tdTotalPrice = document.createElement("td");
  tdTotalPrice.innerHTML = addCurrency(currency, totalPrice);
  var tdTotalFee = document.createElement("td");
  tdTotalFee.innerHTML = addCurrency(currency, totalFee);
  var tdPaymentStatus = document.createElement("td");
  var btnPaymentStatus = document.createElement("button");
  btnPaymentStatus.setAttribute("id", "b-d-fees-table-payment-btn-"+ hostID +"-"+ periodM +"-"+ periodY);
  btnPaymentStatus.setAttribute("class", "btn");
  btnPaymentStatus.classList.add("btn-mid");
  btnPaymentStatus.classList.add("btn-sec");
  btnPaymentStatus.classList.add("b-d-fees-table-payment-btn");
  btnPaymentStatus.innerHTML = paymentBtnTxt;
  btnPaymentStatus.setAttribute("onclick", "togglePeriodFeesPaymentStatus('"+ hostID +"', '"+ periodM +"', '"+ periodY +"')");
  btnPaymentStatus.setAttribute("value", "ready");
  if (paymentStatus != "unpaid" && paymentStatus != "paid") {
    btnPaymentStatus.style.display = "none";
  }
  var tdShowMore = document.createElement("td");
  var aShowMore = document.createElement("a");
  aShowMore.setAttribute("class", "table-row-link-blue");
  aShowMore.setAttribute("href", "../user/?section=fees&nav=hostbookings&id="+ hostID +"&m="+ periodM +"&y="+ periodY);
  aShowMore.innerHTML = wShowMore;
  tableRow.appendChild(tdStatus);
  tableRow.appendChild(tdUsername);
  tdUsername.appendChild(aUsername);
  tableRow.appendChild(tdPeriod);
  tableRow.appendChild(tdNumOfBookings);
  tableRow.appendChild(tdTotalPrice);
  tableRow.appendChild(tdTotalFee);
  tableRow.appendChild(tdPaymentStatus);
  tdPaymentStatus.appendChild(btnPaymentStatus);
  tableRow.appendChild(tdShowMore);
  tdShowMore.appendChild(aShowMore);
  document.getElementById("b-d-fees-table-hosts").appendChild(tableRow);
}

var xhrFeesLoadBookings;
function loadFeesBookings(searchValue, lastID, resetTable) {
  if (resetTable) {
    tableRowsNum = document.getElementsByClassName("b-d-fees-table-row").length;
    for (var rT = 0; rT < tableRowsNum; rT++) {
      document.getElementsByClassName("b-d-fees-table-row")[0].parentNode.removeChild(document.getElementsByClassName("b-d-fees-table-row")[0]);
    }
    document.getElementById("b-d-fees-table-tools-load-more-wrp").style.display = "";
    document.getElementById("b-d-fees-table-tools-loader-wrp").style.display = "flex";
  } else {
    document.getElementById("b-d-fees-table-tools-load-more-wrp").style.display = "flex";
    document.getElementById("b-d-fees-table-tools-loader-wrp").style.display = "";
  }
  document.getElementById("b-d-fees-table-tools-no-content-wrp").style.display = "";
  document.getElementById("b-d-fees-table-tools-errors-wrp").style.display = "";
  document.getElementById("b-d-fees-table-tools-errors-txt").innerHTML = "";
  outputLastID = "0";
  xhrFeesLoadBookings = new XMLHttpRequest();
  xhrFeesLoadBookings.onreadystatechange = function() {
    if (xhrFeesLoadBookings.readyState == 4 && xhrFeesLoadBookings.status == 200) {
      feesContentLoadMoreReady = true;
      document.getElementById("b-d-fees-table-tools-loader-wrp").style.display = "";
      backDoorBtnManager("def", "b-d-fees-table-tools-load-more-btn");
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
                json[key]["hostID"],
                json[key]["hostName"],
                json[key]["fromD"],
                json[key]["fromM"],
                json[key]["fromY"],
                json[key]["toD"],
                json[key]["toM"],
                json[key]["toY"],
                json[key]["currency"],
                json[key]["totalPrice"],
                json[key]["fee"],
                json[key]["percentageFee"],
                json[key]["wShowMore"],
              );
              outputLastID = json[key]["bookingID"];
            } else if (json[key]["type"] == "load-amount") {
              if (json[key]["remain"] > 0) {
                document.getElementById("b-d-fees-table-tools-load-more-wrp").style.display = "flex";
              } else {
                document.getElementById("b-d-fees-table-tools-load-more-wrp").style.display = "";
              }
              if (json[key]["all-bookings"] > 0) {
                document.getElementById("b-d-fees-table-tools-no-content-wrp").style.display = "";
              } else {
                document.getElementById("b-d-fees-table-tools-no-content-wrp").style.display = "flex";
              }
            } else if (json[key]["type"] == "error") {
              document.getElementById("b-d-fees-table-tools-errors-wrp").style.display = "flex";
              document.getElementById("b-d-fees-table-tools-errors-txt").innerHTML = json[key]["error"];
            }
          }
        }
        document.getElementById("b-d-fees-table-about-last-id").innerHTML = outputLastID;
      } else {
        document.getElementById("b-d-fees-table-tools-errors-wrp").style.display = "flex";
        document.getElementById("b-d-fees-table-tools-errors-txt").innerHTML = xhrFeesLoadBookings.response;
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

function renderBookingsRow(bookingStatus, bookingID, plcSts, plcID, plcName, hostID, hostName, fromD, fromM, fromY, toD, toM, toY, currency, totalPrice, totalFee, percentageFee, wShowMore) {
  var tableRow = document.createElement("tr");
  tableRow.setAttribute("class", "b-d-fees-table-row");
  var tdStatus = document.createElement("td");
  tdStatus.innerHTML = bookingStatus;
  var tdPlace = document.createElement("td");
  tdPlace.innerHTML = plcName;
  var tdHost = document.createElement("td");
  var aHost = document.createElement("a");
  aHost.setAttribute("href", "../user/?section=users&nav=about&id="+ hostID +"&m=&y=&paymentreference=");
  aHost.setAttribute("target", "_blank");
  aHost.innerHTML = hostName;
  var tdDates = document.createElement("td");
  tdDates.innerHTML = fromD +"."+ fromM +"."+ fromY +" - "+ toD +"."+ toM +"."+ toY;
  var tdTotalPrice = document.createElement("td");
  tdTotalPrice.innerHTML = addCurrency(currency, totalPrice);
  var tdTotalFee = document.createElement("td");
  tdTotalFee.innerHTML = addCurrency(currency, totalFee);
  var tdFeePercentage = document.createElement("td");
  tdFeePercentage.innerHTML = parseFloat(percentageFee) +"%";
  var tdShowMore = document.createElement("td");
  var aShowMore = document.createElement("a");
  aShowMore.setAttribute("class", "table-row-link-blue");
  aShowMore.setAttribute("href", "../booking/?section=fees&nav=about&id="+ bookingID);
  aShowMore.innerHTML = wShowMore;
  tableRow.appendChild(tdStatus);
  tableRow.appendChild(tdPlace);
  tableRow.appendChild(tdHost);
  tdHost.appendChild(aHost);
  tableRow.appendChild(tdDates);
  tableRow.appendChild(tdTotalPrice);
  tableRow.appendChild(tdTotalFee);
  tableRow.appendChild(tdFeePercentage);
  tableRow.appendChild(tdShowMore);
  tdShowMore.appendChild(aShowMore);
  document.getElementById("b-d-fees-table-bookings").appendChild(tableRow);
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
    loadFeesContent(searchText, "", 0, 0, true);
  } else {
    if (lastNumOfLetters > 2) {
      searchBookingReady = false;
      searchBtnActive = false;
      loadFeesContent("", "", 0, 0, true);
    }
  }
  lastNumOfLetters = searchText.length;
}

function feesContentSearchCancel() {
  document.getElementById("table-filter-search-bar-input-fees").value = "";
  searchBookingReady = false;
  searchBtnActive = false;
  loadFeesContent("", "", 0, 0, true);
}

function feesContentSearchBtn() {
  searchText = document.getElementById("table-filter-search-bar-input-fees").value;
  if (searchText != "") {
    searchBtnActive = true;
    searchBookingReady = true;
    loadFeesContent(searchText, "", 0, 0, true);
  }
}

var bookingPaymentStsToggleTimer;
function togglePeriodFeesPaymentStatus(hostID, periodM, periodY) {
  if (document.getElementById("b-d-fees-table-payment-btn-"+ hostID +"-"+ periodM +"-"+ periodY).value == "ready") {
    document.getElementById("b-d-fees-table-payment-btn-"+ hostID +"-"+ periodM +"-"+ periodY).value = "in-use";
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    backDoorBtnManager("load", "b-d-fees-table-payment-btn-"+ hostID +"-"+ periodM +"-"+ periodY);
    var xhrPaymentToggle = new XMLHttpRequest();
    xhrPaymentToggle.onreadystatechange = function() {
      if (xhrPaymentToggle.readyState == 4 && xhrPaymentToggle.status == 200) {
        document.getElementById("b-d-fees-table-payment-btn-"+ hostID +"-"+ periodM +"-"+ periodY).value = "ready";
        window.onbeforeunload = null;
        if (testJSON(xhrPaymentToggle.response)) {
          var json = JSON.parse(xhrPaymentToggle.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                backDoorBtnManager("success", "b-d-fees-table-payment-btn-"+ hostID +"-"+ periodM +"-"+ periodY);
                bookingPaymentStsToggleTimer = setTimeout(function(){
                  if (json[key]["task"] == "paid") {
                    if (document.getElementById("b-d-fees-table-payment-btn-"+ hostID +"-"+ periodM +"-"+ periodY)) {
                      document.getElementById("b-d-fees-table-payment-btn-"+ hostID +"-"+ periodM +"-"+ periodY).innerHTML = json[key]["unpaid"];
                    }
                  } else {
                    if (document.getElementById("b-d-fees-table-payment-btn-"+ hostID +"-"+ periodM +"-"+ periodY)) {
                      document.getElementById("b-d-fees-table-payment-btn-"+ hostID +"-"+ periodM +"-"+ periodY).innerHTML = json[key]["paid"];
                    }
                  }
                  if (document.getElementById("b-d-fees-table-row-status-"+ hostID +"-"+ periodM +"-"+ periodY)) {
                    document.getElementById("b-d-fees-table-row-status-"+ hostID +"-"+ periodM +"-"+ periodY).innerHTML = json[key]["status"];
                  }
                  backDoorBtnManager("def", "b-d-fees-table-payment-btn-"+ hostID +"-"+ periodM +"-"+ periodY);
                }, 1000);
              } else if (json[key]["type"] == "error") {
                backDoorBtnManager("def", "b-d-fees-table-payment-btn-"+ hostID +"-"+ periodM +"-"+ periodY);
                alert(json[key]["error"]);
              }
            }
          }
        } else {
          backDoorBtnManager("def", "b-d-fees-table-payment-btn-"+ hostID +"-"+ periodM +"-"+ periodY);
          alert(xhrPaymentToggle.response);
        }
      }
    }
    xhrPaymentToggle.open("POST", "php-backend/host-period-payment-status-manager.php");
    xhrPaymentToggle.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrPaymentToggle.send("hostID="+ hostID +"&periodM="+ periodM +"&periodY="+ periodY);
  }
}

function togglePaymentReferenceFeesPaymentStatus(paymentReferenceCode) {
  if (document.getElementById("b-d-fees-table-payment-btn-"+ paymentReferenceCode).value == "ready") {
    document.getElementById("b-d-fees-table-payment-btn-"+ paymentReferenceCode).value = "in-use";
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    backDoorBtnManager("load", "b-d-fees-table-payment-btn-"+ paymentReferenceCode);
    var xhrPayRefPaymentToggle = new XMLHttpRequest();
    xhrPayRefPaymentToggle.onreadystatechange = function() {
      if (xhrPayRefPaymentToggle.readyState == 4 && xhrPayRefPaymentToggle.status == 200) {
        document.getElementById("b-d-fees-table-payment-btn-"+ paymentReferenceCode).value = "ready";
        window.onbeforeunload = null;
        if (testJSON(xhrPayRefPaymentToggle.response)) {
          var json = JSON.parse(xhrPayRefPaymentToggle.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                loadFeesPaymentReference(document.getElementById("table-filter-search-bar-input-fees").value, "", true);
              } else if (json[key]["type"] == "error") {
                backDoorBtnManager("def", "b-d-fees-table-payment-btn-"+ paymentReferenceCode);
                alert(json[key]["error"]);
              }
            }
          }
        } else {
          backDoorBtnManager("def", "b-d-fees-table-payment-btn-"+ paymentReferenceCode);
          alert(xhrPayRefPaymentToggle.response);
        }
      }
    }
    xhrPayRefPaymentToggle.open("POST", "php-backend/payment-reference-payment-status-manager.php");
    xhrPayRefPaymentToggle.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrPayRefPaymentToggle.send("paymentReference="+ paymentReferenceCode);
  }
}
