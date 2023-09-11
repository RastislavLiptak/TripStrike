window.addEventListener('load', function(e) {
  userDataBlckWidth();
  userContCenterBlckWidth();
});

window.addEventListener('resize', function(e) {
  userDataBlckWidth();
  userContCenterBlckWidth();
});

function userDataBlckWidth() {
  document.getElementById("user-data-width").style.width = document.getElementById("user-data-fixed").offsetWidth +"px";
}

function userContCenterBlckWidth() {
  if (window.innerWidth > 2300) {
    document.getElementById("user-content-center-width").style.width = document.getElementById("user-data-fixed").offsetWidth +"px";
  } else {
    document.getElementById("user-content-center-width").style.width = "0px";
  }
}

var noCottageMsg = "does not offer any cottages";
var speaksMsg = "Speaks";
var speaksMultiMsg = speaksMsg;
function userDictionary(night, personPerNight, doesNotOfferAnyCottages, speaks, speaksMulti, showMore) {
  noCottageMsg = doesNotOfferAnyCottages;
  speaksMsg = speaks;
  speaksMultiMsg = speaksMulti;
  setNightWord(night, personPerNight);
  if (document.getElementById("u-a-blck-ratings")) {
    userCommentsDictionary(showMore);
  }
}

function updateProfileData(firstname, lastname, email, phonenum) {
  if (document.getElementById("my-profile")) {
    for (var uIT = 0; uIT < document.getElementsByClassName("user-info-title").length; uIT++) {
      document.getElementsByClassName("user-info-title")[uIT].innerHTML = firstname+" "+lastname;
    }
    for (var uIE = 0; uIE < document.getElementsByClassName("user-info-email").length; uIE++) {
      document.getElementsByClassName("user-info-email")[uIE].innerHTML = email;
      document.getElementsByClassName("user-info-email")[uIE].href = "mailto:"+ email;
    }
    for (var uIP = 0; uIP < document.getElementsByClassName("user-info-phone").length; uIP++) {
      document.getElementsByClassName("user-info-phone")[uIP].innerHTML = phonenum;
      document.getElementsByClassName("user-info-phone")[uIP].href = "tel:"+ phonenum.replace(/\D/g,'') +"<";
    }
    if (document.getElementById("u-c-l-alt-cont-txt-no-cottage")) {
      document.getElementById("u-c-l-alt-cont-txt-no-cottage").innerHTML = firstname+" "+lastname+" "+noCottageMsg;
    }
    if (document.getElementById("u-c-no-cottage-txt")) {
      document.getElementById("u-c-no-cottage-txt").innerHTML = firstname+" "+lastname+" "+noCottageMsg;
    }
    if (document.getElementById("u-a-links-errors-txt-no-cottage")) {
      document.getElementById("u-a-links-errors-txt-no-cottage").innerHTML = firstname+" "+lastname+" "+noCottageMsg;
    }
  }
}

var langString = "";
var uniLangClassName, singleLangClassName, multiLangClassName;
function updateProfileAbout(desc, langArr) {
  if (document.getElementById("user-about")) {
    langString = "";
    uniLangClassName = "u-a-details-block-desc-txt u-a-details-block-desc-txt-languages";
    if (desc.replace(/(?:\r\n|\r|\n)/g, "").replace(" ", "") == "") {
      desc = "...";
    }
    desc = anchorme({
      input: desc,
      options: {
        attributes: (arg) => {
          return {
            target: "_blank",
            rel: "noopener noreferrer",
          };
        },
      },
    });
    document.getElementById("u-a-details-block-desc-txt-description").innerHTML= desc.replace(/(?:\r\n|\r|\n)/g, "<br>");
    for (var l = 0; l < langArr.length; l++) {
      langString = langString + langArr[l] + ", ";
    }
    langString = langString.replace(/,\s*$/, "");
    if (langArr.length < 1) {
      singleLangClassName = uniLangClassName;
      multiLangClassName = uniLangClassName;
    } else if (langArr.length > 1) {
      singleLangClassName = uniLangClassName;
      multiLangClassName = uniLangClassName +" u-a-details-block-desc-txt-languages-priority";
    } else {
      singleLangClassName = uniLangClassName +" u-a-details-block-desc-txt-languages-priority";
      multiLangClassName = uniLangClassName;
    }
    document.getElementById("u-a-details-block-desc-txt-languages").className = singleLangClassName;
    document.getElementById("u-a-details-block-desc-txt-multi-languages").className = multiLangClassName;
    document.getElementById("u-a-details-block-desc-txt-languages").innerHTML = speaksMsg +" "+ langString;
    document.getElementById("u-a-details-block-desc-txt-multi-languages").innerHTML = speaksMultiMsg +" "+ langString;
  }
}

