<?php
  include "../data.php";
  include "../random-hash-maker.php";
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
  $name = $_POST['name'];
  $startL = $_POST['lftStrt'];
  $startT = $_POST['topStrt'];
  $wrpW = $_POST['wrpWidth'];
  $crpH = $_POST['cropH'];
  $crpW = $_POST['cropW'];
  $sqlNameCH = $link -> query("SELECT * FROM images WHERE name='$name' && status='crop' & successful='1'");
  if ($sqlNameCH->num_rows != 0) {
    $nameCheck = false;
    while (!$nameCheck) {
      $newName = randomHash(128);
      $sqlCH = $link -> query("SELECT * FROM images WHERE name='$newName'");
      if ($sqlCH->num_rows == 0) {
        $nameCheck = true;
      } else {
        $nameCheck = false;
      }
    }
    $src = "../../../../media/temp/".$newName.".jpeg";
    $dtbSrc = str_replace("../","",$src);
    $sql = "INSERT INTO images (beid, name, status, src, successful) VALUES ('$beId', '$newName', 'temp', '$dtbSrc', '0')";
    if (mysqli_query($link, $sql)) {
      $orgPath = "../../../../media/crop/".$name.".jpeg";
      list($width_picture, $height_picture) = getimagesize($orgPath);
      $x = $width_picture / $wrpW;
      $crop_start_x = $startL * $x;
      $crop_start_y = $startT * $x;
      $width = $crpW * $x;
      $height = $crpH * $x;
      $imgcreateFromSts = 1;
      $exploded = explode('.', $orgPath);
      $ext = $exploded[count($exploded) - 1];
      if (preg_match('/jpg|jpeg/i', $ext)) {
        $im = imagecreatefromjpeg($orgPath);
      } else if (preg_match('/png/i', $ext)) {
        $im = imagecreatefrompng($orgPath);
      } else if (preg_match('/gif/i', $ext)) {
        $im = imagecreatefromgif($orgPath);
      } else if (preg_match('/bmp/i', $ext)) {
        $im = imagecreatefrombmp($orgPath);
      } else {
        $imgcreateFromSts = 0;
      }
      if ($imgcreateFromSts == 1) {
        $size = min(imagesx($im), imagesy($im));
        $im2 = imagecrop($im, ['x' => $crop_start_x, 'y' => $crop_start_y, 'width' => $width, 'height' => $height]);
        if ($im2 !== FALSE) {
          if (imagejpeg($im2, $src)) {
            $sqlUpdt = "UPDATE images SET successful='1' WHERE name='$newName'";
            if (mysqli_query($link, $sqlUpdt)) {
              outputCreate("1", $dtbSrc);
            } else {
              outputCreate("2", "upl-err-sql-2");
            }
          } else {
            outputCreate("2", "upl-err-imagejpeg");
          }
        } else {
          outputCreate("2", "upl-err-crop");
        }
      } else {
        outputCreate("2", "upl-err-create-from-faild");
      }
    } else {
      outputCreate("2", "upl-err-sql-1");
    }
  } else {
    outputCreate("2", "upl-err-missing");
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
