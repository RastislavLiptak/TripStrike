<form class="n-c-layout" id="n-c-layout-map">
  <div class="n-c-error-wrp">
    <p class="n-c-error-txt" id="n-c-error-txt-map"></p>
  </div>
  <h2 class="n-c-title"><?php echo $wrd_map; ?></h2>
  <div id="n-c-map-wrp">
    <div id="n-c-map-lat-lng-wrp">
      <p id="n-c-map-lat">0</p>
      <p id="n-c-map-lng">0</p>
    </div>
    <button type="button" id="n-c-map-search-btn" onclick="nCModMapSearch('show')"></button>
    <div id="n-c-map"></div>
  </div>
</form>
