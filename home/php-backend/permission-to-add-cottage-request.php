<?php
  include "../../uni/code/php-backend/data.php";
  include "../../email-sender/php-backend/send-mail.php";
  include "../../email-sender/php-backend/permission-to-add-cottage-request-mail.php";
  include "../../uni/code/php-backend/account-data-check.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/code/php-backend/get-user.php";
  $output = [];
  $date = date("Y-m-d H:i:s");
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $contact_email = $_POST['contactemail'];
  $address = $_POST['address'];
  $country = $_POST['country'];
  $notes = $_POST['notes'];
  if (check($firstname, "empty")) {
    if (check($lastname, "empty")) {
      if (check($contact_email, "empty")) {
        if (check($contact_email, "email")) {
          if (check($address, "empty")) {
            if (check($country, "empty")) {
              permissionToAddCottageRequestMail($firstname, $lastname, $contact_email, $address, $country, $notes, $sign, $wrd_shrt, $date);
            } else {
              error("Country is empty");
            }
          } else {
            error("Address / Coordinates is empty");
          }
        } else {
          error("Email is not in valid form");
        }
      } else {
        error("Contact email is empty");
      }
    } else {
      error("Lastname is empty");
    }
  } else {
    error("Firstname is empty");
  }

  function mailDone($sts, $mailType) {
    if ($sts == "done") {
      done();
    } else {
      error("Sending request failed (mail: failed)");
    }
  }

  function error($msg) {
    global $output;
    array_push($output, [
      "type" => "error",
      "error" => $msg
    ]);
    returnOutput();
  }

  function done() {
    global $output;
    array_push($output, [
      "type" => "done"
    ]);
    returnOutput();
  }

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
