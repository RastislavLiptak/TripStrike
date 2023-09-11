<?php
  include "../uni/code/php-head.php";
  include "php-backend/get-list-of-features-codes.php";
  if (!isset($_GET['section'])) {
    header("Location: ../features-codes/?section=features-codes");
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <?php
      include "../uni/code/php-frontend/head.php";
    ?>
    <script src="js/features-codes.js"></script>
    <script src="js/add-code.js"></script>
    <script src="../../uni/code/js/uni.js"></script>
    <script src="../../uni/code/js/modals.js"></script>
    <link rel="stylesheet" type="text/css" href="../../uni/code/css/modals.css" preload>
    <link rel="stylesheet" type="text/css" href="css/features-codes.css">
    <link rel="stylesheet" type="text/css" href="css/add-code.css">
    <link rel="stylesheet" type="text/css" href="../../uni/code/css/table.css">
    <title><?php echo $wrd_featuresCodes." - ".$title." Back Door"; ?></title>
  </head>
  <body onload="
    addFeatureCodeDictionary(
      '<?php echo $wrd_newFeature; ?>'
    );
  ">
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
      include "php-frontend/add-code.php";
    ?>
    <div id="page-layout">
      <?php
        include "../uni/code/php-frontend/main-menu.php";
      ?>
      <div id="content-wrp">
        <div id="content">
          <div id="content-title-wrp">
            <h1 id="content-title"><?php echo $wrd_featuresCodes; ?></h1>
          </div>
          <div class="table-layout">
            <div class="table-filter-wrp" id="table-filter-wrp-features-codes">
              <div class="table-filter-search-bar-wrp">
                <button type="button" class="table-filter-search-bar-input-btn table-filter-search-bar-input-btn-search" onclick="featuresCodesSearchBtn();"></button>
                <input type="text" class="table-filter-search-bar-input" id="table-filter-search-bar-input-features-codes" placeholder="<?php echo $wrd_searchForCodeByCodeFeatureOrUser; ?>" value="" onkeyup="if(!(event.keyCode>36&&event.keyCode<41) && event.keyCode!=16){featuresCodesSearchType(this);}">
                <button type="button" class="table-filter-search-bar-input-btn table-filter-search-bar-input-btn-cancel" onclick="featuresCodesSearchCancel();"></button>
              </div>
              <div class="table-filter-tools-wrp">
                <div class="table-filter-select-wrp">
                  <select class="table-filter-select" id="b-d-features-codes-table-filter-availability" onchange="featuresCodesAvailabilityFilterOnchange();">
                    <option value="" selected><?php echo $wrd_availability; ?></option>
                    <option value="available"><?php echo $wrd_available; ?></option>
                    <option value="used"><?php echo $wrd_used; ?></option>
                  </select>
                </div>
                <div class="table-filter-table-wrp">
                  <button class="btn btn-big btn-sec" onclick="addFeatureCodeModal('show');"><?php echo $wrd_addCode; ?></button>
                </div>
              </div>
            </div>
            <?php
              $listOfFeaturesCodes = getListOfFeaturesCodes("list", "", "", "", "");
              $loadedListData = getListOfFeaturesCodes("load-amount", "", "", "", "");
            ?>
            <div class="table-wrp" id="b-d-features-codes-table-wrp">
              <table class="table" id="b-d-features-codes-table">
                <tr>
                  <th><?php echo $wrd_status; ?></th>
                  <th><?php echo $wrd_codeHash; ?></th>
                  <th><?php echo $wrd_feature; ?></th>
                  <th><?php echo $wrd_user; ?></th>
                  <th><?php echo $wrd_numberOfConnectedFeatures; ?></th>
                </tr>
                <?php
                  if (sizeof($listOfFeaturesCodes) > 0) {
                    for ($lOFC=0; $lOFC < sizeof($listOfFeaturesCodes); $lOFC++) {
                      if ($listOfFeaturesCodes[$lOFC]['status'] == "") {
                        $rowStatus = "<i>".$wrd_unknown." (empty)</i>";
                      } else if ($listOfFeaturesCodes[$lOFC]['status'] == "-") {
                        $rowStatus = "<i>".$wrd_unknown." (-)</i>";
                      } else if ($listOfFeaturesCodes[$lOFC]['status'] == "active") {
                        $rowStatus = "<i>".$wrd_used."</i>";
                      } else if ($listOfFeaturesCodes[$lOFC]['status'] == "inactive") {
                        $rowStatus = $wrd_available;
                      }
                      if ($listOfFeaturesCodes[$lOFC]['userStatus'] == "not-connected") {
                        if ($listOfFeaturesCodes[$lOFC]['status'] == "inactive") {
                          $rowUser = "-";
                        } else {
                          $rowUser = "<i>".$wrd_notConnectedToAnyUser."</i>";
                        }
                      } else if ($listOfFeaturesCodes[$lOFC]['userStatus'] == "not-found") {
                        $rowUser = "<i>".$wrd_userNotFound."</i>";
                      } else if ($listOfFeaturesCodes[$lOFC]['userStatus'] == "active") {
                        $rowUser = "<a href='../user/?section=users&nav=about&id=".$listOfFeaturesCodes[$lOFC]['userID']."&m=&y=' target='_blank'>".$listOfFeaturesCodes[$lOFC]['username']."</a>";
                      } else {
                        $rowUser = "<i>".$listOfFeaturesCodes[$lOFC]['userStatus']."</i>";
                      }
                ?>
                      <tr class="b-d-features-codes-table-row">
                        <td><?php echo $rowStatus; ?></td>
                        <td><?php echo $listOfFeaturesCodes[$lOFC]['code']; ?></td>
                        <td><?php echo $listOfFeaturesCodes[$lOFC]['feature']; ?></td>
                        <td><?php echo $rowUser; ?></td>
                        <td><?php echo $listOfFeaturesCodes[$lOFC]['numOfFeatures']; ?></td>
                      </tr>
                <?php
                    }
                  }
                ?>
              </table>
            </div>
            <div class="table-about-wrp">
              <?php
                if (sizeof($listOfFeaturesCodes) > 0) {
                  $lastCode = $listOfFeaturesCodes[sizeof($listOfFeaturesCodes) -1]['code'];
                  $lastFeature = $listOfFeaturesCodes[sizeof($listOfFeaturesCodes) -1]['feature'];
                } else {
                  $lastCode = "";
                  $lastFeature = "";
                }
              ?>
              <p id="b-d-features-codes-table-about-last-code"><?php echo $lastCode; ?></p>
              <p id="b-d-features-codes-table-about-last-feature"><?php echo $lastFeature; ?></p>
            </div>
            <div class="table-tools-wrp">
              <div class="table-tools-errors-wrp" id="b-d-features-codes-table-tools-errors-wrp">
                <div class="table-tools-errors-txt-size">
                  <p class="table-tools-errors-txt" id="b-d-features-codes-table-tools-errors-txt"></p>
                </div>
              </div>
              <div class="table-tools-loader-wrp" id="b-d-features-codes-table-tools-loader-wrp">
                <img src="../../uni/gifs/loader-tail.svg" alt="loading gif" class="table-tools-loader-img">
              </div>
              <?php
                if ($loadedListData["remain"] > 0) {
                  $loadMoreStyle = "display: flex;";
                } else {
                  $loadMoreStyle = "";
                }
              ?>
              <div class="table-tools-load-more-wrp" id="b-d-features-codes-table-tools-load-more-wrp" style="<?php echo $loadMoreStyle; ?>">
                <button class="btn btn-mid btn-prim table-tools-load-more-btn" id="b-d-features-codes-table-tools-load-more-btn" onclick="featuresCodesLoadMore();"><?php echo $wrd_loadMore; ?></button>
              </div>
              <?php
                if ($loadedListData["all-codes"] > 0) {
                  $noContentStyle = "";
                } else {
                  $noContentStyle = "display: flex;";
                }
              ?>
              <div class="table-tools-no-content-wrp" id="b-d-features-codes-table-tools-no-content-wrp" style="<?php echo $noContentStyle; ?>">
                <p class="table-tools-no-content-txt"><?php echo $wrd_noContent; ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
