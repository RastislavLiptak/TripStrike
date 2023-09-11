<?php
  function changeInTheStatusOfFeesPaymentMail($langShrt, $hostEmail, $bookingsData, $totalFees, $currency) {
    global $title, $domain, $projectFolder;
    require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/uni/dictionary/langs/'.$langShrt.'.php';
    $tableContent = "";
    for ($bD=0; $bD < sizeof($bookingsData); $bD++) {
      if ($bookingsData[$bD]['paymentStatus'] == "1") {
        $statusTxt = $wrd_paid;
      } else {
        $statusTxt = $wrd_unpaid;
      }
      $tableContent = $tableContent."
        <tr>
          <td style='border: 1px solid;padding: 9px;'>".$bookingsData[$bD]['fromD'].".".$bookingsData[$bD]['fromM'].".".$bookingsData[$bD]['fromY']." - ".$bookingsData[$bD]['toD'].".".$bookingsData[$bD]['toM'].".".$bookingsData[$bD]['toY']."</td>
          <td style='border: 1px solid;padding: 9px;'><a href='".$domain."/place/?id=".$bookingsData[$bD]['placeID']."'><b>".$bookingsData[$bD]['placeName']."</b></a></td>
          <td style='border: 1px solid;padding: 9px;'>".$statusTxt."</td>
          <td style='border: 1px solid;padding: 9px;'>".addCurrency($currency, $bookingsData[$bD]['fee'])."</td>
        </tr>
      ";
    }
    if ($langShrt == "cz") {
      $subject = "Změna stavu placení poplatků";
      $body = "
        Dobrý den,<br>
        u níže uvedených rezervací došlo ke změně stavu uhrazení poplatků.
        <br>
        <br>
        <table style='width: 100%;max-width: 650px;border: 1px solid;border-collapse: collapse;'>
          <tr>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_dates."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_place."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_status."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_fee."</th>
          </tr>
          ".$tableContent."
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>".$wrd_total."</b></td>
            <td style='border: 1px solid;padding: 9px;text-align: right;' colspan='3'>".addCurrency($currency, $totalFees)."</td>
          </tr>
        </table>
        <br>
        Pokud došlo k chybě a některé z údajů nesedí, kontaktujte nás prosím pomocí <a href='".$domain."/support/'>kontaktního formuláře</a>.
        <br>
        <br>
        S pozdravem,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    } else if ($langShrt == "sk") {
      $subject = "Zmena stavu platenia poplatkov";
      $body = "
        Dobrý deň,<br>
        u nižšie uvedených rezervácií došlo ku zmene stavu uhradenia poplatkov.
        <br>
        <br>
        <table style='width: 100%;max-width: 650px;border: 1px solid;border-collapse: collapse;'>
          <tr>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_dates."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_place."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_status."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_fee."</th>
          </tr>
          ".$tableContent."
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>".$wrd_total."</b></td>
            <td style='border: 1px solid;padding: 9px;text-align: right;' colspan='3'>".addCurrency($currency, $totalFees)."</td>
          </tr>
        </table>
        <br>
        Pokiaľ došlo k chybe a niektoré z údajov nesedia, kontaktujte nás prosím pomocou <a href='".$domain."/support/'>kontaktného formulára</a>.
        <br>
        <br>
        S pozdravom,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    } else {
      $subject = "Change in the status of fees payment";
      $body = "
        Good day,<br>
        for the bookings listed below, the payment status has changed.
        <br>
        <br>
        <table style='width: 100%;max-width: 650px;border: 1px solid;border-collapse: collapse;'>
          <tr>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_dates."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_place."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_status."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_fee."</th>
          </tr>
          ".$tableContent."
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>".$wrd_total."</b></td>
            <td style='border: 1px solid;padding: 9px;text-align: right;' colspan='3'>".addCurrency($currency, $totalFees)."</td>
          </tr>
        </table>
        <br>
        If an error has occurred and some of the data does not match, please contact us using the <a href='".$domain."/support/'>contact form</a>.
        <br>
        <br>
        Regards,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    }
    sendMail($hostEmail, $subject, $body, "change-in-the-status-of-fees-payment");
  }
?>
