<form class="n-c-layout" id="n-c-layout-details">
  <div class="n-c-error-wrp">
    <p class="n-c-error-txt" id="n-c-error-txt-details"></p>
  </div>
  <h2 class="n-c-title"><?php echo $wrd_details; ?></h2>

  <div class="n-c-details-row" id="n-c-type-wrp">
    <div class="n-c-select-blck" id="n-c-select-blck-type">
      <p class="n-c-content-row-title"><?php echo $wrd_typeOfAccommodation; ?>:</p>
      <select class="n-c-select n-c-select-details" id="n-c-select-type">
        <option class="n-c-select-option n-c-select-option-type" value="cottage" selected><?php echo $wrd_cottage; ?></option>
        <option class="n-c-select-option n-c-select-option-type" value="camp"><?php echo $wrd_camp; ?></option>
        <option class="n-c-select-option n-c-select-option-type" value="guesthouse"><?php echo $wrd_guesthouse; ?></option>
        <option class="n-c-select-option n-c-select-option-type" value="hotel"><?php echo $wrd_hotel; ?></option>
        <option class="n-c-select-option n-c-select-option-type" value="apartment"><?php echo $wrd_apartment; ?></option>
      </select>
    </div>
  </div>

  <div class="n-c-details-row" id="n-c-number-of-wrp">
    <div class="n-c-number-of-blck">
      <p class="n-c-content-row-title"><?php echo $wrd_numBedrooms; ?>:</p>
      <input type="number" class="n-c-input-number n-c-input-number-details" id="n-c-input-number-details-bedrooms" value="1" min="1">
    </div>
    <div class="n-c-number-of-blck">
      <p class="n-c-content-row-title"><?php echo $wrd_maxGuestNum; ?>:</p>
      <input type="number" class="n-c-input-number n-c-input-number-details" id="n-c-input-number-details-guests" value="1" min="1">
    </div>
    <div class="n-c-number-of-blck">
      <p class="n-c-content-row-title"><?php echo $wrd_numberOfBathrooms; ?>:</p>
      <input type="number" class="n-c-input-number n-c-input-number-details" id="n-c-input-number-details-bathrooms" value="1" min="1">
    </div>
  </div>
  <div class="n-c-details-row" id="n-c-distance-from-the-water-wrp">
    <div class="n-c-number-of-blck" id="n-c-number-of-blck-distance-from-the-water">
      <p class="n-c-content-row-title"><?php echo $wrd_distanceFromTheWater; ?>:</p>
      <div id="n-c-distance-from-the-water-inpt-wrp">
        <input type="number" class="n-c-input-number n-c-input-number-details" id="n-c-input-number-details-distance-from-the-water" value="" min="0">
        <p class="n-c-content-row-txt"><?php echo $wrd_meters; ?></p>
      </div>
    </div>
  </div>
  <div class="n-c-details-row" id="n-c-operation-wrp">
    <div class="n-c-select-blck" id="n-c-select-blck-operation">
      <p class="n-c-content-row-title"><?php echo $wrd_operation; ?>:</p>
      <select class="n-c-select n-c-select-details" id="n-c-select-operation" onchange="ncDetailsOperationOnchange(this)">
        <option class="n-c-select-option n-c-select-option-operation" value="year-round" selected><?php echo $wrd_yearRound; ?></option>
        <option class="n-c-select-option n-c-select-option-operation" value="summer"><?php echo $wrd_summerSeason; ?></option>
        <option class="n-c-select-option n-c-select-option-operation" value="winter"><?php echo $wrd_winterSeason; ?></option>
      </select>
    </div>
    <!-- summer -->
    <div class="n-c-select-blck n-c-select-blck-operation-season-select" id="n-c-select-blck-operation-season-select-from-summer">
      <p class="n-c-content-row-title n-c-content-row-title-sec"><?php echo $wrd_from; ?>:</p>
      <select class="n-c-select n-c-select-details-sml" id="n-c-select-operation-from-summer">
        <option class="n-c-select-option n-c-select-option-operation-from-summer" value="3"><?php echo $wrd_march; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-from-summer" value="4"><?php echo $wrd_april; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-from-summer" value="5" selected><?php echo $wrd_may; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-from-summer" value="6"><?php echo $wrd_june; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-from-summer" value="7"><?php echo $wrd_july; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-from-summer" value="8"><?php echo $wrd_august; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-from-summer" value="9"><?php echo $wrd_september; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-from-summer" value="10"><?php echo $wrd_october; ?></option>
      </select>
    </div>
    <div class="n-c-select-blck n-c-select-blck-operation-season-select" id="n-c-select-blck-operation-season-select-to-summer">
      <p class="n-c-content-row-title n-c-content-row-title-sec"><?php echo $wrd_to; ?>:</p>
      <select class="n-c-select n-c-select-details-sml" id="n-c-select-operation-to-summer">
        <option class="n-c-select-option n-c-select-option-operation-to-summer" value="3"><?php echo $wrd_march; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-to-summer" value="4"><?php echo $wrd_april; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-to-summer" value="5"><?php echo $wrd_may; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-to-summer" value="6"><?php echo $wrd_june; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-to-summer" value="7"><?php echo $wrd_july; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-to-summer" value="8"><?php echo $wrd_august; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-to-summer" value="9" selected><?php echo $wrd_september; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-to-summer" value="10"><?php echo $wrd_october; ?></option>
      </select>
    </div>
    <!-- winter -->
    <div class="n-c-select-blck n-c-select-blck-operation-season-select" id="n-c-select-blck-operation-season-select-from-winter">
      <p class="n-c-content-row-title n-c-content-row-title-sec"><?php echo $wrd_from; ?>:</p>
      <select class="n-c-select n-c-select-details-sml" id="n-c-select-operation-from-winter">
        <option class="n-c-select-option n-c-select-option-operation-from-winter" value="1"><?php echo $wrd_january; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-from-winter" value="2"><?php echo $wrd_february; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-from-winter" value="3"><?php echo $wrd_march; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-from-winter" value="4"><?php echo $wrd_april; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-from-winter" value="9"><?php echo $wrd_september; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-from-winter" value="10"><?php echo $wrd_october; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-from-winter" value="11" selected><?php echo $wrd_november; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-from-winter" value="12"><?php echo $wrd_december; ?></option>
      </select>
    </div>
    <div class="n-c-select-blck n-c-select-blck-operation-season-select" id="n-c-select-blck-operation-season-select-to-winter">
      <p class="n-c-content-row-title n-c-content-row-title-sec"><?php echo $wrd_to; ?>:</p>
      <select class="n-c-select n-c-select-details-sml" id="n-c-select-operation-to-winter">
        <option class="n-c-select-option n-c-select-option-operation-to-winter" value="1"><?php echo $wrd_january; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-to-winter" value="2" selected><?php echo $wrd_february; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-to-winter" value="3"><?php echo $wrd_march; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-to-winter" value="4"><?php echo $wrd_april; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-to-winter" value="9"><?php echo $wrd_september; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-to-winter" value="10"><?php echo $wrd_october; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-to-winter" value="11"><?php echo $wrd_november; ?></option>
        <option class="n-c-select-option n-c-select-option-operation-to-winter" value="12"><?php echo $wrd_december; ?></option>
      </select>
    </div>
  </div>
  <div class="n-c-details-row" id="n-c-equipment-wrp">
    <p class="n-c-content-row-title"><?php echo $wrd_equipment; ?>:</p>
    <div id="n-c-equipment-blck">
      <div id="n-c-equipment-list">
        <div id="n-c-equipment-add-btn-wrp">
          <button type="button" id="n-c-equipment-add-btn" onclick="plcEquipModal('show', 'new');"></button>
        </div>
      </div>
    </div>
  </div>
  <div class="n-c-details-row" id="n-c-url-id-wrp">
    <p id="n-c-url-id-txt"><?php echo $wrd_cottageId; ?></p>
    <?php
      $newCottIDReady = false;
      while (!$newCottIDReady) {
        $newCottID = randomHash(10);
        $sqlhID = $link -> query("SELECT * FROM idlist WHERE id='$newCottID'");
        if ($sqlhID->num_rows == 0) {
          $newCottIDReady = true;
        } else {
          $newCottIDReady = false;
        }
      }
    ?>
    <input type="text" placeholder="<?php echo $wrd_cottageIdTxt; ?>" maxlength="10" id="n-c-url-id-input" value="<?php echo $newCottID; ?>" onkeyup="ncDetailsIdCorrection();">
  </div>
</form>
