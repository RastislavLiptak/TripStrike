function barChartCreateNew(id, dataObject, type) {
  barChartRenderBasics(id);
  if (Object.keys(dataObject).length == 0) {
    barChartSetContent(id, "loader", type);
  } else {
    barChartUpdate(id, dataObject, type);
  }
}

function barChartRenderBasics(id) {
  document.getElementById("bar-chart-wrp-" +id).innerHTML = "";
  var chartLayout = document.createElement("div");
  chartLayout.setAttribute("class", "bar-chart-layout");
  chartLayout.setAttribute("id", "bar-chart-layout-" +id);
  var chartAboutWrp = document.createElement("div");
  chartAboutWrp.setAttribute("class", "bar-chart-about-wrp");
  var chartAboutID = document.createElement("p");
  chartAboutID.setAttribute("class", "bar-chart-about-id");
  chartAboutID.innerHTML = id;
  var leftLegendSize = document.createElement("div");
  leftLegendSize.setAttribute("class", "bar-chart-legend-left-size");
  var leftLegendLayout = document.createElement("div");
  leftLegendLayout.setAttribute("class", "bar-chart-legend-left-layout");
  leftLegendLayout.setAttribute("id", "bar-chart-legend-left-layout-" +id);
  var leftLegendTop = document.createElement("p");
  leftLegendTop.setAttribute("class", "bar-chart-legend-left-text");
  leftLegendTop.innerHTML = "-";
  var leftLegendBottom = document.createElement("p");
  leftLegendBottom.setAttribute("class", "bar-chart-legend-left-text");
  leftLegendBottom.innerHTML = 0;
  var chartSize = document.createElement("div");
  chartSize.setAttribute("class", "bar-chart-size");
  var markLinesWrp = document.createElement("div");
  markLinesWrp.setAttribute("class", "bar-chart-mark-lines-wrp");
  markLinesWrp.setAttribute("id", "bar-chart-mark-lines-wrp-" +id);
  var markLine1 = document.createElement("div");
  markLine1.setAttribute("class", "bar-chart-mark-lines-bars");
  var markLine2 = document.createElement("div");
  markLine2.setAttribute("class", "bar-chart-mark-lines-bars");
  var markLine3 = document.createElement("div");
  markLine3.setAttribute("class", "bar-chart-mark-lines-bars");
  var markLine4 = document.createElement("div");
  markLine4.setAttribute("class", "bar-chart-mark-lines-bars");
  var markLine5 = document.createElement("div");
  markLine5.setAttribute("class", "bar-chart-mark-lines-bars");
  var dataLayout = document.createElement("div");
  dataLayout.setAttribute("class", "bar-chart-data-layout");
  dataLayout.setAttribute("id", "bar-chart-data-layout-" +id);
  var loaderWrp = document.createElement("div");
  loaderWrp.setAttribute("class", "bar-chart-loader-wrp");
  loaderWrp.setAttribute("id", "bar-chart-loader-wrp-" +id);
  var loaderImg = document.createElement("img");
  loaderImg.setAttribute("class", "bar-chart-loader-img");
  loaderImg.setAttribute("src", "../../uni/gifs/loader-tail.svg");
  loaderImg.setAttribute("alt", "loader");
  var errorWrp = document.createElement("div");
  errorWrp.setAttribute("class", "bar-chart-error-wrp");
  errorWrp.setAttribute("id", "bar-chart-error-wrp-" +id);
  var errorSize = document.createElement("div");
  errorSize.setAttribute("class", "bar-chart-error-size");
  var errorTxt = document.createElement("p");
  errorTxt.setAttribute("class", "bar-chart-error-txt");
  errorTxt.setAttribute("id", "bar-chart-error-txt-" +id);
  var rightLegendSize = document.createElement("div");
  rightLegendSize.setAttribute("class", "bar-chart-legend-right-size");
  var rightLegendLayout = document.createElement("div");
  rightLegendLayout.setAttribute("class", "bar-chart-legend-right-layout");
  rightLegendLayout.setAttribute("id", "bar-chart-legend-right-layout-" +id);
  chartLayout.appendChild(chartAboutWrp);
  chartAboutWrp.appendChild(chartAboutID);
  chartLayout.appendChild(leftLegendSize);
  leftLegendSize.appendChild(leftLegendLayout);
  leftLegendLayout.appendChild(leftLegendTop);
  leftLegendLayout.appendChild(leftLegendBottom);
  chartLayout.appendChild(chartSize);
  chartSize.appendChild(markLinesWrp);
  markLinesWrp.appendChild(markLine1);
  markLinesWrp.appendChild(markLine2);
  markLinesWrp.appendChild(markLine3);
  markLinesWrp.appendChild(markLine4);
  markLinesWrp.appendChild(markLine5);
  chartSize.appendChild(dataLayout);
  chartSize.appendChild(loaderWrp);
  loaderWrp.appendChild(loaderImg);
  chartSize.appendChild(loaderWrp);
  chartSize.appendChild(errorWrp);
  errorWrp.appendChild(errorSize);
  errorSize.appendChild(errorTxt);
  chartLayout.appendChild(rightLegendSize);
  rightLegendSize.appendChild(rightLegendLayout);
  document.getElementById("bar-chart-wrp-" +id).appendChild(chartLayout);
}

