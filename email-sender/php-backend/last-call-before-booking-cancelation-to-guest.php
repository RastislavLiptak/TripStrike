<?php
function lastCallBeforeBookingCancelationToGuest($bookingId, $plcID, $plcName, $plcCurrency, $guestName, $guestEmail, $guestLanguage, $bookingTotalPrice, $fromY, $fromM, $fromD, $firstDay, $toY, $toM, $toD, $lastDay, $hostID, $hostFirstname, $hostLastname, $hostEmail, $hostPhone, $hostBankAccount, $hostIBAN, $hostBICSWIFT) {
  global $title, $link, $domain;
  include "../uni/dictionary/langs/".$guestLanguage.".php";
  $priceDeposit = 10 * $bookingTotalPrice / 100;
  if ($firstDay == "whole") {
    $firstDay = $wrd_theWholeDay;
  } else {
    $firstDay = $wrd_from." 14:00";
  }
  if ($lastDay == "whole") {
    $lastDay = $wrd_theWholeDay;
  } else {
    $lastDay = $wrd_to." 11:00";
  }
  if ($guestLanguage == "cz") {
    if ($priceDeposit >= 5) {
      $depositTxt1 = "ani zálohy (".addCurrency($plcCurrency, $priceDeposit).") ";
      $depositTxt2 = "<b>Záloha:</b> ".addCurrency($plcCurrency, $priceDeposit)."<br>";
    } else {
      $depositTxt1 = "";
      $depositTxt2 = "";
    }
    if ($hostBankAccount != "-") {
      $bankAccountTxt = "<b>Účet:</b> ".$hostBankAccount."<br>";
    } else {
      $bankAccountTxt = "";
    }
    if ($hostIBAN != "-") {
      $ibanTxt = "<b>IBAN:</b> ".$hostIBAN."<br>";
    } else {
      $ibanTxt = "";
    }
    if ($hostBICSWIFT != "-") {
      $bicSwiftTxt = "<b>BIC/SWIFT:</b> ".$hostBICSWIFT."<br>";
    } else {
      $bicSwiftTxt = "";
    }
    $subject = "Poslední výzva k platbě";
    $body = "
      Dobrý den,<br>
      dovolujeme si Vás upozornit, že zatím neproběhla platba celé sumy (".addCurrency($plcCurrency, $bookingTotalPrice).") ".$depositTxt1."za rezervaci na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") v místě
      <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a>. Pokud rezervaci nezaplatíte do 5 hodin, Vaše rezervace bude automaticky zrušená. Platbu prosím nahlaste svému hostiteli.
      <br>
      <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezervaci můžete spravovat z tohoto odkazu</a></b>
      <br>
      <br>
      V případě, že došlo k omylu a peníze jste již uhradily, kontaktujte o této skutečnosti hostitele.
      <br>
      <br>
      <br>
      <b>Informace k platbě</b><br>
      ".$depositTxt2."
      <b>Celá suma:</b> ".addCurrency($plcCurrency, $bookingTotalPrice)."<br>
      ".$bankAccountTxt."
      ".$ibanTxt."
      ".$bicSwiftTxt."
      <b>Variabilní symbol:</b> ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."<br>
      <b>Zpráva pro příjemce:</b> ".$guestName."; ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."; ".$plcName."
      <br>
      <br>
      <br>
      <b>Kontakt na hostitele:</b><br>
      <b>Jméno:</b> ".$hostFirstname." ".$hostLastname."<br>
      <b>Email:</b> ".$hostEmail."<br>
      <b>Telefon:</b> ".$hostPhone."<br>
      <br>
      <br>
      S pozdravem,<br>
      <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$hostID."&section=about'>".$hostFirstname." ".$hostLastname."</a>
    ";
  } else if ($guestLanguage == "sk") {
    if ($priceDeposit >= 5) {
      $depositTxt1 = "ani zálohy (".addCurrency($plcCurrency, $priceDeposit).") ";
      $depositTxt2 = "<b>Záloha:</b> ".addCurrency($plcCurrency, $priceDeposit)."<br>";
    } else {
      $depositTxt1 = "";
      $depositTxt2 = "";
    }
    if ($hostBankAccount != "-") {
      $bankAccountTxt = "<b>Účet:</b> ".$hostBankAccount."<br>";
    } else {
      $bankAccountTxt = "";
    }
    if ($hostIBAN != "-") {
      $ibanTxt = "<b>IBAN:</b> ".$hostIBAN."<br>";
    } else {
      $ibanTxt = "";
    }
    if ($hostBICSWIFT != "-") {
      $bicSwiftTxt = "<b>BIC/SWIFT:</b> ".$hostBICSWIFT."<br>";
    } else {
      $bicSwiftTxt = "";
    }
    $subject = "Posledná výzva k platbe";
    $body = "
      Dobrý deň,<br>
      dovoľujeme si Vás upozorniť, že zatiaľ neprebehla platba celej sumy (".addCurrency($plcCurrency, $bookingTotalPrice).") ".$depositTxt1."za rezerváciu na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") v mieste
      <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a>. Ak rezerváciu nezaplatíte do 5 hodín, Vaša rezervácia bude automaticky zrušená. Platbu prosím nahláste svojmu hostiteľovi.
      <br>
      <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezerváciu môžete spravovať z tohto odkazu</a></b>
      <br>
      <br>
      V prípade, že došlo k omylu a peniaze ste už uhradili, kontaktujte o tejto skutočnosti hostiteľa.
      <br>
      <br>
      <br>
      <b>Informácie k platbe</b><br>
      ".$depositTxt2."
      <b>Celá suma:</b> ".addCurrency($plcCurrency, $bookingTotalPrice)."<br>
      ".$bankAccountTxt."
      ".$ibanTxt."
      ".$bicSwiftTxt."
      <b>Variabilný symbol:</b> ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."<br>
      <b>Správa pre príjemcu:</b> ".$guestName."; ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."; ".$plcName."
      <br>
      <br>
      <br>
      <b>Kontakt na hostiteľa:</b><br>
      <b>Meno:</b> ".$hostFirstname." ".$hostLastname."<br>
      <b>Email:</b> ".$hostEmail."<br>
      <b>Telefón:</b> ".$hostPhone."<br>
      <br>
      <br>
      S pozdravom,<br>
      <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$hostID."&section=about'>".$hostFirstname." ".$hostLastname."</a>
    ";
  } else {
    if ($priceDeposit >= 5) {
      $depositTxt1 = "and the deposit (".addCurrency($plcCurrency, $priceDeposit).") ";
      $depositTxt2 = "<b>Deposit:</b> ".addCurrency($plcCurrency, $priceDeposit)."<br>";
    } else {
      $depositTxt1 = "";
      $depositTxt2 = "";
    }
    if ($hostBankAccount != "-") {
      $bankAccountTxt = "<b>Account:</b> ".$hostBankAccount."<br>";
    } else {
      $bankAccountTxt = "";
    }
    if ($hostIBAN != "-") {
      $ibanTxt = "<b>IBAN:</b> ".$hostIBAN."<br>";
    } else {
      $ibanTxt = "";
    }
    if ($hostBICSWIFT != "-") {
      $bicSwiftTxt = "<b>BIC/SWIFT:</b> ".$hostBICSWIFT."<br>";
    } else {
      $bicSwiftTxt = "";
    }
    $subject = "Last request for payment";
    $body = "
      Good day,<br>
      we would like to inform you that the full amount (".addCurrency($plcCurrency, $bookingTotalPrice).") ".$depositTxt1."for the booking ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") in
      <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> have not been paid yet. If you do not pay for this booking within 5 hours, it will be automatically canceled. Please report the payment to your host.
      <br>
      <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>You can manage the booking from this link</a></b>
      <br>
      <br>
      In case of an error and you have already paid the money, contact the host.
      <br>
      <br>
      <br>
      <b>Payment information</b><br>
      ".$depositTxt2."
      <b>Total amount:</b> ".addCurrency($plcCurrency, $bookingTotalPrice)."<br>
      ".$bankAccountTxt."
      ".$ibanTxt."
      ".$bicSwiftTxt."
      <b>Variable symbol:</b> ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."<br>
      <b>Message for the recipient:</b> ".$guestName."; ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."; ".$plcName."
      <br>
      <br>
      <br>
      <b>Contact the host:</b><br>
      <b>Name:</b> ".$hostFirstname." ".$hostLastname."<br>
      <b>Email:</b> ".$hostEmail."<br>
      <b>Telephone Number:</b> ".$hostPhone."<br>
      <br>
      <br>
      Regards,<br>
      <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$hostID."&section=about'>".$hostFirstname." ".$hostLastname."</a>
    ";
  }
  sendMail($guestEmail, $subject, $body, "last-call-before-booking-cancelation-to-guest");
}
?>
