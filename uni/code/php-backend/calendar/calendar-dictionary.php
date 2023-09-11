<?php
  include "../data.php";
  include "../../../dictionary/lang-select.php";
  $output = [];
  array_push($output, [
    "loading" => $wrd_loading,
    "error" => $wrd_error,
    "mon" => $wrd_monday_short,
    "tue" => $wrd_tuesday_short,
    "wed" => $wrd_wednesday_short,
    "thu" => $wrd_thursday_short,
    "fri" => $wrd_friday_short,
    "sat" => $wrd_saturday_short,
    "sun" => $wrd_sunday_short,
    "january" => $wrd_january,
    "february" => $wrd_february,
    "march" => $wrd_march,
    "april" => $wrd_april,
    "may" => $wrd_may,
    "june" => $wrd_june,
    "july" => $wrd_july,
    "august" => $wrd_august,
    "september" => $wrd_september,
    "october" => $wrd_october,
    "november" => $wrd_november,
    "december" => $wrd_december
  ]);

  returnOutput();

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
