<?php
  $showSeeMore = true;
  if ($sign == "no") {
    $seeMoreOnclick = "signUpModal('show')";
    $seeMoreTxt = $wrd_createAccount;
  } else {
    if ($bnft_add_cottage != "none") {
      $seeMoreOnclick = "newCottageModal('show')";
      $seeMoreTxt = $wrd_addHut;
    } else {
      $showSeeMore = false;
    }
  }
?>
<div id="home-no-place-wrp">
  <div id="home-no-place-filter">
    <div id="home-no-place-content">
      <h1 id="home-no-place-title"><?php echo $wrd_thePageHasNoContentYet; ?></h1>
      <?php
        if ($showSeeMore) {
      ?>
        <button type="button" id="home-no-place-see-more-blck" onclick="<?php echo $seeMoreOnclick; ?>">
          <div id="home-no-place-see-more-wrp">
            <p id="home-no-place-see-more-txt"><?php echo $seeMoreTxt; ?></p>
            <div id="home-no-place-see-more-icn"></div>
          </div>
        </button>
      <?php
        }
      ?>
    </div>
  </div>
</div>
