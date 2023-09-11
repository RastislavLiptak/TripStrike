<?php
  include "../data.php";
  include "../random-hash-maker.php";
  include "../img-crop/place-img-convert.php";
  include "../get-frontend-id.php";
  include "../../../dictionary/lang-select.php";
  include "../get-user.php";
  header('Content-Type: application/json');
  ini_set('memory_limit', '-1');
  $output = [];
  $numOfImgDone = 0;
  $multipleErrorString = "";
  $allowed = array('jpg', 'png', 'jpeg');
  if (isset($_SERVER["CONTENT_LENGTH"])) {
    if ($_SERVER["CONTENT_LENGTH"] < ((int)ini_get('post_max_size') * 1024 * 1024)) {
      if ($bnft_add_cottage == "good") {
        $numOfImages = count($_FILES['file']['name']);
        if ($numOfImages > 0) {
          for ($f=0; $f < $numOfImages; $f++) {
            $fileName = $_FILES['file']['name'][$f];
            $fileTmpName = $_FILES['file']['tmp_name'][$f];
            $fileSize = $_FILES['file']['size'][$f];
            $fileError = $_FILES['file']['error'][$f];
            $fileExt = explode('.', $fileName);
            $fileActualExt = strtolower(end($fileExt));
            if (in_array($fileActualExt, $allowed)){
              if ($fileError===0) {
                if ($fileSize < 5000000) {    //bytes - 5MB allow
                  $nameCheck = false;
                  while (!$nameCheck) {
                    $name = randomHash(128);
                    $sqlCH = $link -> query("SELECT * FROM images WHERE name='$name'");
                    if ($sqlCH -> num_rows == 0) {
                      $nameCheck = true;
                    } else {
                      $nameCheck = false;
                    }
                  }
                  $fileNameNew = $name.".".$fileActualExt;
                  $src = "../../../../media/temp/".$fileNameNew;
                  $dtbSrc = str_replace("../","", $src);
                  $sql = "INSERT INTO images (beid, name, status, src, successful) VALUES ('$usrBeId' ,'$name', 'plc-org-temp', '$dtbSrc', '0')";
                  if (mysqli_query($link, $sql)) {
                    if (move_uploaded_file($fileTmpName, $src)) {
                      $sqlUpdt = "UPDATE images SET successful='1' WHERE name='$name';";
                      if (mysqli_query($link, $sqlUpdt)) {
                        placeImageConvert(384, 216, $name, $name, $usrBeId, "sml-temp", "", "../../../../", "media/temp/");
                      } else {
                        imageProcessingDone();
                        multipleErrorHandler($fileName." failed to update status in database<br>".mysqli_error($link));
                      }
                    } else {
                      imageProcessingDone();
                      multipleErrorHandler($fileName." failed to move file");
                    }
                  } else {
                    imageProcessingDone();
                    multipleErrorHandler($fileName." failed to save to the database<br>".mysqli_error($link));
                  }
                } else {
                  imageProcessingDone();
                  multipleErrorHandler($fileName." is too large. Maximal size of the file is limited to 5MB");
                }
              } else {
                imageProcessingDone();
                multipleErrorHandler($fileName." has an error (error: ".$fileError.")");
              }
            } else {
              imageProcessingDone();
              multipleErrorHandler($fileName." is in a format that is not supported. You can use images in JPG, JPEG or PNG only");
            }
          }
        } else {
          error("No file selected");
        }
      } else {
        if ($bnft_add_cottage == "none") {
          error("Task is unavailable");
        } else if ($bnft_add_cottage == "ban") {
          error("Task is banned");
        } else {
          error("Task availability status not detected");
        }
      }
    } else {
      error("The total size of uploaded files is over 8388608 bytes");
    }
  } else {
    error("Failed to get content length");
  }

  function imgDone($type, $msg, $imgBeId, $name, $sts) {
    global $output;
    if ($msg == "done") {
      imageProcessingDone();
      array_push($output, [
        "type" => "image",
        "org" => $imgBeId,
        "sml" => "media/temp/".$name.".jpeg"
      ]);
      checkIfAllImagesAreDone();
    } else {
      imageProcessingDone();
      multipleErrorHandler("Image convert failed (error: ".$msg.")");
    }
  }

  function imageProcessingDone() {
    global $numOfImgDone;
    ++$numOfImgDone;
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
    global $output, $multipleErrorString, $numOfImages, $numOfImgDone;
    if ($numOfImages == $numOfImgDone) {
      if ($multipleErrorString != "") {
        array_push($output, [
          "type" => "error",
          "error" => $multipleErrorString
        ]);
      }
      returnOutput();
    }
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
