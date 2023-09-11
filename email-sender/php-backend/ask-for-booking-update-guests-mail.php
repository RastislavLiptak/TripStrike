<?php
  function askForBookingUpdateGuestsMail($hostLanguage, $hostEmail, $plcID, $plcName, $fromD, $fromM, $fromY, $firstDay, $toD, $toM, $toY, $lastDay, $orgGuestNum, $newGuestNum, $new_total, $price_diff, $price_currency, $bookingID, $g_name, $g_email, $g_phone) {
    global $title, $domain;
    include "../../uni/dictionary/langs/".$hostLanguage.".php";
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
    if ($hostLanguage == "cz") {
      $subject = "Žádost změny počtu hostů v rezervaci v chatě ".$plcName;
      if ($price_diff != 0) {
        $nowPriceTxt = "Nová cena za rezervaci: ".addCurrency($price_currency, $new_total)." (Rozdíl: ".addCurrency($price_currency, $price_diff).")";
      } else {
        $nowPriceTxt = "Cena za rezervaci není touto změnou ovlivněna a činí ".addCurrency($price_currency, $new_total);
      }
      $body = "
        Dobrý den,<br>
        host, který má v chatě <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") vytvořenou rezervaci, zažádal o změnu počtu hostů.
        <br>
        <br>
        <br>
        <b>Původní počet hostů:</b> ".$orgGuestNum."<br>
        <b>Nový počet hostů:</b> ".$newGuestNum."<br>
        <i>".$nowPriceTxt."</i>
        <br>
        <br>
        Tuto změnu můžete <a href='".$domain."/bookings/booking-update.php?plc=".$plcID."&booking=".$bookingID."'>potvrdit nebo zamítnout zde</a><br>
        Dokud tuto změnu nepotvrdíte, platí původní údaje.
        <br>
        <br>
        <br>
        <b>Detaily rezervace:</b><br>
        <b>Místo:</b> <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a><br>
        <b>Od:</b> ".$fromD.".".$fromM.".".$fromY." (".$firstDay.")<br>
        <b>Do:</b> ".$toD.".".$toM.".".$toY." (".$lastDay.")<br>
        <b><a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingID."'>Rezervaci můžete spravovat z tohoto odkazu</a></b>
        <br>
        <br>
        <b>Kontakt na hosta:</b><br>
        <b>Jméno:</b> ".$g_name."<br>
        <b>Email:</b> $g_email<br>
        <b>Telefonní číslo:</b> $g_phone
        <br>
        <br>
        S pozdravem,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    } else if ($hostLanguage == "sk") {
      $subject = "Žiadosť zmeny počtu hostí v rezervácii v chate ".$plcName;
      if ($price_diff != 0) {
        $nowPriceTxt = "Nová cena za rezerváciu: ".addCurrency($price_currency, $new_total)." (Rozdiel: ".addCurrency($price_currency, $price_diff).")";
      } else {
        $nowPriceTxt = "Cena za rezerváciu nie je touto zmenou ovplyvnená a činí ".addCurrency($price_currency, $new_total);
      }
      $body = "
        Dobrý deň,<br>
        hosť, ktorý má v chate <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") vytvorenú rezerváciu, požiadal o zmenu počtu hostí.
        <br>
        <br>
        <br>
        <b>Pôvodný počet hostí:</b> ".$orgGuestNum."<br>
        <b>Nový počet hostí:</b> ".$newGuestNum."<br>
        <i>".$nowPriceTxt."</i>
        <br>
        <br>
        Túto zmenu môžete <a href='".$domain."/bookings/booking-update.php?plc=".$plcID."&booking=".$bookingID."'>potvrdiť alebo zamietnuť tu</a><br>
        Kým túto zmenu nepotvrdíte, platia pôvodné údaje.
        <br>
        <br>
        <br>
        <b>Detaily rezervácie:</b><br>
        <b>Miesto:</b> <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a><br>
        <b>Od:</b> ".$fromD.".".$fromM.".".$fromY." (".$firstDay.")<br>
        <b>Do:</b> ".$toD.".".$toM.".".$toY." (".$lastDay.")<br>
        <b><a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingID."'>Rezerváciu môžete spravovať z tohto odkazu</a></b>
        <br>
        <br>
        <b>Kontakt na hosťa:</b><br>
        <b>Meno:</b> ".$g_name."<br>
        <b>Email:</b> $g_email<br>
        <b>Telefónne číslo:</b> $g_phone
        <br>
        <br>
        S pozdravom,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    } else {
      $subject = "Request to change the number of guests in the booking in the cottage ".$plcName;
      if ($price_diff != 0) {
        $nowPriceTxt = "New booking price: ".addCurrency($price_currency, $new_total)." (Difference: ".addCurrency($price_currency, $price_diff).")";
      } else {
        $nowPriceTxt = "Booking price is not affected by this change and is ".addCurrency($price_currency, $new_total);
      }
      $body = "
        Good day,<br>
        a guest who has a booking made in the <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> cottage for the period ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") has requested a change in the number of guests.
        <br>
        <br>
        <br>
        <b>Original number of guests:</b> ".$orgGuestNum."<br>
        <b>New number of guests:</b> ".$newGuestNum."<br>
        <i>".$nowPriceTxt."</i>
        <br>
        <br>
        You can <a href='".$domain."/bookings/booking-update.php?plc=".$plcID."&booking=".$bookingID."'>confirm or reject this request here</a><br>
        Until you confirm this change, the original data is valid.
        <br>
        <br>
        <br>
        <b>Booking details:</b><br>
        <b>Place:</b> <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a><br>
        <b>From:</b> ".$fromD.".".$fromM.".".$fromY." (".$firstDay.")<br>
        <b>To:</b> ".$toD.".".$toM.".".$toY." (".$lastDay.")<br>
        <b><a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingID."'>You can manage the booking from this link</a></b>
        <br>
        <br>
        <b>Guest contact:</b><br>
        <b>Name:</b> ".$g_name."<br>
        <b>Email:</b> $g_email<br>
        <b>Phone number:</b> $g_phone
        <br>
        <br>
        Regards,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    }
    sendMail($hostEmail, $subject, $body, "ask-for-booking-update-guests-mail");
  }
?>
