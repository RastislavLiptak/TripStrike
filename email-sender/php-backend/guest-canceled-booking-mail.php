<?php
  function guestCanceledBookingMail($bookingID, $plcName, $plcID, $hostEmail, $langShrt, $g_name, $g_email, $g_phone, $guestNum, $totalPrice, $priceCurrency, $depositPaidSts, $fullAmountPaidSts, $fromY, $fromM, $fromD, $firstDay, $toY, $toM, $toD, $lastDay, $twoWeeksBefore) {
    global $title, $domain;
    $priceDeposit = 10 * $totalPrice / 100;
    if ($langShrt == "cz") {
      $subject = "Host zrušil svojí rezervaci - ".$plcName;
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
      if ($twoWeeksBefore) {
        $cancellationFeeTxt = "
          <br>
          Protože ke zrušení došlo více než 2 týdny před začátkem pobytu, máte povinnost vrátit všechny hostem již uhrazené peníze.
        ";
      } else {
        $cancellationFeeTxt = "
          <br>
          Protože ke zrušení došlo méně než 2 týdny před začátkem pobytu, máte povinnost vrátit všechny hostem již uhrazené peníze, s výjimkou depositu, který si můžete, jakožto poplatek za zrušení rezervace, ponechat.
        ";
      }
      if ($depositPaidSts == 0) {
        $depositPaidTxt = "Neuhrazeno";
      } else {
        $depositPaidTxt = "Uhrazeno";
      }
      if ($fullAmountPaidSts == 0) {
        $fullAmountPaidTxt = "Neuhrazeno";
      } else {
        $fullAmountPaidTxt = "Uhrazeno";
      }
      if ($priceDeposit >= 5) {
        $depositTxt = "<b>Záloha:</b> ".addCurrency($priceCurrency, $priceDeposit)." (".$depositPaidTxt.")<br>";
      } else {
        $depositTxt = "";
      }
      $body = "
        Dobrý den,<br>
        rezervace vytvořená v <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a> na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") byla zrušená.
        ".$cancellationFeeTxt."
        <br>
        <br>
        <br>
        <b>Detaily rezervace</b><br>
        <b>Místo:</b> <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a><br>
        <b>Jméno:</b> ".$g_name."<br>
        <b>Email:</b> ".$g_email."<br>
        <b>Telefonní číslo:</b> ".$g_phone."<br>
        <b>Od:</b> ".$fromD.".".$fromM.".".$fromY." (".$firstDay.")<br>
        <b>Do:</b> ".$toD.".".$toM.".".$toY." (".$lastDay.")<br>
        <b>Počet hostů:</b> ".$guestNum."<br>
        <b><a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingID."'>Více informací o rezervaci naleznete zde</a></b>
        <br>
        <br>
        <b>Informace k platbě</b><br>
        ".$depositTxt."
        <b>Celá suma:</b> ".addCurrency($priceCurrency, $totalPrice)." (".$fullAmountPaidTxt.")<br>
        <b>Variabilní symbol:</b> ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."<br>
        <b>Zpráva pro příjemce:</b> ".$g_name."; ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."; ".$plcName."
        <br>
        <br>
        <br>
        S pozdravem,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    } else if ($langShrt == "sk") {
      $subject = "Hosť zrušil svoju rezerváciu - ".$plcName;
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
      if ($twoWeeksBefore) {
        $cancellationFeeTxt = "
          <br>
          Pretože k zrušeniu došlo viac ako 2 týždne pred začiatkom pobytu, máte povinnosť vrátiť všetky hosťom už uhradené peniaze.
        ";
      } else {
        $cancellationFeeTxt = "
          <br>
          Pretože k zrušeniu došlo menej ako 2 týždne pred začiatkom pobytu, máte povinnosť vrátiť všetky hosťom už uhradené peniaze, s výnimkou depositu, ktorý si môžete, ako poplatok za zrušenie rezervácie, ponechať.
        ";
      }
      if ($depositPaidSts == 0) {
        $depositPaidTxt = "Neuhradené";
      } else {
        $depositPaidTxt = "Uhradené";
      }
      if ($fullAmountPaidSts == 0) {
        $fullAmountPaidTxt = "Neuhradené";
      } else {
        $fullAmountPaidTxt = "Uhradené";
      }
      if ($priceDeposit >= 5) {
        $depositTxt = "<b>Záloha:</b> ".addCurrency($priceCurrency, $priceDeposit)." (".$depositPaidTxt.")<br>";
      } else {
        $depositTxt = "";
      }
      $body = "
        Dobrý deň,<br>
        rezervácia vytvorená v <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a> na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") bola zrušená.
        ".$cancellationFeeTxt."
        <br>
        <br>
        <br>
        <b>Detaily rezervácie</b><br>
        <b>Miesto:</b> <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a><br>
        <b>Meno:</b> ".$g_name."<br>
        <b>Email:</b> ".$g_email."<br>
        <b>Telefónne číslo:</b> ".$g_phone."<br>
        <b>Od:</b> ".$fromD.".".$fromM.".".$fromY." (".$firstDay.")<br>
        <b>Do:</b> ".$toD.".".$toM.".".$toY." (".$lastDay.")<br>
        <b>Počet hostí:</b> ".$guestNum."<br>
        <b><a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingID."'>Viac informácií o rezervácii nájdete tu</a></b>
        <br>
        <br>
        <b>Informácie k platbe</b><br>
        ".$depositTxt."
        <b>Celá suma:</b> ".addCurrency($priceCurrency, $totalPrice)." (".$fullAmountPaidTxt.")<br>
        <b>Variabilný symbol:</b> ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."<br>
        <b>Správa pre príjemcu:</b> ".$g_name."; ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."; ".$plcName."
        <br>
        <br>
        <br>
        S pozdravom,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    } else {
      $subject = "The guest canceled his booking - ".$plcName;
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
      if ($twoWeeksBefore) {
        $cancellationFeeTxt = "
          <br>
          As the cancellation took place more than 2 weeks before the start of the stay, you are obliged to return all money already paid by the guest.
        ";
      } else {
        $cancellationFeeTxt = "
          <br>
          As the cancellation took place less than 2 weeks before the start of the stay, you are obliged to return all money already paid by the guest, except for the deposit, which you can keep as a cancellation fee.
        ";
      }
      if ($depositPaidSts == 0) {
        $depositPaidTxt = "Unpaid";
      } else {
        $depositPaidTxt = "Paid";
      }
      if ($fullAmountPaidSts == 0) {
        $fullAmountPaidTxt = "Unpaid";
      } else {
        $fullAmountPaidTxt = "Paid";
      }
      if ($priceDeposit >= 5) {
        $depositTxt = "<b>Deposit:</b> ".addCurrency($priceCurrency, $priceDeposit)." (".$depositPaidTxt.")<br>";
      } else {
        $depositTxt = "";
      }
      $body = "
        Good day,<br>
        the booking in <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a> for the period ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") was canceled.
        ".$cancellationFeeTxt."
        <br>
        <br>
        <br>
        <b>Booking details</b><br>
        <b>Place:</b> <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a><br>
        <b>Name:</b> ".$g_name."<br>
        <b>Email:</b> ".$g_email."<br>
        <b>Phone number:</b> ".$g_phone."<br>
        <b>From:</b> ".$fromD.".".$fromM.".".$fromY." (".$firstDay.")<br>
        <b>To:</b> ".$toD.".".$toM.".".$toY." (".$lastDay.")<br>
        <b>Number of guests:</b> ".$guestNum."<br>
        <b><a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingID."'>More information about this booking can be found here</a></b>
        <br>
        <br>
        <b>Payment information</b><br>
        ".$depositTxt."
        <b>Total amount:</b> ".addCurrency($priceCurrency, $totalPrice)." (".$fullAmountPaidTxt.")<br>
        <b>Variable symbol:</b> ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."<br>
        <b>Message for recipient:</b> ".$g_name."; ".sprintf('%02d', $fromD)."".sprintf('%02d', $fromM)."".$fromY."; ".$plcName."
        <br>
        <br>
        <br>
        Regards,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    }
    sendMail($hostEmail, $subject, $body, "guest-canceled-booking-mail");
  }
?>
