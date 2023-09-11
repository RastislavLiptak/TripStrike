<?php
  function callForFeesPaymentMail($langShrt, $hostEmail, $bookingsData, $totalFees, $currency, $paymentReference, $IBAN, $bankAccount, $BicSwift) {
    global $title, $domain, $projectFolder;
    require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/uni/dictionary/langs/'.$langShrt.'.php';
    $tableContent = "";
    for ($bD=0; $bD < sizeof($bookingsData); $bD++) {
      if ($bookingsData[$bD]['paymentStatus'] == "1") {
        $statusTxt = $wrd_paid;
      } else {
        $statusTxt = $wrd_unpaid;
      }
      $tableContent = $tableContent."
        <tr>
          <td style='border: 1px solid;padding: 9px;'>".$bookingsData[$bD]['fromD'].".".$bookingsData[$bD]['fromM'].".".$bookingsData[$bD]['fromY']." - ".$bookingsData[$bD]['toD'].".".$bookingsData[$bD]['toM'].".".$bookingsData[$bD]['toY']."</td>
          <td style='border: 1px solid;padding: 9px;'><a href='".$domain."/place/?id=".$bookingsData[$bD]['placeID']."'><b>".$bookingsData[$bD]['placeName']."</b></a></td>
          <td style='border: 1px solid;padding: 9px;'>".$statusTxt."</td>
          <td style='border: 1px solid;padding: 9px;'>".($bookingsData[$bD]['feeInPercent'] + 0)."%</td>
          <td style='border: 1px solid;padding: 9px;'>".addCurrency($currency, $bookingsData[$bD]['fee'])."</td>
        </tr>
      ";
    }
    if ($langShrt == "cz") {
      if ($IBAN == "" || $IBAN == "0") {
        $paymentDataIban = "";
      } else {
        $paymentDataIban = "<b>IBAN:</b> ".$IBAN."<br>";
      }
      if ($bankAccount == "" || $bankAccount == "0") {
        $paymentDataBankAccount = "";
      } else {
        $paymentDataBankAccount = "<b>Účet:</b> ".$bankAccount."<br>";
      }
      if ($BicSwift == "" || $BicSwift == "0") {
        $paymentDataBicSwift = "";
      } else {
        $paymentDataBicSwift = "<b>BIC/SWIFT:</b> ".$BicSwift."<br>";
      }
      $paymentData = "
        ".$paymentDataIban."
        ".$paymentDataBankAccount."
        ".$paymentDataBicSwift."
        <b>Celá suma:</b> ".addCurrency($currency, $totalFees)."<br>
        <b>Reference platby / Variabilní symbol:</b> ".$paymentReference."
      ";
      $subject = "Výzva k zaplacení poplatků za rezervace";
      $body = "
        Dobrý den,<br>
        tímto Vás vyzíváme k zaplacení poplatků za níže uvedené rezervace.
        <br>
        <br>
        <table style='width: 100%;max-width: 650px;border: 1px solid;border-collapse: collapse;'>
          <tr>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_dates."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_place."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_status."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_feeInPercent."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_fee."</th>
          </tr>
          ".$tableContent."
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>".$wrd_total."</b></td>
            <td style='border: 1px solid;padding: 9px;text-align: right;' colspan='4'>".addCurrency($currency, $totalFees)."</td>
          </tr>
        </table>
        <br>
        Jednotlivé sumy jsou vypsány v kolonce <i>".$wrd_fee."</i> a představují procentuální část, uvedenou v kolonce <i>".$wrd_feeInPercent."</i>, z celkové hodonty rezervace. Pokud některý z výše uvedených údajů neodpovídá skutečnosti, neváhejte nás <a href='".$domain."/support/'>kontaktovat zde</a>.
        <br>
        <br>
        Pokud je ale vše v pořádku, zaplaťe prosím požadovanou částku na bankovní konto, k němuž údaje jsou uvedeny níže. Hned jak platbu přijmeme jí zaevidujeme v systému a Vám do emailové schránky přijde potvrzení o uhrazení.
        <br>
        Před odesláním platby se prosím ujistěte, že reference platby / variabilní symbol jsou napsány správně a dále pak, že suma je přesně ".addCurrency($currency, $totalFees).".
        <br>
        <br>
        Všechny informace naleznete také na <a href='".$domain."/fees/fee-details.php?paymentreference=".$paymentReference."'>tomto odkazu</a>.
        <br>
        <br>
        <b>Informace k platbě</b><br>
        ".$paymentData."
        <br>
        <br>
        S pozdravem,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    } else if ($langShrt == "sk") {
      if ($IBAN == "" || $IBAN == "0") {
        $paymentDataIban = "";
      } else {
        $paymentDataIban = "<b>IBAN:</b> ".$IBAN."<br>";
      }
      if ($bankAccount == "" || $bankAccount == "0") {
        $paymentDataBankAccount = "";
      } else {
        $paymentDataBankAccount = "<b>Účet:</b> ".$bankAccount."<br>";
      }
      if ($BicSwift == "" || $BicSwift == "0") {
        $paymentDataBicSwift = "";
      } else {
        $paymentDataBicSwift = "<b>BIC/SWIFT:</b> ".$BicSwift."<br>";
      }
      $paymentData = "
        ".$paymentDataIban."
        ".$paymentDataBankAccount."
        ".$paymentDataBicSwift."
        <b>Celá suma:</b> ".addCurrency($currency, $totalFees)."<br>
        <b>Referencie platby / Variabilný symbol:</b> ".$paymentReference."
      ";
      $subject = "Výzva na zaplatenie poplatkov za rezervácie";
      $body = "
        Dobrý deň,<br>
        týmto Vás vyzývame na zaplatenie poplatkov za nižšie uvedené rezervácie.
        <br>
        <br>
        <table style='width: 100%;max-width: 650px;border: 1px solid;border-collapse: collapse;'>
          <tr>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_dates."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_place."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_status."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_feeInPercent."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_fee."</th>
          </tr>
          ".$tableContent."
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>".$wrd_total."</b></td>
            <td style='border: 1px solid;padding: 9px;text-align: right;' colspan='4'>".addCurrency($currency, $totalFees)."</td>
          </tr>
        </table>
        <br>
        Jednotlivé sumy sú vypísané v kolónke <i>".$wrd_fee."</i> a predstavujú percentuálnu časť, uvedenú v kolónke <i>".$wrd_feeInPercent."</i>, z celkovej hodnoty rezervácie. Pokiaľ niektorý z vyššie uvedených údajov nezodpovedá skutočnosti, neváhajte nás <a href='".$domain."/support/'>kontaktovať tu</a>.
        <br>
        <br>
        Pokiaľ je ale všetko v poriadku, zaplaťte prosím požadovanú čiastku na bankové konto, ku ktorému údaje sú uvedené nižšie. Hneď ako platbu prijmeme ju zaevidujeme v systéme a Vám do emailovej schránky príde potvrdenie o uhradení.
        <br>
        Pred odoslaním platby sa prosím uistite, že referencie platby / variabilný symbol sú napísané správne a ďalej potom, že suma je presne ".addCurrency($currency, $totalFees).".
        <br>
        <br>
        Všetky informácie nájdete aj na <a href='".$domain."/fees/fee-details.php?paymentreference=".$paymentReference."'>tomto odkaze</a>.
        <br>
        <br>
        <b>Informácie k platbe</b><br>
        ".$paymentData."
        <br>
        <br>
        S pozdravom,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    } else {
      if ($IBAN == "" || $IBAN == "0") {
        $paymentDataIban = "";
      } else {
        $paymentDataIban = "<b>IBAN:</b> ".$IBAN."<br>";
      }
      if ($bankAccount == "" || $bankAccount == "0") {
        $paymentDataBankAccount = "";
      } else {
        $paymentDataBankAccount = "<b>Account:</b> ".$bankAccount."<br>";
      }
      if ($BicSwift == "" || $BicSwift == "0") {
        $paymentDataBicSwift = "";
      } else {
        $paymentDataBicSwift = "<b>BIC/SWIFT:</b> ".$BicSwift."<br>";
      }
      $paymentData = "
        ".$paymentDataIban."
        ".$paymentDataBankAccount."
        ".$paymentDataBicSwift."
        <b>Total amount:</b> ".addCurrency($currency, $totalFees)."<br>
        <b>Payment reference / Variable symbol:</b> ".$paymentReference."
      ";
      $subject = "Request for payment of booking fees";
      $body = "
        Good day,<br>
        we hereby invite you to pay the fees for the booking listed below.
        <br>
        <br>
        <table style='width: 100%;max-width: 650px;border: 1px solid;border-collapse: collapse;'>
          <tr>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_dates."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_place."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_status."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_feeInPercent."</th>
            <th style='border: 1px solid;padding: 9px;'>".$wrd_fee."</th>
          </tr>
          ".$tableContent."
          <tr>
            <td style='border: 1px solid;padding: 9px;'><b>".$wrd_total."</b></td>
            <td style='border: 1px solid;padding: 9px;text-align: right;' colspan='4'>".addCurrency($currency, $totalFees)."</td>
          </tr>
        </table>
        <br>
        The individual amounts are listed in the <i>".$wrd_fee."</i> column, which represent percentage (<i>".$wrd_feeInPercent."</i>) of of the total value of the booking. If any of the above information does not correspond to the facts, do not hesitate to <a href='".$domain."/support/'>contact us here</a>.
        <br>
        <br>
        However, if everything is in order, please pay the required amount to the bank account to which the data are listed below. As soon as we receive the payment, we will register it in the system and a confirmation of payment mail will be sent to your e-mail box.
        <br>
        Before sending the payment, please make sure that the payment reference / variable symbol is written correctly and then that the amount is exactly ".addCurrency($currency, $totalFees).".
        <br>
        <br>
        You can also find all the information at <a href='".$domain."/fees/fee-details.php?paymentreference=".$paymentReference."'>this link</a>.
        <br>
        <br>
        <b>Payment information</b><br>
        ".$paymentData."
        <br>
        <br>
        Regards,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    }
    sendMail($hostEmail, $subject, $body, "confirmation-of-fees-payment");
  }
?>
