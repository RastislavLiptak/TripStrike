var ncDefLatLng, ncMap, ncMapMarker, ncMapMarkerArr, ncMapInput, ncMapSearchBox, ncMapSearchPlaces, ncMapSearchInput, ncMapBounds;
function newCottageMap() {
  ncDefLatLng = {lat: 50.000, lng: 17.000};
  ncMap = new google.maps.Map(document.getElementById('n-c-map'), {
    zoom: 2.7,
    center: ncDefLatLng,
    mapTypeControl: false,
    streetViewControl: false,
    mapTypeId: 'terrain'
  });

  google.maps.event.addListener(ncMap, 'click', function(event) {
     ncPlaceMarker(event.latLng);
  });

  ncMapMarkerArr = [];
  function ncPlaceMarker(location) {
    ncMapSearchClose("close");
    ncClearMapMarkers();
    ncSaveMapLatLng(location.lat(), location.lng());
    ncMapMarker = new google.maps.Marker({
      position: location,
      map: ncMap
    });
    ncMapMarkerArr.push(ncMapMarker);
    google.maps.event.addListener(ncMapMarker, 'click', function() {
      ncClearMapMarkers();
    });
  }

  ncMapInput = document.getElementById("n-c-map-search-input");
  ncMapSearchBox = new google.maps.places.SearchBox(ncMapInput);
  ncMap.addListener("bounds_changed", function() {
    ncMapSearchBox.setBounds(ncMap.getBounds());
  });

  ncMapSearchBox.addListener("places_changed", function() {
    ncMapSearchPlaces = ncMapSearchBox.getPlaces();
    if (ncMapSearchPlaces.lenght === 0) {
      return;
    } else {
      ncMapBounds = new google.maps.LatLngBounds();
      ncMapSearchPlaces.forEach(function (p) {
        if (!p.geometry) {
          return;
        } else {
          ncPlaceMarker(p.geometry.location);
          if (p.geometry.viewport) {
            ncMapBounds.union(p.geometry.viewport);
          } else {
            ncMapBounds.extend(p.geometry.location);
          }
        }
      });
      ncMap.fitBounds(ncMapBounds);
    }
  });

  document.getElementById('n-c-map-search-modal-btn-search').onclick = function () {
    ncMapSearchInput = document.getElementById("n-c-map-search-input");
    if (ncMapSearchInput.value == "") {
      document.getElementById("n-c-map-search-input").focus();
    } else {
      google.maps.event.trigger(ncMapSearchInput, 'focus', {});
      google.maps.event.trigger(ncMapSearchInput, 'keydown', { keyCode: 13 });
      google.maps.event.trigger(this, 'focus', {});
    }
  };
}

function ncClearMapMarkers() {
  ncSaveMapLatLng(0, 0);
  for (var i = 0; i < ncMapMarkerArr.length; i++ ) {
    ncMapMarkerArr[i].setMap(null);
  }
  ncMapMarkerArr.length = 0;
}

var nCModBlckTime;
function nCModMapSearch(tsk) {
  modCover(tsk, 'modal-cover-n-c-map-search');
  clearTimeout(nCModBlckTime);
  if (tsk == "show") {
    document.getElementById("n-c-map-search-blck").style.display = "table";
    nCModBlckTime = setTimeout(function(){
      document.getElementById("n-c-map-search-blck").style.opacity = "1";
      document.getElementById("n-c-map-search-input").focus();
    }, 30);
  } else if (tsk == "hide") {
    document.getElementById("n-c-map-search-blck").style.opacity = "0";
    nCModBlckTime = setTimeout(function(){
      document.getElementById("n-c-map-search-blck").style.display = "none";
    }, 160);
  }
}

function ncMapSearchClose(tsk) {
  if (document.getElementById("n-c-map-search-input").value == "" || tsk == "close") {
    nCModMapSearch('hide');
  }
  document.getElementById("n-c-map-search-input").value = "";
}

function ncSaveMapLatLng(lat, lng) {
  document.getElementById("n-c-map-lat").innerHTML = lat;
  document.getElementById("n-c-map-lng").innerHTML = lng;
}

function ncMapReset() {
  ncClearMapMarkers();
}
