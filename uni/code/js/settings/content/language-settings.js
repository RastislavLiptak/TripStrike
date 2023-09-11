var cookieReady = true;
var cookieReload;
function setLang(lang) {
  if (cookieReady) {
    cookieReady = false;
    clearTimeout(cookieReload);
    selectLangBlck(lang);
    cookieReload = setTimeout(function(){
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
          cookieReady = true;
          if (xhr.response == "done") {
            location.reload();
          } else {
            console.log(xhr.response);
            selectLangBlck(selected_lang);
          }
        }
      }
      xhr.open("POST", "../uni/code/php-backend/settings/language-settings.php");
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.send("lang="+ lang);
    }, 150);
  }
}

function selectLangBlck(lang) {
  var clss = "lang-block-select";
  var lBtn = document.getElementsByClassName("lang-block-btn");
  for (var i = 0; i < lBtn.length; ++i) {
    lBtn[i].classList.remove(clss);
  }
  var elmnt = document.getElementById("lang-block-btn-" +lang);
  var arr = elmnt.className.split(" ");
  if (arr.indexOf(clss) == -1) {
    elmnt.className += " " + clss;
  }
}
