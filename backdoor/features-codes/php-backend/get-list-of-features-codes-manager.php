<?php
  include "../../../uni/code/php-backend/data.php";
  include "../../../uni/code/php-backend/get-frontend-id.php";
  include "../../../uni/code/php-backend/get-data-from-date.php";
  include "../../../uni/dictionary/lang-select.php";
  include "../../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/check-sign-in.php";
  include "get-list-of-features-codes.php";
  header('Content-Type: application/json');
  $output = [];
  $lastCode = mysqli_real_escape_string($link, $_POST['lastCode']);
  $lastFeature = mysqli_real_escape_string($link, $_POST['lastFeature']);
  $availabilityFilter = mysqli_real_escape_string($link, $_POST['availabilityFilter']);
  $search = mysqli_real_escape_string($link, $_POST['search']);
  $backDoorCheckSignInSts = backDoorCheckSignIn();
  if ($backDoorCheckSignInSts == "good") {
    $loadedListData = getListOfFeaturesCodes("load-amount", $lastCode, $lastFeature, $availabilityFilter, $search);
    array_push($output, [
      "type" => "load-amount",
      "all-codes" => $loadedListData['all-codes'],
      "loaded" => $loadedListData['loaded'],
      "remain" => $loadedListData['remain']
    ]);
    $listOfFeaturesCodes = getListOfFeaturesCodes("list", $lastCode, $lastFeature, $availabilityFilter, $search);
    for ($lOFC=0; $lOFC < sizeof($listOfFeaturesCodes); $lOFC++) {
      if ($listOfFeaturesCodes[$lOFC]['status'] == "") {
        $rowStatus = "<i>".$wrd_unknown." (empty)</i>";
      } else if ($listOfFeaturesCodes[$lOFC]['status'] == "-") {
        $rowStatus = "<i>".$wrd_unknown." (-)</i>";
      } else if ($listOfFeaturesCodes[$lOFC]['status'] == "active") {
        $rowStatus = "<i>".$wrd_used."</i>";
      } else if ($listOfFeaturesCodes[$lOFC]['status'] == "inactive") {
        $rowStatus = $wrd_available;
      }
      if ($listOfFeaturesCodes[$lOFC]['userStatus'] == "not-connected") {
        if ($listOfFeaturesCodes[$lOFC]['status'] == "inactive") {
          $rowUser = "-";
        } else {
          $rowUser = "<i>".$wrd_notConnectedToAnyUser."</i>";
        }
      } else if ($listOfFeaturesCodes[$lOFC]['userStatus'] == "not-found") {
        $rowUser = "<i>".$wrd_userNotFound."</i>";
      } else if ($listOfFeaturesCodes[$lOFC]['userStatus'] == "active") {
        $rowUser = "<a href='../user/?section=users&nav=about&id=".$listOfFeaturesCodes[$lOFC]['userID']."&m=&y=' target='_blank'>".$listOfFeaturesCodes[$lOFC]['username']."</a>";
      } else {
        $rowUser = "<i>".$listOfFeaturesCodes[$lOFC]['userStatus']."</i>";
      }
      array_push($output, [
        "type" => "code",
        "status" => $rowStatus,
        "code" => $listOfFeaturesCodes[$lOFC]['code'],
        "feature" => $listOfFeaturesCodes[$lOFC]['feature'],
        "user" => $rowUser,
        "numOfFeatures" => $listOfFeaturesCodes[$lOFC]['numOfFeatures']
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
