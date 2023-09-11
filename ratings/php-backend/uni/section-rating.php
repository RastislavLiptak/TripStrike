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
  $part = mysqli_real_escape_string($link, $_POST['part']);
  $section = mysqli_real_escape_string($link, $_POST['section']);
  $progress = mysqli_real_escape_string($link, $_POST['progress']);
  $ratingsReady = ratingVerify($url_plc_id, $url_booking, $url_fromd, $url_fromm, $url_fromy, $url_tod, $url_tom, $url_toy);
  if ($ratingsReady == "good") {
    if (is_numeric($progress)) {
      if ($progress >= 0 && $progress <= 100) {
        $sqlBeId = $link->query("SELECT beid FROM idlist WHERE id='$url_plc_id'");
        $plcBeId = $sqlBeId->fetch_assoc()['beid'];
        $sqlPlaceHost = $link->query("SELECT usrbeid FROM places WHERE beid='$plcBeId'");
        $hostBeID = $sqlPlaceHost->fetch_assoc()['usrbeid'];
        if ($part == "places") {
          if ($section == "location") {
            $sqlRatingPlaceLctCheck = $link->query("SELECT * FROM rating WHERE critic='$usrBeId' and beid='$plcBeId' and section='lct'");
            if ($sqlRatingPlaceLctCheck->num_rows > 0) {
              $sqlRatingPlaceLctUpdate = "UPDATE rating SET percentage='$progress', fulldate='$date', datey='$dateY', datem='$dateM', dated='$dateD' WHERE critic='$usrBeId' and beid='$plcBeId' and section='lct'";
              mysqli_query($link, $sqlRatingPlaceLctUpdate);
            } else {
              $sqlRatingPlaceLctInstert = "INSERT INTO rating (critic, beid, percentage, section, fulldate, datey, datem, dated) VALUES ('$usrBeId', '$plcBeId', '$progress', 'lct', '$date', '$dateY', '$dateM', '$dateD')";
              mysqli_query($link, $sqlRatingPlaceLctInstert);
            }
          } else if ($section == "tidines") {
            $sqlRatingPlaceTidyCheck = $link->query("SELECT * FROM rating WHERE critic='$usrBeId' and beid='$plcBeId' and section='tidy'");
            if ($sqlRatingPlaceTidyCheck->num_rows > 0) {
              $sqlRatingPlaceTidyUpdate = "UPDATE rating SET percentage='$progress', fulldate='$date', datey='$dateY', datem='$dateM', dated='$dateD' WHERE critic='$usrBeId' and beid='$plcBeId' and section='tidy'";
              mysqli_query($link, $sqlRatingPlaceTidyUpdate);
            } else {
              $sqlRatingPlaceTidyInstert = "INSERT INTO rating (critic, beid, percentage, section, fulldate, datey, datem, dated) VALUES ('$usrBeId', '$plcBeId', '$progress', 'tidy', '$date', '$dateY', '$dateM', '$dateD')";
              mysqli_query($link, $sqlRatingPlaceTidyInstert);
            }
          } else if ($section == "price") {
            $sqlRatingPlacePrcCheck = $link->query("SELECT * FROM rating WHERE critic='$usrBeId' and beid='$plcBeId' and section='prc'");
            if ($sqlRatingPlacePrcCheck->num_rows > 0) {
              $sqlRatingPlacePrcUpdate = "UPDATE rating SET percentage='$progress', fulldate='$date', datey='$dateY', datem='$dateM', dated='$dateD' WHERE critic='$usrBeId' and beid='$plcBeId' and section='prc'";
              mysqli_query($link, $sqlRatingPlacePrcUpdate);
            } else {
              $sqlRatingPlacePrcInstert = "INSERT INTO rating (critic, beid, percentage, section, fulldate, datey, datem, dated) VALUES ('$usrBeId', '$plcBeId', '$progress', 'prc', '$date', '$dateY', '$dateM', '$dateD')";
              mysqli_query($link, $sqlRatingPlacePrcInstert);
            }
          } else if ($section == "parking") {
            $sqlRatingPlaceParkCheck = $link->query("SELECT * FROM rating WHERE critic='$usrBeId' and beid='$plcBeId' and section='park'");
            if ($sqlRatingPlaceParkCheck->num_rows > 0) {
              $sqlRatingPlaceParkUpdate = "UPDATE rating SET percentage='$progress', fulldate='$date', datey='$dateY', datem='$dateM', dated='$dateD' WHERE critic='$usrBeId' and beid='$plcBeId' and section='park'";
              mysqli_query($link, $sqlRatingPlaceParkUpdate);
            } else {
              $sqlRatingPlaceParkInstert = "INSERT INTO rating (critic, beid, percentage, section, fulldate, datey, datem, dated) VALUES ('$usrBeId', '$plcBeId', '$progress', 'park', '$date', '$dateY', '$dateM', '$dateD')";
              mysqli_query($link, $sqlRatingPlaceParkInstert);
            }
          } else if ($section == "ad") {
            $sqlRatingPlaceAdCheck = $link->query("SELECT * FROM rating WHERE critic='$usrBeId' and beid='$plcBeId' and section='ad'");
            if ($sqlRatingPlaceAdCheck->num_rows > 0) {
              $sqlRatingPlaceAdUpdate = "UPDATE rating SET percentage='$progress', fulldate='$date', datey='$dateY', datem='$dateM', dated='$dateD' WHERE critic='$usrBeId' and beid='$plcBeId' and section='ad'";
              mysqli_query($link, $sqlRatingPlaceAdUpdate);
            } else {
              $sqlRatingPlaceAdInstert = "INSERT INTO rating (critic, beid, percentage, section, fulldate, datey, datem, dated) VALUES ('$usrBeId', '$plcBeId', '$progress', 'ad', '$date', '$dateY', '$dateM', '$dateD')";
              mysqli_query($link, $sqlRatingPlaceAdInstert);
            }
          }
          ratingSummary($plcBeId);
          ratingCriticSummary($plcBeId);
        } else if ($part == "users") {
          if ($section == "language") {
            $sqlRatingUserLangCheck = $link->query("SELECT * FROM rating WHERE critic='$usrBeId' and beid='$hostBeID' and section='lang'");
            if ($sqlRatingUserLangCheck->num_rows > 0) {
              $sqlRatingUserLangUpdate = "UPDATE rating SET percentage='$progress', fulldate='$date', datey='$dateY', datem='$dateM', dated='$dateD' WHERE critic='$usrBeId' and beid='$hostBeID' and section='lang'";
              mysqli_query($link, $sqlRatingUserLangUpdate);
            } else {
              $sqlRatingUserLangInstert = "INSERT INTO rating (critic, beid, percentage, section, fulldate, datey, datem, dated) VALUES ('$usrBeId', '$hostBeID', '$progress', 'lang', '$date', '$dateY', '$dateM', '$dateD')";
              mysqli_query($link, $sqlRatingUserLangInstert);
            }
          } else if ($section == "communication") {
            $sqlRatingUserCommCheck = $link->query("SELECT * FROM rating WHERE critic='$usrBeId' and beid='$hostBeID' and section='comm'");
            if ($sqlRatingUserCommCheck->num_rows > 0) {
              $sqlRatingUserCommUpdate = "UPDATE rating SET percentage='$progress', fulldate='$date', datey='$dateY', datem='$dateM', dated='$dateD' WHERE critic='$usrBeId' and beid='$hostBeID' and section='comm'";
              mysqli_query($link, $sqlRatingUserCommUpdate);
            } else {
              $sqlRatingUserCommInstert = "INSERT INTO rating (critic, beid, percentage, section, fulldate, datey, datem, dated) VALUES ('$usrBeId', '$hostBeID', '$progress', 'comm', '$date', '$dateY', '$dateM', '$dateD')";
              mysqli_query($link, $sqlRatingUserCommInstert);
            }
          } else if ($section == "personality") {
            $sqlRatingUserPrsnCheck = $link->query("SELECT * FROM rating WHERE critic='$usrBeId' and beid='$hostBeID' and section='prsn'");
            if ($sqlRatingUserPrsnCheck->num_rows > 0) {
              $sqlRatingUserPrsnUpdate = "UPDATE rating SET percentage='$progress', fulldate='$date', datey='$dateY', datem='$dateM', dated='$dateD' WHERE critic='$usrBeId' and beid='$hostBeID' and section='prsn'";
              mysqli_query($link, $sqlRatingUserPrsnUpdate);
            } else {
              $sqlRatingUserPrsnInstert = "INSERT INTO rating (critic, beid, percentage, section, fulldate, datey, datem, dated) VALUES ('$usrBeId', '$hostBeID', '$progress', 'prsn', '$date', '$dateY', '$dateM', '$dateD')";
              mysqli_query($link, $sqlRatingUserPrsnInstert);
            }
          }
          ratingSummary($hostBeID);
          ratingCriticSummary($hostBeID);
        }
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
