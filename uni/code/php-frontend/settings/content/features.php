<div class="settings-errors-wrp">
  <p class="settings-error-txt" id="settings-error-txt-features"></p>
</div>
<h1 class="settings-content-title"><?php echo $wrd_features; ?></h1>
<form class="settings-form-wrp">
  <div class="settings-form-row">
    <div class="settings-input-wrp">
      <p class="settings-input-title"><?php echo $wrd_activateFeature; ?>:</p>
      <input type="text" name="Activate feature" class="settings-input" id="settings-input-activate-feature-code" value="">
    </div>
  </div>
</form>
<div class="settings-btns-wrap">
  <button class="btn btn-mid btn-prim" id="settings-save-btn-features" onclick="saveFeaturesSettings()"><?php echo $wrd_save; ?></button>
</div>
