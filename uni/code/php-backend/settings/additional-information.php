<?php
  include "../data.php";
  include "../account-data-check.php";
  include "../multibyte_character_encodings.php";
  $output = [];
  if (isset($_SESSION["signID"]) && isset($_SESSION["email"])) {
    $signId = $_SESSION["signID"];
    $em = $_SESSION["email"];
    $sqlUser = $link->query("SELECT * FROM users WHERE email='$em' && status='active'");
    if ($sqlUser->num_rows > 0) {
      $usr = $sqlUser->fetch_assoc();
      $beId = $usr['beid'];
      $sqlLog = $link->query("SELECT * FROM signin WHERE beid='$beId' && signinid='$signId' && status='in'");
      if ($sqlLog->num_rows > 0) {
        $description = mysqli_real_escape_string($link, $_POST['description']);
        $langArr = $_POST['langArr'];
        $sql = "UPDATE users SET description='$description' WHERE beid='$beId'";
        if (mysqli_query($link, $sql)) {
          $sqlArchive = "UPDATE usersarchive SET description='$description' WHERE beid='$beId'";
          if (mysqli_query($linkBD, $sqlArchive)) {
            $sqlLD = "DELETE FROM languages WHERE beid='$beId'";
            if (mysqli_query($link, $sqlLD)) {
              $langs = explode(",", $langArr);
              $arrLanght = count($langs);
              $errLang = 0;
              for ($i=0; $i < $arrLanght; $i++) {
                $l = mysqli_real_escape_string($link, $langs[$i]);
                if ($l != "" && $l != " ") {
                  $l = mb_strtolower($l);
                  $l = str_replace(array("'", '"'), array("", ""), $l);
                  $sqlL = $link->query("SELECT * FROM languages WHERE beid='$beId' && language='$l'");
                  if ($sqlL->num_rows == 0) {
                    $sqlL = "INSERT INTO languages (beid, language) VALUES ('$beId', '$l')";
                    if (!mysqli_query($link, $sqlL)) {
                      $errLang++;
                    }
                  }
                }
              }
              if ($errLang == 0) {
                done();
              } else {
                error("Addings languages failed (failed ".$errLang." times)");
              }
            } else {
              error("Deleting old languages failed");
            }
          } else {
            error("Failed to save to archive database");
          }
        } else {
          error("Failed to save to database");
        }
      } else {
        error("action denied - sign in data not matching with data in database");
      }
    } else {
      error("data from session not maching with data from database");
    }
  } else {
    error("session error - missing data");
  }

  function error($msg) {
    global $output;
    array_push($output, [
      "type" => "error",
      "error" => $msg
    ]);
    returnOutput();
  }

  function done() {
    global $output, $link, $beId;
    $sqlNewData = $link->query("SELECT description FROM users WHERE beid='$beId'");
    $nd = $sqlNewData->fetch_assoc();
    array_push($output, [
      "type" => "done",
      "description" => $nd['description']
    ]);
    returnOutput();
  }

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
