function ratingsOverviewHandler(tsk) {
  if (tsk == "show") {
    setUpStepByStepSliderFncReset();
    document.getElementById("ratings-overview-wrp").style.display = "flex";
  } else {
    document.getElementById("ratings-overview-wrp").style.display = "";
  }
}

var ratingsHostSlidesReady, ratingsHostSlidesNum;
function ratingsOverviewRateHost() {
  ratingsHostSlidesReady = false;
  ratingsHostSlidesNum = 0;
  ratingsOverviewHandler("hide");
  for (var rOC = 0; rOC < document.getElementsByClassName("set-up-step-by-step-content").length; rOC++) {
    if (document.getElementsByClassName("set-up-step-by-step-content")[rOC].classList.contains("set-up-step-by-step-content-users")) {
      if (ratingsHostSlidesReady && ratingsHostSlidesNum == 0) {
        ratingsHostSlidesNum = rOC;
      } else {
        ratingsHostSlidesReady = true;
      }
    }
  }
  ratingsContentBackBtnHandler("overview", ratingsHostSlidesNum);
  ratingsContentSetSlide(ratingsHostSlidesNum);
}

var ratingsSectSlidesNum;
function ratingsOverviewRateSection(sect, part) {
  ratingsOverviewHandler("hide");
  for (var rOCU = 0; rOCU < document.getElementsByClassName("set-up-step-by-step-content").length; rOCU++) {
    if (document.getElementsByClassName("set-up-step-by-step-content")[rOCU].id == "set-up-step-by-step-content-"+ part +"-"+ sect) {
      ratingsSectSlidesNum = rOCU;
    }
  }
  ratingsContentBackBtnHandler("overview", ratingsSectSlidesNum);
  ratingsContentSetSlide(ratingsSectSlidesNum);
}

var ratingsContentCommentCommentsObject = {};
function ratingsDeleteComment(btn) {
  ratingsDeleteCommentBtnHandler(btn, "load");
  ratingsContentCommentCommentsObject = {};
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      if (testJSON(xhr.response)) {
        var json = JSON.parse(xhr.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "done") {
              ratingsDataUpdate(json[key]["criticsSummaryPlace"], json[key]["plcLct"], json[key]["plcTidy"], json[key]["plcPrc"], json[key]["plcPark"], json[key]["plcAd"], json[key]["criticsSummaryHost"], json[key]["hstLang"], json[key]["hstComm"], json[key]["hstPrsn"]);
            } else if (json[key]["type"] == "comment") {
              ratingsContentCommentCommentsObject[++Object.keys(ratingsContentCommentCommentsObject).length] = {
                id: json[key]["id"],
                sts: json[key]["sts"],
                img: json[key]["img"],
                comment: json[key]["comment"]
              };
            } else {
              ratingsDeleteCommentBtnHandler(btn, "def");
              alert(json[key]["msg"]);
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
        ratingsDeleteCommentBtnHandler(btn, "def");
        alert(xhr.response);
      }
    }
  }
  xhr.open("POST", "php-backend/uni/delete-comments.php");
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("booking="+ url_booking +"&fromd="+ url_fromd +"&fromm="+ url_fromm +"&fromy="+ url_fromy +"&tod="+ url_tod +"&tom="+ url_tom +"&toy="+ url_toy +"&plc="+ url_plc_id +"&id="+ btn.value);
}

function ratingsDeleteCommentBtnHandler(btn, tsk) {
  if (tsk == "load") {
    btn.style.backgroundColor = "rgba(0,0,0,0.15)";
    btn.style.backgroundImage = "url('../uni/gifs/loader-tail.svg')";
    btn.style.backgroundSize = "115%";
    btn.style.opacity = "1";
  } else {
    btn.style.backgroundColor = "";
    btn.style.backgroundImage = "";
    btn.style.backgroundSize = "";
    btn.style.opacity = "";
  }
}
