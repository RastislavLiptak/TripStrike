var supportContactFormSubmitReady = true;
var xhrSupportContactForm, supportContactFormSubmitTimer, contEmail, emSubject, emContent;
function supportContactFormSubmit() {
  if (supportContactFormSubmitReady) {
    clearTimeout(supportContactFormSubmitTimer);
    supportContactFormSubmitReady = false;
    document.getElementById("support-contact-form-error").innerHTML = "";
    contEmail = document.getElementById("support-contact-form-input-email").value;
    emSubject = document.getElementById("support-contact-form-input-subject").value;
    emContent = document.getElementById("support-contact-form-textarea-content").value;
    supportBtnHandler("load", "support-contact-form-submit");
    xhrSupportContactForm = new XMLHttpRequest();
    xhrSupportContactForm.onreadystatechange = function() {
      if (xhrSupportContactForm.readyState == 4 && xhrSupportContactForm.status == 200) {
        supportContactFormSubmitReady = true;
        supportBtnHandler("def", "support-contact-form-submit");
        if (testJSON(xhrSupportContactForm.response)) {
          var json = JSON.parse(xhrSupportContactForm.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                document.getElementById("support-contact-form-input-subject").value = "";
                document.getElementById("support-contact-form-textarea-content").value = "";
                supportBtnHandler("done", "support-contact-form-submit");
                supportContactFormSubmitTimer = setTimeout(function(){
                  supportBtnHandler("def", "support-contact-form-submit");
                }, 1500);
              } else {
                document.getElementById("support-contact-form-error").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          document.getElementById("support-contact-form-error").innerHTML = xhrSupportContactForm.response;
        }
      }
    }
    xhrSupportContactForm.open("POST", "php-backend/contact-support.php");
    xhrSupportContactForm.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrSupportContactForm.send("email="+ contEmail +"&subject="+ emSubject +"&content="+ emContent);
  }
}

function supportBtnHandler(task, id) {
  if (task == "def") {
    document.getElementById(id).style.color = "";
    document.getElementById(id).style.backgroundImage = "";
    document.getElementById(id).style.backgroundSize = "";
  } else if (task == "load") {
    document.getElementById(id).style.color = "rgba(0,0,0,0)";
    document.getElementById(id).style.backgroundImage = "url('../uni/gifs/loader-tail.svg')";
    document.getElementById(id).style.backgroundSize = "auto 63%";
  } else if (task == "done") {
    document.getElementById(id).style.color = "rgba(0,0,0,0)";
    document.getElementById(id).style.backgroundImage = "url('../uni/icons/ok2.svg')";
    document.getElementById(id).style.backgroundSize = "auto 47%";
  }
}
