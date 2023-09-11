<?php
  include "../data.php";
  include "../account-data-check.php";
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
        $activateFeature = mysqli_real_escape_string($link, $_POST['activateFeature']);
        if (check($activateFeature, "empty")) {
          $sqlValidationInactive = $linkBD->query("SELECT * FROM featuresvalidation WHERE id='$activateFeature' and sts='inactive'");
          $sqlValidation = $linkBD->query("SELECT beid, name, sts FROM featuresvalidation WHERE id='$activateFeature'");
          if ($sqlValidation->num_rows > 0) {
            if ($sqlValidation->num_rows == $sqlValidationInactive->num_rows) {
              $featureError = "";
              $featureErrorNum = 0;
              while($vld = $sqlValidation->fetch_assoc()) {
                $featureBeID = $vld['beid'];
                $featureName = $vld['name'];
                $sqlFeatureStsUpdate = "UPDATE featuresvalidation SET sts='active' WHERE beid='$featureBeID'";
                if (mysqli_query($linkBD, $sqlFeatureStsUpdate)) {
                  $sqlFeatureInUse = "INSERT INTO featuresinuse (beid, usrbeid) VALUES('$featureBeID', '$beId')";
                  if (!mysqli_query($linkBD, $sqlFeatureInUse)) {
                    ++$featureErrorNum;
                    if ($featureError == "") {
                      $featureError = "Failed to connect feature '".$featureName."' to your account (error: ".mysqli_error($link).")";
                    } else {
                      $featureError = $featureError."<br>Failed to connect feature '".$featureName."' to your account (error: ".mysqli_error($link).")";
                    }
                  }
                } else {
                  ++$featureErrorNum;
                  if ($featureError == "") {
                    $featureError = "Failed to update status for '".$featureName."' feature (error: ".mysqli_error($link).")";
                  } else {
                    $featureError = $featureError."<br>Failed to update status for '".$featureName."' feature (error: ".mysqli_error($link).")";
                  }
                }
              }
              if ($featureErrorNum == $sqlValidation->num_rows) {
                error($featureError);
              } else {
                done($featureError);
              }
            } else {
              if ($sqlValidationInactive->num_rows == 0) {
                error("Feature is taken");
              } else {
                error("Feature is partly taken");
              }
            }
          } else {
            error("Feature with this ID does not exist");
          }
        } else {
          error("Activate features field is empty");
        }
      } else {
        error("action denied - sign in data not matching with data in database");
      }
    } else {
      error("data from session not maching with data from database");
    }
  } else {
    error("session error - missing data");
  }

  function error($msg) {
    global $output;
    array_push($output, [
      "type" => "error",
      "error" => $msg
    ]);
    returnOutput();
  }

  function done($errorMsg) {
    global $output;
    array_push($output, [
      "type" => "done",
      "error" => $errorMsg
    ]);
    returnOutput();
  }

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
