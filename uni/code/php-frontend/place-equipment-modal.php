<div class="modal-cover modal-cover-up-2" id="modal-cover-plc-tool-equipment">
  <div class="modal-block" id="modal-cover-plc-tool-equipment-blck">
    <button class="cancel-btn" onclick="plcEquipModal('hide', '');"></button>
    <div id="plc-tool-equipment-wrp">
      <div id="plc-tool-equipment-list">
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-1" value="<?php echo $wrd_wifiConnection; ?>" onclick="plcEquipToolCheckBtn(1)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-1" alt="new cottage benefit icon" src="../uni/icons/wifi.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_wifiConnection; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="1" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-1" onclick="plcEquipToolCheckClick(1)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-2" value="<?php echo $wrd_paidWifiConnection; ?>" onclick="plcEquipToolCheckBtn(2)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-2" alt="new cottage benefit icon" src="../uni/icons/wifi2.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_paidWifiConnection; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="2" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-2" onclick="plcEquipToolCheckClick(2)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-3" value="<?php echo $wrd_radio; ?>" onclick="plcEquipToolCheckBtn(3)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-3" alt="new cottage benefit icon" src="../uni/icons/radio.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_radio; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="3" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-3" onclick="plcEquipToolCheckClick(3)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-4" value="<?php echo $wrd_television; ?>" onclick="plcEquipToolCheckBtn(4)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-4" alt="new cottage benefit icon" src="../uni/icons/tv.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_television; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="4" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-4" onclick="plcEquipToolCheckClick(4)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-5" value="<?php echo $wrd_DVDPlayer; ?>" onclick="plcEquipToolCheckBtn(5)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-5" alt="new cottage benefit icon" src="../uni/icons/dvd-rom-logotype.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_DVDPlayer; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="5" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-5" onclick="plcEquipToolCheckClick(5)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-6" value="<?php echo $wrd_CDPlayer; ?>" onclick="plcEquipToolCheckBtn(6)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-6" alt="new cottage benefit icon" src="../uni/icons/compact-disc.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_CDPlayer; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="6" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-6" onclick="plcEquipToolCheckClick(6)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-7" value="<?php echo $wrd_gardenFurniture; ?>" onclick="plcEquipToolCheckBtn(7)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-7" alt="new cottage benefit icon" src="../uni/icons/picnic.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_gardenFurniture; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="7" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-7" onclick="plcEquipToolCheckClick(7)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-8" value="<?php echo $wrd_grill; ?>" onclick="plcEquipToolCheckBtn(8)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-8" alt="new cottage benefit icon" src="../uni/icons/grill.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_grill; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="8" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-8" onclick="plcEquipToolCheckClick(8)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-9" value="<?php echo $wrd_fireplace; ?>" onclick="plcEquipToolCheckBtn(9)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-9" alt="new cottage benefit icon" src="../uni/icons/fireplace.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_fireplace; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="9" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-9" onclick="plcEquipToolCheckClick(9)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-10" value="<?php echo $wrd_outdoorFireplace; ?>" onclick="plcEquipToolCheckBtn(10)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-10" alt="new cottage benefit icon" src="../uni/icons/fire.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_outdoorFireplace; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="10" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-10" onclick="plcEquipToolCheckClick(10)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-11" value="<?php echo $wrd_fireExtinguisher; ?>" onclick="plcEquipToolCheckBtn(11)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-11" alt="new cottage benefit icon" src="../uni/icons/fire-extinguisher.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_fireExtinguisher; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="11" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-11" onclick="plcEquipToolCheckClick(11)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-12" value="<?php echo $wrd_firstAidKit; ?>" onclick="plcEquipToolCheckBtn(12)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-12" alt="new cottage benefit icon" src="../uni/icons/first-aid-kit-box-with-cross-sign.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_firstAidKit; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="12" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-12" onclick="plcEquipToolCheckClick(12)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-13" value="<?php echo $wrd_parking; ?>" onclick="plcEquipToolCheckBtn(13)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-13" alt="new cottage benefit icon" src="../uni/icons/parking.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_parking; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="13" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-13" onclick="plcEquipToolCheckClick(13)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-14" value="<?php echo $wrd_petsAllowed; ?>" onclick="plcEquipToolCheckBtn(14)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-14" alt="new cottage benefit icon" src="../uni/icons/pet.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_petsAllowed; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="14" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-14" onclick="plcEquipToolCheckClick(14)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-15" value="<?php echo $wrd_heating; ?>" onclick="plcEquipToolCheckBtn(15)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-15" alt="new cottage benefit icon" src="../uni/icons/heater2.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_heating; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="15" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-15" onclick="plcEquipToolCheckClick(15)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-16" value="<?php echo $wrd_heatedFloors; ?>" onclick="plcEquipToolCheckBtn(16)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-16" alt="new cottage benefit icon" src="../uni/icons/heater.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_heatedFloors; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="16" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-16" onclick="plcEquipToolCheckClick(16)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-17" value="<?php echo $wrd_airConditioning; ?>" onclick="plcEquipToolCheckBtn(17)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-17" alt="new cottage benefit icon" src="../uni/icons/air-conditioning.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_airConditioning; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="17" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-17" onclick="plcEquipToolCheckClick(17)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-18" value="<?php echo $wrd_drinkingWater; ?>" onclick="plcEquipToolCheckBtn(18)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-18" alt="new cottage benefit icon" src="../uni/icons/water.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_drinkingWater; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="18" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-18" onclick="plcEquipToolCheckClick(18)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-19" value="<?php echo $wrd_bar; ?>" onclick="plcEquipToolCheckBtn(19)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-19" alt="new cottage benefit icon" src="../uni/icons/bar.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_bar; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="19" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-19" onclick="plcEquipToolCheckClick(19)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-20" value="<?php echo $wrd_beachBar; ?>" onclick="plcEquipToolCheckBtn(20)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-20" alt="new cottage benefit icon" src="../uni/icons/beach-bar.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_beachBar; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="20" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-20" onclick="plcEquipToolCheckClick(20)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-21" value="<?php echo $wrd_dishes; ?>" onclick="plcEquipToolCheckBtn(21)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-21" alt="new cottage benefit icon" src="../uni/icons/dishes.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_dishes; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="21" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-21" onclick="plcEquipToolCheckClick(21)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-22" value="<?php echo $wrd_cutlery; ?>" onclick="plcEquipToolCheckBtn(22)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-22" alt="new cottage benefit icon" src="../uni/icons/cutlery.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_cutlery; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="22" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-22" onclick="plcEquipToolCheckClick(22)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-23" value="<?php echo $wrd_fridge; ?>" onclick="plcEquipToolCheckBtn(23)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-23" alt="new cottage benefit icon" src="../uni/icons/fridge.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_fridge; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="23" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-23" onclick="plcEquipToolCheckClick(23)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-24" value="<?php echo $wrd_stove." / ".$wrd_inductionHob; ?>" onclick="plcEquipToolCheckBtn(24)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-24" alt="new cottage benefit icon" src="../uni/icons/stove.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_stove." / ".$wrd_inductionHob; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="24" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-24" onclick="plcEquipToolCheckClick(24)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-25" value="<?php echo $wrd_oven; ?>" onclick="plcEquipToolCheckBtn(25)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-25" alt="new cottage benefit icon" src="../uni/icons/oven.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_oven; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="25" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-25" onclick="plcEquipToolCheckClick(25)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-26" value="<?php echo $wrd_microwave; ?>" onclick="plcEquipToolCheckBtn(26)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-26" alt="new cottage benefit icon" src="../uni/icons/microwave.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_microwave; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="26" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-26" onclick="plcEquipToolCheckClick(26)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-27" value="<?php echo $wrd_dishwasher; ?>" onclick="plcEquipToolCheckBtn(27)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-27" alt="new cottage benefit icon" src="../uni/icons/dishwasher.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_dishwasher; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="27" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-27" onclick="plcEquipToolCheckClick(27)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-28" value="<?php echo $wrd_kettle; ?>" onclick="plcEquipToolCheckBtn(28)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-28" alt="new cottage benefit icon" src="../uni/icons/kettle.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_kettle; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="28" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-28" onclick="plcEquipToolCheckClick(28)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-29" value="<?php echo $wrd_toaster; ?>" onclick="plcEquipToolCheckBtn(29)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-29" alt="new cottage benefit icon" src="../uni/icons/toaster.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_toaster; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="29" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-29" onclick="plcEquipToolCheckClick(29)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-30" value="<?php echo $wrd_coffeeMachine; ?>" onclick="plcEquipToolCheckBtn(30)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-30" alt="new cottage benefit icon" src="../uni/icons/coffee-machine.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_coffeeMachine; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="30" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-30" onclick="plcEquipToolCheckClick(30)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-31" value="<?php echo $wrd_playground; ?>" onclick="plcEquipToolCheckBtn(31)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-31" alt="new cottage benefit icon" src="../uni/icons/playground.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_playground; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="31" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-31" onclick="plcEquipToolCheckClick(31)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-32" value="<?php echo $wrd_paddleboardRental; ?>" onclick="plcEquipToolCheckBtn(32)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-32" alt="new cottage benefit icon" src="../uni/icons/paddleboarding.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_paddleboardRental; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="32" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-32" onclick="plcEquipToolCheckClick(32)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-33" value="<?php echo $wrd_bicycleRental; ?>" onclick="plcEquipToolCheckBtn(33)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-33" alt="new cottage benefit icon" src="../uni/icons/bike.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_bicycleRental; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="33" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-33" onclick="plcEquipToolCheckClick(33)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-34" value="<?php echo $wrd_tennisCourt; ?>" onclick="plcEquipToolCheckBtn(34)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-34" alt="new cottage benefit icon" src="../uni/icons/tennis-court.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_tennisCourt; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="34" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-34" onclick="plcEquipToolCheckClick(34)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-35" value="<?php echo $wrd_tennisEquipment; ?>" onclick="plcEquipToolCheckBtn(35)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-35" alt="new cottage benefit icon" src="../uni/icons/tennis-racket.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_tennisEquipment; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="35" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-35" onclick="plcEquipToolCheckClick(35)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-36" value="<?php echo $wrd_tableTennis; ?>" onclick="plcEquipToolCheckBtn(36)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-36" alt="new cottage benefit icon" src="../uni/icons/ping-pong.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_tableTennis; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="36" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-36" onclick="plcEquipToolCheckClick(36)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-37" value="<?php echo $wrd_tableFootball; ?>" onclick="plcEquipToolCheckBtn(37)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-37" alt="new cottage benefit icon" src="../uni/icons/table-football.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_tableFootball; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="37" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-37" onclick="plcEquipToolCheckClick(37)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-38" value="<?php echo $wrd_billiards; ?>" onclick="plcEquipToolCheckBtn(38)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-38" alt="new cottage benefit icon" src="../uni/icons/billiard.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_billiards; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="38" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-38" onclick="plcEquipToolCheckClick(38)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-39" value="<?php echo $wrd_darts; ?>" onclick="plcEquipToolCheckBtn(39)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-39" alt="new cottage benefit icon" src="../uni/icons/dart-board.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_darts; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="39" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-39" onclick="plcEquipToolCheckClick(39)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-40" value="<?php echo $wrd_pool; ?>" onclick="plcEquipToolCheckBtn(40)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-40" alt="new cottage benefit icon" src="../uni/icons/pool.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_pool; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="40" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-40" onclick="plcEquipToolCheckClick(40)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-41" value="<?php echo $wrd_sauna; ?>" onclick="plcEquipToolCheckBtn(41)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-41" alt="new cottage benefit icon" src="../uni/icons/sauna.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_sauna; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="41" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-41" onclick="plcEquipToolCheckClick(41)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-42" value="<?php echo $wrd_whirlpool; ?>" onclick="plcEquipToolCheckBtn(42)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-42" alt="new cottage benefit icon" src="../uni/icons/whirlpool.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_whirlpool; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="42" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-42" onclick="plcEquipToolCheckClick(42)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-43" value="<?php echo $wrd_bathtub; ?>" onclick="plcEquipToolCheckBtn(43)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-43" alt="new cottage benefit icon" src="../uni/icons/bathtub.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_bathtub; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="43" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-43" onclick="plcEquipToolCheckClick(43)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-44" value="<?php echo $wrd_shower; ?>" onclick="plcEquipToolCheckBtn(44)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-44" alt="new cottage benefit icon" src="../uni/icons/shower.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_shower; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="44" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-44" onclick="plcEquipToolCheckClick(44)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-45" value="<?php echo $wrd_hygieneSupplies; ?>" onclick="plcEquipToolCheckBtn(45)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-45" alt="new cottage benefit icon" src="../uni/icons/toothbrush.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_hygieneSupplies; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="45" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-45" onclick="plcEquipToolCheckClick(45)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-46" value="<?php echo $wrd_towels; ?>" onclick="plcEquipToolCheckBtn(46)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-46" alt="new cottage benefit icon" src="../uni/icons/towels.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_towels; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="46" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-46" onclick="plcEquipToolCheckClick(46)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-47" value="<?php echo $wrd_washingMachine; ?>" onclick="plcEquipToolCheckBtn(47)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-47" alt="new cottage benefit icon" src="../uni/icons/washing-machine.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_washingMachine; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="47" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-47" onclick="plcEquipToolCheckClick(47)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-48" value="<?php echo $wrd_washingPowder; ?>" onclick="plcEquipToolCheckBtn(48)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-48" alt="new cottage benefit icon" src="../uni/icons/washing-powder.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_washingPowder; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="48" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-48" onclick="plcEquipToolCheckClick(48)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-49" value="<?php echo $wrd_hairdryer; ?>" onclick="plcEquipToolCheckBtn(49)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-49" alt="new cottage benefit icon" src="../uni/icons/hairdryer-silhouette-side-view.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_hairdryer; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="49" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-49" onclick="plcEquipToolCheckClick(49)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-50" value="<?php echo $wrd_iron; ?>" onclick="plcEquipToolCheckBtn(50)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-50" alt="new cottage benefit icon" src="../uni/icons/electric-iron.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_iron; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="50" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-50" onclick="plcEquipToolCheckClick(50)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-51" value="<?php echo $wrd_ironingBoard; ?>" onclick="plcEquipToolCheckBtn(51)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-51" alt="new cottage benefit icon" src="../uni/icons/ironing-board.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_ironingBoard; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="51" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-51" onclick="plcEquipToolCheckClick(51)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-52" value="<?php echo $wrd_toilet; ?>" onclick="plcEquipToolCheckBtn(52)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-52" alt="new cottage benefit icon" src="../uni/icons/toilet.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_toilet; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="52" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-52" onclick="plcEquipToolCheckClick(52)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>
        <button class="plc-tool-equipment-btn" id="plc-tool-equipment-btn-53" value="<?php echo $wrd_sewerage; ?>" onclick="plcEquipToolCheckBtn(53)">
          <div class="plc-tool-equipment-img-wrp">
            <img class="plc-tool-equipment-img" id="plc-tool-equipment-img-53" alt="new cottage benefit icon" src="../uni/icons/sewerage.svg">
          </div>
          <div class="plc-tool-equipment-txt-wrp">
            <p class="plc-tool-equipment-txt"><?php echo $wrd_sewerage; ?></p>
          </div>
          <div class="plc-tool-equipment-check-wrp">
            <label class="plc-tool-equipment-label">
              <input type="checkbox" value="53" class="plc-tool-equipment-checkbox" id="plc-tool-equipment-checkbox-53" onclick="plcEquipToolCheckClick(53)">
              <span class="plc-tool-equipment-checkmark"></span>
            </label>
          </div>
        </button>



        <div class="plc-tool-equipment-add">
          <div class="plc-tool-equipment-img-wrp">
            <div class="plc-tool-equipment-img" id="plc-tool-equipment-img-add" onclick="document.getElementById('plc-tool-equipment-inpt').focus();"></div>
          </div>
          <input type="text" id="plc-tool-equipment-inpt" placeholder="<?php echo $wrd_addMore; ?>" onkeyup="plcEquipToolAddKeyUp(this)">
          <div id="plc-tool-equipment-add-btn-wrp">
            <button type="button" id="plc-tool-equipment-add-btn" onclick="plcEquipToolAddNew()"></button>
          </div>
        </div>
      </div>
    </div>
    <div class="plc-tool-modal-btn-footer">
      <button class="btn btn-mid btn-prim" id="plc-tool-equipment-submit-btn" value="" onclick="plcEquipToolSubmit()"><?php echo $wrd_submit; ?></button>
    </div>
  </div>
</div>
