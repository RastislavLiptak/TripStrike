<?php
require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/uni/code/php-backend/get-account-data.php';
function bookingPaymentDetailsMail($fold, $bookingId, $langShrt, $guestName, $guestEmail, $plcID, $hostBeId, $hostID, $plcName, $firstDay, $lastDay, $fromD, $fromM, $fromY, $toD, $toM, $toY, $creationDate, $price, $priceCurrency, $lessThan48h) {
  global $title, $link, $domain;
  include $fold."uni/dictionary/langs/".$langShrt.".php";
  $priceDeposit = 10 * $price / 100;
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
  if ($firstDay == $wrd_theWholeDay) {
    $fromDate = $fromY."-".sprintf("%02d", $fromM)."-".sprintf("%02d", $fromD)." 00:00:00";
  } else {
    $fromDate = $fromY."-".sprintf("%02d", $fromM)."-".sprintf("%02d", $fromD)." 14:00:00";
  }
  $from_diff = abs(strtotime($creationDate) - strtotime($fromDate));
  $from_hours = floor($from_diff / 3600);
  if ($creationDate > $fromDate) {
    $from_hours = $from_hours * (-1);
  }
  $sqlHost = $link->query("SELECT * FROM users WHERE beid='$hostBeId' && status='active'");
  if ($sqlHost->num_rows > 0) {
    $hst = $sqlHost->fetch_assoc();
    $hostName = $hst['firstname']." ".$hst['lastname'];
    $hostEmail = $hst['contactemail'];
    $hostPhone = $hst['contactphonenum'];
    $hostBankAccount = $hst['bankaccount'];
    $hostIBAN = $hst['iban'];
    $hostBICSWIFT = $hst['bicswift'];
    $hostAutomaticCancelationSts = getAccountData($hostBeId, "cancel-unpaid-bookings");
  } else {
    $hostName = $wrd_unknown;
    $hostEmail = $wrd_unknown;
    $hostPhone = $wrd_unknown;
    $hostBankAccount = $wrd_unknown;
    $hostIBAN = $wrd_unknown;
    $hostBICSWIFT = $wrd_unknown;
    $hostAutomaticCancelationSts = 0;
  }
  $subject = $wrd_bookingPaymentDetails;
  if ($langShrt == "cz") {
    if ($priceDeposit >= 5) {
      $depositTxt1 = "zálohu (10% = ".addCurrency($priceCurrency, $priceDeposit).") nebo ";
      $depositTxt2 = "<b>Záloha:</b> ".addCurrency($priceCurrency, $priceDeposit)."<br>";
    } else {
      $depositTxt1 = "";
      $depositTxt2 = "";
    }
    if ($lessThan48h == 0) {
      if ($hostAutomaticCancelationSts == "1") {
        $reservationWillBePermanent = "Rezervace bude stálá, až uhradíte zálohu nebo celou sumu. Pokud tak neučiníte do 48 hodin, rezervace bude automaticky zrušená.";
      } else {
        $reservationWillBePermanent = "";
      }
      $paymentInformation = "
        Uhraďte prosím co nejdříve ".$depositTxt1."celou sumu (".addCurrency($priceCurrency, $price).") na účet hostitele. Do poznámky napište jméno a příjmení, datum prvního dne rezervace a jméno chaty. Jako variabilní symbol použijte datum prvního dne rezervace.
      ";
      $cancellation = "
        Pokud rezervaci zrušíte alespoň dva týdny před prvním dnem rezervace, bude Vám vrácena celá suma. Jinak je stornovací poplatek 10% z celkové sumy, nebo celá záloha.
      ";
    } else {
      $reservationWillBePermanent = "";
      $paymentInformation = "
        Protože byla vaše rezervace vytvořená méně jak 48 hodin před termínem prvního dne, musíte se na způsobu platby dohodnout s hostitelem. Bankovní převod je možný pouze s jeho souhlasem.
      ";
      $cancellation = "
        Stornovací poplatek je 10% z celkové sumy.
      ";
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
    $body = "
      Dobrý den,<br>
      Vaše žádost rezervace chaty <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") byla přijata. ".$reservationWillBePermanent."
      <br>
      <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezervaci můžete spravovat z tohoto odkazu</a></b>
      <br>
      <br>
      <br>
      <b>Informace k platbě</b><br>
      ".$paymentInformation."
      <br>
      <br>
      ".$depositTxt2."
      <b>Celá suma:</b> ".addCurrency($priceCurrency, $price)."<br>
      ".$bankAccountTxt."
      ".$ibanTxt."
      ".$bicSwiftTxt."
      <b>Variabilní symbol:</b> ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."<br>
      <b>Zpráva pro příjemce:</b> ".$guestName."; ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."; ".$plcName."
      <br>
      <br>
      <br>
      <b>Zrušení rezervace:</b><br>
      ".$cancellation."
      <br>
      <br>
      <br>
      <b>Kontakt na hostitele:</b><br>
      <b>Jméno:</b> ".$hostName."<br>
      <b>Email:</b> ".$hostEmail."<br>
      <b>Telefon:</b> ".$hostPhone."<br>
      <br>
      <br>
      S pozdravem,<br>
      <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$hostID."&section=about'>".$hostName."</a>
    ";
  } else if ($langShrt == "sk") {
    if ($priceDeposit >= 5) {
      $depositTxt1 = "zálohu (10% = ".addCurrency($priceCurrency, $priceDeposit).") alebo ";
      $depositTxt2 = "<b>Záloha:</b> ".addCurrency($priceCurrency, $priceDeposit)."<br>";
    } else {
      $depositTxt1 = "";
      $depositTxt2 = "";
    }
    if ($lessThan48h == 0) {
      if ($hostAutomaticCancelationSts == "1") {
        $reservationWillBePermanent = "Rezervácia bude stála, až uhradíte zálohu alebo celú sumu. Ak tak neurobíte do 48 hodín, rezervácia bude automaticky zrušená.";
      } else {
        $reservationWillBePermanent = "";
      }
      $paymentInformation = "
        Uhraďte prosím čo najskôr ".$depositTxt1."celú sumu (".addCurrency($priceCurrency, $price).") na účet hostiteľa. Do poznámky napíšte meno a priezvisko, dátum prvého dňa rezervácie a meno chaty. Ako variabilný symbol použite dátum prvého dňa rezervácie.
      ";
      $cancellation = "
        Ak rezerváciu zrušíte aspoň dva týždne pred prvým dňom rezervácie, bude Vám vrátená celá suma. Inak je stornovací poplatok 10% z celkovej sumy, alebo celá záloha.
      ";
    } else {
      $reservationWillBePermanent = "";
      $paymentInformation = "
        Pretože bola vaša rezervácia vytvorená menej ako 48 hodín pred termínom prvého dňa, musíte sa na spôsobe platby dohodnúť s hostiteľom. Bankový prevod je možný iba s jeho súhlasom.
      ";
      $cancellation = "
        Storno poplatok je 10% z celkovej sumy.
      ";
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
    $body = "
      Dobrý deň,<br>
      Vaša žiadosť rezervácie chaty <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") bola prijatá. ".$reservationWillBePermanent."
      <br>
      <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezerváciu môžete spravovať z tohto odkazu</a></b>
      <br>
      <br>
      <br>
      <b>Informácie k platbe</b><br>
      ".$paymentInformation."
      <br>
      <br>
      ".$depositTxt2."
      <b>Celá suma:</b> ".addCurrency($priceCurrency, $price)."<br>
      ".$bankAccountTxt."
      ".$ibanTxt."
      ".$bicSwiftTxt."
      <b>Variabilný symbol:</b> ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."<br>
      <b>Správa pre príjemcu:</b> ".$guestName."; ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."; ".$plcName."
      <br>
      <br>
      <br>
      <b>Zrušenie rezervácie:</b><br>
      ".$cancellation."
      <br>
      <br>
      <br>
      <b>Kontakt na hostiteľa:</b><br>
      <b>Meno:</b> ".$hostName."<br>
      <b>Email:</b> ".$hostEmail."<br>
      <b>Telefón:</b> ".$hostPhone."<br>
      <br>
      <br>
      S pozdravom,<br>
      <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$hostID."&section=about'>".$hostName."</a>
    ";
  } else {
    if ($priceDeposit >= 5) {
      $depositTxt1 = "the deposit (10% = ".addCurrency($priceCurrency, $priceDeposit).") or ";
      $depositTxt2 = "<b>Deposit:</b> ".addCurrency($priceCurrency, $priceDeposit)."<br>";
    } else {
      $depositTxt1 = "";
      $depositTxt2 = "";
    }
    if ($lessThan48h == 0) {
      if ($hostAutomaticCancelationSts == "1") {
        $reservationWillBePermanent = "The booking will be permanent when you pay the deposit or the full amount. If you do not do so within 48 hours, the reservation will be automatically canceled.";
      } else {
        $reservationWillBePermanent = "";
      }
      $paymentInformation = "
        Please pay the ".$depositTxt1."the full amount (".addCurrency($priceCurrency, $price).") to the host's account as soon as possible. Write the name and surname, the date of the first day of booking and the name of the cottage in the note. Use the date of the first day of booking as a variable symbol.
      ";
      $cancellation = "
        If you cancel your reservation at least two weeks before the first day of booking, the full amount will be refunded. Otherwise, the cancellation fee is 10% of the total amount, or the entire deposit.
      ";
    } else {
      $reservationWillBePermanent = "";
      $paymentInformation = "
        As your reservation was made less than 48 hours before the first day, you must agree on the method of payment with the host. Bank transfer is possible only with his consent.
      ";
      $cancellation = "
        The cancellation fee is 10% of the total amount.
      ";
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
    $body = "
      Good day,<br>
      your request to book cottage <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> for the period ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") was confirmed. ".$reservationWillBePermanent."
      <br>
      <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>You can manage the booking from this link</a></b>
      <br>
      <br>
      <br>
      <b>Payment information</b><br>
      ".$paymentInformation."
      <br>
      <br>
      ".$depositTxt2."
      <b>Total amount:</b> ".addCurrency($priceCurrency, $price)."<br>
      ".$bankAccountTxt."
      ".$ibanTxt."
      ".$bicSwiftTxt."
      <b>Variable symbol:</b> ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."<br>
      <b>Message for the recipient:</b> ".$guestName."; ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."; ".$plcName."
      <br>
      <br>
      <br>
      <b>Cancellation:</b><br>
      ".$cancellation."
      <br>
      <br>
      <br>
      <b>Contact the host:</b><br>
      <b>Name:</b> ".$hostName."<br>
      <b>Email:</b> ".$hostEmail."<br>
      <b>Telephone Number:</b> ".$hostPhone."<br>
      <br>
      <br>
      Regards,<br>
      <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$hostID."&section=about'>".$hostName."</a>
    ";
  }
  sendMail($guestEmail, $subject, $body, "booking-payment-details");
}
?>
