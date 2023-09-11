<?php
  function supportContactedSuccessfullyMail($langShrt, $contactEmail, $emailSubject) {
    global $title, $domain;
    if ($langShrt == "cz") {
      $subject = "Potvrzení úspěšného kontaktování podpory";
      $body = "
        Dobrý den,<br>
        děkujeme, že jste nás kontaktovali. Tento email je pouze potvrzením úspěšného odeslání vaší zprávy <b>".$emailSubject."</b>, které se budeme co nejdříve věnovat.
        <br>
        <br>
        S pozdravem,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    } else if ($langShrt == "sk") {
      $subject = "Potvrdenie úspešného kontaktovania podpory";
      $body = "
        Dobrý deň,<br>
        ďakujeme, že ste nás kontaktovali. Tento email je len potvrdením úspešného odoslania vašej správy <b>".$emailSubject."</b>, ktoré sa budeme čo najskôr venovať.
        <br>
        <br>
        S pozdravom,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    } else {
      $subject = "Confirmation of successful contacting a support";
      $body = "
        Good day,<br>
        thank you for contacting us. This email is only a confirmation of the successful sending of your <b>".$emailSubject."</b> message, which we will deal with as soon as possible.
        <br>
        <br>
        Regards,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    }
    sendMail($contactEmail, $subject, $body, "support-contacted-successfully");
  }
?>
