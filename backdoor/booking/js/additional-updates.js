var url_string = window.location.href;
var url = new URL(url_string);
var booking_id = url.searchParams.get("id");

var additionalUpdatesLoadMoreReady = true;
var xhrBookingAdditionalUpdates, tableRowsNum, outputLastID, lastID;
function additionalUpdatesLoadMore() {
  if (additionalUpdatesLoadMoreReady) {
    additionalUpdatesLoadMoreReady = false;
    lastID = document.getElementById("b-d-booking-additional-updates-table-about-last-id").innerHTML;
    document.getElementById("b-d-booking-additional-updates-table-tools-no-content-wrp").style.display = "";
    document.getElementById("b-d-booking-additional-updates-table-tools-errors-wrp").style.display = "";
    document.getElementById("b-d-booking-additional-updates-table-tools-errors-txt").innerHTML = "";
    outputLastID = "";
    xhrBookingAdditionalUpdates = new XMLHttpRequest();
    xhrBookingAdditionalUpdates.onreadystatechange = function() {
      if (xhrBookingAdditionalUpdates.readyState == 4 && xhrBookingAdditionalUpdates.status == 200) {
        additionalUpdatesLoadMoreReady = true;
        document.getElementById("b-d-booking-additional-updates-table-tools-loader-wrp").style.display = "";
        backDoorBtnManager("def", "b-d-booking-additional-updates-table-tools-load-more-btn");
        if (testJSON(xhrBookingAdditionalUpdates.response)) {
          var json = JSON.parse(xhrBookingAdditionalUpdates.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "update") {
                additionalUpdatesRow(
                  json[key]["status"],
                  json[key]["paymentStatus"],
                  json[key]["plcID"],
                  json[key]["plcName"],
                  json[key]["hostID"],
                  json[key]["hostName"],
                  json[key]["guestID"],
                  json[key]["guestName"],
                  json[key]["source"],
                  json[key]["numOfGuests"],
                  json[key]["fromDate"],
                  json[key]["toDate"],
                  json[key]["currency"],
                  json[key]["priceMode"],
                  json[key]["workPrice"],
                  json[key]["weekPrice"],
                  json[key]["totalPrice"],
                  json[key]["fee"],
                  json[key]["percentageFee"],
                  json[key]["validFrom"]
                );
                outputLastID = json[key]["updateID"];
              } else if (json[key]["type"] == "load-amount") {
                if (json[key]["remain"] > 0) {
                  document.getElementById("b-d-booking-additional-updates-table-tools-load-more-wrp").style.display = "flex";
                } else {
                  document.getElementById("b-d-booking-additional-updates-table-tools-load-more-wrp").style.display = "";
                }
                if (json[key]["all-bookings"] > 0) {
                  document.getElementById("b-d-booking-additional-updates-table-tools-no-content-wrp").style.display = "";
                } else {
                  document.getElementById("b-d-booking-additional-updates-table-tools-no-content-wrp").style.display = "flex";
                }
              } else if (json[key]["type"] == "error") {
                document.getElementById("b-d-booking-additional-updates-table-tools-errors-wrp").style.display = "flex";
                document.getElementById("b-d-booking-additional-updates-table-tools-errors-txt").innerHTML = json[key]["error"];
              }
            }
          }
          document.getElementById("b-d-booking-additional-updates-table-about-last-id").innerHTML = outputLastID;
        } else {
          document.getElementById("b-d-booking-additional-updates-table-tools-errors-wrp").style.display = "flex";
          document.getElementById("b-d-booking-additional-updates-table-tools-errors-txt").innerHTML = xhrBookingAdditionalUpdates.response;
        }
      }
    }
    xhrBookingAdditionalUpdates.open("POST", "php-backend/get-additional-updates-manager.php");
    xhrBookingAdditionalUpdates.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrBookingAdditionalUpdates.send("lastID="+ lastID +"&bookingID="+ booking_id);
  }
}

function additionalUpdatesRow(updateStatus, updatePaymentStatus, plcID, plcName, hostID, hostName, guestID, guestName, bookingSource, numOfGuests, fromDate, toDate, currency, plcPriceMode, plcWorkPrice, plcWeekPrice, totalPrice, totalFee, percentageFee, validFrom) {
  var tableRow = document.createElement("tr");
  tableRow.setAttribute("class", "b-d-booking-additional-updates-table-row");
  var tdStatus = document.createElement("td");
  tdStatus.innerHTML = updateStatus;
  var tdPaymentStatus = document.createElement("td");
  tdPaymentStatus.innerHTML = updatePaymentStatus;
  var tdHost = document.createElement("td");
  tdHost.innerHTML = hostName;
  var tdGuest = document.createElement("td");
  tdGuest.innerHTML = guestName;
  var tdPlc = document.createElement("td");
  tdPlc.innerHTML = plcName;
  var tdSource = document.createElement("td");
  tdSource.innerHTML = bookingSource;
  var tdNumOfGuests = document.createElement("td");
  tdNumOfGuests.innerHTML = numOfGuests;
  var tdFrom = document.createElement("td");
  tdFrom.innerHTML = fromDate;
  var tdTo = document.createElement("td");
  tdTo.innerHTML = toDate;
  var tdPriceMode = document.createElement("td");
  tdPriceMode.innerHTML = plcPriceMode;
  var tdPlcWorkPrice = document.createElement("td");
  tdPlcWorkPrice.innerHTML = addCurrency(currency, plcWorkPrice);
  var tdPlcWeekPrice = document.createElement("td");
  tdPlcWeekPrice.innerHTML = addCurrency(currency, plcWeekPrice);
  var tdTotalPrice = document.createElement("td");
  tdTotalPrice.innerHTML = addCurrency(currency, totalPrice);
  var tdTotalFee = document.createElement("td");
  tdTotalFee.innerHTML = addCurrency(currency, totalFee);
  var tdFeePercentage = document.createElement("td");
  tdFeePercentage.innerHTML = percentageFee;
  var tdvalidFrom = document.createElement("td");
  tdvalidFrom.innerHTML = validFrom;
  tableRow.appendChild(tdStatus);
  tableRow.appendChild(tdPaymentStatus);
  tableRow.appendChild(tdHost);
  tableRow.appendChild(tdGuest);
  tableRow.appendChild(tdPlc);
  tableRow.appendChild(tdSource);
  tableRow.appendChild(tdNumOfGuests);
  tableRow.appendChild(tdFrom);
  tableRow.appendChild(tdTo);
  tableRow.appendChild(tdPriceMode);
  tableRow.appendChild(tdPlcWorkPrice);
  tableRow.appendChild(tdPlcWeekPrice);
  tableRow.appendChild(tdTotalPrice);
  tableRow.appendChild(tdTotalFee);
  tableRow.appendChild(tdFeePercentage);
  tableRow.appendChild(tdvalidFrom);
  document.getElementById("b-d-booking-additional-updates-table").appendChild(tableRow);
}
