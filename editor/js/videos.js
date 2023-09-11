function editorContentVideoSearchFocus(sts) {
  if (sts == "in") {
    if (!document.getElementById("editor-content-videos-search-size").classList.contains("editor-content-videos-search-size-focused")) {
      document.getElementById("editor-content-videos-search-size").classList.add("editor-content-videos-search-size-focused");
    }
  } else {
    if (document.getElementById("editor-content-input-style-videos-search").value == "") {
      document.getElementById("editor-content-videos-search-size").classList.remove("editor-content-videos-search-size-focused");
    }
  }
}

window.addEventListener('load', function() {
  document.getElementById("editor-content-input-style-videos-search").addEventListener("keyup", function(event) {
    if (event.keyCode === 13) {
      event.preventDefault();
      editorVideoSearch();
    }
  });
}, false);

function editorVideoSearch() {
  if (document.getElementById("editor-content-input-style-videos-search").value != "") {
    var vidID = YouTubeGetID(document.getElementById("editor-content-input-style-videos-search").value);
    document.getElementById("editor-content-input-style-videos-search").value = "";
    document.getElementById("editor-content-input-style-videos-search").blur();
    if (!document.getElementById("editor-content-video-blck-"+ vidID)) {
      editorVideoRender(vidID);
      var xhrEdYtVidData = new XMLHttpRequest();
      xhrEdYtVidData.onreadystatechange = function() {
        if (xhrEdYtVidData.readyState == 4 && xhrEdYtVidData.status == 200) {
          if (testJSON(xhrEdYtVidData.response)) {
            var json = JSON.parse(xhrEdYtVidData.response);
            for (var key in json) {
              if (json.hasOwnProperty(key)) {
                if (json[key]["type"] == "data") {
                  editorVideoData(json[key]["id"], json[key]["title"], json[key]["thumbnail-medium"]);
                } else if (json[key]["type"] == "error") {
                  document.getElementById("editor-content-video-blck-"+ vidID).parentNode.removeChild(document.getElementById("editor-content-video-blck-"+ vidID));
                  alert(json[key]["error"]);
                }
              }
            }
          } else {
            document.getElementById("editor-content-video-blck-"+ vidID).parentNode.removeChild(document.getElementById("editor-content-video-blck-"+ vidID));
            alert(xhrEdYtVidData.response);
          }
        }
      }
      xhrEdYtVidData.open("POST", "../uni/code/php-backend/youtube-video-data.php");
      xhrEdYtVidData.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhrEdYtVidData.send("video="+ vidID);
    }
  }
}

function editorVideoRender(vidID) {
  var blck = document.createElement("div");
  blck.setAttribute("class", "editor-content-video-blck");
  blck.setAttribute("id", "editor-content-video-blck-"+ vidID);
  var aboutWrp = document.createElement("div");
  aboutWrp.setAttribute("class", "editor-content-video-about-wrp");
  var aboutID = document.createElement("p");
  aboutID.setAttribute("class", "editor-content-video-about-txt");
  aboutID.innerHTML = vidID;
  var size = document.createElement("div");
  size.setAttribute("class", "editor-content-video-size");
  var layout = document.createElement("div");
  layout.setAttribute("class", "editor-content-video-layout");
  var imgWrp = document.createElement("div");
  imgWrp.setAttribute("class", "editor-content-video-img-wrp");
  imgWrp.setAttribute("id", "editor-content-video-img-wrp-"+ vidID);
  var deleteBtn = document.createElement("button");
  deleteBtn.setAttribute("type", "button");
  deleteBtn.setAttribute("class", "editor-content-video-delete-btn");
  deleteBtn.setAttribute("id", "editor-content-video-delete-btn-"+ vidID);
  deleteBtn.setAttribute("onclick", "editorVideosDelete('"+ vidID +"')");
  var loader = document.createElement("div");
  loader.setAttribute("class", "editor-content-video-loader");
  loader.setAttribute("id", "editor-content-video-loader-"+ vidID);
  var detailsWrp = document.createElement("div");
  detailsWrp.setAttribute("class", "editor-content-video-details-wrp");
  detailsWrp.setAttribute("id", "editor-content-video-details-wrp-"+ vidID);
  var titleWrp = document.createElement("div");
  titleWrp.setAttribute("class", "editor-content-video-details-title-wrp");
  var titleTxt = document.createElement("p");
  titleTxt.setAttribute("class", "editor-content-video-details-title-txt");
  titleTxt.setAttribute("id", "editor-content-video-details-title-txt-"+ vidID);
  titleTxt.innerHTML = "...";
  var logoWrp = document.createElement("div");
  logoWrp.setAttribute("class", "editor-content-video-logo-wrp");
  logoWrp.setAttribute("id", "editor-content-video-logo-wrp-"+ vidID);
  var logoIcn = document.createElement("img");
  logoIcn.setAttribute("class", "editor-content-video-logo");
  logoIcn.setAttribute("alt", "YouTube logo");
  logoIcn.setAttribute("src", "../uni/icons/social/youtube.svg");
  blck.appendChild(aboutWrp);
  aboutWrp.appendChild(aboutID);
  blck.appendChild(size);
  size.appendChild(layout);
  layout.appendChild(imgWrp);
  imgWrp.appendChild(deleteBtn);
  imgWrp.appendChild(loader);
  layout.appendChild(detailsWrp);
  detailsWrp.appendChild(titleWrp);
  titleWrp.appendChild(titleTxt);
  detailsWrp.appendChild(logoWrp);
  logoWrp.appendChild(logoIcn);
  document.getElementById("editor-content-no-video-wrp").style.display = "";
  document.getElementById("editor-content-videos-list-wrp").prepend(blck);
}

function editorVideoData(vidID, vidTitle, vidThumb) {
  var img = document.createElement("img");
  img.setAttribute("class", "editor-content-video-img");
  img.setAttribute("alt", vidTitle +" thumbnail");
  img.setAttribute("src", vidThumb);
  document.getElementById("editor-content-video-img-wrp-"+ vidID).appendChild(img);
  document.getElementById("editor-content-video-details-title-txt-"+ vidID).innerHTML = vidTitle;
  document.getElementById("editor-content-video-logo-wrp-"+ vidID).style.display = "table";
  document.getElementById("editor-content-video-delete-btn-"+ vidID).style.display = "table";
  document.getElementById("editor-content-video-loader-"+ vidID).style.display = "none";
}

function editorVideosDelete(vidId) {
  document.getElementById("editor-content-video-blck-"+ vidId).parentNode.removeChild(document.getElementById("editor-content-video-blck-"+ vidId));
  if (document.getElementsByClassName("editor-content-video-blck").length == 0) {
    document.getElementById("editor-content-no-video-wrp").style.display = "table";
  }
}
