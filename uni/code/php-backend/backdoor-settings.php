<?php
  $bds_percAmountOfTheFees = getBackDoorSettingsValue("amount-of-the-fees");
  $bds_detailsForThePaymentOfFeesIban = getBackDoorSettingsValue("details-for-the-payment-of-fees-iban");
  $bds_detailsForThePaymentOfFeesBankAccount = getBackDoorSettingsValue("details-for-the-payment-of-fees-bank-account");
  $bds_detailsForThePaymentOfFeesBicSwift = getBackDoorSettingsValue("details-for-the-payment-of-fees-bic-swift");
  $bds_dateOfCallForFeesDay = getBackDoorSettingsValue("date-of-call-for-fees-day");
  $bds_dateOfCallForFeesTimeHours = getBackDoorSettingsValue("date-of-call-for-fees-time-hours");
  $bds_dateOfCallForFeesTimeMinutes = getBackDoorSettingsValue("date-of-call-for-fees-time-minutes");

  function getBackDoorSettingsValue($name) {
    global $linkBD;
    $sqlBackDoorSettings = $linkBD->query("SELECT * FROM settings WHERE name='$name'");
    if ($sqlBackDoorSettings->num_rows > 0) {
      $bdsRow = $sqlBackDoorSettings->fetch_assoc();
      if ($bdsRow['value'] != "" || ($bdsRow['value'] == "" && $bdsRow['type'] == "txt")){
        return $bdsRow['value'];
      } else {
        return 0;
      }
    } else {
      return 0;
    }
  }
?>
