<?php
  function forgottenPasswordCodeMail($userID, $userName, $userContactEmail, $userLanguage, $code) {
    global $title, $domain;
    if ($userLanguage == "cz") {
      $subject = "Obnovení zapomenutého hesla";
      $body = "
        Dobrý den,<br>
        přijali jsme žádost o změnu zapomenutého hesla u profilu <a href='".$domain."/user/?id=".$userID."&section=about'>".$userName."</a>. Pokud jste o změnu nezažádali, je možné, že došlo k pokusu o útok na váš účet.
        <br>
        <br>
        <br>
        <b>Ověřovací kód:</b> ".$code."
        <br>
        <br>
        <br>
        S pozdravem,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    } else if ($userLanguage == "sk") {
      $subject = "Obnovenie zabudnutého hesla";
      $body = "
        Dobrý deň,<br>
        prijali sme žiadosť o zmenu zabudnutého hesla u profilu <a href='".$domain."/user/?id=".$userID."&section=about'>".$userName."</a>. Ak ste o zmenu nepožiadali, je možné, že došlo k pokusu o útok na váš účet.
        <br>
        <br>
        <br>
        <b>Overovací kód:</b> ".$code."
        <br>
        <br>
        <br>
        S pozdravom,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    } else {
      $subject = "Recover a forgotten password";
      $body = "
        Good day,<br>
        we have received a request to change the forgotten password for <a href='".$domain."/user/?id=".$userID."&section=about'>".$userName."</a>'s profile. If you didn't request the change, it's possible that an attempt was made to attack your account.
        <br>
        <br>
        <br>
        <b>Verification code:</b> ".$code."
        <br>
        <br>
        <br>
        Regards,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    }
    sendMail($userContactEmail, $subject, $body, "forgotten-password");
  }
?>
