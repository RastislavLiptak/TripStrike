<?php
  function searchForUsers($searchQuery) {
    global $link, $linkBD;
    $outputUsers = [];
    $searchBeIDList = [];
    $explodeName = explode(' ', $searchQuery, 2);
    if (sizeof($explodeName) > 1) {
      if ($explodeName[1] != "") {
        $fstName = $explodeName[0];
        $secName = $explodeName[1];
        $sqlSearchUserByName = $linkBD->query("SELECT beid FROM usersarchive WHERE firstname LIKE '%$fstName%' or lastname LIKE '%$secName%' or firstname LIKE '%$secName%' or lastname LIKE '%$fstName%'");
      } else {
        $searchQuery = str_replace(" ", "", $searchQuery);
        $sqlSearchUserByName = $linkBD->query("SELECT beid FROM usersarchive WHERE firstname LIKE '%$searchQuery%' or lastname LIKE '%$searchQuery%'");
      }
    } else {
      $sqlSearchUserByName = $linkBD->query("SELECT beid FROM usersarchive WHERE firstname LIKE '%$searchQuery%' or lastname LIKE '%$searchQuery%'");
    }
    if ($sqlSearchUserByName->num_rows > 0) {
      while($rowSearchUserByName = $sqlSearchUserByName->fetch_assoc()) {
        array_push($searchBeIDList, $rowSearchUserByName["beid"]);
      }
    }
    $sqlSearchID = $link->query("SELECT beid FROM idlist WHERE id LIKE '%$searchQuery%'");
    if ($sqlSearchID->num_rows > 0) {
      while($rowSearchID = $sqlSearchID->fetch_assoc()) {
        array_push($searchBeIDList, $rowSearchID["beid"]);
      }
    }
    $sqlAllUsers = $linkBD->query("SELECT * FROM usersarchive WHERE beid IN ('".implode("', '", $searchBeIDList)."') LIMIT 10");
    if ($sqlAllUsers->num_rows > 0) {
      while ($rowAllUsers = $sqlAllUsers->fetch_assoc()) {
        array_push($outputUsers, [
          "type" => "user",
          "id" => getFrontendId($rowAllUsers['beid']),
          "username" => $rowAllUsers['firstname']." ".$rowAllUsers['lastname']
        ]);
      }
    }
    return $outputUsers;
  }
?>
