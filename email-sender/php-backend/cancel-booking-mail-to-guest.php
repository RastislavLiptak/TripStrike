<?php
  function cancelBookingMailToGuest($canceledBy, $bookingSts, $plcID, $hostID, $email, $hostName, $hostEmail, $hostPhone, $name, $language, $depositPaidSts, $fullAmountPaidSts, $fromy, $fromm, $fromd, $firstDay, $toy, $tom, $tod, $lastDay, $guestNum, $creationDateY, $creationDateM, $creationDateD, $twoWeeksBefore) {
    global $title, $domain;
    if ($language == "cz") {
      $subject = "Zrušení rezervace - ".$name;
      if ($firstDay == "whole") {
        $firstDay = "Celý den";
      } else {
        $firstDay = "od 14:00";
      }
      if ($lastDay == "whole") {
        $lastDay = "Celý den";
      } else {
        $lastDay = "do 11:00";
      }
      if ($bookingSts == "booked") {
        if ($canceledBy == "by-host") {
          $cancellationFeeTxt = "
            <br>
            Zrušení vašeho pobytu provedl hostitel, proto se prosím s případnými dotazy obraťte na něj.
          ";
        } else if ($canceledBy == "by-guest") {
          if ($twoWeeksBefore) {
            $cancellationFeeTxt = "
              <br>
              Protože ke zrušení došlo více jak 2 týdny před začátkem vašeho pobytu, máte právo na vrácení všech doposud uhrazených peněz.
            ";
          } else {
            if ($depositPaidSts == 1 || $fullAmountPaidSts == 1) {
              $cancellationFeeTxt = "
                <br>
                Protože ke zrušení došlo méně, než 2 týdny před začátkem Vašeho pobytu, hostitel není povinnen k vrácení depositu (10% z celkové ceny rezervace), který si může ponechat jako poplatek za zrušení.
              ";
            } else {
              $cancellationFeeTxt = "";
            }
          }
        }
      } else {
        $cancellationFeeTxt = "";
      }
      $body = "
        Dobrý den,<br>
        Vaše rezervace chaty <b>".$name."</b> byla dne ".date('d').". ".date('m').". ".date('Y')." (".date('H:i:s').") zrušená.
        ".$cancellationFeeTxt."
        <br>
        <br>
        <br>
        <b>Detaily rezervace</b><br>
        <b>Místo:</b> <a href='".$domain."/place/?id=".$plcID."'>".$name."</a><br>
        <b>Od:</b> ".$fromd.".".$fromm.".".$fromy." (".$firstDay.")<br>
        <b>Do:</b> ".$tod.".".$tom.".".$toy." (".$lastDay.")<br>
        <b>Počet hostů:</b> ".$guestNum."<br>
        <b>Datum vytvoření rezervace:</b> ".$creationDateD.".".$creationDateM.".".$creationDateY."
        <br>
        <br>
        <br>
        <b>Kontakt na hostitele:</b><br>
        <b>Jméno:</b> ".$hostName."<br>
        <b>Email:</b> ".$hostEmail."<br>
        <b>Telefon:</b> ".$hostPhone."<br>
        <br>
        <br>
        S pozdravem,<br>
        <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$hostID."&section=about'>".$hostName."</a>
      ";
    } else if ($language == "sk") {
      $subject = "Zrušenie rezervácie - ".$name;
      if ($firstDay == "whole") {
        $firstDay = "Celý deň";
      } else {
        $firstDay = "od 14:00";
      }
      if ($lastDay == "whole") {
        $lastDay = "Celý deň";
      } else {
        $lastDay = "do 11:00";
      }
      if ($bookingSts == "booked") {
        if ($canceledBy == "by-host") {
          $cancellationFeeTxt = "
            <br>
            Zrušenie vášho pobytu vykonal hostiteľ, preto sa prosím s prípadnými otázkami obráťte na neho.
          ";
        } else if ($canceledBy == "by-guest") {
          if ($twoWeeksBefore) {
            $cancellationFeeTxt = "
              <br>
              Pretože k zrušeniu došlo viac ako 2 týždne pred začiatkom vášho pobytu, máte právo na vrátenie všetkých doposiaľ uhradených peňazí.
            ";
          } else {
            if ($depositPaidSts == 1 || $fullAmountPaidSts == 1) {
              $cancellationFeeTxt = "
                <br>
                Pretože k zrušeniu došlo menej ako 2 týždne pred začiatkom Vášho pobytu, hostiteľ nie je povinný k vráteniu depositu (10% z celkovej ceny rezervácie), ktorý si môže ponechať ako poplatok za zrušenie.
              ";
            } else {
              $cancellationFeeTxt = "";
            }
          }
        }
      } else {
        $cancellationFeeTxt = "";
      }
      $body = "
        Dobrý deň,<br>
        Vaša rezervácia chaty <b>".$name."</b> bola dňa ".date('d').". ".date('m').". ".date('Y')." (".date('H:i:s').") zrušená.
        ".$cancellationFeeTxt."
        <br>
        <br>
        <br>
        <b>Detaily rezervácie</b><br>
        <b>Miesto:</b> <a href='".$domain."/place/?id=".$plcID."'>".$name."</a><br>
        <b>Od:</b> ".$fromd.".".$fromm.".".$fromy." (".$firstDay.")<br>
        <b>Do:</b> ".$tod.".".$tom.".".$toy." (".$lastDay.")<br>
        <b>Počet hostí:</b> ".$guestNum."<br>
        <b>Dátum vytvorenia rezervácie:</b> ".$creationDateD.".".$creationDateM.".".$creationDateY."
        <br>
        <br>
        <br>
        <b>Kontakt na hostiteľa:</b><br>
        <b>Meno:</b> ".$hostName."<br>
        <b>Email:</b> ".$hostEmail."<br>
        <b>Telefón:</b> ".$hostPhone."<br>
        <br>
        <br>
        S pozdravom,<br>
        <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$hostID."&section=about'>".$hostName."</a>
      ";
    } else {
      $subject = "Cancellation of booking - ".$name;
      if ($firstDay == "whole") {
        $firstDay = "Whole day";
      } else {
        $firstDay = "from 14:00";
      }
      if ($lastDay == "whole") {
        $lastDay = "Whole day";
      } else {
        $lastDay = "to 11:00";
      }
      if ($bookingSts == "booked") {
        if ($canceledBy == "by-host") {
          $cancellationFeeTxt = "
            <br>
            Your stay was canceled by the host, so please contact him with any questions.
          ";
        } else if ($canceledBy == "by-guest") {
          if ($twoWeeksBefore) {
            $cancellationFeeTxt = "
              <br>
              As the cancellation took place more than 2 weeks before the start of your stay, you have the right to a refund of any money paid so far.
            ";
          } else {
            if ($depositPaidSts == 1 || $fullAmountPaidSts == 1) {
              $cancellationFeeTxt = "
                <br>
                As the cancellation took place less than 2 weeks before the start of your stay, the host is not obliged to return the deposit (10% of the total price of the booking), which he can keep as a cancellation fee.
              ";
            } else {
              $cancellationFeeTxt = "";
            }
          }
        }
      } else {
        $cancellationFeeTxt = "";
      }
      $body = "
        Good day,<br>
        Your booking for the cottage <b>".$name."</b> was canceled on ".date('d').". ".date('m').". ".date('Y')." (".date('H:i:s').").
        ".$cancellationFeeTxt."
        <br>
        <br>
        <br>
        <b>Booking details</b><br>
        <b>Place:</b> <a href='".$domain."/place/?id=".$plcID."'>".$name."</a><br>
        <b>From:</b> ".$fromd.".".$fromm.".".$fromy." (".$firstDay.")<br>
        <b>To:</b> ".$tod.".".$tom.".".$toy." (".$lastDay.")<br>
        <b>Number of guests:</b> ".$guestNum."<br>
        <b>Date of booking creation:</b> ".$creationDateD.".".$creationDateM.".".$creationDateY."
        <br>
        <br>
        <br>
        <b>Contact the host:</b><br>
        <b>Name:</b> ".$hostName."<br>
        <b>Email:</b> ".$hostEmail."<br>
        <b>Telephone number:</b> ".$hostPhone."<br>
        <br>
        <br>
        Regards,<br>
        <a href='".$domain."'>".$title."</a> & <a href='".$domain."/user/?id=".$hostID."&section=about'>".$hostName."</a>
      ";
    }
    sendMail($email, $subject, $body, "booking-canceled-mail-to-guest");
  }
?>
