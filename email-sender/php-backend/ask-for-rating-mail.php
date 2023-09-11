<?php
  function askForRatingMail($bookingBeId, $bookingId, $fromD, $fromM, $fromY, $toD, $toM, $toY, $numOfGuests, $plcID, $plcName, $hostID, $hostFirstname, $hostLastname, $guestContactemail, $guestLanguage) {
    global $title, $domain;
    if ($guestLanguage == "cz") {
      $subject = "Jaký byl Váš pobyt?";
      $body = "
        Dobrý den,<br>
        tímto bychom Vás chtěli požádat o zpětnou vazbu na pobyt v chatě <b>".$plcName."</b>, kde jste měli vytvořenou rezervaci na termín ".$fromD.".".$fromM.".".$fromY." - ".$toD.".".$toM.".".$toY.".
        <br>
        Váš názor nám můžete předat zodpovězením několika otázek, které <a href='".$domain."/ratings/?booking=".$bookingId."&fromd=".$fromD."&fromm=".$fromM."&fromy=".$fromY."&tod=".$toD."&tom=".$toM."&toy=".$toY."&plc=".$plcID."'>najdete zde</a>.
        <br>
        <br>
        <br>
        <b>Detaily rezervace</b><br>
        <b>Místo:</b> <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a><br>
        <b>Od:</b> ".$fromD.".".$fromM.".".$fromY."<br>
        <b>Do:</b> ".$toD.".".$toM.".".$toY."<br>
        <b>Počet hostů:</b> ".$numOfGuests."<br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezervaci můžete spravovat z tohoto odkazu</a></b>
        <br>
        <br>
        <br>
        S pozdravem,<br>
        <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$hostID."&section=about'>".$hostFirstname." ".$hostLastname."</a>
      ";
    } else if ($guestLanguage == "sk") {
      $subject = "Aký bol Váš pobyt?";
      $body = "
        Dobrý deň,<br>
        týmto by sme Vás chceli požiadať o spätnú väzbu na pobyt v chate <b>".$plcName."</b>, kde ste mali vytvorenú rezerváciu na termín ".$fromD.".".$fromM.".".$fromY." - ".$toD.".".$toM.".".$toY.".
        <br>
        Váš názor nám môžete odovzdať zodpovedaním niekoľkých otázok, ktoré <a href='".$domain."/ratings/?booking=".$bookingId."&fromd=".$fromD."&fromm=".$fromM."&fromy=".$fromY."&tod=".$toD."&tom=".$toM."&toy=".$toY."&plc=".$plcID."'>nájdete tu</a>.
        <br>
        <br>
        <br>
        <b>Detaily rezervácie</b><br>
        <b>Miesto:</b> <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a><br>
        <b>Od:</b> ".$fromD.".".$fromM.".".$fromY."<br>
        <b>Do:</b> ".$toD.".".$toM.".".$toY."<br>
        <b>Počet hostí:</b> ".$numOfGuests."<br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezerváciu môžete spravovať z tohto odkazu</a></b>
        <br>
        <br>
        <br>
        S pozdravom,<br>
        <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$hostID."&section=about'>".$hostFirstname." ".$hostLastname."</a>
      ";
    } else {
      $subject = "How was your stay?";
      $body = "
        Good day,<br>
        we would like to ask you for feedback on your stay in the cottage <b>".$plcName."</b>, where you had made a booking for the period ".$fromD.".".$fromM.".".$fromY." - ".$toD.".".$toM.".".$toY.".
        <br>
        You can give us your opinion by answering a few questions, which you can <a href='".$domain."/ratings/?booking=".$bookingId."&fromd=".$fromD."&fromm=".$fromM."&fromy=".$fromY."&tod=".$toD."&tom=".$toM."&toy=".$toY."&plc=".$plcID."'>find here</a>.
        <br>
        <br>
        <br>
        <b>Booking details</b><br>
        <b>Place:</b> <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a><br>
        <b>From:</b> ".$fromD.".".$fromM.".".$fromY."<br>
        <b>To:</b> ".$toD.".".$toM.".".$toY."<br>
        <b>Number of guests:</b> ".$numOfGuests."<br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>You can manage the booking from this link</a></b>
        <br>
        <br>
        <br>
        Regards,<br>
        <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$hostID."&section=about'>".$hostFirstname." ".$hostLastname."</a>
      ";
    }
    sendMail($guestContactemail, $subject, $body, $bookingBeId);
  }
?>
