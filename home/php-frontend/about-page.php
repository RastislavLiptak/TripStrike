<?php
  include "../libraries/PHPExcel-1.8/Classes/PHPExcel.php";
  $employeesListFile = "../excel/list-of-employees.xlsx";
  $employeesListRender =  PHPExcel_IOFactory::createReaderForFile($employeesListFile);
  $employeesListExcel_Obj = $employeesListRender->load($employeesListFile);
  $employeesListWorksheet = $employeesListExcel_Obj->getSheet('0');
  $employeesListLastRow = $employeesListWorksheet->getHighestRow();
?>
<div id="home-about-page-wrp">
  <div class="home-about-card">
    <div class="home-about-icon-wrp">
      <div class="home-about-icon" id="home-about-icon-about-us"></div>
    </div>
    <div class="home-about-txt-wrp">
      <h2 class="home-about-title"><?php echo $wrd_whoWeAre; ?></h2>
      <p class="home-about-desc"><?php echo $title." ".$wrd_whoWeAreDesc1; ?></p>
    </div>
    <div class="home-about-btn-wrp">
      <button type="button" class="home-about-btn" onclick="modCover('show', 'modal-cover-about-page')"><?php echo $wrd_learnMore; ?></button>
    </div>
  </div>
  <div class="home-about-card">
    <div class="home-about-icon-wrp">
      <div class="home-about-icon" id="home-about-icon-place"></div>
    </div>
    <?php
      if ($bnft_add_cottage == "good") {
    ?>
        <div class="home-about-txt-wrp">
          <h2 class="home-about-title"><?php echo $wrd_addYourCottage; ?></h2>
          <p class="home-about-desc"><?php echo $wrd_dataAboutYourCottageCanBeAdded; ?></p>
        </div>
        <div class="home-about-btn-wrp">
          <button type="button" class="home-about-btn home-about-btn-main" onclick="newCottageModal('show')"><?php echo $wrd_addHut; ?></button>
        </div>
    <?php
      } else if ($bnft_add_cottage == "none") {
    ?>
        <div class="home-about-txt-wrp">
          <h2 class="home-about-title"><?php echo $wrd_addYourCottage; ?></h2>
          <p class="home-about-desc"><?php echo $wrd_hostConnotBeAnyoneAskForPermission; ?></p>
        </div>
        <div class="home-about-btn-wrp">
          <button type="button" class="home-about-btn" onclick="modCover('show', 'modal-cover-about-page-add-your-cottage')"><?php echo $wrd_askForPermission; ?></button>
        </div>
    <?php
      } else if ($bnft_add_cottage == "ban") {
    ?>
        <div class="home-about-txt-wrp">
          <h2 class="home-about-title"><?php echo $wrd_addYourCottage; ?></h2>
          <p class="home-about-desc"><?php echo $wrd_youHaveBeenBannedThisFeatureIfAnErrorHasOccurredOrWantYouToKnowMoreContactUs; ?></p>
        </div>
    <?php
      } else {
    ?>
        <div class="home-about-txt-wrp">
          <h2 class="home-about-title"><?php echo $wrd_addYourCottage; ?></h2>
          <p class="home-about-desc"><?php echo $wrd_weWereUnableToDetermineWithCertaintyWhetherOrNotYouHaveThisFeatureEnabledThereforePleaseContactUsOrFillInAndSendTheApplicationAgainWeApologizeForTheInconvenience; ?></p>
        </div>
        <div class="home-about-btn-wrp">
          <button type="button" class="home-about-btn" onclick="modCover('show', 'modal-cover-about-page-add-your-cottage')"><?php echo $wrd_askForPermission; ?></button>
        </div>
    <?php
      }
    ?>
  </div>
  <div class="home-about-card">
    <div class="home-about-icon-wrp">
      <div class="home-about-icon" id="home-about-icon-booking-mechanism"></div>
    </div>
    <div class="home-about-txt-wrp">
      <h2 class="home-about-title"><?php echo $wrd_howTheBookingSystemWorks; ?></h2>
      <p class="home-about-desc"><?php echo $wrd_howTheBookingSystemWorksDesc1; ?></p>
    </div>
    <div class="home-about-btn-wrp">
      <button type="button" class="home-about-btn" onclick="modCover('show', 'modal-cover-booking-mechanism')"><?php echo $wrd_moreDetails; ?></button>
    </div>
  </div>
