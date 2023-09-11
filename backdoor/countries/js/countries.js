window.addEventListener("load", function(){
  stripedChartCreateNew('countries', []);
  countriesLoadData();
});

var xhrcountriesLoadData, countriesFilterType, countriesFilterPeriod;
function countriesLoadData() {
  countriesLoadDataCancel();
  stripedChartLoader("countries");
  countriesStatistics("-", "-", "-", "-");
  document.getElementById("striped-chart-text-date").innerHTML = "-";
  countriesFilterType = document.getElementById("striped-chart-filter-type-countries").value;
  countriesFilterPeriod = document.getElementById("striped-chart-filter-period-countries").value;
  xhrcountriesLoadData = new XMLHttpRequest();
  xhrcountriesLoadData.onreadystatechange = function() {
    if (xhrcountriesLoadData.readyState == 4 && xhrcountriesLoadData.status == 200) {
      if (testJSON(xhrcountriesLoadData.response)) {
        var json = JSON.parse(xhrcountriesLoadData.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "data") {
              stripedChartContent("countries", json[key]["data"]);
            } else if (json[key]["type"] == "statistics") {
              countriesStatistics(
                json[key]["average"],
                json[key]["median"],
                json[key]["minimum"],
                json[key]["maximum"]
              );
            } else if (json[key]["type"] == "dates") {
              document.getElementById("striped-chart-text-date").innerHTML = json[key]["data"];
            } else if (json[key]["type"] == "error") {
              stripedChartError("countries", json[key]["error"]);
            }
          }
        }
      } else {
        stripedChartError("countries", xhrcountriesLoadData.response);
      }
    }
  }
  xhrcountriesLoadData.open("POST", "php-backend/get-countries-data.php");
  xhrcountriesLoadData.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrcountriesLoadData.send("type="+ countriesFilterType +"&period="+ countriesFilterPeriod);
}

function countriesLoadDataCancel() {
  if (xhrcountriesLoadData != null) {
    xhrcountriesLoadData.abort();
  }
}

function countriesStatistics(average, median, minimum, maximum) {
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
  document.getElementById("countries-details-txt-average").innerHTML = average;
  document.getElementById("countries-details-txt-median").innerHTML = median;
  document.getElementById("countries-details-txt-minimum").innerHTML = minimum;
  document.getElementById("countries-details-txt-maximum").innerHTML = maximum;
}
