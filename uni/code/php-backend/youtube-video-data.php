<?php
  include "data.php";
  include "youtube-about-video.php";
  $output = [];
  $vidID = $_POST['video'];
  if ($vidID != "") {
    if (youtubeAboutVideo($vidID, "sts") == "good") {
        $title = youtubeAboutVideo($vidID, "title");
        $thumbnail_default = youtubeAboutVideo($vidID, "thumb-default");
        $thumbnail_medium = youtubeAboutVideo($vidID, "thumb-medium");
        done($vidID, $title, $thumbnail_default, $thumbnail_medium);
    } else {
      error($vidID, "Failed to load video from Youtube");
    }
  } else {
    error($vidID, "ID of the video is undefined");
  }


  function done($vidID, $title, $thumbnail_default, $thumbnail_medium) {
    global $output;
    array_push($output, [
      "type" => "data",
      "id" => $vidID,
      "title" => $title,
      "thumbnail-default" => $thumbnail_default,
      "thumbnail-medium" => $thumbnail_medium
    ]);
    returnOutput();
  }

  function error($vidID, $msg) {
    global $output;
    array_push($output, [
      "type" => "error",
      "id" => $vidID,
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
