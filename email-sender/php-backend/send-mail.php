<?php
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/libraries/PHPMailer/src/Exception.php';
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/libraries/PHPMailer/src/PHPMailer.php';
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/libraries/PHPMailer/src/SMTP.php';
  function sendMail($to, $subject, $body, $mailType) {
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP(); // enable SMTP
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true; // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465; // or 587
    $mail->IsHTML(true);
    $mail->Username = "liptak.rastislav@gmail.com";
    $mail->Password = "hvezdne valky";
    $mail->SetFrom("liptak.rastislav@gmail.com");
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AddAddress($to);
    if ($mail->Send()) {
      mailDone('done', $mailType);
    } else {
      // echo "Mail faild: ".$mail->ErrorInfo."<br>";
      mailDone('faild', $mailType);
    }
  }

  // function sendMail($to, $subject, $body, $mailType) {
  //   $headers = "MIME-Version: 1.0" . "\r\n";
  //   $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  //   $headers .= "From: cott@okautoumyvarka.sk" . "\r\n";
  //   if (mail($to, $subject, $body, $headers)) {
  //     mailDone('done', $mailType);
  //   } else {
  //     mailDone('faild', $mailType);
  //   }
  // }
?>
