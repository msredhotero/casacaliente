<?php

date_default_timezone_set('America/Buenos_Aires');

include ('../includes/funcionesUsuarios.php');
include ('../includes/funciones.php');
include ('../includes/funcionesHTML.php');
include ('../includes/funcionesReferencias.php');
include ('../includes/funcionesReportes.php');


$serviciosUsuarios  		= new ServiciosUsuarios();
$serviciosFunciones 		= new Servicios();
$serviciosHTML				= new ServiciosHTML();
$serviciosReferencias 	= new ServiciosReferencias();
$serviciosReportes      = new ServiciosReportes();

$fecha = date('Y-m-d');

require('fpdf.php');

define('EURO',chr(128));
//$header = array("Hora", "Cancha 1", "Cancha 2", "Cancha 3");

////***** Parametros ****////////////////////////////////
$idlocatario	=	$_GET['idlocatario'];
$desde         =  $_GET['desde'];
$hasta         =  $_GET['hasta'];

$resDatos = $serviciosReportes->rptListaTaxaPorApartamento($idlocatario, $desde, $hasta);
//die(var_dump($resDatos));

/////////////////////////////  fin parametross  ///////////////////////////



$pdf = new FPDF();


function Footer($pdf)
{

$pdf->SetY(-10);

$pdf->SetFont('Arial','I',10);

$pdf->Cell(0,10,'Pagina '.$pdf->PageNo()." - Fecha: ".date('Y-m-d'),0,0,'C');
}


$cantidadRegistros = 0;
#Establecemos los márgenes izquierda, arriba y derecha:
//$pdf->SetMargins(2, 2 , 2);

#Establecemos el margen inferior:
$pdf->SetAutoPageBreak(false,1);



	$pdf->AddPage('L');

	//////////////////// Aca arrancan a cargarse los datos de los equipos  /////////////////////////

	$pdf->SetFillColor(160,232,232);
	$pdf->SetFont('Arial','B',15);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetY(5);
	$pdf->SetX(5);
	$pdf->Cell(282,20,'LLISTA TAXA GENERALITAT',1,0,'L',true);
	$pdf->Ln();
	$pdf->SetX(5);

	$pdf->SetFont('Arial','',8);
	$pdf->Cell(145,5,'',1,0,'L',true);
	$pdf->Cell(30,5,'PERSONES',1,0,'C',true);
	$pdf->Cell(92,5,'UNITATS',1,0,'C',true);
	$pdf->Cell(15,5,'',1,0,'C',true);
	$pdf->Ln();
	$pdf->SetX(5);


	$pdf->Cell(22,5,'TRIMESTRE',1,0,'C',true);
	$pdf->Cell(20,5,'DATA',1,0,'C',true);
	$pdf->Cell(23,5,'NIF',1,0,'C',true);
	$pdf->Cell(40,5,'COGNOM',1,0,'C',true);
   $pdf->Cell(40,5,'NOM',1,0,'C',true);
   $pdf->Cell(15,5,'>17',1,0,'C',true);
   $pdf->Cell(15,5,'<=16',1,0,'C',true);
   $pdf->Cell(23,5,'ESTADA',1,0,'C',true);
   $pdf->Cell(23,5,'SUBJECTES',1,0,'C',true);
   $pdf->Cell(23,5,'EXEMPTES',1,0,'C',true);
   $pdf->Cell(23,5,'NO SUBJECTES',1,0,'C',true);
   $pdf->Cell(15,5,'TOTAL',1,0,'C',true);


	$i=0;
	$hutg = '';

	$contadorY1 = 44;
	$contadorY2 = 44;
while ($rowE = mysql_fetch_array($resDatos)) {
	$i+=1;

	if ($hutg != $rowE['hutg']) {
		$hutg = $rowE['hutg'];
		$pdf->SetFont('Arial','',14);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->Cell(35,7,$rowE['hutg'],1,1,'C',false);
	}

	if ($i > 50) {
		Footer($pdf);
		$pdf->AddPage('L');
		$pdf->SetFillColor(160,232,232);
		$pdf->SetFont('Arial','B',15);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetY(5);
		$pdf->SetX(5);
		$pdf->Cell(282,20,'LLISTA TAXA GENERALITAT',1,0,'L',true);
		$pdf->Ln();
		$pdf->SetX(5);

		$pdf->SetFont('Arial','',8);
		$pdf->Cell(145,5,'',1,0,'L',true);
		$pdf->Cell(30,5,'PERSONES',1,0,'C',true);
		$pdf->Cell(92,5,'UNITATS',1,0,'C',true);
		$pdf->Cell(15,5,'',1,0,'C',true);
		$pdf->Ln();
		$pdf->SetX(5);


		$pdf->Cell(22,5,'TRIMESTRE',1,0,'C',true);
		$pdf->Cell(20,5,'DATA',1,0,'C',true);
		$pdf->Cell(23,5,'NIF',1,0,'C',true);
		$pdf->Cell(40,5,'COGNOM',1,0,'C',true);
	   $pdf->Cell(40,5,'NOM',1,0,'C',true);
	   $pdf->Cell(15,5,'>17',1,0,'C',true);
	   $pdf->Cell(15,5,'<=16',1,0,'C',true);
	   $pdf->Cell(23,5,'ESTADA',1,0,'C',true);
	   $pdf->Cell(23,5,'SUBJECTES',1,0,'C',true);
	   $pdf->Cell(23,5,'EXEMPTES',1,0,'C',true);
	   $pdf->Cell(23,5,'NO SUBJECTES',1,0,'C',true);
	   $pdf->Cell(15,5,'TOTAL'.EURO,1,0,'C',true);

		$i=0;

	}


	$pdf->Ln();
	$pdf->SetX(5);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(22,5,$rowE['idpago'],1,0,'C',false);
	$pdf->Cell(20,5,$rowE['datalloguer'],1,0,'C',false);
	$pdf->Cell(23,5,$rowE['nif'],1,0,'C',false);
	$pdf->Cell(40,5,substr($rowE['cognom'],0,28),1,0,'L',false);
	$pdf->Cell(40,5,substr($rowE['nom'],0,28),1,0,'L',false);
	$pdf->Cell(15,5,$rowE['mayores'],1,0,'C',false);
	$pdf->Cell(15,5,$rowE['menores'],1,0,'C',false);
	$pdf->Cell(23,5,$rowE['unitatsestada'],1,0,'C',false);
	$pdf->Cell(23,5,$rowE['unitatssubjetes'],1,0,'C',false);
	$pdf->Cell(23,5,$rowE['unitatsexempts'],1,0,'C',false);
	$pdf->Cell(23,5,$rowE['unitatsnosubjetes'],1,0,'C',false);
	$pdf->Cell(15,5,$rowE['total'],1,0,'R',false);


	$contadorY1 += 4;

	//$pdf->SetY($contadorY1);


}


$pdf->Ln();
$pdf->Ln();
$pdf->Ln();


Footer($pdf);



$nombreTurno = "rptLListatTaxaPerApart-".$fecha.".pdf";

$pdf->Output($nombreTurno,'I');


?>
