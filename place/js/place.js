var url_string = window.location.href;
var url = new URL(url_string);
var plc_id = url.searchParams.get("id");

window.addEventListener("load", function(){
  paymentBlckResize();
  getNearestAvailableDate();
  equipmentTxtResize();
});

window.addEventListener("scroll", function(){
  paymentBlckScroll();
});

window.addEventListener("resize", function(){
  paymentBlckScroll();
  paymentBlckResize();
  equipmentTxtResize();
});

var wrdShowAll;
function plcDictionary(showAll) {
  wrdShowAll = showAll;
}

var plcFullScreenReady = true;
var plcFullScreenT, plcFullScreenWrp;
function placeImgFullScreen(tsk, n) {
  if (plcFullScreenReady) {
    plcFullScreenWrp = document.getElementById("plc-full-screen-wrp");
    plcFullScreenReady = false;
    clearTimeout(plcFullScreenT);
    if (tsk == "show") {
      pageScrollBlock(true, "plc-full-screen-wrp");
      selectFullScreen(n);
      plcFullScreenWrp.style.display = "table";
      plcFullScreenT = setTimeout(function(){
        plcFullScreenWrp.style.opacity = "1";
        plcFullScreenReady = true;
      }, 10);
    } else {
      pageScrollBlock(false, "plc-full-screen-wrp");
      plcFullScreenWrp.style.opacity = "";
      plcFullScreenT = setTimeout(function(){
        plcFullScreenWrp.style.display = "";
        plcFullScreenReady = true;
      }, 160);
    }
  }
}

var allFSImgs = document.getElementsByClassName("plc-full-screen-img-wrp");
function selectFullScreen(n) {
  for (var i = 0; i < allFSImgs.length; i++) {
    allFSImgs[i].style.display = "";
  }
  imgFullScreenBtnsHandler(n);
  document.getElementById("plc-full-screen-img-wrp-" +n).style.display = "table";
  if (document.getElementById("plc-full-screen-img-src-" +n).innerHTML != "") {
    document.getElementById("plc-full-screen-img-" +n).src = document.getElementById("plc-full-screen-img-src-" +n).innerHTML;
    document.getElementById("plc-full-screen-img-loader-" +n).style.display = "none";
    document.getElementById("plc-full-screen-img-" +n).style.display = "table";
    document.getElementById("plc-full-screen-img-src-" +n).innerHTML = "";
  }
}

var beforeN, nextN;
function imgFullScreenBtnsHandler(n) {
  if (allFSImgs.length > 1) {
    beforeN = n -1;
    nextN = ++n;
    if (beforeN < 0) {
      beforeN = allFSImgs.length -1;
    }
    if (nextN >= allFSImgs.length) {
      nextN = 0;
    }
    document.getElementById("plc-full-screen-img-counter").innerHTML = n++ +" / "+ allFSImgs.length;
    document.getElementById("plc-full-screen-arrow-btn-left").value = beforeN;
    document.getElementById("plc-full-screen-arrow-btn-right").value = nextN;
    document.getElementById("plc-full-screen-mobile-arrows-btn-left").value = beforeN;
    document.getElementById("plc-full-screen-mobile-arrows-btn-right").value = nextN;
  } else {
    document.getElementById("plc-full-screen-img-counter").innerHTML = "1 / 1";
    document.getElementById("plc-full-screen-arrow-btn-left").style.display = "none";
    document.getElementById("plc-full-screen-arrow-btn-right").style.display = "none";
    document.getElementById("plc-full-screen-mobile-arrows-btn-left").style.display = "none";
    document.getElementById("plc-full-screen-mobile-arrows-btn-right").style.display = "none";
  }
}

function scrollRating() {
  document.getElementById("p-d-a-block-rating").scrollIntoView({behavior: 'smooth', block: 'center'});
}

