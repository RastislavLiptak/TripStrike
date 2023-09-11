window.addEventListener('load', function() {
  detailsPageNavSliderBtnManager();
});

window.addEventListener('resize', function() {
  detailsPageNavSliderBtnManager();
});


function detailsPageNavSliderBtnManager() {
  for (var n = 0; n < document.getElementsByClassName("details-page-nav-wrp").length; n++) {
    detailsPageNavSliderBtnHandler(document.getElementsByClassName("details-page-nav-slider")[n].id.replace("details-page-nav-slider-", ""));
  }
}

function detailsPageNavSliderBtnHandler(id) {
  if (document.getElementById("details-page-nav-slider-"+ id).offsetWidth < document.getElementById("details-page-nav-slider-content-"+ id).offsetWidth) {
    if (document.getElementById("details-page-nav-slider-"+ id).scrollLeft == 0) {
      document.getElementById("details-page-nav-slider-btn-wrp-left-"+ id).style.display = "";
      document.getElementById("details-page-nav-slider-btn-wrp-right-"+ id).style.display = "table";
    } else if (document.getElementById("details-page-nav-slider-"+ id).scrollLeft + document.getElementById("details-page-nav-slider-"+ id).offsetWidth >= document.getElementById("details-page-nav-slider-"+ id).scrollWidth) {
      document.getElementById("details-page-nav-slider-btn-wrp-left-"+ id).style.display = "table";
      document.getElementById("details-page-nav-slider-btn-wrp-right-"+ id).style.display = "";
    } else {
      document.getElementById("details-page-nav-slider-btn-wrp-left-"+ id).style.display = "table";
      document.getElementById("details-page-nav-slider-btn-wrp-right-"+ id).style.display = "table";
    }
  } else {
    document.getElementById("details-page-nav-slider-btn-wrp-left-"+ id).style.display = "";
    document.getElementById("details-page-nav-slider-btn-wrp-right-"+ id).style.display = "";
  }
}

function detailsPageNavSliderBtnLeftOnclick(id) {
  scrollTo(document.getElementById("details-page-nav-slider-"+ id), document.getElementById("details-page-nav-slider-"+ id).scrollLeft - document.getElementById("details-page-nav-slider-"+ id).offsetWidth / 1.5, 250);
}

function detailsPageNavSliderBtnRightOnclick(id) {
  scrollTo(document.getElementById("details-page-nav-slider-"+ id), document.getElementById("details-page-nav-slider-"+ id).scrollLeft + document.getElementById("details-page-nav-slider-"+ id).offsetWidth / 1.5, 250);
}
