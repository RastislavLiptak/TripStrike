window.addEventListener('load', function(e) {
  getCottLinksData();
});

var url_string = window.location.href;
var url = new URL(url_string);
var user_id = url.searchParams.get("id");
var arrCottageIds = [];
var lastCottId = "";
var imgLinksListReady = true;
function getCottLinksData() {
  if (imgLinksListReady && imgLinksListReady != "no-more") {
    imgLinksListReady = false;
    if (typeof arrCottageIds !== 'undefined' && arrCottageIds.length > 0) {
      lastCottId = arrCottageIds[0];
      loadMoreStatus("loading");
    } else {
      lastCottId = "";
      userCottageMainLoaderHandler("show");
    }
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        if (testJSON(xhr.response)) {
          var json = JSON.parse(xhr.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "link") {
                arrCottIdsHandler("old", json[key]["id"]);
                imgCottLinkRender("old", "user-cottage-grid", json[key]["class"], json[key]["id"], json[key]["name"], json[key]["src"], json[key]["priceMode"], json[key]["priceWeek"], json[key]["priceWork"], json[key]["currency"], json[key]["rating"]);
                moveLoadMoreBtn();
              } else if (json[key]["type"] == "load-next-sts") {
                loadMoreStatus("show");
                if (json[key]["all"] == 0) {
                  document.getElementById("u-c-no-cottage").style.display = "flex";
                  imgLinksListReady = "no-more";
                  loadMoreStatus("hide");
                } else if (json[key]["progress"] == 0 || json[key]["remain"] == 0) {
                  imgLinksListReady = "no-more";
                  loadMoreStatus("hide");
                }
              } else {
                userCottageError(json[key]["error"]);
                loadMoreStatus("auto");
              }
            }
          }
          if (imgLinksListReady != "no-more") {
            imgLinksListReady = true;
          }
        } else {
          if (imgLinksListReady != "no-more") {
            imgLinksListReady = true;
          }
          userCottageError("json-error");
          loadMoreStatus("auto");
        }
        userCottageMainLoaderHandler("hide");
      }
    }
    xhr.open("POST", "../user/php-backend/get-user-cottage-list.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("user="+ user_id +"&lastId="+ lastCottId);
  }
}

function arrCottIdsHandler(sts, id) {
  if (sts == "new") {
    arrCottageIds.push(id);
  } else {
    arrCottageIds.unshift(id);
  }
}

var nOfLinks = document.getElementsByClassName("link-img-blck");
function userCottageError(code) {
  if (code == "json-error") {
    if (nOfLinks.length == 0) {
      modCover("show", "modal-cover-huts-list-error-1-1");
    } else {
      modCover("show", "modal-cover-huts-list-error-1-2");
    }
  } else if (code == "last-cottage-n-found" || code == "id-n-found" || code == "user-id-n-found") {
    if (nOfLinks.length != 0) {
      modCover("show", "modal-cover-huts-list-error-2-1");
    } else {
      modCover("show", "modal-cover-huts-list-error-2-2");
    }
    console.log(code);
  }
}

function userCottageMainLoaderHandler(tsk) {
  if (tsk == "hide") {
    document.getElementById("u-c-loader").style.display = "none";
  } else {
    document.getElementById("u-c-loader").style.display = "";
  }
}

var child;
function userCottageRetry(sts, modId) {
  modCover('hide', modId);
  imgLinksListReady = true;
  if (sts == "from-0") {
    arrCottageIds = [];
    child = nOfLinks[0];
    while (child) {
      child.parentNode.removeChild(child);
      child = nOfLinks[0];
    }
    getCottLinksData();
  } else if (sts == "from-last") {
    getCottLinksData();
  }
}

var moreBtnSts = false;
function loadMoreStatus(sts) {
  var lMWrp = document.getElementById("u-c-load-more-wrp");
  var lMBtn = document.getElementById("u-c-load-more-btn");
  var lMIcon = document.getElementById("u-c-load-more-btn-icon");
  var lMTxt = document.getElementById("u-c-load-more-btn-txt");
  if (sts == "loading") {
    lMWrp.style.display = "flex";
    lMBtn.style.cursor = "default";
    lMIcon.style.backgroundImage = "url('../uni/gifs/loader-tail.svg')";
    lMIcon.style.backgroundSize = "110%";
    lMTxt.style.opacity = "0";
  } else if (sts == "show") {
    moreBtnSts = true;
    lMWrp.style.display = "flex";
    lMBtn.style.cursor = "";
    lMIcon.style.backgroundImage = "";
    lMIcon.style.backgroundSize = "";
    lMTxt.style.opacity = "";
  } else if (sts == "hide") {
    moreBtnSts = false;
    lMWrp.style.display = "";
    lMBtn.style.cursor = "";
    lMIcon.style.backgroundImage = "";
    lMIcon.style.backgroundSize = "";
    lMTxt.style.opacity = "";
  } else if (sts == "auto") {
    if (moreBtnSts) {
      loadMoreStatus("show");
    } else {
      loadMoreStatus("hide");
    }
  }
}

var moreBtnWrpId = "u-c-load-more-wrp";
var btnClone, moreBtn, numOfLinksInRow, usrCottGrid;
function moveLoadMoreBtn() {
  usrCottGrid = document.getElementById("user-cottage-grid");
  moreBtn = document.getElementById(moreBtnWrpId);
  btnClone = moreBtn.cloneNode(true);
  moreBtn.parentNode.removeChild(moreBtn);
  if (window.innerWidth >= 1750) {
    numOfLinksInRow = 6;
  } else if (window.innerWidth >= 1500) {
    numOfLinksInRow = 5;
  } else if (window.innerWidth >= 1290) {
    numOfLinksInRow = 4;
  } else if (window.innerWidth >= 1025) {
    numOfLinksInRow = 3;
  } else if (window.innerWidth >= 750) {
    numOfLinksInRow = 2;
  } else {
    numOfLinksInRow = 1;
  }
  if ((document.getElementsByClassName("link-img-blck").length / numOfLinksInRow) % 1 != 0) {
    btnClone.style.marginTop = "";
    usrCottGrid.appendChild(btnClone);
  } else {
    btnClone.style.marginTop = "25px";
    usrCottGrid.parentNode.insertBefore(btnClone, usrCottGrid.nextSibling);
  }
}
