<?php
  function linkDataRender($beId, $current, $last, $class) {
    global $link;
    $sql = $link->query("SELECT * FROM places WHERE beId='$beId'");
    $cott = $sql->fetch_assoc();
    $sqlID = $link->query("SELECT id FROM idlist WHERE beid='$beId' ORDER BY fullDate DESC LIMIT 1");
    $cottID = $sqlID->fetch_assoc();
    $imgSrc = "none";
    $sqlImgBeID = $link->query("SELECT imgbeid FROM placeimages WHERE cottbeid='$beId' and type='sml' and sts='main' ORDER BY numid DESC LIMIT 1");
    if ($sqlImgBeID->num_rows > 0) {
      $imgName = $sqlImgBeID->fetch_assoc()["imgbeid"];
      $sqlImgSrc = $link->query("SELECT src FROM images WHERE name='$imgName' && status='plc-sml'");
      if ($sqlImgSrc ->num_rows > 0) {
        $imgSrc = $sqlImgSrc->fetch_assoc()['src'];
      }
    }
    $sqlCottRating = $link->query("SELECT percentage FROM ratingsummary WHERE beid='$beId'");
    if ($sqlCottRating->num_rows > 0) {
      $rating = str_replace('.',',',round($sqlCottRating->fetch_assoc()["percentage"] * 5 / 100, 2));
    } else {
      $rating = "none";
    }
    if ($current +1 == $last) {
      $jsonSts = 1;
    } else {
      $jsonSts = 0;
    }
    pushLinkToArray($jsonSts, $class, $cottID["id"], $cott["name"], $imgSrc, $cott["priceMode"], $cott["weekDayPrice"], $cott["workDayPrice"], $cott["currency"], $rating);
  }
?>
