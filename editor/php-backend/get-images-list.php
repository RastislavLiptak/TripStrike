<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-user.php";
  include "place-verification.php";
  header('Content-Type: application/json');
  $output = [];
  $url_id = mysqli_real_escape_string($link, $_POST['urlId']);
  $placeVerify = placeVerification($url_id);
  if ($placeVerify == "good") {
    $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$url_id' LIMIT 1");
    $beId = $sqlIdToBeId->fetch_assoc()["beid"];
    $sqlImgList = $link->query("SELECT imgbeid, sts FROM placeimages WHERE cottbeid='$beId' and type='mid' and sts!='delete' ORDER BY numid DESC");
    if ($sqlImgList->num_rows > 0) {
      while($rowImgList = $sqlImgList->fetch_assoc()) {
        $imgListSts = $rowImgList["sts"];
        $imgListBeId = $rowImgList["imgbeid"];
        $sqlSetImgSrc = $link->query("SELECT src FROM images WHERE name='$imgListBeId'");
        if ($sqlSetImgSrc->num_rows > 0) {
          $imgListSrc = $sqlSetImgSrc->fetch_assoc()['src'];
          save($imgListBeId, $imgListSrc, $imgListSts);
        }
      }
    }
    returnOutput();
  } else {
    error($placeVerify);
  }

  function save($name, $src, $sts) {
    global $output;
    array_push($output, [
      "type" => "image",
      "name" => $name,
      "src" => $src,
      "sts" => $sts
    ]);
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
