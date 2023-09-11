window.addEventListener('load', function() {
  loadMapApi();
});

var api_sts = "none";
function loadMapApi() {
  if (document.getElementById("n-c-map") || document.getElementById("plc-map")) {
    api_sts = "loading";
    var key = "";
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = "https://maps.googleapis.com/maps/api/js?key="+ key +"&callback=loadMapApiDone&libraries=places";
    document.body.appendChild(script);
  }
}

function loadMapApiDone() {
  api_sts = "done";
  checkMapSchedule();
}

var mapLine = [];
var line_map, line_lat, line_lng;
function checkMapSchedule() {
  if (mapLine.length > 0) {
    line_map = mapLine[0].map;
    line_lat = mapLine[0].lat;
    line_lng = mapLine[0].lng
    removeMapSchedule();
    mapManager(line_map, line_lat, line_lng);
  }
}

var mapObj;
function addMapSchedule(map, lat, lng) {
  mapObj = {map: map, lat: lat, lng: lng};
  mapLine.push(mapObj);
}

function removeMapSchedule() {
  mapLine.shift();
}

function mapManager(map, lat, lng) {
  if (api_sts == "done") {
    if (map == "new-cottage") {
      newCottageMap();
    } else if (map == "place") {
      plcMap(lat, lng);
    } else if (map == "editor") {
      newEditorMap(lat, lng);
    }
    checkMapSchedule();
  } else {
    addMapSchedule(map, lat, lng);
    if (api_sts != "loading") {
      loadMapApi();
    }
  }
}
