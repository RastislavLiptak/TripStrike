var url_string = window.location.href;
var url = new URL(url_string);
var url_booking = url.searchParams.get("booking");
var url_fromd = url.searchParams.get("fromd");
var url_fromm = url.searchParams.get("fromm");
var url_fromy = url.searchParams.get("fromy");
var url_tod = url.searchParams.get("tod");
var url_tom = url.searchParams.get("tom");
var url_toy = url.searchParams.get("toy");
var url_plc_id = url.searchParams.get("plc");

var ratingsContentContinueSaveReady = true;
var ratingsContentContinueCommentsObject = {};
var ratingTimerContentContinue, rSectionRatingProgress, xhrSectionRating;
function ratingsContentContinueSave(part, sect) {
  if (ratingsContentContinueSaveReady) {
    ratingsContentContinueSaveReady = false;
    clearTimeout(ratingTimerContentContinue);
    ratingsContentContinueCommentsObject = {};
    ratingsBtnHandler("load", "set-up-step-by-step-content-footer-continue-btn-"+ part +"-"+ sect);
    rSectionRatingProgress = document.getElementById("set-up-step-by-step-content-slider-about-"+ part +"-"+ sect +"-progress").innerHTML;
    document.getElementById("set-up-step-by-step-content-error-txt-"+ part +"-"+ sect).innerHTML = "";
    xhrSectionRating = new XMLHttpRequest();
    xhrSectionRating.onreadystatechange = function() {
      if (xhrSectionRating.readyState == 4 && xhrSectionRating.status == 200) {
        ratingsContentContinueSaveReady = true;
        if (testJSON(xhrSectionRating.response)) {
          var json = JSON.parse(xhrSectionRating.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                ratingsDataUpdate(json[key]["criticsSummaryPlace"], json[key]["plcLct"], json[key]["plcTidy"], json[key]["plcPrc"], json[key]["plcPark"], json[key]["plcAd"], json[key]["criticsSummaryHost"], json[key]["hstLang"], json[key]["hstComm"], json[key]["hstPrsn"]);
                ratingsBtnHandler("done", "set-up-step-by-step-content-footer-continue-btn-"+ part +"-"+ sect);
                ratingTimerContentContinue = setTimeout(function(){
                  ratingsBtnHandler("def", "set-up-step-by-step-content-footer-continue-btn-"+ part +"-"+ sect);
                  ratingsContentContinue();
                }, 750);
              } else if (json[key]["type"] == "comment") {
                ratingsContentContinueCommentsObject[++Object.keys(ratingsContentContinueCommentsObject).length] = {
                  id: json[key]["id"],
                  sts: json[key]["sts"],
                  img: json[key]["img"],
                  comment: json[key]["comment"]
                };
              } else {
                ratingsBtnHandler("def", "set-up-step-by-step-content-footer-continue-btn-"+ part +"-"+ sect);
                document.getElementById("set-up-step-by-step-content-error-txt-"+ part +"-"+ sect).innerHTML = json[key]["error"];
              }
            }
          }
          document.getElementById("ratings-overview-section-details-comment-list-places").innerHTML = "";
          document.getElementById("ratings-overview-section-details-comment-list-host").innerHTML = "";
          for (var commKey in ratingsContentContinueCommentsObject) {
             ratingsRenderComments(
               ratingsContentContinueCommentsObject[commKey]["id"],
               ratingsContentContinueCommentsObject[commKey]["sts"],
               ratingsContentContinueCommentsObject[commKey]["img"],
               ratingsContentContinueCommentsObject[commKey]["comment"]
             );
          }
        } else {
          ratingsBtnHandler("def", "set-up-step-by-step-content-footer-continue-btn-"+ part +"-"+ sect);
          document.getElementById("set-up-step-by-step-content-error-txt-"+ part +"-"+ sect).innerHTML = xhrSectionRating.response;
        }
      }
    }
    xhrSectionRating.open("POST", "php-backend/uni/section-rating.php");
    xhrSectionRating.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrSectionRating.send("booking="+ url_booking +"&fromd="+ url_fromd +"&fromm="+ url_fromm +"&fromy="+ url_fromy +"&tod="+ url_tod +"&tom="+ url_tom +"&toy="+ url_toy +"&plc="+ url_plc_id +"&part="+ part +"&section="+ sect +"&progress="+ rSectionRatingProgress);
  }
}

