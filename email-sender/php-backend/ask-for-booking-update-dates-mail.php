<?php
  function askForBookingUpdateDatesMail($hostLanguage, $hostEmail, $plcID, $plcName, $orgFromD, $orgFromM, $orgFromY, $orgFirstDay, $orgToD, $orgToM, $orgToY, $orgLastDay, $newFromD, $newFromM, $newFromY, $newFirstDay, $newToD, $newToM, $newToY, $newLastDay, $guestNum, $new_total, $price_diff, $price_currency, $bookingID, $g_name, $g_email, $g_phone, $inOperation, $plcOperationFrom, $plcOperationTo, $listOfBookingsAndTechnicalShutdownsArray) {
    global $title, $domain;
    include "../../uni/dictionary/langs/".$hostLanguage.".php";
    if ($orgFirstDay == "whole") {
      $orgFirstDay = $wrd_theWholeDay;
    } else {
      $orgFirstDay = $wrd_from." 14:00";
    }
    if ($orgLastDay == "whole") {
      $orgLastDay = $wrd_theWholeDay;
    } else {
      $orgLastDay = $wrd_to." 11:00";
    }
    if ($newFirstDay == "whole") {
      $newFirstDay = $wrd_theWholeDay;
    } else {
      $newFirstDay = $wrd_from." 14:00";
    }
    if ($newLastDay == "whole") {
      $newLastDay = $wrd_theWholeDay;
    } else {
      $newLastDay = $wrd_to." 11:00";
    }
    if ($hostLanguage == "cz") {
      $subject = "Žádost změny termínu rezervace v chatě ".$plcName;
      if ($price_diff != 0) {
        $nowPriceTxt = "Nová cena za rezervaci: ".addCurrency($price_currency, $new_total)." (Rozdíl: ".addCurrency($price_currency, $price_diff).")";
      } else {
        $nowPriceTxt = "Cena za rezervaci není touto změnou ovlivněna a činí ".addCurrency($price_currency, $new_total);
      }
      if (!$inOperation) {
        $operationTxt = "
          Nový termín rezervace zasahuje i mimo rozmezí termínů provozu (".convertMonthNumToText("../../", $hostLanguage, $plcOperationFrom)." - ".convertMonthNumToText("../../", $hostLanguage, $plcOperationTo).")
          <br>
          <br>
        ";
      } else {
        $operationTxt = "";
      }
      if (sizeof($listOfBookingsAndTechnicalShutdownsArray) > 0) {
        $affectedTxt = "<b>Změna ovlivní následující termíny v kalendáři:</b><br>";
        for ($aT=0; $aT < sizeof($listOfBookingsAndTechnicalShutdownsArray); $aT++) {
          if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['type'] == "booking") {
            $aff_type = "Rezervace";
          } else {
            $aff_type = "Technické odstavení";
          }
          if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['firstDay'] == "whole") {
            $aff_from = $listOfBookingsAndTechnicalShutdownsArray[$aT]['fromD'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['fromM'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['fromY']." (".$wrd_theWholeDay.")";
          } else {
            $aff_from = $listOfBookingsAndTechnicalShutdownsArray[$aT]['fromD'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['fromM'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['fromY']." (".$wrd_from." 14:00)";
          }
          if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['lastDay'] == "whole") {
            $aff_to = $listOfBookingsAndTechnicalShutdownsArray[$aT]['toD'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['toM'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['toY']." (".$wrd_theWholeDay.")";
          } else {
            $aff_to = $listOfBookingsAndTechnicalShutdownsArray[$aT]['toD'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['toM'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['toY']." (".$wrd_to." 11:00)";
          }
          if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['task'] == "delete") {
            if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['type'] == "booking") {
              $aff_change = "Rezervace bude zrušená";
            } else {
              $aff_change = "Technické odstavení bude zrušené";
            }
          } else if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['task'] == "reject") {
            if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['type'] == "booking") {
              $aff_change = "Rezervace bude zamítnutá";
            } else {
              $aff_change = "Technické odstavení bude zamítnuté";
            }
          } else if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['task'] == "shorten") {
            if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['type'] == "booking") {
              $aff_change = "Rezervace bude zkrácená";
            } else {
              $aff_change = "Technické odstavení bude zkrácené";
            }
          } else if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['task'] == "connect") {
            if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['type'] == "booking") {
              $aff_change = "Rezervace bude spojená s rezervací, na kterou je provedená úprava";
            } else {
              $aff_change = "Technické odstavení bude spojené s rezervací, na kterou je provedená úprava";
            }
          } else if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['task'] == "split") {
            if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['type'] == "booking") {
              $aff_change = "Rezervace bude rozdělená";
            } else {
              $aff_change = "Technické odstavení bude rozdělené";
            }
          } else {
            $aff_change = "Neznámá (task: ".$listOfBookingsAndTechnicalShutdownsArray[$aT]['task'].")";
          }
          $affectedTxt = $affectedTxt."
            ------------<br>
            <b>Typ:</b> ".$aff_type."<br>
            <b>Od:</b> ".$aff_from."<br>
            <b>Do:</b> ".$aff_to."<br>
            <b>Změna:</b> ".$aff_change."<br>
          ";
        }
        $affectedTxt = $affectedTxt."
          ------------
          <br>
          <br>
        ";
      } else {
        $affectedTxt = "";
      }
      $body = "
        Dobrý den,<br>
        host, který má v chatě <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> na termín ".$orgFromD.".".$orgFromM.".".$orgFromY." (".$orgFirstDay.") - ".$orgToD.".".$orgToM.".".$orgToY." (".$orgLastDay.") vytvořenou rezervaci, zažádal o změnu termínu.
        <br>
        <br>
        <br>
        <b>Původní termín:</b> ".$orgFromD.".".$orgFromM.".".$orgFromY." (".$orgFirstDay.") - ".$orgToD.".".$orgToM.".".$orgToY." (".$orgLastDay.")<br>
        <b>Nový termín:</b> ".$newFromD.".".$newFromM.".".$newFromY." (".$newFirstDay.") - ".$newToD.".".$newToM.".".$newToY." (".$newLastDay.")<br>
        <i>".$nowPriceTxt."</i>
        <br>
        <br>
        ".$affectedTxt."
        ".$operationTxt."
        Tuto změnu můžete <a href='".$domain."/bookings/booking-update.php?plc=".$plcID."&booking=".$bookingID."'>potvrdit nebo zamítnout zde</a><br>
        Dokud tuto změnu nepotvrdíte, platí původní údaje.
        <br>
        <br>
        <br>
        <b>Detaily rezervace:</b><br>
        <b>Místo:</b> <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a><br>
        <b>Počet hostů:</b> ".$guestNum."<br>
        <b><a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingID."'>Rezervaci můžete spravovat z tohoto odkazu</a></b>
        <br>
        <br>
        <b>Kontakt na hosta:</b><br>
        <b>Jméno:</b> ".$g_name."<br>
        <b>Email:</b> $g_email<br>
        <b>Telefonní číslo:</b> $g_phone
        <br>
        <br>
        S pozdravem,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    } else if ($hostLanguage == "sk") {
      $subject = "Žiadosť zmeny termínu rezervácie v chate ".$plcName;
      if ($price_diff != 0) {
        $nowPriceTxt = "Nová cena za rezerváciu: ".addCurrency($price_currency, $new_total)." (Rozdiel: ".addCurrency($price_currency, $price_diff).")";
      } else {
        $nowPriceTxt = "Cena za rezerváciu nie je touto zmenou ovplyvnená a činí ".addCurrency($price_currency, $new_total);
      }
      if (!$inOperation) {
        $operationTxt = "
          Nový termín rezervácie zasahuje aj mimo rozmedzia termínov prevádzky (".convertMonthNumToText("../../", $hostLanguage, $plcOperationFrom)." - ".convertMonthNumToText("../../", $hostLanguage, $plcOperationTo).")
          <br>
          <br>
        ";
      } else {
        $operationTxt = "";
      }
      if (sizeof($listOfBookingsAndTechnicalShutdownsArray) > 0) {
        $affectedTxt = "<b>Zmena ovplyvní nasledujúce termíny v kalendári:</b><br>";
        for ($aT=0; $aT < sizeof($listOfBookingsAndTechnicalShutdownsArray); $aT++) {
          if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['type'] == "booking") {
            $aff_type = "Rezervácia";
          } else {
            $aff_type = "Technické odstavenie";
          }
          if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['firstDay'] == "whole") {
            $aff_from = $listOfBookingsAndTechnicalShutdownsArray[$aT]['fromD'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['fromM'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['fromY']." (".$wrd_theWholeDay.")";
          } else {
            $aff_from = $listOfBookingsAndTechnicalShutdownsArray[$aT]['fromD'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['fromM'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['fromY']." (".$wrd_from." 14:00)";
          }
          if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['lastDay'] == "whole") {
            $aff_to = $listOfBookingsAndTechnicalShutdownsArray[$aT]['toD'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['toM'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['toY']." (".$wrd_theWholeDay.")";
          } else {
            $aff_to = $listOfBookingsAndTechnicalShutdownsArray[$aT]['toD'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['toM'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['toY']." (".$wrd_to." 11:00)";
          }
          if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['task'] == "delete") {
            if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['type'] == "booking") {
              $aff_change = "Rezervácia bude zrušená";
            } else {
              $aff_change = "Technické odstavenie bude zrušené";
            }
          } else if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['task'] == "reject") {
            if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['type'] == "booking") {
              $aff_change = "Rezervácia bude zamietnutá";
            } else {
              $aff_change = "Technické odstavenie bude zamietnuté";
            }
          } else if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['task'] == "shorten") {
            if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['type'] == "booking") {
              $aff_change = "Rezervácia bude skrátená";
            } else {
              $aff_change = "Technické odstavenie bude skrátené";
            }
          } else if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['task'] == "connect") {
            if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['type'] == "booking") {
              $aff_change = "Rezervácia bude spojená s rezerváciou, na ktorú je vykonaná úprava";
            } else {
              $aff_change = "Technické odstavenie bude spojené s rezerváciou, na ktorú je vykonaná úprava";
            }
          } else if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['task'] == "split") {
            if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['type'] == "booking") {
              $aff_change = "Rezervace bude rozdelená";
            } else {
              $aff_change = "Technické odstavenie bude rozdelené";
            }
          } else {
            $aff_change = "Neznáma (task: ".$listOfBookingsAndTechnicalShutdownsArray[$aT]['task'].")";
          }
          $affectedTxt = $affectedTxt."
            ------------<br>
            <b>Typ:</b> ".$aff_type."<br>
            <b>Od:</b> ".$aff_from."<br>
            <b>Do:</b> ".$aff_to."<br>
            <b>Zmena:</b> ".$aff_change."<br>
          ";
        }
        $affectedTxt = $affectedTxt."
          ------------
          <br>
          <br>
        ";
      } else {
        $affectedTxt = "";
      }
      $body = "
        Dobrý deň,<br>
        hosť, ktorý má v chate <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> na termín ".$orgFromD.".".$orgFromM.".".$orgFromY." (".$orgFirstDay.") - ".$orgToD.".".$orgToM.".".$orgToY." (".$orgLastDay.") vytvorenú rezerváciu, požiadal o zmenu termínu.
        <br>
        <br>
        <br>
        <b>Pôvodný termín:</b> ".$orgFromD.".".$orgFromM.".".$orgFromY." (".$orgFirstDay.") - ".$orgToD.".".$orgToM.".".$orgToY." (".$orgLastDay.")<br>
        <b>Nový termín:</b> ".$newFromD.".".$newFromM.".".$newFromY." (".$newFirstDay.") - ".$newToD.".".$newToM.".".$newToY." (".$newLastDay.")<br>
        <i>".$nowPriceTxt."</i>
        <br>
        <br>
        ".$affectedTxt."
        ".$operationTxt."
        Túto zmenu môžete <a href='".$domain."/bookings/booking-update.php?plc=".$plcID."&booking=".$bookingID."'>potvrdiť alebo zamietnuť tu</a><br>
        Kým túto zmenu nepotvrdíte, platia pôvodné údaje.
        <br>
        <br>
        <br>
        <b>Detaily rezervácie:</b><br>
        <b>Miesto:</b> <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a><br>
        <b>Počet hostí:</b> ".$guestNum."<br>
        <b><a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingID."'>Rezerváciu môžete spravovať z tohto odkazu</a></b>
        <br>
        <br>
        <b>Kontakt na hosťa:</b><br>
        <b>Meno:</b> ".$g_name."<br>
        <b>Email:</b> $g_email<br>
        <b>Telefónne číslo:</b> $g_phone
        <br>
        <br>
        S pozdravom,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    } else {
      $subject = "Request to change the booking date in the cottage ".$plcName;
      if ($price_diff != 0) {
        $nowPriceTxt = "New booking price: ".addCurrency($price_currency, $new_total)." (Difference: ".addCurrency($price_currency, $price_diff).")";
      } else {
        $nowPriceTxt = "Booking price is not affected by this change and is ".addCurrency($price_currency, $new_total);
      }
      if (!$inOperation) {
        $operationTxt = "
          The new booking date extends beyond the range of operating dates (".convertMonthNumToText("../../", $hostLanguage, $plcOperationFrom)." - ".convertMonthNumToText("../../", $hostLanguage, $plcOperationTo).")
          <br>
          <br>
        ";
      } else {
        $operationTxt = "";
      }
      if (sizeof($listOfBookingsAndTechnicalShutdownsArray) > 0) {
        $affectedTxt = "<b>The change will affect the following dates in the calendar:</b><br>";
        for ($aT=0; $aT < sizeof($listOfBookingsAndTechnicalShutdownsArray); $aT++) {
          if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['type'] == "booking") {
            $aff_type = "Booking";
          } else {
            $aff_type = "Technical shutdown";
          }
          if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['firstDay'] == "whole") {
            $aff_from = $listOfBookingsAndTechnicalShutdownsArray[$aT]['fromD'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['fromM'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['fromY']." (".$wrd_theWholeDay.")";
          } else {
            $aff_from = $listOfBookingsAndTechnicalShutdownsArray[$aT]['fromD'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['fromM'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['fromY']." (".$wrd_from." 14:00)";
          }
          if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['lastDay'] == "whole") {
            $aff_to = $listOfBookingsAndTechnicalShutdownsArray[$aT]['toD'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['toM'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['toY']." (".$wrd_theWholeDay.")";
          } else {
            $aff_to = $listOfBookingsAndTechnicalShutdownsArray[$aT]['toD'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['toM'].".".$listOfBookingsAndTechnicalShutdownsArray[$aT]['toY']." (".$wrd_to." 11:00)";
          }
          if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['task'] == "delete") {
            if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['type'] == "booking") {
              $aff_change = "The booking will be canceled";
            } else {
              $aff_change = "Technical shutdown will be canceled";
            }
          } else if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['task'] == "reject") {
            if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['type'] == "booking") {
              $aff_change = "The booking will be rejected";
            } else {
              $aff_change = "Technical shutdown will be rejected";
            }
          } else if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['task'] == "shorten") {
            if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['type'] == "booking") {
              $aff_change = "The booking will be shortened";
            } else {
              $aff_change = "Technical shutdown will be shortened";
            }
          } else if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['task'] == "connect") {
            if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['type'] == "booking") {
              $aff_change = "The booking will be linked to the booking for which the adjustment is made";
            } else {
              $aff_change = "Technical shutdown will be linked to the booking for which the adjustment is made";
            }
          } else if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['task'] == "split") {
            if ($listOfBookingsAndTechnicalShutdownsArray[$aT]['type'] == "booking") {
              $aff_change = "The booking will be split";
            } else {
              $aff_change = "Technical shutdown will be split";
            }
          } else {
            $aff_change = "Unknown (task: ".$listOfBookingsAndTechnicalShutdownsArray[$aT]['task'].")";
          }
          $affectedTxt = $affectedTxt."
            ------------<br>
            <b>Type:</b> ".$aff_type."<br>
            <b>From:</b> ".$aff_from."<br>
            <b>To:</b> ".$aff_to."<br>
            <b>Change:</b> ".$aff_change."<br>
          ";
        }
        $affectedTxt = $affectedTxt."
          ------------
          <br>
          <br>
        ";
      } else {
        $affectedTxt = "";
      }
      $body = "
        Good day,<br>
        a guest who has a booking made in the <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a> cottage for the period ".$orgFromD.".".$orgFromM.".".$orgFromY." (".$orgFirstDay.") - ".$orgToD.".".$orgToM.".".$orgToY." (".$orgLastDay.") has requested a change of date.
        <br>
        <br>
        <br>
        <b>Original date:</b> ".$orgFromD.".".$orgFromM.".".$orgFromY." (".$orgFirstDay.") - ".$orgToD.".".$orgToM.".".$orgToY." (".$orgLastDay.")<br>
        <b>New date:</b> ".$newFromD.".".$newFromM.".".$newFromY." (".$newFirstDay.") - ".$newToD.".".$newToM.".".$newToY." (".$newLastDay.")<br>
        <i>".$nowPriceTxt."</i>
        <br>
        <br>
        ".$affectedTxt."
        ".$operationTxt."
        You can <a href='".$domain."/bookings/booking-update.php?plc=".$plcID."&booking=".$bookingID."'>confirm or reject this request here</a><br>
        Until you confirm this change, the original data is valid.
        <br>
        <br>
        <br>
        <b>Booking details:</b><br>
        <b>Place:</b> <a href='".$domain."/place/?id=".$plcID."'><b>".$plcName."</b></a><br>
        <b>Number of guests:</b> ".$guestNum."<br>
        <b><a href='".$domain."/bookings/about-guest-booking.php?id=".$bookingID."'>You can manage the booking from this link</a></b>
        <br>
        <br>
        <b>Guest contact:</b><br>
        <b>Name:</b> ".$g_name."<br>
        <b>Email:</b> $g_email<br>
        <b>Phone number:</b> $g_phone
        <br>
        <br>
        Regards,<br>
        <a href='".$domain."'>".$title."</a>
      ";
    }
    sendMail($hostEmail, $subject, $body, "ask-for-booking-update-dates-mail");
  }
?>
