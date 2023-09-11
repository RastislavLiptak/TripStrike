<?php
  if (!function_exists('addCurrency')) {
    function addCurrency($currency, $num) {
      $symbol = currencyConvert($currency);
      if (is_numeric($num) && floor($num) == $num) {
        $num = floor($num);
      }
      $num = round($num, 2);
      $num = $num + 0;
      if ($currency == "eur") {
        $output = $num."".$symbol;
      } else {
        $output = $num;
      }
      return $output;
    }
  }

  if (!function_exists('currencyConvert')) {
    function currencyConvert($currency) {
      global $default_currency_arr, $default_currency_arr_symbol;
      return $default_currency_arr_symbol[array_search($currency, $default_currency_arr)];
    }
  }
?>
