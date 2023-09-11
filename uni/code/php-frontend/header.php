<div id="header-wrap">
  <div id="header-top-offset"></div>
  <div id="header">
    <div class="header-elmnt">
      <button type="button" aria-label="menu button" id="main-menu-btn" class="menu-btn" onclick="menu()" value="none"></button>
    </div>
    <div class="header-elmnt" id="header-title">
      <div id="title-wrp" onclick="location.href = '../home/'">
        <h2 id="title-h2"><?php echo $title; ?></h2>
        <h1 id="subtitle-h1"><?php echo $subtitle; ?></h1>
      </div>
    </div>
    <div class="header-elmnt" id="header-account">
      <button id="account-btn" onmouseover="accountBtn('over')" onmouseleave="accountBtn('leave')">
        <?php
          if ($sign != "yes") {
        ?>
          <div id="account-details-wrp">
            <img src="../uni/images/profile-image.png" alt="default profile image" id="account-img">
            <p id="account-txt"><?php echo $wrd_signIn; ?></p>
          </div>
        <?php
          } else {
        ?>
          <div id="account-details-wrp">
            <img src="../<?php echo $smallImg; ?>" alt="profile image" id="account-img">
            <p id="account-txt"><?php echo $setfirstname; ?></p>
          </div>
        <?php
          }
        ?>
        <span id="account-drop-btn" onclick="accountDrop('toggle')"></span>
      </button>
      <div id="account-drop-wrp">
        <div id="account-drop-blck">
          <?php
            if ($sign != "yes") {
          ?>
            <button class="account-drop-btn account-drop-btn-none" onclick="signInModal('show')"><?php echo $wrd_signIn; ?></button>
            <button class="account-drop-btn account-drop-btn-none" onclick="signUpModal('show')"><?php echo $wrd_signUp; ?></button>
          <?php
            } else {
          ?>
            <div id="account-drop-acc-links">
              <button class="account-drop-btn account-drop-btn-none" onclick="location.href = '../user/?id=<?php echo $setid; ?>&section=about'"><?php echo $wrd_aboutYou; ?></button>
              <button class="account-drop-btn account-drop-btn-none" onclick="location.href = '../user/?id=<?php echo $setid; ?>&section=huts'"><?php echo $wrd_myHuts; ?></button>
            </div>
            <button class="account-drop-btn account-drop-btn-arrow" onclick="toggSet('show', 'public-data')"><?php echo $wrd_profileSet; ?></button>
            <button class="account-drop-btn account-drop-btn-arrow" onclick="toggSet('show', 'prof-img')"><?php echo $wrd_profileImage; ?></button>
            <button class="account-drop-btn account-drop-btn-cancel" onclick="signOut()"><?php echo $wrd_signOut; ?></button>
          <?php
            }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="main-menu-cover">
  <div id="menu-fix-wrap">
    <div id="menu-wrap" class="hideMenu">
      <div id="menu-cancel-wrp">
        <button id="menu-cancel-btn" class="menu-btn" onclick="menu()"></button>
      </div>
      <div id="menu-content-wrp">
        <button class="menu-elmnt" onclick="location.href = '../home/'">
          <img src="../uni/icons/home.svg">
          <p><?php echo $wrd_home; ?></p>
        </button>
        <?php
          if ($sign == "yes") {
        ?>
          <button class="menu-elmnt" onclick="location.href = '../bookings/my-bookings.php'">
            <img src="../uni/icons/huts.svg">
            <p><?php echo $wrd_myBookings; ?></p>
          </button>
          <button class="menu-elmnt" onclick="location.href = '../user/?id=<?php echo $setid; ?>&section=about'">
            <img src="../uni/icons/about2.svg">
            <p><?php echo $wrd_profile; ?></p>
          </button>
          <button class="menu-elmnt" onclick="location.href = '../user/?id=<?php echo $setid; ?>&section=huts'">
            <img src="../uni/icons/keys.svg">
            <p><?php echo $wrd_myHuts; ?></p>
          </button>
        <?php
          } else {
        ?>
          <button class="menu-elmnt" onclick="signInModal('show');menu()">
            <img src="../uni/icons/padlock3.svg">
            <p><?php echo $wrd_signIn; ?></p>
          </button>
          <button class="menu-elmnt" onclick="signUpModal('show');menu()">
            <img src="../uni/icons/add.svg">
            <p><?php echo $wrd_signUp; ?></p>
          </button>
        <?php
          }
          if ($bnft_add_cottage == "good") {
        ?>
          <button class="menu-elmnt" onclick="newCottageModal('show')">
            <img src="../uni/icons/add.svg">
            <p><?php echo $wrd_addHut; ?></p>
          </button>
        <?php
          }
          if ($bnft_edit_cottage == "good") {
        ?>
          <button class="menu-elmnt" onclick="toggEditorModal('show')">
            <img src="../uni/icons/edit.svg">
            <p><?php echo $wrd_editor; ?></p>
          </button>
        <?php
          }
          if ($bnft_add_cottage == "good" && $sign == "yes") {
        ?>
            <button class="menu-elmnt" onclick="location.href = '../bookings/guest-list.php'">
              <img src="../uni/icons/guest-with-a-suitcase.svg">
              <p><?php echo $wrd_guestList; ?></p>
            </button>

            <button class="menu-elmnt" onclick="location.href = '../fees/'">
              <img src="../uni/icons/dollar.svg">
              <p><?php echo $wrd_fees; ?></p>
            </button>
        <?php
          }
        ?>
        <button class="menu-elmnt" onclick="location.href = '../support/'">
          <img src="../uni/icons/help.svg">
          <p><?php echo $wrd_support; ?></p>
        </button>
        <button class="menu-elmnt" onclick="toggSet('show', 'none')">
          <img src="../uni/icons/settings.svg">
          <p><?php echo $wrd_settings; ?></p>
        </button>
        <br>
        <?php
          if ($sign == "yes") {
            $sqlBDAdminsCheck = $linkBD->query("SELECT * FROM admins WHERE beid='$usrBeId'");
            if ($sqlBDAdminsCheck->num_rows > 0) {
        ?>
            <button class="menu-elmnt" onclick="location.href = '../backdoor/'">
              <img src="../uni/icons/access.svg">
              <p><?php echo "Backdoor"; ?></p>
            </button>
        <?php
            }
          }
        ?>
        <button class="menu-elmnt" onclick="toggSet('show', 'lang')">
          <img src="../uni/icons/langs/<?php echo $wrd_shrt; ?>.svg" class="lang-img">
          <p><?php echo $wrd_lang; ?></p>
        </button>
      </div>
    </div>
  </div>
</div>
