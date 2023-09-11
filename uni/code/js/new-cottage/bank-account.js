function ncBankAccountReset() {
  document.getElementById("nc-bank-account-form-input-bank-account-number").value = "";
  document.getElementById("nc-bank-account-form-input-iban").value = "";
  document.getElementById("nc-bank-account-form-input-bicswift").value = "";
  ncBankAccountBtn("def");
}

function ncBankAccountModal(tsk) {
  if (tsk == "show") {
    ncBankAccountReset();
    document.getElementById("nc-bank-account-form-input-bank-account-number").value = document.getElementById("settings-input-bank-account-number").value;
    document.getElementById("nc-bank-account-form-input-iban").value = document.getElementById("settings-input-iban").value;
    document.getElementById("nc-bank-account-form-input-bicswift").value = document.getElementById("settings-input-bicswift").value;
    modCover('show', 'modal-cover-nc-bank-account');
  } else if (tsk == "hide") {
    ncBankAccountReset();
    modCover('hide', 'modal-cover-nc-bank-account');
  }
}

var xhrBankAccountSave;
var xhrBankAccountReady = true;
var ncBankAccountNum, ncIBAN, ncBICSWIFT;
function ncBankAccountSave() {
  if (xhrBankAccountReady) {
    xhrBankAccountReady = false;
    if (xhrBankAccountSave != null) {
      xhrBankAccountSave.abort();
    }
    ncBankAccountBtn("load");
    document.getElementById("nc-bank-account-error-wrp").style.display = "";
    document.getElementById("nc-bank-account-error-txt").innerHTML = "";
    ncBankAccountNum = document.getElementById("nc-bank-account-form-input-bank-account-number").value;
    ncIBAN = document.getElementById("nc-bank-account-form-input-iban").value;
    ncBICSWIFT = document.getElementById("nc-bank-account-form-input-bicswift").value;
    xhrBankAccountSave = new XMLHttpRequest();
    xhrBankAccountSave.onreadystatechange = function() {
      if (xhrBankAccountSave.readyState == 4 && xhrBankAccountSave.status == 200) {
        xhrBankAccountReady = true;
        ncBankAccountBtn("def");
        if (testJSON(xhrBankAccountSave.response)) {
          var json = JSON.parse(xhrBankAccountSave.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                document.getElementById("settings-input-bank-account-number").value = document.getElementById("nc-bank-account-form-input-bank-account-number").value;
                document.getElementById("settings-input-iban").value = document.getElementById("nc-bank-account-form-input-iban").value;
                document.getElementById("settings-input-bicswift").value = document.getElementById("nc-bank-account-form-input-bicswift").value;
                  ncBankAccountModal("hide");
              } else if (json[key]["type"] == "error") {
                document.getElementById("nc-bank-account-error-wrp").style.display = "table";
                document.getElementById("nc-bank-account-error-txt").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          document.getElementById("nc-bank-account-error-wrp").style.display = "table";
          document.getElementById("nc-bank-account-error-txt").innerHTML = xhrBankAccountSave.response;
        }
      }
    }
    xhrBankAccountSave.open("POST", "../uni/code/php-backend/new-cottage/update-bank-account-data.php");
    xhrBankAccountSave.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrBankAccountSave.send("bankAccount="+ ncBankAccountNum +"&iban="+ ncIBAN +"&bicswift="+ ncBICSWIFT);
  }
}

function ncBankAccountBtn(tsk) {
  if (tsk == "def") {
    document.getElementById("nc-bank-account-form-submit-btn").style.color = "#fff";
    document.getElementById("nc-bank-account-form-submit-btn").style.backgroundImage = "unset";
    document.getElementById("nc-bank-account-form-submit-btn").style.backgroundSize = "unset";
  } else if (tsk == "load") {
    document.getElementById("nc-bank-account-form-submit-btn").style.color = "rgba(0,0,0,0)";
    document.getElementById("nc-bank-account-form-submit-btn").style.backgroundImage = "url('../uni/gifs/loader-tail.svg')";
    document.getElementById("nc-bank-account-form-submit-btn").style.backgroundSize = "auto 63%";
  }
}
