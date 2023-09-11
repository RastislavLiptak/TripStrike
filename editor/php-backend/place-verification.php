<?php
  function placeVerification($url_id) {
    global $link, $sign, $usrBeId, $bnft_edit_cottage;
    if ($sign == "yes") {
      if ($bnft_edit_cottage == "good") {
        $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$url_id' LIMIT 1");
        if ($sqlIdToBeId->num_rows > 0) {
          $beId = $sqlIdToBeId->fetch_assoc()["beid"];
          $sqlOldPlc = $link->query("SELECT * FROM places WHERE beid='$beId' LIMIT 1");
          if ($sqlOldPlc->num_rows > 0) {
            $oldPlc = $sqlOldPlc->fetch_assoc();
            if ($usrBeId == $oldPlc['usrbeid']) {
              if ($oldPlc['status'] == "active") {
                return "good";
              } else {
                return "this-place-is-not-activated (status: ".$oldPlc['status'].")";
              }
            } else {
              return "the-logged-in-account-has-no-right-to-edit";
            }
          } else {
            return "ID-not-connected-to-place";
          }
        } else {
          return "ID-from-url-not-exist";
        }
      } else {
        if ($bnft_edit_cottage == "none") {
          return "task-not-available";
        } else if ($bnft_edit_cottage == "ban") {
          return "task-is-banned";
        } else {
          return "task-unexpected-status";
        }
      }
    } else {
      return "not-signed-in";
    }
  }
?>
