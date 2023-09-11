<?php
  function confirmationOfFeesPaymentMail($langShrt, $hostEmail, $bookingsData, $totalFees, $currency) {
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
      $subject = "Potvrzení o zaplacení poplatků";
      $body = "
        Dobrý den,<br>
        potvrzujeme přijetí platby poplatků za níže uvedené rezervace.
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
        Pokud jste uhradily i rezervace, které zde nevidíte, zkontrolujte si prosím znovu svou poštu, jestli nebudou vypsány v jiné zprávě. V případě, že je nenajdete, nebo se budete chtít ujistit, <a href='".$domain."/support/'>kontaktujte nás prosím zde</a>.
        <br>
        <br>
        S pozdravem,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    } else if ($langShrt == "sk") {
      $subject = "Potvrdenie o zaplatení poplatkov";
      $body = "
        Dobrý deň,<br>
        potvrdzujeme prijatie platby poplatkov za nižšie uvedené rezervácie.
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
        Ak ste uhradili aj rezervácie, ktoré tu nevidíte, skontrolujte si prosím znova svoju poštu, či nebudú vypísané v inej správe. V prípade, že ich nenájdete, alebo sa budete chcieť uistiť, <a href='".$domain."/support/'kontaktujte nás prosím tu</a>.
        <br>
        <br>
        S pozdravom,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    } else {
      $subject = "Confirmation of fees payment";
      $body = "
        Good day,<br>
        we confirm receipt of payment of the fees for the bookings listed below.
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
        If you have also paid for bookings that you do not see here, please check your mail again to see if they will be listed in another message. If you do not find them or you want to make sure, <a href='".$domain."/support/'please contact us here</a>.
        <br>
        <br>
        Regards,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    }
    sendMail($hostEmail, $subject, $body, "confirmation-of-fees-payment");
  }
?>
