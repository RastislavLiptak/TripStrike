<?php
function lastCallBeforeBookingCancelationToHost($bookingId, $plcID, $plcName, $plcCurrency, $guestName, $guestEmail, $guestPhonenum, $bookingNotes, $bookingGuestnum, $bookingTotalPrice, $bookingFeePerc, $fromY, $fromM, $fromD, $firstDay, $toY, $toM, $toD, $lastDay, $hostEmail, $hostLanguage) {
  global $title, $link, $domain;
  include "../uni/dictionary/langs/".$hostLanguage.".php";
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
  $bookingFee = $bookingFeePerc * $bookingTotalPrice / 100;
  if ($hostLanguage == "cz") {
    if ($priceDeposit >= 5) {
      $depositTxt1 = "zálohy (".addCurrency($plcCurrency, $priceDeposit).") ani ";
      $depositTxt2 = "<b>Záloha:</b> ".addCurrency($plcCurrency, $priceDeposit)."<br>";
    } else {
      $depositTxt1 = "";
      $depositTxt2 = "";
    }
    $subject = "Upozornění na automatickému zrušení rezervace";
    $body = "
      Dobrý den,<br>
      dovolujeme si Vás upozornit, že nemáme v systému informaci o platbě ".$depositTxt1."celé sumy (".addCurrency($plcCurrency, $bookingTotalPrice).") za rezervaci na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") v místě <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a>. Pokud zatím nedošlo k platbě, ani k jakékoliv formě dohody s hostem, můžete tuto zprávu ignorovat, již jsme na kontaktní email hosta zaslali upozornění o zaplacení rezervace a kontaktování Vaší osoby.
      <br>
      <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezervaci můžete spravovat z tohoto odkazu</a></b>
      <br>
      <br>
      Jestli ale máte zajištěnou formu odměny za Vaše služby, zaškrtněte políčko <i>Zaplacení celé sumy</i>, nebo <i>Zaplacení depozitu</i> v <a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingId."'>tomto odkazu</a>. Pokud tak neučiníte, rezervace bude do 5 hodin automaticky zrušená.
      <br>
      <br>
      <br>
      <b>Detaily rezervace</b><br>
      <b>Místo:</b> <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a><br>
      <b>Jméno:</b> ".$guestName."<br>
      <b>Email:</b> ".$guestEmail."<br>
      <b>Telefonní číslo:</b> ".$guestPhonenum."<br>
      <b>Od:</b> ".$fromD.".".$fromM.".".$fromY." (".$firstDay.")<br>
      <b>Do:</b> ".$toD.".".$toM.".".$toY." (".$lastDay.")<br>
      <b>Počet hostů:</b> ".$bookingGuestnum."<br>
      $depositTxt2
      <b>Celkem:</b> ".addCurrency($plcCurrency, $bookingTotalPrice)."<br>
      <b>Poplatek:</b> ".addCurrency($plcCurrency, $bookingFee)." (".($bookingFeePerc + 0)."%)<br>
      <b>Poznámky:</b> ".$bookingNotes."<br>
      <b><a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingId."'>Rezervaci můžete spravovat z tohoto odkazu</a></b><br>
      <br>
      <br>
      S pozdravem,<br>
      <a href='".$domain."'>".$title."</a>
    ";
  } else if ($hostLanguage == "sk") {
    if ($priceDeposit >= 5) {
      $depositTxt1 = "zálohy (".addCurrency($plcCurrency, $priceDeposit).") ani ";
      $depositTxt2 = "<b>Záloha:</b> ".addCurrency($plcCurrency, $priceDeposit)."<br>";
    } else {
      $depositTxt1 = "";
      $depositTxt2 = "";
    }
    $subject = "Upozornenie na automatickému zrušenie rezervácie";
    $body = "
      Dobrý deň,<br>
      dovoľujeme si Vás upozorniť, že nemáme v systéme informáciu o platbe ".$depositTxt1."celej sumy (".addCurrency($plcCurrency, $bookingTotalPrice).") za rezerváciu na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") v mieste <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a>. Ak ešte nedošlo k platbe, ani k akejkoľvek forme dohody s hosťom, môžete túto správu ignorovať, už sme na kontaktný email hosťa zaslali upozornenie o zaplatení rezervácie a kontaktu Vašej osoby.
      <br>
      <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezerváciu môžete spravovať z tohto odkazu</a></b>
      <br>
      <br>
      Ak ale máte zaistenú formu odmeny za Vaše služby, zaškrtnite políčko <i>Zaplatenie celej sumy</i>, alebo <i>Zaplatenie depozitu</i> v <a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingId."'>tomto odkaze</a>. Pokiaľ tak neurobíte, rezervácia bude do 5 hodín automaticky zrušená.
      <br>
      <br>
      <br>
      <b>Detaily rezervácie</b><br>
      <b>Miesto:</b> <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a><br>
      <b>Meno:</b> ".$guestName."<br>
      <b>Email:</b> ".$guestEmail."<br>
      <b>Telefónne číslo:</b> ".$guestPhonenum."<br>
      <b>Od:</b> ".$fromD.".".$fromM.".".$fromY." (".$firstDay.")<br>
      <b>Do:</b> ".$toD.".".$toM.".".$toY." (".$lastDay.")<br>
      <b>Počet hostí:</b> ".$bookingGuestnum."<br>
      $depositTxt2
      <b>Celkom:</b> ".addCurrency($plcCurrency, $bookingTotalPrice)."<br>
      <b>Poplatok:</b> ".addCurrency($plcCurrency, $bookingFee)." (".($bookingFeePerc + 0)."%)<br>
      <b>Poznámky:</b> ".$bookingNotes."<br>
      <b><a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingId."'>Rezerváciu môžete spravovať z tohto odkazu</a></b><br>
      <br>
      <br>
      S pozdravom,<br>
      <a href='".$domain."'>".$title."</a>
    ";
  } else {
    if ($priceDeposit >= 5) {
      $depositTxt1 = "the deposit (".addCurrency($plcCurrency, $priceDeposit).") or ";
      $depositTxt2 = "<b>Deposit:</b> ".addCurrency($plcCurrency, $priceDeposit)."<br>";
    } else {
      $depositTxt1 = "";
      $depositTxt2 = "";
    }
    $subject = "Notification about automatic booking cancellation";
    $body = "
      Good day,<br>
      please note that we do not have information in the system about the payment of ".$depositTxt1."the full amount (".addCurrency($plcCurrency, $bookingTotalPrice).") for the booking on ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") in <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a>. If no payment has been made yet, or any form of agreement with the guest, you can ignore this message, we have already sent a notification to the guest's contact email about the payment of the booking and contacting your person.
      <br>
      <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>You can manage the booking from this link</a></b>
      <br>
      <br>
      However, if you have a secured form of reward for your services, check the box <i>Payment of the full amount</i> or <i>Payment of the deposit</i> in <a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingId."'>this link</a>. If you do not do so, the reservation will be automatically canceled within 5 hours.
      <br>
      <br>
      <br>
      <b>Booking details</b><br>
      <b>Place:</b> <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a><br>
      <b>Name:</b> ".$guestName."<br>
      <b>Email:</b> ".$guestEmail."<br>
      <b>Telephone number:</b> ".$guestPhonenum."<br>
      <b>From:</b> ".$fromD.".".$fromM.".".$fromY." (".$firstDay.")<br>
      <b>To:</b> ".$toD.".".$toM.".".$toY." (".$lastDay.")<br>
      <b>Number of guests:</b> ".$bookingGuestnum."<br>
      $depositTxt2
      <b>Total amount:</b> ".addCurrency($plcCurrency, $bookingTotalPrice)."<br>
      <b>Fee:</b> ".addCurrency($plcCurrency, $bookingFee)." (".($bookingFeePerc + 0)."%)<br>
      <b>Notes:</b> ".$bookingNotes."<br>
      <b><a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingId."'>You can manage the booking from this link</a></b><br>
      <br>
      <br>
      Regards,<br>
      <a href='".$domain."'>".$title."</a>
    ";
  }
  sendMail($hostEmail, $subject, $body, "last-call-before-booking-cancelation-to-host");
}
?>
