<div class="editor-content-section-wrp" id="editor-content-details-wrp">
  <div class="editor-details-sections">
    <div id="editor-details-options-grid">
      <div class="editor-details-options-grid-cell-blck">
        <div class="editor-details-options-grid-cell-layout">
          <div class="editor-details-options-grid-cell-txt-wrp">
            <p class="editor-details-options-grid-cell-txt"><?php echo $wrd_typeOfAccommodation; ?>:</p>
          </div>
          <div class="editor-details-options-grid-cell-input-wrp">
            <select class="editor-input-style editor-select-style" id="editor-input-type-of-accommodation">
              <?php
                $typeCottageSelected = "";
                $typeCampSelected = "";
                $typeGuesthouseSelected = "";
                $typeHotelSelected = "";
                $typeApartmentSelected = "";
                if ($plcType == "camp") {
                  $typeCampSelected = "selected";
                } else if ($plcType == "guesthouse") {
                  $typeGuesthouseSelected = "selected";
                } else if ($plcType == "hotel") {
                  $typeHotelSelected = "selected";
                } else if ($plcType == "apartment") {
                  $typeApartmentSelected = "selected";
                } else {
                  $typeCottageSelected = "selected";
                }
              ?>
              <option value="cottage" <?php echo $typeCottageSelected; ?>><?php echo $wrd_cottage; ?></option>
              <option value="camp" <?php echo $typeCampSelected; ?>><?php echo $wrd_camp; ?></option>
              <option value="guesthouse" <?php echo $typeGuesthouseSelected; ?>><?php echo $wrd_guesthouse; ?></option>
              <option value="hotel" <?php echo $typeHotelSelected; ?>><?php echo $wrd_hotel; ?></option>
              <option value="apartment" <?php echo $typeApartmentSelected; ?>><?php echo $wrd_apartment; ?></option>
            </select>
          </div>
        </div>
      </div>
      <div class="editor-details-options-grid-cell-blck">
        <div class="editor-details-options-grid-cell-layout">
          <div class="editor-details-options-grid-cell-txt-wrp">
            <p class="editor-details-options-grid-cell-txt"><?php echo $wrd_maxGuestNum; ?>:</p>
          </div>
          <div class="editor-details-options-grid-cell-input-wrp">
            <input type="number" class="editor-input-style" id="editor-input-num-of-guest" value="<?php echo $plcGuestMaxNum; ?>" min="1">
          </div>
        </div>
      </div>
      <div class="editor-details-options-grid-cell-blck">
        <div class="editor-details-options-grid-cell-layout">
          <div class="editor-details-options-grid-cell-txt-wrp">
            <p class="editor-details-options-grid-cell-txt"><?php echo $wrd_numBedrooms; ?>:</p>
          </div>
          <div class="editor-details-options-grid-cell-input-wrp">
            <input type="number" class="editor-input-style" id="editor-input-num-of-bedrooms" value="<?php echo $plcBedroom; ?>" min="1">
          </div>
        </div>
      </div>
      <div class="editor-details-options-grid-cell-blck">
        <div class="editor-details-options-grid-cell-layout">
          <div class="editor-details-options-grid-cell-txt-wrp">
            <p class="editor-details-options-grid-cell-txt"><?php echo $wrd_numberOfBathrooms; ?>:</p>
          </div>
          <div class="editor-details-options-grid-cell-input-wrp">
            <input type="number" class="editor-input-style" id="editor-input-num-of-bathrooms" value="<?php echo $plcBathrooms; ?>" min="1">
          </div>
        </div>
      </div>
      <div class="editor-details-options-grid-cell-blck">
        <div class="editor-details-options-grid-cell-layout">
          <div class="editor-details-options-grid-cell-txt-wrp">
            <p class="editor-details-options-grid-cell-txt"><?php echo $wrd_operation; ?>:</p>
          </div>
          <div class="editor-details-options-grid-cell-input-wrp">
            <select class="editor-input-style editor-select-style" id="editor-input-operation" onchange="editorDetailsOperationOnchange(this);">
              <?php
                $operYearRoundSelected = "";
                $operSummerSelected = "";
                $operWinterSelected = "";
                if ($plcOperation == "summer") {
                  $operSummerSelected = "selected";
                } else if ($plcOperation == "winter") {
                  $operWinterSelected = "selected";
                } else {
                  $operYearRoundSelected = "selected";
                }
              ?>
              <option value="year-round" <?php echo $operYearRoundSelected; ?>><?php echo $wrd_yearRound; ?></option>
              <option value="summer" <?php echo $operSummerSelected; ?>><?php echo $wrd_summerSeason; ?></option>
              <option value="winter" <?php echo $operWinterSelected; ?>><?php echo $wrd_winterSeason; ?></option>
            </select>
          </div>
        </div>
        <?php
          if ($plcOperation == "summer") {
            $operationSeasonsSummerStyle = "display: flex;";
            $operationSeasonsWinterStyle = "";
          } else if ($plcOperation == "winter") {
            $operationSeasonsSummerStyle = "";
            $operationSeasonsWinterStyle = "display: flex;";
          } else {
            $operationSeasonsSummerStyle = "";
            $operationSeasonsWinterStyle = "";
          }
          $operationFromOptionSelect1 = "";
          $operationFromOptionSelect2 = "";
          $operationFromOptionSelect3 = "";
          $operationFromOptionSelect4 = "";
          $operationFromOptionSelect5 = "";
          $operationFromOptionSelect6 = "";
          $operationFromOptionSelect7 = "";
          $operationFromOptionSelect8 = "";
          $operationFromOptionSelect9 = "";
          $operationFromOptionSelect10 = "";
          $operationFromOptionSelect11 = "";
          $operationFromOptionSelect12 = "";
          $operationToOptionSelect1 = "";
          $operationToOptionSelect2 = "";
          $operationToOptionSelect3 = "";
          $operationToOptionSelect4 = "";
          $operationToOptionSelect5 = "";
          $operationToOptionSelect6 = "";
          $operationToOptionSelect7 = "";
          $operationToOptionSelect8 = "";
          $operationToOptionSelect9 = "";
          $operationToOptionSelect10 = "";
          $operationToOptionSelect11 = "";
          $operationToOptionSelect12 = "";
          if ($plcOperationFrom == 1) {
            $operationFromOptionSelect1 = "selected";
          } else if ($plcOperationFrom == 2) {
            $operationFromOptionSelect2 = "selected";
          } else if ($plcOperationFrom == 3) {
            $operationFromOptionSelect3 = "selected";
          } else if ($plcOperationFrom == 4) {
            $operationFromOptionSelect4 = "selected";
          } else if ($plcOperationFrom == 5) {
            $operationFromOptionSelect5 = "selected";
          } else if ($plcOperationFrom == 6) {
            $operationFromOptionSelect6 = "selected";
          } else if ($plcOperationFrom == 7) {
            $operationFromOptionSelect7 = "selected";
          } else if ($plcOperationFrom == 8) {
            $operationFromOptionSelect8 = "selected";
          } else if ($plcOperationFrom == 9) {
            $operationFromOptionSelect9 = "selected";
          } else if ($plcOperationFrom == 10) {
            $operationFromOptionSelect10 = "selected";
          } else if ($plcOperationFrom == 11) {
            $operationFromOptionSelect11 = "selected";
          } else if ($plcOperationFrom == 12) {
            $operationFromOptionSelect12 = "selected";
          } else {
            if ($plcOperation == "winter") {
              $operationFromOptionSelect11 = "selected";
            } else {
              $operationFromOptionSelect5 = "selected";
            }
          }
          if ($plcOperationTo == 1) {
            $operationToOptionSelect1 = "selected";
          } else if ($plcOperationTo == 2) {
            $operationToOptionSelect2 = "selected";
          } else if ($plcOperationTo == 3) {
            $operationToOptionSelect3 = "selected";
          } else if ($plcOperationTo == 4) {
            $operationToOptionSelect4 = "selected";
          } else if ($plcOperationTo == 5) {
            $operationToOptionSelect5 = "selected";
          } else if ($plcOperationTo == 6) {
            $operationToOptionSelect6 = "selected";
          } else if ($plcOperationTo == 7) {
            $operationToOptionSelect7 = "selected";
          } else if ($plcOperationTo == 8) {
            $operationToOptionSelect8 = "selected";
          } else if ($plcOperationTo == 9) {
            $operationToOptionSelect9 = "selected";
          } else if ($plcOperationTo == 10) {
            $operationToOptionSelect10 = "selected";
          } else if ($plcOperationTo == 11) {
            $operationToOptionSelect11 = "selected";
          } else if ($plcOperationTo == 12) {
            $operationToOptionSelect12 = "selected";
          } else {
            if ($plcOperation == "winter") {
              $operationToOptionSelect2 = "selected";
            } else {
              $operationToOptionSelect9 = "selected";
            }
          }
        ?>
        <div class="editor-details-options-grid-cell-operation-seasons" id="editor-details-options-grid-cell-operation-seasons-summer" style="<?php echo $operationSeasonsSummerStyle; ?>">
          <div class="editor-details-options-grid-cell-operation-seasons-row">
            <p class="editor-details-options-grid-cell-operation-seasons-txt"><?php echo $wrd_from.":"; ?></p>
            <select class="editor-input-style editor-select-style editor-select-style-small" id="editor-details-options-grid-cell-select-summer-from">
              <option class="editor-details-options-grid-cell-option-summer-from" value="3" <?php echo $operationFromOptionSelect3; ?>><?php echo $wrd_march; ?></option>
              <option class="editor-details-options-grid-cell-option-summer-from" value="4" <?php echo $operationFromOptionSelect4; ?>><?php echo $wrd_april; ?></option>
              <option class="editor-details-options-grid-cell-option-summer-from" value="5" <?php echo $operationFromOptionSelect5; ?>><?php echo $wrd_may; ?></option>
              <option class="editor-details-options-grid-cell-option-summer-from" value="6" <?php echo $operationFromOptionSelect6; ?>><?php echo $wrd_june; ?></option>
              <option class="editor-details-options-grid-cell-option-summer-from" value="7" <?php echo $operationFromOptionSelect7; ?>><?php echo $wrd_july; ?></option>
              <option class="editor-details-options-grid-cell-option-summer-from" value="8" <?php echo $operationFromOptionSelect8; ?>><?php echo $wrd_august; ?></option>
              <option class="editor-details-options-grid-cell-option-summer-from" value="9" <?php echo $operationFromOptionSelect9; ?>><?php echo $wrd_september; ?></option>
              <option class="editor-details-options-grid-cell-option-summer-from" value="10" <?php echo $operationFromOptionSelect10; ?>><?php echo $wrd_october; ?></option>
            </select>
          </div>
          <div class="editor-details-options-grid-cell-operation-seasons-row">
            <p class="editor-details-options-grid-cell-operation-seasons-txt"><?php echo $wrd_to.":"; ?></p>
            <select class="editor-input-style editor-select-style editor-select-style-small" id="editor-details-options-grid-cell-select-summer-to">
              <option class="editor-details-options-grid-cell-option-summer-to" value="3" <?php echo $operationToOptionSelect3; ?>><?php echo $wrd_march; ?></option>
              <option class="editor-details-options-grid-cell-option-summer-to" value="4" <?php echo $operationToOptionSelect4; ?>><?php echo $wrd_april; ?></option>
              <option class="editor-details-options-grid-cell-option-summer-to" value="5" <?php echo $operationToOptionSelect5; ?>><?php echo $wrd_may; ?></option>
              <option class="editor-details-options-grid-cell-option-summer-to" value="6" <?php echo $operationToOptionSelect6; ?>><?php echo $wrd_june; ?></option>
              <option class="editor-details-options-grid-cell-option-summer-to" value="7" <?php echo $operationToOptionSelect7; ?>><?php echo $wrd_july; ?></option>
              <option class="editor-details-options-grid-cell-option-summer-to" value="8" <?php echo $operationToOptionSelect8; ?>><?php echo $wrd_august; ?></option>
              <option class="editor-details-options-grid-cell-option-summer-to" value="9" <?php echo $operationToOptionSelect9; ?>><?php echo $wrd_september; ?></option>
              <option class="editor-details-options-grid-cell-option-summer-to" value="10" <?php echo $operationToOptionSelect10; ?>><?php echo $wrd_october; ?></option>
            </select>
          </div>
        </div>
        <div class="editor-details-options-grid-cell-operation-seasons" id="editor-details-options-grid-cell-operation-seasons-winter" style="<?php echo $operationSeasonsWinterStyle; ?>">
          <div class="editor-details-options-grid-cell-operation-seasons-row">
            <p class="editor-details-options-grid-cell-operation-seasons-txt"><?php echo $wrd_from.":"; ?></p>
            <select class="editor-input-style editor-select-style editor-select-style-small" id="editor-details-options-grid-cell-select-winter-from">
              <option class="editor-details-options-grid-cell-option-winter-from" value="1" <?php echo $operationFromOptionSelect1; ?>><?php echo $wrd_january; ?></option>
              <option class="editor-details-options-grid-cell-option-winter-from" value="2" <?php echo $operationFromOptionSelect2; ?>><?php echo $wrd_february; ?></option>
              <option class="editor-details-options-grid-cell-option-winter-from" value="3" <?php echo $operationFromOptionSelect3; ?>><?php echo $wrd_march; ?></option>
              <option class="editor-details-options-grid-cell-option-winter-from" value="4" <?php echo $operationFromOptionSelect4; ?>><?php echo $wrd_april; ?></option>
              <option class="editor-details-options-grid-cell-option-winter-from" value="9" <?php echo $operationFromOptionSelect9; ?>><?php echo $wrd_september; ?></option>
              <option class="editor-details-options-grid-cell-option-winter-from" value="10" <?php echo $operationFromOptionSelect10; ?>><?php echo $wrd_october; ?></option>
              <option class="editor-details-options-grid-cell-option-winter-from" value="11" <?php echo $operationFromOptionSelect11; ?>><?php echo $wrd_november; ?></option>
              <option class="editor-details-options-grid-cell-option-winter-from" value="12" <?php echo $operationFromOptionSelect12; ?>><?php echo $wrd_december; ?></option>
            </select>
          </div>
          <div class="editor-details-options-grid-cell-operation-seasons-row">
            <p class="editor-details-options-grid-cell-operation-seasons-txt"><?php echo $wrd_to.":"; ?></p>
            <select class="editor-input-style editor-select-style editor-select-style-small" id="editor-details-options-grid-cell-select-winter-to">
              <option class="editor-details-options-grid-cell-option-winter-to" value="1" <?php echo $operationToOptionSelect1; ?>><?php echo $wrd_january; ?></option>
              <option class="editor-details-options-grid-cell-option-winter-to" value="2" <?php echo $operationToOptionSelect2; ?>><?php echo $wrd_february; ?></option>
              <option class="editor-details-options-grid-cell-option-winter-to" value="3" <?php echo $operationToOptionSelect3; ?>><?php echo $wrd_march; ?></option>
              <option class="editor-details-options-grid-cell-option-winter-to" value="4" <?php echo $operationToOptionSelect4; ?>><?php echo $wrd_april; ?></option>
              <option class="editor-details-options-grid-cell-option-winter-to" value="9" <?php echo $operationToOptionSelect9; ?>><?php echo $wrd_september; ?></option>
              <option class="editor-details-options-grid-cell-option-winter-to" value="10" <?php echo $operationToOptionSelect10; ?>><?php echo $wrd_october; ?></option>
              <option class="editor-details-options-grid-cell-option-winter-to" value="11" <?php echo $operationToOptionSelect11; ?>><?php echo $wrd_november; ?></option>
              <option class="editor-details-options-grid-cell-option-winter-to" value="12" <?php echo $operationToOptionSelect12; ?>><?php echo $wrd_december; ?></option>
            </select>
          </div>
        </div>
      </div>
      <div class="editor-details-options-grid-cell-blck">
        <div class="editor-details-options-grid-cell-layout">
          <div class="editor-details-options-grid-cell-txt-wrp">
            <p class="editor-details-options-grid-cell-txt"><?php echo $wrd_distanceFromTheWater." (".$wrd_inMeters.")"; ?>:</p>
          </div>
          <div class="editor-details-options-grid-cell-input-wrp">
            <input type="number" class="editor-input-style" id="editor-input-distance-from-the-water" value="<?php echo $plcDistanceFromTheWater; ?>" min="0">
            <p class="editor-details-options-grid-cell-input-desc"><?php echo $wrd_meters; ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="editor-details-sections">
    <div class="editor-details-sections-title-wrp">
      <div class="editor-details-sections-title-size">
        <p class="editor-details-sections-title-txt"><?php echo $wrd_equipment; ?></p>
      </div>
    </div>
    <div id="editor-details-equipment-list">
      <?php
        $customEquiNum = 7;
        $sqlEqui = $link->query("SELECT name, src FROM placeequipment WHERE beid='$plcBeId'");
        if ($sqlEqui->num_rows > 0) {
          while($rowEqui = $sqlEqui->fetch_assoc()) {
            if ($rowEqui['src'] == "wifi.svg") {
              $equiNum = 1;
            } else if ($rowEqui['src'] == "tv.svg") {
              $equiNum = 2;
            } else if ($rowEqui['src'] == "grill.svg") {
              $equiNum = 3;
            } else if ($rowEqui['src'] == "parking.svg") {
              $equiNum = 4;
            } else if ($rowEqui['src'] == "pet.svg") {
              $equiNum = 5;
            } else if ($rowEqui['src'] == "water.svg") {
              $equiNum = 6;
            } else {
              $equiNum = ++$customEquiNum;
            }
        ?>
          <div class="editor-details-equipment-block" id="editor-details-equipment-block-<?php echo $equiNum; ?>">
            <button class="editor-details-equipment-block-remove" aria-label="remove equipment" value="<?php echo $equiNum; ?>" onclick="editorDetailsEquipmentRemoveBlck(<?php echo $equiNum; ?>)"></button>
            <div class="editor-details-equipment-block-content">
              <div class="editor-details-equipment-block-img" id="editor-details-equipment-block-img-<?php echo $equiNum; ?>" style="background-image: url('../uni/icons/<?php echo $rowEqui['src']; ?>');">
                <p class="editor-details-equipment-block-img-txt" id="editor-details-equipment-block-img-txt-<?php echo $equiNum; ?>"><?php echo $rowEqui['src']; ?></p>
              </div>
              <p class="editor-details-equipment-block-p" id="editor-details-equipment-block-p-<?php echo $equiNum; ?>"><?php echo $rowEqui['name']; ?></p>
            </div>
          </div>
        <?php
          }
        }
      ?>
      <div id="editor-details-equipment-add-new-wrp">
        <button type="button" aria-label="add equipment" id="editor-details-equipment-add-new-btn" onclick="plcEquipModal('show', 'editor');"><?php echo $wrd_add; ?></button>
      </div>
    </div>
  </div>
  <div id="editor-details-place-id-wrp">
    <p id="editor-details-place-id-txt"><?php echo $wrd_cottageId; ?></p>
    <input type="text" placeholder="ID.." value="<?php echo $plcId; ?>" maxlength="10" id="editor-details-place-id-input">
  </div>
</div>