function addNewCottageToUserList(name, id, desc, mainImg) {
  var altContBtn = document.getElementsByClassName("u-c-l-alt-cont-btn");
  for (var aB = 0; aB < altContBtn.length; aB++) {
    altContBtn[aB].parentNode.removeChild(altContBtn[aB]);
  }
  var altContTxt = document.getElementsByClassName("u-c-l-alt-cont-txt");
  for (var aT = 0; aT < altContTxt.length; aT++) {
    altContTxt[aT].parentNode.removeChild(altContTxt[aT]);
  }
  var uclwArr = document.getElementsByClassName("u-c-l-wrp");
  if (uclwArr.length == 3) {
    uclwArr[2].parentNode.removeChild(uclwArr[2]);
  }
  var wrp = document.createElement("div");
  wrp.setAttribute("class", "u-c-l-wrp");
  var blck = document.createElement("div");
  blck.setAttribute("class", "u-c-l-blck");
  var a = document.createElement("a");
  a.setAttribute("class", "u-c-l-link");
  a.setAttribute("href", "../place/?id=" +id);
  var flex = document.createElement("div");
  flex.setAttribute("class", "u-c-l-flex");
  var imgWrp = document.createElement("div");
  imgWrp.setAttribute("class", "u-c-l-img-wrp");
  var imgCenter = document.createElement("div");
  imgCenter.setAttribute("class", "u-c-l-img-center");
  if (mainImg != "none") {
    var img = document.createElement("img");
    img.setAttribute("class", "u-c-l-img");
    img.setAttribute("alt", name+ " small link thumbnail");
    img.src = "../" +mainImg;
  } else {
    var img = document.createElement("div");
    img.setAttribute("class", "fake-u-c-l-img");
  }
  var txtWrp = document.createElement("div");
  txtWrp.setAttribute("class", "u-c-l-txt");
  var txtPadding = document.createElement("div");
  txtPadding.setAttribute("class", "u-c-l-txt-padding");
  var title = document.createElement("p");
  title.setAttribute("class", "u-c-l-title");
  title.innerHTML = name;
  var description = document.createElement("p");
  description.setAttribute("class", "u-c-l-desc");
  description.innerHTML = desc;
  wrp.appendChild(blck);
  blck.appendChild(a);
  a.appendChild(flex);
  flex.appendChild(imgWrp);
  imgWrp.appendChild(imgCenter);
  imgCenter.appendChild(img);
  flex.appendChild(txtWrp);
  txtWrp.appendChild(txtPadding);
  txtPadding.appendChild(title);
  txtPadding.appendChild(description);
  document.getElementById("user-cott-list").prepend(wrp);
  document.getElementById("u-c-l-new-btn-wrp").className = "u-c-l-new-btn-wrp-show";
}




var uMenuTime, uMenuTsk;
var uMenuTogg = false;
function userMemu(tsk) {
  if (tsk == "toggle") {
    if (uMenuTogg) {
      uMenuTsk = false;
    } else {
      uMenuTsk = true;
    }
  } else if (tsk == "hide") {
    uMenuTsk = false;
  } else if (tsk == "show") {
    uMenuTsk = true;
  }
  clearTimeout(uMenuTime);
  if (!uMenuTsk) {
    for (var uMDW1 = 0; uMDW1 < document.getElementsByClassName("user-menu-dropdown-wrp").length; uMDW1++) {
      document.getElementsByClassName("user-menu-dropdown-wrp")[uMDW1].style.opacity = "";
      document.getElementsByClassName("user-menu-dropdown-wrp")[uMDW1].style.left = "";
    }
    uMenuTime = setTimeout(function(){
      for (var uMDW2 = 0; uMDW2 < document.getElementsByClassName("user-menu-dropdown-wrp").length; uMDW2++) {
        document.getElementsByClassName("user-menu-dropdown-wrp")[uMDW2].style.display = "";
      }
      uMenuTogg = false;
    }, 210);
  } else {
    uMenuTime = setTimeout(function(){
      for (var uMDW2 = 0; uMDW2 < document.getElementsByClassName("user-menu-dropdown-wrp").length; uMDW2++) {
        document.getElementsByClassName("user-menu-dropdown-wrp")[uMDW2].style.opacity = "1";
        document.getElementsByClassName("user-menu-dropdown-wrp")[uMDW2].style.left = "3px";
      }
      uMenuTogg = true;
    }, 10);
    for (var uMDW1 = 0; uMDW1 < document.getElementsByClassName("user-menu-dropdown-wrp").length; uMDW1++) {
      document.getElementsByClassName("user-menu-dropdown-wrp")[uMDW1].style.display = "flex";
    }
  }
}

