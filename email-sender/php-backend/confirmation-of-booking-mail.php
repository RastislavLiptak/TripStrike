<?php
function confirmationOfBookingMail($bookingId, $langShrt, $guestEmail, $guestNum, $plcID, $hostBeId, $hostID, $plcName, $firstDay, $lastDay, $fromD, $fromM, $fromY, $toD, $toM, $toY, $price, $priceCurrency, $lessThan48h) {
  global $title, $link, $domain;
  include "../../uni/dictionary/langs/".$langShrt.".php";
  $date = date("Y-m-d H:i:s");
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
  if ($firstDay == $wrd_theWholeDay) {
    $fromDate = $fromY."-".sprintf("%02d", $fromM)."-".sprintf("%02d", $fromD)." 00:00:00";
  } else {
    $fromDate = $fromY."-".sprintf("%02d", $fromM)."-".sprintf("%02d", $fromD)." 14:00:00";
  }
  $from_diff = abs(strtotime($date) - strtotime($fromDate));
  $from_hours = floor($from_diff / 3600);
  if ($date > $fromDate) {
    $from_hours = $from_hours * (-1);
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
  $subject = $wrd_confirmationOfTheCreationOfTheBookingRequest;
  if ($langShrt == "cz") {
    if ($priceDeposit >= 5) {
      $depositTxt = "<b>Záloha:</b> ".addCurrency($priceCurrency, $priceDeposit)."<br>";
    } else {
      $depositTxt = "";
    }
    if ($lessThan48h == 0) {
      $contactYourHost = "";
      $cancellation = "
        Pokud rezervaci zrušíte alespoň dva týdny před prvním dnem rezervace, bude Vám vrácena celá suma. Jinak je stornovací poplatek 10% z celkové sumy, nebo celá záloha
      ";
    } else {
      $contactYourHost = "
        <br>
        Vzhledem k tomu, že je vaše rezervace vytvořené méně než 48h před prvním dnem, kontaktujte prosím Vašeho hostitele, z důvodu potvrzení pobytu.
      ";
      $cancellation = "
        Stornovací poplatek je 10% z celkové sumy.
      ";
    }
    $body = "
      Dobrý den,<br>
      potvrzujeme vytvoření žádosti o rezervaci chaty <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay."). Nyní prosím vyčkejte na potvrzení od hostitele, které by jste měli obdržet do 48 hodin. Pokud se tak nestane, kontaktujte prosím Vašeho hostitele.
      <br>
      Po obdržení potvrzení od hostitele Vám budou zaslány instrukce a informace k platbě.
      ".$contactYourHost."
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
      <b>Zrušení rezervace:</b><br>
      ".$cancellation."
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
    if ($lessThan48h == 0) {
      $contactYourHost = "";
      $cancellation = "
        Ak rezerváciu zrušíte aspoň dva týždne pred prvým dňom rezervácie, bude Vám vrátená celá suma. Inak je stornovací poplatok 10% z celkovej sumy, alebo celá záloha.
      ";
    } else {
      $contactYourHost = "
        <br>
        Vzhľadom k tomu, že je vaša rezervácie vytvorené menej ako 48h pred prvým dňom, kontaktujte prosím Vášho hostiteľa, z dôvodu potvrdenie pobytu.
      ";
      $cancellation = "
        Storno poplatok je 10% z celkovej sumy.
      ";
    }
    $body = "
      Dobrý deň,<br>
      potvrdzujeme vytvorenie žiadosti o rezerváciu chaty <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay."). Teraz prosím, čakajte na potvrdenie od hostiteľa, ktoré by ste mali dostať do 48 hodín. Ak sa tak nestane, kontaktujte prosím Vášho hostiteľa.
      <br>
      Po obdržaní potvrdenia od hostiteľa Vám budú zaslané inštrukcie a informácie k platbe.
      ".$contactYourHost."
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
      <b>Zrušenie rezervácie:</b><br>
      ".$cancellation."
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
    if ($lessThan48h == 0) {
      $contactYourHost = "";
      $cancellation = "
        If you cancel your reservation at least two weeks before the first day of booking, the full amount will be refunded. Otherwise, the cancellation fee is 10% of the total amount, or the entire deposit.
      ";
    } else {
      $contactYourHost = "
        <br>
        As your reservation is made less than 48 hours before the first day, please contact your host to confirm your stay.
      ";
      $cancellation = "
        The cancellation fee is 10% of the total amount.
      ";
    }
    $body = "
      Good day,<br>
      we confirm the creation of the request to book the cottage <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> for the period ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay."). Now please wait for the confirmation from the host, which you should receive within 48 hours. If this does not happen, please contact your host.
      <br>
      After receiving confirmation from the host, you will be sent instructions and payment information.
      ".$contactYourHost."
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
      <b>Cancellation:</b><br>
      ".$cancellation."
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
  sendMail($guestEmail, $subject, $body, "confirmation-of-booking");
}
?>
