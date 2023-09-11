<?php
  function automaticBookingRejectionAlertMail($langShrt, $hostEmail, $guestNum, $plcID, $plcName, $bookingID, $firstDay, $lastDay, $fromD, $fromM, $fromY, $toD, $toM, $toY, $price, $feePerc, $priceCurrency) {
    global $title, $link, $domain;
    include "../uni/dictionary/langs/".$langShrt.".php";
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
    $fee = $feePerc * $price / 100;
    if ($langShrt == "cz") {
      $subject = "Připomenutí nabídky rezervace";
      $body = "
        Dobrý den,<br>
        na chatu <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> máte na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") žádost o rezervaci, která zatím nebyla potvrzena, ani zamítnuta. V případě, že máte o tuto rezervaci zájem, potvrďte ji co možná nejdřív, jinak bude do 5 hodin automaticky zamítnuta.
        <br>
        <br>
        <b>Místo:</b> <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a><br>
        <b>Od:</b> ".$fromD.".".$fromM.".".$fromY." (".$firstDay.")<br>
        <b>Do:</b> ".$toD.".".$toM.".".$toY." (".$lastDay.")<br>
        <b>Počet hostů:</b> ".$guestNum."<br>
        <b>Cena:</b> ".addCurrency($priceCurrency, $price)."<br>
        <b>Poplatek:</b> ".addCurrency($priceCurrency, $fee)." (".($feePerc + 0)."%)<br>
        <b><a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingID."'>Rezervaci můžete spravovat z tohoto odkazu</a></b>
        <br>
        <br>
        <i>Více informací Vám poskytneme, až potvrdíte rezervaci</i>
        <br>
        <br>
        Máte o tuto rezervaci zájem?
        <br>
        <a href='".$domain."/bookings/booking-offer.php?task=confirm&plc=".$plcID."&booking=".$bookingID."'>Potvrdit</a> / <a href='".$domain."/bookings/booking-offer.php?task=reject&plc=".$plcID."&booking=".$bookingID."'>Zamítnout</a>
        <br>
        <br>
        S pozdravem,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    } else if ($langShrt == "sk") {
      $subject = "Pripomenutie ponuky rezervácie";
      $body = "
        Dobrý deň,<br>
        na chate <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> máte na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") žiadosť o rezerváciu, ktorá zatiaľ nebola potvrdená, ani zamietnutá. V prípade, že máte o túto rezerváciu záujem, potvrďte ju čo možno najskôr, inak bude do 5 hodín automaticky zamietnutá.
        <br>
        <br>
        <b>Miesto:</b> <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a><br>
        <b>Od:</b> ".$fromD.".".$fromM.".".$fromY." (".$firstDay.")<br>
        <b>Do:</b> ".$toD.".".$toM.".".$toY." (".$lastDay.")<br>
        <b>Počet hostí:</b> ".$guestNum."<br>
        <b>Cena:</b> ".addCurrency($priceCurrency, $price)."<br>
        <b>Poplatok:</b> ".addCurrency($priceCurrency, $fee)." (".($feePerc + 0)."%)<br>
        <b><a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingID."'>Rezerváciu môžete spravovať z tohto odkazu</a></b>
        <br>
        <br>
        <i>Viac informácií Vám poskytneme, až potvrdíte rezerváciu</i>
        <br>
        <br>
        Máte o túto rezerváciu záujem?
        <br>
        <a href='".$domain."/bookings/booking-offer.php?task=confirm&plc=".$plcID."&booking=".$bookingID."'>Potvrdiť</a> / <a href='".$domain."/bookings/booking-offer.php?task=reject&plc=".$plcID."&booking=".$bookingID."'>Zamietnuť</a>
        <br>
        <br>
        S pozdravom,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    } else {
      $subject = "Reservation offer reminder";
      $body = "
        Good day,<br>
        for the date ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") in the cottage <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> you have a reservation request that has not been confirmed or rejected yet. If you are interested in this reservation, confirm it as soon as possible, otherwise it will be automatically rejected within 5 hours.
        <br>
        <br>
        <b>Place:</b> <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a><br>
        <b>From:</b> ".$fromD.".".$fromM.".".$fromY." (".$firstDay.")<br>
        <b>To:</b> ".$toD.".".$toM.".".$toY." (".$lastDay.")<br>
        <b>Number of guests:</b> ".$guestNum."<br>
        <b>Price:</b> ".addCurrency($priceCurrency, $price)."<br>
        <b>Fee:</b> ".addCurrency($priceCurrency, $fee)." (".($feePerc + 0)."%)<br>
        <b><a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingID."'>You can manage the booking from this link</a></b>
        <br>
        <br>
        <i>We will provide you with more information when you confirm this booking</i>
        <br>
        <br>
        Are you interested in this booking?
        <br>
        <a href='".$domain."/bookings/booking-offer.php?task=confirm&plc=".$plcID."&booking=".$bookingID."'>Confirm</a> / <a href='".$domain."/bookings/booking-offer.php?task=reject&plc=".$plcID."&booking=".$bookingID."'>Reject</a>
        <br>
        <br>
        Regards,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    }
    sendMail($hostEmail, $subject, $body, "booking-offer-reminder");
  }
?>
