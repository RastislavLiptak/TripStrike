<?php
  include "../../../uni/code/php-backend/data.php";
  include "../../../uni/code/php-backend/get-frontend-id.php";
  include "../../../uni/code/php-backend/get-data-from-date.php";
  include "../../../uni/dictionary/lang-select.php";
  include "../../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/check-sign-in.php";
  include "search-for-users.php";
  header('Content-Type: application/json');
  $output = [];
  $listOfUsers = [];
  $searchQuery = mysqli_real_escape_string($link, $_POST['search']);
  $backDoorCheckSignInSts = backDoorCheckSignIn();
  if ($backDoorCheckSignInSts == "good") {
    $listOfUsers = searchForUsers($searchQuery);
    if (sizeof($listOfUsers) > 0) {
      $output = $listOfUsers;
      returnOutput();
    } else {
      done();
    }
  } else {
    error($backDoorCheckSignInSts);
  }

  function done() {
    global $output;
    array_push($output, [
      "type" => "done"
    ]);
    returnOutput();
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
