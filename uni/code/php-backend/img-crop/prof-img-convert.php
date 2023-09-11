<?php
ini_set('memory_limit', '-1');
include dirname(__DIR__)."/random-hash-maker.php";
function profileImage($beId, $newSize, $type, $img, $fold) {
  global $link;
  $altImg = "false";
  $sqlD = $link -> query("SELECT * FROM images WHERE name='$img'");
  if ($sqlD->num_rows > 0 && $img != "sign-up") {
    $tempSrc = $fold."media/temp/".$img.".jpeg";
  } else {
    if ($img != "sign-up") {
      $altImg = "true";
    }
    $tempSrc = $fold."uni/images/profile-image.png";
  }
  $nameCheck = false;
  while (!$nameCheck) {
    $newName = randomHash(128);
    $sqlCH = $link -> query("SELECT * FROM images WHERE name='$newName'");
    if ($sqlCH -> num_rows == 0) {
      $nameCheck = true;
    } else {
      $nameCheck = false;
    }
  }
  $src = $fold."media/profile/".$newName.".jpeg";
  if ($type == "small") {
    $sts = "prf-small";
  } else if ($type == "mid") {
    $sts = "prf-mid";
  } else if ($type == "big") {
    $sts = "prf-big";
  }
  $sqlIDel = "UPDATE images SET status='delete' WHERE beid='$beId' && status='$sts'";
  if (mysqli_query($link, $sqlIDel)) {
    $dtbSrc = str_replace("../","",$src);
    $sqlI = "INSERT INTO images (beid, name, status, src, successful) VALUES ('$beId', '$newName', '$sts', '$dtbSrc', '0')";
    if (mysqli_query($link, $sqlI)) {
      $imgcreateFromSts = 1;
      $exploded = explode('.', $tempSrc);
      $ext = $exploded[count($exploded) - 1];
      if (preg_match('/jpg|jpeg/i', $ext)) {
        $imgTmp = imagecreatefromjpeg($tempSrc);
      } else if (preg_match('/png/i', $ext)) {
        $imgTmp = imagecreatefrompng($tempSrc);
      } else if (preg_match('/gif/i', $ext)) {
        $imgTmp = imagecreatefromgif($tempSrc);
      } else if (preg_match('/bmp/i', $ext)) {
        $imgTmp = imagecreatefrombmp($tempSrc);
      } else {
        $imgcreateFromSts = 0;
      }
      if ($imgcreateFromSts == 1) {
        list($width, $height) = getimagesize($tempSrc);
        $newHeight = $newSize;
        $newWidth = $newSize;
        $tmp = imagecreatetruecolor($newWidth, $newHeight);
        if (imagecopyresampled($tmp, $imgTmp, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height)) {
          $unlink = false;
          if (file_exists($src)) {
            if (unlink($src)) {
              $unlink = true;
            } else {
              $unlink = false;
            }
          } else {
            $unlink = true;
          }
          if ($unlink) {
            if (imagejpeg($tmp, $src)) {
              $sqlIUpdt = "UPDATE images SET successful='1' WHERE name='$newName'";
              if (mysqli_query($link, $sqlIUpdt)) {
                if ($altImg == "false") {
                  imgDone($type, "done", $beId);
                } else {
                  imgDone($type, "alternative image", $beId);
                }
              } else {
                imgDone($type, "image-sql-faild-2", $beId);
              }
            } else {
              imgDone($type, "image-imagejpeg", $beId);
            }
          } else {
            imgDone($type, "image-unlink", $beId);
          }
        } else {
          imgDone($type, "image-resize", $beId);
        }
      } else {
        imgDone($type, "create-from-faild", $beId);
      }
    } else {
      imgDone($type, "image-sql-faild-1", $beId);
    }
  } else {
    imgDone($type, "img-del-sql-error", $beId);
  }
}
?>
