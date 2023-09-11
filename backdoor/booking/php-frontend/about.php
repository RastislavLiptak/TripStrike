<div class="details-page-content-txt-blck">
  <div class="details-page-content-txt-blck-title-wrp">
    <p class="content-subtitle"><?php echo $wrd_basicInfo; ?></p>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo $wrd_status.":"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <?php
          if ($bookingStatus == "") {
        ?>
            <p class="content-text"><b><?php echo $wrd_unknown." (empty)"; ?></b></p>
        <?php
          } else if ($bookingStatus == "-") {
        ?>
            <p class="content-text"><b><?php echo $wrd_unknown." (-)"; ?></b></p>
        <?php
          } else if ($bookingStatus == "rejected") {
        ?>
            <p class="content-text"><b><?php echo $wrd_rejected; ?></b></p>
        <?php
          } else if ($bookingStatus == "canceled") {
        ?>
            <p class="content-text"><b><?php echo $wrd_canceled; ?></b></p>
        <?php
          } else if ($bookingStatus == "waiting") {
        ?>
            <p class="content-text"><b><?php echo $wrd_waitingForConfirmation; ?></b></p>
        <?php
          } else if ($bookingStatus == "booked") {
        ?>
            <p class="content-text"><b><?php echo $wrd_booked; ?></b></p>
        <?php
          } else if ($bookingStatus == "paid") {
        ?>
            <p class="content-text"><b><?php echo $wrd_paid; ?></b></p>
        <?php
          } else if ($bookingStatus == "unpaid") {
        ?>
            <p class="content-text"><b><?php echo $wrd_unpaid; ?></b></p>
        <?php
          }
        ?>
      </div>
    </div>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo $wrd_from.":"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <?php
          if ($bookArch["firstday"] == "whole") {
        ?>
            <p class="content-text"><b><?php echo $bookArch["fromd"].".".$bookArch["fromm"].".".$bookArch["fromy"]." (".$wrd_theWholeDay.")"; ?></b></p>
        <?php
          } else {
        ?>
            <p class="content-text"><b><?php echo $bookArch["fromd"].".".$bookArch["fromm"].".".$bookArch["fromy"]." (".$wrd_from." 14:00)"; ?></b></p>
        <?php
          }
        ?>
      </div>
    </div>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo $wrd_to.":"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <?php
          if ($bookArch["lastday"] == "whole") {
        ?>
            <p class="content-text"><b><?php echo $bookArch["tod"].".".$bookArch["tom"].".".$bookArch["toy"]." (".$wrd_theWholeDay.")"; ?></b></p>
        <?php
          } else {
        ?>
            <p class="content-text"><b><?php echo $bookArch["tod"].".".$bookArch["tom"].".".$bookArch["toy"]." (".$wrd_to." 11:00)"; ?></b></p>
        <?php
          }
        ?>
      </div>
    </div>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo $wrd_guestNum.":"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <p class="content-text"><b><?php echo $bookArch["guestnum"]; ?></b></p>
      </div>
    </div>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo $wrd_totalPrice.":"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <p class="content-text"><b><?php echo addCurrency($bookArch['currency'], $bookArch['totalprice']); ?></b></p>
      </div>
    </div>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo $wrd_fee.":"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <p class="content-text"><b><?php echo addCurrency($bookArch['currency'], $bookArch['fee']); ?></b></p>
      </div>
    </div>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo $wrd_feeInPercent.":"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <p class="content-text"><b><?php echo ($bookArch['percentagefee'] + 0)."%"; ?></b></p>
      </div>
    </div>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo $wrd_feePaymentStatus.":"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <?php
          if ($bookingPaymentStatus == "1") {
        ?>
            <p class="content-text"><b id="b-d-booking-about-payment-status"><?php echo $wrd_paid; ?></b></p>
        <?php
          } else if ($bookingPaymentStatus == "0") {
        ?>
            <p class="content-text"><b id="b-d-booking-about-payment-status"><?php echo $wrd_unpaid; ?></b></p>
        <?php
          } else {
        ?>
            <p class="content-text"><b id="b-d-booking-about-payment-status"><?php echo $wrd_unknown." (".$bookingPaymentStatus.")"; ?></b></p>
        <?php
          }
        ?>
      </div>
    </div>
  </div>
