function mainMenu() {
  if (document.getElementsByClassName("main-menu-size-show").length > 0) {
    document.getElementById("main-menu-size").className = "main-menu-size-hide";
  } else {
    document.getElementById("main-menu-size").className = "main-menu-size-show";
  }
}
