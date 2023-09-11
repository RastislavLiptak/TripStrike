<?php
  // information about selected language
  if (!isset($_COOKIE["languageBanner"])) {
    $languageBannerTsk = false;
    setcookie("languageBanner", "done", time() + (900*24*60*60*1000), "/");
  } else {
    $languageBannerTsk = true;
  }
?>
