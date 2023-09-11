var xhrLoadConditionOfStayData;
function loadConditionOfStayData(sect, plcID) {
  if (xhrLoadConditionOfStayData != null) {
    xhrLoadConditionOfStayData.abort();
  }
  if (sect == "new-cottage") {
    conditionsOfStayLangWrap("loader");
    document.getElementById("n-c-error-txt-conditions").innerHTML = "";
    ncConditionsOfStayReset();
  } else if (sect == "editor") {
    editorConditionsOfStayLangListLoaderHandler("");
  }
  xhrLoadConditionOfStayData = new XMLHttpRequest();
  xhrLoadConditionOfStayData.onreadystatechange = function() {
    if (xhrLoadConditionOfStayData.readyState == 4 && xhrLoadConditionOfStayData.status == 200) {
      if (testJSON(xhrLoadConditionOfStayData.response)) {
        var json = JSON.parse(xhrLoadConditionOfStayData.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "lang") {
              if (sect == "new-cottage") {
                conditionsOfStayLangWrap("content");
              } else if (sect == "editor") {
                editorConditionsOfStayLangListLoaderHandler("none");
                document.getElementById("editor-content-textarea-conditions").disabled = false;
              }
              conditionsOfStayLangSelectRender(json[key]["sts"], sect, json[key]["id"], json[key]["img1"], json[key]["img2"], json[key]["img3"], json[key]["lang-icn"]);
              if (sect == "new-cottage") {
                ncConditionsOfStayLangListControlBtnsManager();
              }
              if (sect == "editor") {
                editorConditionsLangListScrollToSelected();
                editorConditionsLangListControlBtnsManager();
              }
            } else if (json[key]["type"] == "text") {
              if (sect == "new-cottage") {
                conditionsOfStayLangWrap("content");
                document.getElementById("n-c-conditions-of-stay-textarea").disabled = false;
                document.getElementById("n-c-conditions-of-stay-textarea").value = json[key]["text"];
              } else if (sect == "editor") {
                editorConditionsOfStayLangListLoaderHandler("none");
                document.getElementById("editor-content-textarea-conditions").disabled = false;
                document.getElementById("editor-content-textarea-conditions").value = json[key]["text"];
              }
            } else if (json[key]["type"] == "error") {
              if (sect == "new-cottage") {
                document.getElementById("n-c-error-txt-conditions").innerHTML = json[key]["error"];
              } else if (sect == "editor") {
                alert(json[key]["error"]);
              }
            }
          }
        }
      } else {
        if (sect == "new-cottage") {
          document.getElementById("n-c-error-txt-conditions").innerHTML = xhrLoadConditionOfStayData.response;
        } else if (sect == "editor") {
          alert(xhrLoadConditionOfStayData.response);
        }
      }
    }
  }
  xhrLoadConditionOfStayData.open("POST", "../uni/code/php-backend/conditions-of-stay/load-data.php");
  xhrLoadConditionOfStayData.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrLoadConditionOfStayData.send("plcId="+ plcID);
}

