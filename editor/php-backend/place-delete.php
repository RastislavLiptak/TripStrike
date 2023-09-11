<?php
  function placeDelete($beId) {
    global $link;
    $sqlUpdt = "UPDATE places SET status='delete' WHERE beid='$beId'";
    if (mysqli_query($link, $sqlUpdt)) {
      $plcDeleteErrorTxt = "";
      $sqlEquip = "DELETE FROM placeequipment WHERE beid='$beId'";
      if (!mysqli_query($link, $sqlEquip)) {
        $plcDeleteErrorTxt = $plcDeleteErrorTxt."Failed to delete placeequipment database: ".mysqli_error($link)."<br>";
      }
      $sqlVideos = "DELETE FROM placevideos WHERE beid='$beId'";
      if (!mysqli_query($link, $sqlVideos)) {
        $plcDeleteErrorTxt = $plcDeleteErrorTxt."Failed to delete placevideos database: ".mysqli_error($link)."<br>";
      }
      $sqlComm = "DELETE FROM comments WHERE beid='$beId'";
      if (!mysqli_query($link, $sqlComm)) {
        $plcDeleteErrorTxt = $plcDeleteErrorTxt."Failed to delete comments database: ".mysqli_error($link)."<br>";
      }
      $sqlRatings = "DELETE FROM rating WHERE beid='$beId'";
      if (!mysqli_query($link, $sqlRatings)) {
        $plcDeleteErrorTxt = $plcDeleteErrorTxt."Failed to delete rating database: ".mysqli_error($link)."<br>";
      }
      $sqlRatingsCritic = "DELETE FROM ratingcriticsummary WHERE beid='$beId'";
      if (!mysqli_query($link, $sqlRatingsCritic)) {
        $plcDeleteErrorTxt = $plcDeleteErrorTxt."Failed to delete ratingcriticsummary database: ".mysqli_error($link)."<br>";
      }
      $sqlRatingsSect = "DELETE FROM ratingsectionsummary WHERE beid='$beId'";
      if (!mysqli_query($link, $sqlRatingsSect)) {
        $plcDeleteErrorTxt = $plcDeleteErrorTxt."Failed to delete ratingsectionsummary database: ".mysqli_error($link)."<br>";
      }
      $sqlRatingsSummary = "DELETE FROM ratingsummary WHERE beid='$beId'";
      if (!mysqli_query($link, $sqlRatingsSummary)) {
        $plcDeleteErrorTxt = $plcDeleteErrorTxt."Failed to delete ratingsummary database: ".mysqli_error($link)."<br>";
      }
      $plcDeleteErrorTxt = "";
      $sqlPlcRulesKey = $link->query("SELECT beid FROM placeconditionskey WHERE plcbeid='$beId'");
      if ($sqlPlcRulesKey->num_rows > 0) {
        while($plcKey = $sqlPlcRulesKey->fetch_assoc()) {
          $plcRulesKey = $plcKey['beid'];
          $sqlRulesText = "DELETE FROM conditionsofstayofthehost WHERE beid='$plcRulesKey'";
          if (!mysqli_query($link, $sqlRulesText)) {
            $plcDeleteErrorTxt = $plcDeleteErrorTxt."Failed to delete conditionsofstayofthehost database: ".mysqli_error($link)."<br>";
          }
        }
      }
      $sqlRulesKey = "DELETE FROM placeconditionskey WHERE plcbeid='$beId'";
      if (!mysqli_query($link, $sqlRulesKey)) {
        $plcDeleteErrorTxt = $plcDeleteErrorTxt."Failed to delete placeconditionskey database: ".mysqli_error($link)."<br>";
      }
      $sqlPlcImg = $link->query("SELECT imgbeid FROM placeimages WHERE cottbeid='$beId'");
      if ($sqlPlcImg->num_rows > 0) {
        while($plcImg = $sqlPlcImg->fetch_assoc()) {
          $imgBeId = $plcImg['imgbeid'];
          $sqlImgKey = "DELETE FROM placeimageskey WHERE convertname='$imgBeId'";
          if (!mysqli_query($link, $sqlImgKey)) {
            $plcDeleteErrorTxt = $plcDeleteErrorTxt."Failed to delete placeimageskey database: ".mysqli_error($link)."<br>";
          }
          $sqlImg = $link->query("SELECT * FROM images WHERE name='$imgBeId'");
          if ($sqlImg->num_rows > 0) {
            while($img = $sqlImg->fetch_assoc()) {
              $nameImg = $img['name'];
              $srcImg = $img['src'];
              $sqlImgD = "UPDATE images SET status='delete' WHERE name='$nameImg'";
              if (mysqli_query($link, $sqlImgD)) {
                if (file_exists("../../../".$srcImg)) {
                  unlink("../../../".$srcImg);
                }
              } else {
                $plcDeleteErrorTxt = $plcDeleteErrorTxt."Failed to update images database: ".mysqli_error($link)."<br>";
              }
            }
          }
        }
        $sqlDelPlcImg = "UPDATE placeimages SET sts='delete' WHERE cottbeid='$beId'";
        if (!mysqli_query($link, $sqlDelPlcImg)) {
          $plcDeleteErrorTxt = $plcDeleteErrorTxt."Failed to delete placeimages database: ".mysqli_error($link)."<br>";
        }
      }
      $sqlDataRemove = "UPDATE places SET type='-', description='-', lat='0', lng='0' WHERE beid='$beId'";
      if (!mysqli_query($link, $sqlDataRemove)) {
        $plcDeleteErrorTxt = $plcDeleteErrorTxt."Failed to update places database: ".mysqli_error($link)."<br>";
      }
      if ($plcDeleteErrorTxt == "") {
        placeDeleteOutput("done");
      } else {
        placeDeleteOutput($plcDeleteErrorTxt);
      }
    } else {
      placeDeleteOutput("Failed to set place status to delete ".mysqli_error($link));
    }
  }
?>
