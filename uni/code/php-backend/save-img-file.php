<?php
  include "data.php";
  include "random-hash-maker.php";
  header('Content-Type: application/json');
  ini_set('memory_limit', '-1');
  if (isset($_SESSION["signID"]) && isset($_SESSION["email"])) {
    $em = $_SESSION["email"];
    $sqlUser = $link->query("SELECT * FROM users WHERE email='$em' && status='active'");
    if ($sqlUser->num_rows > 0) {
      $usr = $sqlUser->fetch_assoc();
      $beId = $usr['beid'];
    } else {
      $beId = "none";
    }
  } else {
    $beId = "none";
  }
  $file = $_FILES['file'];
  $fileName = $_FILES['file']['name'];
  $fileTmpName = $_FILES['file']['tmp_name'];
  $fileSize = $_FILES['file']['size'];
  $fileError = $_FILES['file']['error'];
  $fileType = $_FILES['file']['type'];
  $fileExt = explode('.', $fileName);
  $fileActualExt = strtolower(end($fileExt));
  $allowed = array('jpg', 'png', 'jpeg');
  if (in_array($fileActualExt, $allowed)){
    if ($fileError===0) {
      if ($fileSize < 2000000) {    //bytes - 2MB allow
        $name = fileNameGenerator();
        $fileNameNew = $name.".".$fileActualExt;
        $src = "../../../media/temp/".$fileNameNew;
        $dtbSrc = str_replace("../","",$src);
        $sql = "INSERT INTO images (beid, name, status, src, successful) VALUES ('$beId' ,'$name', 'temp', '$dtbSrc', '0')";
        if (mysqli_query($link, $sql)) {
          if (move_uploaded_file($fileTmpName, $src)) {
            $sqlUpdt = "UPDATE images SET successful='1' WHERE name='$name';";
            if (mysqli_query($link, $sqlUpdt)){
              imageConvert($src);
            } else {
              outputCreate("2", "upl-err-sql-2");
            }
          } else {
            outputCreate("2", "upl-err-move");
          }
        } else {
          outputCreate("2", "upl-err-sql-1");
        }
      } else {
        outputCreate("2", "upl-err-out-size");
      }
    } else {
      outputCreate("2", "file-error: ".$fileError);
    }
  } else {
    outputCreate("2", "upl-err-type");
  }

  function imageConvert($temp) {
    global $link, $beId;
    $setRatio = $_POST['ratio'];
    $name = fileNameGenerator();
    $src = "../../../media/crop/".$name.".jpeg";
    $dtbSrc = str_replace("../","",$src);
    $sql = "INSERT INTO images (beid, name, status, src, successful) VALUES ('$beId', '$name', 'crop', '$dtbSrc', '0')";
    if (mysqli_query($link, $sql)) {
      $sqlUpdt = "UPDATE images SET successful='2' WHERE name='$name';";
      if (mysqli_query($link, $sqlUpdt)) {
        list($image_width, $image_height) = getimagesize($temp);
        $imgcreateFromSts = 1;
        $exploded = explode('.', $temp);
        $ext = $exploded[count($exploded) - 1];
        if (preg_match('/jpg|jpeg/i', $ext)) {
          $gd_image = imagecreatefromjpeg($temp);
        } else if (preg_match('/png/i', $ext)) {
          $gd_image = imagecreatefrompng($temp);
        } else if (preg_match('/gif/i', $ext)) {
          $gd_image = imagecreatefromgif($temp);
        } else if (preg_match('/bmp/i', $ext)) {
          $gd_image = imagecreatefrombmp($temp);
        } else {
          $imgcreateFromSts = 0;
        }
        if ($imgcreateFromSts == 1) {
          if ($setRatio == "1:1") {
            if ($image_width >= $image_height) {
              $ratio_width = $image_width;
              $ratio_height = $image_width;
            } else if ($image_width < $image_height) {
              $ratio_width = $image_height;
              $ratio_height = $image_height;
            }
          } else if ($setRatio == "16:9") {
            if ($image_width >= $image_height) {
              $ratio_width = $image_width;
              $ratio_height = $image_width * 720 / 1280;
            } else if($image_width < $image_height) {
              $ratio_height = $image_height;
              $ratio_width = $image_height * 1280 / 720;
            }
          }
          $image_ratio = $image_width / $image_height;
          $ratio = $ratio_width / $ratio_height;
          if ($image_width <= $ratio_width && $image_height <= $ratio_height) {
            $new_width = $image_width;
            $new_height = $image_height;
          } else if ($ratio > $image_ratio) {
            $new_width = (int) ($ratio_height * $image_ratio);
            $new_height = $ratio_height;
          } else {
            $new_width = $ratio_width;
            $new_height = (int) ($ratio_width / $image_ratio);
          }
          $gd = imagecreatetruecolor($new_width, $new_height);
          if (imagecopyresampled($gd, $gd_image, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height)) {
            $disp = imagecreatetruecolor($ratio_width,$ratio_height);
            $color = imagecolorallocate($disp,0,0,0);
            if (imagefill($disp,0,0,$color)) {
              if (imagecopy($disp, $gd, (imagesx($disp)/2)-(imagesx($gd)/2), (imagesy($disp)/2)-(imagesy($gd)/2), 0, 0, imagesx($gd), imagesy($gd))) {
                if (imagejpeg($disp, $src, 9)) {
                  $sqlUpdt2 = "UPDATE images SET successful='1' WHERE name='$name';";
                  if (mysqli_query($link, $sqlUpdt2)) {
                    outputCreate("1", $name);
                  } else {
                    outputCreate("2", "upl-err-sql-5");
                  }
                } else {
                  outputCreate("2", "upl-err-proccess-final");
                }
              } else {
                outputCreate("2", "upl-err-proccess-copy");
              }
            } else {
              outputCreate("2", "upl-err-proccess-fill");
            }
          } else {
            outputCreate("2", "upl-err-proccess-copy-resampled");
          }
        } else {
          outputCreate("2", "upl-err-create-from-faild");
        }
      } else {
        outputCreate("2", "upl-err-sql-4");
      }
    } else {
      outputCreate("2", "upl-err-sql-3");
    }
  }

  function fileNameGenerator() {
    global $link;
    $nameCheck = false;
    while (!$nameCheck) {
      $name = randomHash(128);
      $sqlCH = $link -> query("SELECT * FROM images WHERE name='$name'");
      if ($sqlCH->num_rows == 0) {
        $nameCheck = true;
      } else {
        $nameCheck = false;
      }
    }
    return $name;
  }

  function outputCreate($sts, $msg) {
    $output = [];
    array_push($output, [
      "sts" => $sts,
      "msg" => $msg
    ]);
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
