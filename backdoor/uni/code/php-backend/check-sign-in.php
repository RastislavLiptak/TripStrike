<?php
  function backDoorCheckSignIn() {
    global $sign;
    if ($sign == "yes") {
      if (isset($_SESSION["backdoor"])) {
        if (isset($_COOKIE["backdoor"])) {
          setcookie("backdoor", "ready", time() + 900, "/");
          $_SESSION['backdoor'] = "ready";
          return "good";
        } else {
          return "backend-locked";
        }
      } else {
        return "backend-locked";
      }
    } else {
      return "not-signed-in";
    }
  }
?>
