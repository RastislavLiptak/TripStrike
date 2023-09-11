var url_string = window.location.href;
var url = new URL(url_string);
var booking_id = url.searchParams.get("id");

var bookingPaymentStsToggleReady = true;
var bookingPaymentStsToggleTimer;
function bookingPaymentStsToggle() {
  if (bookingPaymentStsToggleReady) {
    bookingPaymentStsToggleReady = false;
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    clearTimeout(bookingPaymentStsToggleTimer);
    backDoorBtnManager("load", "details-page-header-options-btn-paid");
    backDoorBtnManager("load", "details-page-header-options-btn-unpaid");
    var xhrPaymentToggle = new XMLHttpRequest();
    xhrPaymentToggle.onreadystatechange = function() {
      if (xhrPaymentToggle.readyState == 4 && xhrPaymentToggle.status == 200) {
        bookingPaymentStsToggleReady = true;
        window.onbeforeunload = null;
        if (testJSON(xhrPaymentToggle.response)) {
          var json = JSON.parse(xhrPaymentToggle.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                backDoorBtnManager("success", "details-page-header-options-btn-paid");
                backDoorBtnManager("success", "details-page-header-options-btn-unpaid");
                bookingPaymentStsToggleTimer = setTimeout(function(){
                  if (json[key]["task"] == "paid") {
                    document.getElementById("details-page-header-options-btn-paid").classList.add("details-page-header-options-btn-hidden");
                    document.getElementById("details-page-header-options-btn-unpaid").classList.remove("details-page-header-options-btn-hidden");
                    if (document.getElementById("b-d-booking-about-payment-status")) {
                      document.getElementById("b-d-booking-about-payment-status").innerHTML = json[key]["paid"];
                    }
                  } else {
                    document.getElementById("details-page-header-options-btn-paid").classList.remove("details-page-header-options-btn-hidden");
                    document.getElementById("details-page-header-options-btn-unpaid").classList.add("details-page-header-options-btn-hidden");
                    if (document.getElementById("b-d-booking-about-payment-status")) {
                      document.getElementById("b-d-booking-about-payment-status").innerHTML = json[key]["unpaid"];
                    }
                  }
                  if (document.getElementById("b-d-booking-history-table") || document.getElementById("b-d-booking-additional-updates-table")) {
                    location.reload();
                  }
                  backDoorBtnManager("def", "details-page-header-options-btn-paid");
                  backDoorBtnManager("def", "details-page-header-options-btn-unpaid");
                }, 1000);
              } else if (json[key]["type"] == "error") {
                backDoorBtnManager("def", "details-page-header-options-btn-paid");
                backDoorBtnManager("def", "details-page-header-options-btn-unpaid");
                alert(json[key]["error"]);
              }
            }
          }
        } else {
          backDoorBtnManager("def", "details-page-header-options-btn-paid");
          backDoorBtnManager("def", "details-page-header-options-btn-unpaid");
          alert(xhrPaymentToggle.response);
        }
      }
    }
    xhrPaymentToggle.open("POST", "php-backend/one-booking-fees-payment-manager.php");
    xhrPaymentToggle.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrPaymentToggle.send("bookingID="+ booking_id);
  }
}
