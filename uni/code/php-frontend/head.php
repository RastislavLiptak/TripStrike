<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="../uni/code/css/uni.css">
<link rel="stylesheet" type="text/css" href="../uni/code/css/header.css" preload>
<link rel="stylesheet" type="text/css" href="../uni/code/css/modals.css" preload>
<link rel="stylesheet" type="text/css" href="../uni/code/css/input.css" preload>
<link rel="stylesheet" type="text/css" href="../uni/code/css/crop.css" preload>
<link rel="stylesheet" type="text/css" href="../uni/code/css/floating-banners.css">
<link rel="stylesheet" type="text/css" href="../uni/code/css/cookie-banner.css">
<link rel="stylesheet" type="text/css" href="../uni/code/css/language-banner.css">
<script src="../uni/code/js/uni.js" async></script>
<script src="../uni/code/js/window-onclick.js" async></script>
<script src="../uni/code/js/header.js"></script>
<script src="../uni/code/js/modals.js"></script>
<script src="../uni/code/js/input.js" async></script>
<script src="../uni/code/js/crop.js" async></script>
<script src="../uni/code/js/select.js" async></script>
<script src="../uni/code/js/cookie-banner.js" async></script>
<script src="../uni/code/js/language-banner.js" async></script>
<script src="../uni/code/js/test-json.js"></script>
<script src="../uni/code/js/google-map-load-script.js"></script>
<script src="../uni/code/js/scroll-to-animation.js" async></script>
<script src="../uni/code/js/scrollbar-width-calc.js" async></script>
<?php
  include "settings/settings-head.php";
  if ($bnft_add_cottage == "good") {
    include "new-cottage/new-cottage-head.php";
  }
  if ($bnft_edit_cottage == "good") {
?>
  <script src="../uni/code/js/editor-modal.js" async></script>
  <link rel="stylesheet" type="text/css" href="../uni/code/css/editor-modal.css" preload>
<?php
  }
  if ($bnft_add_cottage == "good" || $bnft_edit_cottage == "good") {
?>
  <script src="../uni/code/js/uni-conditions-of-stay.js" async></script>
  <script src="../uni/code/js/place-equipment-modal.js" async></script>
  <script src="../uni/code/js/calendar-sync-modal.js" async></script>
  <link rel="stylesheet" type="text/css" href="../uni/code/css/place-equipment-modal.css" preload>
  <link rel="stylesheet" type="text/css" href="../uni/code/css/uni-conditions-of-stay.css" preload>
  <link rel="stylesheet" type="text/css" href="../uni/code/css/calendar-sync-modal.css" preload>
<?php
  }
  if (getOS() == "Windows 10" || getOS() == "Windows 8.1" || getOS() == "Windows 8" || getOS() == "Windows 7") {
?>
  <link rel="stylesheet" type="text/css" href="../uni/code/css/custom-scrollbar.css">
<?php
  }
  if ($sign != "yes") {
?>
  <link rel="stylesheet" type="text/css" href="../uni/code/css/sign-in/sign-uni.css" preload>
  <link rel="stylesheet" type="text/css" href="../uni/code/css/sign-in/sign-in/sign-in.css" preload>
  <link rel="stylesheet" type="text/css" href="../uni/code/css/sign-in/sign-up/sign-up.css" preload>
  <script src="../uni/code/js/sign-in/sign-uni.js"></script>
  <script src="../uni/code/js/sign-in/sign-in/sign-in.js"></script>
  <script src="../uni/code/js/sign-in/sign-up/sign-up.js"></script>
<?php
  }
?>
<link rel="icon" href="../uni/images/logo/favicon.png">
