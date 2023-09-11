<?php
  function askForRatingUpdateMail($bookingBeId, $bookingId, $fromD, $fromM, $fromY, $toD, $toM, $toY, $numOfGuests, $plcId, $plcName, $hostID, $hostFirstname, $hostLastname, $guestContactemail, $guestLanguage, $ratingPlcLct, $ratingPlcTidy, $ratingPlcPrc, $ratingPlcPark, $ratingPlcAd, $ratingHstLang, $ratingHstComm, $ratingHstPrsn) {
    global $title, $domain;
    if ($guestLanguage == "cz") {
      $subject = "Jaký byl Váš pobyt?";
      $body = "
        Dobrý den,<br>
        tímto bychom Vás chtěli požádat o zpětnou vazbu na pobyt v chatě <b>".$plcName."</b>, kde jste měli vytvořenou rezervaci na termín ".$fromD.".".$fromM.".".$fromY." - ".$toD.".".$toM.".".$toY.".
        <br>
        Na tuto chatu již bylo někdy v minulosti hodnocení vyplněné, proto prosím jen zkontrolujte údaje. Pokud se v něčem Váš názor liší, můžete jej <a href='".$domain."/ratings/?booking=".$bookingId."&fromd=".$fromD."&fromm=".$fromM."&fromy=".$fromY."&tod=".$toD."&tom=".$toM."&toy=".$toY."&plc=".$plcId."'>upravit zde</a>.
        <br>
        <br>
        <br>
        <b>Detaily rezervace</b><br>
        <b>Místo:</b> <a href='".$domain."/place/?id=".$plcId."'>".$plcName."</a><br>
        <b>Od:</b> ".$fromD.".".$fromM.".".$fromY."<br>
        <b>Do:</b> ".$toD.".".$toM.".".$toY."<br>
        <b>Počet hostů:</b> ".$numOfGuests."<br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezervaci můžete spravovat z tohoto odkazu</a></b>
        <br>
        <br>
        <b>Hodnocení chaty</b><br>
        <b>Lokace:</b> ".$ratingPlcLct."<br>
        <b>Čistota:</b> ".$ratingPlcTidy."<br>
        <b>Cena:</b> ".$ratingPlcPrc."<br>
        <b>Parkování:</b> ".$ratingPlcPark."<br>
        <b>Inzerát:</b> ".$ratingPlcAd."
        <br>
        <br>
        <b>Hodnocení hostitele</b><br>
        <b>Jazyk:</b> ".$ratingHstLang."<br>
        <b>Komunikace:</b> ".$ratingHstComm."<br>
        <b>Osobnost:</b> ".$ratingHstPrsn."
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
        Na túto chatu už bolo niekedy v minulosti hodnotenia vyplnené, preto prosím len skontrolujte údaje. Ak sa v niečom Váš názor odlišuje, môžete ho <a href='".$domain."/ratings/?booking=".$bookingId."&fromd=".$fromD."&fromm=".$fromM."&fromy=".$fromY."&tod=".$toD."&tom=".$toM."&toy=".$toY."&plc=".$plcId."'>upraviť tu</a>.
        <br>
        <br>
        <br>
        <b>Detaily rezervácie</b><br>
        <b>Miesto:</b> <a href='".$domain."/place/?id=".$plcId."'>".$plcName."</a><br>
        <b>Od:</b> ".$fromD.".".$fromM.".".$fromY."<br>
        <b>Do:</b> ".$toD.".".$toM.".".$toY."<br>
        <b>Počet hostí:</b> ".$numOfGuests."<br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezerváciu môžete spravovať z tohto odkazu</a></b>
        <br>
        <br>
        <b>Hodnotenie chaty</b><br>
        <b>Lokácia:</b> ".$ratingPlcLct."<br>
        <b>Čistota:</b> ".$ratingPlcTidy."<br>
        <b>Cena:</b> ".$ratingPlcPrc."<br>
        <b>Parkovanie:</b> ".$ratingPlcPark."<br>
        <b>Inzerát:</b> ".$ratingPlcAd."
        <br>
        <br>
        <b>Hodnotenie hostiteľa</b><br>
        <b>Jazyk:</b> ".$ratingHstLang."<br>
        <b>Komunikácia:</b> ".$ratingHstComm."<br>
        <b>Osobnosť:</b> ".$ratingHstPrsn."
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
        This cottage has already been rated, so please just check the details. If your opinion differs on something, you can <a href='".$domain."/ratings/?booking=".$bookingId."&fromd=".$fromD."&fromm=".$fromM."&fromy=".$fromY."&tod=".$toD."&tom=".$toM."&toy=".$toY."&plc=".$plcId."'>edit it here</a>.
        <br>
        <br>
        <br>
        <b>Booking details</b><br>
        <b>Place:</b> <a href='".$domain."/place/?id=".$plcId."'>".$plcName."</a><br>
        <b>From:</b> ".$fromD.".".$fromM.".".$fromY."<br>
        <b>To:</b> ".$toD.".".$toM.".".$toY."<br>
        <b>Number of guests:</b> ".$numOfGuests."<br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>You can manage the booking from this link</a></b>
        <br>
        <br>
        <b>Cottage rating</b><br>
        <b>Location:</b> ".$ratingPlcLct."<br>
        <b>Tidiness:</b> ".$ratingPlcTidy."<br>
        <b>Price:</b> ".$ratingPlcPrc."<br>
        <b>Parking:</b> ".$ratingPlcPark."<br>
        <b>Ad:</b> ".$ratingPlcAd."
        <br>
        <br>
        <b>Host rating</b><br>
        <b>Language:</b> ".$ratingHstLang."<br>
        <b>Communication:</b> ".$ratingHstComm."<br>
        <b>Personality:</b> ".$ratingHstPrsn."
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
