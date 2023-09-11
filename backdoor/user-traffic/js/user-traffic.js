window.addEventListener("load", function(){
  barChartCreateNew('user-traffic', [], "num");
  userTrafficLoadData();
});

var xhrUserTrafficLoadData, userTrafficFilterType, userTrafficFilterPeriod;
function userTrafficLoadData() {
  userTrafficLoadDataCancel();
  barChartSetContent("user-traffic", "loader", "num");
  userTrafficStatistics("-", "-", "-", "-", "-", "-", "-", "-");
  userTrafficFilterType = document.getElementById("bar-chart-filter-type").value;
  userTrafficFilterPeriod = document.getElementById("bar-chart-filter-period").value;
  xhrUserTrafficLoadData = new XMLHttpRequest();
  xhrUserTrafficLoadData.onreadystatechange = function() {
    if (xhrUserTrafficLoadData.readyState == 4 && xhrUserTrafficLoadData.status == 200) {
      if (testJSON(xhrUserTrafficLoadData.response)) {
        var json = JSON.parse(xhrUserTrafficLoadData.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "data") {
              barChartUpdate("user-traffic", json[key]["data"], "num");
            } else if (json[key]["type"] == "statistics") {
              userTrafficStatistics(
                json[key]["average"],
                json[key]["median"],
                json[key]["coefficient-of-variation"],
                json[key]["growth-rate"],
                json[key]["minimum"],
                json[key]["maximum"],
                json[key]["dispersion"],
                json[key]["standard-deviation"]
              );
            } else if (json[key]["type"] == "error") {
              barChartError("user-traffic", json[key]["error"], "num");
            }
          }
        }
      } else {
        barChartError("user-traffic", xhrUserTrafficLoadData.response, "num");
      }
    }
  }
  xhrUserTrafficLoadData.open("POST", "php-backend/get-user-traffic-data.php");
  xhrUserTrafficLoadData.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrUserTrafficLoadData.send("type="+ userTrafficFilterType +"&period="+ userTrafficFilterPeriod);
}

function userTrafficLoadDataCancel() {
  if (xhrUserTrafficLoadData != null) {
    xhrUserTrafficLoadData.abort();
  }
}

function userTrafficStatistics(average, median, coefficientOfVariation, growthRate, minimum, maximum, dispersion, standardDeviation) {
  if (growthRate != "-") {
    growthRate = Math.round(growthRate * 100) / 100;
  }
  if (average != "-") {
    average = Math.round(average * 100) / 100;
  }
  if (median != "-") {
    median = Math.round(median * 100) / 100;
  }
  if (coefficientOfVariation != "-") {
    coefficientOfVariation = Math.round(coefficientOfVariation * 100) / 100;
  }
  if (minimum != "-") {
    minimum = Math.round(minimum * 100) / 100;
  }
  if (maximum != "-") {
    maximum = Math.round(maximum * 100) / 100;
  }
  if (dispersion != "-") {
    dispersion = Math.round(dispersion * 100) / 100;
  }
  if (standardDeviation != "-") {
    standardDeviation = Math.round(standardDeviation * 100) / 100;
  }
  document.getElementById("user-traffic-details-txt-growth-rate").innerHTML = growthRate +"%";
  document.getElementById("user-traffic-details-txt-average").innerHTML = average;
  document.getElementById("user-traffic-details-txt-median").innerHTML = median;
  document.getElementById("user-traffic-details-txt-coefficient-of-variation").innerHTML = coefficientOfVariation +"%";
  document.getElementById("user-traffic-details-txt-minimum").innerHTML = minimum;
  document.getElementById("user-traffic-details-txt-maximum").innerHTML = maximum;
  document.getElementById("user-traffic-details-txt-dispersion").innerHTML = dispersion;
  document.getElementById("user-traffic-details-txt-standard-deviation").innerHTML = standardDeviation;
}
