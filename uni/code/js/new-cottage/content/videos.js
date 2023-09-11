function ncVideosSearch() {
  if (document.getElementById("n-c-videos-searchbar-input").value != "") {
    document.getElementById("n-c-error-txt-videos").innerHTML = "";
    var vidID = YouTubeGetID(document.getElementById("n-c-videos-searchbar-input").value);
    document.getElementById("n-c-videos-searchbar-input").value = "";
    if (!document.getElementById("n-c-videos-blck-"+ vidID)) {
      ncVideoRender(vidID);
      var xhrYtVidData = new XMLHttpRequest();
      xhrYtVidData.onreadystatechange = function() {
        if (xhrYtVidData.readyState == 4 && xhrYtVidData.status == 200) {
          if (testJSON(xhrYtVidData.response)) {
            var json = JSON.parse(xhrYtVidData.response);
            for (var key in json) {
              if (json.hasOwnProperty(key)) {
                if (json[key]["type"] == "data") {
                  ncVideoData(json[key]["id"], json[key]["title"], json[key]["thumbnail-default"]);
                } else if (json[key]["type"] == "error") {
                  document.getElementById("n-c-error-txt-videos").innerHTML = json[key]["error"];
                  document.getElementById("n-c-videos-blck-"+ json[key]["id"]).parentNode.removeChild(document.getElementById("n-c-videos-blck-"+ json[key]["id"]));
                }
              }
            }
          } else {
            document.getElementById("n-c-error-txt-videos").innerHTML = xhrYtVidData.response;
            document.getElementById("n-c-videos-blck-"+ vidID).parentNode.removeChild(document.getElementById("n-c-videos-blck-"+ vidID));
          }
        }
      }
      xhrYtVidData.open("POST", "../uni/code/php-backend/youtube-video-data.php");
      xhrYtVidData.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhrYtVidData.send("video="+ vidID);
    }
  }
}

function ncVideoRender(vidID) {
  var blck = document.createElement("div");
  blck.setAttribute("class", "n-c-videos-blck");
  blck.setAttribute("id", "n-c-videos-blck-"+ vidID);
  var aboutWrp = document.createElement("div");
  aboutWrp.setAttribute("class", "n-c-videos-about-wrp");
  var aboutID = document.createElement("p");
  aboutID.setAttribute("class", "n-c-videos-about-txt");
  aboutID.setAttribute("id", "n-c-videos-about-id-"+ vidID);
  aboutID.innerHTML = vidID;
  var deleteBtn = document.createElement("button");
  deleteBtn.setAttribute("type", "button");
  deleteBtn.setAttribute("class", "n-c-videos-delete-btn");
  deleteBtn.setAttribute("id", "n-c-videos-delete-btn-"+ vidID);
  deleteBtn.setAttribute("onclick", "ncVideoDelete('"+ vidID +"')");
  var layout = document.createElement("div");
  layout.setAttribute("class", "n-c-videos-layout");
  var imgWrp = document.createElement("div");
  imgWrp.setAttribute("class", "n-c-videos-img-wrp");
  imgWrp.setAttribute("id", "n-c-videos-img-wrp-"+ vidID);
  var loader = document.createElement("div");
  loader.setAttribute("class", "n-c-videos-loader");
  loader.setAttribute("id", "n-c-videos-loader-"+ vidID);
  var txtWrp = document.createElement("div");
  txtWrp.setAttribute("class", "n-c-videos-txt-wrp");
  var titleWrp = document.createElement("div");
  titleWrp.setAttribute("class", "n-c-videos-title-wrp");
  var titleTxt = document.createElement("p");
  titleTxt.setAttribute("class", "n-c-videos-title-txt");
  titleTxt.setAttribute("id", "n-c-videos-title-txt-"+ vidID);
  titleTxt.innerHTML = "...";
  var logoWrp = document.createElement("div");
  logoWrp.setAttribute("class", "n-c-videos-logo-wrp");
  logoWrp.setAttribute("id", "n-c-videos-logo-wrp-"+ vidID);
  var logoIcn = document.createElement("img");
  logoIcn.setAttribute("class", "n-c-videos-logo-icn");
  logoIcn.classList.add("n-c-videos-logo-icn-youtube");
  logoIcn.setAttribute("alt", "YouTube logo");
  logoIcn.setAttribute("src", "../uni/icons/social/youtube.svg");
  blck.appendChild(aboutWrp);
  aboutWrp.appendChild(aboutID);
  blck.appendChild(deleteBtn);
  blck.appendChild(layout);
  layout.appendChild(imgWrp);
  imgWrp.appendChild(loader);
  layout.appendChild(txtWrp);
  txtWrp.appendChild(titleWrp);
  titleWrp.appendChild(titleTxt);
  txtWrp.appendChild(logoWrp);
  logoWrp.appendChild(logoIcn);
  document.getElementById("n-c-videos-grid").prepend(blck);
}

function ncVideoData(vidID, vidTitle, vidThumb) {
  var img = document.createElement("img");
  img.setAttribute("class", "n-c-videos-img");
  img.setAttribute("alt", vidTitle +"thumbnail");
  img.setAttribute("src", vidThumb);
  document.getElementById("n-c-videos-img-wrp-"+ vidID).appendChild(img);
  document.getElementById("n-c-videos-title-txt-"+ vidID).innerHTML = vidTitle;
  document.getElementById("n-c-videos-logo-wrp-"+ vidID).style.display = "table";
  document.getElementById("n-c-videos-delete-btn-"+ vidID).style.display = "table";
  document.getElementById("n-c-videos-loader-"+ vidID).style.display = "none";
}

function ncVideoDelete(vidID) {
  document.getElementById("n-c-videos-blck-"+ vidID).parentNode.removeChild(document.getElementById("n-c-videos-blck-"+ vidID));
}

var ncVideosNumOfBlcks;
function ncVideosReset() {
  document.getElementById("n-c-videos-searchbar-input").value = "";
  ncVideosNumOfBlcks = document.getElementsByClassName("n-c-videos-blck").length;
  for (var v = 0; v < ncVideosNumOfBlcks; v++) {
    document.getElementsByClassName("n-c-videos-blck")[0].parentNode.removeChild(document.getElementsByClassName("n-c-videos-blck")[0]);
  }
}
