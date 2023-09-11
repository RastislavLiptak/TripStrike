function editorImagesModalToggle(tsk) {
  if (document.getElementById("modal-cover-plc-image-editor")) {
    if (tsk == "show") {
      modCover('show', 'modal-cover-plc-image-editor');
      loadEditImages();
    } else {
      modCover('hide', 'modal-cover-plc-image-editor');
      loadEditImagesCancel();
      addEditImagesCancel();
    }
  }
}

function editorImagesContentManager(cont) {
  document.getElementById("plc-image-editor-grid-error").innerHTML = "";
  if (cont == "grid") {
    document.getElementById("plc-image-editor-grid-error-wrp").style.display = "flex";
    document.getElementById("plc-image-editor-grid").style.display = "grid";
    document.getElementById("plc-image-editor-error-wrp").style.display = "";
    document.getElementById("plc-image-editor-loader-wrp").style.display = "";
  } else if (cont == "error") {
    document.getElementById("plc-image-editor-grid-error-wrp").style.display = "";
    document.getElementById("plc-image-editor-grid").style.display = "";
    document.getElementById("plc-image-editor-error-wrp").style.display = "flex";
    document.getElementById("plc-image-editor-loader-wrp").style.display = "";
  } else if (cont == "loader") {
    document.getElementById("plc-image-editor-grid-error-wrp").style.display = "";
    document.getElementById("plc-image-editor-grid").style.display = "";
    document.getElementById("plc-image-editor-error-wrp").style.display = "";
    document.getElementById("plc-image-editor-loader-wrp").style.display = "flex";
  }
}

var xhr, numOfEditImages;
function loadEditImages() {
  loadEditImagesCancel();
  editorImagesContentManager("loader");
  removeEditImages();
  numOfEditImages = 0;
  xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      if (testJSON(xhr.response)) {
        var json = JSON.parse(xhr.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "image") {
              ++numOfEditImages;
              editorImagesContentManager("grid");
              if (!document.getElementById("plc-image-edit-wrp-add-images")) {
                renderEditAddImages();
              }
              renderEditImages(json[key]["name"], json[key]["src"], json[key]["sts"]);
            } else if (json[key]["type"] == "error") {
              editorImagesContentManager("error");
              document.getElementById("plc-image-editor-error-code").innerHTML = json[key]["error"];
            }
          }
        }
        if (numOfEditImages == 0) {
          editorImagesContentManager("grid");
          renderEditAddImages();
        }
      } else {
        editorImagesContentManager("error");
        document.getElementById("plc-image-editor-error-code").innerHTML = xhr.response;
      }
    }
  }
  xhr.open("POST", "php-backend/get-images-list.php");
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("urlId="+ plc_id);
}

function loadEditImagesCancel() {
  if (xhr != null) {
    xhr.abort();
  }
}

function removeEditImages() {
  var plcImageEditWrps = document.getElementsByClassName("plc-image-edit-wrp");
  while(plcImageEditWrps[0]) {
    plcImageEditWrps[0].parentNode.removeChild(plcImageEditWrps[0]);
  }
}

function renderEditAddImages() {
  var wrp = document.createElement("div");
  wrp.setAttribute("class", "plc-image-edit-wrp");
  wrp.setAttribute("id", "plc-image-edit-wrp-add-images");
  var size = document.createElement("div");
  size.setAttribute("class", "plc-image-edit-size");
  var plusIcon = document.createElement("div");
  plusIcon.setAttribute("id", "plc-image-edit-add-images-plus-icon");
  var fileInput = document.createElement("input");
  fileInput.setAttribute("type", "file");
  fileInput.setAttribute("multiple", "true");
  fileInput.setAttribute("onchange", "addEditImages(this)");
  fileInput.setAttribute("id", "plc-image-edit-add-images-file");
  var fileLabel = document.createElement("label");
  fileLabel.setAttribute("for", "plc-image-edit-add-images-file");
  fileLabel.setAttribute("id", "plc-image-edit-add-images-file-active-area");
  wrp.appendChild(size);
  size.appendChild(plusIcon);
  size.appendChild(fileInput);
  size.appendChild(fileLabel);
  document.getElementById("plc-image-editor-grid").appendChild(wrp);
}

