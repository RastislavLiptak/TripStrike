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
  $comm_id = mysqli_real_escape_string($link, $_POST['id']);
  $ratingsReady = ratingVerify($url_plc_id, $url_booking, $url_fromd, $url_fromm, $url_fromy, $url_tod, $url_tom, $url_toy);
  if ($ratingsReady == "good") {
    $sqlBeId = $link->query("SELECT beid FROM idlist WHERE id='$url_plc_id'");
    $plcBeId = $sqlBeId->fetch_assoc()['beid'];
    $sqlCommentBeId = $link->query("SELECT beid FROM idlist WHERE id='$comm_id'");
    if ($sqlCommentBeId->num_rows > 0) {
      $comm_beId = $sqlCommentBeId->fetch_assoc()['beid'];
      $sqlCommentAuthor = $link->query("SELECT critic FROM comments WHERE commentbeid='$comm_beId'");
      if ($sqlCommentAuthor->num_rows > 0) {
        if ($sqlCommentAuthor->fetch_assoc()['critic'] == $usrBeId) {
          $sqlUpdt = "UPDATE comments SET critic='-', beid='-', comment='', fulldate='$date', datey='$dateY', datem='$dateM', dated='$dateD' WHERE commentbeid='$comm_beId'";
          if (mysqli_query($link, $sqlUpdt)) {
            ratingsOutput($plcBeId);
          } else {
            error("Failed to delete your comment from our database<br>".mysqli_error($link));
          }
        } else {
          error("Comment does not belong to you");
        }
      } else {
        error("Comment with this is not in our database");
      }
    } else {
      error("ID not belongs to any comment");
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
