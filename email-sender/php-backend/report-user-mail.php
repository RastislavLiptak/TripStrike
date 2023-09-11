<?php
  function reportUserMail($plaintiffId, $plaintiffEmail, $reportedUserId, $reportedUserFirstname, $reportedUserLastname, $reportedUserEmail, $reportedUserContactEmail, $reportedUserPhonenum, $reportedUserContactPhonenum, $reportedUserLanguage, $report, $date) {
    global $domain;
    $subject = "Reported user!!";
    $body = "
      <h3>About report</h3>
      <table style='width: 100%;max-width: 650px;border: 1px solid;border-collapse: collapse;'>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>Date of report</b></td>
          <td style='border: 1px solid;padding: 9px;'>".date('d').". ".date('m').". ".date('Y')." (".date('H:i:s').")"."</td>
        </tr>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>Reported for</b></td>
          <td style='border: 1px solid;padding: 9px;'>".$report."</td>
        </tr>
      </table>
      <br>
      <h3>About plaintiff</h3>
      <table style='width: 100%;max-width: 650px;border: 1px solid;border-collapse: collapse;'>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>ID</b></td>
          <td style='border: 1px solid;padding: 9px;'>".$plaintiffId."</td>
        </tr>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>Email</b></td>
          <td style='border: 1px solid;padding: 9px;'>".$plaintiffEmail."</td>
        </tr>
      </table>
      <br>
      <h3>About reported user</h3>
      <table style='width: 100%;max-width: 650px;border: 1px solid;border-collapse: collapse;'>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>ID</b></td>
          <td style='border: 1px solid;padding: 9px;'>".$reportedUserId."</td>
        </tr>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>Firstname</b></td>
          <td style='border: 1px solid;padding: 9px;'>".$reportedUserFirstname."</td>
        </tr>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>Lastname</b></td>
          <td style='border: 1px solid;padding: 9px;'>".$reportedUserLastname."</td>
        </tr>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>Email</b></td>
          <td style='border: 1px solid;padding: 9px;'>".$reportedUserEmail."</td>
        </tr>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>Contact Email</b></td>
          <td style='border: 1px solid;padding: 9px;'>".$reportedUserContactEmail."</td>
        </tr>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>Phone number</b></td>
          <td style='border: 1px solid;padding: 9px;'>".$reportedUserPhonenum."</td>
        </tr>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>Contact phone number</b></td>
          <td style='border: 1px solid;padding: 9px;'>".$reportedUserContactPhonenum."</td>
        </tr>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>Language</b></td>
          <td style='border: 1px solid;padding: 9px;'>".$reportedUserLanguage."</td>
        </tr>
      </table>
      <br>
      <a href='".$domain."/user/?id=".$reportedUserId."&section=about'>".$reportedUserFirstname." ".$reportedUserLastname."</a>
    ";
    sendMail("liptakr@tripstrike.com", $subject, $body, "report-user-mail");
  }
?>
