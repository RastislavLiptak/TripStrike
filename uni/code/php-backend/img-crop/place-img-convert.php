<?php
  ini_set('memory_limit', '-1');
  include dirname(__DIR__)."/random-hash-maker.php";
  function placeImageConvert($maxW, $maxH, $imgBeId, $orgName, $usrBeId, $type, $status, $foldPrev, $foldName) {
    global $link;
    $new_name_check = false;
    while (!$new_name_check) {
      $new_name = randomHash(128);
      $sqlCH = $link->query("SELECT * FROM images WHERE name='$new_name'");
      if ($sqlCH->num_rows == 0) {
        $new_name_check = true;
      } else {
        $new_name_check = false;
      }
    }
    $sqlSrc = $link->query("SELECT src FROM images WHERE name='$orgName'");
    if ($sqlSrc->num_rows > 0) {
      $org_src = $sqlSrc->fetch_assoc()['src'];
      $org_src = $foldPrev."".$org_src;
      if (file_exists($org_src)) {
        list($org_width, $org_height) = getimagesize($org_src);
        if ($type == "org") {
          $new_width = $org_width;
          $new_height = $org_height;
        } else {
          if ($org_width >= $org_height) {
            if ($org_width >= $maxW) {
              $new_width = $maxW;
            } else {
              $new_width = $org_width;
            }
            $new_height = $new_width * $org_height / $org_width;
          } else if ($org_width < $org_height) {
            if ($org_height >= $maxH) {
              $new_height = $maxH;
            } else {
              $new_height = $org_height;
            }
            $new_width = $new_height * $org_width / $org_height;
          }
        }
        $sts = "plc-".$type;
        $new_src = $foldPrev."".$foldName."".$new_name.".jpeg";
        $dtb_src = str_replace("../","",$new_src);
        $sqlI = "INSERT INTO images (beid, name, status, src, successful) VALUES ('$usrBeId', '$new_name', '$sts', '$dtb_src', '0')";
        if (mysqli_query($link, $sqlI)) {
          $imgcreateFromSts = 1;
          $exploded = explode('.', $org_src);
          $ext = $exploded[count($exploded) - 1];
          if (preg_match('/jpg|jpeg/i', $ext)) {
            $imgTmp = imagecreatefromjpeg($org_src);
          } else if (preg_match('/png/i', $ext)) {
            $imgTmp = imagecreatefrompng($org_src);
          } else if (preg_match('/gif/i', $ext)) {
            $imgTmp = imagecreatefromgif($org_src);
          } else if (preg_match('/bmp/i', $ext)) {
            $imgTmp = imagecreatefrombmp($org_src);
          } else {
            $imgcreateFromSts = 0;
          }
          if ($imgcreateFromSts == 1) {
            $tmp = imagecreatetruecolor($new_width, $new_height);
            if (imagecopyresampled($tmp, $imgTmp, 0, 0, 0, 0, $new_width, $new_height, $org_width, $org_height)) {
              $unlink = false;
              if (file_exists($new_src)) {
                if (unlink($new_src)) {
                  $unlink = true;
                } else {
                  $unlink = false;
                }
              } else {
                $unlink = true;
              }
              if ($unlink) {
                if (imagejpeg($tmp, $new_src)) {
                  $sqlIUpdt = "UPDATE images SET successful='1' WHERE name='$new_name'";
                  if (mysqli_query($link, $sqlIUpdt)) {
                    imgDone($type, "done", $imgBeId, $new_name, $status);
                  } else {
                    imgDone($type, "plc-img-convert-2-sql", $imgBeId, $new_name, $status);
                  }
                } else {
                  imgDone($type, "imagejpeg-faild", $imgBeId, $new_name, $status);
                }
              } else {
                imgDone($type, "unlink-faild", $imgBeId, $new_name, $status);
              }
            } else {
              imgDone($type, "resize-faild", $imgBeId, $new_name, $status);
            }
          } else {
            imgDone($type, "create-from-faild", $imgBeId, $new_name, $status);
          }
        } else {
          imgDone($type, "plc-img-convert-1-sql", $imgBeId, $new_name, $status);
        }
      } else {
        imgDone($type, "img-not-exist", $imgBeId, $new_name, $status);
      }
    } else {
      imgDone($type, "no-img-in-database", $imgBeId, $new_name, $status);
    }
  }
?>
