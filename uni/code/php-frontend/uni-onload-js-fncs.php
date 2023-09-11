<?php
  if ($sign != "yes") {
    $accountDropClickTask = "sign-in";
  } else {
    $accountDropClickTask = "user";
  }
  $onload = "
    settingsOnloadData('".$langstring."');
    headerAccountOnclick('".$accountDropClickTask."');
  ";
?>