function renderEditImages(name, src, sts) {
  var wrp = document.createElement("div");
  wrp.setAttribute("class", "plc-image-edit-wrp");
  wrp.setAttribute("id", "plc-image-edit-wrp-"+ name);
  if (sts == "main") {
    wrp.classList.add("plc-image-edit-wrp-prim");
  }
  var size = document.createElement("div");
  size.setAttribute("class", "plc-image-edit-size");
  var img = document.createElement("img");
  img.setAttribute("class", "plc-image-edit-img");
  img.alt = "";
  img.src = "../"+ src;
  var loaderWrp = document.createElement("div");
  loaderWrp.setAttribute("class", "plc-image-edit-loader-wrp");
  loaderWrp.setAttribute("id", "plc-image-edit-loader-wrp-"+ name);
  var loader = document.createElement("div");
  loader.setAttribute("class", "plc-image-edit-loader");
  var toolsWrp = document.createElement("div");
  toolsWrp.setAttribute("class", "plc-image-edit-tools-wrp");
  var deleteBtn = document.createElement("button");
  deleteBtn.setAttribute("type", "button");
  deleteBtn.setAttribute("value", name);
  deleteBtn.setAttribute("onclick", "editorImagesDeleteImg('"+ name +"')")
  deleteBtn.setAttribute("id", "plc-image-edit-delete-"+ name);
  deleteBtn.setAttribute("class", "plc-image-edit-delete");
  if (sts == "main") {
    deleteBtn.classList.add("plc-image-edit-delete-selected");
  }
  var checkWrp = document.createElement("div");
  checkWrp.setAttribute("class", "plc-image-edit-checkbox-wrp");
  var checkLoader = document.createElement("div");
  checkLoader.setAttribute("class", "plc-image-edit-checkbox-loader");
  checkLoader.setAttribute("id", "plc-image-edit-checkbox-loader-"+ name);
  var checkLabel = document.createElement("label");
  checkLabel.setAttribute("class", "plc-image-edit-checkbox-label");
  checkLabel.setAttribute("id", "plc-image-edit-checkbox-label-"+ name);
  var checkInput = document.createElement("input");
  checkInput.setAttribute("type", "radio");
  checkInput.setAttribute("name", "plc-image-edit-checkbox");
  checkInput.setAttribute("class", "plc-image-edit-checkbox-input");
  checkInput.setAttribute("id", "plc-image-edit-checkbox-input-"+ name);
  checkInput.setAttribute("onclick", "editorImagesChangeMainImg(event, '"+ name +"')");
  if (sts == "main") {
    checkInput.setAttribute("checked", "checked");
    checkInput.setAttribute("value", "checked");
  } else {
    checkInput.setAttribute("value", "");
  }
  var checkSpan = document.createElement("span");
  checkSpan.setAttribute("class", "plc-image-edit-checkbox-checkmark");
  wrp.appendChild(size);
  size.appendChild(img);
  size.appendChild(loaderWrp);
  loaderWrp.appendChild(loader);
  size.appendChild(toolsWrp);
  toolsWrp.appendChild(deleteBtn);
  toolsWrp.appendChild(checkWrp);
  checkWrp.appendChild(checkLabel);
  checkWrp.appendChild(checkLoader);
  checkLabel.appendChild(checkInput);
  checkLabel.appendChild(checkSpan);
  document.getElementById("plc-image-editor-grid").appendChild(wrp);
}

