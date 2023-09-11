<?php
  function getListOfFeaturesCodes($get, $lastCode, $lastFeature, $availability, $searchQuery) {
    global $link, $linkBD;
    $listOfUsedData = [];
    $listOfOutputs = [];
    $loadAmount = [];
    $limit = 25;
    $done = 0;
    $loaded = 0;
    $readyToStart = false;
    if ($availability == "available") {
      $availabilityFilter = "sts='inactive'";
    } else if ($availability == "used") {
      $availabilityFilter = "sts='active'";
    } else {
      $availabilityFilter = "";
    }
    if ($lastCode == "" || $lastFeature == "") {
      $readyToStart = true;
    }
    if ($searchQuery != "") {
      $searchBy = getListOfFeaturesCodesSearchType($searchQuery);
    } else {
      $searchBy = [];
    }
    if (sizeof($searchBy) == 0) {
      if ($availabilityFilter == "") {
        $sqlAllFeaturesCodes = $linkBD->query("SELECT * FROM featuresvalidation ORDER BY fulldate DESC");
      } else {
        $sqlAllFeaturesCodes = $linkBD->query("SELECT * FROM featuresvalidation WHERE $availabilityFilter ORDER BY fulldate DESC");
      }
    } else {
      $searchBeIDList = [];
      if (in_array("code", $searchBy)) {
        $sqlFeaturesCodesFilterByCode = $linkBD->query("SELECT beid FROM featuresvalidation WHERE id LIKE '%$searchQuery%' ");
        if ($sqlFeaturesCodesFilterByCode->num_rows > 0) {
          while($rowFeaturesCodesFilterByCode = $sqlFeaturesCodesFilterByCode->fetch_assoc()) {
            array_push($searchBeIDList, $rowFeaturesCodesFilterByCode["beid"]);
          }
        }
      }
      if (in_array("feature", $searchBy)) {
        $sqlFeaturesCodesFilterByFeature = $linkBD->query("SELECT beid FROM featuresvalidation WHERE name LIKE '%$searchQuery%' ");
        if ($sqlFeaturesCodesFilterByFeature->num_rows > 0) {
          while($rowFeaturesCodesFilterByFeature = $sqlFeaturesCodesFilterByFeature->fetch_assoc()) {
            array_push($searchBeIDList, $rowFeaturesCodesFilterByFeature["beid"]);
          }
        }
      }
      if (in_array("user", $searchBy)) {
        $listOfSearchUser = [];
        $explodeName = explode(' ', $searchQuery, 2);
        if (sizeof($explodeName) > 1) {
          if ($explodeName[1] != "") {
            $fstName = $explodeName[0];
            $secName = $explodeName[1];
            $sqlSearchUser = $linkBD->query("SELECT beid FROM usersarchive WHERE firstname LIKE '%$fstName%' or lastname LIKE '%$secName%' or firstname LIKE '%$secName%' or lastname LIKE '%$fstName%'");
          } else {
            $searchQuery = str_replace(" ", "", $searchQuery);
            $sqlSearchUser = $linkBD->query("SELECT beid FROM usersarchive WHERE firstname LIKE '%$searchQuery%' or lastname LIKE '%$searchQuery%'");
          }
        } else {
          $sqlSearchUser = $linkBD->query("SELECT beid FROM usersarchive WHERE firstname LIKE '%$searchQuery%' or lastname LIKE '%$searchQuery%'");
        }
        if ($sqlSearchUser->num_rows > 0) {
          while($rowSearchUser = $sqlSearchUser->fetch_assoc()) {
            array_push($listOfSearchUser, $rowSearchUser["beid"]);
          }
        }
        $sqlFeaturesCodesFilterByUser = $linkBD->query("SELECT beid FROM featuresinuse WHERE usrbeid IN ('".implode("', '", $listOfSearchUser)."')");
        if ($sqlFeaturesCodesFilterByUser->num_rows > 0) {
          while($rowFeaturesCodesFilterByUser = $sqlFeaturesCodesFilterByUser->fetch_assoc()) {
            array_push($searchBeIDList, $rowFeaturesCodesFilterByUser["beid"]);
          }
        }
      }
      if ($availabilityFilter == "") {
        $sqlAllFeaturesCodes = $linkBD->query("SELECT * FROM featuresvalidation WHERE beid IN ('".implode("', '", $searchBeIDList)."') ORDER BY fulldate DESC");
      } else {
        $sqlAllFeaturesCodes = $linkBD->query("SELECT * FROM featuresvalidation WHERE beid IN ('".implode("', '", $searchBeIDList)."') and $availabilityFilter ORDER BY fulldate DESC");
      }
    }
    $numOfAllFeaturesCodes = $sqlAllFeaturesCodes->num_rows;
    if ($numOfAllFeaturesCodes > 0) {
      while ($rowAllFeaturesCodes = $sqlAllFeaturesCodes->fetch_assoc()) {
        if ($done < $limit) {
          $fcCode = $rowAllFeaturesCodes['id'];
          $fcFeature = $rowAllFeaturesCodes['name'];
          if ($readyToStart) {
            $inArray = false;
            for ($lOUD=0; $lOUD < sizeOf($listOfUsedData); $lOUD++) {
              if (
                $listOfUsedData[$lOUD]["code"] == $fcCode &&
                $listOfUsedData[$lOUD]["feature"] == $fcFeature
              ) {
                $inArray = true;
              }
            }
            if (!$inArray) {
              array_push($listOfUsedData, [
                "code" => $fcCode,
                "feature" => $fcFeature
              ]);
              $featureBeID = $rowAllFeaturesCodes['beid'];
              $sqlFeaturesInUse = $linkBD->query("SELECT * FROM featuresinuse WHERE beid='$featureBeID' LIMIT 1");
              if ($sqlFeaturesInUse->num_rows > 0) {
                $userBeID = $sqlFeaturesInUse->fetch_assoc()['usrbeid'];
                $sqlAboutUser = $linkBD->query("SELECT * FROM usersarchive WHERE beid='$userBeID' LIMIT 1");
                if ($sqlAboutUser->num_rows > 0) {
                  $rowUser = $sqlAboutUser->fetch_assoc();
                  $userSts = $rowUser['status'];
                  $userID = getFrontendId($userBeID);
                  $username = $rowUser['firstname']." ".$rowUser['lastname'];
                } else {
                  $userSts = "not-found";
                  $userID = "-";
                  $username = "-";
                }
              } else {
                $userSts = "not-connected";
                $userID = "-";
                $username = "-";
              }
              $featureCode = $rowAllFeaturesCodes['id'];
              $sqlNumOfConnectedFeatures = $linkBD->query("SELECT * FROM featuresvalidation WHERE id='$featureCode'");
              array_push($listOfOutputs, [
                "type" => "code",
                "status" => $rowAllFeaturesCodes['sts'],
                "code" => $featureCode,
                "feature" => $rowAllFeaturesCodes['name'],
                "userStatus" => $userSts,
                "userID" => $userID,
                "username" => $username,
                "numOfFeatures" => $sqlNumOfConnectedFeatures->num_rows
              ]);
              ++$loaded;
              ++$done;
            }
          } else {
            $inArray = false;
            for ($lOUD=0; $lOUD < sizeOf($listOfUsedData); $lOUD++) {
              if (
                $listOfUsedData[$lOUD]["code"] == $fcCode &&
                $listOfUsedData[$lOUD]["feature"] == $fcFeature
              ) {
                $inArray = true;
              }
            }
            if (!$inArray) {
              array_push($listOfUsedData, [
                "code" => $fcCode,
                "feature" => $fcFeature
              ]);
            }
            ++$loaded;
            if ($fcCode == $lastCode && $fcFeature == $lastFeature) {
              $readyToStart = true;
            }
          }
        }
      }
      $loadAmount = [
        "all-codes" => $numOfAllFeaturesCodes,
        "loaded" => $loaded,
        "remain" => $numOfAllFeaturesCodes - $loaded
      ];
    } else {
      if ($get == "load-amount") {
        $loadAmount = [
          "all-codes" => 0,
          "loaded" => 0,
          "remain" => 0
        ];
      }
    }
    if ($get == "list") {
      return $listOfOutputs;
    } else {
      return $loadAmount;
    }
  }

  function getListOfFeaturesCodesSearchType($searchQuery) {
    return ["code", "feature", "user"];
  }
?>
