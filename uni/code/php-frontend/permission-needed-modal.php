<div class="modal-cover" id="modal-cover-permission-needed">
  <div class="modal-block alert-error-block-w-btn permission-needed-to-change-modal" id="modal-cover-permission-needed-blck">
    <button class="cancel-btn" onclick="modCover('hide', 'modal-cover-permission-needed');"></button>
    <div class="permission-needed-to-change-scroll-wrp">
      <div class="permission-needed-to-change-content-wrp" id="permission-needed-to-change-content-wrp-permission-needed">
        <div id="permission-needed-to-change-accept-data">
          <p id="permission-needed-to-change-accept-details-id"></p>
          <p id="permission-needed-to-change-accept-details-f-d"></p>
          <p id="permission-needed-to-change-accept-details-f-m"></p>
          <p id="permission-needed-to-change-accept-details-f-y"></p>
          <p id="permission-needed-to-change-accept-details-t-d"></p>
          <p id="permission-needed-to-change-accept-details-t-m"></p>
          <p id="permission-needed-to-change-accept-details-t-y"></p>
          <p id="permission-needed-to-change-accept-name"></p>
          <p id="permission-needed-to-change-accept-email"></p>
          <p id="permission-needed-to-change-accept-phone"></p>
          <p id="permission-needed-to-change-accept-guests"></p>
          <p id="permission-needed-to-change-accept-notes"></p>
          <p id="permission-needed-to-change-accept-f-d"></p>
          <p id="permission-needed-to-change-accept-f-m"></p>
          <p id="permission-needed-to-change-accept-f-y"></p>
          <p id="permission-needed-to-change-accept-t-d"></p>
          <p id="permission-needed-to-change-accept-t-m"></p>
          <p id="permission-needed-to-change-accept-t-y"></p>
          <p id="permission-needed-to-change-accept-firstday"></p>
          <p id="permission-needed-to-change-accept-lastday"></p>
          <p id="permission-needed-to-change-accept-deposit"></p>
          <p id="permission-needed-to-change-accept-full-amount"></p>
          <p id="permission-needed-to-change-accept-category"></p>
        </div>
        <p class="permission-needed-to-change-title"><?php echo $wrd_changesYouAreAboutToMakeWillAffectOtherBookings; ?></p>
        <div id="permission-needed-to-change-list"></div>
        <div class="permission-needed-to-change-btn-wrp">
          <button type="button" class="btn btn-sml btn-fth permission-needed-to-change-btn" onclick="modCover('hide', 'modal-cover-permission-needed');"><?php echo $wrd_cancel; ?></button>
          <button type="button" class="btn btn-sml btn-prim permission-needed-to-change-btn" id="permission-needed-to-change-btn-accept" onclick="permissionNeededAccept(this.value)" value=""><?php echo $wrd_acceptCookies; ?></button>
        </div>
      </div>
    </div>
  </div>
</div>
