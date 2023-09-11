<h1 class="settings-content-title"><?php echo $wrd_language; ?></h1>
<div id="language-settings-grid">
  <?php
    for ($setLangN=0; $setLangN < sizeof($default_langs_arr); $setLangN++) {
      if ($default_langs_arr[$setLangN] == $wrd_shrt) {
        $langBlockClass = "lang-block-btn lang-block-select";
      } else {
        $langBlockClass = "lang-block-btn";
      }
  ?>
    <div class="lang-block-wrp">
      <button type="button" class="<?php echo $langBlockClass; ?>" id="lang-block-btn-<?php echo $default_langs_arr[$setLangN]; ?>" onclick="setLang('<?php echo $default_langs_arr[$setLangN]; ?>')">
        <img src="../uni/icons/langs/<?php echo $default_langs_arr[$setLangN]; ?>.svg" alt="<?php echo $default_langs_arr[$setLangN]; ?> icon">
        <div class="lang-block-txt-size">
          <div class="lang-block-txt-wrp">
            <p><?php echo $default_langs_arr_title[$setLangN]; ?></p>
          </div>
        </div>
      </button>
    </div>
  <?php
    }
  ?>
</div>
