<?php
  include "../data.php";
  include "../get-frontend-id.php";
  include "../../../dictionary/lang-select.php";
  include "../get-user.php";
  $output = [];
  if ($sign == "yes") {
    if ($bnft_add_cottage == "good") {
      $plcId = mysqli_real_escape_string($link, $_POST['plcID']);
      $sqlBeId = $link->query("SELECT beid FROM idlist WHERE id='$plcId'");
      if ($sqlBeId->num_rows > 0) {
        $plcBeId = $sqlBeId->fetch_assoc()['beid'];
        $sqlPlace = $link->query("SELECT * FROM places WHERE beid='$plcBeId' and usrbeid='$usrBeId'");
        if ($sqlPlace->num_rows > 0) {
          if ($sqlPlace->num_rows == 1) {
            $plc = $sqlPlace->fetch_assoc();
            if ($usrBeId == $plc['usrbeid']) {
              $sqlActiveUpdt = "UPDATE places SET status='active' WHERE beid='$plcBeId'";
              if (mysqli_query($link, $sqlActiveUpdt)) {
                $sqlImgBeID = $link->query("SELECT imgbeid FROM placeimages WHERE cottbeid='$plcBeId' and type='sml' and sts='main' ORDER BY numid DESC LIMIT 1");
                if ($sqlImgBeID->num_rows > 0) {
                  $imgName = $sqlImgBeID->fetch_assoc()["imgbeid"];
                  $sqlImgSrc = $link->query("SELECT src FROM images WHERE name='$imgName' && status='plc-sml'");
                  if ($sqlImgSrc ->num_rows > 0) {
                    $imgSrc = $sqlImgSrc->fetch_assoc()['src'];
                  } else {
                    $imgSrc = "none";
                  }
                } else {
                  $imgSrc = "none";
                }
                done($plc['name'], $plc['description'], $imgSrc, $plc['workDayPrice'], $plc['weekDayPrice']);
              } else {
                error("Place activation failed<br>".mysqli_error($link));
              }
            } else {
              error("Place does not belong to you (place activation)");
            }
          } else {
            error("More than one place with this ID (".$plcId.") found (place activation)");
          }
        } else {
          error("Place with this ID (".$plcId.") not found (place activation)<br>".mysqli_error($link));
        }
      } else {
        error("Failed to get backend ID of the place (place activation)<br>".mysqli_error($link));
      }
    } else {
      if ($bnft_add_cottage == "none") {
        error("Feature is unavailable (place activation)");
      } else if ($bnft_add_cottage == "ban") {
        error("Feature is banned (place activation)");
      } else {
        error("Failed to get availability status of this feature(place activation)");
      }
    }
  } else {
    error("You are not signed in (place activation)");
  }

  function error($msg) {
    global $output;
    array_push($output, [
      "type" => "error",
      "error" => $msg
    ]);
    returnOutput();
  }

  function done($name, $desc, $img, $priceWork, $priceWeek) {
    global $output;
    array_push($output, [
      "type" => "done",
      "name" => $name,
      "desc" => $desc,
      "img" => $img,
      "priceWork" => $priceWork,
      "priceWeek" => $priceWeek
    ]);
    returnOutput();
  }

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