var mustScroll, scrolled;
function paymentBlckScroll() {
  if (document.getElementById("plc-imgs-blck-size")) {
    mustScroll = document.getElementById("plc-imgs-blck-size").offsetHeight + 23; // <- this number is randomly selected
    scrolled = (window.pageYOffset || document.scrollTop) - (document.clientTop || 0);
    if (scrolled >= mustScroll) {
      document.getElementById("plc-details-payment-wrp").style.position = "fixed";
      document.getElementById("plc-details-payment-wrp").style.top = "70px";
      document.getElementById("plc-details-btns-wrp").style.opacity = "0";
      document.getElementById("plc-details-payment-wrp").style.zIndex = "1";
      document.getElementById("plc-details-payment-wrp").style.borderColor = "#333";
      document.getElementById("plc-details-payment-header-title").style.opacity = "1";
    } else {
      document.getElementById("plc-details-payment-wrp").style.position = "";
      document.getElementById("plc-details-payment-wrp").style.top = "";
      document.getElementById("plc-details-btns-wrp").style.opacity = "";
      document.getElementById("plc-details-payment-wrp").style.zIndex = "";
      document.getElementById("plc-details-payment-wrp").style.borderColor = "";
      document.getElementById("plc-details-payment-header-title").style.opacity = "";
    }
  }
}

var paymentBlckResizeRepeat;
function paymentBlckResize() {
  document.getElementById("plc-details-payment-wrp").style.width = document.getElementById("plc-details-tools-wrp").offsetWidth +"px";
}

var getNearestAvailableDateReady = true;
function getNearestAvailableDate() {
  if (getNearestAvailableDateReady && document.getElementById("plc-top-booking-info-wrp")) {
    getNearestAvailableDateReady = false;
    document.getElementById("plc-top-booking-nearest-available-txt").style.display = "";
    document.getElementById("plc-top-booking-multiple-nearest-available-txt").style.display = "";
    document.getElementById("plc-top-booking-nearest-all-available-since-txt").style.display = "";
    document.getElementById("plc-top-booking-nearest-available-date").style.display = "";
    document.getElementById("plc-top-booking-nearest-all-available-since-date").style.display = "";
    document.getElementById("plc-top-booking-nearest-unavailable-txt").style.display = "";
    document.getElementById("plc-top-booking-nearest-all-available-txt").style.display = "";
    document.getElementById("plc-top-booking-nearest-available-txt").style.marginRight = "";
    document.getElementById("plc-top-booking-multiple-nearest-available-txt").style.marginRight = "";
    document.getElementById("plc-top-booking-nearest-all-available-since-txt").style.marginRight = "";
    document.getElementById("plc-top-booking-nearest-available-date").style.marginRight = "";
    document.getElementById("plc-top-booking-nearest-all-available-since-date").style.marginRight = "";
    document.getElementById("plc-top-booking-nearest-unavailable-txt").style.marginRight = "";
    document.getElementById("plc-top-booking-nearest-all-available-txt").style.marginRight = "";
    document.getElementById("plc-top-booking-nearest-available-date").innerHTML = "";
    document.getElementById("plc-top-booking-nearest-all-available-since-date").innerHTML = "";
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        getNearestAvailableDateReady = true;
        if (testJSON(xhr.response)) {
          var json = JSON.parse(xhr.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "found") {
                document.getElementById("plc-top-booking-nearest-available-txt").style.marginRight = "4px";
                document.getElementById("plc-top-booking-multiple-nearest-available-txt").style.marginRight = "4px";
                document.getElementById("plc-top-booking-nearest-available-date").style.marginRight = "6px";
                if (json[key]["num"] > 1) {
                  document.getElementById("plc-top-booking-multiple-nearest-available-txt").style.display = "table";
                } else {
                  document.getElementById("plc-top-booking-nearest-available-txt").style.display = "table";
                }
                document.getElementById("plc-top-booking-nearest-available-date").style.display = "table";
                document.getElementById("plc-top-booking-nearest-available-date").innerHTML = json[key]["date"];
              } else if (json[key]["type"] == "all-available") {
                document.getElementById("plc-top-booking-nearest-all-available-txt").style.marginRight = "4px";
                document.getElementById("plc-top-booking-nearest-all-available-txt").style.display = "table";
              } else if (json[key]["type"] == "all-available-since") {
                document.getElementById("plc-top-booking-nearest-all-available-since-txt").style.marginRight = "4px";
                document.getElementById("plc-top-booking-nearest-all-available-since-date").style.marginRight = "4px";
                document.getElementById("plc-top-booking-nearest-all-available-since-txt").style.display = "table";
                document.getElementById("plc-top-booking-nearest-all-available-since-date").style.display = "table";
                document.getElementById("plc-top-booking-nearest-all-available-since-date").innerHTML = json[key]["since"];

              } else if (json[key]["type"] == "not-found") {
                document.getElementById("plc-top-booking-nearest-unavailable-txt").style.marginRight = "4px";
                document.getElementById("plc-top-booking-nearest-unavailable-txt").style.display = "table";
              } else if (json[key]["type"] == "error") {
                console.log(json[key]["error"]);
              }
            }
          }
        } else {
          console.log(xhr.response);
        }
      }
    }
    xhr.open("POST", "php-backend/get-nearest-available-date.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("plcId="+ plc_id);
  }
}

