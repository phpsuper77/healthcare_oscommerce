<?php
require('includes/application_top.php');
define('FPDF_FONTPATH','./pdf/font/');
require('./pdf/fpdf.php');
//require('includes/languages/german_agb.php');

class PDF extends FPDF
{

function Header()
{
		$title=TEXT_AGBS_PDF_TITLE;
		$title=strip_tags($title);
    		$this->SetFont('Arial','B',15);
    		$w=$this->GetStringWidth($title)+6;
    		$this->SetX((210-$w)/2);
#    		$this->SetDrawColor(0,80,180);
 	    	$this->SetFillColor(250,250,250);
	    	$this->SetTextColor(0,0,0);
	    	$this->SetLineWidth(1);
	    	$this->Cell($w,9,$title,1,1,'C',1);
	    	$this->Ln(10);
	}

function Footer()
	{
    		$this->SetY(-15);
    		$this->SetFont('Arial','I',8);
    		$this->SetTextColor(128);
    		$this->Cell(0,10,'Seite '.$this->PageNo(),0,0,'C');
	}

function JuraBody($jura)
	{
    		$this->SetFont('Arial','',12);
    		$this->MultiCell(0,5,$jura);
    		$this->Ln();
    		$this->SetFont('','I');
    		$this->Cell(0,5,'');
	}

function PrintJura($st)
	{
    		$this->AddPage();
    		$this->JuraBody($st);
	}

}

$pdf=new PDF();
$pdf->Open();
if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_LANGUAGES . $language . '/agb.tpl.php')){
  $text=str_replace("<br>", "\n", str_replace("<BR>", "\n", implode("", @file(DIR_FS_CATALOG . '/' . DIR_WS_LANGUAGES . $language . '/agb.tpl.php')))); 
}else{
  $text=TEXT_AGBS;
}
$text=strip_tags($text);
$text=ereg_replace("&auml;","ä",$text);
$text=ereg_replace("&Auml;","Ä",$text);
$text=ereg_replace("&Ouml;","Ö",$text);
$text=ereg_replace("&ouml;","ö",$text);
$text=ereg_replace("&Uuml;","Ü",$text);
$text=ereg_replace("&uuml;","ü",$text);
$pdf->PrintJura($text);
$pdf->Output();
?>
