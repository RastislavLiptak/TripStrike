<div class="modal-cover modal-cover-up-2" id="modal-cover-calendar-sync">
  <div class="modal-block" id="modal-cover-calendar-sync-blck">
    <button class="cancel-btn" onclick="calendarSyncModal('hide', '');"></button>
    <div id="calendar-sync-modal-layout">
      <div id="calendar-sync-modal-scroll">
        <div id="calendar-sync-modal-content">
          <div id="calendar-sync-modal-content-title-wrp">
            <p id="calendar-sync-modal-content-title"><?php echo $wrd_listOfSupportedBookingResources; ?></p>
          </div>
          <div id="calendar-sync-modal-grid">
            <?php
              include "../libraries/PHPExcel-1.8/Classes/PHPExcel.php";
              $calendarSyncSupportFile = "../excel/calendar-sync-support.xlsx";
              $calendarSyncSupportRender =  PHPExcel_IOFactory::createReaderForFile($calendarSyncSupportFile);
              $calendarSyncSupportExcel_Obj = $calendarSyncSupportRender->load($calendarSyncSupportFile);
              $calendarSyncSupportWorksheet = $calendarSyncSupportExcel_Obj->getSheet('0');
              $calendarSyncSupportLastRow = $calendarSyncSupportWorksheet->getHighestRow();
              for ($row = 2;$row <= $calendarSyncSupportLastRow;$row++) {
            ?>
                <div class="calendar-sync-modal-grid-blck">
                  <div class="calendar-sync-modal-grid-blck-about">
                    <p id="calendar-sync-modal-grid-blck-about-title-<?php echo $calendarSyncSupportWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(1).$row)->getValue(); ?>"><?php echo $calendarSyncSupportWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(0).$row)->getValue(); ?></p>
                    <p id="calendar-sync-modal-grid-blck-about-logo-<?php echo $calendarSyncSupportWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(1).$row)->getValue(); ?>"><?php echo $calendarSyncSupportWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(3).$row)->getValue(); ?></p>
                    <p id="calendar-sync-modal-grid-blck-about-favicon-<?php echo $calendarSyncSupportWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(1).$row)->getValue(); ?>"><?php echo $calendarSyncSupportWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(4).$row)->getValue(); ?></p>
                    <p id="calendar-sync-modal-grid-blck-about-url-<?php echo $calendarSyncSupportWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(1).$row)->getValue(); ?>"><?php echo $calendarSyncSupportWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(2).$row)->getValue(); ?></p>
                    <p id="calendar-sync-modal-grid-blck-about-ical-link-txt-<?php echo $calendarSyncSupportWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(1).$row)->getValue(); ?>"><?php echo $wrd_iCalLink; ?></p>
                  </div>
                  <button type="button" class="calendar-sync-modal-grid-btn" onclick="calendarSyncBlckBtnToggle('<?php echo $calendarSyncSupportWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(1).$row)->getValue(); ?>');">
                    <div class="calendar-sync-modal-grid-btn-layout">
                      <div class="calendar-sync-modal-grid-favicon-wrp">
                        <div class="calendar-sync-modal-grid-favicon-img" style="background-image: url('../uni/icons/logos/<?php echo $calendarSyncSupportWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(4).$row)->getValue(); ?>');"></div>
                      </div>
                      <div class="calendar-sync-modal-grid-txt-wrp">
                        <p class="calendar-sync-modal-grid-txt"><?php echo $calendarSyncSupportWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(0).$row)->getValue(); ?></p>
                      </div>
                      <div class="calendar-sync-modal-grid-check-wrp">
                        <label class="calendar-sync-modal-grid-label">
                          <input type="checkbox" value="<?php echo $calendarSyncSupportWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(1).$row)->getValue(); ?>" class="calendar-sync-modal-grid-checkbox" id="calendar-sync-modal-grid-checkbox-<?php echo $calendarSyncSupportWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(1).$row)->getValue(); ?>">
                          <span class="calendar-sync-modal-grid-checkmark"></span>
                        </label>
                      </div>
                    </div>
                  </button>
                </div>
            <?php
              }
            ?>
          </div>
        </div>
      </div>
      <div id="calendar-sync-modal-tools-wrp">
        <button type="button" class="btn btn-mid btn-prim" id="calendar-sync-add-btn" onclick="calendarSyncModalAdd(this);"><?php echo $wrd_add; ?></button>
      </div>
    </div>
  </div>
</div>
