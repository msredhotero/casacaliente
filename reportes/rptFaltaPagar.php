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

$resDatos = $serviciosReportes->rptFaltaPagar($idlocatario, $any);
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
	$pdf->Cell(283,20,'FALTA PAGAR',1,0,'L',true);
	$pdf->Ln();
	$pdf->SetX(5);

	$pdf->SetFont('Arial','B',10);

	$pdf->Cell(20,5,'DATA',1,0,'C',true);
	$pdf->Cell(20,5,'ENTRADA',1,0,'C',true);
	$pdf->Cell(35,5,'NRO LLOGUER',1,0,'C',true);
	$pdf->Cell(32,5,'NIF',1,0,'C',true);
	$pdf->Cell(43,5,'COGNOM',1,0,'C',true);
   $pdf->Cell(43,5,'NOM',1,0,'C',true);
   $pdf->Cell(30,5,'TOTAL',1,0,'C',true);
	$pdf->Cell(30,5,'PAGO',1,0,'C',true);
	$pdf->Cell(30,5,'FALTA',1,0,'C',true);


	$i=0;
	$cantidad = 0;
	$primero = 0;
	$hutg = '';


	$contadorY1 = 44;
	$contadorY2 = 44;
while ($rowE = mysql_fetch_array($resDatos)) {
	$i+=1;
	$cantidad+=1;

	if ($i > 30) {
		Footer($pdf);
		$pdf->AddPage('L');
		$pdf->SetFillColor(160,232,232);
		$pdf->SetFont('Arial','B',15);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetY(5);
		$pdf->SetX(5);
		$pdf->Cell(283,20,'FALTA PAGAR',1,0,'L',true);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(20,5,'DATA',1,0,'C',true);
		$pdf->Cell(20,5,'ENTRADA',1,0,'C',true);
		$pdf->Cell(35,5,'NRO LLOGUER',1,0,'C',true);
		$pdf->Cell(32,5,'NIF',1,0,'C',true);
		$pdf->Cell(43,5,'COGNOM',1,0,'C',true);
	   $pdf->Cell(43,5,'NOM',1,0,'C',true);
	   $pdf->Cell(30,5,'TOTAL',1,0,'C',true);
		$pdf->Cell(30,5,'PAGO',1,0,'C',true);
		$pdf->Cell(30,5,'FALTA',1,0,'C',true);

		$i=0;

	}


	$pdf->Ln();
	$pdf->SetX(5);
	$pdf->SetFont('Arial','',10);

	$pdf->Cell(20,5,$rowE['datalloguer'],1,0,'C',false);
	$pdf->Cell(20,5,$rowE['entrada'],1,0,'C',false);
	$pdf->Cell(35,5,$rowE['nrolloguer'],1,0,'C',false);
	$pdf->Cell(32,5,$rowE['nif'],1,0,'C',false);
	$pdf->Cell(43,5,substr( utf8_decode($rowE['cognom']),0,25),1,0,'L',false);
	$pdf->Cell(43,5,substr( utf8_decode($rowE['nom']),0,25),1,0,'L',false);
	$pdf->Cell(30,5,number_format( $rowE['total'],2,',','.').' '.EURO,1,0,'R',false);
	$pdf->Cell(30,5,number_format( $rowE['montopagado'],2,',','.').' '.EURO,1,0,'R',false);
	$pdf->Cell(30,5,number_format( $rowE['faltapagar'],2,',','.').' '.EURO,1,0,'R',false);

	$totales1 += $rowE['faltapagar'];

	$primero = 1;
	$contadorY1 += 4;

	//$pdf->SetY($contadorY1);


}



$pdf->Ln();
$pdf->Ln();
$pdf->SetX(5);
$pdf->SetFont('Arial','b',10);
$pdf->Cell(193,5,'Falta Pagar',1,0,'L',true);
$pdf->Cell(30,5,number_format($totales1,2,',','.').' '.EURO,1,0,'R',false);


$pdf->Ln();
$pdf->Ln();


Footer($pdf);



$nombreTurno = "rptFaltaPagar-".$fecha.".pdf";

$pdf->Output($nombreTurno,'I');


?>
