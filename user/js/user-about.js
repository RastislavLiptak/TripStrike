var url_string = window.location.href;
var url = new URL(url_string);
var user_id = url.searchParams.get("id");

window.addEventListener('load', function() {
  loadCottageData();
  loadCommentsData();
  userCommentsShowMoreBtns();
});

window.addEventListener('resize', function() {
  userCommentsShowMoreBtns();
});

var loadCottReady = true;
function loadCottageData() {
  if (loadCottReady) {
    loadCottReady = false;
    loadCottageDataErrorHandler("hide");
    loadCottageDataLoaderHandler("show");
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        loadCottageDataLoaderHandler("hide");
        loadCottReady = true;
        if (testJSON(xhr.response)) {
          var json = JSON.parse(xhr.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "link") {
                imgCottLinkRender("old", "u-a-links-list", json[key]["class"], json[key]["id"], json[key]["name"], json[key]["src"], json[key]["priceMode"], json[key]["priceWeek"], json[key]["priceWork"], json[key]["currency"], json[key]["rating"]);
              } else {
                loadCottageError(json[key]["error"]);
              }
            }
          }
        } else {
          loadCottageError("json-error");
        }
      }
    }
    xhr.open("POST", "../user/php-backend/get-user-top-picks.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("user="+ user_id);
  }
}

function loadCottageError(code) {
  fakeImgCottLinkRender("u-a-links-list");
  if (code == "json-error") {
    loadCottageDataErrorHandler(3);
  } else if (code == "no-user") {
    loadCottageDataErrorHandler(2);
  } else if (code == "no-cottages") {
    loadCottageDataErrorHandler(1);
  }
}

function loadCottageDataLoaderHandler(tsk) {
  if (tsk == "show") {
    document.getElementById("u-a-links-loader").style.display = "";
  } else {
    document.getElementById("u-a-links-loader").style.display = "none";
  }
}

function loadCottageDataErrorHandler(tsk) {
  if (tsk == "hide") {
    for (var i = 0; i < document.getElementsByClassName("u-a-links-errors-blck").length; i++) {
      document.getElementsByClassName("u-a-links-errors-blck")[i].style.display = "";
    }
  } else {
    document.getElementById("u-a-links-errors-blck-"+ tsk).style.display = "flex";
  }
}

var wrd_showMore = "Show more";
function userCommentsDictionary(showMore) {
  wrd_showMore = showMore;
}

var arrCommIds = [];
var lastCommId = "";
var loadCommReady = true;
function loadCommentsData() {
  if (document.getElementById("u-a-r-grid")){
    if (loadCommReady && loadCommReady != "no-more") {
      loadCommReady = false;
      if (typeof arrCommIds !== 'undefined' && arrCommIds.length > 0) {
        lastCommId = arrCommIds[0];
      } else {
        lastCommId = "";
      }
      loadCommentsDataError("reset");
      loadMoreStatus("loading");
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
          loadCommReady = true;
          if (testJSON(xhr.response)) {
            var json = JSON.parse(xhr.response);
            for (var key in json) {
              if (json.hasOwnProperty(key)) {
                if (json[key]["type"] == "link") {
                  arrCommIdsHandler(json[key]["id"]);
                  userCommRender(json[key]["date"], json[key]["comment"], json[key]["criticId"], json[key]["firstname"], json[key]["lastname"], json[key]["img"], json[key]["accSts"], json[key]["stars"]);
                  moveLoadMoreBtn();
                } else if (json[key]["type"] == "load-next-sts") {
                  loadMoreStatus("show");
                  if (json[key]["all"] == 0) {
                    loadCommentsDataError("no-comment-found");
                    loadCommReady = "no-more";
                    loadMoreStatus("hide");
                  } else if (json[key]["progress"] == 0 || json[key]["remain"] == 0) {
                    loadCommReady = "no-more";
                    loadMoreStatus("hide");
                  }
                } else {
                  loadCommentsDataError(json[key]["error"]);
                  loadMoreStatus("auto");
                }
              }
            }
            if (loadCommReady != "no-more") {
              loadCommReady = true;
            }
          } else {
            if (loadCommReady != "no-more") {
              loadCommReady = true;
            }
            loadCommentsDataError(xhr.response);
            loadMoreStatus("auto");
          }
          userCommentsShowMoreBtns();
        }
      }
      xhr.open("POST", "../user/php-backend/get-user-rating-comments.php");
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.send("user="+ user_id +"&lastId="+ lastCommId);
    }
  }
}

function arrCommIdsHandler(id) {
  arrCommIds.unshift(id);
}

var moreBtnWrpId = "u-a-r-load-more-wrp";
var btnClone, moreBtn, numOfCommsInRow, usrCommGrid;
function moveLoadMoreBtn() {
  usrCommGrid = document.getElementById("u-a-r-grid");
  moreBtn = document.getElementById(moreBtnWrpId);
  btnClone = moreBtn.cloneNode(true);
  moreBtn.parentNode.removeChild(moreBtn);
  if (window.innerWidth <= 850) {
    numOfCommsInRow = 1;
  } else if (window.innerWidth <= 1150) {
    numOfCommsInRow = 2;
  } else if (window.innerWidth <= 1450) {
    numOfCommsInRow = 3;
  } else {
    numOfCommsInRow = 4;
  }
  if ((document.getElementsByClassName("u-a-r-comment-wrp").length / numOfCommsInRow) % 1 != 0) {
    btnClone.style.marginTop = "25px";
    usrCommGrid.appendChild(btnClone);
  } else {
    btnClone.style.marginTop = "";
    usrCommGrid.parentNode.insertBefore(btnClone, usrCommGrid.nextSibling);
  }
}

