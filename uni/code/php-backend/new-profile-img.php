<?php
  include "data.php";
  include "img-crop/prof-img-convert.php";
  $output = [];
  if (isset($_SESSION["signID"]) && isset($_SESSION["email"])) {
    $signId = $_SESSION["signID"];
    $em = $_SESSION["email"];
    $sqlUser = $link->query("SELECT * FROM users WHERE email='$em' && status='active'");
    if ($sqlUser->num_rows > 0) {
      $usr = $sqlUser->fetch_assoc();
      $beId = $usr['beid'];
      $sqlLog = $link->query("SELECT * FROM signin WHERE beid='$beId' && signinid='$signId' && status='in'");
      if ($sqlLog->num_rows > 0) {
        $img = $link->escape_string($_POST['name']);
        convertImageManager("small", $img, $beId);
        convertImageManager("mid", $img, $beId);
        convertImageManager("big", $img, $beId);
      } else {
        outputCreate(2, "data-error-3");
      }
    } else {
      outputCreate(2, "data-error-2");
    }
  } else {
    outputCreate(2, "data-error-1");
  }

  function convertImageManager($task, $img, $beId) {
    global $link;
    if ($task == "small") {
      $sts = "prf-small";
    } else if ($task == "mid") {
      $sts = "prf-mid";
    } else if ($task == "big") {
      $sts = "prf-big";
    }
    $sqlImg = $link->query("SELECT * FROM images WHERE beid='$beId' && status='$sts'");
    if ($sqlImg->num_rows > 0) {
      $rowI = $sqlImg->fetch_assoc();
      $srcI = $rowI['src'];
      $nameI = $rowI['name'];
    } else {
      $srcI = "none";
      $nameI = "none";
    }
    if ($srcI != "none") {
      $sqlDelImg = "UPDATE images SET status='delete' WHERE name='$nameI'";
      if (mysqli_query($link, $sqlDelImg)) {
        if (file_exists("../../../".$srcI)) {
          if (unlink("../../../".$srcI)) {
            if ($task == "small") {
              profileImage($beId, 32, "small", $img, "../../../");
            } else if ($task == "mid") {
              profileImage($beId, 64, "mid", $img, "../../../");
            } else if ($task == "big") {
              profileImage($beId, 256, "big", $img, "../../../");
            }
          } else {
            imgDone($task, "delete-error", $beId);
          }
        } else {
          if ($task == "small") {
            profileImage($beId, 32, "small", $img, "../../../");
          } else if ($task == "mid") {
            profileImage($beId, 64, "mid", $img, "../../../");
          } else if ($task == "big") {
            profileImage($beId, 256, "big", $img, "../../../");
          }
        }
      } else {
        imgDone($task, "sql-error", $beId);
      }
    } else {
      if ($task == "small") {
        profileImage($beId, 32, "small", $img, "../../../");
      } else if ($task == "mid") {
        profileImage($beId, 64, "mid", $img, "../../../");
      } else if ($task == "big") {
        profileImage($beId, 256, "big", $img, "../../../");
      }
    }
  }

  $imgDoneNum = 0;
  $imgGoodNum = 0;
  $imgErrorNum = 0;
  $imgErrorStrng = "";
  function imgDone($type, $msg, $beId) {
    global $imgDoneNum, $imgGoodNum, $imgErrorNum, $imgErrorStrng;
    $smlSrc = getImgSrc($beId, "prf-small");
    $midSrc = getImgSrc($beId, "prf-mid");
    $bigSrc = getImgSrc($beId, "prf-big");
    $imgDoneNum++;
    if ($msg == "done") {
      $imgGoodNum++;
    } else {
      $imgErrorNum++;
    }
    if ($imgGoodNum == 3) {
      outputCreate(4, $smlSrc);
      outputCreate(5, $midSrc);
      outputCreate(6, $bigSrc);
      outputCreate(1, "done");
    } else if ($imgErrorNum == 3) {
      $outputString = $outputString." ".$type." => ".$msg.";";
      outputCreate(4, $smlSrc);
      outputCreate(5, $midSrc);
      outputCreate(6, $bigSrc);
      outputCreate(3, $outputString);
      outputCreate(2, "all-img-failed");
    } else {
      if ($imgDoneNum == 3) {
        if ($msg != "done") {
          if ($imgErrorStrng != "") {
            $outputString = $outputString." ".$type." => ".$msg.";";
          } else {
            $outputString = $type." => ".$msg.";";
          }
        }
        outputCreate(4, $smlSrc);
        outputCreate(5, $midSrc);
        outputCreate(6, $bigSrc);
        outputCreate(3, $outputString);
        outputCreate(2, "some-img-failed");
      } else {
        if ($msg != "done") {
          if ($imgErrorStrng != "") {
            $outputString = $outputString." ".$type." => ".$msg.";";
          } else {
            $outputString = $type." => ".$msg.";";
          }
        }
      }
    }
  }

  function getImgSrc($beId, $sts) {
    global $link;
    $sqlB = $link->query("SELECT * FROM images WHERE beid='$beId' && status='$sts'");
    if ($sqlB->num_rows == 1) {
      $bImg = $sqlB->fetch_assoc();
      return $bImg['src'];
    } else {
      return "#";
    }
  }

  function outputCreate($sts, $msg) {
    global $output;
    array_push($output, [
      "sts" => $sts,
      "msg" => $msg
    ]);
    if ($sts == 1 || $sts == 2) {
      $JSON = json_encode($output);
      echo $JSON;
    }
  }
?>
