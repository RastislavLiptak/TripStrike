<?php
  include "../uni/code/php-head.php";
  include "../libraries/PHPExcel-1.8/Classes/PHPExcel.php";
  $subtitle = $wrd_support;
  $employeesListFile = "../excel/list-of-employees.xlsx";
  $employeesListRender =  PHPExcel_IOFactory::createReaderForFile($employeesListFile);
  $employeesListExcel_Obj = $employeesListRender->load($employeesListFile);
  $employeesListWorksheet = $employeesListExcel_Obj->getSheet('0');
  $employeesListLastRow = $employeesListWorksheet->getHighestRow();
?>
<!DOCTYPE html>
<html lang="<?php echo $wrd_htmlLang; ?>">
  <head>
    <meta charset="UTF-8">
    <?php
      include "../uni/code/php-frontend/head.php";
    ?>
    <script src="js/support.js"></script>
    <link rel="stylesheet" type="text/css" href="css/support.css">
    <meta name="description" content="<?php echo $wrd_metaDescription; ?>">
    <title><?php echo $wrd_support." - ".$title; ?></title>
  </head>
  <body onload="<?php echo $onload; ?>">
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
    ?>
    <div id="support-wrp">
      <div class="support-content-block">
        <h1 class="support-content-title"><?php echo $wrp_ourTeam; ?></h1>
        <div id="support-employees-layout">
          <?php
            for ($row = 2;$row <= $employeesListLastRow;$row++) {
          ?>
              <div class="support-employees-card">
                <div class="support-employees-card-img-wrp">
                  <img src="../uni/images/employees/<?php echo $employeesListWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(5).$row)->getValue(); ?>" alt="<?php echo $employeesListWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(2).$row)->getValue()." image"; ?>" class="support-employees-card-img">
                </div>
                <div class="support-employees-card-txt-wrp">
                  <p class="support-employees-name"><?php echo $employeesListWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(0).$row)->getValue()." ".$employeesListWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(1).$row)->getValue(); ?></p>
                </div>
                <div class="support-employees-card-txt-wrp">
                  <p class="support-employees-position"><?php echo $employeesListWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(2).$row)->getValue(); ?></p>
                </div>
                <div class="support-employees-card-txt-wrp">
                  <a class="support-employees-email" href="mailto:<?php echo $employeesListWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(3).$row)->getValue(); ?>" target="_blank"><?php echo $employeesListWorksheet->getCell(PHPExcel_Cell::stringFromColumnIndex(3).$row)->getValue(); ?></a>
                </div>
              </div>
          <?php
            }
          ?>
        </div>
      </div>
      <div class="support-content-block">
        <h1 class="support-content-title"><?php echo $wrd_contactUs; ?></h1>
        <div id="support-contact-form">
          <?php
            $supportContactEmail = "";
            if ($setcontactemail != "") {
              $supportContactEmail = $setcontactemail;
            } else if (isset($_COOKIE["guest-email"])) {
              $supportContactEmail = $_COOKIE["guest-email"];
            }
          ?>
          <input type="email" placeholder="<?php echo $wrd_contactEmail; ?>" value="<?php echo $supportContactEmail; ?>" class="support-contact-form-input" id="support-contact-form-input-email">
          <input type="text" placeholder="<?php echo $wrd_subject; ?>" class="support-contact-form-input" id="support-contact-form-input-subject">
          <textarea placeholder="<?php echo $wrd_type; ?>" class="support-contact-form-textarea" id="support-contact-form-textarea-content"></textarea>
          <p id="support-contact-form-error"></p>
          <div id="support-contact-form-footer">
            <button class="btn btn-big btn-prim" id="support-contact-form-submit" onclick="supportContactFormSubmit();"><?php echo $wrd_send; ?></button>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
