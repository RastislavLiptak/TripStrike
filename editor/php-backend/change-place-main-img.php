<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-user.php";
  include "place-verification.php";
  header('Content-Type: application/json');
  $output = [];
  $url_id = mysqli_real_escape_string($link, $_POST['urlId']);
  $name = mysqli_real_escape_string($link, $_POST['name']);
  $placeVerify = placeVerification($url_id);
  if ($placeVerify == "good") {
    $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$url_id' LIMIT 1");
    $beId = $sqlIdToBeId->fetch_assoc()["beid"];
    $sqlGetSomeMainImg = $link->query("SELECT imgbeid FROM placeimages WHERE cottbeid='$beId' and sts='main' LIMIT 1");
    if ($sqlGetSomeMainImg->num_rows > 0) {
      $someMainImgBeId = $sqlGetSomeMainImg->fetch_assoc()["imgbeid"];
      $sqlGetUniMainImgBeId = $link->query("SELECT beid FROM placeimageskey WHERE convertname='$someMainImgBeId' LIMIT 1");
      if ($sqlGetUniMainImgBeId->num_rows > 0) {
        $uniMainImgBeId = $sqlGetUniMainImgBeId->fetch_assoc()["beid"];
        $sqlImageKeyDowngrade = "UPDATE placeimageskey SET sts='common' WHERE beid='$uniMainImgBeId'";
        if (mysqli_query($link, $sqlImageKeyDowngrade)) {
          $sqlImageDowngrade = "UPDATE placeimages SET sts='common' WHERE cottbeid='$beId' and sts='main'";
          if (mysqli_query($link, $sqlImageDowngrade)) {
            $sqlGetUniCommonImgBeId = $link->query("SELECT beid FROM placeimageskey WHERE convertname='$name' LIMIT 1");
            if ($sqlGetUniCommonImgBeId->num_rows > 0) {
              $uniCommonImgBeId = $sqlGetUniCommonImgBeId->fetch_assoc()["beid"];
              $sqlCommonImgIds = $link->query("SELECT convertname FROM placeimageskey WHERE beid='$uniCommonImgBeId'");
              if ($sqlCommonImgIds->num_rows > 0) {
                while($commonImgIds = $sqlCommonImgIds->fetch_assoc()) {
                  $upgradeBeId = $commonImgIds['convertname'];
                  $sqlImageKeyUpgrade = "UPDATE placeimageskey SET sts='main' WHERE convertname='$upgradeBeId'";
                  mysqli_query($link, $sqlImageKeyUpgrade);
                  $sqlImageKeyUpgrade = "UPDATE placeimages SET sts='main' WHERE imgbeid='$upgradeBeId'";
                  mysqli_query($link, $sqlImageKeyUpgrade);
                }
                done();
              } else {
                error("No image to upgrade to main image found <br>".mysqli_error($link));
              }
            } else {
              error("Failed to get new main image in database <br>".mysqli_error($link));
            }
          } else {
            error("Failed to downgrade current main image from main to common in images database <br>".mysqli_error($link));
          }
        } else {
          error("Failed to downgrade current main image from main to common in key database <br>".mysqli_error($link));
        }
      } else {
        error("Failed to get current main image with original size from database <br>".mysqli_error($link));
      }
    } else {
      error("Failed to get current main image from database <br>".mysqli_error($link));
    }
  } else {
    error($placeVerify);
  }

  function done() {
    global $link, $output, $beId;
    $setImgBeIdArr = [];
    $sqlSetCommonImgBeId = $link->query("SELECT imgbeid FROM placeimages WHERE cottbeid='$beId' and type='mid' and sts='common' ORDER BY numid DESC LIMIT 2");
    if ($sqlSetCommonImgBeId->num_rows > 0) {
      while($rowSetCommonImg = $sqlSetCommonImgBeId->fetch_assoc()) {
        array_push($setImgBeIdArr, $rowSetCommonImg["imgbeid"]);
      }
    }
    $sqlSetMainImgBeId = $link->query("SELECT imgbeid FROM placeimages WHERE cottbeid='$beId' and type='mid' and sts='main' ORDER BY numid DESC LIMIT 1");
    if ($sqlSetMainImgBeId->num_rows > 0) {
      array_push($setImgBeIdArr, $sqlSetMainImgBeId->fetch_assoc()["imgbeid"]);
    }
    $setImgNum = 0;
    $setImgSizeNum = count($setImgBeIdArr);
    if (count($setImgBeIdArr) == 3) {
      $setImgSizeNum = 0;
    } else if (count($setImgBeIdArr) == 2) {
      $setImgSizeNum = 1;
    } else {
      $setImgSizeNum = 2;
    }
    foreach ($setImgBeIdArr as $img) {
      $sqlSetImgBeId = $link->query("SELECT imgbeid FROM placeimages WHERE imgbeid='$img' LIMIT 1");
      if ($sqlSetImgBeId->num_rows > 0) {
        $setImgBeId = $sqlSetImgBeId->fetch_assoc()["imgbeid"];
        $sqlSetImgSrc = $link->query("SELECT src FROM images WHERE name='$setImgBeId'");
        if ($sqlSetImgSrc->num_rows > 0) {
          $setImgSrc = $sqlSetImgSrc->fetch_assoc()['src'];
          if (count($setImgBeIdArr) == $setImgNum +1) {
            $editorImgClass = "main";
          } else {
            $editorImgClass = "";
          }
          array_push($output, [
            "type" => "image",
            "src" => $setImgSrc,
            "status" => $editorImgClass,
            "num" => $setImgNum
          ]);
          ++$setImgNum;
          ++$setImgSizeNum;
        }
      }
    }
    if ($setImgNum == 0) {
      array_push($output, [
        "type" => "no-images"
      ]);
    }
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
