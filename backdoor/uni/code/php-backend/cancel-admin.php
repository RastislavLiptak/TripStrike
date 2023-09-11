<?php
  function cancelAdmin($accBeId) {
    global $linkBD;
    $sqlDel = "UPDATE admins SET status='delete', password='-', title='-', fulldate=NOW(), datey='0', datem='0', dated='0' WHERE beid='$accBeId'";
    if (mysqli_query($linkBD, $sqlDel)) {
      return "done";
    } else {
      return "Admins database error: ".mysqli_error($linkBD);
    }
  }
?>
