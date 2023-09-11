<?php
  include "../uni/code/php-head.php";
  if (!isset($_GET['section'])) {
    header("Location: ../dashboard/?section=dashboard");
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <?php
      include "../uni/code/php-frontend/head.php";
    ?>
    <title><?php echo "Dashboard - ".$title." Back Door"; ?></title>
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
            <h1 id="content-title">Dashboard</h1>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
