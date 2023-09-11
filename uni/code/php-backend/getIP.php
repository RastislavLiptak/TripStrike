<?php
  function ip_info() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $publ_ip = $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $publ_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $publ_ip = $_SERVER['REMOTE_ADDR'];
    }
    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$publ_ip));
    if ($ip_data && $ip_data->geoplugin_countryName != null) {
      $output = $ip_data->geoplugin_countryCode;
    } else {
      $output = "unknown";
    }
    return $output;
  }
?>