var moreBtnSts = false;
function loadMoreStatus(sts) {
  var lMWrp = document.getElementById("u-a-r-load-more-wrp");
  var lMBtn = document.getElementById("u-a-r-load-more-btn");
  var lMIcon = document.getElementById("u-a-r-load-more-btn-icon");
  var lMTxt = document.getElementById("u-a-r-load-more-btn-txt");
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

function loadCommentsDataError(code) {
  if (code == "reset") {
    document.getElementById("u-a-r-error-wrp").style.display = "";
  } else {
    document.getElementById("u-a-r-error-wrp").style.display = "flex";
    document.getElementById("u-a-r-error-code-text").innerHTML = code;
  }
}

var commNum = 0;
function userCommRender(comm_date, comm_txt, comm_criticId, comm_firstname, comm_lastname, comm_img, acc_sts, comm_stars) {
  var wrp = document.createElement("div");
  wrp.setAttribute("class", "u-a-r-comment-wrp");
  var blck = document.createElement("div");
  blck.setAttribute("class", "u-a-r-comment-blck");
  var header = document.createElement("div");
  header.setAttribute("class", "u-a-r-comment-header");
  var criticA = document.createElement("a");
  criticA.setAttribute("class", "u-a-r-comment-critic-link");
  if (comm_criticId != "#") {
    criticA.setAttribute("href", "../user/?id="+ comm_criticId +"&section=about");
  }
  var criticWrp = document.createElement("div");
  criticWrp.setAttribute("class", "u-a-r-comment-critic-wrp");
  var imgWrp = document.createElement("div");
  imgWrp.setAttribute("class", "u-a-r-comment-critic-img-wrp");
  var img = document.createElement("img");
  if (acc_sts == "my-content") {
    img.setAttribute("class", "u-a-r-comment-critic-img my-u-a-r-comment-critic-img");
  } else {
    img.setAttribute("class", "u-a-r-comment-critic-img");
  }
  img.alt = "";
  img.src = "../"+ comm_img;
  var criticAbout = document.createElement("div");
  criticAbout.setAttribute("class", "u-a-r-comment-critic-about");
  var criticName = document.createElement("p");
  criticName.setAttribute("class", "u-a-r-comment-critic-name");
  criticName.innerHTML = comm_firstname +" "+ comm_lastname;
  var date = document.createElement("p");
  date.setAttribute("class", "u-a-r-comment-date");
  date.innerHTML = comm_date;
  var starsWrp = document.createElement("div");
  starsWrp.setAttribute("class", "u-a-r-comment-stars-wrp");
  var starsIcn = document.createElement("div");
  starsIcn.setAttribute("class", "u-a-r-comment-stars-icn");
  var starsNum = document.createElement("div");
  starsNum.setAttribute("class", "u-a-r-comment-stars-num");
  starsNum.innerHTML = comm_stars;
  var content = document.createElement("div");
  content.setAttribute("class", "u-a-r-comment-content-wrp");
  var txtSize = document.createElement("div");
  txtSize.setAttribute("class", "u-a-r-comment-txt-size");
  var txtWrp = document.createElement("div");
  txtWrp.setAttribute("class", "u-a-r-comment-txt-wrp");
  var txt = document.createElement("p");
  txt.setAttribute("class", "u-a-r-comment-txt");
  txt.setAttribute("id", "u-a-r-comment-txt-"+ commNum);
  txt.innerHTML = comm_txt;
  var btnWrp = document.createElement("div");
  btnWrp.setAttribute("class", "u-a-r-comment-more-btn-wrp");
  var btn = document.createElement("button");
  btn.setAttribute("class", "u-a-r-comment-more-btn");
  btn.setAttribute("onclick", "userCommModal('show', "+ commNum +")");
  btn.innerHTML = wrd_showMore;
  wrp.appendChild(blck);
  blck.appendChild(header);
  header.appendChild(criticA);
  criticA.appendChild(criticWrp);
  criticWrp.appendChild(imgWrp);
  imgWrp.appendChild(img);
  criticWrp.appendChild(criticAbout);
  criticAbout.appendChild(criticName);
  criticAbout.appendChild(date);
  if (comm_stars != 0) {
    header.appendChild(starsWrp);
    starsWrp.appendChild(starsIcn);
    starsWrp.appendChild(starsNum);
  }
  blck.appendChild(content);
  content.appendChild(txtSize);
  txtSize.appendChild(txtWrp);
  txtWrp.appendChild(txt);
  content.appendChild(btnWrp);
  btnWrp.appendChild(btn);
  document.getElementById("u-a-r-grid").appendChild(wrp);
  ++commNum;
}

function userCommentsShowMoreBtns() {
  for (var c = 0; c < document.getElementsByClassName("u-a-r-comment-critic-about").length; c++) {
    document.getElementsByClassName("u-a-r-comment-more-btn")[c].style.display = "";
    if (document.getElementsByClassName("u-a-r-comment-txt-size")[c].offsetHeight < document.getElementsByClassName("u-a-r-comment-txt")[c].offsetHeight) {
      document.getElementsByClassName("u-a-r-comment-more-btn")[c].style.display = "table";
    }
  }
}

function userCommModal(tsk, num) {
  if (tsk == "show") {
    document.getElementById("usr-comment-detail-txt").innerHTML = document.getElementById("u-a-r-comment-txt-"+ num).textContent.replace(/(\r\n|\r|\n)/g, '<br>');
    modCover("show", "modal-cover-comment-detail");
  } else {
    modCover("hide", "modal-cover-comment-detail");
  }
}
