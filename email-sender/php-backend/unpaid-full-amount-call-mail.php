<?php
  function unpaidFullAmountCallMail($bookingId, $g_email, $g_language, $fromD, $fromM, $fromY, $firstDay, $toD, $toM, $toY, $lastDay, $totalprice, $totalcurrency, $plcID, $plc_name, $h_ID, $h_firstname, $h_lastname, $h_email, $h_phone) {
    global $title, $projectFolder, $domain;
    if ($firstDay == "whole") {
      $firstDay = "00:00";
    } else {
      $firstDay = "14:00";
    }
    if ($lastDay == "whole") {
      $lastDay = "00:00";
    } else {
      $lastDay = "11:00";
    }
    if ($g_language == "cz") {
      $subject = "Domluvte si způsob platby ".$plc_name;
      $body = "
        Dobrý den,<br>
        pokud jste s hostitelem již nějak domluvení na platbě celé sumy za Vaší rezervaci chaty <a href='".$domain."/place/?id=".$plcID."'><b>".$plc_name."</b></a> na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay."), tento email se Vás netýká. Pokud ale ne, co nejdřív telefonicky kontaktujte svého hostitele a domluvte se na způsobu doplacení celé sumy <b>".addCurrency($totalcurrency, $totalprice)."</b> za rezervaci.
        <br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezervaci můžete spravovat z tohoto odkazu</a></b>
        <br>
        <br>
        <b>Kontakt na hostitele:</b><br>
        <b>Jméno:</b> ".$h_firstname." ".$h_lastname."<br>
        <b>Email:</b> ".$h_email."<br>
        <b>Telefon:</b> ".$h_phone."<br>
        <br>
        S pozdravem,<br>
        <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$h_ID."&section=about'>".$h_firstname." ".$h_lastname."</a>
      ";
    } else if ($g_language == "sk") {
      $subject = "Dohodnite si spôsob platby ".$plc_name;
      $body = "
        Dobrý deň,<br>
        ak ste s hostiteľom už nejako dohodnutie na platbe celej sumy za Vašej rezerváciu chaty <a href='".$domain."/place/?id=".$plcID."'><b>".$plc_name."</b></a> na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay."), tento email sa Vás netýka. Pokiaľ ale nie, čo najskôr telefonicky kontaktujte svojho hostiteľa a dohodnite sa na spôsobe doplatenie celej sumy <b>".addCurrency($totalcurrency, $totalprice)."</b> za rezerváciu.
        <br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>Rezerváciu môžete spravovať z tohto odkazu</a></b>
        <br>
        <br>
        <b>Kontakt na hostiteľa:</b><br>
        <b>Meno:</b> ".$h_firstname." ".$h_lastname."<br>
        <b>Email:</b> ".$h_email."<br>
        <b>Telefón:</b> ".$h_phone."<br>
        <br>
        S pozdravom,<br>
        <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$h_ID."&section=about'>".$h_firstname." ".$h_lastname."</a>
      ";
    } else {
      $subject = "Agree on the method of payment ".$plc_name;
      $body = "
        Good day,<br>
        If you have already agreed with the host on the payment of the full amount for your reservation of the cottage <a href='".$domain."/place/?id=".$plcID."'><b>".$plc_name."</b></a> for the date ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay."), this email does not apply to you. If not, contact your host by phone as soon as possible to agree on a full payment of <b>".addCurrency($totalcurrency, $totalprice)."</b> per booking.
        <br>
        <b><a href='".$domain."/bookings/about-my-booking.php?id=".$bookingId."'>You can manage the booking from this link</a></b>
        <br>
        <br>
        <b>Contact the host:</b><br>
        <b>Name:</b> ".$h_firstname." ".$h_lastname."<br>
        <b>Email:</b> ".$h_email."<br>
        <b>Telephone:</b> ".$h_phone."<br>
        <br>
        Regards,<br>
        <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$h_ID."&section=about'>".$h_firstname." ".$h_lastname."</a>
      ";
    }
    sendMail($g_email, $subject, $body, "unpaid-full-amount-call-mail");
  }
?>
