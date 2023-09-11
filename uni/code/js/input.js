function inptFcs(id, task) {
  autocompleteIssueBlocker("input");
  if (task == "in") {
    hideAllInputAlerts();
    inputClass(id, "inpt-focus-p", "placeholder");
  } else if (task == "out") {
    var val = document.getElementById(id).value;
    if (val != "") {
      inputClass(id, "inpt-fill-p", "placeholder");
    } else {
      inputClass(id, "", "placeholder");
    }
  }
}

function inputClass(id, clss, e) {
  var elmnt = document.getElementById(id+ "-p");
  elmnt.classList.remove("inpt-focus-p");
  elmnt.classList.remove("inpt-fill-p");
  var arr = elmnt.className.split(" ");
  if (arr.indexOf(clss) == -1) {
    elmnt.className += " " + clss;
  }
}

function autocompleteIssueBlocker(clss) {
  var inpt = document.getElementsByClassName(clss);
  for (var i = 0; i < inpt.length; ++i) {
    inpt[i].value = inpt[i].value;
  }
}

var selectTime;
function selectDrop(event, id) {
  if (id != "unset") {
    event.preventDefault();
  }
  if (id != "unset" && id != null && !document.getElementById(id).classList.contains("select-btn-select")) {
    clearTimeout(selectTime);
    selectTime = setTimeout(function(){
      selectClass(id, "select-btn-select", "add");
      selectClass(id+ "-drop", "option-wrp-select", "add");
    }, 30);
  } else {
    selectClass("select", "select-btn-select", "remove");
    selectClass("option-wrp", "option-wrp-select", "remove");
  }
}

function selectClass(id, clss, task) {
  if (task == "add") {
    var elmnt = document.getElementById(id);
    var arr = elmnt.className.split(" ");
    if (arr.indexOf(clss) == -1) {
      elmnt.className += " " + clss;
    }
  } else if (task == "remove") {
    var el = document.getElementsByClassName(id);
    for (var i = 0; i < el.length; ++i) {
      el[i].classList.remove(clss);
    }
  }
}

function optionSet(event, el, id) {
  event.preventDefault();
  document.getElementById(id).value = el.value;
  document.getElementById(id).innerHTML = el.innerHTML;
  clss = "option-select";
  var btns = document.getElementById(id +"-scroll-wrp").querySelectorAll(".option");
  for (var i = 0; i < btns.length; ++i) {
    btns[i].classList.remove(clss);
  }
  var arr = el.className.split(" ");
  if (arr.indexOf(clss) == -1) {
    el.className += " " + clss;
  }
}

function showInputAlert(wrpId, txtId) {
  hideAllInputAlerts();
  document.getElementById(wrpId).style.display = "block";
  document.getElementById(txtId).style.display = "block";
}

function hideAllInputAlerts() {
  var wrp = document.getElementsByClassName("input-error-wrp");
  for (var i1 = 0; i1 < wrp.length; i1++) {
    wrp[i1].style.display = "none";
  }
  var p = document.getElementsByClassName("input-error-txt");
  for (var i2 = 0; i2 < p.length; i2++) {
    p[i2].style.display = "none";
  }
}

function inptNumOnly(e, rnd) {
  if (rnd == "round") {
    e.value = e.value.replace(/\D/g,'');
  } else if (rnd == "decimal") {
    e.value = e.value.replace(/[^0-9.]/g, "");
  }
}

function inptNumBtn(tsk, id) {
  var val = document.getElementById(id).value;
  if (isNaN(val)) {
    val = 1;
  }
  if (tsk == "up") {
    ++val;
  } else {
    --val;
  }
  if (val < 1) {
    val = 1;
  }
  document.getElementById(id).value = val;
}
