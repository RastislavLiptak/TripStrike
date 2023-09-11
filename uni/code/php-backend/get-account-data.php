<?php
include "multibyte_character_encodings.php";
if (!function_exists('getAccountData')) {
  function getAccountData($beId, $get) {
    global $link, $linkBD, $wrd_january, $wrd_february, $wrd_march, $wrd_april, $wrd_may, $wrd_june, $wrd_july, $wrd_august, $wrd_september, $wrd_october, $wrd_november, $wrd_december;
    $sqlUsers = $link->query("SELECT * FROM users WHERE beid='$beId'");
    $users = $sqlUsers->fetch_assoc();
    $languages_string = "";
    $languages_array = [];
    $features_array = [];
    $sqlLanguages = $link->query("SELECT * FROM languages WHERE beid='$beId'");
    if ($sqlLanguages->num_rows > 0) {
      while ($languages = $sqlLanguages->fetch_assoc()) {
        $dtbLang = mb_ucfirst($languages['language'], "utf8");
        $languages_string = $languages_string."".$dtbLang.", ";
        array_push($languages_array, $dtbLang);
      }
    }
    $sqlfeatures = $linkBD->query("SELECT beid FROM featuresinuse WHERE usrbeid='$beId'");
    if ($sqlfeatures->num_rows > 0) {
      while ($bnfts = $sqlfeatures->fetch_assoc()) {
        array_push($features_array, $bnfts['beid']);
      }
    }
    if ($get == "id") {
      return getFrontendId($beId);
    } else if ($get == "firstname") {
      return $users['firstname'];
    } else if ($get == "lastname") {
      return $users['lastname'];
    } else if ($get == "status") {
      return $users['status'];
    } else if ($get == "email") {
      return $users['email'];
    } else if ($get == "contact-email") {
      return $users['contactemail'];
    } else if ($get == "synchronize-email-status") {
      if ($users['syncemailsts'] == 1) {
        return true;
      } else {
        return false;
      }
    } else if ($get == "phone-num") {
      return $users['phonenum'];
    } else if ($get == "contact-phone-num") {
      return $users['contactphonenum'];
    } else if ($get == "synchronize-phone-num-status") {
      if ($users['syncnumsts'] == 1) {
        return true;
      } else {
        return false;
      }
    } else if ($get == "bank-account") {
      return $users['bankaccount'];
    } else if ($get == "iban") {
      return $users['iban'];
    } else if ($get == "bicswift") {
      return $users['bicswift'];
    } else if ($get == "birth-day") {
      return $users['birthd'];
    } else if ($get == "birth-month") {
      if ($users['birthm'] == 1) {
        return $wrd_january;
      } else if ($users['birthm'] == 2) {
        return $wrd_february;
      } else if ($users['birthm'] == 3) {
        return $wrd_march;
      } else if ($users['birthm'] == 4) {
        return $wrd_april;
      } else if ($users['birthm'] == 5) {
        return $wrd_may;
      } else if ($users['birthm'] == 6) {
        return $wrd_june;
      } else if ($users['birthm'] == 7) {
        return $wrd_july;
      } else if ($users['birthm'] == 8) {
        return $wrd_august;
      } else if ($users['birthm'] == 9) {
        return $wrd_september;
      } else if ($users['birthm'] == 10) {
        return $wrd_october;
      } else if ($users['birthm'] == 11) {
        return $wrd_november;
      } else if ($users['birthm'] == 12) {
        return $wrd_december;
      }
    } else if ($get == "birth-month-num") {
      return $users['birthm'];
    } else if ($get == "birth-year") {
      return $users['birthy'];
    } else if ($get == "description") {
      return $users['description'];
    } else if ($get == "languages-string") {
      return $languages_string;
    } else if ($get == "languages-array") {
      return $languages_array;
    } else if ($get == "sign-up-day") {
      return $users['signupd'];
    } else if ($get == "sign-up-month") {
      return $users['signupm'];
    } else if ($get == "sign-up-year") {
      return $users['signupy'];
    } else if ($get == "small-profile-image") {
      $sqlImgS = $link->query("SELECT src FROM images WHERE beid='$beId' && status='prf-small'");
      if ($sqlImgS->num_rows > 0) {
        $sImg = $sqlImgS->fetch_assoc();
        return $sImg['src'];
      } else {
        return "#";
      }
    } else if ($get == "medium-profile-image") {
      $sqlImgM = $link->query("SELECT src FROM images WHERE beid='$beId' && status='prf-mid'");
      if ($sqlImgM->num_rows > 0) {
        $mImg = $sqlImgM->fetch_assoc();
        return $mImg['src'];
      } else {
        return "#";
      }
    } else if ($get == "big-profile-image") {
      $sqlImgB = $link->query("SELECT src FROM images WHERE beid='$beId' && status='prf-big'");
      if ($sqlImgB->num_rows > 0) {
        $bImg = $sqlImgB->fetch_assoc();
        return $bImg['src'];
      } else {
        return "#";
      }
    } else if ($get == "feature-add-cottage") {
      $featuresList_string = join("','", $features_array);
      $sqlAddCottage = $linkBD->query("SELECT sts FROM featuresvalidation WHERE beid IN ('$featuresList_string') && name='add-cottage' LIMIT 1");
      if ($sqlAddCottage->num_rows > 0) {
        $bnftsAddCott = $sqlAddCottage->fetch_assoc();
        if ($bnftsAddCott['sts'] == "active") {
          return "good";
        } else if ($bnftsAddCott['sts'] == "ban") {
          return "ban";
        } else {
          return "error";
        }
      } else {
        return "none";
      }
    } else if ($get == "feature-edit-cottage") {
      $featuresList_string = join("','", $features_array);
      $sqlEditCottage = $linkBD->query("SELECT sts FROM featuresvalidation WHERE beid IN ('$featuresList_string') && name='edit-cottage' LIMIT 1");
      if ($sqlEditCottage->num_rows > 0) {
        $bnftsEditCott = $sqlEditCottage->fetch_assoc();
        if ($bnftsEditCott['sts'] == "active") {
          return "good";
        } else if ($bnftsEditCott['sts'] == "ban") {
          return "ban";
        } else {
          return "error";
        }
      } else {
        return "none";
      }
    } else if ($get == "feature-add-comment") {
      $featuresList_string = join("','", $features_array);
      $sqlAddComment = $linkBD->query("SELECT sts FROM featuresvalidation WHERE beid IN ('$featuresList_string') && name='add-comment' LIMIT 1");
      if ($sqlAddComment->num_rows > 0) {
        $bnftsAddComment = $sqlAddComment->fetch_assoc();
        if ($bnftsAddComment['sts'] == "active") {
          return "good";
        } else if ($bnftsAddComment['sts'] == "ban") {
          return "ban";
        } else {
          return "error";
        }
      } else {
        return "none";
      }
    } else if ($get == "feature-add-rating") {
      $featuresList_string = join("','", $features_array);
      $sqlAddRating = $linkBD->query("SELECT sts FROM featuresvalidation WHERE beid IN ('$featuresList_string') && name='add-rating' LIMIT 1");
      if ($sqlAddRating->num_rows > 0) {
        $bnftsAddRating = $sqlAddRating->fetch_assoc();
        if ($bnftsAddRating['sts'] == "active") {
          return "good";
        } else if ($bnftsAddRating['sts'] == "ban") {
          return "ban";
        } else {
          return "error";
        }
      } else {
        return "none";
      }
    } else if ($get == "feature-no-fees") {
      $featuresList_string = join("','", $features_array);
      $sqlNoFees = $linkBD->query("SELECT sts FROM featuresvalidation WHERE beid IN ('$featuresList_string') && name='no-fees' LIMIT 1");
      if ($sqlNoFees->num_rows > 0) {
        $bnftsNoFees = $sqlNoFees->fetch_assoc();
        if ($bnftsNoFees['sts'] == "active") {
          return "good";
        } else if ($bnftsNoFees['sts'] == "ban") {
          return "ban";
        } else {
          return "error";
        }
      } else {
       return "none";
      }
    } else if (
      $get == "pay-or-your-booking-will-be-canceled" ||
      $get == "cancel-unpaid-bookings" ||
      $get == "pay-full-amount-alert" ||
      $get == "unpaid-full-amount-call"
    ) {
      $sqlSchedSettings = $link->query("SELECT status FROM userscheduled WHERE beid='$beId' && task='$get' LIMIT 1");
      if ($sqlSchedSettings->num_rows > 0) {
        $schedSett = $sqlSchedSettings->fetch_assoc();
        return $schedSett['status'];
      } else {
        return "0";
      }
    }
  }
}
?>
