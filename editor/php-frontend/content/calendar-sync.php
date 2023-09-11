<div class="editor-content-section-wrp" id="editor-content-calendar-sync-wrp">
  <div id="editor-content-calendar-sync-layout">
    <div class="editor-content-calendar-sync-input-layout">
      <div class="editor-content-calendar-sync-input-title-wrp">
        <p class="editor-content-calendar-sync-input-title-txt"><?php echo $wrd_exportBookingDates; ?>:</p>
      </div>
      <div class="editor-content-calendar-sync-input-form">
        <div class="editor-content-calendar-sync-form-layout">
          <div class="c-s-wrp">
            <div class="c-s-blck">
              <div class="c-s-content-layout">
                <div class="c-s-input-wrp">
                  <input type="text" placeholder="" class="c-s-input" id="c-s-input-sync-export" value="">
                </div>
                <div class="c-s-delete-wrp">
                  <button type="button" id="c-s-copy-btn" onclick="editorCalendarSyncCopyExport();"><?php echo $wrd_copy; ?></button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="editor-content-calendar-sync-desc-wrp">
          <p class="editor-content-calendar-sync-desc-txt"><?php echo $wrd_calendarSyncDescText1; ?></p>
          <p class="editor-content-calendar-sync-desc-txt"><?php echo $wrd_calendarSyncDescText4; ?></p>
        </div>
      </div>
    </div>
    <div class="editor-content-calendar-sync-input-layout">
      <div class="editor-content-calendar-sync-input-title-wrp">
        <p class="editor-content-calendar-sync-input-title-txt"><?php echo $wrd_importBookingDates; ?>:</p>
      </div>
      <div class="editor-content-calendar-sync-input-form">
        <div class="editor-content-calendar-sync-form-layout" id="editor-content-calendar-sync-import-form-layout">
          <?php
            include "../libraries/PHPExcel-1.8/Classes/PHPExcel.php";
            $checkCalendarSyncSupportFile = "../excel/calendar-sync-support.xlsx";
            $checkCalendarSyncSupportRender =  PHPExcel_IOFactory::createReaderForFile($checkCalendarSyncSupportFile);
            $checkCalendarSyncSupportExcel_Obj = $checkCalendarSyncSupportRender->load($checkCalendarSyncSupportFile);
            $checkCalendarSyncSupportWorksheet = $checkCalendarSyncSupportExcel_Obj->getSheet('0');
            $checkCalendarSyncSupportLastRow = $checkCalendarSyncSupportWorksheet->getHighestRow();
            $sqlCalendarSync = $link->query("SELECT * FROM placecalendarsync WHERE beid='$plcBeId'");
            if ($sqlCalendarSync->num_rows > 0) {
              while($rowCalendarSync = $sqlCalendarSync->fetch_assoc()) {
                $calendarSyncCode = $rowCalendarSync['code'];
                if ($rowCalendarSync['error'] != "") {
                  $calendarSyncErrorStyle = "display: table;";
                } else {
                  $calendarSyncErrorStyle = "";
                }

                $calendarSyncTitle = "";
                $calendarSyncLogo = "";
                $calendarSyncURL = "";
                for ($rowCS = 2;$rowCS <= $checkCalendarSyncSupportLastRow;$rowCS++) {
                  if ($checkCalendarSyncSupportWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(1).$rowCS)->getValue() == $calendarSyncCode) {
                    $calendarSyncTitle = $checkCalendarSyncSupportWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(0).$rowCS)->getValue();
                    $calendarSyncLogo = $checkCalendarSyncSupportWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(3).$rowCS)->getValue();
                    $calendarSyncURL = $checkCalendarSyncSupportWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(2).$rowCS)->getValue();
                  }
                }
          ?>
            <div class="c-s-wrp" id="c-s-wrp-<?php echo $calendarSyncCode; ?>">
              <div class="c-s-about-wrp">
                <p class="c-s-about-code"><?php echo $calendarSyncCode; ?></p>
              </div>
              <div class="c-s-blck">
                <div class="c-s-content-layout">
                  <div class="c-s-logo-wrp">
                    <img class="c-s-logo-img" alt="<?php echo $calendarSyncTitle; ?>" src="../uni/icons/logos/<?php echo $calendarSyncLogo; ?>">
                  </div>
                  <div class="c-s-input-wrp">
                    <input type="text" placeholder="<?php echo $wrd_iCalLink.": ".$calendarSyncURL; ?>" class="c-s-input" id="c-s-input-<?php echo $calendarSyncCode; ?>" value="<?php echo $rowCalendarSync['url']; ?>">
                  </div>
                  <div class="c-s-delete-wrp">
                    <button type="button" class="c-s-delete-btn" onclick="editorCalendarSyncDeleteBlock('<?php echo $calendarSyncCode; ?>')"></button>
                  </div>
                </div>
                <div class="c-s-error-wrp" style="<?php echo $calendarSyncErrorStyle; ?>">
                  <div class="c-s-error-txt-wrp">
                    <p class="c-s-error-txt"><?php echo $rowCalendarSync['error']; ?></p>
                  </div>
                </div>
              </div>
            </div>
          <?php
              }
            }
          ?>
        </div>
        <div id="editor-content-calendar-sync-add-link-btn-wrp">
          <button type="button" id="editor-content-calendar-sync-add-link-btn" onclick="calendarSyncModal('show', 'editor');">
            <div id="editor-content-calendar-sync-add-link-btn-icon"></div>
            <p id="editor-content-calendar-sync-add-link-btn-txt"><?php echo $wrd_addBookingSource; ?></p>
          </button>
        </div>
        <div class="editor-content-calendar-sync-desc-wrp">
          <p class="editor-content-calendar-sync-desc-txt"><?php echo $wrd_calendarSyncDescText2; ?></p>
          <p class="editor-content-calendar-sync-desc-txt"><?php echo $wrd_calendarSyncDescText3; ?></p>
        </div>
      </div>
    </div>
  </div>
</div>
