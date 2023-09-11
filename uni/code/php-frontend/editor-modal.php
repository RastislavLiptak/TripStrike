<div class="modal-cover modal-cover-up" id="modal-cover-editor-modal">
  <div class="modal-block" id="modal-cover-editor-modal-blck">
    <button class="cancel-btn" onclick="toggEditorModal('hide')"></button>
    <div id="e-scroll-wrp">
      <div id="e-content">
        <div id="e-header">
          <div class="e-header-text-wrp">
            <h1 id="e-header-title"><?php echo $wrd_editor ?></h1>
          </div>
        </div>
        <div id="e-grid">
          <div id="e-load-more-btn-wrp">
            <button type="button" id="e-load-more-btn" onclick="loadEditModalContentData()"></button>
          </div>
        </div>
        <div id="e-main-no-content">
          <p id="e-main-no-content-txt"><?php echo $wrd_noPlacesFoundForYouToEdit; ?></p>
        </div>
        <div id="e-main-loader-wrp">
          <img alt="" src="../uni/gifs/loader-tail.svg" id="e-main-loader">
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal-cover modal-cover-up" id="modal-cover-editor-modal-error">
  <div class="modal-block" id="modal-cover-editor-modal-error-blck">
    <button class="cancel-btn" onclick="editorModalHide()"></button>
    <div class="e-error-content">
      <h3 class="e-error-title"><?php echo $wrd_error; ?></h3>
      <p class="e-error-txt" id="e-m-error-txt-1"><?php echo $wrd_codeError; ?></p>
      <p class="e-error-txt" id="e-m-error-txt-2"><?php echo $wrd_dataError; ?></p>
      <p class="e-error-txt" id="e-m-error-txt-3"><?php echo $wrd_unknownError; ?></p>
      <div class="e-error-code-wrp">
        <p class="e-error-code-title"><?php echo $wrd_errorCode.":"; ?></p>
        <div class="e-error-code-txt-wrp">
          <p class="e-error-code-txt" id="e-m-error-code-txt"></p>
        </div>
      </div>
      <div class="e-error-btn-wrp">
        <button class="btn btn-sml btn-sec e-error-btn" onclick="editorModalHide()"><?php echo $wrd_close; ?></button>
        <button class="btn btn-sml btn-prim e-error-btn" onclick="editorModalReset()"><?php echo $wrd_reset; ?></button>
      </div>
    </div>
  </div>
</div>
