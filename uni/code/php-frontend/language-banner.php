<?php
if (!$languageBannerTsk) {
?>
<div class="floating-banner" id="language-banner">
  <div class="floating-banner-img-wrp">
    <img class="floating-banner-img" src="../uni/icons/langs/<?php echo $wrd_shrt; ?>.svg" alt="language icon">
  </div>
  <div class="floating-banner-txt-wrp floating-banner-txt-wrp-w-img">
    <p class="floating-banner-txt"><?php echo $wrd_theLanguageIsSetTo." "; ?><button type="button" name="button" onclick="toggSet('show', 'lang')"><?php echo $wrd_lang; ?></button></p>
  </div>
  <div class="floating-banner-btn-wrp">
    <button type="button" class="floating-banner-btn" onclick="closeLanguageBanner()"></button>
  </div>
</div>
<?php
}
?>
