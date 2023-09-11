<?php
  $mMSectionDashboardSelected = "";
  $mMSectionFeesSelected = "";
  $mMSectionFinanceSettingsSelected = "";
  $mMSectionFeaturesCodesSelected = "";
  $mMSectionUsersSelected = "";
  $mMSectionUserTrafficSelected = "";
  $mMSectionPlaceTrafficSelected = "";
  $mMSectionBrowsersSelected = "";
  $mMSectionCountriesSelected = "";
  $mMSectionSpendTimeSelected = "";
  if (isset($_GET['section'])) {
    if ($_GET['section'] == "dashboard") {
      $mMSectionDashboardSelected = "main-menu-block-selected";
    } else if ($_GET['section'] == "fees") {
      $mMSectionFeesSelected = "main-menu-block-selected";
    } else if ($_GET['section'] == "finance-settings") {
      $mMSectionFinanceSettingsSelected = "main-menu-block-selected";
    } else if ($_GET['section'] == "features-codes") {
      $mMSectionFeaturesCodesSelected = "main-menu-block-selected";
    } else if ($_GET['section'] == "users") {
      $mMSectionUsersSelected = "main-menu-block-selected";
    } else if ($_GET['section'] == "user-traffic") {
      $mMSectionUserTrafficSelected = "main-menu-block-selected";
    } else if ($_GET['section'] == "place-traffic") {
      $mMSectionPlaceTrafficSelected = "main-menu-block-selected";
    } else if ($_GET['section'] == "browsers") {
      $mMSectionBrowsersSelected = "main-menu-block-selected";
    } else if ($_GET['section'] == "countries") {
      $mMSectionCountriesSelected = "main-menu-block-selected";
    } else if ($_GET['section'] == "spend-time") {
      $mMSectionSpendTimeSelected = "main-menu-block-selected";
    }
  }
?>
<div id="main-menu-size" class="main-menu-size-show">
  <div id="main-menu-wrp">
    <div id="main-menu-content">
      <div class="main-menu-block">
        <a href="../dashboard/?section=dashboard" class="main-menu-block-title <?php echo $mMSectionDashboardSelected; ?>">Dashboard</a>
      </div>
      <div class="main-menu-block">
        <p class="main-menu-block-title"><?php echo $wrd_analytics; ?></p>
        <div class="main-menu-block-options-blck">
          <a href="../user-traffic/?section=user-traffic" class="main-menu-block-option-txt <?php echo $mMSectionUserTrafficSelected; ?>">- <?php echo $wrd_userTraffic; ?></a>
          <a href="../spend-time/?section=spend-time" class="main-menu-block-option-txt <?php echo $mMSectionSpendTimeSelected; ?>">- <?php echo $wrd_spendTime; ?></a>
          <a href="../countries/?section=countries" class="main-menu-block-option-txt <?php echo $mMSectionCountriesSelected; ?>">- <?php echo $wrd_countries; ?></a>
          <a href="../browsers/?section=browsers" class="main-menu-block-option-txt <?php echo $mMSectionBrowsersSelected; ?>">- <?php echo $wrd_browsers; ?></a>
          <a href="../place-traffic/?section=place-traffic" class="main-menu-block-option-txt <?php echo $mMSectionPlaceTrafficSelected; ?>">- <?php echo $wrd_placeTraffic; ?></a>
        </div>
      </div>
      <div class="main-menu-block">
        <p class="main-menu-block-title"><?php echo $wrd_finance; ?></p>
        <div class="main-menu-block-options-blck">
          <a href="../fees/?section=fees" class="main-menu-block-option-txt <?php echo $mMSectionFeesSelected; ?>">- <?php echo $wrd_fees; ?></a>
          <a href="../finance-settings/?section=finance-settings" class="main-menu-block-option-txt <?php echo $mMSectionFinanceSettingsSelected; ?>">- <?php echo $wrd_financeSettings; ?></a>
        </div>
      </div>
      <div class="main-menu-block">
        <a href="../features-codes/?section=features-codes" class="main-menu-block-title <?php echo $mMSectionFeaturesCodesSelected; ?>"><?php echo $wrd_featuresCodes; ?></a>
      </div>
      <div class="main-menu-block">
        <a href="../accounts/?section=users" class="main-menu-block-title <?php echo $mMSectionUsersSelected; ?>"><?php echo $wrd_users; ?></a>
      </div>
    </div>
  </div>
</div>
