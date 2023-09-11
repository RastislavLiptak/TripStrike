<?php
  include "get-account-data.php";
  $sign = "no";
  if (isset($_SESSION["signID"]) && isset($_SESSION["email"])) {
    $sign = "yes";
    $signId = $_SESSION["signID"];
    $em = $_SESSION["email"];
  } else if (isset($_COOKIE["signID"]) && isset($_COOKIE["email"])) {
    $sign = "yes";
    $signId = $_COOKIE["signID"];
    $em = $_COOKIE["email"];
  } else {
    $sign = "no";
  }
  if ($sign == "yes") {
    $sqlUser = $link->query("SELECT * FROM users WHERE email='$em' && status='active'");
    if ($sqlUser->num_rows > 0) {
      $usr = $sqlUser->fetch_assoc();
      $usrBeId = $usr['beid'];
      $sqlLog = $link->query("SELECT * FROM signin WHERE beid='$usrBeId' && signinid='$signId' && status='in'");
      if ($sqlLog->num_rows > 0) {
        $_SESSION["signID"] = $signId;
        $_SESSION["email"] = $em;
      } else {
        $sqlUser = "";
        $usr = "";
        $sign = "no";
      }
    } else {
      $sign = "no";
    }
  }

  //settings data
  if ($sign == "yes") {
    $setid = getAccountData($usrBeId, "id");
    $setfirstname = getAccountData($usrBeId, "firstname");
    $setlastname = getAccountData($usrBeId, "lastname");
    $setemail = getAccountData($usrBeId, "email");
    $setcontactemail = getAccountData($usrBeId, "contact-email");
    $setsyncemailsts = getAccountData($usrBeId, "synchronize-email-status");
    $setphonenum = getAccountData($usrBeId, "phone-num");
    $setcontactphonenum = getAccountData($usrBeId, "contact-phone-num");
    $setsyncnumsts = getAccountData($usrBeId, "synchronize-phone-num-status");
    $bankaccount = getAccountData($usrBeId, "bank-account");
    $iban = getAccountData($usrBeId, "iban");
    $bicswift = getAccountData($usrBeId, "bicswift");
    $setbirthd = getAccountData($usrBeId, "birth-day");
    $setbirthm = getAccountData($usrBeId, "birth-month");
    $setbirthmN = getAccountData($usrBeId, "birth-month-num");
    $setbirthy = getAccountData($usrBeId, "birth-year");
    $setdescription = getAccountData($usrBeId, "description");
    $langstring = getAccountData($usrBeId, "languages-string");
    $langarray = getAccountData($usrBeId, "languages-array");
    $smallImg = getAccountData($usrBeId, "small-profile-image");
    $midImg = getAccountData($usrBeId, "medium-profile-image");
    $bigImg = getAccountData($usrBeId, "big-profile-image");
    $bnft_add_cottage = getAccountData($usrBeId, "feature-add-cottage");
    $bnft_edit_cottage = getAccountData($usrBeId, "feature-edit-cottage");
    $bnft_add_comment = getAccountData($usrBeId, "feature-add-comment");
    $bnft_add_rating = getAccountData($usrBeId, "feature-add-rating");
    $sched_payOrYourBookingWillBeCanceled = getAccountData($usrBeId, "pay-or-your-booking-will-be-canceled");
    $sched_cancelUnpaidBookings = getAccountData($usrBeId, "cancel-unpaid-bookings");
    $sched_payFullAmountAlert = getAccountData($usrBeId, "pay-full-amount-alert");
    $sched_unpaidFullAmountCall = getAccountData($usrBeId, "unpaid-full-amount-call");
  } else {
    $usrBeId = "";
    $setid = "";
    $setfirstname = "";
    $setlastname = "";
    $setemail = "";
    $setcontactemail = "";
    $setsyncemailsts = false;
    $setphonenum = "";
    $setcontactphonenum = "";
    $setsyncnumsts = false;
    $bankaccount = "-";
    $iban = "-";
    $bicswift = "-";
    $setbirthd = 0;
    $setbirthmN = 1;
    $setbirthy = "";
    $setdescription = "";
    $setbirthm = $wrd_january;
    $langstring = "";
    $smallImg = "#";
    $midImg = "#";
    $bigImg = "#";
    $bnft_add_cottage = "none";
    $bnft_edit_cottage = "none";
    $bnft_add_comment = "none";
    $bnft_add_rating = "none";
    $sched_payOrYourBookingWillBeCanceled = "0";
    $sched_cancelUnpaidBookings = "0";
    $sched_payFullAmountAlert = "0";
    $sched_unpaidFullAmountCall = "0";
  }
?>