function barChartSetContent(id, set, type) {
  if (document.getElementById("bar-chart-layout-" +id)) {
    document.getElementById("bar-chart-data-layout-" +id).style.display = "";
    document.getElementById("bar-chart-loader-wrp-" +id).style.display = "";
    document.getElementById("bar-chart-error-wrp-" +id).style.display = "";
    if (set == "loader") {
      document.getElementById("bar-chart-loader-wrp-" +id).style.display = "flex";
    } else if (set == "error") {
      document.getElementById("bar-chart-error-wrp-" +id).style.display = "block";
    } else if (set == "content") {
      document.getElementById("bar-chart-data-layout-" +id).style.display = "flex";
    }
  }
}

function barChartError(id, errorTxt, type) {
  if (document.getElementById("bar-chart-layout-" +id)) {
    document.getElementById("bar-chart-error-txt-" +id).innerHTML = errorTxt;
    barChartSetContent(id, "error", type);
  } else {
    barChartCreateNew(id, [], type);
    barChartError(id, errorTxt, type);
  }
}

var rangeValues = [];
var listOfValues = [];
var listOfValueTitles = [];
var topRangeValue = 0;
function barChartAnalyzeData(get, dataObject) {
  rangeValues = [];
  listOfValues = [];
  var listOfValueTitles = [];
  for (var dOA = 0; dOA < Object.keys(dataObject).length; dOA++) {
    for (var dOAV = 0; dOAV < dataObject[dOA]['values'].length; dOAV++) {
      rangeValues.push(dataObject[dOA]['values'][dOAV]['value']);
      if (!listOfValues.includes(dataObject[dOA]['values'][dOAV]['status'])) {
        listOfValues.push(dataObject[dOA]['values'][dOAV]['status']);
        listOfValueTitles.push(dataObject[dOA]['values'][dOAV]['title']);
      }
    }
  }
  if (get == "range-top") {
    topRangeValue = Math.ceil(Math.max(...rangeValues) + 0.01 * Math.max(...rangeValues));
    return barChartCalcNumOfLines("verify-top-range", topRangeValue);
  } else if (get == "values") {
    return listOfValues;
  } else if (get == "value-titles") {
    return listOfValueTitles;
  }
}

var newTopRange, numOfLinesInChart;
function barChartCalcNumOfLines(get, topRangeValue) {
  if (topRangeValue != 0) {
    dataFound = false;
    while (!dataFound) {
      if ((topRangeValue / 10) % 1 == 0) {
        newTopRange = topRangeValue;
        numOfLinesInChart = 9;
        dataFound = true;
      } else if ((topRangeValue / 9) % 1 == 0) {
        newTopRange = topRangeValue;
        numOfLinesInChart = 8;
        dataFound = true;
      } else if ((topRangeValue / 8) % 1 == 0) {
        newTopRange = topRangeValue;
        numOfLinesInChart = 7;
        dataFound = true;
      } else if ((topRangeValue / 7) % 1 == 0) {
        newTopRange = topRangeValue;
        numOfLinesInChart = 6;
        dataFound = true;
      } else if ((topRangeValue / 6) % 1 == 0) {
        newTopRange = topRangeValue;
        numOfLinesInChart = 5;
        dataFound = true;
      } else if ((topRangeValue / 5) % 1 == 0) {
        newTopRange = topRangeValue;
        numOfLinesInChart = 4;
        dataFound = true;
      } else if ((topRangeValue / 4) % 1 == 0) {
        newTopRange = topRangeValue;
        numOfLinesInChart = 3;
        dataFound = true;
      } else if ((topRangeValue / 3) % 1 == 0) {
        newTopRange = topRangeValue;
        numOfLinesInChart = 2;
        dataFound = true;
      } else if ((topRangeValue / 2) % 1 == 0) {
        newTopRange = topRangeValue;
        numOfLinesInChart = 1;
        dataFound = true;
      }
      if (!dataFound) {
        ++topRangeValue;
      }
    }
  } else {
    newTopRange = topRangeValue;
    numOfLinesInChart = 0;
  }
  if (get == "num-of-lines") {
    return numOfLinesInChart;
  } else if (get == "verify-top-range") {
    return newTopRange;
  }
}

