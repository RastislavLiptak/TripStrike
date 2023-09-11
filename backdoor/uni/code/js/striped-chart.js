function stripedChartCreateNew(id, dataObject) {
  stripedChartRenderBasics(id);
  if (Object.keys(dataObject).length == 0) {
    stripedChartLoader(id);
  } else {
    stripedChartContent(id, dataObject);
  }
}

function stripedChartGetColor(i) {
  return "#00c0ff";
}

function stripedChartRenderBasics(id) {
  document.getElementById("striped-chart-wrp-" +id).innerHTML = "";
  var chartSize = document.createElement("div");
  chartSize.setAttribute("class", "striped-chart-size");
  chartSize.setAttribute("id", "striped-chart-size-" +id);
  document.getElementById("striped-chart-wrp-" +id).appendChild(chartSize);
}

function stripedChartLoader(id) {
  if (document.getElementById("striped-chart-size-" +id)) {
    document.getElementById("striped-chart-size-" +id).innerHTML = "";
    var loaderWrp = document.createElement("div");
    loaderWrp.setAttribute("class", "striped-chart-loader-wrp");
    var loaderImg = document.createElement("img");
    loaderImg.setAttribute("class", "striped-chart-loader-img");
    loaderImg.setAttribute("src", "../../uni/gifs/loader-tail.svg");
    loaderImg.setAttribute("alt", "loader gif");
    loaderWrp.appendChild(loaderImg);
    document.getElementById("striped-chart-size-" +id).appendChild(loaderWrp);
  }
}

function stripedChartError(id, txt) {
  if (document.getElementById("striped-chart-size-" +id)) {
    document.getElementById("striped-chart-size-" +id).innerHTML = "";
    var errorWrp = document.createElement("div");
    errorWrp.setAttribute("class", "striped-chart-error-wrp");
    var errorTxt = document.createElement("p");
    errorTxt.setAttribute("class", "striped-chart-error-txt");
    errorTxt.innerHTML = txt;
    errorWrp.appendChild(errorTxt);
    document.getElementById("striped-chart-size-" +id).appendChild(errorWrp);
  }
}

var stripeColor = "";
function stripedChartContent(id, dataObject) {
  if (document.getElementById("striped-chart-size-" +id)) {
    document.getElementById("striped-chart-size-" +id).innerHTML = "";
    var contentWrp = document.createElement("div");
    contentWrp.setAttribute("class", "striped-chart-content");
    if (dataObject.length > 0) {
      for (var sCC = 0; sCC < dataObject.length; sCC++) {
        stripeColor = stripedChartGetColor(sCC);
        var blck = document.createElement("div");
        blck.setAttribute("class", "striped-chart-blck");
        var prcWrp = document.createElement("div");
        prcWrp.setAttribute("class", "striped-chart-blck-perc-wrp");
        var prcTxt = document.createElement("p");
        prcTxt.setAttribute("class", "striped-chart-blck-perc-txt");
        prcTxt.style.color = stripeColor;
        prcTxt.innerHTML = dataObject[sCC]['perc'] +"%";
        var details = document.createElement("div");
        details.setAttribute("class", "striped-chart-blck-details");
        var detailsTxtWrp = document.createElement("div");
        detailsTxtWrp.setAttribute("class", "striped-chart-blck-details-txt-wrp");
        var detailsTxt = document.createElement("p");
        detailsTxt.setAttribute("class", "striped-chart-blck-details-txt");
        if (dataObject[sCC]['link'] != "#") {
          var detailsTxtLink = document.createElement("a");
          detailsTxtLink.setAttribute("href", dataObject[sCC]['link']);
          detailsTxtLink.setAttribute("target", "_blank");
          detailsTxtLink.innerHTML = dataObject[sCC]['title'];
          detailsTxt.appendChild(detailsTxtLink);
        } else {
          detailsTxt.innerHTML = dataObject[sCC]['title'];
        }
        var barWrp = document.createElement("div");
        barWrp.setAttribute("class", "striped-chart-blck-details-bar-wrp");
        var bar = document.createElement("div");
        bar.setAttribute("class", "striped-chart-blck-details-bar");
        bar.style.backgroundColor = stripeColor;
        bar.style.width = stripedChartCalcBarWidth(dataObject[sCC]['prim-value'], dataObject) +"%";
        var barSize = document.createElement("div");
        barSize.setAttribute("class", "striped-chart-blck-details-bar-size");
        var barCover = document.createElement("div");
        barCover.setAttribute("class", "striped-chart-blck-details-bar-cover");
        barCover.style.width = (dataObject[sCC]['sec-value'] * 100 / dataObject[sCC]['prim-value']) +"%";
        var descWrp = document.createElement("div");
        descWrp.setAttribute("class", "striped-chart-blck-details-bar-desc-wrp");
        var descTxt = document.createElement("p");
        descTxt.setAttribute("class", "striped-chart-blck-details-bar-desc-txt");
        descTxt.style.color = stripeColor;
        if (dataObject[sCC]['sec-value'] != "0") {
          descTxt.innerHTML = dataObject[sCC]['sec-value'] +" / "+ dataObject[sCC]['prim-value'];
        } else {
          descTxt.innerHTML = dataObject[sCC]['prim-value'];
        }
        blck.appendChild(prcWrp);
        prcWrp.appendChild(prcTxt);
        blck.appendChild(details);
        details.appendChild(detailsTxtWrp);
        detailsTxtWrp.appendChild(detailsTxt);
        details.appendChild(barWrp);
        barWrp.appendChild(bar);
        bar.appendChild(barSize);
        barSize.appendChild(barCover);
        barSize.appendChild(descWrp);
        descWrp.appendChild(descTxt);
        contentWrp.appendChild(blck);
      }
    } else {
      var noContentWrp = document.createElement("div");
      noContentWrp.setAttribute("class", "striped-chart-no-content-wrp");
      var noContentTxt = document.createElement("p");
      noContentTxt.setAttribute("class", "striped-chart-no-content-txt");
      noContentTxt.innerHTML = "No Content";
      noContentWrp.appendChild(noContentTxt);
      contentWrp.appendChild(noContentWrp);
    }
    document.getElementById("striped-chart-size-" +id).appendChild(contentWrp);
  }
}

var topValue = 0;
function stripedChartCalcBarWidth(primaryValue, dataObject) {
  topValue = 0;
  for (var sCCBW = 0; sCCBW < dataObject.length; sCCBW++) {
    if (dataObject[sCCBW]['prim-value'] > topValue) {
      topValue = dataObject[sCCBW]['prim-value'];
    }
  }
  return primaryValue * 90 / topValue; // 90 means 90% of the width of a wrap
}
