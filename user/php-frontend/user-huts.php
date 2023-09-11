<div id="user-cottage-grid-wrp">
  <div class="u-c-grid-set" id="user-cottage-grid">
  </div>
  <div id="u-c-load-more-wrp">
    <button type="button" id="u-c-load-more-btn" onclick="getCottLinksData()">
      <div id="u-c-load-more-btn-icon"></div>
      <p id="u-c-load-more-btn-txt"><?php echo $wrd_loadMore; ?></p>
    </button>
  </div>
  <div id="u-c-loader"></div>
  <div id="u-c-no-cottage">
    <div id="u-c-no-cottage-txt-wrp">
      <p id="u-c-no-cottage-txt"><?php echo $accfirstname." ".$acclastname." ".$wrd_doesNotOfferAnyCottages;?></p>
    </div>
  </div>
</div>
<div class="modal-cover" id="modal-cover-huts-list-error-1-1">
  <div class="modal-block modal-block-c-error" id="modal-cover-huts-list-error-1-1-blck">
    <div class="modal-block-c-layout">
      <p class="modal-cover-c-e-txt"><?php echo $wrd_loadCottagesScriptError; ?></p>
      <div class="modal-cover-c-e-btns-wpr">
        <button class="btn btn-mid btn-sec modal-cover-c-e-btn" onclick="window.history.back()"><?php echo $wrd_back; ?></button>
        <button class="btn btn-mid btn-prim modal-cover-c-e-btn" onclick="userCottageRetry('from-0', 'modal-cover-huts-list-error-1-1')"><?php echo $wrd_retry; ?></button>
      </div>
    </div>
  </div>
</div>
<div class="modal-cover" id="modal-cover-huts-list-error-1-2">
  <div class="modal-block modal-block-c-error" id="modal-cover-huts-list-error-1-2-blck">
    <div class="modal-block-c-layout">
      <p class="modal-cover-c-e-txt"><?php echo $wrd_loadCottagesScriptError; ?></p>
      <div class="modal-cover-c-e-btns-wpr">
        <button class="btn btn-mid btn-sec modal-cover-c-e-btn" onclick="modCover('hide', 'modal-cover-huts-list-error-1-2')"><?php echo $wrd_close; ?></button>
        <button class="btn btn-mid btn-prim modal-cover-c-e-btn" onclick="userCottageRetry('from-last', 'modal-cover-huts-list-error-1-2')"><?php echo $wrd_retry; ?></button>
      </div>
    </div>
  </div>
</div>
<div class="modal-cover" id="modal-cover-huts-list-error-2-1">
  <div class="modal-block modal-block-c-error" id="modal-cover-huts-list-error-2-1-blck">
    <div class="modal-block-c-layout">
      <p class="modal-cover-c-e-txt"><?php echo $wrd_loadCottagesDataError; ?></p>
      <div class="modal-cover-c-e-btns-wpr">
        <button class="btn btn-mid btn-sec modal-cover-c-e-btn" onclick="window.history.back()"><?php echo $wrd_back; ?></button>
        <button class="btn btn-mid btn-prim modal-cover-c-e-btn" onclick="userCottageRetry('from-0', 'modal-cover-huts-list-error-2-1')"><?php echo $wrd_retry; ?></button>
      </div>
    </div>
  </div>
</div>
<div class="modal-cover" id="modal-cover-huts-list-error-2-2">
  <div class="modal-block modal-block-c-error" id="modal-cover-huts-list-error-2-2-blck">
    <div class="modal-block-c-layout">
      <p class="modal-cover-c-e-txt"><?php echo $wrd_loadCottagesDataError; ?></p>
      <div class="modal-cover-c-e-btns-wpr">
        <button class="btn btn-mid btn-sec modal-cover-c-e-btn" onclick="modCover('hide', 'modal-cover-huts-list-error-2-2')"><?php echo $wrd_close; ?></button>
        <button class="btn btn-mid btn-prim modal-cover-c-e-btn" onclick="userCottageRetry('from-last', 'modal-cover-huts-list-error-2-2')"><?php echo $wrd_retry; ?></button>
      </div>
    </div>
  </div>
</div>
