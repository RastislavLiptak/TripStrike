<?php
  include "../data.php";
  include "../get-frontend-id.php";
  include "../../../../uni/dictionary/lang-select.php";
  include "../get-user.php";
  include "tools/get-conditions-text.php";
  $output = [];
  $conditionsTextDone = false;
  $condID = mysqli_real_escape_string($link, $_POST['condId']);
  if ($sign == "yes") {
    getConditionsText($condID);
  } else {
    error(1, "You are not signed in");
  }

  function getConditionsTextOutput($txt) {
    global $output;
    array_push($output, [
      "type" => "text",
      "text" => $txt
    ]);
  }

  function getConditionsTextDone() {
    global $conditionsTextDone;
    $conditionsTextDone = true;
    if ($conditionsTextDone) {
      returnOutput();
    }
  }

  function error($sts, $msg) {
    global $output;
    array_push($output, [
      "type" => "error",
      "error" => $msg
    ]);
    if ($sts == 1) {
      returnOutput();
    }
  }

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
