<h1 class="settings-content-title"><?php echo $wrd_profileImage; ?></h1>

<div id="settings-crop-wrp">
  <label for="file-2" id="sett-crop-img-btn" ondrop="dropFile(event, 2)" ondragover="return false">
    <div id="set-curr-prof-img-wrp">
      <img src="../<?php echo $bigImg; ?>" id="set-curr-prof-img">
      <div id="sett-crop-img-icn-wrp">
        <div id="sett-crop-img-icn-blck">
          <img src="../uni/icons/img-set.svg" alt="change-image-icon" id="sett-crop-img-icn">
          <p id="sett-crop-img-icn-txt"><?php echo $wrd_selectImage; ?></p>
        </div>
      </div>
    </div>
  </label>
</div>