</div>
<!-- who we are? -->
<div class="modal-cover" id="modal-cover-about-page">
  <div class="modal-block" id="modal-cover-about-page-blck">
    <button class="cancel-btn" onclick="modCover('hide', 'modal-cover-about-page')"></button>
    <div class="modal-home-about-page-content-wrp" id="modal-home-about-page-content-wrp-about-page">
      <h2 class="modal-home-about-page-title"><?php echo $wrd_whoWeAre; ?></h2>
      <p class="modal-home-about-page-desc"><?php echo $title." ".$wrd_whoWeAreDesc2; ?></p>
      <br>
      <p class="modal-home-about-page-desc"><?php echo $wrd_whoWeAreDesc3; ?></p>
      <br>
      <p class="modal-home-about-page-desc"><?php echo $wrd_whoWeAreDesc4 ?> <a href="../support/" target="_blank"><?php echo $wrd_whoWeAreDesc5; ?></a><?php echo $wrd_whoWeAreDesc6; ?></p>
      <div id="home-about-page-employees-wrp">
        <h2 class="modal-home-about-page-title"><?php echo $wrp_ourTeam; ?></h2>
        <div id="home-about-page-employees-grid">
          <?php
            for ($row = 2;$row <= $employeesListLastRow;$row++) {
          ?>
            <div class="home-about-page-employees-card">
              <div class="home-about-page-employees-img-wrp">
                <img src="../uni/images/employees/<?php echo $employeesListWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(4).$row)->getValue(); ?>" alt="<?php echo $employeesListWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(2).$row)->getValue()." image"; ?>" class="home-about-page-employees-img">
              </div>
              <div class="home-about-page-employees-txt-wrp">
                <div class="home-about-page-employees-txt-about">
                  <p class="home-about-page-employees-name"><?php echo $employeesListWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(0).$row)->getValue()." ".$employeesListWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(1).$row)->getValue(); ?></p>
                  <p class="home-about-page-employees-position"><?php echo $employeesListWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(2).$row)->getValue(); ?></p>
                </div>
                <div class="home-about-page-employees-txt-contacts">
                  <a class="home-about-page-employees-email" href="mailto:<?php echo $employeesListWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(3).$row)->getValue(); ?>" target="_blank"><?php echo $employeesListWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(3).$row)->getValue(); ?></a>
                </div>
              </div>
            </div>
          <?php
            }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Add your cottage -->
<div class="modal-cover" id="modal-cover-about-page-add-your-cottage">
  <div class="modal-block" id="modal-cover-about-page-add-your-cottage-blck">
    <button class="cancel-btn" onclick="modCover('hide', 'modal-cover-about-page-add-your-cottage')"></button>
    <div class="modal-home-about-page-content-wrp" id="modal-home-about-page-content-wrp-about-page-add-your-cottage">
      <h2 class="modal-home-about-page-title"><?php echo $wrd_requestPermissionLoAddACottage; ?></h2>
      <form id="home-about-page-add-your-cottage-form" onsubmit="aboutPageAddYourCottageSend(event)">
        <div class="home-about-page-add-your-cottage-form-row">
          <div class="home-about-page-add-your-cottage-form-input-wrp">
            <p class="home-about-page-add-your-cottage-form-input-txt"><?php echo $wrd_firstName; ?>:</p>
            <input type="text" name="firstname" value="<?php echo htmlspecialchars($setfirstname); ?>" class="home-about-page-add-your-cottage-form-input" id="home-about-page-add-your-cottage-form-input-firstname">
          </div>
        </div>
        <div class="home-about-page-add-your-cottage-form-row">
          <div class="home-about-page-add-your-cottage-form-input-wrp">
            <p class="home-about-page-add-your-cottage-form-input-txt"><?php echo $wrd_lastName; ?>:</p>
            <input type="text" name="lastname" value="<?php echo htmlspecialchars($setlastname); ?>" class="home-about-page-add-your-cottage-form-input" id="home-about-page-add-your-cottage-form-input-lastname">
          </div>
        </div>
        <div class="home-about-page-add-your-cottage-form-row">
          <div class="home-about-page-add-your-cottage-form-input-wrp">
            <p class="home-about-page-add-your-cottage-form-input-txt"><?php echo $wrd_contactEmail; ?>:</p>
            <input type="text" name="cantact email" value="<?php echo $setcontactemail; ?>" class="home-about-page-add-your-cottage-form-input" id="home-about-page-add-your-cottage-form-input-contact-email">
          </div>
        </div>
        <h3 class="home-about-page-add-your-cottage-form-title"><?php echo $wrd_cottageDescription; ?></h3>
        <div class="home-about-page-add-your-cottage-form-row">
          <div class="home-about-page-add-your-cottage-form-input-wrp">
            <p class="home-about-page-add-your-cottage-form-input-txt"><?php echo $wrd_address." / ".$wrd_coordinates; ?>:</p>
            <input type="text" name="adress" class="home-about-page-add-your-cottage-form-input" id="home-about-page-add-your-cottage-form-input-address">
          </div>
        </div>
        <div class="home-about-page-add-your-cottage-form-row">
          <div class="home-about-page-add-your-cottage-form-input-wrp">
            <p class="home-about-page-add-your-cottage-form-input-txt"><?php echo $wrd_country; ?>:</p>
            <input type="text" name="" class="home-about-page-add-your-cottage-form-input" id="home-about-page-add-your-cottage-form-input-country">
          </div>
        </div>
        <div class="home-about-page-add-your-cottage-form-row">
          <h3 class="home-about-page-add-your-cottage-form-title"><?php echo $wrd_notes; ?></h3>
          <textarea class="home-about-page-add-your-cottage-form-textarea" id="home-about-page-add-your-cottage-form-input-notes" placeholder="<?php echo $wrd_type; ?>"></textarea>
        </div>
        <div id="home-about-page-add-your-cottage-form-error-wrp">
          <p id="home-about-page-add-your-cottage-form-error-txt"></p>
        </div>
        <div id="home-about-page-add-your-cottage-form-footer">
          <button type="submit" class="btn btn-mid btn-prim" id="home-about-page-add-your-cottage-form-footer-submit"><?php echo $wrd_send; ?></button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal-cover" id="modal-cover-about-page-add-your-cottage-thanks">
  <div class="modal-block" id="modal-cover-about-page-add-your-cottage-thanks-blck">
    <button class="cancel-btn" onclick="modCover('hide', 'modal-cover-about-page-add-your-cottage-thanks')"></button>
    <div class="modal-home-about-page-content-wrp" id="modal-home-about-page-content-wrp-about-page-add-your-cottage-thanks">
      <p class="modal-home-about-page-desc"><?php echo $wrd_thankYouForYourInterestInThisProjectWeWillRespondToYourRequestAsSoonAsPossibleAndLetYouKnowIfYouHaveNotPassedOtherwiseYouWillReceiveInstructionsOnHowToActivateTheFeature; ?></p>
    </div>
  </div>
