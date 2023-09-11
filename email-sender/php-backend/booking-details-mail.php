<?php
  function bookingDetailsMail($fold, $bookingID, $lngShrt, $hostBeId, $hostEmail, $plcID, $plcName, $guestName, $guestEmail, $guestPhone, $numOfGuests, $firstDay, $lastDay, $fromDate, $toDate, $creationDate, $price, $feePerc, $priceCurrency) {
    global $title, $domain;
    include $fold."uni/dictionary/get-lang-shortcut.php";
    $fee = $feePerc * $price / 100;
    if ($lngShrt == "cz") {
      $subject = "Detaily rezervace - ".$plcName;
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
        Pro chatu <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> byla vytvořená nová rezervace:<br>
        <h3>Detaily rezervace</h3>
        <table style='width: 100%;max-width: 650px;border: 1px solid;border-collapse: collapse;'>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Datum a čas vytvoření rezervace</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$creationDate."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Chata</b></td>
            <td style='border: 1px solid;padding: 9px;'><a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a></td>
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
            <td style='border: 1px solid;padding: 9px;'><b>Cena</b></td>
            <td style='border: 1px solid;padding: 9px;'>".addCurrency($priceCurrency, $price)."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Poplatek</b></td>
            <td style='border: 1px solid;padding: 9px;'>".addCurrency($priceCurrency, $fee)." (".($feePerc + 0)."%)</td>
          </tr>
        </table>
        <br>
        <h3>Host</h3>
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
        <b><a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingID."'>Rezervaci můžete spravovat z tohoto odkazu</a></b>
        <br>
        <br>
        S pozdravem,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    } else if ($lngShrt == "sk") {
      $subject = "Detaily rezervácie - ".$plcName;
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
        Pre chatu <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> bola vytvorená nová rezervácia:<br>
        <h3>Detaily rezervácie</h3>
        <table style='width: 100%;max-width: 650px;border: 1px solid;border-collapse: collapse;'>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Dátum a čas vytvorenia rezervácie</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$creationDate.""."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Chata</b></td>
            <td style='border: 1px solid;padding: 9px;'><a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a></td>
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
            <td style='border: 1px solid;padding: 9px;'><b>Cena</b></td>
            <td style='border: 1px solid;padding: 9px;'>".addCurrency($priceCurrency, $price)."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Poplatok</b></td>
            <td style='border: 1px solid;padding: 9px;'>".addCurrency($priceCurrency, $fee)." (".($feePerc + 0)."%)</td>
          </tr>
        </table>
        <br>
        <h3>Host</h3>
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
        <b><a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingID."'>Rezerváciu môžete spravovať z tohto odkazu</a></b>
        <br>
        <br>
        S pozdravom,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    } else {
      $subject = "Booking details - ".$plcName;
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
        A new booking has been created for the cottage <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a>:<br>
        <h3>Booking details</h3>
        <table style='width: 100%;max-width: 650px;border: 1px solid;border-collapse: collapse;'>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Date and time of making the booking</b></td>
            <td style='border: 1px solid;padding: 9px;'>".$creationDate.""."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Cottage</b></td>
            <td style='border: 1px solid;padding: 9px;'><a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a></td>
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
            <td style='border: 1px solid;padding: 9px;'><b>Price</b></td>
            <td style='border: 1px solid;padding: 9px;'>".addCurrency($priceCurrency, $price)."</td>
          </tr>
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>Fee</b></td>
            <td style='border: 1px solid;padding: 9px;'>".addCurrency($priceCurrency, $fee)." (".($feePerc + 0)."%)</td>
          </tr>
        </table>
        <br>
        <h3>Guest</h3>
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
        <b><a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingID."'>You can manage the booking from this link</a></b>
        <br>
        <br>
        Regards,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    }
    sendMail($hostEmail, $subject, $body, "booking-details");
  }
?>
