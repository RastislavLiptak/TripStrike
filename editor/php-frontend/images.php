<div id="editor-images-wrp">
  <div id="editor-images-layout-wrp">
    <div id="editor-images-position-wrp">
      <div id="editor-images-list">
        <?php
          $setImgBeIdArr = [];
          $sqlSetCommonImgBeId = $link->query("SELECT imgbeid FROM placeimages WHERE cottbeid='$plcBeId' and type='mid' and sts='common' ORDER BY numid DESC LIMIT 2");
          if ($sqlSetCommonImgBeId->num_rows > 0) {
            while($rowSetCommonImg = $sqlSetCommonImgBeId->fetch_assoc()) {
              array_push($setImgBeIdArr, $rowSetCommonImg["imgbeid"]);
            }
          }
          $sqlSetMainImgBeId = $link->query("SELECT imgbeid FROM placeimages WHERE cottbeid='$plcBeId' and type='mid' and sts='main' ORDER BY numid DESC LIMIT 1");
          if ($sqlSetMainImgBeId->num_rows > 0) {
            array_push($setImgBeIdArr, $sqlSetMainImgBeId->fetch_assoc()["imgbeid"]);
          }
          $setImgNum = 0;
          foreach ($setImgBeIdArr as $img) {
            $sqlSetImgBeId = $link->query("SELECT imgbeid FROM placeimages WHERE imgbeid='$img' LIMIT 1");
            if ($sqlSetImgBeId->num_rows > 0) {
              $setImgBeId = $sqlSetImgBeId->fetch_assoc()["imgbeid"];
              $sqlSetImgSrc = $link->query("SELECT src FROM images WHERE name='$setImgBeId'");
              if ($sqlSetImgSrc->num_rows > 0) {
                $setImgSrc = $sqlSetImgSrc->fetch_assoc()['src'];
                if (count($setImgBeIdArr) == $setImgNum +1) {
                  $editorImgClass = "editor-image-prim";
                } else {
                  $editorImgClass = "editor-image-sec";
                }
        ?>
                <div class="<?php echo $editorImgClass; ?>" style="background-image: url(../<?php echo $setImgSrc; ?>);"></div>
        <?php
                ++$setImgNum;
              }
            }
          }
          if ($setImgNum == 0) {
        ?>
            <div id="editor-image-def-wrp">
              <div id="editor-image-def-icon"></div>
            </div>
        <?php
          }
        ?>
      </div>
    </div>
    <div id="editor-images-btn-wrp">
      <button type="button" id="editor-images-btn" onclick="editorImagesModalToggle('show');"><?php echo $wrd_editImages; ?></button>
    </div>
  </div>
</div>