</div>
<!-- How the reservation system works? -->
<div class="modal-cover" id="modal-cover-booking-mechanism">
  <div class="modal-block" id="modal-cover-booking-mechanism-blck">
    <button class="cancel-btn" onclick="modCover('hide', 'modal-cover-booking-mechanism')"></button>
    <div class="modal-home-about-page-content-wrp" id="modal-home-about-page-content-wrp-booking-mechanism">
      <h2 class="modal-home-about-page-title"><?php echo $wrd_howTheBookingSystemWorks; ?></h2>
      <h3 class="modal-home-about-page-subtitle"><?php echo $wrd_hosts; ?></h3>
      <p class="modal-home-about-page-desc modal-home-about-page-desc-bold"><?php echo $wrd_creationOfBooking; ?></p>
      <p class="modal-home-about-page-desc"><?php echo $wrd_howTheBookingSystemWorksDesc2; ?></p>
      <br>
      <p class="modal-home-about-page-desc modal-home-about-page-desc-bold"><?php echo $wrd_bookingManagement; ?></p>
      <p class="modal-home-about-page-desc"><?php echo $wrd_howTheBookingSystemWorksDesc3; ?></p>
      <br>
      <p class="modal-home-about-page-desc"><?php echo $wrd_howTheBookingSystemWorksDesc4; ?></p>
      <br>
      <p class="modal-home-about-page-desc modal-home-about-page-desc-bold"><?php echo $wrd_bookingCancel; ?></p>
      <p class="modal-home-about-page-desc"><?php echo $wrd_howTheBookingSystemWorksDesc5; ?></p>
      <br>
      <br>
      <h3 class="modal-home-about-page-subtitle"><?php echo $wrd_guestsCapital; ?></h3>
      <p class="modal-home-about-page-desc modal-home-about-page-desc-bold"><?php echo $wrd_creationOfBooking; ?></p>
      <p class="modal-home-about-page-desc"><?php echo $wrd_howTheBookingSystemWorksDesc6; ?></p>
      <br>
      <p class="modal-home-about-page-desc modal-home-about-page-desc-bold"><?php echo $wrd_bookingManagement; ?></p>
      <p class="modal-home-about-page-desc"><?php echo $wrd_howTheBookingSystemWorksDesc7; ?></p>
      <br>
      <p class="modal-home-about-page-desc modal-home-about-page-desc-bold"><?php echo $wrd_bookingCancel; ?></p>
      <p class="modal-home-about-page-desc"><?php echo $wrd_howTheBookingSystemWorksDesc8; ?></p>
      <br>
      <br>
      <h3 class="modal-home-about-page-subtitle"><?php echo $wrd_automaticProcesses; ?></h3>
      <p class="modal-home-about-page-desc"><?php echo $wrd_howTheBookingSystemWorksDesc9; ?></p>
      <br>
      <p class="modal-home-about-page-desc"><?php echo $wrd_howTheBookingSystemWorksDesc10; ?></p>
      <br>
      <p class="modal-home-about-page-desc"><?php echo $wrd_howTheBookingSystemWorksDesc11; ?></p>
      <br>
      <p class="modal-home-about-page-desc"><?php echo $wrd_howTheBookingSystemWorksDesc12; ?></p>
      <br>
      <p class="modal-home-about-page-desc"><?php echo $wrd_howTheBookingSystemWorksDesc13; ?></p>
      <br>
      <br>
      <h3 class="modal-home-about-page-subtitle"><?php echo $wrd_bookingFees; ?></h3>
      <p class="modal-home-about-page-desc"><?php echo $wrd_howTheBookingSystemWorksDesc14; ?></p>
      <br>
      <p class="modal-home-about-page-desc"><?php echo $wrd_howTheBookingSystemWorksDesc15; ?></p>
      <br>
      <p class="modal-home-about-page-desc"><?php echo $wrd_howTheBookingSystemWorksDesc16; ?></p>
    </div>
  </div>
</div>
