<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/account-data-check.php";
  include "../../email-sender/php-backend/send-mail.php";
  include "../../email-sender/php-backend/contact-support-mail.php";
  include "../../email-sender/php-backend/support-contacted-successfully-mail.php";
  $output = [];
  $contactEmail = $_POST['email'];
  $emSubject = $_POST['subject'];
  $emContent = nl2br($_POST['content']);
  if (check($contactEmail, "empty")) {
    if (check($contactEmail, "email")) {
      if (check($emSubject, "empty")) {
        if (check($emContent, "empty")) {
          contactSupportMail($wrd_shrt, $contactEmail, $emSubject, $emContent);
        } else {
          error("Email content field is empty");
        }
      } else {
        error("Subject field is empty");
      }
    } else {
      error("Contact email field value is not in email format");
    }
  } else {
    error("Contact email field is empty");
  }

  function mailDone($sts, $mailType) {
    global $wrd_shrt, $sign, $contactEmail, $emSubject;
    if ($mailType == "contact-support") {
      if ($sts == "done") {
        supportContactedSuccessfullyMail($wrd_shrt, $contactEmail, $emSubject);
      } else {
        error("Failed to send an email to support");
      }
    } else {
      if ($sts == "done") {
        if ($sign != "yes") {
          setcookie("guest-email", $contactEmail, time() + (86400 * 30 * 365), "/");
        }
        done();
      } else {
        error("Failed to send an email with support contacting confirmation to you. But don't worry. Support has been contacted successfully");
      }
    }
  }

  function done() {
    global $output;
    array_push($output, [
      "type" => "done"
    ]);
    returnOutput();
  }

  function error($msg) {
    global $output;
    array_push($output, [
      "type" => "error",
      "error" => $msg
    ]);
    returnOutput();
  }

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
