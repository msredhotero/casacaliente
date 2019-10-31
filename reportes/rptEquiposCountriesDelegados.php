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

//$header = array("Hora", "Cancha 1", "Cancha 2", "Cancha 3");

////***** Parametros ****////////////////////////////////
$idlocatario	=	$_GET['idlocatario'];
$desde         =  $_GET['desde'];
$hasta         =  $_GET['hasta'];

$resTemporadas = $serviciosReportes->rptListaTaxaPorApartamento($idlocatario, $desde, $hasta)


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



	$pdf->AddPage('L','mm','A4');

	//////////////////// Aca arrancan a cargarse los datos de los equipos  /////////////////////////

	$pdf->SetFillColor(183,183,183);
	$pdf->SetFont('Arial','B',15);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetY(5);
	$pdf->SetX(5);
	$pdf->Cell(270,10,'LLISTA TAXA GENERALITAT',1,0,'L',true);
	$pdf->Ln();
	$pdf->SetX(5);

	$pdf->SetFont('Arial','',12);
	$pdf->Cell(20,5,'TRIMESTRE',1,0,'C',true);
	$pdf->Cell(20,5,'DATA',1,0,'C',true);
	$pdf->Cell(20,5,'NIF',1,0,'C',true);
	$pdf->Cell(40,5,'COGNOM',1,0,'C',true);
   $pdf->Cell(40,5,'NOM',1,0,'C',true);
   $pdf->Cell(15,5,'MAYORES',1,0,'C',true);
   $pdf->Cell(15,5,'MENORES',1,0,'C',true);
   $pdf->Cell(20,5,'U. ESTADA',1,0,'C',true);
   $pdf->Cell(20,5,'U. SUBJECTES',1,0,'C',true);
   $pdf->Cell(20,5,'U. EXEMPTES',1,0,'C',true);
   $pdf->Cell(20,5,'U. NO SUBJECTES',1,0,'C',true);
   $pdf->Cell(15,5,'TOTAL',1,0,'C',true);

	$cantPartidos = 0;
	$i=0;

	$contadorY1 = 44;
	$contadorY2 = 44;
while ($rowE = mysql_fetch_array($resDatos)) {
	$i+=1;
	$cantPartidos += 1;

	if ($i > 50) {
		Footer($pdf);
		$pdf->AddPage();
		$pdf->Image('../imagenes/logoparainformes.png',2,2,40);
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetY(25);
		$pdf->SetX(5);
		$pdf->Cell(200,5,utf8_decode($nombre),1,0,'C',true);
		$pdf->SetFont('Arial','',10);
		$pdf->Ln();
		$pdf->SetX(5);

		$i=0;

		$pdf->SetFont('Arial','',12);
		$pdf->Cell(5,5,'',1,0,'C',true);
		$pdf->Cell(60,5,'EQUIPO',1,0,'C',true);
		$pdf->Cell(60,5,'CATEGORIA',1,0,'C',true);
		$pdf->Cell(60,5,'DIVISION',1,0,'C',true);

	}


	$pdf->Ln();
	$pdf->SetX(5);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(5,5,$cantPartidos,1,0,'C',false);
	$pdf->Cell(60,5,utf8_decode($rowE['nombre']),1,0,'C',false);
	$pdf->Cell(60,5,utf8_decode($rowE['categoria']),1,0,'C',false);
	$pdf->Cell(60,5,utf8_decode($rowE['division']),1,0,'C',false);


	$contadorY1 += 4;

	//$pdf->SetY($contadorY1);


}


$pdf->Ln();
$pdf->Ln();
$pdf->Ln();


Footer($pdf);



$nombreTurno = "EQUIPOS-CLUB-".$fecha.".pdf";

$pdf->Output($nombreTurno,'I');


?>
