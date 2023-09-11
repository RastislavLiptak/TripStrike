<?php
  include "../data.php";
  include "../get-frontend-id.php";
  include "../../../../uni/dictionary/lang-select.php";
  include "../get-user.php";
  include "tools/get-conditions-id.php";
  include "tools/get-lang-list.php";
  include "tools/get-conditions-text.php";
  $output = [];
  $langListDone = false;
  $conditionsTextDone = false;
  $plcID = mysqli_real_escape_string($link, $_POST['plcId']);
  if ($sign == "yes") {
    if ($plcID == "") {
      $selectedLang = $wrd_shrt;
      getLangList();
      getConditionsText($selectedLang);
    } else {
      $sqlplcBeID = $link->query("SELECT beid FROM idlist WHERE id='$plcID'");
      if ($sqlplcBeID->num_rows > 0) {
        $plcBeID = $sqlplcBeID->fetch_assoc()['beid'];
        $selectedLang = getConditionsID($plcBeID);
        getLangList();
        getConditionsText($selectedLang);
      } else {
        error(1, "Place with ID: ".$plcID." does not exist");
      }
    }
  } else {
    error(1, "You are not signed in");
  }

  function getLangListOutput($id, $img1, $img2, $img3, $langIcn) {
    global $output, $selectedLang;
    if ($selectedLang == $id) {
      $sts = "selected";
    } else {
      $sts = "none";
    }
    array_push($output, [
      "type" => "lang",
      "id" => $id,
      "img1" => $img1,
      "img2" => $img2,
      "img3" => $img3,
      "lang-icn" => $langIcn,
      "sts" => $sts
    ]);
  }

  function getLangListDone() {
    global $langListDone, $conditionsTextDone;
    $langListDone = true;
    if ($langListDone && $conditionsTextDone) {
      returnOutput();
    }
  }

  function getConditionsTextOutput($txt) {
    global $output;
    array_push($output, [
      "type" => "text",
      "text" => $txt
    ]);
  }

  function getConditionsTextDone() {
    global $conditionsTextDone, $langListDone;
    $conditionsTextDone = true;
    if ($langListDone && $conditionsTextDone) {
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