var addEditImagesReady = true;
var reloadImageList = true;
var xhrAddImages;
function addEditImages(inpt) {
  if (addEditImagesReady && inpt.files.length != 0) {
    addEditImagesReady = false;
    reloadImageList = true;
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    addEditImagesIconManager("load");
    document.getElementById("plc-image-editor-grid-error").innerHTML = "";
    var formData = new FormData();
    formData.append("urlId", plc_id);
    for (var f = 0; f < inpt.files.length; f++) {
      formData.append("file[]", inpt.files[f]);
    }
    xhrAddImages = new XMLHttpRequest();
    xhrAddImages.onreadystatechange = function() {
      if (xhrAddImages.readyState == 4 && xhrAddImages.status == 200) {
        addEditImagesReady = true;
        inpt.value = "";
        window.onbeforeunload = null;
        if (testJSON(xhrAddImages.response)) {
          var json = JSON.parse(xhrAddImages.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "image") {
                editorImagesOutputManager(json[key]["type"], json[key]["src"], json[key]["status"], json[key]["num"]);
              } else if (json[key]["type"] == "no-images") {
                reloadImageList = false;
                loadEditImages();
                editorImagesOutputManager(json[key]["type"], "", "main", "0");
              } else {
                reloadImageList = false;
                addEditImagesIconManager("def");
                document.getElementById("plc-image-editor-grid-error").innerHTML = json[key]["error"];
              }
            }
          }
          if (reloadImageList) {
            loadEditImages();
          }
        } else {
          addEditImagesIconManager("def");
          document.getElementById("plc-image-editor-grid-error").innerHTML = xhrAddImages.response;
        }
      }
    }
    xhrAddImages.open("POST", "php-backend/add-place-img.php");
    xhrAddImages.send(formData);
  }
}

function addEditImagesCancel() {
  addEditImagesReady = true;
  window.onbeforeunload = null;
  if (xhrAddImages != null) {
    xhrAddImages.abort();
  }
}

function addEditImagesIconManager(tsk) {
  if (tsk == "def") {
    document.getElementById("plc-image-edit-add-images-plus-icon").style.backgroundImage = "";
    document.getElementById("plc-image-edit-add-images-plus-icon").style.backgroundSize = "";
  } else if (tsk == "load") {
    document.getElementById("plc-image-edit-add-images-plus-icon").style.backgroundImage = "url('../uni/gifs/loader-tail.svg')";
    document.getElementById("plc-image-edit-add-images-plus-icon").style.backgroundSize = "";
  }
}

var mainImgChangeReady = true;
var deleteImgReady = true;
var orgMainName;
function editorImagesChangeMainImg(event, name) {
  event.preventDefault();
  if (document.getElementById("plc-image-edit-checkbox-input-"+ name).value != "checked") {
    if (mainImgChangeReady && deleteImgReady) {
      mainImgChangeReady = false;
      window.onbeforeunload = function(ev) {
        ev.returnValue = "Your changes may not be saved.";
      };
      orgMainName = document.getElementsByClassName("plc-image-edit-delete-selected")[0].value;
      editorImagesMainImgRadios("disable");
      document.getElementById("plc-image-edit-wrp-"+ name).classList.add("plc-image-edit-wrp-prim");
      editorImagesChangeMainImgLoaderHanler("loader", name);
      editorImagesChangeMainImgLoaderHanler("loader", orgMainName);
      document.getElementById("plc-image-editor-grid-error").innerHTML = "";
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
          mainImgChangeReady = true;
          editorImagesMainImgRadios("enable");
          window.onbeforeunload = null;
          if (testJSON(xhr.response)) {
            var json = JSON.parse(xhr.response);
            for (var key in json) {
              if (json.hasOwnProperty(key)) {
                if (json[key]["type"] == "image") {
                  document.getElementById("plc-image-edit-checkbox-input-"+ orgMainName).value = "";
                  document.getElementById("plc-image-edit-checkbox-input-"+ orgMainName).checked = false;
                  document.getElementById("plc-image-edit-checkbox-input-"+ name).value = "checked";
                  document.getElementById("plc-image-edit-checkbox-input-"+ name).checked = true;
                  editorImagesChangeMainImgLoaderHanler("checkbox", name);
                  editorImagesChangeMainImgLoaderHanler("checkbox", orgMainName);
                  document.getElementById("plc-image-edit-delete-"+ name).classList.add("plc-image-edit-delete-selected");
                  document.getElementById("plc-image-edit-delete-"+ orgMainName).classList.remove("plc-image-edit-delete-selected");
                  document.getElementById("plc-image-edit-wrp-"+ orgMainName).classList.remove("plc-image-edit-wrp-prim");
                  editorImagesOutputManager(json[key]["type"], json[key]["src"], json[key]["status"], json[key]["num"]);
                } else if (json[key]["type"] == "no-images") {
                  loadEditImages();
                  editorImagesOutputManager(json[key]["type"], "", "main", "0");
                } else if (json[key]["type"] == "error") {
                  document.getElementById("plc-image-editor-grid-error").innerHTML = json[key]["error"];
                }
              }
            }
          } else {
            document.getElementById("plc-image-editor-grid-error").innerHTML = xhr.response;
          }
        }
      }
      xhr.open("POST", "php-backend/change-place-main-img.php");
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.send("urlId="+ plc_id +"&name="+ name);
    }
  }
}

