<?php
  function payFullAmountMailAlert($bookingId, $g_email, $g_name, $g_language, $fromD, $fromM, $fromY, $firstDay, $toD, $toM, $toY, $lastDay, $totalprice, $totalcurrency, $plcID, $plc_name, $h_bankAccount, $h_iban, $h_bicswift, $h_ID, $h_firstname, $h_lastname, $h_email, $h_phone) {
    global $title, $domain, $projectFolder;
    $priceDeposit = 10 * $totalprice / 100;
    if ($firstDay == "whole") {
      $firstDay = "00:00";
    } else {
      $firstDay = "14:00";
    }
    if ($lastDay == "whole") {
      $lastDay = "00:00";
    } else {
      $lastDay = "11:00";
    }
    if ($g_language == "cz") {
      if ($priceDeposit >= 5) {
        $aboutPrice = "
          <b>Záloha:</b> ".addCurrency($totalcurrency, $priceDeposit)."<br>
          <b>Celá suma:</b> ".addCurrency($totalcurrency, $totalprice)."<br>
        ";
      } else {
        $aboutPrice = "
          <b>Celá suma:</b> ".addCurrency($totalcurrency, $totalprice)."<br>
        ";
      }
      if ($h_bankAccount != "-") {
        $bankAccountTxt = "<b>Účet:</b> ".$h_bankAccount."<br>";
      } else {
        $bankAccountTxt = "";
      }
      if ($h_iban != "-") {
        $ibanTxt = "<b>IBAN:</b> ".$h_iban."<br>";
      } else {
        $ibanTxt = "";
      }
      if ($h_bicswift != "-") {
        $bicSwiftTxt = "<b>BIC/SWIFT:</b> ".$h_bicswift."<br>";
      } else {
        $bicSwiftTxt = "";
      }
      $subject = "Výzva k doplacení celé sumy rezervace chaty ".$plc_name;
      $body = "
        Dobrý den,<br>
        pokud jste s hostitelem již nějak domluvení na platbě celé sumy za Vaší rezervaci chaty  <a href='".$domain."/place/?id=".$plcID."'><b>".$plc_name."</b></a> na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay."), tento email se Vás netýká. V opačném případě co nejdříve uhraďte zbývající sumu, nebo kontaktujte svého hostitele.
        <br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezervaci můžete spravovat z tohoto odkazu</a></b>
        <br>
        <br>
        <b>Informace k platbě:</b><br>
        ".$aboutPrice."
        ".$bankAccountTxt."
        ".$ibanTxt."
        ".$bicSwiftTxt."
        <b>Variabilní symbol:</b> ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."<br>
        <b>Zpráva pro příjemce:</b> ".$g_name."; ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."; ".$plc_name."
        <br>
        <br>
        <b>Kontakt na hostitele:</b><br>
        <b>Jméno:</b> ".$h_firstname." ".$h_lastname."<br>
        <b>Email:</b> ".$h_email."<br>
        <b>Telefon:</b> ".$h_phone."<br>
        <br>
        <br>
        S pozdravem,<br>
        <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$h_ID."&section=about'>".$h_firstname." ".$h_lastname."</a>
      ";
    } else if ($g_language == "sk") {
      if ($priceDeposit >= 5) {
        $aboutPrice = "
          <b>Záloha:</b> ".addCurrency($totalcurrency, $priceDeposit)."<br>
          <b>Celá suma:</b> ".addCurrency($totalcurrency, $totalprice)."<br>
        ";
      } else {
        $aboutPrice = "
          <b>Celá suma:</b> ".addCurrency($totalcurrency, $totalprice)."<br>
        ";
      }
      if ($h_bankAccount != "-") {
        $bankAccountTxt = "<b>Účet:</b> ".$h_bankAccount."<br>";
      } else {
        $bankAccountTxt = "";
      }
      if ($h_iban != "-") {
        $ibanTxt = "<b>IBAN:</b> ".$h_iban."<br>";
      } else {
        $ibanTxt = "";
      }
      if ($h_bicswift != "-") {
        $bicSwiftTxt = "<b>BIC/SWIFT:</b> ".$h_bicswift."<br>";
      } else {
        $bicSwiftTxt = "";
      }
      $subject = "Výzva na doplatenie celej sumy rezervácie chaty ".$plc_name;
      $body = "
        Dobrý deň,<br>
        ak ste s hostiteľom už nejako dohodnutie na platbe celej sumy za Vašej rezerváciu chaty  <a href='".$domain."/place/?id=".$plcID."'><b>".$plc_name."</b></a> na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay."), tento email sa Vás netýka. V opačnom prípade čo najskôr uhraďte zostávajúce sumu, alebo kontaktujte svojho hostiteľa.
        <br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezerváciu môžete spravovať z tohto odkazu</a></b>
        <br>
        <br>
        <b>Informácie k platbe:</b><br>
        ".$aboutPrice."
        ".$bankAccountTxt."
        ".$ibanTxt."
        ".$bicSwiftTxt."
        <b>Variabilný symbol:</b> ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."<br>
        <b>Správa pre príjemcu:</b> ".$g_name."; ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."; ".$plc_name."
        <br>
        <br>
        <b>Kontakt na hostiteľa:</b><br>
        <b>Meno:</b> ".$h_firstname." ".$h_lastname."<br>
        <b>Email:</b> ".$h_email."<br>
        <b>Telefón:</b> ".$h_phone."<br>
        <br>
        <br>
        S pozdravom,<br>
        <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$h_ID."&section=about'>".$h_firstname." ".$h_lastname."</a>
      ";
    } else {
      if ($priceDeposit >= 5) {
        $aboutPrice = "
          <b>Deposit:</b> ".addCurrency($totalcurrency, $priceDeposit)."<br>
          <b>Total amount:</b> ".addCurrency($totalcurrency, $totalprice)."<br>
        ";
      } else {
        $aboutPrice = "
          <b>Total amount:</b> ".addCurrency($totalcurrency, $totalprice)."<br>
        ";
      }
      if ($h_bankAccount != "-") {
        $bankAccountTxt = "<b>Account:</b> ".$h_bankAccount."<br>";
      } else {
        $bankAccountTxt = "";
      }
      if ($h_iban != "-") {
        $ibanTxt = "<b>IBAN:</b> ".$h_iban."<br>";
      } else {
        $ibanTxt = "";
      }
      if ($h_bicswift != "-") {
        $bicSwiftTxt = "<b>BIC/SWIFT:</b> ".$h_bicswift."<br>";
      } else {
        $bicSwiftTxt = "";
      }
      $subject = "Call for payment of the full amount of the cottage reservation ".$plc_name;
      $body = "
        Good day,<br>
        If you have already agreed with the host on the payment of the full amount for your reservation of the cottage  <a href='".$domain."/place/?id=".$plcID."'><b>".$plc_name."</b></a> for the date ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay."), this email does not apply to you. Otherwise, pay the remaining amount as soon as possible or contact your host.
        <br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>You can manage the booking from this link</a></b>
        <br>
        <br>
        <b>Payment information:</b><br>
        ".$aboutPrice."
        ".$bankAccountTxt."
        ".$ibanTxt."
        ".$bicSwiftTxt."
        <b>Variable symbol:</b> ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."<br>
        <b>Message for the recipient:</b> ".$g_name."; ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."; ".$plc_name."
        <br>
        <br>
        <b>Contact the host:</b><br>
        <b>Name:</b> ".$h_firstname." ".$h_lastname."<br>
        <b>Email:</b> ".$h_email."<br>
        <b>Telephone:</b> ".$h_phone."<br>
        <br>
        <br>
        Regards,<br>
        <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$h_ID."&section=about'>".$h_firstname." ".$h_lastname."</a>
      ";
    }
    sendMail($g_email, $subject, $body, "pay-full-amount-mail-alert");
  }
?>
