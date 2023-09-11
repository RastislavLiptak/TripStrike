<?php
function hostConfirmedTheBookingMail($fold, $bookingId, $langShrt, $guestEmail, $guestNum, $plcID, $hostBeId, $hostID, $plcName, $firstDay, $lastDay, $fromD, $fromM, $fromY, $toD, $toM, $toY, $price, $priceCurrency) {
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
  $sqlHost = $link->query("SELECT * FROM users WHERE beid='$hostBeId' && status='active'");
  if ($sqlHost->num_rows > 0) {
    $hst = $sqlHost->fetch_assoc();
    $hostName = $hst['firstname']." ".$hst['lastname'];
    $hostEmail = $hst['contactemail'];
    $hostPhone = $hst['contactphonenum'];
  } else {
    $hostName = $wrd_unknown;
    $hostEmail = $wrd_unknown;
    $hostPhone = $wrd_unknown;
  }
  $subject = $wrd_theHostConfirmedYourBooking;
  if ($langShrt == "cz") {
    if ($priceDeposit >= 5) {
      $depositTxt = "<b>Záloha:</b> ".addCurrency($priceCurrency, $priceDeposit)."<br>";
    } else {
      $depositTxt = "";
    }
    $body = "
      Dobrý den,<br>
      Vaše rezervace pobytu v chatě <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") byla hostitelem potvrzena. Již jsme Vám zaslali detailní informace k platbě. Pokud do hodiny nepřijde žádný email, kontaktujte svého hostitele.
      <br>
      <br>
      <br>
      <b>Detaily rezervace</b><br>
      <b>Místo:</b> <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a><br>
      <b>Od:</b> ".$fromD.".".$fromM.".".$fromY." (".$firstDay.")<br>
      <b>Do:</b> ".$toD.".".$toM.".".$toY." (".$lastDay.")<br>
      <b>Počet hostů:</b> ".$guestNum."<br>
      ".$depositTxt."
      <b>Celá suma:</b> ".addCurrency($priceCurrency, $price)."<br>
      <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezervaci můžete spravovat z tohoto odkazu</a></b>
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
      $depositTxt = "<b>Záloha:</b> ".addCurrency($priceCurrency, $priceDeposit)."<br>";
    } else {
      $depositTxt = "";
    }
    $body = "
      Dobrý deň,<br>
      Vaša rezervácia pobytu v chate <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") bola hostiteľom potvrdená. Už sme Vám zaslali detailné informácie k platbe. Pokiaľ do hodiny nepríde žiadny email, kontaktujte svojho hostiteľa.
      <br>
      <br>
      <br>
      <b>Detaily rezervácie</b><br>
      <b>Miesto:</b> <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a><br>
      <b>Od:</b> ".$fromD.".".$fromM.".".$fromY." (".$firstDay.")<br>
      <b>Do:</b> ".$toD.".".$toM.".".$toY." (".$lastDay.")<br>
      <b>Počet hostí:</b> ".$guestNum."<br>
      ".$depositTxt."
      <b>Celá suma:</b> ".addCurrency($priceCurrency, $price)."<br>
      <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezerváciu môžete spravovať z tohto odkazu</a></b>
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
      $depositTxt = "<b>Deposit:</b> ".addCurrency($priceCurrency, $priceDeposit)."<br>";
    } else {
      $depositTxt = "";
    }
    $body = "
      Good day,<br>
      your booking at the <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> cottage for the date ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") has been confirmed by the host. We have already sent you detailed information for payment. If no email arrives within the hour, contact your host.
      <br>
      <br>
      <br>
      <b>Booking details</b><br>
      <b>Place:</b> <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a><br>
      <b>From:</b> ".$fromD.".".$fromM.".".$fromY." (".$firstDay.")<br>
      <b>To:</b> ".$toD.".".$toM.".".$toY." (".$lastDay.")<br>
      <b>Number of guests:</b> ".$guestNum."<br>
      ".$depositTxt."
      <b>Total amount:</b> ".addCurrency($priceCurrency, $price)."<br>
      <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>You can manage the booking from this link</a></b>
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
  sendMail($guestEmail, $subject, $body, "host-confirmed-the-booking");
}
?>
