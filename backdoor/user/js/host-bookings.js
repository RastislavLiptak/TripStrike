var url_string = window.location.href;
var url = new URL(url_string);
var user_id = url.searchParams.get("id");
var contentSection = url.searchParams.get("section");
var urlPaymentReference = url.searchParams.get("paymentreference");

var hostBookingsLoadMoreReady = true;
var searchBookingReady = false;
if (urlPaymentReference != "") {
  var searchPaymentReferenceReady = true;
} else {
  var searchPaymentReferenceReady = false;
}
var searchValue, paymentReference, lastID, periodY, periodM;
function hostBookingsLoadMore() {
  if (hostBookingsLoadMoreReady) {
    hostBookingsLoadMoreReady = false;
    backDoorBtnManager("load", "b-d-host-bookings-table-tools-load-more-btn");
    if (searchBookingReady) {
      searchValue = document.getElementById("table-filter-search-bar-input-host-bookings").value;
    } else {
      searchValue = "";
    }
    if (document.getElementsByClassName("b-d-host-bookings-table-row").length > 0) {
      lastID = document.getElementById("b-d-host-bookings-table-about-last-id").innerHTML;
    } else {
      lastID = "";
    }
    if (searchPaymentReferenceReady) {
      paymentReference = document.getElementById("table-filter-search-bar-input-host-bookings-payment-reference").value;
    } else {
      paymentReference = "";
    }
    hostBookingsPeriodFilter(searchValue, lastID, paymentReference, false);
  }
}

var searchText;
var lastNumOfLetters = 0;
var searchBtnActive = false;
function hostBookingsSearchType(inpt) {
  searchText = inpt.value;
  paymentReference = document.getElementById("table-filter-search-bar-input-host-bookings-payment-reference").value;
  if (searchText.length >= 3 || searchBtnActive) {
    if (searchText == "") {
      searchBookingReady = false;
      searchBtnActive = false;
    } else {
      searchBookingReady = true;
      searchBtnActive = true;
    }
    hostBookingsPeriodFilter(searchText, "", paymentReference, true);
  } else {
    if (lastNumOfLetters > 2) {
      searchBookingReady = false;
      searchBtnActive = false;
      hostBookingsPeriodFilter("", "", paymentReference, true);
    }
  }
  lastNumOfLetters = searchText.length;
}

function hostBookingsSearchCancel() {
  document.getElementById("table-filter-search-bar-input-host-bookings").value = "";
  paymentReference = document.getElementById("table-filter-search-bar-input-host-bookings-payment-reference").value;
  searchBookingReady = false;
  searchBtnActive = false;
  hostBookingsPeriodFilter("", "", paymentReference, true);
}

function hostBookingsSearchBtn() {
  searchText = document.getElementById("table-filter-search-bar-input-host-bookings").value;
  paymentReference = document.getElementById("table-filter-search-bar-input-host-bookings-payment-reference").value;
  if (searchText != "") {
    searchBtnActive = true;
    searchBookingReady = true;
    hostBookingsPeriodFilter(searchText, "", paymentReference, true);
  }
}

var lastNumOfLettersPayR = 0;
var searchPayRBtnActive = false;
function hostBookingsSearchPayRType(inpt) {
  searchText = document.getElementById("table-filter-search-bar-input-host-bookings").value;
  paymentReference = inpt.value;
  if (paymentReference.length >= 3 || searchPayRBtnActive) {
    if (paymentReference == "") {
      searchPaymentReferenceReady = false;
      searchPayRBtnActive = false;
    } else {
      searchPaymentReferenceReady = true;
      searchPayRBtnActive = true;
    }
    hostBookingsPeriodFilter(searchText, "", paymentReference, true);
  } else {
    if (lastNumOfLettersPayR > 2) {
      searchPaymentReferenceReady = false;
      searchPayRBtnActive = false;
      hostBookingsPeriodFilter(searchText, "", "", true);
    }
  }
  lastNumOfLettersPayR = paymentReference.length;
}

function hostBookingsSearchPayRCancel() {
  searchText = document.getElementById("table-filter-search-bar-input-host-bookings").value;
  document.getElementById("table-filter-search-bar-input-host-bookings-payment-reference").value = "";
  searchPaymentReferenceReady = false;
  searchPayRBtnActive = false;
  hostBookingsPeriodFilter(searchText, "", "", true);
}

function hostBookingsSearchPayRBtn() {
  searchText = document.getElementById("table-filter-search-bar-input-host-bookings").value;
  paymentReference = document.getElementById("table-filter-search-bar-input-host-bookings-payment-reference").value;
  if (paymentReference != "") {
    searchPayRBtnActive = true;
    searchPaymentReferenceReady = true;
    hostBookingsPeriodFilter(searchText, "", paymentReference, true);
  }
}

function hostBookingsPeriodFilterOnchange() {
  searchText = document.getElementById("table-filter-search-bar-input-host-bookings").value;
  paymentReference = document.getElementById("table-filter-search-bar-input-host-bookings-payment-reference").value;
  hostBookingsPeriodFilter(searchText, "", paymentReference, true);
}

