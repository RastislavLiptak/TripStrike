var detailsForThePaymentOfFeesSaveReady = true;
var detailsForThePaymentOfFeesIban, detailsForThePaymentOfFeesBankAccount, detailsForThePaymentOfFeesBicSwift, detailsForThePaymentOfFeesSaveTimer;
function detailsForThePaymentOfFeesSave() {
  if (detailsForThePaymentOfFeesSaveReady) {
    detailsForThePaymentOfFeesSaveReady = false;
    clearTimeout(detailsForThePaymentOfFeesSaveTimer);
    backDoorBtnManager("load", "b-d-finance-settings-btn-bank-details-for-the-payment-of-fees");
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    detailsForThePaymentOfFeesIban = document.getElementById("b-d-settings-input-field-number-bank-details-for-the-payment-of-fees-iban").value;
    detailsForThePaymentOfFeesBankAccount = document.getElementById("b-d-settings-input-field-number-bank-details-for-the-payment-of-fees-bank-account").value;
    detailsForThePaymentOfFeesBicSwift = document.getElementById("b-d-settings-input-field-number-bank-details-for-the-payment-of-fees-bic-swift").value;
    document.getElementById("b-d-settings-error-wrp-bank-details-for-the-payment-of-fees").style.display = "";
    document.getElementById("b-d-settings-error-txt-bank-details-for-the-payment-of-fees").innerHTML = "";
    var xhrdetailsForThePaymentOfFees = new XMLHttpRequest();
    xhrdetailsForThePaymentOfFees.onreadystatechange = function() {
      if (xhrdetailsForThePaymentOfFees.readyState == 4 && xhrdetailsForThePaymentOfFees.status == 200) {
        window.onbeforeunload = null;
        detailsForThePaymentOfFeesSaveReady = true;
        if (testJSON(xhrdetailsForThePaymentOfFees.response)) {
          var json = JSON.parse(xhrdetailsForThePaymentOfFees.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                backDoorBtnManager("success", "b-d-finance-settings-btn-bank-details-for-the-payment-of-fees");
                detailsForThePaymentOfFeesSaveTimer = setTimeout(function(){
                  backDoorBtnManager("def", "b-d-finance-settings-btn-bank-details-for-the-payment-of-fees");
                }, 1000);
              } else if (json[key]["type"] == "error") {
                backDoorBtnManager("def", "b-d-finance-settings-btn-bank-details-for-the-payment-of-fees");
                document.getElementById("b-d-settings-error-wrp-bank-details-for-the-payment-of-fees").style.display = "table";
                document.getElementById("b-d-settings-error-txt-bank-details-for-the-payment-of-fees").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          backDoorBtnManager("def", "b-d-finance-settings-btn-bank-details-for-the-payment-of-fees");
          document.getElementById("b-d-settings-error-wrp-bank-details-for-the-payment-of-fees").style.display = "table";
          document.getElementById("b-d-settings-error-txt-bank-details-for-the-payment-of-fees").innerHTML = xhrdetailsForThePaymentOfFees.response;
        }
      }
    }
    xhrdetailsForThePaymentOfFees.open("POST", "php-backend/bank-details-for-the-payment-of-fees.php");
    xhrdetailsForThePaymentOfFees.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrdetailsForThePaymentOfFees.send("iban="+ detailsForThePaymentOfFeesIban +"&bankAccount="+ detailsForThePaymentOfFeesBankAccount +"&bicSwift="+ detailsForThePaymentOfFeesBicSwift);
  }
}
