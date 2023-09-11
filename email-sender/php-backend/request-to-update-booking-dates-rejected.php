<?php
  function requestToUpdateBookingDatesRejected($bookingId, $plcName, $plcID, $newFromD, $newFromM, $newFromY, $newFirstDay, $newToD, $newToM, $newToY, $newLastDay, $orgFromD, $orgFromM, $orgFromY, $orgFirstDay, $orgToD, $orgToM, $orgToY, $orgLastDay, $guestNum, $g_language, $g_email, $hostID, $h_name, $h_email, $h_phone) {
    global $title, $domain;
    include "../../uni/dictionary/langs/".$g_language.".php";
    if ($orgFirstDay == "whole") {
      $orgFirstDay = $wrd_theWholeDay;
    } else {
      $orgFirstDay = $wrd_from." 14:00";
    }
    if ($orgLastDay == "whole") {
      $orgLastDay = $wrd_theWholeDay;
    } else {
      $orgLastDay = $wrd_to." 11:00";
    }
    if ($newFirstDay == "whole") {
      $newFirstDay = $wrd_theWholeDay;
    } else {
      $newFirstDay = $wrd_from." 14:00";
    }
    if ($newLastDay == "whole") {
      $newLastDay = $wrd_theWholeDay;
    } else {
      $newLastDay = $wrd_to." 11:00";
    }
    if ($g_language == "cz") {
      $subject = "Vaše žádost o změnu termínu rezervace byla zamítnuta";
      $body = "
        Dobrý den,<br>
        Vaše žádost o změnu termínu na <b>".$newFromD.".".$newFromM.".".$newFromY." (".$newFirstDay.") - ".$newToD.".".$newToM.".".$newToY." (".$newLastDay.")</b> byla zamítnuta.
        <br>
        <br>
        <b>Detaily rezervace:</b><br>
        <b>Místo:</b> <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a></br>
        <b>Od:</b> ".$orgFromD.".".$orgFromM.".".$orgFromY." (".$orgFirstDay.")</br>
        <b>Do:</b> ".$orgToD.".".$orgToM.".".$orgToY." (".$orgLastDay.")</br>
        <b>Počet hostů:</b> ".$guestNum."<br>
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
      $subject = "Vaša žiadosť o zmenu termínu rezervácie bola zamietnutá";
      $body = "
        Dobrý deň,<br>
        Vaša žiadosť o zmenu termínu na <b>".$newFromD.".".$newFromM.".".$newFromY." (".$newFirstDay.") - ".$newToD.".".$newToM.".".$newToY." (".$newLastDay.")</b> bola zamietnutá.
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
      $subject = "Your request to change the booking date has been rejected";
      $body = "
        Good day,<br>
        Your request to change the date to <b>".$newFromD.".".$newFromM.".".$newFromY." (".$newFirstDay.") - ".$newToD.".".$newToM.".".$newToY." (".$newLastDay.")</b> has been rejected.
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
    sendMail($g_email, $subject, $body, "request-to-update-booking-dates-rejected");
  }
?>
