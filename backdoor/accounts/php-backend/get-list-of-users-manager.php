<?php
  include "../../../uni/code/php-backend/data.php";
  include "../../../uni/code/php-backend/get-frontend-id.php";
  include "../../../uni/dictionary/lang-select.php";
  include "../../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/check-sign-in.php";
  include "get-list-of-users.php";
  header('Content-Type: application/json');
  $output = [];
  $lastID = mysqli_real_escape_string($link, $_POST['lastID']);
  $search = mysqli_real_escape_string($link, $_POST['search']);
  $backDoorCheckSignInSts = backDoorCheckSignIn();
  if ($backDoorCheckSignInSts == "good") {
    if ($lastID != "") {
      $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$lastID' LIMIT 1");
      $lastBeID = $sqlIdToBeId->fetch_assoc()["beid"];
    } else {
      $lastBeID = "";
    }
    $loadedListData = getListOfUsers("load-amount", $lastBeID, $search);
    array_push($output, [
      "type" => "load-amount",
      "all-users" => $loadedListData['all-users'],
      "loaded" => $loadedListData['loaded'],
      "remain" => $loadedListData['remain']
    ]);
    $listOfUsers = getListOfUsers("list", $lastBeID, $search);
    for ($lOU=0; $lOU < sizeof($listOfUsers); $lOU++) {
      if ($listOfUsers[$lOU]['status'] == "") {
        $rowStatus = "<i>".$wrd_unknown." (empty)</i>";
      } else if ($listOfUsers[$lOU]['status'] == "-") {
        $rowStatus = "<i>".$wrd_unknown." (-)</i>";
      } else if ($listOfUsers[$lOU]['status'] == "delete") {
        $rowStatus = "<i>".$wrd_deleted."</i>";
      } else if ($listOfUsers[$lOU]['status'] == "active") {
        $rowStatus = $wrd_active;
      }
      array_push($output, [
        "type" => "user",
        "status" => $rowStatus,
        "userID" => $listOfUsers[$lOU]['userID'],
        "firstname" => $listOfUsers[$lOU]['firstname'],
        "lastname" => $listOfUsers[$lOU]['lastname'],
        "contactEmail" => $listOfUsers[$lOU]['contactEmail'],
        "wShowMore" => $wrd_showMore
      ]);
    }
    returnOutput();
  } else {
    error($backDoorCheckSignInSts);
  }

  function error($msg) {
    global $output;
    array_push($output, [
      "type" => "error",
      "error" => $msg
    ]);
    returnOutput();
  }

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