var cond_img_num;
function conditionsOfStayLangSelectRender(sts, clss, id, img1, img2, img3, langIcn) {
  var wrp = document.createElement("div");
  wrp.setAttribute("class", "conditions-of-stay-lang-select-wrp");
  wrp.classList.add("conditions-of-stay-lang-select-wrp-"+ clss);
  wrp.setAttribute("id", "conditions-of-stay-lang-select-wrp-"+ id);
  var btn = document.createElement("button");
  btn.setAttribute("type", "button");
  btn.setAttribute("aria-label", "select language of conditions");
  btn.setAttribute("id", "conditions-of-stay-lang-select-btn-"+ clss +"-"+ id);
  btn.setAttribute("class", "conditions-of-stay-lang-select-btn");
  btn.classList.add("conditions-of-stay-lang-select-btn-"+ clss);
  if (sts == "selected") {
    if (clss == "new-cottage") {
      btn.classList.add("conditions-of-stay-lang-select-btn-new-cottage-selected");
    } else if (clss == "editor") {
      btn.classList.add("conditions-of-stay-lang-select-btn-editor-selected");
    }
  }
  btn.setAttribute("value", id);
  if (clss == "new-cottage") {
    btn.setAttribute("onclick", "loadConditionOfStayText('"+ id +"', '"+ clss +"');");
  } else if (clss == "editor") {
    btn.setAttribute("onclick", "loadConditionOfStayText('"+ id +"', '"+ clss +"');");
  }
  var layout = document.createElement("div");
  layout.setAttribute("class", "conditions-of-stay-lang-select-layout");
  layout.classList.add("conditions-of-stay-lang-select-layout-"+ clss);
  cond_img_num = 0;
  if (img3 != "") {
    var img_el_3 = document.createElement("div");
    img_el_3.setAttribute("class", "conditions-of-stay-lang-select-img");
    img_el_3.classList.add("conditions-of-stay-lang-select-img-3");
    img_el_3.classList.add("conditions-of-stay-lang-select-img-"+ clss);
    if (img3 != "#") {
      img_el_3.style.backgroundImage = "url(../"+ img3 +")";
    } else {
      img_el_3.classList.add("conditions-of-stay-lang-select-img-fake");
    }
    ++cond_img_num;
  }
  if (img2 != "") {
    var img_el_2 = document.createElement("div");
    img_el_2.setAttribute("class", "conditions-of-stay-lang-select-img");
    if (cond_img_num == 0) {
      img_el_2.classList.add("conditions-of-stay-lang-select-img-3");
    } else {
      img_el_2.classList.add("conditions-of-stay-lang-select-img-2");
    }
    img_el_2.classList.add("conditions-of-stay-lang-select-img-"+ clss);
    if (img2 != "#") {
      img_el_2.style.backgroundImage = "url(../"+ img2 +")";
    } else {
      img_el_2.classList.add("conditions-of-stay-lang-select-img-fake");
    }
    ++cond_img_num;
  }
  if (img1 != "") {
    var img_el_1 = document.createElement("div");
    img_el_1.setAttribute("class", "conditions-of-stay-lang-select-img");
    img_el_1.classList.add("conditions-of-stay-lang-select-img-1");
    img_el_1.classList.add("conditions-of-stay-lang-select-img-"+ clss);
    if (img1 != "#") {
      img_el_1.style.backgroundImage = "url(../"+ img1 +")";
    } else {
      img_el_1.classList.add("conditions-of-stay-lang-select-img-fake");
    }
  }
  if (langIcn != "") {
    var el_mini_lang_icn = document.createElement("div");
    el_mini_lang_icn.setAttribute("class", "conditions-of-stay-lang-select-lang-icn");
    el_mini_lang_icn.classList.add("conditions-of-stay-lang-select-lang-icn-"+ clss);
    el_mini_lang_icn.style.backgroundImage = "url(../"+ langIcn +")";
  }
  if (clss == "new-cottage") {
    layout.style.width = 38 + (cond_img_num * (38 / 4)) +"px";
  } else if (clss == "editor") {
    layout.style.width = 42 + (cond_img_num * (42 / 4)) +"px";
  }
  wrp.appendChild(btn);
  btn.appendChild(layout);
  if (img3 != "") {
    layout.appendChild(img_el_3);
  }
  if (img2 != "") {
    layout.appendChild(img_el_2);
  }
  if (img1 != "") {
    layout.appendChild(img_el_1);
  }
  if (langIcn != "") {
    layout.appendChild(el_mini_lang_icn);
  }
  if (clss == "new-cottage") {
    document.getElementById("n-c-conditions-of-stay-lang-slider-content").appendChild(wrp);
  } else if (clss == "editor") {
    document.getElementById("editor-content-conditions-lang-slider-content").appendChild(wrp);
  }
}

var xhrloadConditionOfStayText;
function loadConditionOfStayText(condId, sect) {
  if (xhrloadConditionOfStayText != null) {
    xhrloadConditionOfStayText.abort();
  }
  if (sect == "new-cottage") {
    document.getElementById("n-c-conditions-of-stay-textarea").value = "";
    document.getElementById("n-c-conditions-of-stay-textarea").disabled = true;
    document.getElementById("n-c-error-txt-conditions").innerHTML = "";
  } else if (sect == "editor") {
    document.getElementById("editor-content-textarea-conditions").value = "";
    document.getElementById("editor-content-textarea-conditions").disabled = true;
  }
  selectConditionOfStayLang(condId, sect);
  xhrloadConditionOfStayText = new XMLHttpRequest();
  xhrloadConditionOfStayText.onreadystatechange = function() {
    if (xhrloadConditionOfStayText.readyState == 4 && xhrloadConditionOfStayText.status == 200) {
      if (testJSON(xhrloadConditionOfStayText.response)) {
        var json = JSON.parse(xhrloadConditionOfStayText.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "text") {
              if (sect == "new-cottage") {
                document.getElementById("n-c-conditions-of-stay-textarea").disabled = false;
                document.getElementById("n-c-conditions-of-stay-textarea").value = json[key]["text"];
              } else if (sect == "editor") {
                document.getElementById("editor-content-textarea-conditions").disabled = false;
                document.getElementById("editor-content-textarea-conditions").value = json[key]["text"];
              }
            } else if (json[key]["type"] == "error") {
              if (sect == "new-cottage") {
                document.getElementById("n-c-error-txt-conditions").innerHTML = json[key]["error"];
              } else if (sect == "editor") {
                alert(json[key]["error"]);
              }
            }
          }
        }
      } else {
        if (sect == "new-cottage") {
          document.getElementById("n-c-error-txt-conditions").innerHTML = xhrloadConditionOfStayText.response;
        } else if (sect == "editor") {
          alert(xhrloadConditionOfStayText.response);
        }
      }
    }
  }
  xhrloadConditionOfStayText.open("POST", "../uni/code/php-backend/conditions-of-stay/load-conditions-text.php");
  xhrloadConditionOfStayText.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrloadConditionOfStayText.send("condId="+ condId);
}

var condOfStaySelectedLangNum;
function selectConditionOfStayLang(condId, sect) {
  condOfStaySelectedLangNum = document.getElementsByClassName("conditions-of-stay-lang-select-btn-"+ sect +"-selected").length;
  for (var s = 0; s < condOfStaySelectedLangNum; s++) {
    document.getElementsByClassName("conditions-of-stay-lang-select-btn-"+ sect +"-selected")[0].classList.remove("conditions-of-stay-lang-select-btn-"+ sect +"-selected");
  }
  document.getElementById("conditions-of-stay-lang-select-btn-"+ sect +"-"+ condId).classList.add("conditions-of-stay-lang-select-btn-"+ sect +"-selected");
}
