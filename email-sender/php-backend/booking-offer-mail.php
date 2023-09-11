<?php
function bookingOfferMail($langShrt, $hostEmail, $guestNum, $plcID, $plcName, $bookingID, $firstDay, $lastDay, $fromD, $fromM, $fromY, $toD, $toM, $toY, $price, $feePerc, $priceCurrency) {
  global $title, $link, $domain;
  include "../../uni/dictionary/langs/".$langShrt.".php";
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
  $subject = $wrd_bookingOffer;
  if ($langShrt == "cz") {
    $body = "
      Dobrý den,<br>
      na Vaši chatu <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> byla vytvořena žádost o rezervaci pobytu.
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
    $body = "
      Dobrý deň,<br>
      na Vašu chatu <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> bola vytvorená žiadosť o rezerváciu pobytu.
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
    $body = "
      Good day,<br>
      a request to book a stay has been created for your cottage <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a>.
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
  sendMail($hostEmail, $subject, $body, "booking-offer");
}
?>
