<?php
  function getDataFromDate($get, $val) {
    $val = str_replace(" ", "", $val);
    $val = str_replace("-", ".", $val);
    $val = str_replace("/", ".", $val);
    $listOfNums = explode(".", $val);
    if (sizeof($listOfNums) == 1) {
      if (strlen($listOfNums[0]) == 4) {
        if ($get == "d") {
          return "-";
        } else if ($get == "m") {
          return "-";
        } else if ($get == "y") {
          return $listOfNums[0];
        }
      } else {
        if ($get == "d") {
          return sprintf("%02d", $listOfNums[0]);
        } else if ($get == "m") {
          return "-";
        } else if ($get == "y") {
          return "-";
        }
      }
    } else if (sizeof($listOfNums) == 2) {
      if (strlen($listOfNums[1]) == 4) {
        if ($get == "d") {
          return "-";
        } else if ($get == "m") {
          return sprintf("%02d", $listOfNums[0]);
        } else if ($get == "y") {
          return $listOfNums[1];
        }
      } else {
        if ($get == "d") {
          return sprintf("%02d", $listOfNums[0]);
        } else if ($get == "m") {
          return sprintf("%02d", $listOfNums[1]);
        } else if ($get == "y") {
          return "-";
        }
      }
    } else if (sizeof($listOfNums) == 3) {
      if ($get == "d") {
        return sprintf("%02d", $listOfNums[0]);
      } else if ($get == "m") {
        return sprintf("%02d", $listOfNums[1]);
      } else if ($get == "y") {
        return $listOfNums[2];
      }
    } else {
      return "-";
    }
  }
?>
