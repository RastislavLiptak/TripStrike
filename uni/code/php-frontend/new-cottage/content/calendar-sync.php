<form class="n-c-layout" id="n-c-layout-calendar-sync">
  <div class="n-c-error-wrp">
    <p class="n-c-error-txt" id="n-c-error-txt-calendar-sync"></p>
  </div>
  <h2 class="n-c-title"><?php echo $wrd_calendarSync; ?></h2>
  <div id="n-c-calendar-sync-wrp">
    <div id="n-c-calendar-sync-layout">
      <div id="n-c-calendar-sync-content"></div>
      <div id="n-c-calendar-sync-add-link-btn-wrp">
        <button type="button" id="n-c-calendar-sync-add-link-btn" onclick="calendarSyncModal('show', 'new-cottage');">
          <div id="n-c-calendar-sync-add-link-btn-icon"></div>
          <p id="n-c-calendar-sync-add-link-btn-txt"><?php echo $wrd_addBookingSource; ?></p>
        </button>
      </div>
      <div id="n-c-calendar-sync-desc-wrp">
        <p class="n-c-calendar-sync-desc-txt"><?php echo $wrd_calendarSyncDescText1; ?></p>
        <p class="n-c-calendar-sync-desc-txt"><?php echo $wrd_calendarSyncDescText2; ?></p>
        <p class="n-c-calendar-sync-desc-txt"><?php echo $wrd_calendarSyncDescText3; ?></p>
      </div>
    </div>
  </div>
</form>
