<?php
  function getConditionsID($plcBeID) {
    global $link, $usrBeId, $default_langs_arr;
    $sqlPlaceConditionsBeId = $link->query("SELECT beid FROM placeconditionskey WHERE plcBeID='$plcBeID' and usrbeid='$usrBeId'");
    if ($sqlPlaceConditionsBeId->num_rows > 0) {
      $plcConditionsBeId = $sqlPlaceConditionsBeId->fetch_assoc()['beid'];
      $sqlPlaceConditionsTxt = $link->query("SELECT txt FROM conditionsofstayofthehost WHERE beid='$plcConditionsBeId'");
      if ($sqlPlaceConditionsTxt->num_rows > 0) {
        $plcConditionsTxt = $sqlPlaceConditionsTxt->fetch_assoc()['txt'];
        $conditionShrtFound = "not found";
        foreach ($default_langs_arr as &$defLngShrt) {
          $condition_file = "../../../../conditions/texts/conditions_of_stay_of_the_host/".$defLngShrt."_conditions_of_stay_of_the_host.txt";
          $condition_file_text = fopen($condition_file, "r") or die("Unable to open file!");
          if ($plcConditionsTxt == str_replace("'", "&apos;", fread($condition_file_text, filesize($condition_file)))) {
            $conditionShrtFound = $defLngShrt;
          }
          fclose($condition_file_text);
        }
        if ($conditionShrtFound != "not found") {
          return $conditionShrtFound;
        } else {
          $allConditionBeIDs = [];
          $sqlAllConditionBeIDs = $link->query("SELECT beid FROM placeconditionskey WHERE usrbeid='$usrBeId'");
          if ($sqlAllConditionBeIDs->num_rows > 0) {
            while($allConditionBeId = $sqlAllConditionBeIDs->fetch_assoc()) {
              array_push($allConditionBeIDs, $allConditionBeId['beid']);
            }
            $getLastConditionBeIdCond = "";
            foreach ($allConditionBeIDs as &$allLastConditionBeId) {
              if ($getLastConditionBeIdCond == "") {
                $getLastConditionBeIdCond = "beid='".$allLastConditionBeId."'";
              } else {
                $getLastConditionBeIdCond = $getLastConditionBeIdCond." || beid='".$allLastConditionBeId."'";
              }
            }
            $getLastConditionBeIdCond = "(".$getLastConditionBeIdCond.") && txt='".$plcConditionsTxt."'";
            $sqlGetLastConditionBeId = $link->query("SELECT beid FROM conditionsofstayofthehost WHERE ".$getLastConditionBeIdCond." ORDER BY fullDate DESC LIMIT 1");
            if ($sqlGetLastConditionBeId->num_rows > 0) {
              $lastConditionBeId = $sqlGetLastConditionBeId->fetch_assoc()['beid'];
              $sqlPlaceConditionsID = $link->query("SELECT id FROM idlist WHERE beid='$lastConditionBeId'");
              if ($sqlPlaceConditionsID->num_rows > 0) {
                return $sqlPlaceConditionsID->fetch_assoc()['id'];
              } else {
                return $wrd_shrt;
              }
            } else {
              return $wrd_shrt;
            }
          } else {
            return $wrd_shrt;
          }
        }
      } else {
        return $wrd_shrt;
      }
    } else {
      return $wrd_shrt;
    }
  }
?>
