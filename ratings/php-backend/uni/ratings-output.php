<?php
  function ratingsOutput($plcBeId) {
    global $link, $output, $usrBeId, $midImg;
    $sqlPlaceHost = $link->query("SELECT usrbeid FROM places WHERE beid='$plcBeId'");
    $hostBeID = $sqlPlaceHost->fetch_assoc()['usrbeid'];
    $criticsSummaryPlace = 0;
    $lctPlcOutput = "none";
    $tidyPlcOutput = "none";
    $prcPlcOutput = "none";
    $parkPlcOutput = "none";
    $adPlcOutput = "none";
    $criticsSummaryHost = 0;
    $langHstOutput = "none";
    $commHstOutput = "none";
    $prsnHstOutput = "none";
    $sqlCriticsSummaryPlace = $link->query("SELECT percentage FROM ratingcriticsummary WHERE beid='$plcBeId' and critic='$usrBeId'");
    if ($sqlCriticsSummaryPlace->num_rows > 0) {
      $criticsSummaryPlace = $sqlCriticsSummaryPlace->fetch_assoc()['percentage'];
    }
    $sqlCriticsSummaryHost = $link->query("SELECT percentage FROM ratingcriticsummary WHERE beid='$hostBeID' and critic='$usrBeId'");
    if ($sqlCriticsSummaryHost->num_rows > 0) {
      $criticsSummaryHost = $sqlCriticsSummaryHost->fetch_assoc()['percentage'];
    }
    $sqlPlcLct = $link->query("SELECT percentage FROM ratingsectionsummary WHERE beid='$plcBeId' and section='lct'");
    if ($sqlPlcLct->num_rows > 0) {
      $lctPlcOutput = $sqlPlcLct->fetch_assoc()['percentage'];
    }
    $sqlPlcTidy = $link->query("SELECT percentage FROM ratingsectionsummary WHERE beid='$plcBeId' and section='tidy'");
    if ($sqlPlcTidy->num_rows > 0) {
      $tidyPlcOutput = $sqlPlcTidy->fetch_assoc()['percentage'];
    }
    $sqlPlcPrc = $link->query("SELECT percentage FROM ratingsectionsummary WHERE beid='$plcBeId' and section='prc'");
    if ($sqlPlcPrc->num_rows > 0) {
      $prcPlcOutput = $sqlPlcPrc->fetch_assoc()['percentage'];
    }
    $sqlPlcPark = $link->query("SELECT percentage FROM ratingsectionsummary WHERE beid='$plcBeId' and section='park'");
    if ($sqlPlcPark->num_rows > 0) {
      $parkPlcOutput = $sqlPlcPark->fetch_assoc()['percentage'];
    }
    $sqlPlcAd = $link->query("SELECT percentage FROM ratingsectionsummary WHERE beid='$plcBeId' and section='ad'");
    if ($sqlPlcAd->num_rows > 0) {
      $adPlcOutput = $sqlPlcAd->fetch_assoc()['percentage'];
    }
    $sqlHostLang = $link->query("SELECT percentage FROM ratingsectionsummary WHERE beid='$hostBeID' and section='lang'");
    if ($sqlHostLang->num_rows > 0) {
      $langHstOutput = $sqlHostLang->fetch_assoc()['percentage'];
    }
    $sqlHostComm = $link->query("SELECT percentage FROM ratingsectionsummary WHERE beid='$hostBeID' and section='comm'");
    if ($sqlHostComm->num_rows > 0) {
      $commHstOutput = $sqlHostComm->fetch_assoc()['percentage'];
    }
    $sqlHostPrsn = $link->query("SELECT percentage FROM ratingsectionsummary WHERE beid='$hostBeID' and section='prsn'");
    if ($sqlHostPrsn->num_rows > 0) {
      $prsnHstOutput = $sqlHostPrsn->fetch_assoc()['percentage'];
    }
    $sqlPlcComments = $link->query("SELECT comment, commentbeid FROM comments WHERE critic='$usrBeId' and beid='$plcBeId' ORDER BY fulldate ASC");
    if ($sqlPlcComments->num_rows > 0) {
      while($rowPlcComm = $sqlPlcComments->fetch_assoc()) {
        array_push($output, [
          "type" => "comment",
          "id" => getFrontendId($rowPlcComm['commentbeid']),
          "sts" => "place",
          "img" => $midImg,
          "comment" => $rowPlcComm['comment']
        ]);
      }
    }
    $sqlHostComments = $link->query("SELECT comment, commentbeid FROM comments WHERE critic='$usrBeId' and beid='$hostBeID' ORDER BY fulldate ASC");
    if ($sqlHostComments->num_rows > 0) {
      while($rowHostComm = $sqlHostComments->fetch_assoc()) {
        array_push($output, [
          "type" => "comment",
          "id" => getFrontendId($rowHostComm['commentbeid']),
          "sts" => "user",
          "img" =>$midImg,
          "comment" => $rowHostComm['comment']
        ]);
      }
    }
    array_push($output, [
      "type" => "done",
      "criticsSummaryPlace" => $criticsSummaryPlace,
      "plcLct" => $lctPlcOutput,
      "plcTidy" => $tidyPlcOutput,
      "plcPrc" => $prcPlcOutput,
      "plcPark" => $parkPlcOutput,
      "plcAd" => $adPlcOutput,
      "criticsSummaryHost" => $criticsSummaryHost,
      "hstLang" => $langHstOutput,
      "hstComm" => $commHstOutput,
      "hstPrsn" => $prsnHstOutput
    ]);
    returnOutput();
  }
?>
