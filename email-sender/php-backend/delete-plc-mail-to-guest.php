<?php
  function deletePlcMailToGuest($plcID, $hostID, $hostName, $hostEmail, $hostPhone, $email, $name, $language, $fromy, $fromm, $fromd, $firstDay, $toy, $tom, $tod, $lastDay, $guestNum, $creationDateY, $creationDateM, $creationDateD) {
    global $title, $domain;
    if ($language == "cz") {
      $subject = "Zrušení rezervace - ".$name;
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
      $body = "
        Dobrý den,<br>
        chata <b>".$name."</b>, na kterou jste měli vytvořenou rezervaci, byla odstraněna z nabídky <a href='".$domain."'>".$title."</a>. Z tohoto důvodu byla Vaše rezervace dne ".date('d').". ".date('m').". ".date('Y')." (".date('H:i:s').") zrušena.<br>
        Pro více informací kontaktujte hostitele.
        <br>
        <br>
        <b>Detaily rezervace</b><br>
        <b>Místo:</b> <a href='".$domain."/place/?id=".$plcID."'>".$name."</a><br>
        <b>Od:</b> ".$fromd.".".$fromm.".".$fromy." (".$firstDay.")<br>
        <b>Do:</b> ".$tod.".".$tom.".".$toy." (".$lastDay.")<br>
        <b>Počet hostů:</b> ".$guestNum."<br>
        <b>Datum vytvoření rezervace:</b> ".$creationDateD.".".$creationDateM.".".$creationDateY."
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
    } else if ($language == "sk") {
      $subject = "Zrušenie rezervácie - ".$name;
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
      $body = "
        Dobrý deň,<br>
        chata <b>".$name."</b>, na ktorú ste mali vytvorenú rezerváciu, bola odstránená z ponuky <a href='".$domain."'>".$title."</a>. Z tohto dôvodu bola Vaša rezervácia dňa ".date('d').". ".date('m').". ".date('Y')." (".date('H:i:s').") zrušená.<br>
        Pre viac informácií kontaktujte hostiteľa.
        <br>
        <br>
        <b>Detaily rezervácie</b><br>
        <b>Miesto:</b> <a href='".$domain."/place/?id=".$plcID."'>".$name."</a><br>
        <b>Od:</b> ".$fromd.".".$fromm.".".$fromy." (".$firstDay.")<br>
        <b>Do:</b> ".$tod.".".$tom.".".$toy." (".$lastDay.")<br>
        <b>Počet hostí:</b> ".$guestNum."<br>
        <b>Dátum vytvorenia rezervácie:</b> ".$creationDateD.".".$creationDateM.".".$creationDateY."
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
      $subject = "Cancellation of booking - ".$name;
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
      $body = "
        Good day,<br>
        cottage <b>".$name."</b>, for which you had a booking, was removed from the menu of <a href='".$domain."'>".$title."</a>. Due to this, your booking was canceled on ".date('d').". ".date('m').". ".date('Y')." (".date('H:i:s').").<br>
        Contact the host for more information.
        <br>
        <br>
        <b>Booking details</b><br>
        <b>Place:</b> <a href='".$domain."/place/?id=".$plcID."'>".$name."</a><br>
        <b>From:</b> ".$fromd.".".$fromm.".".$fromy." (".$firstDay.")<br>
        <b>To:</b> ".$tod.".".$tom.".".$toy." (".$lastDay.")<br>
        <b>Number of guests:</b> ".$guestNum."<br>
        <b>Date of booking creation:</b> ".$creationDateD.".".$creationDateM.".".$creationDateY."
        <br>
        <br>
        <br>
        <b>Contact the host:</b><br>
        <b>Name:</b> ".$hostName."<br>
        <b>Email:</b> ".$hostEmail."<br>
        <b>Telephone number:</b> ".$hostPhone."<br>
        <br>
        <br>
        Regards,<br>
        <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$hostID."&section=about'>".$hostName."</a>
      ";
    }

    sendMail($email, $subject, $body, "place-deleted-mail-to-guest");
  }
?>