var barChartColorsList = [
  "#00c0ff",
  "#005288",
  "#a92215",
  "#f1ab17",
  "#8d863f",
  "#EECAB4",
  "#4d6270"
];
function barChartGetColor(i) {
  if (i < barChartColorsList.length) {
    return barChartColorsList[i];
  } else {
    if (i % 2 == 0) {
      return "#2E383E";
    } else {
      return "#b7b7b7";
    }
  }
}

var chartContentResize = new ResizeObserver(function(barCharts) {
  for (var bCh = 0; bCh < document.getElementsByClassName("bar-chart-about-id").length; bCh++) {
    barChartResizeHandler(document.getElementsByClassName("bar-chart-about-id")[bCh].innerHTML);
  }
});

var barMeasuredSts, barMeasuredOutput, barMeasuringColumn, barColumnWidth, hiddenBarColumns, newNumOfBars, numOfBarsInOneSection, finalNumOfSectionsReady, finalNumOfSections, numOfHiddenSections;
function barChartResizeHandler(id) {
  barMeasuredSts = false;
  barMeasuringColumn = 0;
  barColumnWidth = 0;
  hiddenBarColumns = 0;
  finalNumOfSections = 0;
  while (!barMeasuredSts && document.getElementsByClassName("bar-chart-data-column-" +id).length > barMeasuringColumn) {
    if (document.getElementsByClassName("bar-chart-data-column-" +id)[barMeasuringColumn].offsetWidth > 0) {
      barMeasuredSts = true;
      barColumnWidth = document.getElementsByClassName("bar-chart-data-column-" +id)[barMeasuringColumn].offsetWidth;
    } else {
      ++hiddenBarColumns;
    }
    ++barMeasuringColumn;
  }
  numOfBarsInOneSection = document.getElementsByClassName("bar-chart-data-column-" +id).length / document.getElementsByClassName("bar-chart-data-block-" +id).length;
  if (numOfBarsInOneSection != 1) {
    barColumnWidth = barColumnWidth + barColumnWidth * (1 - 1 / numOfBarsInOneSection);
  }
  newNumOfBars = Math.floor((barColumnWidth * (document.getElementsByClassName("bar-chart-data-column-" +id).length - hiddenBarColumns)) / 90);
  finalNumOfSectionsReady = false;
  while (!finalNumOfSectionsReady) {
    if (newNumOfBars % numOfBarsInOneSection === 0) {
      finalNumOfSectionsReady = true;
      finalNumOfSections = newNumOfBars / numOfBarsInOneSection;
    } else {
      --newNumOfBars;
    }
  }
  if (finalNumOfSections == 0) {
    finalNumOfSections = 1;
  }
  numOfHiddenSections = document.getElementsByClassName("bar-chart-data-block-" +id).length - finalNumOfSections;
  for (var hSS = 0; hSS < document.getElementsByClassName("bar-chart-data-block-" +id).length; hSS++) {
    if (hSS < numOfHiddenSections) {
      document.getElementsByClassName("bar-chart-data-block-" +id)[hSS].style.display = "none";
    } else {
      document.getElementsByClassName("bar-chart-data-block-" +id)[hSS].style.display = "";
    }
  }
}

