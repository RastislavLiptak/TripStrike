<?php
  include "../uni/code/php-head.php";
  include "php-backend/get-list-of-users.php";
  if (!isset($_GET['section'])) {
    header("Location: ../dashboard/?section=users");
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <?php
      include "../uni/code/php-frontend/head.php";
    ?>
    <script src="js/accounts.js"></script>
    <link rel="stylesheet" type="text/css" href="css/accounts.css">
    <link rel="stylesheet" type="text/css" href="../../uni/code/css/table.css">
    <title><?php echo $wrd_users." - ".$title." Back Door"; ?></title>
  </head>
  <body>
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
    ?>
    <div id="page-layout">
      <?php
        include "../uni/code/php-frontend/main-menu.php";
      ?>
      <div id="content-wrp">
        <div id="content">
          <div id="content-title-wrp">
            <h1 id="content-title"><?php echo $wrd_users; ?></h1>
          </div>
          <div class="table-layout">
            <div class="table-filter-wrp" id="table-filter-wrp-users">
              <div class="table-filter-search-bar-wrp">
                <button type="button" class="table-filter-search-bar-input-btn table-filter-search-bar-input-btn-search" onclick="usersSearchBtn();"></button>
                <input type="text" class="table-filter-search-bar-input" id="table-filter-search-bar-input-users" placeholder="<?php echo $wrd_searchForUserByFirstnameAndLastnameIDOrContactEmail; ?>" value="" onkeyup="if(!(event.keyCode>36&&event.keyCode<41) && event.keyCode!=16){usersSearchType(this);}">
                <button type="button" class="table-filter-search-bar-input-btn table-filter-search-bar-input-btn-cancel" onclick="usersSearchCancel();"></button>
              </div>
              <div class="table-filter-tools-wrp">

              </div>
            </div>
            <?php
              $listOfUsers = getListOfUsers("list", "", "");
              $loadedListData = getListOfUsers("load-amount", "", "");
            ?>
            <div class="table-wrp" id="b-d-users-table-wrp">
              <table class="table" id="b-d-users-table">
                <tr>
                  <th><?php echo $wrd_status; ?></th>
                  <th><?php echo "ID"; ?></th>
                  <th><?php echo $wrd_firstName; ?></th>
                  <th><?php echo $wrd_lastName; ?></th>
                  <th><?php echo $wrd_contactEmail; ?></th>
                </tr>
                <?php
                  if (sizeof($listOfUsers) > 0) {
                    for ($lOU=0; $lOU < sizeof($listOfUsers); $lOU++) {
                      if ($listOfUsers[$lOU]['status'] == "") {
                        $rowStatus = "<i>".$wrd_unknown." (empty)</i>";
                      } else if ($listOfUsers[$lOU]['status'] == "-") {
                        $rowStatus = "<i>".$wrd_unknown." (-)</i>";
                      } else if ($listOfUsers[$lOU]['status'] == "delete") {
                        $rowStatus = "<i>".$wrd_deleted."</i>";
                      } else if ($listOfUsers[$lOU]['status'] == "active") {
                        $rowStatus = $wrd_active;
                      }
                ?>
                      <tr class="b-d-users-table-row">
                        <td><?php echo $rowStatus; ?></td>
                        <td><?php echo $listOfUsers[$lOU]['userID']; ?></td>
                        <td><?php echo $listOfUsers[$lOU]['firstname']; ?></td>
                        <td><?php echo $listOfUsers[$lOU]['lastname']; ?></td>
                        <td><?php echo $listOfUsers[$lOU]['contactEmail']; ?></td>
                        <td><a href="../user/?section=users&nav=about&id=<?php echo $listOfUsers[$lOU]['userID']; ?>&m=&y=&paymentreference=" class="table-row-link-blue"><?php echo $wrd_showMore; ?></a></td>
                      </tr>
                <?php
                    }
                  }
                ?>
              </table>
            </div>
            <div class="table-about-wrp">
              <?php
                if (sizeof($listOfUsers) > 0) {
                  $lastID = $listOfUsers[sizeof($listOfUsers) -1]['userID'];
                } else {
                  $lastID = "";
                }
              ?>
              <p id="b-d-users-table-about-last-id"><?php echo $lastID; ?></p>
            </div>
            <div class="table-tools-wrp">
              <div class="table-tools-errors-wrp" id="b-d-users-table-tools-errors-wrp">
                <div class="table-tools-errors-txt-size">
                  <p class="table-tools-errors-txt" id="b-d-users-table-tools-errors-txt"></p>
                </div>
              </div>
              <div class="table-tools-loader-wrp" id="b-d-users-table-tools-loader-wrp">
                <img src="../../uni/gifs/loader-tail.svg" alt="loading gif" class="table-tools-loader-img">
              </div>
              <?php
                if ($loadedListData["remain"] > 0) {
                  $loadMoreStyle = "display: flex;";
                } else {
                  $loadMoreStyle = "";
                }
              ?>
              <div class="table-tools-load-more-wrp" id="b-d-users-table-tools-load-more-wrp" style="<?php echo $loadMoreStyle; ?>">
                <button class="btn btn-mid btn-prim table-tools-load-more-btn" id="b-d-users-table-tools-load-more-btn" onclick="usersLoadMore();"><?php echo $wrd_loadMore; ?></button>
              </div>
              <?php
                if ($loadedListData["all-users"] > 0) {
                  $noContentStyle = "";
                } else {
                  $noContentStyle = "display: flex;";
                }
              ?>
              <div class="table-tools-no-content-wrp" id="b-d-users-table-tools-no-content-wrp" style="<?php echo $noContentStyle; ?>">
                <p class="table-tools-no-content-txt"><?php echo $wrd_noContent; ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
