<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/rating-summary.php";
  include "link-data-render.php";
  $userId = $link->escape_string($_POST['user']);
  $output = [];
  $allCottBeIds = [];
  $finalArrBeIds = [];
  $max = 6;
  $sqlUserBeId = $link->query("SELECT beid FROM idlist WHERE id='$userId'");
  if ($sqlUserBeId->num_rows > 0) {
    $usrBeId = $sqlUserBeId->fetch_assoc()['beid'];
    $sqlCottChck = $link->query("SELECT beid FROM places WHERE usrbeid='$usrBeId' and status='active' ORDER BY fullDate DESC");
    if ($sqlCottChck->num_rows > 0) {
      while($c = $sqlCottChck->fetch_assoc()) {
        ratingSummary($c['beid']);
        array_push($allCottBeIds, $c['beid']);
      }
      $allCottBeIds_string = join("','", $allCottBeIds);
      $sqlRatingCott = $link->query("SELECT beid FROM ratingsummary WHERE beid IN ('$allCottBeIds_string') ORDER BY percentage DESC LIMIT $max");
      if ($sqlRatingCott->num_rows > 0) {
        while($r = $sqlRatingCott->fetch_assoc()) {
          array_push($finalArrBeIds, $r['beid']);
          array_splice($allCottBeIds, array_search($r['beid'], $allCottBeIds), 1);
        }
      }
      $selectedByRatingNum = sizeof($finalArrBeIds);
      $max = $max - $selectedByRatingNum;
      if ($max > sizeof($allCottBeIds)) {
        $max = sizeof($allCottBeIds);
      }
      if ($max > 0) {
        for ($a = 0; $a < $max; $a++) {
          array_push($finalArrBeIds, $allCottBeIds[$a]);
        }
      }
      for ($i = 0; $i < sizeof($finalArrBeIds); $i++) {
        if ($selectedByRatingNum > 0 && $selectedByRatingNum > $i) {
          $class = "link-img-blck-rating";
        } else {
          $class = "link-img-blck-date";
        }
        linkDataRender($finalArrBeIds[$i], $i, sizeof($finalArrBeIds), $class);
      }
    } else {
      error("no-cottages");
    }
  } else {
    error("no-user");
  }

  function pushLinkToArray($sts, $class, $id, $name, $src, $priceMode, $priceWeek, $priceWork, $currency, $rating) {
    global $output;
    array_push($output, [
      "type" => "link",
      "class" => $class,
      "id" => $id,
      "name" => $name,
      "src" => $src,
      "priceMode" => $priceMode,
      "priceWeek" => $priceWeek,
      "priceWork" => $priceWork,
      "currency" => $currency,
      "rating" => $rating
    ]);
    if ($sts == 1) {
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
