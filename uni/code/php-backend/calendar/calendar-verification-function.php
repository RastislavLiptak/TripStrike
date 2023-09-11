<?php
  function calendarVerification($id, $type) {
    global $link;
    $plcBeIdArr = [];
    if ($id != "") {
      $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$id' LIMIT 1");
      if ($sqlIdToBeId->num_rows > 0) {
        $beId = $sqlIdToBeId->fetch_assoc()["beid"];
        $sqlIdTtype = $link->query("SELECT type FROM backendidlist WHERE beid='$beId' LIMIT 1");
        if ($sqlIdTtype->num_rows > 0) {
          $idType = $sqlIdTtype->fetch_assoc()["type"];
          if ($idType == "place") {
            $sqlPlcCH = $link->query("SELECT beid, usrbeid FROM places WHERE beid='$beId' and status='active' LIMIT 1");
            if ($sqlPlcCH->num_rows > 0) {
              if ($type == "host") {
                $usrBeId = getUsrBeId();
                if ($usrBeId != "not-signed") {
                  $sqlOwnership = $link->query("SELECT * FROM places WHERE beid='$beId' and usrbeid='$usrBeId' and status='active'");
                  if ($sqlOwnership->num_rows > 0) {
                    if (getAccountData($usrBeId, "feature-edit-cottage") == "good") {
                      return "good";
                    } else if (getAccountData($usrBeId, "feature-edit-cottage") == "ban") {
                      return "This feature is banned";
                    } else if (getAccountData($usrBeId, "feature-edit-cottage") == "error") {
                      return "Failed to get permission to this feature";
                    } else {
                      return "Feature is not available";
                    }
                  } else {
                    return "This cottage does not belong to you <br>".mysqli_error($link);
                  }
                } else {
                  return "Not signed in";
                }
              } else {
                return "good";
              }
            } else {
              return "Faild to find this cottage in database <br>".mysqli_error($link);
            }
          } else {
            return "ID does not belong to the cottage";
          }
        } else {
          return "Backend ID is not in the list <br>".mysqli_error($link);
        }
      } else {
        return "ID not exist <br>".mysqli_error($link);
      }
    } else {
      return "Empty ID value received from JS Ajax";
    }
  }

  function getUsrBeId() {
    global $link;
    if (isset($_SESSION["signID"]) && isset($_SESSION["email"])) {
      $sign = "yes";
      $signId = $_SESSION["signID"];
      $em = $_SESSION["email"];
    } else if (isset($_COOKIE["signID"]) && isset($_COOKIE["email"])) {
      $sign = "yes";
      $signId = $_COOKIE["signID"];
      $em = $_COOKIE["email"];
    } else {
      return "not-signed-in";
    }

    if ($sign == "yes") {
      $sqlUser = $link->query("SELECT * FROM users WHERE email='$em' && status='active'");
      if ($sqlUser->num_rows > 0) {
        $usr = $sqlUser->fetch_assoc();
        $uBeId = $usr['beid'];
        $sqlLog = $link->query("SELECT * FROM signin WHERE beid='$uBeId' && signinid='$signId' && status='in'");
        if ($sqlLog->num_rows > 0) {
          return $uBeId;
        } else {
          return "not-signed-in";
        }
      } else {
        return "not-signed-in";
      }
    }
  }
?>
