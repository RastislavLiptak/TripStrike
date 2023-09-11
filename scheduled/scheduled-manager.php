<?php
  // localhost:8888/projects/Cottages/scheduled/scheduled-manager.php
  include "../uni/code/php-backend/data.php";
  include "../uni/code/php-backend/get-frontend-id.php";
  include "../uni/code/php-backend/get-account-data.php";
  include "../email-sender/php-backend/send-mail.php";
  // functions
  include "cancel-unpaid-bookings.php";
  include "pay-full-amount-alert.php";
  include "rate-your-booking.php";
  include "unpaid-full-amount-call.php";
  include "forgotten-password-code-expiration.php";
  include "pay-or-your-booking-will-be-canceled.php";
  include "automatically-reject-booking-offer.php";
  include "notify-host-about-automatic-booking-rejection.php";

  cancelUnpaidBookingsHandler();
  payFullAmountAlertHandler();
  rateYourBookingHandler();
  unpaidFullAmountCallHandler();
  forgottenPasswordCodeExpiration();
  payOrYourBookingWillBeCanceled();
  automaticallyRejectBookingOffer();
  notifyHostAboutAutomaticBookingRejection();

  function mailDone($sts, $mailType) {
    global $link;
    if (
      $mailType != "place-deleted-mail-to-guest" &&
      $mailType != "unpaid-booking-canceled-mail-to-guest" &&
      $mailType != "booking-canceled-mail-to-guest" &&
      $mailType != "pay-full-amount-mail-alert" &&
      $mailType != "unpaid-full-amount-call-mail" &&
      $mailType != "last-call-before-booking-cancelation-to-guest" &&
      $mailType != "last-call-before-booking-cancelation-to-host" &&
      $mailType != "host-rejected-the-booking" &&
      $mailType != "booking-offer-reminder"
    ) {
      $sqlRatingUpdt = "UPDATE booking SET ratingMail='1' WHERE beid='$mailType'";
      mysqli_query($link, $sqlRatingUpdt);
    }
  }

  function cancelBookingOutput($type, $txt) {
    // do anything
  }
?>
