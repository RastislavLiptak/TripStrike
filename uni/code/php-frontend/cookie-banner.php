<?php
if (!isset($_COOKIE["cookiesAccept"])) {
?>
  <div class="floating-banner" id="cookie-banner">
    <div class="floating-banner-txt-wrp">
      <p class="floating-banner-txt"><?php echo $wrd_weAreUseingCookies; ?></p>
    </div>
    <div class="floating-banner-btn-wrp">
      <button type="button" class="floating-banner-btn" onclick="closeCookieBanner()"></button>
    </div>
  </div>
<?php
}
?>
