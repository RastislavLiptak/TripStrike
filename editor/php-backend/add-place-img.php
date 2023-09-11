<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/random-hash-maker.php";
  include "../../uni/code/php-backend/img-crop/place-img-convert.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-user.php";
  include "place-verification.php";
  header('Content-Type: application/json');
  ini_set('max_execution_time', '-1');
  ini_set('memory_limit', '-1');
  $output = [];
  $multipleErrorString = "";
  $orgImgDone = 0;
  $hugeImgDone = 0;
  $bigImgDone = 0;
  $midImgDone = 0;
  $smlImgDone = 0;
  $miniImgDone = 0;
  $newMainImgName = "";
  $allowed = array('jpg', 'png', 'jpeg');
  $url_id = mysqli_real_escape_string($link, $_POST['urlId']);
  $placeVerify = placeVerification($url_id);
  if (isset($_SERVER["CONTENT_LENGTH"])) {
    if ($_SERVER["CONTENT_LENGTH"] < ((int)ini_get('post_max_size') * 1024 * 1024)) {
      if ($placeVerify == "good") {
        $numOfImages = count($_FILES['file']['name']);
        if ($numOfImages > 0) {
          for ($f=0; $f < $numOfImages; $f++) {
            $plcSqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$url_id' LIMIT 1");
            $plcBeId = $plcSqlIdToBeId->fetch_assoc()["beid"];
            $fileName = $_FILES['file']['name'][$f];
            $fileTmpName = $_FILES['file']['tmp_name'][$f];
            $fileSize = $_FILES['file']['size'][$f];
            $fileError = $_FILES['file']['error'][$f];
            $fileExt = explode('.', $fileName);
            $fileActualExt = strtolower(end($fileExt));
            if (in_array($fileActualExt, $allowed)){
              if ($fileError===0) {
                if ($fileSize < 5000000) {  //bytes - 5MB allow
                  $orgNameCheck = false;
                  while (!$orgNameCheck) {
                    $orgName = randomHash(128);
                    $sqlCH = $link->query("SELECT * FROM images WHERE name='$orgName'");
                    if ($sqlCH->num_rows == 0) {
                      $orgNameCheck = true;
                    } else {
                      $orgNameCheck = false;
                    }
                  }
                  $imgBeIdReady = false;
                  while (!$imgBeIdReady) {
                    $imgBeId = randomHash(64);
                    $sqlImgBeIdCH = $link -> query("SELECT * FROM backendidlist WHERE beid='$imgBeId'");
                    if ($sqlImgBeIdCH->num_rows == 0) {
                      $imgBeIdReady = true;
                    } else {
                      $imgBeIdReady = false;
                    }
                  }
                  $orgNameFileNew = $orgName.".".$fileActualExt;
                  $orgSrc = "../../media/temp/".$orgNameFileNew;
                  $orgDtbSrc = str_replace("../","",$orgSrc);
                  $sqlOrg = "INSERT INTO images (beid, name, status, src, successful) VALUES ('$plcBeId' ,'$orgName', 'plc-org-temp', '$orgDtbSrc', '0')";
                  if (mysqli_query($link, $sqlOrg)) {
                    if (move_uploaded_file($fileTmpName, $orgSrc)) {
                      $sqlOrgUpdt = "UPDATE images SET successful='1' WHERE name='$orgName';";
                      if (mysqli_query($link, $sqlOrgUpdt)) {
                        placeImageConvert("", "", $imgBeId, $orgName, $usrBeId, "org", $fileName, "../../", "media/places/");
                        placeImageConvert(1920, 1080, $imgBeId, $orgName, $usrBeId, "huge", $fileName, "../../", "media/places/");
                        placeImageConvert(1024, 576, $imgBeId, $orgName, $usrBeId, "big", $fileName, "../../", "media/places/");
                        placeImageConvert(544, 306, $imgBeId, $orgName, $usrBeId, "mid", $fileName, "../../", "media/places/");
                        placeImageConvert(384, 216, $imgBeId, $orgName, $usrBeId, "sml", $fileName, "../../", "media/places/");
                        placeImageConvert(144, 81, $imgBeId, $orgName, $usrBeId, "mini", $fileName, "../../", "media/places/");
                      } else {
                        allModificationsOfTheImageDone();
                        multipleErrorHandler($fileName." failed to update status for original image<br>".mysqli_error($link));
                      }
                    } else {
                      allModificationsOfTheImageDone();
                      multipleErrorHandler($fileName." failed upload file in to our server");
                    }
                  } else {
                    allModificationsOfTheImageDone();
                    multipleErrorHandler($fileName." failed to save to the database<br>".mysqli_error($link));
                  }
                } else {
                  allModificationsOfTheImageDone();
                  multipleErrorHandler($fileName." is too large. Maximal size of the file is limited to 5MB");
                }
              } else {
                allModificationsOfTheImageDone();
                multipleErrorHandler($fileName." has an error (error: ".$fileError.")");
              }
            } else {
              allModificationsOfTheImageDone();
              multipleErrorHandler($fileName." is in a format that is not supported. You can use images in JPG, JPEG or PNG only");
            }
          }
        } else {
          error("No file selected");
        }
      } else {
        error($placeVerify);
      }
    } else {
      error("The total size of uploaded files is over 8388608 bytes");
    }
  } else {
    error("Failed to get content length");
  }

  function imgDone($type, $msg, $imgBeId, $name, $sts) {
    global $link, $plcBeId, $orgImgDone, $hugeImgDone, $bigImgDone, $midImgDone, $smlImgDone, $miniImgDone, $newMainImgName;
    if ($msg == "done") {
      $dtbSts = $type;
      $sqlMainPlc = $link->query("SELECT * FROM placeimages WHERE cottbeid='$plcBeId' && sts='main'");
      if ($sqlMainPlc->num_rows != 6) {
        if ($newMainImgName == "") {
          $imgSts = "main";
          $newMainImgName = $imgBeId;
        } else {
          if ($newMainImgName == $imgBeId) {
            $imgSts = "main";
          } else {
            $imgSts = "common";
          }
        }
      } else {
        $imgSts = "common";
      }
      if ($imgSts == "main") {
        $sqlMainPlcByType = $link->query("SELECT * FROM placeimages WHERE cottbeid='$plcBeId' && sts='main' && type='$dtbSts'");
        if ($sqlMainPlcByType->num_rows > 0) {
          deleteOldMainBeId($sqlMainPlcByType->fetch_assoc()["imgbeid"]);
        }
      }
      $sqlImgNum = $link->query("SELECT * FROM placeimages");
      $sqlImgId = $sqlImgNum->num_rows;
      $sqlImgPlc = "INSERT INTO placeimages (cottbeid, imgbeid, type, sts, numid) VALUES ('$plcBeId', '$name', '$dtbSts', '$imgSts', '$sqlImgId')";
      if (mysqli_query($link, $sqlImgPlc)) {
        $sqlImgKey = "INSERT INTO placeimageskey (beid, convertname, type, sts) VALUES ('$imgBeId', '$name', '$dtbSts', '$imgSts')";
        if (!mysqli_query($link, $sqlImgKey)) {
          multipleErrorHandler($sts." (type: ".$type.") failed to save key of the image to the database<br>".mysqli_error($link));
        }
      } else {
        multipleErrorHandler($sts." (type: ".$type.") failed to save to the database of cottage images<br>".mysqli_error($link));
      }
    } else {
      multipleErrorHandler($sts." image convert failed (type: ".$type."; error: ".$msg.")");
    }
    if ($type == "org") {
      ++$orgImgDone;
    } else if ($type == "huge") {
      ++$hugeImgDone;
    } else if ($type == "big") {
      ++$bigImgDone;
    } else if ($type == "mid") {
      ++$midImgDone;
    } else if ($type == "sml") {
      ++$smlImgDone;
    } else if ($type == "mini") {
      ++$miniImgDone;
    }
    checkIfAllImagesAreDone();
  }

  function deleteOldMainBeId($imgForDeleteBeId) {
    $sqlDeleteKey = "DELETE FROM placeimageskey WHERE convertname='$imgForDeleteBeId'";
    if (mysqli_query($link, $sqlDeleteKey)) {
      $sqlDelPlcImg = "UPDATE placeimages SET sts='delete' WHERE imgbeid='$imgForDeleteBeId'";
      if (mysqli_query($link, $sqlDelPlcImg)) {
        $sqlDeleteSrc = $link->query("SELECT src FROM images WHERE name='$imgForDeleteBeId'");
        if ($sqlDeleteSrc->num_rows > 0) {
          $deleteSrc = $sqlDeleteSrc->fetch_assoc()["src"];
          $sqlDeleteImg = "UPDATE images SET status='delete' WHERE name='$imgForDeleteBeId'";
          if (mysqli_query($link, $sqlDeleteImg)) {
            if (file_exists("../../".$deleteSrc)) {
              unlink("../../".$deleteSrc);
            }
          } else {
            multipleErrorHandler("Failed to delete old main image (type: ".$type.") from a database of images<br>".mysqli_error($link));
          }
        }
      } else {
        multipleErrorHandler("Failed to delete old main image (type: ".$type.") from a database of places images<br>".mysqli_error($link));
      }
    } else {
      multipleErrorHandler("Failed to delete old main image (type: ".$type.") from key database<br>".mysqli_error($link));
    }
  }

  function allModificationsOfTheImageDone() {
    global $orgImgDone, $hugeImgDone, $bigImgDone, $midImgDone, $smlImgDone, $miniImgDone;
    ++$orgImgDone;
    ++$hugeImgDone;
    ++$bigImgDone;
    ++$midImgDone;
    ++$smlImgDone;
    ++$miniImgDone;
  }

  function multipleErrorHandler($txt) {
    global $multipleErrorString;
    if ($multipleErrorString == "") {
      $multipleErrorString = $txt;
    } else {
      $multipleErrorString = $multipleErrorString."<br>".$txt;
    }
    checkIfAllImagesAreDone();
  }

  function checkIfAllImagesAreDone() {
    global $output, $multipleErrorString, $numOfImages, $orgImgDone, $hugeImgDone, $bigImgDone, $midImgDone, $smlImgDone, $miniImgDone;
    if (
      $orgImgDone == $numOfImages &&
      $hugeImgDone == $numOfImages &&
      $bigImgDone == $numOfImages &&
      $midImgDone == $numOfImages &&
      $smlImgDone == $numOfImages &&
      $miniImgDone == $numOfImages
    ) {
      if ($multipleErrorString != "") {
        array_push($output, [
          "type" => "error",
          "error" => $multipleErrorString
        ]);
      }
      done();
    }
  }

  function done() {
    global $link, $output, $plcBeId;
    $setImgBeIdArr = [];
    $sqlSetCommonImgBeId = $link->query("SELECT imgbeid FROM placeimages WHERE cottbeid='$plcBeId' and type='mid' and sts='common' ORDER BY numid DESC LIMIT 2");
    if ($sqlSetCommonImgBeId->num_rows > 0) {
      while($rowSetCommonImg = $sqlSetCommonImgBeId->fetch_assoc()) {
        array_push($setImgBeIdArr, $rowSetCommonImg["imgbeid"]);
      }
    }
    $sqlSetMainImgBeId = $link->query("SELECT imgbeid FROM placeimages WHERE cottbeid='$plcBeId' and type='mid' and sts='main' ORDER BY numid DESC LIMIT 1");
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
