<?php
  function pricingOfTheBookingHasBeenChangedMail($bookingId, $priceSts, $langShrt, $plcID, $plcName, $bookingStatus, $guestName, $guestEmail, $hostID, $hostName, $hostEmail, $hostPhone, $hostBankAccount, $hostIBAN, $hostBICSWIFT, $fromY, $fromM, $fromD, $firstDay, $toY, $toM, $toD, $lastDay, $oldPrice, $oldCurrency, $newPrice, $newCurrency) {
    global $title, $domain;
    if ($langShrt == "cz") {
      $subject = "Změna ceny za rezervaci chaty ".$plcName;
      if ($firstDay == "whole") {
        $firstDay = "Celý den";
      } else {
        $firstDay = "od 14:00";
      }
      if ($lastDay == "whole") {
        $lastDay = "Celý den";
      } else {
        $lastDay = "do 11:00";
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
      if ($bookingStatus == "booked") {
        $paymentInfo = "
          ".$bankAccountTxt."
          ".$ibanTxt."
          ".$bicSwiftTxt."
          <b>Variabilní symbol:</b> ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."<br>
          <b>Zpráva pro příjemce:</b> ".$guestName."; ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."; ".$plcName."
        ";
      } else {
        $paymentInfo = "";
      }
      $body = "
        Dobrý den,<br>
        byla upravená cena za jednu noc u chaty <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a>, kde máte na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") vytvořenou rezervaci. Kvůli tomu došlo ke změně ceny Vašeho pobytu, která je nyní ve výši ".addCurrency($newCurrency, $newPrice).". Pokud máte dotazy, kontaktujte svého hostitele.
        <br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezervaci můžete spravovat z tohoto odkazu</a></b>
        <br>
        <br>
        <b>Informace k platbě</b><br>
        <b>Stará cena:</b> ".addCurrency($oldCurrency, $oldPrice)."<br>
        <b>Nová cena:</b> ".addCurrency($newCurrency, $newPrice)."<br>
        ".$paymentInfo."
        <br>
        <br>
        <b>Kontakt na hostitele:</b><br>
        <b>Jméno:</b> ".$hostName."<br>
        <b>Email:</b> ".$hostEmail."<br>
        <b>Telefon:</b> ".$hostPhone."<br>
        <br>
        S pozdravem,<br>
        <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$hostID."&section=about'>".$hostName."</a>
      ";
    } else if ($langShrt == "sk") {
      $subject = "Zmena ceny za rezerváciu chaty ".$plcName;
      if ($firstDay == "whole") {
        $firstDay = "Celý deň";
      } else {
        $firstDay = "od 14:00";
      }
      if ($lastDay == "whole") {
        $lastDay = "Celý deň";
      } else {
        $lastDay = "do 11:00";
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
      if ($bookingStatus == "booked") {
        $paymentInfo = "
          ".$bankAccountTxt."
          ".$ibanTxt."
          ".$bicSwiftTxt."
          <b>Variabilný symbol:</b> ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."<br>
          <b>Správa pre príjemcu:</b> ".$guestName."; ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."; ".$plcName."
        ";
      } else {
        $paymentInfo = "";
      }
      $body = "
        Dobrý deň,<br>
        bola upravená cena za jednu noc u chaty <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a>, kde máte na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") vytvorenú rezerváciu. Kvôli tomu došlo k zmene ceny Vášho pobytu, ktorá je teraz vo výške ".addCurrency($newCurrency, $newPrice).". Ak máte otázky, kontaktujte svojho hostiteľa.
        <br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezerváciu môžete spravovať z tohto odkazu</a></b>
        <br>
        <br>
        <b>Informácie k platbe</b><br>
        <b>Stará cena:</b> ".addCurrency($oldCurrency, $oldPrice)."<br>
        <b>Nová cena:</b> ".addCurrency($newCurrency, $newPrice)."<br>
        ".$paymentInfo."
        <br>
        <br>
        <b>Kontakt na hostiteľa:</b><br>
        <b>Meno:</b> ".$hostName."<br>
        <b>Email:</b> ".$hostEmail."<br>
        <b>Telefón:</b> ".$hostPhone."<br>
        <br>
        S pozdravom,<br>
        <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$hostID."&section=about'>".$hostName."</a>
      ";
    } else {
      $subject = "Change of price for booking a cottage ".$plcName;
      if ($firstDay == "whole") {
        $firstDay = "Whole day";
      } else {
        $firstDay = "from 14:00";
      }
      if ($lastDay == "whole") {
        $lastDay = "Whole day";
      } else {
        $lastDay = "to 11:00";
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
      if ($bookingStatus == "booked") {
        $paymentInfo = "
          ".$bankAccountTxt."
          ".$ibanTxt."
          ".$bicSwiftTxt."
          <b>Variable symbol:</b> ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."<br>
          <b>Message for the recipient:</b> ".$guestName."; ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."; ".$plcName."
        ";
      } else {
        $paymentInfo = "";
      }
      $body = "
        Good day,<br>
        the price for one night at the cottage <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a>, where you have made a reservation for the period ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay."), has been changed. Due to this, the price of your stay has adjusted and is now ".addCurrency($newCurrency, $newPrice).". If you have any questions, contact your host.
        <br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>You can manage the booking from this link</a></b>
        <br>
        <br>
        <b>Payment information</b><br>
        <b>Old price:</b> ".addCurrency($oldCurrency, $oldPrice)."<br>
        <b>New price:</b> ".addCurrency($newCurrency, $newPrice)."<br>
        ".$paymentInfo."
        <br>
        <br>
        <b>Contact the host:</b><br>
        <b>Name:</b> ".$hostName."<br>
        <b>Email:</b> ".$hostEmail."<br>
        <b>Telephone Number:</b> ".$hostPhone."<br>
        <br>
        Regards,<br>
        <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$hostID."&section=about'>".$hostName."</a>
      ";
    }
    sendMail($guestEmail, $subject, $body, "pricing-of-the-cottage-changed-".$priceSts);
  }
?>
