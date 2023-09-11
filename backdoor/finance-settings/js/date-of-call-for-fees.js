var dateOfCallForFeesSaveReady = true;
var dateOfCallForFeesDay, dateOfCallForFeesTimeH, dateOfCallForFeesTimeM, dateOfCallForFeesSaveTimer;
function dateOfCallForFeesSave() {
  if (dateOfCallForFeesSaveReady) {
    dateOfCallForFeesSaveReady = false;
    clearTimeout(dateOfCallForFeesSaveTimer);
    backDoorBtnManager("load", "b-d-finance-settings-btn-date-of-call-for-fees");
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    dateOfCallForFeesDay = document.getElementById("b-d-settings-input-field-number-date-of-call-for-fees-day").value;
    dateOfCallForFeesTime = document.getElementById("b-d-settings-input-field-number-date-of-call-for-fees-time").value;
    document.getElementById("b-d-settings-error-wrp-date-of-call-for-fees").style.display = "";
    document.getElementById("b-d-settings-error-txt-date-of-call-for-fees").innerHTML = "";
    var xhrdateOfCallForFees = new XMLHttpRequest();
    xhrdateOfCallForFees.onreadystatechange = function() {
      if (xhrdateOfCallForFees.readyState == 4 && xhrdateOfCallForFees.status == 200) {
        window.onbeforeunload = null;
        dateOfCallForFeesSaveReady = true;
        if (testJSON(xhrdateOfCallForFees.response)) {
          var json = JSON.parse(xhrdateOfCallForFees.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                backDoorBtnManager("success", "b-d-finance-settings-btn-date-of-call-for-fees");
                dateOfCallForFeesSaveTimer = setTimeout(function(){
                  backDoorBtnManager("def", "b-d-finance-settings-btn-date-of-call-for-fees");
                }, 1000);
              } else if (json[key]["type"] == "error") {
                backDoorBtnManager("def", "b-d-finance-settings-btn-date-of-call-for-fees");
                document.getElementById("b-d-settings-error-wrp-date-of-call-for-fees").style.display = "table";
                document.getElementById("b-d-settings-error-txt-date-of-call-for-fees").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          backDoorBtnManager("def", "b-d-finance-settings-btn-date-of-call-for-fees");
          document.getElementById("b-d-settings-error-wrp-date-of-call-for-fees").style.display = "table";
          document.getElementById("b-d-settings-error-txt-date-of-call-for-fees").innerHTML = xhrdateOfCallForFees.response;
        }
      }
    }
    xhrdateOfCallForFees.open("POST", "php-backend/date-of-call-for-fees.php");
    xhrdateOfCallForFees.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrdateOfCallForFees.send("day="+ dateOfCallForFeesDay +"&time="+ dateOfCallForFeesTime);
  }
}
