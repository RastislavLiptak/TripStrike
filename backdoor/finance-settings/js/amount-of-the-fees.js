var amountOfTheFeesSaveReady = true;
var amountOfTheFeesPerc, amountOfTheFeesSaveTimer;
function amountOfTheFeesSave() {
  if (amountOfTheFeesSaveReady) {
    amountOfTheFeesSaveReady = false;
    clearTimeout(amountOfTheFeesSaveTimer);
    backDoorBtnManager("load", "b-d-finance-settings-btn-amount-of-the-fees");
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    amountOfTheFeesPerc = document.getElementById("b-d-settings-input-field-number-amount-of-the-fees").value;
    document.getElementById("b-d-settings-error-wrp-amount-of-the-fees").style.display = "";
    document.getElementById("b-d-settings-error-txt-amount-of-the-fees").innerHTML = "";
    var xhrAmountOfTheFees = new XMLHttpRequest();
    xhrAmountOfTheFees.onreadystatechange = function() {
      if (xhrAmountOfTheFees.readyState == 4 && xhrAmountOfTheFees.status == 200) {
        window.onbeforeunload = null;
        amountOfTheFeesSaveReady = true;
        if (testJSON(xhrAmountOfTheFees.response)) {
          var json = JSON.parse(xhrAmountOfTheFees.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                backDoorBtnManager("success", "b-d-finance-settings-btn-amount-of-the-fees");
                amountOfTheFeesSaveTimer = setTimeout(function(){
                  backDoorBtnManager("def", "b-d-finance-settings-btn-amount-of-the-fees");
                }, 1000);
              } else if (json[key]["type"] == "error") {
                backDoorBtnManager("def", "b-d-finance-settings-btn-amount-of-the-fees");
                document.getElementById("b-d-settings-error-wrp-amount-of-the-fees").style.display = "table";
                document.getElementById("b-d-settings-error-txt-amount-of-the-fees").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          backDoorBtnManager("def", "b-d-finance-settings-btn-amount-of-the-fees");
          document.getElementById("b-d-settings-error-wrp-amount-of-the-fees").style.display = "table";
          document.getElementById("b-d-settings-error-txt-amount-of-the-fees").innerHTML = xhrAmountOfTheFees.response;
        }
      }
    }
    xhrAmountOfTheFees.open("POST", "php-backend/amount-of-the-fees.php");
    xhrAmountOfTheFees.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrAmountOfTheFees.send("feesPerc="+ amountOfTheFeesPerc);
  }
}
