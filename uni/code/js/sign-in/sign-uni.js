function signSubmitBtn(task, id) {
  if (task == "def") {
    document.getElementById(id).style.color = "#fff";
    document.getElementById(id).style.backgroundImage = "unset";
    document.getElementById(id).style.backgroundSize = "unset";
  } else if (task == "load") {
    document.getElementById(id).style.color = "rgba(0,0,0,0)";
    document.getElementById(id).style.backgroundImage = "url('../uni/gifs/loader-tail.svg')";
    document.getElementById(id).style.backgroundSize = "auto 63%";
  } else if (task == "success") {
    document.getElementById(id).style.color = "rgba(0,0,0,0)";
    document.getElementById(id).style.backgroundImage = "url('../uni/icons/ok2.svg')";
    document.getElementById(id).style.backgroundSize = "auto 47%";
  }
}

function signSwitch(switchTo) {
  if (switchTo == "sign-up") {
    signInModal("hide");
    signUpModal("show");
  } else {
    signUpModal("hide");
    signInModal("show");
  }
}