function hostBookingsPeriodFilter(searchValue, lastID, paymentReference, resetTable) {
  periodY = document.getElementById("b-d-host-bookings-table-filter-years").value;
  periodM = document.getElementById("b-d-host-bookings-table-filter-months").value;
  loadHostBookings(searchValue, lastID, periodY, periodM, paymentReference, resetTable);
}

var xhrLoadHostBookings;
function loadHostBookings(searchValue, lastID, periodY, periodM, paymentReference, resetTable) {
  loadHostBookingsCancel();
  if (resetTable) {
    tableRowsNum = document.getElementsByClassName("b-d-host-bookings-table-row").length;
    for (var rT = 0; rT < tableRowsNum; rT++) {
      document.getElementsByClassName("b-d-host-bookings-table-row")[0].parentNode.removeChild(document.getElementsByClassName("b-d-host-bookings-table-row")[0]);
    }
    document.getElementById("b-d-host-bookings-table-tools-load-more-wrp").style.display = "";
    document.getElementById("b-d-host-bookings-table-tools-loader-wrp").style.display = "flex";
  } else {
    document.getElementById("b-d-host-bookings-table-tools-load-more-wrp").style.display = "flex";
    document.getElementById("b-d-host-bookings-table-tools-loader-wrp").style.display = "";
  }
  document.getElementById("b-d-host-bookings-table-tools-no-content-wrp").style.display = "";
  document.getElementById("b-d-host-bookings-table-tools-errors-wrp").style.display = "";
  document.getElementById("b-d-host-bookings-table-tools-errors-txt").innerHTML = "";
  outputLastID = "";
  xhrLoadHostBookings = new XMLHttpRequest();
  xhrLoadHostBookings.onreadystatechange = function() {
    if (xhrLoadHostBookings.readyState == 4 && xhrLoadHostBookings.status == 200) {
      hostBookingsLoadMoreReady = true;
      document.getElementById("b-d-host-bookings-table-tools-loader-wrp").style.display = "";
      backDoorBtnManager("def", "b-d-host-bookings-table-tools-load-more-btn");
      if (testJSON(xhrLoadHostBookings.response)) {
        var json = JSON.parse(xhrLoadHostBookings.response);
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
                json[key]["totalPrice"],
                json[key]["fee"],
                json[key]["percentageFee"],
                json[key]["wShowMore"]
              );
              outputLastID = json[key]["bookingID"];
            } else if (json[key]["type"] == "load-amount") {
              if (json[key]["remain"] > 0) {
                document.getElementById("b-d-host-bookings-table-tools-load-more-wrp").style.display = "flex";
              } else {
                document.getElementById("b-d-host-bookings-table-tools-load-more-wrp").style.display = "";
              }
              if (json[key]["all-bookings"] > 0) {
                document.getElementById("b-d-host-bookings-table-tools-no-content-wrp").style.display = "";
              } else {
                document.getElementById("b-d-host-bookings-table-tools-no-content-wrp").style.display = "flex";
              }
            } else if (json[key]["type"] == "error") {
              document.getElementById("b-d-host-bookings-table-tools-errors-wrp").style.display = "flex";
              document.getElementById("b-d-host-bookings-table-tools-errors-txt").innerHTML = json[key]["error"];
            }
          }
        }
        document.getElementById("b-d-host-bookings-table-about-last-id").innerHTML = outputLastID;
      } else {
        document.getElementById("b-d-host-bookings-table-tools-errors-wrp").style.display = "flex";
        document.getElementById("b-d-host-bookings-table-tools-errors-txt").innerHTML = xhrLoadHostBookings.response;
      }
    }
  }
  xhrLoadHostBookings.open("POST", "php-backend/get-list-of-host-bookings-manager.php");
  xhrLoadHostBookings.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrLoadHostBookings.send("lastID="+ lastID +"&periodY="+ periodY +"&userID="+ user_id +"&periodM="+ periodM +"&paymentReference="+ paymentReference +"&search="+ searchValue);
}

function loadHostBookingsCancel() {
  hostBookingsLoadMoreReady = true;
  if (xhrLoadHostBookings != null) {
    xhrLoadHostBookings.abort();
  }
}

function renderBookingsRow(bookingStatus, bookingID, plcSts, plcID, plcName, fromD, fromM, fromY, toD, toM, toY, currency, totalPrice, totalFee, percentageFee, wShowMore) {
  var tableRow = document.createElement("tr");
  tableRow.setAttribute("class", "b-d-host-bookings-table-row");
  var tdStatus = document.createElement("td");
  tdStatus.innerHTML = bookingStatus;
  var tdPlace = document.createElement("td");
  tdPlace.innerHTML = plcName;
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
  aShowMore.setAttribute("href", "../booking/?section="+ contentSection +"&nav=about&id="+ bookingID);
  aShowMore.innerHTML = wShowMore;
  tableRow.appendChild(tdStatus);
  tableRow.appendChild(tdPlace);
  tableRow.appendChild(tdDates);
  tableRow.appendChild(tdTotalPrice);
  tableRow.appendChild(tdTotalFee);
  tableRow.appendChild(tdFeePercentage);
  tableRow.appendChild(tdShowMore);
  tdShowMore.appendChild(aShowMore);
  document.getElementById("b-d-host-bookings-table").appendChild(tableRow);
}
