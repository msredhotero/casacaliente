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

$resDatos = $serviciosReportes->rptConsultaOcupacionalAnio($idlocatario, $any);
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



	$pdf->AddPage();

	//////////////////// Aca arrancan a cargarse los datos de los equipos  /////////////////////////

	$pdf->SetFillColor(160,232,232);
	$pdf->SetFont('Arial','B',15);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetY(5);
	$pdf->SetX(5);
	$pdf->Cell(197,20,'SETMANES PER APARTAMENT ANY',1,0,'L',true);
	$pdf->Ln();
	$pdf->SetX(5);

	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(42,5,'COD APARTAMENT',1,0,'C',true);
	$pdf->Cell(35,5,utf8_decode('N° LLOGUER'),1,0,'C',true);
	$pdf->Cell(30,5,'ENTRADA',1,0,'C',true);
	$pdf->Cell(30,5,'SORTIDA',1,0,'C',true);
	$pdf->Cell(30,5,'DIES',1,0,'C',true);
	$pdf->Cell(30,5,'SETMANES',1,0,'C',true);


	$i=0;
	$cantidad = 0;
	$primero = 0;
	$hutg = '';


	$contadorY1 = 44;
	$contadorY2 = 44;
while ($rowE = mysql_fetch_array($resDatos)) {
	$i+=1;
	$cantidad+=1;

	if ($hutg != $rowE['codapartament']) {

		if ($primero != 0) {
			$pdf->Ln();
			$pdf->SetX(5);
			$pdf->SetFont('Arial','b',10);
			$pdf->Cell(137,5,'SUBTOTAL COD APARTAMENT: ',1,0,'C',true);
		   $pdf->Cell(30,5, $subtotales1,1,0,'R',false);
			$pdf->Cell(30,5, $subtotales2,1,0,'R',false);


			$subtotales1 = 0;
			$subtotales2 = 0;

		}

		$hutg = $rowE['codapartament'];
		$pdf->SetFont('Arial','',14);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->Cell(10,7,$rowE['codapartament'],1,1,'C',false);

		$i += 2;
	}

	if ($i > 30) {
		Footer($pdf);
		$pdf->AddPage();
		$pdf->SetFillColor(160,232,232);
		$pdf->SetFont('Arial','B',15);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetY(5);
		$pdf->SetX(5);
		$pdf->Cell(197,20,'SETMANES PER APARTAMENT ANY',1,0,'L',true);
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(42,5,'COD APARTAMENT',1,0,'C',true);
		$pdf->Cell(35,5,utf8_decode('N° LLOGUER'),1,0,'C',true);
		$pdf->Cell(30,5,'ENTRADA',1,0,'C',true);
		$pdf->Cell(30,5,'SORTIDA',1,0,'C',true);
		$pdf->Cell(30,5,'DIES',1,0,'C',true);
		$pdf->Cell(30,5,'SETMANES',1,0,'C',true);

		$i=0;

	}


	$pdf->Ln();
	$pdf->SetX(5);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(42,5,'',1,0,'C',false);
	$pdf->Cell(35,5,$rowE['nrolloguer'],1,0,'C',false);
	$pdf->Cell(30,5,$rowE['entrada'],1,0,'C',false);
	$pdf->Cell(30,5,$rowE['sortida'],1,0,'C',false);
	$pdf->Cell(30,5,$rowE['dias'],1,0,'C',false);
	$pdf->Cell(30,5,$rowE['semanas'],1,0,'C',false);

	$totales1 += $rowE['dias'];
	$totales2 += $rowE['semanas'];

	$subtotales1 += $rowE['dias'];
	$subtotales2 += $rowE['semanas'];

	$primero = 1;
	$contadorY1 += 4;

	//$pdf->SetY($contadorY1);


}

$pdf->Ln();
$pdf->SetX(5);
$pdf->SetFont('Arial','b',10);
$pdf->Cell(137,5,'SUBTOTAL COD APARTAMENT: ',1,0,'C',true);
$pdf->Cell(30,5, $subtotales1,1,0,'R',false);
$pdf->Cell(30,5, $subtotales2,1,0,'R',false);

$pdf->Ln();
$pdf->Ln();
$pdf->SetX(5);
$pdf->SetFont('Arial','b',10);
$pdf->Cell(137,5,'TOTALES',1,0,'L',true);
$pdf->Cell(30,5, $totales1,1,0,'R',false);
$pdf->Cell(30,5, $totales2,1,0,'R',false);

$pdf->Ln();
$pdf->Ln();


Footer($pdf);



$nombreTurno = "rptFacturesPerClient-".$fecha.".pdf";

$pdf->Output($nombreTurno,'I');


?>
