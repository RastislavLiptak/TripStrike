<?php
  function requestToUpdateNumberOfGuestsBookingRejected($bookingId, $plcName, $plcID, $fromD, $fromM, $fromY, $firstDay, $toD, $toM, $toY, $lastDay, $newGuestNum, $orgGuestNum, $g_language, $g_email, $hostID, $h_name, $h_email, $h_phone) {
    global $title, $domain;
    include "../../uni/dictionary/langs/".$g_language.".php";
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
    if ($g_language == "cz") {
      $subject = "Vaše žádost o změnu počtu hostů v rezervaci byla zamítnuta";
      $body = "
        Dobrý den,<br>
        Vaše žádost o změnu počtu hostů na <b>".$newGuestNum."</b> byla zamítnuta.
        <br>
        <br>
        <b>Detaily rezervace:</b><br>
        <b>Místo:</b> <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a></br>
        <b>Od:</b> ".$fromD.".".$fromM.".".$fromY." (".$firstDay.")</br>
        <b>Do:</b> ".$toD.".".$toM.".".$toY." (".$lastDay.")</br>
        <b>Počet hostů:</b> ".$orgGuestNum."<br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezervaci můžete spravovat z tohoto odkazu</a></b>
        <br>
        <br>
        <b>Kontakt na hostitele:</b><br>
        <b>Jméno:</b> ".$h_name."<br>
        <b>Email:</b> ".$h_email."<br>
        <b>Telefon:</b> ".$h_phone."<br>
        <br>
        S pozdravem,<br>
        <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$hostID."&section=about'>".$h_name."</a>
      ";
    } else if ($g_language == "sk") {
      $subject = "Vaša žiadosť o zmenu počtu hostí v rezervácii bola zamietnutá";
      $body = "
        Dobrý deň,<br>
        Vaša žiadosť o zmenu počtu hostí na <b>".$newGuestNum."</b> bola zamietnutá.
        <br>
        <br>
        <b>Detaily rezervácie:</b><br>
        <b>Miesto:</b> <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a></br>
        <b>Od:</b> ".$orgFromD.".".$orgFromM.".".$orgFromY." (".$orgFirstDay.")</br>
        <b>Do:</b> ".$orgToD.".".$orgToM.".".$orgToY." (".$orgLastDay.")</br>
        <b>Počet hostí:</b> ".$guestNum."<br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezerváciu môžete spravovať z tohto odkazu</a></b>
        <br>
        <br>
        <b>Kontakt na hostiteľa:</b><br>
        <b>Meno:</b> ".$h_name."<br>
        <b>Email:</b> ".$h_email."<br>
        <b>Telefón:</b> ".$h_phone."<br>
        <br>
        S pozdravom,<br>
        <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$hostID."&section=about'>".$h_name."</a>
      ";
    } else {
      $subject = "Your request to change the number of guests in the booking has been rejected";
      $body = "
        Good day,<br>
        Your request to change the number of guests to <b>".$newGuestNum."</b> has been rejected.
        <br>
        <br>
        <b>Booking details:</b><br>
        <b>Place:</b> <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a></br>
        <b>From:</b> ".$orgFromD.".".$orgFromM.".".$orgFromY." (".$orgFirstDay.")</br>
        <b>To:</b> ".$orgToD.".".$orgToM.".".$orgToY." (".$orgLastDay.")</br>
        <b>Number of guests:</b> ".$guestNum."<br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>You can manage the booking from this link</a></b>
        <br>
        <br>
        <b>Contact the host:</b><br>
        <b>Name:</b> ".$h_name."<br>
        <b>Email:</b> ".$h_email."<br>
        <b>Telephone:</b> ".$h_phone."<br>
        <br>
        Regards,<br>
        <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$hostID."&section=about'>".$h_name."</a>
      ";
    }
    sendMail($g_email, $subject, $body, "request-to-update-number-of-guests-booking-rejected");
  }
?>
