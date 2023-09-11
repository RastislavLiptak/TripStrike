<?php
  function getConditionsText($selectedLang) {
    global $link, $usrBeId, $default_langs_arr;
    if (in_array($selectedLang, $default_langs_arr)) {
      $file = "../../../../conditions/texts/conditions_of_stay_of_the_host/".$selectedLang."_conditions_of_stay_of_the_host.txt";
      $file_text = fopen($file, "r") or die("Unable to open file!");
      getConditionsTextOutput(fread($file_text, filesize($file)));
      fclose($file_text);
    } else {
      $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$selectedLang' LIMIT 1");
      if ($sqlIdToBeId->num_rows > 0) {
        $beId = $sqlIdToBeId->fetch_assoc()["beid"];
        $sqlPlcBeId = $link->query("SELECT plcbeid FROM placeconditionskey WHERE beid='$beId' LIMIT 1");
        if ($sqlPlcBeId->num_rows > 0) {
          $plcBeId = $sqlPlcBeId->fetch_assoc()['plcbeid'];
          $sqlPlcCheck = $link->query("SELECT usrbeid FROM places WHERE beid='$plcBeId' LIMIT 1");
          if ($sqlPlcCheck->num_rows > 0) {
            $plcUsrBeId = $sqlPlcCheck->fetch_assoc()['usrbeid'];
            if ($plcUsrBeId == $usrBeId) {
              $sqlTxt = $link->query("SELECT txt FROM conditionsofstayofthehost WHERE beid='$beId' LIMIT 1");
              if ($sqlTxt->num_rows > 0) {
                getConditionsTextOutput(str_replace("&apos;", "'", $sqlTxt->fetch_assoc()['txt']));
              } else {
                error(0, "Text of the conditions does not exist");
              }
            } else {
              error(0, "Place not belong to you");
            }
          } else {
            error(0, "Cannot get conditions text, because place with requested conditions not found");
          }
        } else {
          error(0, "Cannot get conditions text, because backend ID of the conditions is not registred");
        }
      } else {
        error(0, "Cannot get conditions text, because ID of the conditions is not registred");
      }
    }
    getConditionsTextDone();
  }
?>
