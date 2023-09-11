<?php
  include "../../../uni/code/php-backend/data.php";
  include "../../../uni/code/php-backend/get-frontend-id.php";
  include "../../../uni/dictionary/lang-select.php";
  include "../../../uni/code/php-backend/get-user.php";
  include "../../../uni/code/php-backend/rating-summary.php";
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
  $progress = mysqli_real_escape_string($link, $_POST['progress']);
  $ratingsReady = ratingVerify($url_plc_id, $url_booking, $url_fromd, $url_fromm, $url_fromy, $url_tod, $url_tom, $url_toy);
  if ($ratingsReady == "good") {
    if (is_numeric($progress)) {
      if ($progress >= 0 && $progress <= 100) {
        $sqlBeId = $link->query("SELECT beid FROM idlist WHERE id='$url_plc_id'");
        $plcBeId = $sqlBeId->fetch_assoc()['beid'];
        $sqlRatingLctCheck = $link->query("SELECT * FROM rating WHERE critic='$usrBeId' and beid='$plcBeId' and section='lct'");
        if ($sqlRatingLctCheck->num_rows > 0) {
          $sqlRatingLctUpdate = "UPDATE rating SET percentage='$progress', fulldate='$date', datey='$dateY', datem='$dateM', dated='$dateD' WHERE critic='$usrBeId' and beid='$plcBeId' and section='lct'";
          mysqli_query($link, $sqlRatingLctUpdate);
        } else {
          $sqlRatingLctInstert = "INSERT INTO rating (critic, beid, percentage, section, fulldate, datey, datem, dated) VALUES ('$usrBeId', '$plcBeId', '$progress', 'lct', '$date', '$dateY', '$dateM', '$dateD')";
          mysqli_query($link, $sqlRatingLctInstert);
        }
        $sqlRatingTidyCheck = $link->query("SELECT * FROM rating WHERE critic='$usrBeId' and beid='$plcBeId' and section='tidy'");
        if ($sqlRatingTidyCheck->num_rows > 0) {
          $sqlRatingTidyUpdate = "UPDATE rating SET percentage='$progress', fulldate='$date', datey='$dateY', datem='$dateM', dated='$dateD' WHERE critic='$usrBeId' and beid='$plcBeId' and section='tidy'";
          mysqli_query($link, $sqlRatingTidyUpdate);
        } else {
          $sqlRatingTidyInstert = "INSERT INTO rating (critic, beid, percentage, section, fulldate, datey, datem, dated) VALUES ('$usrBeId', '$plcBeId', '$progress', 'tidy', '$date', '$dateY', '$dateM', '$dateD')";
          mysqli_query($link, $sqlRatingTidyInstert);
        }
        $sqlRatingPrcCheck = $link->query("SELECT * FROM rating WHERE critic='$usrBeId' and beid='$plcBeId' and section='prc'");
        if ($sqlRatingPrcCheck->num_rows > 0) {
          $sqlRatingPrcUpdate = "UPDATE rating SET percentage='$progress', fulldate='$date', datey='$dateY', datem='$dateM', dated='$dateD' WHERE critic='$usrBeId' and beid='$plcBeId' and section='prc'";
          mysqli_query($link, $sqlRatingPrcUpdate);
        } else {
          $sqlRatingPrcInstert = "INSERT INTO rating (critic, beid, percentage, section, fulldate, datey, datem, dated) VALUES ('$usrBeId', '$plcBeId', '$progress', 'prc', '$date', '$dateY', '$dateM', '$dateD')";
          mysqli_query($link, $sqlRatingPrcInstert);
        }
        $sqlRatingParkCheck = $link->query("SELECT * FROM rating WHERE critic='$usrBeId' and beid='$plcBeId' and section='park'");
        if ($sqlRatingParkCheck->num_rows > 0) {
          $sqlRatingParkUpdate = "UPDATE rating SET percentage='$progress', fulldate='$date', datey='$dateY', datem='$dateM', dated='$dateD' WHERE critic='$usrBeId' and beid='$plcBeId' and section='park'";
          mysqli_query($link, $sqlRatingParkUpdate);
        } else {
          $sqlRatingParkInstert = "INSERT INTO rating (critic, beid, percentage, section, fulldate, datey, datem, dated) VALUES ('$usrBeId', '$plcBeId', '$progress', 'park', '$date', '$dateY', '$dateM', '$dateD')";
          mysqli_query($link, $sqlRatingParkInstert);
        }
        $sqlRatingAdCheck = $link->query("SELECT * FROM rating WHERE critic='$usrBeId' and beid='$plcBeId' and section='ad'");
        if ($sqlRatingAdCheck->num_rows > 0) {
          $sqlRatingAdUpdate = "UPDATE rating SET percentage='$progress', fulldate='$date', datey='$dateY', datem='$dateM', dated='$dateD' WHERE critic='$usrBeId' and beid='$plcBeId' and section='ad'";
          mysqli_query($link, $sqlRatingAdUpdate);
        } else {
          $sqlRatingAdInstert = "INSERT INTO rating (critic, beid, percentage, section, fulldate, datey, datem, dated) VALUES ('$usrBeId', '$plcBeId', '$progress', 'ad', '$date', '$dateY', '$dateM', '$dateD')";
          mysqli_query($link, $sqlRatingAdInstert);
        }
        ratingSummary($plcBeId);
        ratingCriticSummary($plcBeId);
        ratingsOutput($plcBeId);
      } else {
        error("Rating is out of range");
      }
    } else {
      error("Rating is not a numeric value");
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
