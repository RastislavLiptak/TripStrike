window.addEventListener("load", function(){
  barChartCreateNew('spend-time', [], "time");
  spendTimeLoadData();
});

var xhrSpendTimeLoadData, spendTimeFilterType, spendTimeFilterPeriod, spendTimeFilterData;
function spendTimeLoadData() {
  spendTimeLoadDataCancel();
  barChartSetContent("spend-time", "loader", "time");
  spendTimeStatistics("-", "-", "-", "-", "-", "-", "-", "-");
  spendTimeFilterType = document.getElementById("bar-chart-filter-type").value;
  spendTimeFilterPeriod = document.getElementById("bar-chart-filter-period").value;
  spendTimeFilterData = document.getElementById("bar-chart-filter-data").value;
  xhrSpendTimeLoadData = new XMLHttpRequest();
  xhrSpendTimeLoadData.onreadystatechange = function() {
    if (xhrSpendTimeLoadData.readyState == 4 && xhrSpendTimeLoadData.status == 200) {
      if (testJSON(xhrSpendTimeLoadData.response)) {
        var json = JSON.parse(xhrSpendTimeLoadData.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["type"] == "data") {
              barChartUpdate("spend-time", json[key]["data"], "time");
            } else if (json[key]["type"] == "statistics") {
              spendTimeStatistics(
                json[key]["average"],
                json[key]["median"],
                json[key]["coefficient-of-variation"],
                json[key]["growth-rate"],
                json[key]["minimum"],
                json[key]["maximum"]
              );
            } else if (json[key]["type"] == "error") {
              barChartError("spend-time", json[key]["error"], "time");
            }
          }
        }
      } else {
        barChartError("spend-time", xhrSpendTimeLoadData.response, "time");
      }
    }
  }
  xhrSpendTimeLoadData.open("POST", "php-backend/get-spend-time-data.php");
  xhrSpendTimeLoadData.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhrSpendTimeLoadData.send("type="+ spendTimeFilterType +"&period="+ spendTimeFilterPeriod +"&data="+ spendTimeFilterData);
}

function spendTimeLoadDataCancel() {
  if (xhrSpendTimeLoadData != null) {
    xhrSpendTimeLoadData.abort();
  }
}

function spendTimeStatistics(average, median, coefficientOfVariation, growthRate, minimum, maximum) {
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
  document.getElementById("spend-time-details-txt-growth-rate").innerHTML = growthRate +"%";
  document.getElementById("spend-time-details-txt-average").innerHTML = secondsToTime(average, "full");
  document.getElementById("spend-time-details-txt-median").innerHTML = secondsToTime(median, "full");
  document.getElementById("spend-time-details-txt-coefficient-of-variation").innerHTML = coefficientOfVariation +"%";
  document.getElementById("spend-time-details-txt-minimum").innerHTML = secondsToTime(minimum, "full");
  document.getElementById("spend-time-details-txt-maximum").innerHTML = secondsToTime(maximum, "full");
}
