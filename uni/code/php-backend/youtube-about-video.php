<?php
  function youtubeAboutVideo($vidID, $get) {
    global $youtubeAPIKey;
    $videoList = json_decode(file_get_contents("https://www.googleapis.com/youtube/v3/videos?part=snippet&id={$vidID}&key={$youtubeAPIKey}"));
    if ($videoList->pageInfo->totalResults > 0) {
      if ($get == "sts") {
        return "good";
      } else if ($get == "title") {
        return $videoList->items[0]->snippet->title;
      } else if ($get == "thumb-default") {
        return $videoList->items[0]->snippet->thumbnails->default->url;
      } else if ($get == "thumb-medium") {
        return $videoList->items[0]->snippet->thumbnails->medium->url;
      } else if ($get == "thumb-high") {
        return $videoList->items[0]->snippet->thumbnails->high->url;
      }
    } else {
      if ($get == "sts") {
        return "bad";
      } else {
        return "";
      }
    }
  }
?>