</div>
<div class="details-page-content-txt-blck">
  <div class="details-page-content-txt-blck-title-wrp">
    <p class="content-subtitle"><?php echo $wrd_place; ?></p>
    <?php
      if (getFrontendId($plcBeId) != "none") {
    ?>
        <a href="../../place/?id=<?php echo getFrontendId($plcBeId); ?>" target="_blank" class="details-page-content-txt-blck-title-link details-page-content-txt-blck-title-link-blue"><?php echo $wrd_public ?></a>
    <?php
      }
    ?>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo "ID: "; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <p class="content-text"><b><?php echo getFrontendId($plcBeId); ?></b></p>
      </div>
    </div>
  </div>
  <?php
    if ($placeStatus == "not-exist") {
  ?>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><i><?php echo $wrd_notFoundInDatabase.":"; ?></i></p>
      </div>
  <?php
    } else {
  ?>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><?php echo $wrd_name.":"; ?></p>
        <div class="details-page-content-txt-size">
          <div class="details-page-content-txt-value">
            <p class="content-text"><b><?php echo $plc['name']; ?></b></p>
          </div>
        </div>
      </div>
  <?php
      if ($placeStatus == "good") {
  ?>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><?php echo $wrd_priceMode.":"; ?></p>
        <div class="details-page-content-txt-size">
          <div class="details-page-content-txt-value">
            <?php
              if ($bookArch['plcpricemode'] == "nights") {
            ?>
                <p class="content-text"><b><?php echo $wrd_pricePerNight; ?></b></p>
            <?php
              } else {
            ?>
                <p class="content-text"><b><?php echo $wrd_pricePerNightForOneGuest; ?></b></p>
            <?php
              }
            ?>
          </div>
        </div>
      </div>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><?php echo $wrd_priceOnWorkingDays.":"; ?></p>
        <div class="details-page-content-txt-size">
          <div class="details-page-content-txt-value">
            <p class="content-text"><b><?php echo addCurrency($bookArch['currency'], $bookArch['plcworkprice']); ?></b></p>
          </div>
        </div>
      </div>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><?php echo $wrd_weekendPrice.":"; ?></p>
        <div class="details-page-content-txt-size">
          <div class="details-page-content-txt-value">
            <p class="content-text"><b><?php echo addCurrency($bookArch['currency'], $bookArch['plcweekprice']); ?></b></p>
          </div>
        </div>
      </div>
  <?php
      } else {
  ?>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><?php echo $wrd_status.":"; ?></p>
        <div class="details-page-content-txt-size">
          <div class="details-page-content-txt-value">
            <p class="content-text"><b><?php echo $placeStatus; ?></b></p>
          </div>
        </div>
      </div>
  <?php
      }
    }
  ?>
</div>
<div class="details-page-content-txt-blck">
  <div class="details-page-content-txt-blck-title-wrp">
    <p class="content-subtitle"><?php echo $wrd_host; ?></p>
    <?php
      if (getFrontendId($hostBeId) != "none") {
    ?>
        <a href="../../user/?id=<?php echo getFrontendId($hostBeId); ?>&section=about" target="_blank" class="details-page-content-txt-blck-title-link details-page-content-txt-blck-title-link-blue"><?php echo $wrd_public ?></a>
        <a href="../user/?section=users&nav=about&id=<?php echo getFrontendId($hostBeId); ?>&m=&y=" target="_blank" class="details-page-content-txt-blck-title-link details-page-content-txt-blck-title-link-red">BackDoor</a>
    <?php
      }
    ?>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo "ID: "; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <p class="content-text"><b><?php echo getFrontendId($hostBeId); ?></b></p>
      </div>
    </div>
  </div>
  <?php
    if ($hostStatus == "good") {
  ?>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><?php echo $wrd_firstName.":"; ?></p>
        <div class="details-page-content-txt-size">
          <div class="details-page-content-txt-value">
            <p class="content-text"><b><?php echo $hst['firstname']; ?></b></p>
          </div>
        </div>
      </div>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><?php echo $wrd_lastName.":"; ?></p>
        <div class="details-page-content-txt-size">
          <div class="details-page-content-txt-value">
            <p class="content-text"><b><?php echo $hst['lastname']; ?></b></p>
          </div>
        </div>
      </div>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><?php echo $wrd_contactEmail.":"; ?></p>
        <div class="details-page-content-txt-size">
          <div class="details-page-content-txt-value">
            <p class="content-text"><b><?php echo $hst['contactemail']; ?></b></p>
          </div>
        </div>
      </div>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><?php echo $wrd_contactPhoneNumber.":"; ?></p>
        <div class="details-page-content-txt-size">
          <div class="details-page-content-txt-value">
            <p class="content-text"><b><?php echo $hst['contactphonenum']; ?></b></p>
          </div>
        </div>
      </div>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><?php echo $wrd_language.":"; ?></p>
        <div class="details-page-content-txt-size">
          <div class="details-page-content-txt-value">
            <p class="content-text"><b><?php echo $hst['language']; ?></b></p>
          </div>
        </div>
      </div>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><?php echo $wrd_bankAccount.":"; ?></p>
        <div class="details-page-content-txt-size">
          <div class="details-page-content-txt-value">
            <p class="content-text"><b><?php echo $hst['bankaccount']; ?></b></p>
          </div>
        </div>
      </div>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><?php echo "IBAN: "; ?></p>
        <div class="details-page-content-txt-size">
          <div class="details-page-content-txt-value">
            <p class="content-text"><b><?php echo $hst['iban']; ?></b></p>
          </div>
        </div>
      </div>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><?php echo "BIC/SWIFT: "; ?></p>
        <div class="details-page-content-txt-size">
          <div class="details-page-content-txt-value">
            <p class="content-text"><b><?php echo $hst['bicswift']; ?></b></p>
          </div>
        </div>
      </div>
  <?php
    } else if ($hostStatus == "not-exist") {
  ?>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><i><?php echo $wrd_notFoundInDatabase.":"; ?></i></p>
      </div>
  <?php
    } else {
  ?>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><?php echo $wrd_status.":"; ?></p>
        <div class="details-page-content-txt-size">
          <div class="details-page-content-txt-value">
            <p class="content-text"><b><?php echo $hostStatus; ?></b></p>
          </div>
        </div>
      </div>
  <?php
    }
  ?>
