<?php
  include "../../../uni/code/php-backend/data.php";
  include "../../../uni/code/php-backend/get-frontend-id.php";
  include "../../../uni/dictionary/lang-select.php";
  include "../../../uni/code/php-backend/get-user.php";
  include "../../../uni/code/php-backend/random-hash-maker.php";
  include "../uni/rating-verify.php";
  include "../uni/ratings-output.php";
  $output = [];
  $date = date("Y-m-d H:i:s");
  $dateY = date("Y");
  $dateM = date("m");
  $dateD = date("d");
  $url_booking = mysqli_real_escape_string($link, $_POST['booking']);
  $url_fromd = mysqli_real_escape_string($link, $_POST['fromd']);
  $url_fromm = mysqli_real_escape_string($link, $_POST['fromm']);
  $url_fromy = mysqli_real_escape_string($link, $_POST['fromy']);
  $url_tod = mysqli_real_escape_string($link, $_POST['tod']);
  $url_tom = mysqli_real_escape_string($link, $_POST['tom']);
  $url_toy = mysqli_real_escape_string($link, $_POST['toy']);
  $url_plc_id = mysqli_real_escape_string($link, $_POST['plc']);
  $part = mysqli_real_escape_string($link, $_POST['part']);
  $comment = mysqli_real_escape_string($link, $_POST['comment']);
  $ratingsReady = ratingVerify($url_plc_id, $url_booking, $url_fromd, $url_fromm, $url_fromy, $url_tod, $url_tom, $url_toy);
  if ($ratingsReady == "good") {
    $sqlBeId = $link->query("SELECT beid FROM idlist WHERE id='$url_plc_id'");
    $plcBeId = $sqlBeId->fetch_assoc()['beid'];
    $sqlPlaceHost = $link->query("SELECT usrbeid FROM places WHERE beid='$plcBeId'");
    $hostBeID = $sqlPlaceHost->fetch_assoc()['usrbeid'];
    if (str_replace(' ', '', $comment) != "") {
      $idReady = false;
      while (!$idReady) {
        $commentId = randomHash(11);
        if ($link->query("SELECT * FROM idlist WHERE id='$commentId'")->num_rows == 0) {
          $idReady = true;
        } else {
          $idReady = false;
        }
      }
      $beIdReady = false;
      while (!$beIdReady) {
        $commentBeId = randomHash(64);
        if ($link->query("SELECT * FROM backendidlist WHERE beid='$commentBeId'")->num_rows == 0) {
          $beIdReady = true;
        } else {
          $beIdReady = false;
        }
      }

      $backendIDNum = $link->query("SELECT * FROM backendidlist")->num_rows;
      $sqlCommentBeID = "INSERT INTO backendidlist (beid, numid, type) VALUES('$commentBeId', '$backendIDNum', 'comment')";
      if (mysqli_query($link, $sqlCommentBeID)) {
        $sqlCommentID = "INSERT INTO idlist(beid, id, fullDate, dateD, dateM, dateY) VALUES('$commentBeId', '$commentId', '$date', '$dateD', '$dateM', '$dateY')";
        if (mysqli_query($link, $sqlCommentID)) {
          if ($part == "places") {
            $sqlCommentSave = "INSERT INTO comments(critic, beid, commentbeid, comment, fulldate, datey, datem, dated) VALUES ('$usrBeId', '$plcBeId', '$commentBeId', '$comment', '$date', '$dateY', '$dateM', '$dateD')";
          } else if ($part == "users") {
            $sqlCommentSave = "INSERT INTO comments(critic, beid, commentbeid, comment, fulldate, datey, datem, dated) VALUES ('$usrBeId', '$hostBeID', '$commentBeId', '$comment', '$date', '$dateY', '$dateM', '$dateD')";
          }
          if (mysqli_query($link, $sqlCommentSave)) {
            ratingsOutput($plcBeId);
          } else {
            error("Failed to save data");
          }
        } else {
          error("Failed to save ID");
        }
      } else {
        error("Failed to save backend ID");
      }
    } else {
      ratingsOutput($plcBeId);
    }
  } else {
    error("Verification error: ".$ratingsReady);
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