function descriptionShow() {
  document.getElementById("description-less").className = "description-hide";
  document.getElementById("description-more").className = "description-show";
}

var newW;
function equipmentTxtResize() {
  if (document.getElementById("plc-details-equi-list")) {
    //                                                                 margin  padding  icon txt-padding
    newW = document.getElementById("plc-details-equi-list").offsetWidth - 12 - (2 * 10) - 25 - 6;
  }
}

var myLatLng, map, marker;
function plcMap(lat, lng) {
  if (document.getElementById('plc-map')) {
    myLatLng = {lat: lat, lng: lng};
    map = new google.maps.Map(document.getElementById('plc-map'), {
      zoom: 17,
      center: myLatLng,
      mapId: '7c2affb743f66675',
      zoomControl: true,
      mapTypeControl: false,
      streetViewControl: false,
      fullscreenControl: false,
      scaleControl: false,
      mapTypeId: 'roadmap'
    });

    marker = new google.maps.Marker({
      position: myLatLng,
      map: map
    });

    var geocoder = new google.maps.Geocoder;
    geocoder.geocode({'location': myLatLng}, function(results, status) {
      if (status === 'OK') {
        if (results[0]) {
          document.getElementById("plc-map-error").style.display = "";
          document.getElementById("plc-map-address").style.display = "flex";
          document.getElementById("plc-map-address").innerHTML = results[0].formatted_address;
          document.getElementById("plc-map-address").href = "https://www.google.com/maps/search/?api=1&query="+ lat +","+ lng;
          document.getElementById("plc-map-error").innerHTML = "";
        } else {
          document.getElementById("plc-map-error").style.display = "";
          document.getElementById("plc-map-address").style.display = "";
          document.getElementById("plc-map-address").innerHTML = "";
          document.getElementById("plc-map-address").href = "";
          document.getElementById("plc-map-error").innerHTML = "";
        }
      } else {
        document.getElementById("plc-map-error").style.display = "table";
        document.getElementById("plc-map-address").style.display = "";
        document.getElementById("plc-map-address").href = "";
        document.getElementById("plc-map-error").innerHTML = "Geocoder failed due to: " + status;
        document.getElementById("plc-map-address").innerHTML = "";
      }
    });
  }
}

