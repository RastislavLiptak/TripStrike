<?php
function hostRejectedTheBookingMail($fold, $langShrt, $guestEmail, $guestNum, $plcID, $hostBeId, $hostID, $plcName, $firstDay, $lastDay, $fromD, $fromM, $fromY, $toD, $toM, $toY) {
  global $title, $link, $domain;
  include $fold."uni/dictionary/langs/".$langShrt.".php";
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
  $subject = $wrd_theHostRejectedYourBooking;
  if ($langShrt == "cz") {
    $body = "
      Dobrý den,<br>
      je nám to líto, ale vaše rezervace pobytu v chatě <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") byla zamítnuta. Pro více informací můžete kontaktovat hostitele.
      <br>
      <br>
      <br>
      <b>Detaily rezervace</b><br>
      <b>Místo:</b> <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a><br>
      <b>Od:</b> ".$fromD.".".$fromM.".".$fromY." (".$firstDay.")<br>
      <b>Do:</b> ".$toD.".".$toM.".".$toY." (".$lastDay.")<br>
      <b>Počet hostů:</b> ".$guestNum."<br>
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
    $body = "
      Dobrý deň,<br>
      je nám to ľúto, ale vaša rezervácia pobytu v chate <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> na termín ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") bola zamietnutá. Pre viac informácií môžete kontaktovať hostiteľa.
      <br>
      <br>
      <br>
      <b>Detaily rezervácie</b><br>
      <b>Miesto:</b> <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a><br>
      <b>Od:</b> ".$fromD.".".$fromM.".".$fromY." (".$firstDay.")<br>
      <b>Do:</b> ".$toD.".".$toM.".".$toY." (".$lastDay.")<br>
      <b>Počet hostí:</b> ".$guestNum."<br>
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
    $body = "
      Good day,<br>
      we are sorry, but your booking for the cottage <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> for the date ".$fromD.".".$fromM.".".$fromY." (".$firstDay.") - ".$toD.".".$toM.".".$toY." (".$lastDay.") has been rejected. You can contact the host for more information.
      <br>
      <br>
      <br>
      <b>Booking details</b><br>
      <b>Place:</b> <a href='".$domain."/place/?id=".$plcID."'>".$plcName."</a><br>
      <b>From:</b> ".$fromD.".".$fromM.".".$fromY." (".$firstDay.")<br>
      <b>To:</b> ".$toD.".".$toM.".".$toY." (".$lastDay.")<br>
      <b>Number of guests:</b> ".$guestNum."<br>
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
  sendMail($guestEmail, $subject, $body, "host-rejected-the-booking");
}
?>
