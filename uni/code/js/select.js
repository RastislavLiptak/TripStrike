function birthSet(id) {
  birthCreate("d", "auto", id);
}

function birthCreate(get, sts, id) {
  var year = document.getElementById(id +"-y").value;
  if (get == "all") {
    birthCreate("y", sts, id);
  } else if (get == "y") {
    document.getElementById(id +"-y-scroll-wrp").innerHTML = "";
    var todayY = new Date().getFullYear();
    var minY = todayY - 110;
    if (year != "") {
      var defBtnY = year;
    } else {
      var defBtnY = todayY - 40;
    }
    document.getElementById(id +"-y").value = defBtnY;
    document.getElementById(id +"-y").innerHTML = defBtnY;
    for (var i = todayY; i > minY; i--) {
      var btn = document.createElement("BUTTON");
      btn.setAttribute("class", "option");
      if (sts == "def" && i == defBtnY) {
        btn.setAttribute("class", "option option-select");
      } else {
        btn.setAttribute("class", "option");
      }
      btn.setAttribute("value", i);
      btn.setAttribute("onclick", "optionSet(event, this, '"+ id +"-y');birthSet('"+ id +"')");
      btn.innerHTML = i;
      document.getElementById(id +"-y-scroll-wrp").appendChild(btn);
    }
    birthCreate("d", sts, id);
  } else if (get == "d") {
    document.getElementById(id +"-d-scroll-wrp").innerHTML = "";
    var day = document.getElementById(id +"-d").value;
    var month = document.getElementById(id +"-m").value;
    if (month == 1 || month == 3 || month == 5 || month == 7 || month == 8 || month == 10 || month == 12) {
      var maxD = 31;
    } else if (month == 2 && year % 4 === 0) {
      var maxD = 29;
    } else if (month == 2) {
      var maxD = 28;
    } else {
      var maxD = 30;
    }
    if (day == 0) {
      day = 1;
    } else if (day > maxD) {
      day = maxD;
    }
    for (var i = 1; i <= maxD; i++) {
      var btn = document.createElement("BUTTON");
      if (i == day) {
        btn.setAttribute("class", "option option-select");
        document.getElementById(id +"-d").value = i;
        document.getElementById(id +"-d").innerHTML = i;
      } else {
        btn.setAttribute("class", "option");
      }
      btn.setAttribute("value", i);
      btn.setAttribute("onclick", "optionSet(event, this, '"+ id +"-d')");
      btn.innerHTML = i;
      document.getElementById(id +"-d-scroll-wrp").appendChild(btn);
    }
  }
}