var ratingsContentCommentSaveReady = true;
var ratingsContentCommentCommentsObject = {};
var ratingTimerContentComment, rSectionCommentTxt, xhrSectionComment;
function ratingsContentCommentSave(part) {
  if (ratingsContentCommentSaveReady) {
    ratingsContentCommentSaveReady = false;
    clearTimeout(ratingTimerContentComment);
    ratingsContentCommentCommentsObject = {};
    ratingsBtnHandler("load", "set-up-step-by-step-content-footer-continue-btn-"+ part +"-comment");
    rSectionCommentTxt = document.getElementById("set-up-step-by-step-content-comment-textarea-"+ part).value;
    document.getElementById("set-up-step-by-step-content-error-txt-"+ part +"-comment").innerHTML = "";
    xhrSectionComment = new XMLHttpRequest();
    xhrSectionComment.onreadystatechange = function() {
      if (xhrSectionComment.readyState == 4 && xhrSectionComment.status == 200) {
        ratingsContentCommentSaveReady = true;
        if (testJSON(xhrSectionComment.response)) {
          var json = JSON.parse(xhrSectionComment.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                ratingsDataUpdate(json[key]["criticsSummaryPlace"], json[key]["plcLct"], json[key]["plcTidy"], json[key]["plcPrc"], json[key]["plcPark"], json[key]["plcAd"], json[key]["criticsSummaryHost"], json[key]["hstLang"], json[key]["hstComm"], json[key]["hstPrsn"]);
                ratingsBtnHandler("done", "set-up-step-by-step-content-footer-continue-btn-"+ part +"-comment");
                ratingTimerContentComment = setTimeout(function(){
                  ratingsBtnHandler("def", "set-up-step-by-step-content-footer-continue-btn-"+ part +"-comment");
                  document.getElementById("set-up-step-by-step-content-comment-textarea-"+ part).value = "";
                  ratingsContentContinue();
                }, 750);
              } else if (json[key]["type"] == "comment") {
                ratingsContentCommentCommentsObject[++Object.keys(ratingsContentCommentCommentsObject).length] = {
                  id: json[key]["id"],
                  sts: json[key]["sts"],
                  img: json[key]["img"],
                  comment: json[key]["comment"]
                };
              } else {
                ratingsBtnHandler("def", "set-up-step-by-step-content-footer-continue-btn-"+ part +"-comment");
                document.getElementById("set-up-step-by-step-content-error-txt-"+ part +"-comment").innerHTML = json[key]["error"];
              }
            }
          }
          document.getElementById("ratings-overview-section-details-comment-list-places").innerHTML = "";
          document.getElementById("ratings-overview-section-details-comment-list-host").innerHTML = "";
          for (var commKey in ratingsContentCommentCommentsObject) {
             ratingsRenderComments(
               ratingsContentCommentCommentsObject[commKey]["id"],
               ratingsContentCommentCommentsObject[commKey]["sts"],
               ratingsContentCommentCommentsObject[commKey]["img"],
               ratingsContentCommentCommentsObject[commKey]["comment"]
             );
          }
        } else {
          ratingsBtnHandler("def", "set-up-step-by-step-content-footer-continue-btn-"+ part +"-comment");
          document.getElementById("set-up-step-by-step-content-error-txt-"+ part +"-comment").innerHTML = xhrSectionComment.response;
        }
      }
    }
    xhrSectionComment.open("POST", "php-backend/uni/save-comments.php");
    xhrSectionComment.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrSectionComment.send("booking="+ url_booking +"&fromd="+ url_fromd +"&fromm="+ url_fromm +"&fromy="+ url_fromy +"&tod="+ url_tod +"&tom="+ url_tom +"&toy="+ url_toy +"&plc="+ url_plc_id +"&part="+ part +"&comment="+ rSectionCommentTxt);
  }
}

