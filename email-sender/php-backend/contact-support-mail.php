<?php
  function contactSupportMail($langShrt, $contactEmail, $emailSubject, $emailContent) {
    global $title, $domain;
    $subject = "Client's request";
    $body = "
      Good day,<br>
      one of the clients contacted <a href='".$domain."'>".$title."</a> support.
      <br>
      <br>
      <br>
      <b>".$emailSubject."</b>
      <br>
      ".$emailContent."
      <br>
      <br>
      <b>Client's email:</b> ".$contactEmail."
      <br>
      <br>
      <br>
      Regards,<br>
      <a href='".$domain."'>".$title."</a>
    ";
    sendMail("liptakr@tripstrike.com", $subject, $body, "contact-support");
  }
?>
