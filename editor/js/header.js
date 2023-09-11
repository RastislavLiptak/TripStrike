function editorPlaceDeleteModal(tsk) {
  if (document.getElementById("modal-cover-editor-delete")) {
    modCover(tsk, 'modal-cover-editor-delete');
  }
}

var plcDeleteSts = true;
function placeDelete() {
  if (plcDeleteSts) {
    plcDeleteSts = false;
    window.onbeforeunload = function(event) {
      event.returnValue = "Your changes may not be saved.";
    };
    document.getElementById("e-delete-error-txt").innerHTML = "";
    editorBtnHandler("load", "e-delete-btn");
    var xhrPlcDelete = new XMLHttpRequest();
    xhrPlcDelete.onreadystatechange = function() {
      if (xhrPlcDelete.readyState == 4 && xhrPlcDelete.status == 200) {
        window.onbeforeunload = null;
        plcDeleteSts = true;
        if (testJSON(xhrPlcDelete.response)) {
          var json = JSON.parse(xhrPlcDelete.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                editorBtnHandler("success", "e-delete-btn");
                location.reload();
              } else if (json[key]["type"] == "error") {
                document.getElementById("e-delete-error-txt").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          document.getElementById("e-delete-error-txt").innerHTML = xhrPlcDelete.response;
        }
      }
    }
    xhrPlcDelete.open("POST", "php-backend/place-delete-manager.php");
    xhrPlcDelete.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhrPlcDelete.send("urlId="+ plc_id);
  }
}
