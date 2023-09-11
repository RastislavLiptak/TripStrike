<?php
  function permissionToAddCottageRequestMail($firstname, $lastname, $contact_email, $address, $country, $notes, $sign, $langShrt, $date) {
    $subject = "Request permission to add cottages";
    $body = "
      <h3>Applicant</h3>
      <table style='width: 100%;max-width: 650px;border: 1px solid;border-collapse: collapse;'>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>Date of creation of the application</b></td>
          <td style='border: 1px solid;padding: 9px;'>".$date."</td>
        </tr>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>Firstname</b></td>
          <td style='border: 1px solid;padding: 9px;'>".$firstname."</td>
        </tr>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>Lastname</b></td>
          <td style='border: 1px solid;padding: 9px;'>".$lastname."</td>
        </tr>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>Contact Email</b></td>
          <td style='border: 1px solid;padding: 9px;'>".$contact_email."</td>
        </tr>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>Language</b></td>
          <td style='border: 1px solid;padding: 9px;'>".$langShrt."</td>
        </tr>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>Signed in status</b></td>
          <td style='border: 1px solid;padding: 9px;'>".$sign."</td>
        </tr>
      </table>
      <br>
      <h3>About cottage</h3>
      <table style='width: 100%;max-width: 650px;border: 1px solid;border-collapse: collapse;'>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>Address / Coordinates</b></td>
          <td style='border: 1px solid;padding: 9px;'>".$address."</td>
        </tr>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>Country</b></td>
          <td style='border: 1px solid;padding: 9px;'>".$country."</td>
        </tr>
        <tr>
          <td style='border: 1px solid;padding: 9px;'><b>Notes</b></td>
          <td style='border: 1px solid;padding: 9px;'>".$notes."</td>
        </tr>
      </table>
    ";
    sendMail("liptak.rastislav@gmail.com", $subject, $body, "report-user-mail");
  }
?>
