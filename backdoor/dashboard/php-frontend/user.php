<div class="dashboard-block" id="dashboard-block-user">
  <div class="dashboard-block-header">
    <div class="dashboard-block-title-size">
      <div class="dashboard-block-title-wrp">
        <p class="dashboard-block-title-txt"><?php echo $wrd_user; ?></p>
      </div>
    </div>
    <div class="dashboard-see-more-link-wrp">
      <a href="../user/?section=users&nav=about&id=<?php echo $setid; ?>&m=&y=&paymentreference=" class="dashboard-see-more-link"><?php echo $wrd_showMore; ?></a>
    </div>
  </div>
  <div class="dashboard-block-content">
    <div id="d-user-layout">
      <div id="d-user-img-wrp">
        <div id="d-user-img-size">
          <img src="../../<?php echo $midImg; ?>" alt="profile image" id="d-user-img">
        </div>
      </div>
      <div id="d-user-title-wrp">
        <div class="d-user-title-txt-size">
          <p id="d-user-title-name"><?php echo $setfirstname." ".$setlastname; ?></p>
        </div>
        <div class="d-user-title-txt-size">
          <p id="d-user-title-id"><?php echo $setid; ?></p>
        </div>
      </div>
    </div>
  </div>
</div>
