window.addEventListener('load', (event) => {
  var numEx = 0;
  for (var i = 0; i < document.getElementsByClassName("sett-def-wrp").length; i++) {
    numEx++;
  }
  if (numEx == 0) {
    document.getElementById("crop-change-btn-2").style.display = "table";
    document.getElementById("crop-change-btn-2").style.opacity = "1";
  }
});

var sImgSrc, bImgSrc, mImgSrc, goodOutput, logMsg, xhrNSPI;
function setNewProfileImg(img) {
  sImgSrc = "";
  mImgSrc = "";
  bImgSrc = "";
  goodOutput = "";
  errCode = "";
  img = img.replace(/^.*[\\\/]/, "");
  img = img.split(".").slice(0, -1).join(".");
  xhrNSPI = new XMLHttpRequest();
  xhrNSPI.onreadystatechange = function() {
    if (xhrNSPI.readyState == 4 && xhrNSPI.status == 200) {
      if (testJSON(xhrNSPI.response)) {
        var json = JSON.parse(xhrNSPI.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["sts"] == 1) {
              goodOutput = true;
            } else if (json[key]["sts"] == 2) {
              errCode = json[key]["msg"];
              goodOutput = false;
            } else if (json[key]["sts"] == 3) {
              console.log(json[key]["msg"]);
            } else if (json[key]["sts"] == 4) {
              sImgSrc = json[key]["msg"];
            } else if (json[key]["sts"] == 5) {
              mImgSrc = json[key]["msg"];
            } else if (json[key]["sts"] == 6) {
              bImgSrc = json[key]["msg"];
            }
          }
        }
        if (goodOutput) {
          resetCrop(2);
          modCover("hide", "modal-cover-prof-img");
        } else {
          setNewPrfImgErr(errCode);
        }
        if (sImgSrc != "#") {
          document.getElementById("account-img").setAttribute("src", "../"+ sImgSrc);
        } else {
          document.getElementById("account-img").setAttribute("src", "#");
        }
        if (bImgSrc != "#") {
          document.getElementById("set-curr-prof-img").setAttribute("src", "../"+ bImgSrc);
        } else {
          document.getElementById("set-curr-prof-img").setAttribute("src", "#");
        }
        if (document.getElementById("my-profile")) {
          if (mImgSrc != "#") {
            for (var uII1 = 0; uII1 < document.getElementsByClassName("user-info-img").length; uII1++) {
              document.getElementsByClassName("user-info-img")[uII1].setAttribute("src", "../"+ mImgSrc);
            }
          } else {
            for (var uII2 = 0; uII2 < document.getElementsByClassName("user-info-img").length; uII2++) {
              document.getElementsByClassName("user-info-img")[uII2].setAttribute("src", "#");
            }
          }
        }
        if (document.getElementById("my-place-icn")) {
          if (mImgSrc != "#") {
            document.getElementById("p-d-t-user-icn").setAttribute("src", "../"+ mImgSrc);
            document.getElementById("t-f-b-prof-img").setAttribute("src", "../"+ mImgSrc);
            document.getElementById("place-space-icn-prof-img").setAttribute("src", "../"+ mImgSrc);
          } else {
            document.getElementById("p-d-t-user-icn").setAttribute("src", "#");
            document.getElementById("t-f-b-prof-img").setAttribute("src", "#");
            document.getElementById("place-space-icn-prof-img").setAttribute("src", "#");
          }
        }
        if (document.getElementById("p-d-a-comments-wrp")) {
          for (var i = 0; i < document.getElementsByClassName("my-plc-comm-img").length; i++) {
            if (mImgSrc != "#") {
              document.getElementsByClassName("my-plc-comm-img")[i].setAttribute("src", "../"+ mImgSrc);
            } else {
              document.getElementsByClassName("my-plc-comm-img")[i].setAttribute("src", "#");
            }
          }
        }
        for (var c = 0; c < document.getElementsByClassName("my-u-a-r-comment-critic-img").length; c++) {
          if (sImgSrc != "#") {
            document.getElementsByClassName("my-u-a-r-comment-critic-img")[c].setAttribute("src", "../"+ sImgSrc);
          } else {
            document.getElementsByClassName("my-u-a-r-comment-critic-img")[c].setAttribute("src", "#");
          }
        }
        if (document.getElementById("ratings-wrp")) {
          for (var r = 0; r < document.getElementsByClassName("ratings-overview-section-details-comment-img").length; r++) {
            if (mImgSrc != "#") {
              document.getElementsByClassName("ratings-overview-section-details-comment-img")[r].setAttribute("src", "../"+ mImgSrc);
            } else {
              document.getElementsByClassName("ratings-overview-section-details-comment-img")[r].setAttribute("src", "#");
            }
          }
        }
        if (document.getElementById("about-booking-facility-card-i-am-the-host")) {
          if (mImgSrc != "#") {
            document.getElementById("about-booking-facility-host-img").setAttribute("src", "../"+ mImgSrc);
          } else {
            document.getElementById("about-booking-facility-host-img").setAttribute("src", "#");
          }
        }
      } else {
        setNewPrfImgErr("json-error");
      }
    }
  }
  xhrNSPI.open("POST", "../uni/code/php-backend/new-profile-img.php");
  xhrNSPI.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrNSPI.send("name="+ img);
}

var errId;
function setNewPrfImgErr(code) {
  if (code == "json-error") {
    console.log("profile-image-activation-json-error");
    errId = 1.3;
  } else if (code == "data-error-1" || code == "data-error-2" || code == "data-error-3") {
    errId = 8;
  } else if (code == "all-img-failed") {
    errId = 9;
  } else if (code == "some-img-failed") {
    errId = 10;
  }
  cropErrorShow(2, errId)
}

function setNewProfileImgReset() {
  if (xhrNSPI != null) {
    xhrNSPI.abort();
  }
}
