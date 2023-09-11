<?php
  $sqlRandomPlc = $link->query("SELECT * FROM places WHERE status='active' ORDER BY RAND() LIMIT 1");
  if ($sqlRandomPlc->num_rows > 0) {
    $randomPlaceID = getFrontendId($sqlRandomPlc->fetch_assoc()["beid"]);
?>
  <div id="home-searchbar-wrp">
    <button type="button" id="home-searchbar-random-btn" onclick="location.href = '../place/?id=<?php echo $randomPlaceID; ?>'">
      <p id="home-searchbar-random-btn-txt"><?php echo $wrd_tryYourLuck; ?></p>
      <span id="home-searchbar-random-btn-icon"></span>
    </button>
  </div>
<?php
  }
?>
