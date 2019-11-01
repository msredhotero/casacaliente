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
$any         =  $_GET['any'];

$resDatos = $serviciosReportes->rptFacturaPorClienteSinTaxa($idlocatario, $any);
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

$totales1 = 0;
$totales2 = 0;
$totales3 = 0;
$subtotales1 = 0;
$subtotales2 = 0;
$subtotales3 = 0;

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
	$pdf->Cell(283,20,'FACTURES PER CLIENT',1,0,'L',true);
	$pdf->Ln();
	$pdf->SetX(5);

	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(22,5,'TRIMESTRE',1,0,'C',true);
	$pdf->Cell(20,5,utf8_decode('N°ORDRE'),1,0,'C',true);
	$pdf->Cell(20,5,'DATA',1,0,'C',true);
	$pdf->Cell(18,5,utf8_decode('N°FACT'),1,0,'C',true);
	$pdf->Cell(27,5,'NIF',1,0,'C',true);
	$pdf->Cell(43,5,'COGNOM',1,0,'C',true);
   $pdf->Cell(43,5,'NOM',1,0,'C',true);
   $pdf->Cell(30,5,'BASE',1,0,'C',true);
	$pdf->Cell(30,5,'IVA',1,0,'C',true);
	$pdf->Cell(30,5,'TOTAL',1,0,'C',true);


	$i=0;
	$cantidad = 0;
	$primero = 0;
	$hutg = '';


	$contadorY1 = 44;
	$contadorY2 = 44;
while ($rowE = mysql_fetch_array($resDatos)) {
	$i+=1;
	$cantidad+=1;

	if ($hutg != $rowE['trimestre']) {

		if ($primero != 0) {
			$pdf->Ln();
			$pdf->SetX(5);
			$pdf->SetFont('Arial','b',10);
			$pdf->Cell(193,5,'SUBTOTAL TRIMESTRE: ',1,0,'C',true);
		   $pdf->Cell(30,5,number_format( $subtotales1,2,',','.').' '.EURO,1,0,'R',false);
			$pdf->Cell(30,5,number_format( $subtotales2,2,',','.').' '.EURO,1,0,'R',false);
			$pdf->Cell(30,5,number_format( $subtotales3,2,',','.').' '.EURO,1,0,'R',false);

			$subtotales1 = 0;
			$subtotales2 = 0;
			$subtotales3 = 0;
		}

		$hutg = $rowE['trimestre'];
		$pdf->SetFont('Arial','',14);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->Cell(35,7,$rowE['trimestre'],1,1,'C',false);

		$i += 6;
	}

	if ($i > 30) {
		Footer($pdf);
		$pdf->AddPage('L');
		$pdf->SetFillColor(160,232,232);
		$pdf->SetFont('Arial','B',15);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetY(5);
		$pdf->SetX(5);
		$pdf->Cell(283,20,'FACTURES PER CLIENT',1,0,'L',true);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(22,5,'TRIMESTRE',1,0,'C',true);
		$pdf->Cell(20,5,utf8_decode('N°ORDRE'),1,0,'C',true);
		$pdf->Cell(20,5,'DATA',1,0,'C',true);
		$pdf->Cell(18,5,utf8_decode('N°FACT'),1,0,'C',true);
		$pdf->Cell(27,5,'NIF',1,0,'C',true);
		$pdf->Cell(43,5,'COGNOM',1,0,'C',true);
	   $pdf->Cell(43,5,'NOM',1,0,'C',true);
	   $pdf->Cell(30,5,'BASE',1,0,'C',true);
		$pdf->Cell(30,5,'IVA',1,0,'C',true);
		$pdf->Cell(30,5,'TOTAL',1,0,'C',true);

		$i=0;

	}


	$pdf->Ln();
	$pdf->SetX(5);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(22,5,'',1,0,'C',false);
	$pdf->Cell(20,5,$cantidad,1,0,'C',false);
	$pdf->Cell(20,5,$rowE['fechapago'],1,0,'C',false);
	$pdf->Cell(18,5,$rowE['idpago'],1,0,'C',false);
	$pdf->Cell(27,5,$rowE['nif'],1,0,'C',false);
	$pdf->Cell(43,5,substr( utf8_encode($rowE['cognom']),0,25),1,0,'L',false);
	$pdf->Cell(43,5,substr( utf8_encode($rowE['nom']),0,25),1,0,'L',false);
	$pdf->Cell(30,5,number_format( $rowE['base'],2,',','.').' '.EURO,1,0,'R',false);
	$pdf->Cell(30,5,number_format( $rowE['iva'],2,',','.').' '.EURO,1,0,'R',false);
	$pdf->Cell(30,5,number_format( $rowE['monto'],2,',','.').' '.EURO,1,0,'R',false);

	$totales1 += $rowE['base'];
	$totales2 += $rowE['iva'];
	$totales3 += $rowE['monto'];

	$subtotales1 += $rowE['base'];
	$subtotales2 += $rowE['iva'];
	$subtotales3 += $rowE['monto'];

	$primero = 1;
	$contadorY1 += 4;

	//$pdf->SetY($contadorY1);


}

$pdf->Ln();
$pdf->SetX(5);
$pdf->SetFont('Arial','b',10);
$pdf->Cell(193,5,'SUBTOTAL TRIMESTRE: ',1,0,'C',true);
$pdf->Cell(30,5,number_format( $subtotales1,2,',','.').' '.EURO,1,0,'R',false);
$pdf->Cell(30,5,number_format( $subtotales2,2,',','.').' '.EURO,1,0,'R',false);
$pdf->Cell(30,5,number_format( $subtotales3,2,',','.').' '.EURO,1,0,'R',false);

$pdf->Ln();
$pdf->Ln();
$pdf->SetX(5);
$pdf->SetFont('Arial','b',10);
$pdf->Cell(193,5,'Totales',1,0,'L',true);
$pdf->Cell(30,5,number_format($totales1,2,',','.').' '.EURO,1,0,'R',false);
$pdf->Cell(30,5,number_format($totales2,2,',','.').' '.EURO,1,0,'R',false);
$pdf->Cell(30,5,number_format($totales3,2,',','.').' '.EURO,1,0,'R',false);

$pdf->Ln();
$pdf->Ln();


Footer($pdf);



$nombreTurno = "rptFacturesPerClient-".$fecha.".pdf";

$pdf->Output($nombreTurno,'I');


?>
