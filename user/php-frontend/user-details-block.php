<div class="user-data-blck uni-details-block-style">
  <div class="user-info-wrp">
    <div class="user-info-img-wrp">
    <?php
      if ($usrBeId == $accBeId) {
    ?>
    <button class="my-prof-img-wrp" onclick="toggSet('show', 'prof-img')">
      <img src="../<?php echo $accmidimg; ?>" alt="<?php echo $accfirstname." ".$acclastname; ?> profile image" class="user-info-img">
      <div class="my-prof-img-cover">
        <img src="../uni/icons/img-set.svg" alt="dafault img change icon" class="my-prof-img-cover-img">
      </div>
    </button>
    <?php
      } else {
    ?>
      <a href="../user/?id=<?php echo $accId; ?>&section=about">
        <img src="../<?php echo $accmidimg; ?>" alt="<?php echo $accfirstname." ".$acclastname; ?> profile image" class="user-info-img">
      </a>
    <?php
      }
    ?>
    </div>
    <div class="user-info-txt">
      <div class="user-info-txt-wrp user-info-txt-wrp-title">
        <a href="../user/?id=<?php echo $accId; ?>&section=about" class="user-info-title"><?php echo $accfirstname." ".$acclastname; ?></a>
      </div>
      <div class="user-info-txt-wrp user-info-txt-wrp-link">
        <a href="mailto:<?php echo $accemail ?>" target="_blank" class="user-info-link user-info-email"><?php echo $accemail; ?></a>
      </div>
      <div class="user-info-txt-wrp user-info-txt-wrp-link">
        <a href="tel:<?php echo preg_replace('/\s+/', '', $accphonenum); ?><" class="user-info-link user-info-phone"><?php echo $accphonenum; ?></a>
      </div>
      <?php
        if ($sect == "about") {
          $userDetailsSection = "huts";
          $userDetailsSectionText = $wrd_huts;
        } else {
          $userDetailsSection = "about";
          $userDetailsSectionText = $wrd_aboutProfile;
        }
      ?>
      <div class="user-info-about-wrp">
        <a href="../user/?id=<?php echo $accId; ?>&section=<?php echo $userDetailsSection; ?>" class="user-info-about-a">
          <button type="button" class="user-info-about-btn"><?php echo $userDetailsSectionText; ?></button>
        </a>
      </div>
    </div>
  </div>
  <div class="user-menu-wrp">
    <div class="user-menu-blck">
      <button class="user-menu-btn" aria-label="user menu" onclick="userMemu('toggle')"></button>
      <div class="user-menu-dropdown-wrp">
        <?php
          if ($usrBeId != $accBeId) {
        ?>
          <button class="user-menu-dropdown-btn" onclick="modCover('show', 'modal-cover-user-report')"><?php echo $wrd_report; ?></button>
        <?php
          } else {
        ?>
          <button class="user-menu-dropdown-btn" onclick="toggSet('show', 'public-data')"><?php echo $wrd_settings; ?></button>
        <?php
          }
        ?>
          <button class="user-menu-dropdown-btn" onclick="share('show')"><?php echo $wrd_share; ?></button>
      </div>
    </div>
  </div>
</div>