var lastId = "";
var arrCommIds = [];
var commLoadReady = true;
function plcCommentLoader() {
  if (document.getElementById("p-d-a-comments-wrp") && commLoadReady) {
    commLoadReady = false;
    if (typeof arrCommIds !== 'undefined' && arrCommIds.length > 0) {
      lastId = arrCommIds[arrCommIds.length -1];
    } else {
      lastId = "";
    }
    plcCommErrorManager(0);
    plcCommMore("load");
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        if (testJSON(xhr.response)) {
          var json = JSON.parse(xhr.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "comm") {
                arrCommIds.push(json[key]["id"]);
                plcCommentRender(json[key]["id"], json[key]["usr_id"], json[key]["usr_img"], json[key]["firstname"], json[key]["lastname"], json[key]["acc_sts"], json[key]["ago"], json[key]["stars"], json[key]["comment_sts"], json[key]["comment_short"], json[key]["comment_long"]);
              } else if (json[key]["type"] == "load-next-sts") {
                if (json[key]["all"] == 0) {
                  plcCommErrorManager(1);
                  plcCommMore("hide");
                  imgLinksListReady = "no-more";
                } else if (json[key]["progress"] == 0 || json[key]["remain"] == 0) {
                  plcCommMore("hide");
                  imgLinksListReady = "no-more";
                } else {
                  plcCommMore("txt");
                }
              } else {
                plcCommentError(json[key]["error"]);
                plcCommMore("hide");
              }
            }
          }
        } else {
          plcCommMore("hide");
          plcCommentError("json-error");
        }
        if (commLoadReady != "no-more") {
          commLoadReady = true;
        }
      }
    }
    xhr.open("POST", "../place/php-backend/place-comments.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("plcId="+ plc_id +"&lastId="+ lastId);
  }
}

function plcCommMore(tsk) {
  if (tsk == "hide") {
    document.getElementById("p-d-a-btn").style.display = "none";
  } else if (tsk == "show") {
    document.getElementById("p-d-a-btn").style.display = "";
  } else if (tsk == "txt") {
    plcCommMore("show");
    document.getElementById("p-d-a-btn-txt-wrp").style.opacity = "1";
    document.getElementById("p-d-a-btn-txt-wrp").style.cursor = "pointer";
    document.getElementById("p-d-a-btn-loader").style.zIndex = "-1";
    document.getElementById("p-d-a-btn-loader").style.opacity = "0";
  } else if (tsk == "load") {
    plcCommMore("show");
    document.getElementById("p-d-a-btn-txt-wrp").style.opacity = "";
    document.getElementById("p-d-a-btn-txt-wrp").style.cursor = "";
    document.getElementById("p-d-a-btn-loader").style.zIndex = "";
    document.getElementById("p-d-a-btn-loader").style.opacity = "";
  }
}

