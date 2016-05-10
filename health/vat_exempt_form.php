<?php
define('GZIP_COMPRESSION','false');
require('includes/application_top.php');

define('RELATIVE_PATH', DIR_WS_CLASSES.'/html2pdf/');
define('FPDF_FONTPATH', DIR_WS_CLASSES.'/html2pdf/font/');
if ( !defined('DIR_FS_DOCUMENT_ROOT') ) define('DIR_FS_DOCUMENT_ROOT', DIR_FS_CATALOG);

include_once(DIR_WS_CLASSES . '/html2pdf/html2fpdf.php');

class VEF_PDF extends HTML2FPDF{
  function VEF_PDF(){
    $this->HTML2FPDF();
    //$this->SetTopMargin(24); // header images
    $this->UseCSS(false);
    $this->SetFontSize(10);
  }
}

$info_id = 10;

$sql = tep_db_query("select if(length(i1.info_title), i1.info_title, i.info_title) as info_title, if(length(i1.page_title), i1.page_title, i.page_title) as page_title, if(length(i1.description), i1.description, i.description) as description, i.information_id from " . TABLE_INFORMATION . " i LEFT JOIN " . TABLE_INFORMATION . " i1 on i.information_id = i1.information_id  and i1.languages_id = '" . (int)$languages_id . "' and i1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "'  where i.information_id = '" . (int)$info_id . "' and i.languages_id = '" . (int)$languages_id . "' and i.visible = 1 and i.affiliate_id = 0");
$row=tep_db_fetch_array($sql);

$INFO_DESCRIPTION = $row['description'];
$INFO_DESCRIPTION = preg_replace('/ src="(.*)\/images/', ' src="images', $INFO_DESCRIPTION);

      $pdf = new VEF_PDF();
      $pdf->AddPage();
      $pdf->WriteHTML($INFO_DESCRIPTION);
      $pdf->Output('vat_exempt_form.pdf','D');

//echo $INFO_DESCRIPTION;

?>