var barChartRangeTop = 0;
var barChartValues = [];
var barChartValueTitles = [];
function barChartUpdate(id, dataObject, type) {
  if (document.getElementById("bar-chart-layout-" +id)) {
    barChartRangeTop = barChartAnalyzeData("range-top", dataObject);
    barChartValues = barChartAnalyzeData("values", dataObject);
    barChartValueTitles = barChartAnalyzeData("value-titles", dataObject);
    barChartUpdateLeftLegend(id, dataObject, barChartRangeTop, type);
    barChartUpdateMarkLines(id, dataObject, barChartRangeTop);
    barChartUpdateContent(id, dataObject, barChartRangeTop, barChartValues, type);
    barChartUpdateRightLegend(id, dataObject, barChartValues, barChartValueTitles);
    barChartSetContent(id, "content", type);
    chartContentResize.observe(document.querySelector("#bar-chart-data-layout-" +id));
  } else {
    barChartCreateNew(id, dataObject, type);
    barChartUpdate(id, dataObject, type);
  }
}

var minusValue;
function barChartUpdateLeftLegend(id, dataObject, barChartRangeTop, type) {
  if (document.getElementById("bar-chart-layout-" +id)) {
    document.getElementById("bar-chart-legend-left-layout-" +id).innerHTML = "";
    if (barChartRangeTop != 0) {
      minusValue = barChartRangeTop / (barChartCalcNumOfLines("num-of-lines", barChartRangeTop) + 1);
      while (barChartRangeTop >= 0) {
        var leftLegendV = document.createElement("p");
        leftLegendV.setAttribute("class", "bar-chart-legend-left-text");
        if (type == "time") {
          leftLegendV.innerHTML = secondsToTime(barChartRangeTop, "no-seconds");
        } else {
          leftLegendV.innerHTML = barChartRangeTop;
        }
        document.getElementById("bar-chart-legend-left-layout-" +id).appendChild(leftLegendV);
        barChartRangeTop = barChartRangeTop - minusValue;
      }
    } else {
      var leftLegendTop = document.createElement("p");
      leftLegendTop.setAttribute("class", "bar-chart-legend-left-text");
      leftLegendTop.innerHTML = "-";
      var leftLegendBottom = document.createElement("p");
      leftLegendBottom.setAttribute("class", "bar-chart-legend-left-text");
      if (type == "time") {
        leftLegendBottom.innerHTML = secondsToTime(0, "full");
      } else {
        leftLegendBottom.innerHTML = 0;
      }
      document.getElementById("bar-chart-legend-left-layout-" +id).appendChild(leftLegendTop);
      document.getElementById("bar-chart-legend-left-layout-" +id).appendChild(leftLegendBottom);
    }
  } else {
    barChartCreateNew(id, dataObject, type);
    barChartUpdateLeftLegend(id, barChartRangeTop, type);
  }
}

function barChartUpdateMarkLines(id, dataObject, barChartRangeTop) {
  if (document.getElementById("bar-chart-layout-" +id)) {
    document.getElementById("bar-chart-mark-lines-wrp-" +id).innerHTML = "";
    if (barChartRangeTop != 0) {
      for (var mL = 0; mL <= barChartCalcNumOfLines("num-of-lines", barChartRangeTop); mL++) {
        var markLine = document.createElement("div");
        markLine.setAttribute("class", "bar-chart-mark-lines-bars");
        document.getElementById("bar-chart-mark-lines-wrp-" +id).appendChild(markLine);
      }
    } else {
      var markLine1 = document.createElement("div");
      markLine1.setAttribute("class", "bar-chart-mark-lines-bars");
      var markLine2 = document.createElement("div");
      markLine2.setAttribute("class", "bar-chart-mark-lines-bars");
      var markLine3 = document.createElement("div");
      markLine3.setAttribute("class", "bar-chart-mark-lines-bars");
      var markLine4 = document.createElement("div");
      markLine4.setAttribute("class", "bar-chart-mark-lines-bars");
      var markLine5 = document.createElement("div");
      markLine5.setAttribute("class", "bar-chart-mark-lines-bars");
      document.getElementById("bar-chart-mark-lines-wrp-" +id).appendChild(markLine1);
      document.getElementById("bar-chart-mark-lines-wrp-" +id).appendChild(markLine2);
      document.getElementById("bar-chart-mark-lines-wrp-" +id).appendChild(markLine3);
      document.getElementById("bar-chart-mark-lines-wrp-" +id).appendChild(markLine4);
      document.getElementById("bar-chart-mark-lines-wrp-" +id).appendChild(markLine5);
    }
  } else {
    barChartCreateNew(id, [], type);
    barChartUpdateMarkLines(id, dataObject, barChartRangeTop);
  }
}

