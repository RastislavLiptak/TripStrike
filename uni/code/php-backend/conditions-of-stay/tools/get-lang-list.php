<?php
  function getLangList() {
    global $link, $default_langs_arr, $usrBeId;
    $allLangBeIdArr = [];
    foreach ($default_langs_arr as &$lang) {
      getLangListOutput($lang, "uni/icons/langs/".$lang.".svg", "", "", "");
    }
    $sqlAllLangBeId = $link->query("SELECT beid, plcbeid FROM placeconditionskey WHERE usrbeid='$usrBeId'");
    if ($sqlAllLangBeId->num_rows > 0) {
      while($allLangBeId = $sqlAllLangBeId->fetch_assoc()) {
        $plcBeId = $allLangBeId['plcbeid'];
        $sqlCheckPlcSts = $link->query("SELECT * FROM places WHERE beid='$plcBeId' and status='active'");
        if ($sqlCheckPlcSts->num_rows > 0) {
          array_push($allLangBeIdArr, $allLangBeId['beid']);
        }
      }
      $txtContentCondition = "";
      foreach ($default_langs_arr as &$langShrt) {
        $file = "../../../../conditions/texts/conditions_of_stay_of_the_host/".$langShrt."_conditions_of_stay_of_the_host.txt";
        $file_text = fopen($file, "r") or die("Unable to open file!");
        $file_content = str_replace("'", "&apos;", fread($file_text, filesize($file)));
        if ($txtContentCondition == "") {
          $txtContentCondition = "txt='".$file_content."'";
        } else {
          $txtContentCondition = $txtContentCondition." || txt='".$file_content."'";
        }
        fclose($file_text);
      }
      $txtLngIDCondition = "";
      foreach ($allLangBeIdArr as &$txtLangID) {
        if ($txtLngIDCondition == "") {
          $txtLngIDCondition = "beid='".$txtLangID."'";
        } else {
          $txtLngIDCondition = $txtLngIDCondition." || beid='".$txtLangID."'";
        }
      }
      $txtCondition = "(".$txtContentCondition.") && (".$txtLngIDCondition.")";
      $sqlFilter = $link->query("SELECT beid FROM conditionsofstayofthehost WHERE ".$txtCondition);
      if ($sqlFilter->num_rows > 0) {
        while($filter = $sqlFilter->fetch_assoc()) {
          array_splice($allLangBeIdArr, array_search($filter['beid'], $allLangBeIdArr), 1);
        }
      }
      $moreConditions = true;
      while ($moreConditions) {
        if (!empty($allLangBeIdArr)) {
          $sqlGetOneCondition = "";
          foreach ($allLangBeIdArr as &$oneLangID) {
            if ($sqlGetOneCondition == "") {
              $sqlGetOneCondition = "beid='".$oneLangID."'";
            } else {
              $sqlGetOneCondition = $sqlGetOneCondition." || beid='".$oneLangID."'";
            }
          }
          $sqlGetOne = $link->query("SELECT beid, language, txt FROM conditionsofstayofthehost WHERE ".$sqlGetOneCondition." ORDER BY fullDate DESC LIMIT 1");
          if ($sqlGetOne->num_rows > 0) {
            $one = $sqlGetOne->fetch_assoc();
            $oneBeId = $one['beid'];
            $oneLanguage = $one['language'];
            $oneTxt = $one['txt'];
            $oneTxt = str_replace("'", "", $oneTxt);
            $outputLangIcn = "uni/icons/langs/".$oneLanguage.".svg";
            $outputID = getFrontendId($oneBeId);
            $outputImg1 = getPlcCondImgSrc($oneBeId);
            array_splice($allLangBeIdArr, array_search($oneBeId, $allLangBeIdArr), 1);
            $imgNum = 2;
            $outputImg2 = "";
            $outputImg3 = "";
            if (!empty($allLangBeIdArr)) {
              $sqlGetMoreCondition = "";
              foreach ($allLangBeIdArr as &$moreLangID) {
                if ($sqlGetMoreCondition == "") {
                  $sqlGetMoreCondition = "beid='".$moreLangID."'";
                } else {
                  $sqlGetMoreCondition = $sqlGetMoreCondition." || beid='".$moreLangID."'";
                }
              }
              $sqlGetMoreCondition = "(".$sqlGetMoreCondition.") && txt='".$oneTxt."'";
              $sqlGetMore = $link->query("SELECT beid FROM conditionsofstayofthehost WHERE ".$sqlGetMoreCondition." ORDER BY fullDate DESC");
              if ($sqlGetMore->num_rows > 0) {
                while($more = $sqlGetMore->fetch_assoc()) {
                  $moreBeId = $more['beid'];
                  if ($imgNum == 2) {
                    $outputImg2 = getPlcCondImgSrc($moreBeId);
                  } else if ($imgNum == 3) {
                    $outputImg3 = getPlcCondImgSrc($moreBeId);
                  }
                  array_splice($allLangBeIdArr, array_search($moreBeId, $allLangBeIdArr), 1);
                  ++$imgNum;
                }
              }
            }
            getLangListOutput($outputID, $outputImg1, $outputImg2, $outputImg3, $outputLangIcn);
          } else {
            error(0, "Getting list of languages faild (error: ".mysqli_error($link).")");
            $moreConditions = false;
          }
        } else {
          $moreConditions = false;
        }
      }
    }
    getLangListDone();
  }

  function getPlcCondImgSrc($ruleBeId) {
    global $link;
    $sqlPlcBeId = $link->query("SELECT plcbeid FROM placeconditionskey WHERE beid='$ruleBeId' LIMIT 1");
    $plcBeId = $sqlPlcBeId->fetch_assoc()['plcbeid'];
    $sqlImgName = $link->query("SELECT imgbeid FROM placeimages WHERE cottbeid='$plcBeId' and type='mini' and sts='main' LIMIT 1");
    if ($sqlImgName->num_rows > 0) {
      $imgName = $sqlImgName->fetch_assoc()['imgbeid'];
      $sqlImgSrc = $link->query("SELECT src FROM images WHERE name='$imgName' LIMIT 1");
      if ($sqlImgSrc->num_rows > 0) {
        return $sqlImgSrc->fetch_assoc()['src'];
      } else {
        return "#";
      }
    } else {
      return "#";
    }
  }
?>