function editorImagesDeleteImg(name) {
  if (deleteImgReady && mainImgChangeReady) {
    deleteImgReady = false;
    reloadImageList = true;
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    editorImagesMainImgRadios("disable");
    editorImagesDeleteLoaderHandler("loader", name);
    document.getElementById("plc-image-editor-grid-error").innerHTML = "";
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        deleteImgReady = true;
        editorImagesMainImgRadios("enable");
        window.onbeforeunload = null;
        if (testJSON(xhr.response)) {
          var json = JSON.parse(xhr.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "image") {
                editorImagesOutputManager(json[key]["type"], json[key]["src"], json[key]["status"], json[key]["num"]);
              } else if (json[key]["type"] == "no-images") {
                reloadImageList = false;
                loadEditImages();
                editorImagesOutputManager(json[key]["type"], "", "main", "0");
              } else if (json[key]["type"] == "error") {
                reloadImageList = false;
                editorImagesDeleteLoaderHandler("def", name);
                document.getElementById("plc-image-editor-grid-error").innerHTML = json[key]["error"];
              }
            }
          }
          if (reloadImageList) {
            loadEditImages();
          }
        } else {
          document.getElementById("plc-image-editor-grid-error").innerHTML = xhr.response;
        }
      }
    }
    xhr.open("POST", "php-backend/delete-place-img.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("urlId="+ plc_id +"&name="+ name);
  }
}

function editorImagesDeleteLoaderHandler(tsk, name) {
  if (tsk == "def") {
    document.getElementById("plc-image-edit-loader-wrp-"+ name).style.display = "";
  } else if (tsk == "loader") {
    document.getElementById("plc-image-edit-loader-wrp-"+ name).style.display = "flex";
  }
}

function editorImagesMainImgRadios(tsk) {
  var radios = document.getElementsByName("plc-image-edit-checkbox");
  for (var i = 0, r=radios, l=r.length; i < l;  i++){
    if (tsk == "disable") {
      r[i].disabled = true;
    } else {
      r[i].disabled = false;
    }
  }
}

function editorImagesChangeMainImgLoaderHanler(tsk, name) {
  if (tsk == "checkbox") {
    document.getElementById("plc-image-edit-checkbox-loader-"+ name).style.display = "";
    document.getElementById("plc-image-edit-checkbox-loader-"+ name).style.opacity = "";
    document.getElementById("plc-image-edit-checkbox-label-"+ name).style.opacity = "";
  } else if (tsk == "loader") {
    document.getElementById("plc-image-edit-checkbox-loader-"+ name).style.display = "block";
    document.getElementById("plc-image-edit-checkbox-loader-"+ name).style.opacity = "1";
    document.getElementById("plc-image-edit-checkbox-label-"+ name).style.opacity = "0";
  }
}

function editorImagesOutputManager(type, src, sts, num) {
  if (num == "0") {
    document.getElementById("editor-images-list").innerHTML = "";
  }
  if (type == "image") {
    var thumb = document.createElement("div");
    if (sts == "main") {
      thumb.setAttribute("class", "editor-image-prim");
    } else {
      thumb.setAttribute("class", "editor-image-sec");
    }
    thumb.style.backgroundImage = "url(../"+ src +")";
  } else {
    var wrp = document.createElement("div");
    wrp.setAttribute("id", "editor-image-def-wrp");
    var icon = document.createElement("div");
    icon.setAttribute("id", "editor-image-def-icon");
    wrp.appendChild(icon);
  }
  document.getElementById("editor-images-list").appendChild(thumb);
}
