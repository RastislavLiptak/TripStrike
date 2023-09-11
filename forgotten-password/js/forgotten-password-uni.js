function forgottenPasswordContentSwitcher(cont) {
  setUpStepByStepSliderFncReset();
  document.getElementById("set-up-step-by-step-content-"+cont).classList.add("set-up-step-by-step-content-selected");
}

function forgottenPasswordBtnHandler(task, id) {
  if (task == "def") {
    document.getElementById(id).style.color = "";
    document.getElementById(id).style.backgroundImage = "";
    document.getElementById(id).style.backgroundSize = "";
  } else if (task == "load") {
    document.getElementById(id).style.color = "rgba(0,0,0,0)";
    document.getElementById(id).style.backgroundImage = "url('../uni/gifs/loader-tail.svg')";
    document.getElementById(id).style.backgroundSize = "auto 63%";
  } else if (task == "done") {
    document.getElementById(id).style.color = "rgba(0,0,0,0)";
    document.getElementById(id).style.backgroundImage = "url('../uni/icons/ok2.svg')";
    document.getElementById(id).style.backgroundSize = "auto 47%";
  }
}
