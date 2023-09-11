<?php
  include "../uni/code/php-head.php";
  if (!isset($_GET['section'])) {
    header("Location: ../place-traffic/?section=place-traffic");
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <?php
      include "../uni/code/php-frontend/head.php";
    ?>
    <script src="js/place-traffic.js"></script>
    <script src="../uni/code/js/striped-chart.js"></script>
    <link rel="stylesheet" type="text/css" href="../uni/code/css/chart-uni.css">
    <link rel="stylesheet" type="text/css" href="../uni/code/css/striped-chart.css">
    <link rel="stylesheet" type="text/css" href="../../uni/code/css/table.css">
    <title><?php echo $wrd_placeTraffic." - ".$title." Back Door"; ?></title>
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
            <h1 id="content-title"><?php echo $wrd_placeTraffic; ?></h1>
          </div>
          <div class="striped-chart-block">
            <div class="striped-chart-header">
              <div class="striped-chart-text-wrp">
                <p class="striped-chart-text" id="striped-chart-text-date">-</p>
              </div>
              <div class="striped-chart-tools">
                <div class="table-filter-select-wrp">
                  <select class="table-filter-select" id="striped-chart-filter-type-place-traffic" onchange="placeTrafficLoadData()">
                    <option value="summary" selected=""><?php echo $wrd_summary; ?></option>
                    <option value="verified"><?php echo $wrd_verified; ?></option>
                    <option value="unverified"><?php echo $wrd_unverified; ?></option>
                  </select>
                </div>
                <div class="table-filter-select-wrp">
                  <select class="table-filter-select" id="striped-chart-filter-period-place-traffic" onchange="placeTrafficLoadData()">
                    <option value="today"><?php echo $wrd_today; ?></option>
                    <option value="yesterday"><?php echo $wrd_yesterday; ?></option>
                    <option value="week" selected=""><?php echo $wrd_week; ?></option>
                    <option value="two-weeks"><?php echo $wrd_twoWeeks; ?></option>
                    <option value="month"><?php echo $wrd_month; ?></option>
                    <option value="three-months"><?php echo $wrd_threeMonths; ?></option>
                    <option value="six-months"><?php echo $wrd_sixMonths; ?></option>
                    <option value="year"><?php echo $wrd_year; ?></option>
                    <option value="two-years"><?php echo $wrd_twoYears; ?></option>
                    <option value="five-years"><?php echo $wrd_fiveYears; ?></option>
                    <option value="all"><?php echo $wrd_all; ?></option>
                  </select>
                </div>
              </div>
            </div>
            <div class="striped-chart-layout">
              <?php
                include "php-frontend/chart.php";
                include "php-frontend/statistics.php";
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
