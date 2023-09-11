<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/img-crop/place-img-convert.php";
  include "place-verification.php";
  header('Content-Type: application/json');
  ini_set('memory_limit', '-1');
  $output = [];
  $url_id = mysqli_real_escape_string($link, $_POST['urlId']);
  $name = mysqli_real_escape_string($link, $_POST['name']);
  $placeVerify = placeVerification($url_id);
  if ($placeVerify == "good") {
    $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$url_id' LIMIT 1");
    $beId = $sqlIdToBeId->fetch_assoc()["beid"];
    $sqlImgSts = $link->query("SELECT sts FROM placeimages WHERE imgbeid='$name' LIMIT 1");
    if ($sqlImgSts->num_rows > 0) {
      $imgSts = $sqlImgSts->fetch_assoc()["sts"];
      if ($imgSts == "main") {
        $sqlNumOfCommonImgs = $link->query("SELECT * FROM placeimages WHERE cottbeid='$beId' and sts='common'");
        if ($sqlNumOfCommonImgs->num_rows > 0) {
          $sqlGetSomeCommonImg = $link->query("SELECT imgbeid FROM placeimages WHERE cottbeid='$beId' and sts='common' LIMIT 1");
          if ($sqlGetSomeCommonImg->num_rows > 0) {
            $someMainImgBeId = $sqlGetSomeCommonImg->fetch_assoc()["imgbeid"];
            $sqlGetUniCommonImgBeId = $link->query("SELECT beid FROM placeimageskey WHERE convertname='$someMainImgBeId' LIMIT 1");
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
                deleteImage();
              } else {
                error("No image to upgrade to main image found <br>".mysqli_error($link));
              }
            } else {
              error("Failed to get new main image in database <br>".mysqli_error($link));
            }
          } else {
            error("No alternative for main image found <br>".mysqli_error($link));
          }
        } else {
          deleteImage();
        }
      } else {
        deleteImage();
      }
    } else {
      error("Image not found in database <br>".mysqli_error($link));
    }
  } else {
    error($placeVerify);
  }

  function deleteImage() {
    global $link, $name;
    $deleteFailed = false;
    $sqlDelteKey = $link->query("SELECT beid FROM placeimageskey WHERE convertname='$name'");
    if ($sqlDelteKey->num_rows > 0) {
      $deleteKey = $sqlDelteKey->fetch_assoc()["beid"];
      $sqlDelteImageBeId = $link->query("SELECT convertname FROM placeimageskey WHERE beid='$deleteKey'");
      if ($sqlDelteImageBeId->num_rows > 0) {
        $multipleError = "";
        while($rowDelteImageBeId = $sqlDelteImageBeId->fetch_assoc()) {
          $deleteName = $rowDelteImageBeId["convertname"];
          $sqlDeleteSrc = $link->query("SELECT src FROM images WHERE name='$deleteName'");
          if ($sqlDeleteSrc->num_rows > 0) {
            $deleteSrc = $sqlDeleteSrc->fetch_assoc()["src"];
            $sqlDelPlcImg = "UPDATE placeimages SET sts='delete' WHERE imgbeid='$deleteName'";
            if (mysqli_query($link, $sqlDelPlcImg)) {
              $sqlDeleteImg = "UPDATE images SET status='delete' WHERE name='$deleteName'";
              if (mysqli_query($link, $sqlDeleteImg)) {
                if (file_exists("../../".$deleteSrc)) {
                  unlink("../../".$deleteSrc);
                }
              } else {
                $deletefailed = true;
                if ($multipleError == "") {
                  $multipleError = "Faild to delete image from database of images";
                } else {
                  $multipleError = $multipleError."<br>Faild to delete image from database of images";
                }
              }
            } else {
              $deleteFailed = true;
              if ($multipleError == "") {
                $multipleError = "Faild to delete image from database of images for places";
              } else {
                $multipleError = $multipleError."<br>Faild to delete image from database of images for places";
              }
            }
          } else {
            $deleteFailed = true;
            if ($multipleError == "") {
              $multipleError = "SRC of the image not found";
            } else {
              $multipleError = $multipleError."<br>SRC of the image not found";
            }
          }
        }
        if (!$deleteFailed) {
          $sqlDeleteKey = "DELETE FROM placeimageskey WHERE beid='$deleteKey'";
          if (mysqli_query($link, $sqlDeleteKey)) {
            done();
          } else {
            error("Faild to delete key of the deleted image");
          }
        } else {
          error($multipleError);
        }
      } else {
        error("Other variations of image to delete not found");
      }
    } else {
      error("Key of image to delete not found");
    }
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
