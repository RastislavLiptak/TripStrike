<div class="dashboard-block" id="dashboard-block-active-users">
  <div class="dashboard-block-content">
    <div id="d-active-users-layout">
      <div class="d-active-users-txt-wrp">
        <?php
          $date = date("Y-m-d H:i:s");
          $sqlCurrentActiveUsers = $linkBD->query("SELECT * FROM usertrafficlog WHERE verified='1' and todate > '$date'");
        ?>
        <p id="d-active-users-txt-num"><?php echo $sqlCurrentActiveUsers->num_rows; ?></p>
      </div>
      <div class="d-active-users-txt-wrp">
        <p id="d-active-users-txt-desc"><?php echo $wrd_currentlyActiveUsers; ?></p>
      </div>
    </div>
  </div>
</div>