var barHeight;
function barChartUpdateContent(id, dataObject, barChartRangeTop, barChartValues, type) {
  if (document.getElementById("bar-chart-layout-" +id)) {
    document.getElementById("bar-chart-data-layout-" +id).innerHTML = "";
    for (var dOC = 0; dOC < Object.keys(dataObject).length; dOC++) {
      var dataBlock = document.createElement("div");
      dataBlock.setAttribute("class", "bar-chart-data-block");
      dataBlock.classList.add("bar-chart-data-block-" +id);
      var columnSize = document.createElement("div");
      columnSize.setAttribute("class", "bar-chart-data-column-size");
      for (var dOCB = 0; dOCB < dataObject[dOC]['values'].length; dOCB++) {
        barHeight = dataObject[dOC]['values'][dOCB]['value'] * 100 / barChartRangeTop;
        var columnBar = document.createElement("div");
        columnBar.setAttribute("class", "bar-chart-data-column");
        columnBar.classList.add("bar-chart-data-column-" +id);
        columnBar.style.height = barHeight +"%";
        columnBar.style.backgroundColor = barChartGetColor(barChartValues.indexOf(dataObject[dOC]['values'][dOCB]['status']));
        var descWrp = document.createElement("div");
        descWrp.setAttribute("class", "bar-chart-data-column-desc-wrp");
        var descTxt = document.createElement("p");
        descTxt.setAttribute("class", "bar-chart-data-column-desc-txt");
        if (type == "time") {
          descTxt.innerHTML = secondsToTime(dataObject[dOC]['values'][dOCB]['value'], "full");
        } else {
          descTxt.innerHTML = dataObject[dOC]['values'][dOCB]['value'];
        }
        columnBar.appendChild(descWrp);
        descWrp.appendChild(descTxt);
        columnSize.appendChild(columnBar);
      }
      var legendWrp = document.createElement("div");
      legendWrp.setAttribute("class", "bar-chart-data-legend-wrp");
      var legendSize = document.createElement("div");
      legendSize.setAttribute("class", "bar-chart-data-legend-size");
      var legendTxt = document.createElement("p");
      legendTxt.setAttribute("class", "bar-chart-data-legend-txt");
      legendTxt.innerHTML = dataObject[dOC]['columnName'];
      dataBlock.appendChild(columnSize);
      dataBlock.appendChild(legendWrp);
      legendWrp.appendChild(legendSize);
      legendSize.appendChild(legendTxt);
      document.getElementById("bar-chart-data-layout-" +id).prepend(dataBlock);
    }
  } else {
    barChartCreateNew(id, dataObject, type);
    barChartUpdateContent(id, dataObject, barChartRangeTop, type);
  }
}

function barChartUpdateRightLegend(id, dataObject, barChartValues, barChartValueTitles) {
  if (document.getElementById("bar-chart-layout-" +id)) {
    document.getElementById("bar-chart-legend-right-layout-" +id).innerHTML = "";
    if (barChartValues.length > 1) {
      for (var bCV = 0; bCV < barChartValues.length; bCV++) {
        var rightBlock = document.createElement("div");
        rightBlock.setAttribute("class", "bar-chart-legend-right-block");
        var colorCube = document.createElement("div");
        colorCube.setAttribute("class", "bar-chart-legend-right-color");
        colorCube.style.backgroundColor = barChartGetColor(bCV);
        var txtWrp = document.createElement("div");
        txtWrp.setAttribute("class", "bar-chart-legend-right-txt-wrp");
        var txtTitle = document.createElement("p");
        txtTitle.setAttribute("class", "bar-chart-legend-right-txt");
        txtTitle.innerHTML = barChartValueTitles[bCV];
        rightBlock.appendChild(colorCube);
        rightBlock.appendChild(txtWrp);
        txtWrp.appendChild(txtTitle);
        document.getElementById("bar-chart-legend-right-layout-" +id).appendChild(rightBlock);
      }
    }
  } else {
    barChartCreateNew(id, [], type);
    barChartUpdateRightLegend(id, dataObject, barChartValues, barChartValueTitles);
  }
}