var aHref;
function plcCommentRender(rating_id, critic_id, critic_img, critic_firstname, critic_lastname, acc_sts, ago, stars, commentSts, commentShort, commentLong) {
  aHref = "../user/?id="+ critic_id +"&section=about"
  var wrp = document.createElement("div");
  if (commentShort.replace(/\n/g, "").replace(/\s/g, "") != "") {
    wrp.setAttribute("class", "plc-comm-wrp plc-comm-wrp-with-txt");
  } else {
    wrp.setAttribute("class", "plc-comm-wrp plc-comm-wrp-without-txt");
  }
  var imgWrp = document.createElement("div");
  imgWrp.setAttribute("class", "plc-comm-img-wrp");
  var aImg = document.createElement("a");
  aImg.setAttribute("class", "plc-comm-a");
  aImg.href = aHref;
  var img = document.createElement("img");
  if (acc_sts == "my-content") {
    img.setAttribute("class", "plc-comm-img my-plc-comm-img");
  } else {
    img.setAttribute("class", "plc-comm-img");
  }
  img.src = "../"+ critic_img;
  img.alt = "";
  var txtWrp = document.createElement("div");
  txtWrp.setAttribute("class", "plc-comm-about-wrp");
  var detailsWrp = document.createElement("div");
  detailsWrp.setAttribute("class", "plc-comm-details-wrp");
  var detailsLeft = document.createElement("div");
  detailsLeft.setAttribute("class", "plc-comm-details");
  var detailsRight = document.createElement("div");
  detailsRight.setAttribute("class", "plc-comm-details");
  var aUsr = document.createElement("a");
  aUsr.setAttribute("class", "plc-comm-a");
  aUsr.href = aHref;
  var username = document.createElement("p");
  if (acc_sts == "my-content") {
    username.setAttribute("class", "plc-comm-user my-plc-comm-user");
  } else {
    username.setAttribute("class", "plc-comm-user");
  }
  username.innerHTML = critic_firstname +" "+ critic_lastname;
  var agoTxt = document.createElement("p");
  agoTxt.setAttribute("class", "plc-comm-ago");
  agoTxt.innerHTML = ago;
  var starIcn = document.createElement("img");
  starIcn.setAttribute("class", "plc-comm-star-icn");
  starIcn.src = "../uni/icons/star.svg";
  starIcn.alt = "";
  var starTxt = document.createElement("p");
  starTxt.setAttribute("class", "plc-comm-star-txt");
  starTxt.innerHTML = stars;
  var commWrp = document.createElement("div");
  commWrp.setAttribute("class", "plc-comm-txt-wrp");
  var comm = document.createElement("div");
  comm.setAttribute("class", "plc-comm-txt");
  comm.setAttribute("id", "plc-comm-txt-short-"+ rating_id);
  if (commentSts == "fix") {
    comm.innerHTML = commentLong;
  } else {
    comm.innerHTML = commentShort;
  }
  var commL = document.createElement("div");
  commL.setAttribute("class", "plc-comm-txt plc-comm-txt-long");
  commL.setAttribute("id", "plc-comm-txt-long-"+ rating_id);
  commL.innerHTML = commentLong;
  var commBtn = document.createElement("button");
  commBtn.setAttribute("class", "plc-comm-btn");
  commBtn.setAttribute("onclick", "plcCommShortLong('"+ rating_id +"')");
  commBtn.innerHTML = wrdShowAll;
  wrp.appendChild(imgWrp);
  if (acc_sts != "unknown") {
    imgWrp.appendChild(aImg);
    aImg.appendChild(img);
  } else {
    imgWrp.appendChild(img);
  }
  wrp.appendChild(txtWrp);
  txtWrp.appendChild(detailsWrp);
  detailsWrp.appendChild(detailsLeft);
  if (acc_sts != "unknown") {
    detailsLeft.appendChild(aUsr);
    aUsr.appendChild(username);
  } else {
    detailsLeft.appendChild(username);
  }
  detailsLeft.appendChild(agoTxt);
  detailsWrp.appendChild(detailsRight);
  detailsRight.appendChild(starIcn);
  detailsRight.appendChild(starTxt);
  txtWrp.appendChild(commWrp);
  commWrp.appendChild(comm);
  if (commentSts == "short/long") {
    comm.appendChild(commBtn);
    commWrp.appendChild(commL);
  }
  document.getElementById("p-d-a-comments-list").appendChild(wrp);
}

function plcCommentError(code) {
  if (code == "json-error" || code == "last-rate-n-found") {
    plcCommErrorManager(2);
  } else if (code == "plc-id-n-found" || code == "id-n-found") {
    plcCommErrorManager(3);
  }
}

function plcCommentLoaderReset() {
  lastId = "";
  arrCommIds = [];
  commLoadReady = true;
  document.getElementById("p-d-a-comments-list").innerHTML = "";
  plcCommentLoader();
}

function plcCommErrorManager(num) {
  if (num == 0) {
    for (var i = 0; i < document.getElementsByClassName("p-d-a-error-wrp").length; i++) {
      document.getElementsByClassName("p-d-a-error-wrp")[i].style.display = "";
    }
  } else {
    plcCommErrorManager(0);
    document.getElementById("p-d-a-error-wrp-"+ num).style.display = "flex";
  }
}

function plcCommShortLong(id) {
  document.getElementById("plc-comm-txt-short-"+ id).style.display = "none";
  document.getElementById("plc-comm-txt-long-"+ id).style.display = "block";
}
