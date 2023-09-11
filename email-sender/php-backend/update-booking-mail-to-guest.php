<?php
  function updateBookingMailToGuest($bookingId, $hostID, $hostName, $hostEmail, $hostPhone, $guestName, $guestEmail, $guestPhone, $numOfGuests, $plcID, $plcName, $langShrt, $fromDate, $firstDay, $toDate, $lastDay, $totalprice, $deposit, $fullAmount) {
    global $title, $domain;
    if ($langShrt == "cz") {
      $subject = "Aktualizace rezervace - ".$plcName;
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
      if ($deposit == 1) {
        $deposit = "Uhrazeno";
      } else {
        $deposit = "Neuhrazeno";
      }
      if ($fullAmount == 1) {
        $fullAmount = "Uhrazeno";
      } else {
        $fullAmount = "Neuhrazeno";
      }
      $body = "
        Dobrý den,<br>
        Vaše rezervace chaty <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a> byla dne ".date('d').". ".date('m').". ".date('Y')." (".date('H:i:s').") upravena. Prohlédněte si veškteré údaje a případné nesrovnalosti konzultujte s hostitelem.
        <br>
        <br>
        <h3>Kontaktní údaje</h3>
        <table style='width: 100%;max-width: 650px;border: 1px solid;border-collapse: collapse;'>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Jméno</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$guestName."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Email</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$guestEmail."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Telefonní číslo</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$guestPhone."</td>
          </tr>
        </table>
        <br>
        <h3>Detaily rezervace</h3>
        <table style='width: 100%;max-width: 650px;border: 1px solid;border-collapse: collapse;'>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Jméno místa</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$plcName."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Od</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$fromDate." (".$firstDay.")</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Do</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$toDate." (".$lastDay.")</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Počet hostů</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$numOfGuests."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Celá suma</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$totalprice."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Zaplacení zálohy</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$deposit."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Zaplacení celé sumy</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$fullAmount."</td>
          </tr>
        </table>
        <br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezervaci můžete spravovat z tohoto odkazu</a></b>
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
      $subject = "Aktualizácia rezervácie - ".$plcName;
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
      if ($deposit == 1) {
        $deposit = "Uhradené";
      } else {
        $deposit = "Neuhradené";
      }
      if ($fullAmount == 1) {
        $fullAmount = "Uhradené";
      } else {
        $fullAmount = "Neuhradené";
      }
      $body = "
        Dobrý deň,<br>
        Vaša rezervácia chaty <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a> bola dňa ".date('d').". ".date('m').". ".date('Y')." (".date('H:i:s').") upravená. Prezrite si veškteré údaje a prípadné nezrovnalosti konzultujte s hostiteľom.
        <br>
        <br>
        <h3>Kontaktné údaje</h3>
        <table style='width: 100%;max-width: 650px;border: 1px solid;border-collapse: collapse;'>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Meno</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$guestName."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Email</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$guestEmail."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Telefónne číslo</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$guestPhone."</td>
          </tr>
        </table>
        <br>
        <h3>Detaily rezervácie</h3>
        <table style='width: 100%;max-width: 650px;border: 1px solid;border-collapse: collapse;'>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Meno miesta</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$plcName."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Od</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$fromDate." (".$firstDay.")</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Do</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$toDate." (".$lastDay.")</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Počet hostí</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$numOfGuests."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Celá suma</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$totalprice."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Zaplatenie zálohy</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$deposit."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Zaplatenie celej sumy</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$fullAmount."</td>
          </tr>
        </table>
        <br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezerváciu môžete spravovať z tohto odkazu</a></b>
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
      $subject = "Booking update - ".$plcName;
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
      if ($deposit == 1) {
        $deposit = "Paid";
      } else {
        $deposit = "Unpaid";
      }
      if ($fullAmount == 1) {
        $fullAmount = "Paid";
      } else {
        $fullAmount = "Unpaid";
      }
      $body = "
        Good day,<br>
        Your booking for cottage <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a> was updated on ".date('d').". ".date('m').". ".date('Y')." (".date('H:i:s')."). Check all the details and consult the host for any discrepancies.
        <br>
        <br>
        <h3>Contact details</h3>
        <table style='width: 100%;max-width: 650px;border: 1px solid;border-collapse: collapse;'>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Name</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$guestName."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Email</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$guestEmail."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Telephone number</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$guestPhone."</td>
          </tr>
        </table>
        <br>
        <h3>Booking details</h3>
        <table style='width: 100%;max-width: 650px;border: 1px solid;border-collapse: collapse;'>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Place name</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$plcName."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>From</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$fromDate." (".$firstDay.")</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>To</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$toDate." (".$lastDay.")</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Number of guests</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$numOfGuests."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Full amount</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$totalprice."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Payment of deposit</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$deposit."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Payment of the full amount</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$fullAmount."</td>
          </tr>
        </table>
        <br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>You can manage the booking from this link</a></b>
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
    sendMail($guestEmail, $subject, $body, "booking-update-mail-to-guest");
  }
?>
