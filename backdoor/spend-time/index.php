<?php
  include "../uni/code/php-head.php";
  if (!isset($_GET['section'])) {
    header("Location: ../spend-time/?section=spend-time");
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <?php
      include "../uni/code/php-frontend/head.php";
    ?>
    <script src="js/spend-time.js"></script>
    <script src="../uni/code/js/bar-chart.js"></script>
    <script src="../../uni/code/js/seconds-to-time.js"></script>
    <link rel="stylesheet" type="text/css" href="css/spend-time.css">
    <link rel="stylesheet" type="text/css" href="../uni/code/css/chart-uni.css">
    <link rel="stylesheet" type="text/css" href="../uni/code/css/bar-chart.css">
    <link rel="stylesheet" type="text/css" href="../../uni/code/css/table.css">
    <title><?php echo $wrd_spendTime." - ".$title." Back Door"; ?></title>
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
            <h1 id="content-title"><?php echo $wrd_spendTime; ?></h1>
          </div>
          <div class="bar-chart-block">
            <div class="bar-chart-tools">
              <div class="table-filter-select-wrp">
                <select class="table-filter-select" id="bar-chart-filter-data" onchange="spendTimeLoadData()">
                  <option value="avg" selected=""><?php echo $wrd_averageTime; ?></option>
                  <option value="total"><?php echo $wrd_overallTime; ?></option>
                </select>
              </div>
              <div class="table-filter-select-wrp">
                <select class="table-filter-select" id="bar-chart-filter-type" onchange="spendTimeLoadData()">
                  <option value="summary" selected=""><?php echo $wrd_summary; ?></option>
                  <option value="comparison"><?php echo $wrd_comparison; ?></option>
                  <option value="signed-in"><?php echo $wrd_signedIn; ?></option>
                  <option value="not-signed-in"><?php echo $wrd_notSignedIn; ?></option>
                </select>
              </div>
              <div class="table-filter-select-wrp">
                <select class="table-filter-select" id="bar-chart-filter-period" onchange="spendTimeLoadData()">
                  <option value="days" selected=""><?php echo $wrd_days; ?></option>
                  <option value="months"><?php echo $wrd_months; ?></option>
                  <option value="years"><?php echo $wrd_years; ?></option>
                </select>
              </div>
            </div>
            <div class="bar-chart-wrp" id="bar-chart-wrp-spend-time">
            </div>
          </div>
          <?php
            include "php-frontend/statistics.php";
          ?>
        </div>
      </div>
    </div>
  </body>
</html>
