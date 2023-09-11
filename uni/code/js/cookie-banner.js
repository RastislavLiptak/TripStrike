function closeCookieBanner() {
  var d = new Date();
  d.setTime(d.getTime() + (900 * 24 * 60 * 60 * 1000));
  var expires = "expires="+d.toUTCString();
  document.cookie = "cookiesAccept=ok" + ";" + expires + ";path=/";
  var banner = document.getElementById("cookie-banner");
  banner.parentNode.removeChild(banner);
}
