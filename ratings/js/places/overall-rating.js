var ratingsOverallRatingSaveReady = true;
var ratingsOverallRatingCommentsObject = {};
var rOverallRatingProgress, xhrOverallRating, ratingTimerOverallRating;
function ratingsOverallRatingOnclick(sts) {
  if (ratingsOverallRatingSaveReady) {
    ratingsOverallRatingSaveReady = false;
    clearTimeout(ratingTimerOverallRating);
    ratingsOverallRatingCommentsObject = {};
    if (sts == "skip") {
      ratingsBtnHandler("load", "set-up-step-by-step-content-footer-btn-overall-rating-skip");
    } else {
      ratingsBtnHandler("load", "set-up-step-by-step-content-footer-btn-overall-rating-continue");
    }
    rOverallRatingProgress = document.getElementById("set-up-step-by-step-content-slider-about-places-overall-rating-progress").innerHTML;
    document.getElementById("set-up-step-by-step-content-error-txt-places-overall-rating").innerHTML = "";
    xhrOverallRating = new XMLHttpRequest();
    xhrOverallRating.onreadystatechange = function() {
      if (xhrOverallRating.readyState == 4 && xhrOverallRating.status == 200) {
        ratingsOverallRatingSaveReady = true;
        if (testJSON(xhrOverallRating.response)) {
          var json = JSON.parse(xhrOverallRating.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                ratingsDataUpdate(json[key]["criticsSummaryPlace"], json[key]["plcLct"], json[key]["plcTidy"], json[key]["plcPrc"], json[key]["plcPark"], json[key]["plcAd"], json[key]["criticsSummaryHost"], json[key]["hstLang"], json[key]["hstComm"], json[key]["hstPrsn"]);
                if (sts == "skip") {
                  ratingsBtnHandler("done", "set-up-step-by-step-content-footer-btn-overall-rating-skip");
                } else {
                  ratingsBtnHandler("done", "set-up-step-by-step-content-footer-btn-overall-rating-continue");
                }
                ratingTimerOverallRating = setTimeout(function(){
                  if (sts == "skip") {
                    ratingsBtnHandler("def", "set-up-step-by-step-content-footer-btn-overall-rating-skip");
                    ratingsOverviewHandler("show");
                  } else {
                    ratingsBtnHandler("def", "set-up-step-by-step-content-footer-btn-overall-rating-continue");
                    ratingsContentContinue("place");
                  }
                }, 750);
              } else if (json[key]["type"] == "comment") {
                ratingsOverallRatingCommentsObject[++Object.keys(ratingsOverallRatingCommentsObject).length] = {
                  id: json[key]["id"],
                  sts: json[key]["sts"],
                  img: json[key]["img"],
                  comment: json[key]["comment"]
                };
              } else {
                if (sts == "skip") {
                  ratingsBtnHandler("def", "set-up-step-by-step-content-footer-btn-overall-rating-skip");
                } else {
                  ratingsBtnHandler("def", "set-up-step-by-step-content-footer-btn-overall-rating-continue");
                }
                document.getElementById("set-up-step-by-step-content-error-txt-places-overall-rating").innerHTML = json[key]["error"];
              }
            }
          }
          document.getElementById("ratings-overview-section-details-comment-list-places").innerHTML = "";
          document.getElementById("ratings-overview-section-details-comment-list-host").innerHTML = "";
          for (var commKey in ratingsOverallRatingCommentsObject) {
             ratingsRenderComments(
               ratingsOverallRatingCommentsObject[commKey]["id"],
               ratingsOverallRatingCommentsObject[commKey]["sts"],
               ratingsOverallRatingCommentsObject[commKey]["img"],
               ratingsOverallRatingCommentsObject[commKey]["comment"]
             );
          }
        } else {
          if (sts == "skip") {
            ratingsBtnHandler("def", "set-up-step-by-step-content-footer-btn-overall-rating-skip");
          } else {
            ratingsBtnHandler("def", "set-up-step-by-step-content-footer-btn-overall-rating-continue");
          }
          document.getElementById("set-up-step-by-step-content-error-txt-places-overall-rating").innerHTML = xhrOverallRating.response;
        }
      }
    }
    xhrOverallRating.open("POST", "php-backend/places/overall-rating.php");
    xhrOverallRating.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrOverallRating.send("booking="+ url_booking +"&fromd="+ url_fromd +"&fromm="+ url_fromm +"&fromy="+ url_fromy +"&tod="+ url_tod +"&tom="+ url_tom +"&toy="+ url_toy +"&plc="+ url_plc_id +"&progress="+ rOverallRatingProgress);
  }
}