var selectedRatingsContentNum = "none";
function ratingsContentContinue() {
  selectedRatingsContentNum = "none";
  for (var rC = 0; rC < document.getElementsByClassName("set-up-step-by-step-content").length; rC++) {
    if (document.getElementsByClassName("set-up-step-by-step-content")[rC].classList.contains("set-up-step-by-step-content-selected")) {
      selectedRatingsContentNum = rC;
    }
  }
  if (selectedRatingsContentNum == "none") {
    ratingsOverviewHandler("show");
  } else {
    ++selectedRatingsContentNum;
    if (selectedRatingsContentNum >= document.getElementsByClassName("set-up-step-by-step-content").length) {
      ratingsOverviewHandler("show");
    } else {
      ratingsContentBackBtnHandler("back", selectedRatingsContentNum);
      ratingsContentSetSlide(selectedRatingsContentNum);
    }
  }
}

function ratingsContentBack(tsk) {
  if (tsk == "back") {
    selectedRatingsContentNum = "none";
    for (var rC = 0; rC < document.getElementsByClassName("set-up-step-by-step-content").length; rC++) {
      if (document.getElementsByClassName("set-up-step-by-step-content")[rC].classList.contains("set-up-step-by-step-content-selected")) {
        selectedRatingsContentNum = rC;
      }
    }
    if (selectedRatingsContentNum == "none") {
      ratingsOverviewHandler("show");
    } else {
      --selectedRatingsContentNum;
      if (selectedRatingsContentNum < 0) {
        ratingsOverviewHandler("show");
      } else {
        ratingsContentSetSlide(selectedRatingsContentNum);
      }
    }
  } else {
    ratingsOverviewHandler("show");
  }
}

var selectedRatingsBtnNum;
function ratingsContentBackBtnHandler(tsk, selectedRatingsContentNum) {
  if (selectedRatingsContentNum < document.getElementsByClassName("set-up-step-by-step-content-places").length) {
    selectedRatingsBtnNum = selectedRatingsContentNum -1;
  } else {
    selectedRatingsBtnNum = selectedRatingsContentNum -2;
  }
  if (tsk == "back") {
    document.getElementsByClassName("set-up-step-by-step-content-footer-back-btn")[selectedRatingsBtnNum].value = "back";
  } else {
    document.getElementsByClassName("set-up-step-by-step-content-footer-back-btn")[selectedRatingsBtnNum].value = "overview";
  }
}

function ratingsContentSetSlide(selectedRatingsContentNum) {
  setUpStepByStepSliderFncReset();
  ratingsSectSlidesCounter(selectedRatingsContentNum);
  document.getElementsByClassName("set-up-step-by-step-content")[selectedRatingsContentNum].classList.add("set-up-step-by-step-content-selected");
}

function ratingsSectSlidesCounter(selectedRatingsContentNum) {
  if (selectedRatingsContentNum > document.getElementsByClassName("set-up-step-by-step-content-places").length) {
    selectedRatingsContentNum = selectedRatingsContentNum - document.getElementsByClassName("set-up-step-by-step-content-footer-counter-txt-places").length - 1;
    for (var fCU = 0; fCU < document.getElementsByClassName("set-up-step-by-step-content-footer-counter-txt-users").length; fCU++) {
      document.getElementsByClassName("set-up-step-by-step-content-footer-counter-txt-users")[fCU].innerHTML = selectedRatingsContentNum +" / "+ document.getElementsByClassName("set-up-step-by-step-content-footer-counter-txt-users").length;
    }
  } else {
    for (var fCP = 0; fCP < document.getElementsByClassName("set-up-step-by-step-content-footer-counter-txt-places").length; fCP++) {
      document.getElementsByClassName("set-up-step-by-step-content-footer-counter-txt-places")[fCP].innerHTML = selectedRatingsContentNum +" / "+ document.getElementsByClassName("set-up-step-by-step-content-footer-counter-txt-places").length;
    }
  }
}

