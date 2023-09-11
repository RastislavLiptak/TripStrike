window.addEventListener("load", function(){
  stripedChartCreateNew('browsers', []);
  browsersLoadData();
});

var xhrbrowsersLoadData, browsersFilterType, browsersFilterPeriod;
function browsersLoadData() {
  browsersLoadDataCancel();
  stripedChartLoader("browsers");
  browsersStatistics("-", "-", "-", "-");
  document.getElementById("striped-chart-text-date").innerHTML = "-";
  browsersFilterType = document.getElementById("striped-chart-filter-type-browsers").value;
  browsersFilterPeriod = document.getElementById("striped-chart-filter-period-browsers").value;
  xhrbrowsersLoadData = new XMLHttpRequest();
  xhrbrowsersLoadData.onreadystatechange = function() {
    if (xhrbrowsersLoadData.readyState == 4 && xhrbrowsersLoadData.status == 200) {
      if (testJSON(xhrbrowsersLoadData.response)) {
        var json = JSON.parse(xhrbrowsersLoadData.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "data") {
              stripedChartContent("browsers", json[key]["data"]);
            } else if (json[key]["type"] == "statistics") {
              browsersStatistics(
                json[key]["average"],
                json[key]["median"],
                json[key]["minimum"],
                json[key]["maximum"]
              );
            } else if (json[key]["type"] == "dates") {
              document.getElementById("striped-chart-text-date").innerHTML = json[key]["data"];
            } else if (json[key]["type"] == "error") {
              stripedChartError("browsers", json[key]["error"]);
            }
          }
        }
      } else {
        stripedChartError("browsers", xhrbrowsersLoadData.response);
      }
    }
  }
  xhrbrowsersLoadData.open("POST", "php-backend/get-browsers-data.php");
  xhrbrowsersLoadData.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrbrowsersLoadData.send("type="+ browsersFilterType +"&period="+ browsersFilterPeriod);
}

function browsersLoadDataCancel() {
  if (xhrbrowsersLoadData != null) {
    xhrbrowsersLoadData.abort();
  }
}

function browsersStatistics(average, median, minimum, maximum) {
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
  document.getElementById("browsers-details-txt-average").innerHTML = average;
  document.getElementById("browsers-details-txt-median").innerHTML = median;
  document.getElementById("browsers-details-txt-minimum").innerHTML = minimum;
  document.getElementById("browsers-details-txt-maximum").innerHTML = maximum;
}
