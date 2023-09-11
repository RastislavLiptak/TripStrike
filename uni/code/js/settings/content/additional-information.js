window.addEventListener('load', function(event) {
  langBlockSize();
}, false);

window.addEventListener('resize', function(event) {
  langBlockSize();
}, false);

function removeLang(id, el) {
  var lang = el.value;
  var wrp = document.getElementById(id +"-lang-" +lang);
  wrp.parentNode.removeChild(wrp);
}

function langSearchType(id, el, ev) {
  var val = el.value.replace(/["']/g, "");
  el.value = val;
  if (ev.keyCode == 13) {
    ev.preventDefault();
    langSearchSplit(id, val);
  }
  langSearchSize(id);
}

function langSearchSize(id) {
  var val = document.getElementById(id+ "-lang-add-input").value.replace(/["']/g, "");
  var span = document.getElementById(id+ "-lang-add-span");
  span.innerHTML = val;
  var w = span.offsetWidth;
  w = w + 20;
  document.getElementById(id+ "-lang-add-input").style.width = w +"px";
}

function langSearchSplit(id, val) {
  val.replace(/^\s+|\s+$/g, '');
  val.replace(/\s+$/g, '');
  val.replace(/^\s+/g, '');
  addNewLang(id, val);
  document.getElementById(id +"-lang-add-input").value = "";
  document.getElementById(id +"-lang-add-input").focus();
  langSearchSize(id);
}

function addNewLang(id, lang) {
  lang = lang.trim();
  if (typeof lang === 'string') {
    lang = lang.charAt(0).toUpperCase() + lang.slice(1);
  }
  if (lang != "") {
    var wrp = document.createElement("div");
    wrp.setAttribute("class", id +"-lang-wrp");
    wrp.setAttribute("id", id +"-lang-"+ lang);
    var blck = document.createElement("div");
    blck.setAttribute("class", id +"-lang-block");
    var p = document.createElement("p");
    p.setAttribute("class", id +"-lang-block-txt");
    p.innerHTML = lang;
    var btn = document.createElement("button");
    btn.setAttribute("class", id +"-remove");
    btn.setAttribute("value", lang);
    btn.setAttribute("onclick", "removeLang('"+ id +"', this)");
    wrp.appendChild(blck);
    wrp.appendChild(btn);
    blck.appendChild(p);
    document.getElementById(id +"-lang-list").appendChild(wrp);
  }
  if (id == "settings") {
    langBlockSize();
  }
}

var langBlckNewMaxWidth;
function langBlockSize() {
  for (var sLBTDisplay1 = 0; sLBTDisplay1 < document.getElementsByClassName("settings-lang-block-txt").length; sLBTDisplay1++) {
    document.getElementsByClassName("settings-lang-block-txt")[sLBTDisplay1].style.display = "none";
  }
  if (document.getElementById("settings-languages-area")) {
    langBlckNewMaxWidth = document.getElementById("settings-languages-area").offsetWidth - 2 * 12 - 2 * 7 - 5;
    for (var sLBTDisplay2 = 0; sLBTDisplay2 < document.getElementsByClassName("settings-lang-block-txt").length; sLBTDisplay2++) {
      document.getElementsByClassName("settings-lang-block-txt")[sLBTDisplay2].style.maxWidth = langBlckNewMaxWidth +"px";
    }
  }
  for (var sLBTDisplay2 = 0; sLBTDisplay2 < document.getElementsByClassName("settings-lang-block-txt").length; sLBTDisplay2++) {
    document.getElementsByClassName("settings-lang-block-txt")[sLBTDisplay2].style.display = "";
  }
}

var saveAdditionalInformationSettingsReady = true;
var aILangArr = [];
var aIDesc, aILangsBlck, aINewLang, saveAdditionalInformationTimer;
function saveAdditionalInformationSettings() {
  if (saveAdditionalInformationSettingsReady) {
    clearTimeout(saveAdditionalInformationTimer);
    saveAdditionalInformationSettingsReady = false;
    aIDesc = document.getElementById("settings-textarea-desc").value;
    aILangArr = [];
    aILangsBlck = document.getElementsByClassName("settings-lang-block-txt");
    for (i = 0; i < aILangsBlck.length; i++) {
      aINewLang = aILangsBlck[i].textContent;
      aILangArr.push(aINewLang);
    }
    document.getElementById("settings-error-txt-additional-information").innerHTML = "";
    setSaveBtn("load", "settings-save-btn-additional-information");
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        saveAdditionalInformationSettingsReady = true;
        if (testJSON(xhr.response)) {
          var json = JSON.parse(xhr.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                setSaveBtn("success", "settings-save-btn-additional-information");
                if (document.getElementById("my-profile")) {
                  updateProfileAbout(json[key]["description"], aILangArr);
                }
                saveAdditionalInformationTimer = setTimeout(function(){
                  setSaveBtn("def", "settings-save-btn-additional-information");
                }, 1000);
              } else {
                setSaveBtn("def", "settings-save-btn-additional-information");
                document.getElementById("settings-content-scroll").scrollTop = 0;
                document.getElementById("settings-error-txt-additional-information").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          setSaveBtn("def", "settings-save-btn-additional-information");
          document.getElementById("settings-content-scroll").scrollTop = 0;
          document.getElementById("settings-error-txt-additional-information").innerHTML = xhr.response;
        }
      }
    }
    xhr.open("POST", "../uni/code/php-backend/settings/additional-information.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("description="+ aIDesc +"&langArr="+ aILangArr);
  }
}
