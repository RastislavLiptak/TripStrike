var myLatLng, map, marker, mapInpt, mapSearchBox, searchPlaces, mapBounds;
editorMapMarkerArr = [];
function newEditorMap(lat, lng) {
  if (document.getElementById('editor-content-map')) {
    if (lat != 0 && lng != 0) {
      myLatLng = {lat: lat, lng: lng};
      map = new google.maps.Map(document.getElementById('editor-content-map'), {
        zoom: 12,
        center: myLatLng,
        mapTypeControl: false,
        streetViewControl: false,
        mapTypeId: 'terrain'
      });
      marker = new google.maps.Marker({
        position: myLatLng,
        map: map
      });
      editorMapMarkerArr.push(marker);
      google.maps.event.addListener(marker, 'click', function(event) {
        clearEditorMapMarkers()
      });
    } else {
      myLatLng = {lat: 50.000, lng: 17.000};
      map = new google.maps.Map(document.getElementById('editor-content-map'), {
        zoom: 2.7,
        center: myLatLng,
        mapTypeControl: false,
        streetViewControl: false,
        mapTypeId: 'terrain'
      });
    }
    editorSaveMapLatLng(lat, lng);
    google.maps.event.addListener(map, 'click', function(event) {
      editorMapPlaceMarker(event.latLng);
    });

    function editorMapPlaceMarker(location) {
      editorModSearchClose("close", "map");
      clearEditorMapMarkers();
      editorSaveMapLatLng(location.lat(), location.lng());
      marker = new google.maps.Marker({
        position: location,
        map: map
      });
      editorMapMarkerArr.push(marker);
      google.maps.event.addListener(marker, 'click', function(event) {
        clearEditorMapMarkers()
      });
    }

    mapInpt = document.getElementById("modal-editor-content-map-search-inpt");
    mapSearchBox = new google.maps.places.SearchBox(mapInpt);
    map.addListener("bounds_changed", function() {
      mapSearchBox.setBounds(map.getBounds());
    });

    mapSearchBox.addListener("places_changed", function() {
      searchPlaces = mapSearchBox.getPlaces();
      if (searchPlaces.lenght === 0) {
        return;
      } else {
        mapBounds = new google.maps.LatLngBounds();
        searchPlaces.forEach(function (p) {
          if (!p.geometry) {
            return;
          } else {
            editorMapPlaceMarker(p.geometry.location);
            if (p.geometry.viewport) {
              mapBounds.union(p.geometry.viewport);
            } else {
              mapBounds.extend(p.geometry.location);
            }
          }
        });
        map.fitBounds(mapBounds);
      }
    });

    document.getElementById('modal-editor-content-map-btn-search').onclick = function () {
      var input = document.getElementById("modal-editor-content-map-search-inpt");
      if (input.value == "") {
        document.getElementById("modal-editor-content-map-search-inpt").focus();
      } else {
        google.maps.event.trigger(input, 'focus', {});
        google.maps.event.trigger(input, 'keydown', { keyCode: 13 });
        google.maps.event.trigger(this, 'focus', {});
      }
    };
  }
}

function clearEditorMapMarkers() {
  editorSaveMapLatLng(0, 0);
  for (var i = 0; i < editorMapMarkerArr.length; i++ ) {
    editorMapMarkerArr[i].setMap(null);
  }
  editorMapMarkerArr.length = 0;
}

var nCModBlckTime;
function eTModSearch(tsk, id) {
  modCover(tsk, 'modal-cover-editor-content-'+ id +'-search');
  clearTimeout(nCModBlckTime);
  if (tsk == "show") {
    document.getElementById("modal-editor-content-"+ id +"-blck").style.display = "table";
    nCModBlckTime = setTimeout(function(){
      document.getElementById("modal-editor-content-"+ id +"-blck").style.opacity = "1";
      document.getElementById("modal-editor-content-"+ id +"-search-inpt").focus();
    }, 30);
  } else if (tsk == "hide") {
    document.getElementById("modal-editor-content-"+ id +"-blck").style.opacity = "0";
    nCModBlckTime = setTimeout(function(){
      document.getElementById("modal-editor-content-"+ id +"-blck").style.display = "none";
    }, 160);
  }
}

function editorModSearchClose(tsk, id) {
  if (document.getElementById("modal-editor-content-"+ id +"-search-inpt").value == "" || tsk == "close") {
    eTModSearch('hide', id);
  }
  document.getElementById("modal-editor-content-"+ id +"-search-inpt").value = "";
}
