<?php
  function getListOfUsers($get, $lastBeId, $searchQuery) {
    global $link, $linkBD;
    $listOfAllUsers = [];
    $listOfOutputUsers = [];
    $loadAmount = [];
    $limit = 25;
    if ($searchQuery != "") {
      $searchBy = getListOfUsersSearchType($searchQuery);
    } else {
      $searchBy = [];
    }
    if (sizeof($searchBy) == 0) {
      $sqlAllUsers = $linkBD->query("SELECT beid FROM usersarchive ORDER BY signupfulldate DESC");
    } else {
      $searchBeIDList = [];
      if (in_array("name", $searchBy)) {
        $listOfSearchName = [];
        $explodeName = explode(' ', $searchQuery, 2);
        if (sizeof($explodeName) > 1) {
          if ($explodeName[1] != "") {
            $fstName = $explodeName[0];
            $secName = $explodeName[1];
            $sqlSearchName = $linkBD->query("SELECT beid FROM usersarchive WHERE firstname LIKE '%$fstName%' or lastname LIKE '%$secName%' or firstname LIKE '%$secName%' or lastname LIKE '%$fstName%'");
          } else {
            $searchQuery = str_replace(" ", "", $searchQuery);
            $sqlSearchName = $linkBD->query("SELECT beid FROM usersarchive WHERE firstname LIKE '%$searchQuery%' or lastname LIKE '%$searchQuery%'");
          }
        } else {
          $sqlSearchName = $linkBD->query("SELECT beid FROM usersarchive WHERE firstname LIKE '%$searchQuery%' or lastname LIKE '%$searchQuery%'");
        }
        if ($sqlSearchName->num_rows > 0) {
          while($rowSearchName = $sqlSearchName->fetch_assoc()) {
            array_push($listOfSearchName, $rowSearchName["beid"]);
          }
        }
        $sqlUserNameFilter = $linkBD->query("SELECT beid FROM usersarchive WHERE beid IN ('".implode("', '", $listOfSearchName)."')");
        if ($sqlUserNameFilter->num_rows > 0) {
          while($rowUserEmailFilter = $sqlUserNameFilter->fetch_assoc()) {
            array_push($searchBeIDList, $rowUserEmailFilter["beid"]);
          }
        }
      }
      if (in_array("id", $searchBy)) {
        $listOfSearchID = [];
        $sqlSearchID = $link->query("SELECT beid FROM idlist WHERE id LIKE '%$searchQuery%'");
        if ($sqlSearchID->num_rows > 0) {
          while($rowSearchID = $sqlSearchID->fetch_assoc()) {
            array_push($listOfSearchID, $rowSearchID["beid"]);
          }
        }
        $sqlUserIDFilter = $linkBD->query("SELECT beid FROM usersarchive WHERE beid IN ('".implode("', '", $listOfSearchID)."')");
        if ($sqlUserIDFilter->num_rows > 0) {
          while($rowUserIDFilter = $sqlUserIDFilter->fetch_assoc()) {
            array_push($searchBeIDList, $rowUserIDFilter["beid"]);
          }
        }
      }
      if (in_array("email", $searchBy)) {
        $sqlUserEmailFilter = $linkBD->query("SELECT beid FROM usersarchive WHERE contactemail LIKE '%$searchQuery%'");
        if ($sqlUserEmailFilter->num_rows > 0) {
          while($rowUserEmailFilter = $sqlUserEmailFilter->fetch_assoc()) {
            array_push($searchBeIDList, $rowUserEmailFilter["beid"]);
          }
        }
      }
      $sqlAllUsers = $linkBD->query("SELECT beid FROM usersarchive WHERE beid IN ('".implode("', '", $searchBeIDList)."') ORDER BY signupfulldate DESC");
    }
    if ($sqlAllUsers->num_rows > 0) {
      while($rowAllUsers = $sqlAllUsers->fetch_assoc()) {
        array_push($listOfAllUsers, $rowAllUsers["beid"]);
      }
      $stopSts = true;
      if ($lastBeId == "") {
        $start = 0;
      } else {
        if (in_array($lastBeId, $listOfAllUsers)) {
          $start = array_search($lastBeId, $listOfAllUsers) + 1;
        } else {
          $start = 0;
        }
      }
      if ($stopSts) {
        $stop = $start + $limit;
        if ($stop > count($listOfAllUsers) || $stop +3 >= count($listOfAllUsers)) {
          $stop = count($listOfAllUsers);
        }
        if ($get == "list") {
          for ($u = $start; $u < $stop; $u++) {
            $userBeId = $listOfAllUsers[$u];
            $sqlUsers = $linkBD->query("SELECT * FROM usersarchive WHERE beid='$userBeId'");
            if ($sqlUsers->num_rows > 0) {
              while($rowUsers = $sqlUsers->fetch_assoc()) {
                array_push($listOfOutputUsers, [
                  "status" => $rowUsers['status'],
                  "userID" => getFrontendId($userBeId),
                  "firstname" => $rowUsers['firstname'],
                  "lastname" => $rowUsers['lastname'],
                  "contactEmail" => $rowUsers['contactemail']
                ]);
              }
            }
          }
        } else {
          $loadAmount = [
            "all-users" => count($listOfAllUsers),
            "loaded" => $stop - $start,
            "remain" => count($listOfAllUsers) - $stop
          ];
        }
      }
    } else {
      if ($get == "load-amount") {
        $loadAmount = [
          "all-users" => 0,
          "loaded" => 0,
          "remain" => 0
        ];
      }
    }
    if ($get == "list") {
      return $listOfOutputUsers;
    } else {
      return $loadAmount;
    }
  }

  function getListOfUsersSearchType($searchQuery) {
    return ["name", "id", "email"];
  }
?>
