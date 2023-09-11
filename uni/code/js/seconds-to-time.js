function secondsToTime(val, format) {
  if (!isNaN(val)) {
    var hours = Math.floor(val / 3600);
    var minutes = Math.floor((val - (hours * 3600)) / 60);
    var seconds = Math.floor(val - ((hours * 3600) + (minutes * 60)));
    var outputText = "";
    if (hours != 0) {
      outputText = hours +"h";
    }
    if (minutes != 0) {
      if (outputText != "") {
        outputText = outputText +" "+ minutes +"m";
      } else {
        outputText = minutes +"m";
      }
    }
    if (format != "no-seconds" || outputText == "") {
      if (seconds != 0 || outputText == "") {
        if (outputText != "") {
          outputText = outputText +" "+ seconds +"s";
        } else {
          outputText = seconds +"s";
        }
      }
    }
  } else {
    outputText = val;
  }
  return outputText;
}
