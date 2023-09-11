window.addEventListener('scroll', function() {
  headerScroll();
});

window.addEventListener('load', function() {
  headerScroll();
  accountDropEventsOnload();
});

var usrNameTime, scrolledTop, accMenuDropSts;
function headerScroll() {
  var scroll_top = (window.pageYOffset || document.scrollTop)  - (document.clientTop || 0);
  var new_top = 8 - (scroll_top / 4);
  if (isNaN(new_top)) {
    new_top = 8;
  } else if (new_top < 0) {
    new_top = 0;
  }
  document.getElementById("header").style.top = new_top +"px";
  document.getElementById("header-top-offset").style.height = new_top +"px";
  if (!isNaN(scroll_top) && scroll_top > 0) {
    headerRezize("46px");
    document.getElementById("account-txt").style.opacity = "0";
    document.getElementById("header").style.borderBottomColor = "#222325";
    if (!accMenuDropSts) {
      document.getElementById("account-txt").style.marginLeft = "0";
    }
    document.getElementById("title-h2").style.opacity = "0";
    usrNameTime = setTimeout(function(){
      document.getElementById("account-txt").style.maxWidth = "0px";
      document.getElementById("title-h2").style.fontSize = "0px";
    }, 10);
    scrolledTop = false;
  } else {
    document.getElementById("account-txt").style.maxWidth = "";
    document.getElementById("title-h2").style.fontSize = "";
    document.getElementById("account-txt").style.marginLeft = "";
    document.getElementById("header").style.borderBottomColor = "";
    usrNameTime = setTimeout(function() {
      headerRezize("");
      document.getElementById("account-txt").style.opacity = "";
      document.getElementById("title-h2").style.opacity = "";
    }, 20);
    scrolledTop = true;
  }
}

function headerRezize(h) {
  document.getElementById("header").style.height = h;
  document.getElementById("account-txt").style.lineHeight = h;
  document.getElementById("main-menu-btn").style.height = h;
}

var menuTime;
function menu() {
  if (document.getElementById("main-menu-btn").value == "none") {
    document.getElementById("main-menu-cover").style.display = "table";
    clearTimeout(menuTime);
    menuTime = setTimeout(function(){
      document.getElementById("main-menu-cover").style.backgroundColor = "rgba(0,0,0,0.5)";
      document.getElementById("menu-wrap").className = "showMenu";
      document.getElementById("main-menu-btn").value = "clicked";
    }, 30);
  } else {
    clearTimeout(menuTime);
    menuTime = setTimeout(function(){
      document.getElementById("main-menu-cover").style.display = "none";
    }, 300);
    document.getElementById("main-menu-cover").style.backgroundColor = "rgba(0,0,0,0)";
    document.getElementById("menu-wrap").className = "hideMenu";
    document.getElementById("main-menu-btn").value = "none";
  }
}

var accBtnTime;
var drpShowHide = "hide";
function accountBtn(task) {
  if (document.getElementById("account-drop-btn")) {
    if (task == "over") {
      document.getElementById("account-drop-btn").style.display = "table";
      clearTimeout(accBtnTime);
      accBtnTime = setTimeout(function(){
        document.getElementById("account-btn").style.marginRight = "11px";
        document.getElementById("account-drop-btn").style.width = "20px";
        document.getElementById("account-drop-btn").style.opacity = "1";
        document.getElementById("account-drop-btn").style.marginLeft = "1px";
        if (scrolledTop) {
          document.getElementById("account-txt").style.marginLeft = "5.5px";
        } else {
          document.getElementById("account-txt").style.marginLeft = "4px";
        }
      }, 10);
    } else if (task == "leave" && drpShowHide == "hide") {
      document.getElementById("account-btn").style.marginRight = "";
      document.getElementById("account-drop-btn").style.width = "";
      document.getElementById("account-drop-btn").style.opacity = "";
      document.getElementById("account-drop-btn").style.marginLeft = "";
      if (scrolledTop) {
        document.getElementById("account-txt").style.marginLeft = "";
      } else {
        document.getElementById("account-txt").style.marginLeft = "0";
      }
      clearTimeout(accBtnTime);
      accBtnTime = setTimeout(function(){
        document.getElementById("account-drop-btn").style.display = "";
      }, 110);
    }
  }
}

var accountDropClickTask = "";
function headerAccountOnclick(tsk) {
  accountDropClickTask = tsk;
}

function accountDropEventsOnload() {
  var accountDropTouchSts = false;
  var accountDropTouchTime;
  document.getElementById("account-details-wrp").addEventListener('touchstart', function() {
    accountDropTouchSts = true;
    accountDrop("toggle");
    clearTimeout(accountDropTouchTime);
    accountDropTouchTime = setTimeout(function(){
      accountDropTouchSts = false;
    }, 200);
  });

  document.getElementById("account-details-wrp").addEventListener('click', function() {
    if (!accountDropTouchSts) {
      if (accountDropClickTask == "sign-in") {
        signInModal("show");
      } else {
        location.href = '../user/?id=&section=about';
      }
    }
  });
}

var accDrpTime;
function accountDrop(task) {
  if (task == "hide") {
    drpShowHide = "show";
  } else if (task == "show") {
    drpShowHide = "hide";
  }
  if (drpShowHide == "hide") {
    drpShowHide = "show";
    document.getElementById("account-drop-wrp").style.display = "table";
    clearTimeout(accDrpTime);
    accDrpTime = setTimeout(function(){
      document.getElementById("account-drop-wrp").style.opacity = "1";
      document.getElementById("account-drop-wrp").style.top = "58px";
      accMenuDropSts = true;
    }, 10);
  } else if (drpShowHide == "show") {
    drpShowHide = "hide";
    document.getElementById("account-drop-wrp").style.opacity = "0";
    document.getElementById("account-drop-wrp").style.top = "40px";
    clearTimeout(accDrpTime);
    accDrpTime = setTimeout(function(){
      document.getElementById("account-drop-wrp").style.display = "none";
      accMenuDropSts = false;
    }, 160);
  }
  if (task == "hide") {
    accountBtn("leave");
  }
}

function signOut() {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      if (testJSON(xhr.response)) {
        var json = JSON.parse(xhr.response);
        for (var key in json) {
          if (json.hasOwnProperty(key)) {
            if (json[key]["sts"] == 1) {
              location.href = "../home/";
            } else {
              modCover("show", "modal-cover-out-1");
            }
          }
        }
      } else {
        modCover("show", "modal-cover-out-1");
      }
    }
  }
  xhr.open("POST", "../uni/code/php-backend/sign-out.php");
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send();
}