function ratingsBtnHandler(task, id) {
  if (task == "def") {
    document.getElementById(id).style.color = "#fff";
    document.getElementById(id).style.backgroundImage = "unset";
    document.getElementById(id).style.backgroundSize = "unset";
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

function ratingsDataUpdate(criticsSummaryPlace, plcLct, plcTidy, plcPrc, plcPark, plcAd, criticsSummaryHost, hstLang, hstComm, hstPrsn) {
  criticsSummaryPlace = Math.round(((criticsSummaryPlace * 5 / 100) + Number.EPSILON) * 100) / 100;
  document.getElementById("ratings-overview-section-summary-stars-num-place").innerHTML = criticsSummaryPlace.toString().replace(".", ",");
  criticsSummaryHost = Math.round(((criticsSummaryHost * 5 / 100) + Number.EPSILON) * 100) / 100;
  document.getElementById("ratings-overview-section-summary-stars-num-host").innerHTML = criticsSummaryHost.toString().replace(".", ",");
  if (plcLct != "none") {
    setUpStepByStepSliderFncSetProgress("places-location", plcLct);
    document.getElementById("r-o-s-d-block-places-location").classList.remove("r-o-s-d-block-not-rated");
    document.getElementById("r-o-s-d-progress-places-location").style.width = plcLct +"%";
  } else {
    setUpStepByStepSliderFncSetProgress("places-location", 50);
    if (!document.getElementById("r-o-s-d-block-places-location").classList.contains("r-o-s-d-block-not-rated")) {
      document.getElementById("r-o-s-d-block-places-location").classList.add("r-o-s-d-block-not-rated");
    }
    document.getElementById("r-o-s-d-progress-places-location").style.width = "0%";
  }
  if (plcTidy != "none") {
    setUpStepByStepSliderFncSetProgress("places-tidines", plcTidy);
    document.getElementById("r-o-s-d-block-places-tidines").classList.remove("r-o-s-d-block-not-rated");
    document.getElementById("r-o-s-d-progress-places-tidines").style.width = plcTidy +"%";
  } else {
    setUpStepByStepSliderFncSetProgress("places-tidines", 50);
    if (!document.getElementById("r-o-s-d-block-places-tidines").classList.contains("r-o-s-d-block-not-rated")) {
      document.getElementById("r-o-s-d-block-places-tidines").classList.add("r-o-s-d-block-not-rated");
    }
    document.getElementById("r-o-s-d-progress-places-tidines").style.width = "0%";
  }
  if (plcPrc != "none") {
    setUpStepByStepSliderFncSetProgress("places-price", plcPrc);
    document.getElementById("r-o-s-d-block-places-price").classList.remove("r-o-s-d-block-not-rated");
    document.getElementById("r-o-s-d-progress-places-price").style.width = plcPrc +"%";
  } else {
    setUpStepByStepSliderFncSetProgress("places-price", 50);
    if (!document.getElementById("r-o-s-d-block-places-price").classList.contains("r-o-s-d-block-not-rated")) {
      document.getElementById("r-o-s-d-block-places-price").classList.add("r-o-s-d-block-not-rated");
    }
    document.getElementById("r-o-s-d-progress-places-price").style.width = "0%";
  }
  if (plcPark != "none") {
    setUpStepByStepSliderFncSetProgress("places-parking", plcPark);
    document.getElementById("r-o-s-d-block-places-parking").classList.remove("r-o-s-d-block-not-rated");
    document.getElementById("r-o-s-d-progress-places-parking").style.width = plcPark +"%";
  } else {
    setUpStepByStepSliderFncSetProgress("places-parking", 50);
    if (!document.getElementById("r-o-s-d-block-places-parking").classList.contains("r-o-s-d-block-not-rated")) {
      document.getElementById("r-o-s-d-block-places-parking").classList.add("r-o-s-d-block-not-rated");
    }
    document.getElementById("r-o-s-d-progress-places-parking").style.width = "0%";
  }
  if (plcAd != "none") {
    setUpStepByStepSliderFncSetProgress("places-ad", plcAd);
    document.getElementById("r-o-s-d-block-places-ad").classList.remove("r-o-s-d-block-not-rated");
    document.getElementById("r-o-s-d-progress-places-ad").style.width = plcAd +"%";
  } else {
    setUpStepByStepSliderFncSetProgress("places-ad", 50);
    if (!document.getElementById("r-o-s-d-block-places-ad").classList.contains("r-o-s-d-block-not-rated")) {
      document.getElementById("r-o-s-d-block-places-ad").classList.add("r-o-s-d-block-not-rated");
    }
    document.getElementById("r-o-s-d-progress-places-ad").style.width = "0%";
  }
  if (hstLang != "none" || hstComm != "none" || hstPrsn != "none") {
    document.getElementById("ratings-overview-section-rate-btn-wrp-host").style.display = "none";
    document.getElementById("ratings-overview-section-layout-host").style.display = "";
  } else {
    document.getElementById("ratings-overview-section-rate-btn-wrp-host").style.display = "";
    document.getElementById("ratings-overview-section-layout-host").style.display = "none";
  }
  if (hstLang != "none") {
    setUpStepByStepSliderFncSetProgress("users-language", hstLang);
    document.getElementById("r-o-s-d-block-users-language").classList.remove("r-o-s-d-block-not-rated");
    document.getElementById("r-o-s-d-progress-users-language").style.width = hstLang +"%";
  } else {
    setUpStepByStepSliderFncSetProgress("users-language", 50);
    if (!document.getElementById("r-o-s-d-block-users-language").classList.contains("r-o-s-d-block-not-rated")) {
      document.getElementById("r-o-s-d-block-users-language").classList.add("r-o-s-d-block-not-rated");
    }
    document.getElementById("r-o-s-d-progress-users-language").style.width = "0%";
  }
  if (hstComm != "none") {
    setUpStepByStepSliderFncSetProgress("users-communication", hstComm);
    document.getElementById("r-o-s-d-block-users-communication").classList.remove("r-o-s-d-block-not-rated");
    document.getElementById("r-o-s-d-progress-users-communication").style.width = hstComm +"%";
  } else {
    setUpStepByStepSliderFncSetProgress("users-communication", 50);
    if (!document.getElementById("r-o-s-d-block-users-communication").classList.contains("r-o-s-d-block-not-rated")) {
      document.getElementById("r-o-s-d-block-users-communication").classList.add("r-o-s-d-block-not-rated");
    }
    document.getElementById("r-o-s-d-progress-users-communication").style.width = "0%";
  }
  if (hstPrsn != "none") {
    setUpStepByStepSliderFncSetProgress("users-personality", hstPrsn);
    document.getElementById("r-o-s-d-block-users-personality").classList.remove("r-o-s-d-block-not-rated");
    document.getElementById("r-o-s-d-progress-users-personality").style.width = hstPrsn +"%";
  } else {
    setUpStepByStepSliderFncSetProgress("users-personality", 50);
    if (!document.getElementById("r-o-s-d-block-users-personality").classList.contains("r-o-s-d-block-not-rated")) {
      document.getElementById("r-o-s-d-block-users-personality").classList.add("r-o-s-d-block-not-rated");
    }
    document.getElementById("r-o-s-d-progress-users-personality").style.width = "0%";
  }
}

function ratingsRenderComments(commID, sts, midProfImg, commTxt) {
  var row = document.createElement("div");
  row.setAttribute("class", "ratings-overview-section-details-comment-row");
  row.setAttribute("id", "ratings-overview-section-details-comment-row-"+ commID);
  var delBtn = document.createElement("button");
  delBtn.setAttribute("type", "button");
  delBtn.setAttribute("value", commID);
  delBtn.setAttribute("class", "ratings-overview-section-details-comment-delete-btn");
  delBtn.setAttribute("onclick", "ratingsDeleteComment(this);");
  var imgWrp = document.createElement("div");
  imgWrp.setAttribute("class", "ratings-overview-section-details-comment-img-wrp");
  var img = document.createElement("img");
  img.setAttribute("src", "../"+ midProfImg);
  img.setAttribute("alt", "Profile image");
  img.setAttribute("class", "ratings-overview-section-details-comment-img");
  var commWrp = document.createElement("div");
  commWrp.setAttribute("class", "ratings-overview-section-details-comment-txt-wrp");
  var comm = document.createElement("p");
  comm.setAttribute("class", "ratings-overview-section-details-comment-txt");
  comm.innerHTML = commTxt.replace(/(\r\n|\r|\n)/g, '<br>');
  row.appendChild(delBtn);
  row.appendChild(imgWrp);
  imgWrp.appendChild(img);
  row.appendChild(commWrp);
  commWrp.appendChild(comm);
  if (sts == "place") {
    document.getElementById("ratings-overview-section-details-comment-list-places").prepend(row);
  } else {
    document.getElementById("ratings-overview-section-details-comment-list-host").prepend(row);
  }
}
