var url_string = window.location.href;
var url = new URL(url_string);
var url_paymentreference = url.searchParams.get("paymentreference");

var bookingFeesContentLoadMoreReady = true;
var lastID;
function bookingFeesContentLoadMore() {
  if (bookingFeesContentLoadMoreReady) {
    bookingFeesContentLoadMoreReady = false;
    bookingFeesListBtnManager("load", "booking-fees-list-tools-load-more-btn");
    if (document.getElementsByClassName("booking-fees-list-table-row").length > 0) {
      lastID = document.getElementById("booking-fees-list-table-about-last-id").innerHTML;
    } else {
      lastID = "";
    }
    loadFeesBookingsCancel();
    loadFeesBookings(lastID, false);
  }
}

var xhrFeesLoadBookings;
function loadFeesBookings(lastID, resetTable) {
  if (resetTable) {
    tableRowsNum = document.getElementsByClassName("booking-fees-list-table-row").length;
    for (var rT = 0; rT < tableRowsNum; rT++) {
      document.getElementsByClassName("booking-fees-list-table-row")[0].parentNode.removeChild(document.getElementsByClassName("booking-fees-list-table-row")[0]);
    }
    document.getElementById("booking-fees-list-tools-load-more-wrp").style.display = "";
    document.getElementById("booking-fees-list-tools-loader-wrp").style.display = "flex";
  } else {
    document.getElementById("booking-fees-list-tools-load-more-wrp").style.display = "flex";
    document.getElementById("booking-fees-list-tools-loader-wrp").style.display = "";
  }
  document.getElementById("booking-fees-list-tools-no-content-wrp").style.display = "";
  document.getElementById("booking-fees-list-tools-errors-wrp").style.display = "";
  document.getElementById("booking-fees-list-tools-errors-txt").innerHTML = "";
  outputLastID = "0";
  xhrFeesLoadBookings = new XMLHttpRequest();
  xhrFeesLoadBookings.onreadystatechange = function() {
    if (xhrFeesLoadBookings.readyState == 4 && xhrFeesLoadBookings.status == 200) {
      bookingFeesContentLoadMoreReady = true;
      document.getElementById("booking-fees-list-tools-loader-wrp").style.display = "";
      bookingFeesListBtnManager("def", "booking-fees-list-tools-load-more-btn");
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
                document.getElementById("booking-fees-list-tools-load-more-wrp").style.display = "flex";
              } else {
                document.getElementById("booking-fees-list-tools-load-more-wrp").style.display = "";
              }
              if (json[key]["all-bookings"] > 0) {
                document.getElementById("booking-fees-list-tools-no-content-wrp").style.display = "";
              } else {
                document.getElementById("booking-fees-list-tools-no-content-wrp").style.display = "flex";
              }
            } else if (json[key]["type"] == "error") {
              document.getElementById("booking-fees-list-tools-errors-wrp").style.display = "flex";
              document.getElementById("booking-fees-list-tools-errors-txt").innerHTML = json[key]["error"];
            }
          }
        }
        document.getElementById("booking-fees-list-table-about-last-id").innerHTML = outputLastID;
      } else {
        document.getElementById("booking-fees-list-tools-errors-wrp").style.display = "flex";
        document.getElementById("booking-fees-list-tools-errors-txt").innerHTML = xhrFeesLoadBookings.response;
      }
    }
  }
  xhrFeesLoadBookings.open("POST", "php-backend/get-list-of-payment-reference-bookings-manager.php");
  xhrFeesLoadBookings.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrFeesLoadBookings.send("lastID="+ lastID +"&paymentreference="+ url_paymentreference);
}

function loadFeesBookingsCancel() {
  bookingFeesContentLoadMoreReady = true;
  if (xhrFeesLoadBookings != null) {
    xhrFeesLoadBookings.abort();
  }
}

function renderBookingsRow(bookingStatus, bookingID, plcSts, plcID, plcName, fromD, fromM, fromY, toD, toM, toY, currency, totalFee, percentageFee, wShowMore) {
  var tableRow = document.createElement("tr");
  tableRow.setAttribute("class", "booking-fees-list-table-row");
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
  aShowMore.setAttribute("target", "_blank");
  aShowMore.setAttribute("href", "../bookings/about-guest-booking.php?id="+ bookingID);
  aShowMore.innerHTML = wShowMore;
  tableRow.appendChild(tdStatus);
  tableRow.appendChild(tdPlace);
  tableRow.appendChild(tdDates);
  tableRow.appendChild(tdTotalFee);
  tableRow.appendChild(tdFeePercentage);
  tableRow.appendChild(tdShowMore);
  tdShowMore.appendChild(aShowMore);
  document.getElementById("booking-fees-list-table-payment-reference").appendChild(tableRow);
}

function bookingFeesListBtnManager(task, id) {
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
