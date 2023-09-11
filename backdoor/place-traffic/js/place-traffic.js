window.addEventListener("load", function(){
  stripedChartCreateNew('place-traffic', []);
  placeTrafficLoadData();
});

var xhrPlaceTrafficLoadData, placeTrafficFilterType, placeTrafficFilterPeriod;
function placeTrafficLoadData() {
  placeTrafficLoadDataCancel();
  stripedChartLoader("place-traffic");
  placeTrafficStatistics("-", "-", "-", "-");
  document.getElementById("striped-chart-text-date").innerHTML = "-";
  placeTrafficFilterType = document.getElementById("striped-chart-filter-type-place-traffic").value;
  placeTrafficFilterPeriod = document.getElementById("striped-chart-filter-period-place-traffic").value;
  xhrPlaceTrafficLoadData = new XMLHttpRequest();
  xhrPlaceTrafficLoadData.onreadystatechange = function() {
    if (xhrPlaceTrafficLoadData.readyState == 4 && xhrPlaceTrafficLoadData.status == 200) {
      if (testJSON(xhrPlaceTrafficLoadData.response)) {
        var json = JSON.parse(xhrPlaceTrafficLoadData.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "data") {
              stripedChartContent("place-traffic", json[key]["data"]);
            } else if (json[key]["type"] == "statistics") {
              placeTrafficStatistics(
                json[key]["average"],
                json[key]["median"],
                json[key]["minimum"],
                json[key]["maximum"]
              );
            } else if (json[key]["type"] == "dates") {
              document.getElementById("striped-chart-text-date").innerHTML = json[key]["data"];
            } else if (json[key]["type"] == "error") {
              stripedChartError("place-traffic", json[key]["error"]);
            }
          }
        }
      } else {
        stripedChartError("place-traffic", xhrPlaceTrafficLoadData.response);
      }
    }
  }
  xhrPlaceTrafficLoadData.open("POST", "php-backend/get-place-traffic-data.php");
  xhrPlaceTrafficLoadData.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrPlaceTrafficLoadData.send("type="+ placeTrafficFilterType +"&period="+ placeTrafficFilterPeriod);
}

function placeTrafficLoadDataCancel() {
  if (xhrPlaceTrafficLoadData != null) {
    xhrPlaceTrafficLoadData.abort();
  }
}

function placeTrafficStatistics(average, median, minimum, maximum) {
  if (average != "-") {
    average = Math.round(average * 100) / 100;
  }
  if (median != "-") {
    median = Math.round(median * 100) / 100;
  }
  if (minimum != "-") {
    minimum = Math.round(minimum * 100) / 100;
  }
  if (maximum != "-") {
    maximum = Math.round(maximum * 100) / 100;
  }
  document.getElementById("place-traffic-details-txt-average").innerHTML = average;
  document.getElementById("place-traffic-details-txt-median").innerHTML = median;
  document.getElementById("place-traffic-details-txt-minimum").innerHTML = minimum;
  document.getElementById("place-traffic-details-txt-maximum").innerHTML = maximum;
}