</div>
<div class="details-page-content-txt-blck">
  <div class="details-page-content-txt-blck-title-wrp">
    <p class="content-subtitle"><?php echo $wrd_capitalGuest; ?></p>
    <?php
      if ($gstID != "" && $gstID != "-" && $gstID != "none") {
    ?>
      <a href="../../user/?id=<?php echo $gstID; ?>&section=about" target="_blank" class="details-page-content-txt-blck-title-link details-page-content-txt-blck-title-link-blue"><?php echo $wrd_public ?></a>
      <a href="../user/?section=users&nav=about&id=<?php echo $gstID; ?>&m=&y=" target="_blank" class="details-page-content-txt-blck-title-link details-page-content-txt-blck-title-link-red">BackDoor</a>
    <?php
      }
    ?>
  </div>
  <?php
    if ($gstID != "" && $gstID != "-" && $gstID != "none") {
  ?>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><?php echo "ID: "; ?></p>
        <div class="details-page-content-txt-size">
          <div class="details-page-content-txt-value">
            <p class="content-text"><b><?php echo $gstID; ?></b></p>
          </div>
        </div>
      </div>
  <?php
    }
    if ($gstStatus == "good") {
  ?>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><?php echo $wrd_name.":"; ?></p>
        <div class="details-page-content-txt-size">
          <div class="details-page-content-txt-value">
            <p class="content-text"><b><?php echo $gstName; ?></b></p>
          </div>
        </div>
      </div>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><?php echo $wrd_email.":"; ?></p>
        <div class="details-page-content-txt-size">
          <div class="details-page-content-txt-value">
            <p class="content-text"><b><?php echo $gstEmail; ?></b></p>
          </div>
        </div>
      </div>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><?php echo $wrd_phoneNumber.":"; ?></p>
        <div class="details-page-content-txt-size">
          <div class="details-page-content-txt-value">
            <p class="content-text"><b><?php echo $gstPhone; ?></b></p>
          </div>
        </div>
      </div>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><?php echo $wrd_language.":"; ?></p>
        <div class="details-page-content-txt-size">
          <div class="details-page-content-txt-value">
            <p class="content-text"><b><?php echo $gstLanguage; ?></b></p>
          </div>
        </div>
      </div>
  <?php
    } else if ($gstStatus == "not-exist") {
  ?>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><i><?php echo $wrd_notFoundInDatabase.":"; ?></i></p>
      </div>
  <?php
    } else {
  ?>
      <div class="details-page-content-txt-row">
        <p class="content-text content-text-colored"><?php echo $wrd_status.":"; ?></p>
        <div class="details-page-content-txt-size">
          <div class="details-page-content-txt-value">
            <p class="content-text"><b><?php echo $gstStatus; ?></b></p>
          </div>
        </div>
      </div>
  <?php
    }
   ?>
</div>
<div class="details-page-content-txt-blck">
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo $wrd_bookingCreationDate.":"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <p class="content-text"><b><?php echo $bookArch['fulldate']; ?></b></p>
      </div>
    </div>
  </div>
  <div class="details-page-content-txt-row">
    <p class="content-text content-text-colored"><?php echo $wrd_bookingSource.":"; ?></p>
    <div class="details-page-content-txt-size">
      <div class="details-page-content-txt-value">
        <?php
          if ($bookArch['source'] == "booking-form") {
        ?>
            <p class="content-text"><b><?php echo $wrd_bookingForm; ?></b></p>
        <?php
          } else if ($bookArch['source'] == "editor") {
        ?>
            <p class="content-text"><b><?php echo $wrd_bookingEditor; ?></b></p>
        <?php
          }
        ?>
      </div>
    </div>
  </div>
</div>