function reportUser(blck) {
  if (document.getElementById("user-report-reason-btn-" +blck).value == "hide") {
    document.getElementById("user-report-reason-blck-wrp-" +blck).style.padding = "10px 0px";
    document.getElementById("user-report-reason-btn-" +blck).style.backgroundColor = "#3a3939";
    document.getElementById("user-report-reason-checkbox-wrp-"  +blck).style.opacity = "1";
    document.getElementById("user-report-reason-checkbox-wrp-"  +blck).style.maxHeight = "25vh";
    document.getElementById("user-report-reason-checkbox-wrp-"  +blck).style.padding = "10px 0px 20px 0px";
    document.getElementById("user-report-reason-btn-" +blck).value = "show";
  } else {
    document.getElementById("user-report-reason-blck-wrp-" +blck).style.padding = "";
    document.getElementById("user-report-reason-btn-" +blck).style.backgroundColor = "";
    document.getElementById("user-report-reason-checkbox-wrp-"  +blck).style.opacity = "";
    document.getElementById("user-report-reason-checkbox-wrp-"  +blck).style.maxHeight = "";
    document.getElementById("user-report-reason-checkbox-wrp-"  +blck).style.padding = "";
    document.getElementById("user-report-reason-btn-" +blck).value = "hide";
  }
}

var chck, blckN, blcks;
function reportReset(tsk) {
  sendUserReportErrorReset();
  if (tsk == "all") {
    reportReset("check");
    reportReset("slide");
    reportReset("area");
  } else if (tsk == "check") {
    chck = document.getElementsByName("report-user");
    for (var c = 0 ; c < chck.length; c++) {
      chck[c].checked = false;
    }
  } else if (tsk == "slide") {
    blckN;
    blcks = document.getElementsByClassName("user-report-reason-btn");
    for (var b = 0;b < blcks.length; b++) {
      if (blcks[b].value != "hide") {
        blckN = 1 + b;
        reportUser(blckN);
      }
    }
  } else if (tsk == "area") {
    document.getElementById("user-report-textarea").value = "";
  }
  reportUserBtnShowHide("hide");
}

function reportUserCheckClick() {
  reportReset("area");
  sendUserReportErrorReset();
  reportUserBtnShowHide("show");
}

function reportUserAreaKey() {
  reportReset("check");
  sendUserReportErrorReset();
  if (document.getElementById("user-report-textarea").value != "") {
    reportUserBtnShowHide("show");
  } else {
    reportUserBtnShowHide("hide");
  }
}

var rUBtnTime;
function reportUserBtnShowHide(tsk) {
  clearTimeout(rUBtnTime);
  if (tsk == "show") {
    document.getElementById("modal-cover-user-report-blck").style.paddingBottom = "52px";
    rUBtnTime = setTimeout(function(){
      document.getElementById("user-report-save-wrp").style.opacity = "1";
    }, 10);
    document.getElementById("user-report-save-wrp").style.display = "table";
  } else {
    document.getElementById("modal-cover-user-report-blck").style.paddingBottom = "";
    document.getElementById("user-report-save-wrp").style.opacity = "";
    rUBtnTime = setTimeout(function(){
      document.getElementById("user-report-save-wrp").style.display = "";
    }, 160);
  }
}

var unknReport, chckReport, userId;
var reportReady = true;
var windowLocationHref = window.location.href;
var url = new URL(windowLocationHref);
function sendUserReport() {
  if (reportReady) {
    sendUserReportErrorReset();
    reportReady = false;
    userId = url.searchParams.get("id");
    setSaveBtn("load", "user-report-btn");
    chckReport = "0.0";
    unknReport = document.getElementById("user-report-textarea").value;
    chckInpt = document.getElementsByName("report-user");
    for (var c = 0 ; c < chckInpt.length; c++) {
      if (chckInpt[c].checked == true){
        chckReport = chckInpt[c].value;
      }
    }
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        setSaveBtn("def", "user-report-btn");
        reportReady = true;
        if (testJSON(xhr.response)) {
          var json = JSON.parse(xhr.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                reportUserDone();
              } else {
                sendUserReportError(json[key]["error"]);
              }
            }
          }
        } else {
          sendUserReportError(xhr.response);
        }
      }
    }
    xhr.open("POST", "php-backend/report-user.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("userId="+ userId +"&newReport="+ unknReport +"&checkReport="+ chckReport);
  }
}

function sendUserReportError(code) {
  document.getElementById("modal-cover-user-report-blck").style.paddingBottom = "60px";
  document.getElementById("user-report-err").innerHTML = code;
  document.getElementById("user-report-err").style.display = "table";
}

function sendUserReportErrorReset() {
  document.getElementById("modal-cover-user-report-blck").style.paddingBottom = "52px";
  document.getElementById("user-report-err").innerHTML = "";
  document.getElementById("user-report-err").style.display = "";
}

function reportUserDone() {
  document.getElementById("modal-cover-user-report-blck").classList.add("no-modal-animat");
  reportReset("all");
  document.getElementById("user-report-slider").style.display = "none";
  document.getElementById("user-report-save-wrp").style.display = "none";
  document.getElementById("user-report-error-wrp").style.display = "none";
  document.getElementById("user-report-thanks-wrp").style.display = "flex";
  document.getElementById("modal-cover-user-report-blck").style.padding = "45px";
}
