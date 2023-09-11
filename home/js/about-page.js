var aboutPageAddYourCottageReady = true;
var aboutPageAddYourCottageTime, aPAYCFirstname, aPAYCLastname, aPAYCContactEmail, aPAYCAddress, aPAYCCountry, aPAYCNotes;
function aboutPageAddYourCottageSend(e) {
  if (e != "") {
    e.preventDefault();
  }
  if (aboutPageAddYourCottageReady) {
    aboutPageAddYourCottageReady = false;
    aPAYCFirstname = document.getElementById("home-about-page-add-your-cottage-form-input-firstname").value;
    aPAYCLastname = document.getElementById("home-about-page-add-your-cottage-form-input-lastname").value;
    aPAYCContactEmail = document.getElementById("home-about-page-add-your-cottage-form-input-contact-email").value;
    aPAYCAddress = document.getElementById("home-about-page-add-your-cottage-form-input-address").value;
    aPAYCCountry = document.getElementById("home-about-page-add-your-cottage-form-input-country").value;
    aPAYCNotes = document.getElementById("home-about-page-add-your-cottage-form-input-notes").value;
    document.getElementById("home-about-page-add-your-cottage-form-error-txt").innerHTML = "";
    aboutPageAddYourCottageSubmitBtn("load");
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        aboutPageAddYourCottageReady = true;
        if (testJSON(xhr.response)) {
          var json = JSON.parse(xhr.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                aboutPageAddYourCottageSubmitBtn("success");
                clearTimeout(aboutPageAddYourCottageTime);
                aboutPageAddYourCottageTime = setTimeout(function(){
                  aboutPageAddYourCottageSubmitBtn("def");
                  modCover('hide', 'modal-cover-about-page-add-your-cottage');
                  document.getElementById("home-about-page-add-your-cottage-form-input-firstname").value = "";
                  document.getElementById("home-about-page-add-your-cottage-form-input-lastname").value = "";
                  document.getElementById("home-about-page-add-your-cottage-form-input-contact-email").value = "";
                  document.getElementById("home-about-page-add-your-cottage-form-input-address").value = "";
                  document.getElementById("home-about-page-add-your-cottage-form-input-country").value = "";
                  document.getElementById("home-about-page-add-your-cottage-form-input-notes").value = "";
                  modCover('show', 'modal-cover-about-page-add-your-cottage-thanks');
                }, 800);
              } else {
                aboutPageAddYourCottageSubmitBtn("def");
                document.getElementById("home-about-page-add-your-cottage-form-error-txt").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          aboutPageAddYourCottageSubmitBtn("def");
          document.getElementById("home-about-page-add-your-cottage-form-error-txt").innerHTML = xhr.response;
        }
      }
    }
    xhr.open("POST", "php-backend/permission-to-add-cottage-request.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("firstname="+ aPAYCFirstname +"&lastname="+ aPAYCLastname +"&contactemail="+ aPAYCContactEmail +"&address="+ aPAYCAddress +"&country="+ aPAYCCountry +"&notes="+ aPAYCNotes);
  }
}

function aboutPageAddYourCottageSubmitBtn(task) {
  if (task == "def") {
    document.getElementById("home-about-page-add-your-cottage-form-footer-submit").style.color = "#fff";
    document.getElementById("home-about-page-add-your-cottage-form-footer-submit").style.backgroundImage = "unset";
    document.getElementById("home-about-page-add-your-cottage-form-footer-submit").style.backgroundSize = "unset";
  } else if (task == "load") {
    document.getElementById("home-about-page-add-your-cottage-form-footer-submit").style.color = "rgba(0,0,0,0)";
    document.getElementById("home-about-page-add-your-cottage-form-footer-submit").style.backgroundImage = "url('../uni/gifs/loader-tail.svg')";
    document.getElementById("home-about-page-add-your-cottage-form-footer-submit").style.backgroundSize = "auto 63%";
  } else if (task == "success") {
    document.getElementById("home-about-page-add-your-cottage-form-footer-submit").style.color = "rgba(0,0,0,0)";
    document.getElementById("home-about-page-add-your-cottage-form-footer-submit").style.backgroundImage = "url('../uni/icons/ok2.svg')";
    document.getElementById("home-about-page-add-your-cottage-form-footer-submit").style.backgroundSize = "auto 47%";
  }
}
