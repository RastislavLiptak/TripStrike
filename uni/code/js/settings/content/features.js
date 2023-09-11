var saveFeaturesSettingsReady = true;
var bActivateFeature, saveFeaturesTimer;
function saveFeaturesSettings() {
  if (saveFeaturesSettingsReady) {
    clearTimeout(saveFeaturesTimer);
    saveFeaturesSettingsReady = false;
    bActivateFeature = document.getElementById("settings-input-activate-feature-code").value;
    document.getElementById("settings-error-txt-features").innerHTML = "";
    document.getElementById("features-accepted-error-wrp").style.display = "";
    document.getElementById("features-accepted-error").innerHTML = "";
    setSaveBtn("load", "settings-save-btn-features");
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        saveFeaturesSettingsReady = true;
        if (testJSON(xhr.response)) {
          var json = JSON.parse(xhr.response);
          for (var key in json) {
            if (json.hasOwnProperty(key)) {
              if (json[key]["type"] == "done") {
                setSaveBtn("success", "settings-save-btn-features");
                document.getElementById("settings-input-activate-feature-code").value = "";
                if (json[key]["error"] != "") {
                  document.getElementById("features-accepted-error-wrp").style.display = "table";
                  document.getElementById("features-accepted-error").innerHTML = json[key]["error"];
                }
                modCover('show', 'modal-cover-features-accepted');
                saveFeaturesTimer = setTimeout(function(){
                  setSaveBtn("def", "settings-save-btn-features");
                }, 1000);
              } else {
                setSaveBtn("def", "settings-save-btn-features");
                document.getElementById("settings-content-scroll").scrollTop = 0;
                document.getElementById("settings-error-txt-features").innerHTML = json[key]["error"];
              }
            }
          }
        } else {
          setSaveBtn("def", "settings-save-btn-features");
          document.getElementById("settings-content-scroll").scrollTop = 0;
          document.getElementById("settings-error-txt-features").innerHTML = xhr.response;
        }
      }
    }
    xhr.open("POST", "../uni/code/php-backend/settings/features.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("activateFeature="+ bActivateFeature);
  }
}
