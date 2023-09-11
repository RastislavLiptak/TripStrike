var wrd_night = "night";
var wrd_personPerNight = "person per night";
function setNightWord(night, personPerNight) {
  wrd_night = night;
  wrd_personPerNight = personPerNight;
}

var price;
function imgCottLinkRender(sts, wrpId, clss, id, name, src, priceMode, priceWeek, priceWork, currency, rating) {
  fakeImgCottLinkDelete(wrpId);
  if (priceWeek == priceWork) {
    price = addCurrency(currency, priceWork);
  } else {
    price = addCurrency(currency, priceWork) +" ("+ addCurrency(currency, priceWeek) +")";
  }
  if (priceMode == "guests") {
    price = price +" /"+ wrd_personPerNight;
  } else {
    price = price +" /"+ wrd_night;
  }
  var blck = document.createElement("div");
  blck.setAttribute("class", "link-img-blck "+ clss);
  var a = document.createElement("a");
  a.setAttribute("class", "link-img-a");
  a.setAttribute("href", "../place/?id="+ id);
  var wrp = document.createElement("div");
  wrp.setAttribute("class", "link-img-wrp");
  var imgWrp = document.createElement("div");
  imgWrp.setAttribute("class", "link-img-bck-wrp");
  var img = document.createElement("div");
  if (src == "none") {
    img.setAttribute("class", "fake-link-img");
  } else {
    img.setAttribute("class", "link-img");
    img.style.backgroundImage = "url('../"+ src +"')";
  }
  var txtWrp = document.createElement("div");
  txtWrp.setAttribute("class", "link-img-txt-wrp");
  var nameWrp = document.createElement("div");
  nameWrp.setAttribute("class", "link-img-txt-name-wrp");
  var nameP = document.createElement("p");
  nameP.setAttribute("class", "link-img-txt-name");
  nameP.innerHTML = name;
  var detailsWrp = document.createElement("div");
  detailsWrp.setAttribute("class", "link-img-txt-details-wrp");
  var priceWrp = document.createElement("div");
  priceWrp.setAttribute("class", "link-img-txt-price-wrp");
  var pricing = document.createElement("p");
  pricing.setAttribute("class", "link-img-details-style link-img-txt-price");
  pricing.innerHTML = price;
  if (rating != "none") {
    var dotWrp = document.createElement("div");
    dotWrp.setAttribute("class", "link-img-txt-dot-wrp");
    var dot = document.createElement("p");
    dot.setAttribute("class", "link-img-details-style link-img-txt-dot");
    dot.innerHTML = "&#183;";
    var ratingWrp = document.createElement("div");
    ratingWrp.setAttribute("class", "link-img-txt-rating-wrp");
    var ratingIcn = document.createElement("div");
    ratingIcn.setAttribute("class", "link-img-txt-rating-icn");
    var ratingTxt = document.createElement("p");
    ratingTxt.setAttribute("class", "link-img-details-style link-img-txt-rating");
    ratingTxt.innerHTML = rating;
  }
  blck.appendChild(a);
  a.appendChild(wrp);
  wrp.appendChild(imgWrp);
  imgWrp.appendChild(img);
  wrp.appendChild(txtWrp);
  txtWrp.appendChild(nameWrp);
  nameWrp.appendChild(nameP);
  txtWrp.appendChild(detailsWrp);
  detailsWrp.appendChild(priceWrp);
  priceWrp.appendChild(pricing);
  if (rating != "none") {
    detailsWrp.appendChild(dotWrp);
    dotWrp.appendChild(dot);
    detailsWrp.appendChild(ratingWrp);
    ratingWrp.appendChild(ratingIcn);
    ratingWrp.appendChild(ratingTxt);
  }
  if (sts == "new") {
    document.getElementById(wrpId).prepend(blck);
  } else {
    document.getElementById(wrpId).appendChild(blck);
  }
}

function fakeImgCottLinkRender(wrpId) {
  if (!document.getElementById(wrpId).contains(document.getElementsByClassName("fake-link-img-blck")[0])) {
    for (var i = 0; i < 6; i++) {
      var blck = document.createElement("div");
      blck.setAttribute("class", "fake-link-img-blck");
      var wrp = document.createElement("div");
      wrp.setAttribute("class", "fake-link-img-wrp");
      var imgWrp = document.createElement("div");
      imgWrp.setAttribute("class", "fake-link-img-bck-wrp");
      var txtWrp = document.createElement("div");
      txtWrp.setAttribute("class", "fake-link-img-txt-wrp");
      blck.appendChild(wrp);
      wrp.appendChild(imgWrp);
      wrp.appendChild(txtWrp);
      document.getElementById(wrpId).prepend(blck);
    }
  } else {
    fakeImgCottLinkDelete(wrpId);
    fakeImgCottLinkRender(wrpId);
  }
}

var fakeBlocks;
function fakeImgCottLinkDelete(wrpId) {
  fakeBlocks = document.getElementById(wrpId).getElementsByClassName("fake-link-img-blck");
  while (fakeBlocks.length > 0) {
    fakeBlocks[0].parentNode.removeChild(fakeBlocks[0]);
  }
}
