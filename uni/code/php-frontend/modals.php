<?php
  include "place-equipment-modal.php";
  include "calendar-sync-modal.php";
?>
<div class="modal-cover" id="modal-cover-out-1">
  <div class="modal-block" id="modal-cover-out-1-blck">
    <button class="cancel-btn" onclick="modCover('hide', 'modal-cover-out-1')"></button>
    <p class="alert-error-p alert-error-wide"><?php echo $wrd_signOutModalError; ?></p>
  </div>
</div>
<div class="modal-cover modal-cover-up-2" id="modal-cover-prof-img">
  <div class="modal-block" id="modal-cover-prof-img-blck">
    <button class="cancel-btn" onclick="modCover('hide', 'modal-cover-prof-img');resetCrop(2)"></button>
    <div class="crop-wrp" id="crop-wrp-2">
      <div class="crop-content" id="crop-content-2">
        <label for="file-2" class="crop-def" id="crop-def-2" ondrop="dropFile(event, 2)" ondragover="return false">
          <div class="crop-prof-img-def">
            <img src="../uni/icons/def-prof-img2.svg">
            <p><?php echo $wrd_fileDrop; ?></p>
          </div>
        </label>
        <div class="crop-tool-wrap" id="crop-tool-wrap-2">
          <img src="" id="crop-img-2">
          <div class="crop-block-wrap" id="crop-block-wrap-2">
            <div class="crop-cover crop-cover-top" id="crop-cover-top-2"></div>
            <div class="crop-cover crop-cover-left" id="crop-cover-left-2"></div>
            <div class="crop" id="crop-2">
              <button class="crop-resize-btn crop-resize-btn-1" onmousedown="cropResize('1', '1:1', 2)"></button>
              <button class="crop-resize-btn crop-resize-btn-2" onmousedown="cropResize('2', '1:1', 2)"></button>
              <button class="crop-resize-btn crop-resize-btn-3" onmousedown="cropResize('3', '1:1', 2)"></button>
              <button class="crop-resize-btn crop-resize-btn-4" onmousedown="cropResize('4', '1:1', 2)"></button>
              <div class="crop-move" onmousedown="cropMove('down', 2)"></div>
            </div>
            <div class="crop-cover crop-cover-right" id="crop-cover-right-2"></div>
            <div class="crop-cover crop-cover-bottom" id="crop-cover-bottom-2"></div>
          </div>
        </div>
        <div class="crop-final-wrap" id="crop-final-wrap-2">
          <img src="" class="crop-final" id="crop-final-2">
        </div>
      </div>
      <img class="crop-loader" id="crop-loader-2" src="../uni/gifs/loader-tail.svg">
      <input type="file" name="file" class="crop-input" id="file-2" onchange="inputFile(this, 2)">
    </div>
    <div id="crop-error-wrp-2">
      <p class="crop-error-2 crop-error" id="crop-err-2-1"><?php echo $wrd_forgotJsonErr; ?></p>
      <p class="crop-error-2 crop-error" id="crop-err-2-2"><?php echo $wrd_tryLaterCrop; ?></p>
      <p class="crop-error-2 crop-error" id="crop-err-2-3"><?php echo $wrd_wrongFileType; ?></p>
      <p class="crop-error-2 crop-error" id="crop-err-2-4"><?php echo $wrd_tooBigImage; ?></p>
      <p class="crop-error-2 crop-error" id="crop-err-2-5"><?php echo $wrd_tryBrowser; ?></p>
      <p class="crop-error-2 crop-error" id="crop-err-2-6"><?php echo $wrd_uploadImageError; ?></p>
      <p class="crop-error-2 crop-error" id="crop-err-2-7"><?php echo $wrd_tryLater; ?></p>
      <p class="crop-error-2 crop-error" id="crop-err-2-8"><?php echo $wrd_dataError; ?></p>
      <p class="crop-error-2 crop-error" id="crop-err-2-9"><?php echo $wrd_fatalImageError; ?></p>
      <p class="crop-error-2 crop-error" id="crop-err-2-10"><?php echo $wrd_partImageError; ?></p>
    </div>
    <div id="mod-profile-img-btns-wrp">
      <div id="mod-profile-img-btns-center">
        <label for="file-2" class="btn btn-sml btn-sec" id="crop-change-btn-2"><?php echo $wrd_selectImage; ?></label>
        <button class="btn btn-sml btn-thrd" id="crop-btn-2" onclick="crop('profImg', 2)" value="off"><?php echo $wrd_crop; ?></button>
      </div>
    </div>
  </div>
</div>
<div class="modal-cover modal-cover-up-2" id="modal-cover-features-accepted">
  <div class="modal-block" id="modal-cover-features-accepted-blck">
    <button class="cancel-btn" onclick="location.reload();"></button>
    <div id="features-accepted-content">
      <div class="features-accepted-txt-wrp">
        <p class="features-accepted-txt"><?php echo $wrd_congratulations; ?></p>
      </div>
      <div class="features-accepted-txt-wrp">
        <h3 class="features-accepted-title"><?php echo $wrd_featuresHasBeenActivated; ?></h3>
      </div>
      <div class="features-accepted-txt-wrp">
        <p class="features-accepted-txt"><?php echo $wrd_haveThePageReloadForTheChangesToTakeEffect; ?></p>
      </div>
      <div id="features-accepted-error-wrp">
        <div class="features-accepted-txt-wrp">
          <p id="features-accepted-error"></p>
        </div>
      </div>
      <div id="features-accepted-btn-wrp">
        <button class="btn btn-mid btn-prim" onclick="location.reload();"><?php echo $wrd_refresh; ?></button>
      </div>
    </div>
  </div>
</div>
<div class="modal-cover" id="modal-cover-cancel-account-alert">
  <div class="modal-block" id="modal-cover-cancel-account-alert-blck">
    <button class="cancel-btn" onclick="modCover('hide', 'modal-cover-cancel-account-alert')"></button>
    <div id="cancel-profile-alert-modal-wrp">
      <p id="cancel-profile-alert-modal-txt"><?php echo $wrd_areYouSureYouWantToCancelYourProfile; ?></p>
      <p id="cancel-profile-alert-modal-desc"><?php echo $wrd_accountCancelingDescText; ?></p>
      <div id="cancel-profile-alert-modal-footer">
        <button class="btn btn-mid btn-sec cancel-profile-alert-modal-footer-btn" onclick="modCover('hide', 'modal-cover-cancel-account-alert')"><?php echo $wrd_no; ?></button>
        <button class="btn btn-mid btn-fth cancel-profile-alert-modal-footer-btn" onclick="cancelAccountSubmit()"><?php echo $wrd_yes; ?></button>
      </div>
    </div>
  </div>
</div>
<div class="modal-cover" id="modal-cover-cancel-account-partial-errors">
  <div class="modal-block" id="modal-cover-cancel-account-partial-errors-blck">
    <button class="cancel-btn" onclick="location.href = '../home/';"></button>
    <div id="cancel-profile-partial-errors-modal-wrp">
      <p id="cancel-profile-partial-errors-modal-txt"><?php echo $wrd_errorDuringAccountCancelingButaccountIsCanceled; ?></p>
      <p id="cancel-profile-partial-errors-modal-err"></p>
    </div>
  </div>
</div>
