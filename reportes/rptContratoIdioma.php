<?php

date_default_timezone_set('Europe/Madrid');

include ('../includes/funcionesUsuarios.php');
include ('../includes/funciones.php');
include ('../includes/funcionesHTML.php');
include ('../includes/funcionesReferencias.php');


$serviciosUsuarios  		= new ServiciosUsuarios();
$serviciosFunciones 		= new Servicios();
$serviciosHTML				= new ServiciosHTML();
$serviciosReferencias 			= new ServiciosReferencias();

$fecha = date('Y-m-d');

require('fpdf.php');

//$header = array("Hora", "Cancha 1", "Cancha 2", "Cancha 3");

////***** Parametros ****////////////////////////////////
$idioma		=	$_GET['idioma'];

$id         =  $_GET['id'];

$resLloguer =  $serviciosReferencias->traerLloguersPorIdCompleto($id);

$resLloguerAdicional =  $serviciosReferencias->traerLloguersadicionalPorLloguer($id);




$pdf = new FPDF();


function Footer($pdf)
{

$pdf->SetY(-20);

$pdf->SetFont('Arial','I',12);
$pdf->Cell(0,10,utf8_decode( 'PLAYA CANYELLES PETITES - ROSES - COSTA BRAVA - ESPAÑA'),0,0,'C');
$pdf->SetFont('Arial','I',10);
$pdf->Cell(0,20,'Pagina '.$pdf->PageNo()." - Fecha: ".date('Y-m-d'),0,0,'R');
}


$cantidadJugadores = 0;
#Establecemos los márgenes izquierda, arriba y derecha:
//$pdf->SetMargins(2, 2 , 2);

#Establecemos el margen inferior:
$pdf->SetAutoPageBreak(false,1);



	$pdf->AddPage();
	/***********************************    PRIMER CUADRANTE ******************************************/

	$pdf->Image('../imagenes/logo_casa_caliente.png',150,12,40);

	/***********************************    FIN ******************************************/



	//////////////////// Aca arrancan a cargarse los datos de los equipos  /////////////////////////


	$pdf->SetFillColor(183,183,183);
   $pdf->SetTextColor(110,110,110);
	$pdf->SetFont('Arial','B',26);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetY(12);
	$pdf->SetX(5);
	$pdf->Cell(140,13,'Apartaments Casa Caliente',0,0,'L',false);
   $pdf->SetFont('Arial','B',9);

   $pdf->Ln();
   $pdf->SetX(5);
   $pdf->Cell(140,5,'RENTING ; TERRAZA APARTMENTS - PISCINA - SOLARIUM - PARKING PRIVE - JARDIN',0,0,'L',false);
   $pdf->SetFont('Arial','B',8);

   $pdf->Ln();
   $pdf->SetX(5);
   $pdf->Cell(140,5,utf8_decode('Apartaments Casa Caliente - Apartado 431 - 17480 Roses - España.'),0,0,'L',false);

   $pdf->Ln();
   $pdf->SetX(5);
   $pdf->Cell(140,5,'Tel. +34.972.255650 Fax. +34.972.150611',0,0,'L',false);

   $pdf->Ln();
   $pdf->SetX(5);
   $pdf->Cell(140,5,'www.casacaliente.net - casacaliente@casacaliente.net',0,0,'L',false);

   $pdf->Ln();
   $pdf->Line(5, 47, 200, 47);

   $pdf->SetFont('Arial','B',14);
   $pdf->Ln();
   $pdf->SetX(5);
   $pdf->Cell(130,5,'CONTRATO Nº: '.$id,0,0,'L',false);

   $pdf->SetFont('Arial','',12);
   $pdf->SetTextColor(0,0,0);
   $pdf->setX(130);
   $pdf->Cell(70,5,utf8_decode(mysql_result($resLloguer,0,'nom').' '.mysql_result($resLloguer,0,'cognom')),0,0,'L',false);

   $pdf->Ln();
   $pdf->setX(130);
   $pdf->Cell(70,5,'NIF '.mysql_result($resLloguer,0,'nif'),0,0,'L',false);

   $pdf->Ln();
   $pdf->setX(130);
   $pdf->Cell(70,5,utf8_decode(mysql_result($resLloguer,0,'carrer')),0,0,'L',false);

   $pdf->Ln();
   $pdf->setX(130);
   $pdf->Cell(70,5,mysql_result($resLloguer,0,'codipostal').'  '.utf8_decode(mysql_result($resLloguer,0,'ciutat')),0,0,'L',false);

   $pdf->Ln();
   $pdf->setX(130);
   $pdf->Cell(70,5,utf8_decode(mysql_result($resLloguer,0,'pais')),0,0,'L',false);

   $pdf->Ln();
   $pdf->Ln();
   $pdf->setX(5);
   $pdf->Cell(70,5,'Estimado/a Sr. / Sra '.utf8_decode(mysql_result($resLloguer,0,'nom')),0,0,'L',false);

   $pdf->Ln();
   $pdf->Ln();
   $pdf->setX(5);
   $pdf->MultiCell(200,5,utf8_decode('Tenemos reservado para usted el apartamento N° '.mysql_result($resLloguer,0,'codapartament').' de '.mysql_result($resLloguer,0,'dormitorio').' dormitorio/s '.mysql_result($resLloguer,0,'dias').' dias, del '.mysql_result($resLloguer,0,'entradacorta').' (17h) hasta '.mysql_result($resLloguer,0,'sortidacorta').' (9h). Pueden pagar por transferencia bancaria'),0,'L',false);

	//$pdf->SetY($contadorY1);





$pdf->Ln();
$pdf->Ln();
$pdf->Ln();


Footer($pdf);



$nombreTurno = "CONTRACT-".$fecha.".pdf";

$pdf->Output($nombreTurno,'I');


?>
