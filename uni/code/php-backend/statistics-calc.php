<?php
  function statisticsCalc($get, $listOfData) {
    if (sizeof($listOfData) > 0) {
      if ($get == "average") {
        $listOfData = array_filter($listOfData);
        return array_sum($listOfData) / sizeof($listOfData);
      } else if ($get == "mode") {
        $values = array_count_values($listOfData);
        return array_search(max($values), $values);
      } else if ($get == "median") {
        sort($listOfData);
        $count = sizeof($listOfData);
        $index = floor($count / 2);
        if (!$count) {
          return 0;
        } elseif ($count & 1) {
          return round($listOfData[$index], 4);
        } else {
          return (round($listOfData[$index -1], 4) + round($listOfData[$index], 4)) / 2;
        }
      } else if ($get == "dispersion") {
        $average = statisticsCalc("average", $listOfData);
        $numerator = 0;
        for ($d=0; $d < sizeof($listOfData); $d++) {
          $numerator = $numerator + pow(round($listOfData[$d], 4) - $average, 2);
        }
        return $numerator / sizeof($listOfData);
      } else if ($get == "standard-deviation") {
        $dispersion = statisticsCalc("dispersion", $listOfData);
        return pow($dispersion, 1/2);
      } else if ($get == "coefficient-of-variation") {
        $standardDeviation = statisticsCalc("standard-deviation", $listOfData);
        $average = statisticsCalc("average", $listOfData);
        if ($average > 0) {
          return ($standardDeviation / $average) * 100;
        } else {
          return 0;
        }
      } else if ($get == "growth-rate") {
        if (round($listOfData[0], 4) != 0) {
          if (sizeof($listOfData) > 1) {
            return pow(round($listOfData[sizeof($listOfData) -1], 4) / round($listOfData[0], 4), 1 / (sizeof($listOfData) -1)) * 100 - 100;
          } else {
            return 100;
          }
        } else {
          if (round($listOfData[sizeof($listOfData) -1], 4) != round($listOfData[0], 4)) {
            return 100;
          } else {
            return 0;
          }
        }
      } else if ($get == "minimum") {
        return min($listOfData);
      } else if ($get == "maximum") {
        return max($listOfData);
      }
    } else {
      return 0;
    }
  }
?>
