<div class="details-page-content-txt-blck">
  <div class="details-page-content-txt-blck-title-wrp">
    <p class="content-subtitle"><?php echo $wrd_basicInfo; ?></p>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo $wrd_status.":"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <?php
          if ($aboutUsr['status'] == "") {
        ?>
            <p class="content-text"><b><?php echo $wrd_unknown." (empty)"; ?></b></p>
        <?php
          } else if ($aboutUsr['status'] == "-") {
        ?>
            <p class="content-text"><b><?php echo $wrd_unknown." (-)"; ?></b></p>
        <?php
          } else if ($aboutUsr['status'] == "delete") {
        ?>
            <p class="content-text"><b><?php echo $wrd_deleted; ?></b></p>
        <?php
          } else if ($aboutUsr['status'] == "active") {
        ?>
            <p class="content-text"><b><?php echo $wrd_active; ?></b></p>
        <?php
          }
        ?>
      </div>
    </div>
  </div>
</div>
<div class="details-page-content-txt-blck">
  <div class="details-page-content-txt-blck-title-wrp">
    <p class="content-subtitle"><?php echo $wrd_publicData; ?></p>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo "ID: "; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <p class="content-text"><b><?php echo getFrontendId($userBeId); ?></b></p>
      </div>
    </div>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo $wrd_firstName.":"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <p class="content-text"><b><?php echo $aboutUsr['firstname']; ?></b></p>
      </div>
    </div>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo $wrd_lastName.":"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <p class="content-text"><b><?php echo $aboutUsr['lastname']; ?></b></p>
      </div>
    </div>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo $wrd_contactEmail.":"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <p class="content-text"><b><?php echo $aboutUsr['contactemail']; ?></b></p>
      </div>
    </div>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo $wrd_contactPhoneNumber.":"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <p class="content-text"><b><?php echo $aboutUsr['contactphonenum']; ?></b></p>
      </div>
    </div>
  </div>
</div>
<div class="details-page-content-txt-blck">
  <div class="details-page-content-txt-blck-title-wrp">
    <p class="content-subtitle"><?php echo $wrd_personalData; ?></p>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo $wrd_language.":"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <p class="content-text"><b><?php echo $aboutUsr['language']; ?></b></p>
      </div>
    </div>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo $wrd_bankAccountNumber.":"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <p class="content-text"><b><?php echo $aboutUsr['bankaccount']; ?></b></p>
      </div>
    </div>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo "IBAN:"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <p class="content-text"><b><?php echo $aboutUsr['iban']; ?></b></p>
      </div>
    </div>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo "BIC/SWIFT:"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <p class="content-text"><b><?php echo $aboutUsr['bicswift']; ?></b></p>
      </div>
    </div>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo $wrd_birth.":"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <p class="content-text"><b><?php echo $aboutUsr['birthd'].".".$aboutUsr['birthm'].".".$aboutUsr['birthy']; ?></b></p>
      </div>
    </div>
  </div>
</div>
<div class="details-page-content-txt-blck">
  <div class="details-page-content-txt-blck-title-wrp">
    <p class="content-subtitle"><?php echo $wrd_additionalInformation; ?></p>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo $wrd_description.":"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <p class="content-text"><b><?php echo nl2br($aboutUsr['description']); ?></b></p>
      </div>
    </div>
  </div>
</div>
<div class="details-page-content-txt-blck">
  <div class="details-page-content-txt-blck-title-wrp">
    <p class="content-subtitle"><?php echo $wrd_profileImage; ?></p>
  </div>
  <div class="details-page-content-txt-row">
    <div class="details-page-content-image-wrp">
      <img src="../../<?php echo getAccountData($userBeId, "big-profile-image"); ?>" alt="profile image" class="details-page-content-image">
    </div>
  </div>
</div>
<div class="details-page-content-txt-blck">
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo $wrd_userCreationDate.":"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <p class="content-text"><b><?php echo $aboutUsr['signupfulldate']; ?></b></p>
      </div>
    </div>
  </div>
</div>
