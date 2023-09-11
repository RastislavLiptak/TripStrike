<?php
  include "../data.php";
  include "../random-hash-maker.php";
  $output = [];
  $idReady = false;
  while (!$idReady) {
    $id = randomHash(10);
    $sqlID = $link->query("SELECT * FROM idlist WHERE id='$id'");
    if ($sqlID->num_rows == 0) {
      $idReady = true;
    } else {
      $idReady = false;
    }
  }
  done($id);

  function done($id) {
    global $output;
    array_push($output, [
      "type" => "done",
      "id" => $id
    ]);
    returnOutput();
  }

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
