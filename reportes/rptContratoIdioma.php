<?php

date_default_timezone_set('Europe/Madrid');

include ('../includes/funcionesUsuarios.php');
include ('../includes/funciones.php');
include ('../includes/funcionesHTML.php');
include ('../includes/funcionesReferencias.php');

define('EURO',chr(128));

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

$resTaxa = $serviciosReferencias->traerTaxa();

$taxaPer = mysql_result($resTaxa,0,1);
$taxaTur = mysql_result($resTaxa,0,2);
$taxaMax = mysql_result($resTaxa,0,3);

$resLloguer =  $serviciosReferencias->traerLloguersPorIdCompleto($id);

$resLloguerAdicional =  $serviciosReferencias->traerLloguersadicionalPorLloguer($id);

$resPagos = $serviciosReferencias->traerPagosPorLloguers($id);

$totalTaxaPersona = 0;

/*
if (mysql_result($resLloguer,0,'dias') < 7) {
	$totalTaxaPersona = mysql_result($resLloguer,0,'numpertax') * 1 * $taxaPer;
} else {
	$totalTaxaPersona = mysql_result($resLloguer,0,'numpertax') * mysql_result($resLloguer,0,'dias') / 7 * $taxaPer;
}
*/
//die(var_dump($taxaPer));


$taxaturisticaAdicional = 0;
$taxaturisticaAdicionalPersonas = 0;

/*
$taxaturisticaAdicional = 1 * mysql_result($resLloguer,0,'dias') * $taxaTur;

if ($taxaturisticaAdicional > $taxaMax) {
	$taxaturisticaAdicional  = mysql_result($resLloguer,0,'numpertax') * $taxaMax;
} else {
	$taxaturisticaAdicional = mysql_result($resLloguer,0,'numpertax') * mysql_result($resLloguer,0,'dias') * $taxaTur;
}
*/
//die(var_dump($taxaturisticaAdicional));

while ($rowAd = mysql_fetch_array($resLloguerAdicional)) {
	/*
	$totalTaxaTuristica = 1 * $rowAd['dias'] * $rowAd['taxaturistica'];

	if ($totalTaxaTuristica > $taxaMax) {
		$totalTaxaTuristica  = $rowAd['personas'] * $taxaMax;
	} else {
		$totalTaxaTuristica = $rowAd['personas'] * $rowAd['dias'] * $rowAd['taxaturistica'];
	}
	*/
	$taxaturisticaAdicionalPersonas += $rowAd['personas'];

	$taxaturisticaAdicional += $rowAd['taxaturistica'];

	if ($rowAd['taxapersona'] > $totalTaxaPersona) {
		$totalTaxaPersona = $rowAd['taxapersona'];
	}

	/*
	if ($rowAd['dias'] < 7) {
		$totalTaxaPersona += $rowAd['personas'] * 1 * mysql_result($resLloguer,0,'taxa');
	} else {
		$totalTaxaPersona += $rowAd['personas'] * $rowAd['dias'] / 7 * $rowAd['taxapersona'];
	}
	*/
}

$datalloguer	=	strtotime(mysql_result($resLloguer,0,'datalloguerdate'));
$fechaInicio	=	strtotime(mysql_result($resLloguer,0,'entrada'));
$fechaFin		=	strtotime(mysql_result($resLloguer,0,'sortida'));
$idlocatario   = mysql_result($resLloguer,0,'idlocatario');
$nrolloguer = mysql_result($resLloguer,0,'nrolloguer');

//die(var_dump($datalloguer));
$segundopago = strtotime ( '-30 day' ,  ( $fechaFin ) ) ;
$primerpago = strtotime ( '5 day' ,  ( $datalloguer ) ) ;

$periodoLbl = '';
$totalTarifa = 0;
$k = -1;
$dias = 0;

for($i=$fechaInicio+86400; $i<=$fechaFin; $i+=86400){
	$resPeriodo = $serviciosReferencias->calcularCoeficienteTarifa(mysql_result($resLloguer,0,'idtipoubicacion'),date("Y-m-d", $i));
	//die(var_dump(date("Y-m-d", $i)));
	//echo $resPeriodo['periodo'];
	if ($resPeriodo['periodo'] != $periodoLbl) {
		$totalTarifa = 0;
		$dias += 1;
		$k += 1;
		$periodoLbl = $resPeriodo['periodo'];
		$totalTarifa += $resPeriodo['tarifa'];
		$detalleLloguer[] = array(
			'periodo' => $resPeriodo['periodo'],
			$periodoLbl=> $totalTarifa,
			'precio'=> $resPeriodo['precio'],
			'dias'.$periodoLbl => $dias
		);
		$dias = 0;

	} else {
		$detalleLloguer[$k][$periodoLbl] += $resPeriodo['tarifa'];
		$detalleLloguer[$k]['dias'.$periodoLbl] += 1;
	}

}

if (isset($_GET['any'])) {
	$any = $_GET['any'];
} else {
	$any = date('Y',strtotime( mysql_result($resLloguer,0,'entrada')));
}

$resPeriodos = $serviciosReferencias->traerPeriodosPorOrdenPorAny($any,$idlocatario);
$resTipo =     $serviciosReferencias->traerTipoubicacionPorLocatario($idlocatario);

$countX = mysql_num_rows($resTipo);

if ($countX == 0) {
	$withX = 160 / 1;
} else {
	$withX = 160 / $countX;
}


//die(var_dump($withX));

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

switch ($idioma) {
	case 'espanol':
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
			$pdf->Cell(130,5,utf8_decode('CONTRATO Nº: ').$nrolloguer,0,0,'L',false);

			$pdf->SetFont('Arial','',11);
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

			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(70,5,'Atentamente, Apartaments Casa Caliente',0,0,'L',false);

			$pdf->Ln();
			$pdf->Line(5, 112, 200, 112);

			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(70,5,'Detalle del pago',0,0,'L',false);

			$pdf->SetFillColor(195,195,195);

			$pdf->Ln();
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(40,5,'PERIODO',0,0,'C',true);
			$pdf->Cell(40,5,'DIAS',0,0,'C',true);
			$pdf->Cell(40,5,'TARIFA',0,0,'C',true);
			$pdf->Cell(40,5,'PERSONAS',0,0,'C',true);
			$pdf->Cell(40,5,'PRECIO',0,0,'C',true);

			$totalTarifaParcial = 0;

			for ($j=0;$j<count($detalleLloguer);$j++) {
				//echo $j;
				$pdf->Ln();
				$pdf->setX(5);
				$pdf->Cell(40,5,$detalleLloguer[$j]['periodo'],0,0,'C',false);
				$pdf->Cell(40,5,$detalleLloguer[$j]['dias'.$detalleLloguer[$j]['periodo']],0,0,'C',false);
				$pdf->Cell(40,5,round($detalleLloguer[$j]['precio'],2).' '.EURO,0,0,'C',false);
				$pdf->Cell(40,5,mysql_result($resLloguer,0,'numpertax'),0,0,'C',false);
				$pdf->Cell(40,5,round($detalleLloguer[$j][$detalleLloguer[$j]['periodo']],2).' '.EURO,0,0,'C',false);

				$totalTarifaParcial += round($detalleLloguer[$j][$detalleLloguer[$j]['periodo']],2);
			}

			$pdf->SetFont('Arial','',10);
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(170,6,utf8_decode('Apart. N°').mysql_result($resLloguer,0,'codapartament').' x '.mysql_result($resLloguer,0,'dias').utf8_decode(' días = '),0,0,'R',false);
			$pdf->Cell(30,6,number_format( $totalTarifaParcial,2,',','.').' '.EURO,0,0,'R',false);

			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(170,6,utf8_decode('Nº personas. x Nº semanas x 20 = '),0,0,'R',false);
			$pdf->Cell(30,6,number_format( $totalTaxaPersona,2,',','.').' '.EURO,0,0,'R',false);

			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(170,6,utf8_decode('(Impuesto sobre estancias en establecimientos turísticos) '.$taxaturisticaAdicionalPersonas.' personas mayores de 16 años x '.mysql_result($resLloguer,0,'dias').' días x ').$taxaTur.EURO.' = ',0,0,'R',false);
			$pdf->Cell(30,6,number_format( $taxaturisticaAdicional,2,',','.').' '.EURO,0,0,'R',false);

			$pdf->SetFont('Arial','B',12);

			$pdf->Ln();
			$pdf->Line(170, $pdf->getY(), 205, $pdf->getY());

			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(170,6,utf8_decode('Suma total = '),0,0,'R',false);
			$pdf->Cell(30,6,number_format( $totalTarifaParcial + $totalTaxaPersona + $taxaturisticaAdicional,2,',','.').' '.EURO,0,0,'R',false);

			$pdf->Ln();
			$k = 0;
			$pdf->SetFont('Arial','',11);

			while ($row = mysql_fetch_array($resPagos))
			{
				$pagoTotalParcial = 0;
				$k += 1;
				$pdf->Ln();
				$pdf->setX(5);
				$pdf->Cell(200,6,$k.utf8_decode('º Pago de ').number_format( ($row['cuota'] + $row['taxa']),2,',','.').' '.EURO.' antes del dia '.date('d/m/Y',strtotime( $row['fechapago'])),0,0,'R',false);
				$pagoTotalParcial = $totalTarifaParcial + $totalTaxaPersona + $taxaturisticaAdicional - ($row['cuota'] + $row['taxa']);
			}

			if ($k == 0) {
				$pdf->Ln();
				$pdf->setX(5);
				$pdf->Cell(200,6,utf8_decode('1º Pago de ').number_format( $totalTarifaParcial + $totalTaxaPersona + $taxaturisticaAdicional + 7,2,',','.').' '.EURO.' antes del dia '.date('d/m/Y',$primerpago),0,0,'R',false);
				$pdf->Ln();
				$pdf->setX(5);
				$pdf->Cell(200,6,utf8_decode('2º Pago de ').number_format( $totalTarifaParcial + $totalTaxaPersona + $taxaturisticaAdicional - 7,2,',','.').' '.EURO.' antes del dia '.date('d/m/Y',$segundopago),0,0,'R',false);
			}

			if ($k == 1) {
				$pdf->Ln();
				$pdf->setX(5);
				$pdf->Cell(200,6,utf8_decode('2º Pago de ').number_format( $pagoTotalParcial,2,',','.').' '.EURO.' antes del dia '.date('d/m/Y',$segundopago),0,0,'R',false);
			}




			$pdf->SetFont('Arial','',9);
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(200,6,utf8_decode('(Posibles gastos bancarios a cargo del inquilino)'),0,0,'R',false);

			$pdf->SetFont('Arial','',11);
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(200,6,utf8_decode('Banco; Banco Sabadell, nr. 0081 0213 31 0001951501 a nombre de IJSSELSTEIN KROM CB. Roses'),0,0,'L',false);

			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(200,6,utf8_decode('IBAN: ES40 0081 0213 31 0001951501'),0,0,'L',false);

			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(200,6,utf8_decode('Nombre: IJSSELSTEIN KROM CB. Dirección: Rahola Molinas Nº5, 17480 Roses - España. NIF: E55293088'),0,0,'L',false);

			//$pdf->SetY($contadorY1);

		/******************************************************************************** fin primera pagina *********************/
		Footer($pdf);


		$pdf->AddPage();
		/***********************************    PRIMER CUADRANTE ******************************************/

		$pdf->Image('../imagenes/logo_casa_caliente.png',150,12,40);

		/***********************************    FIN ******************************************/



		//////////////////// Aca arrancan a cargarse los datos  /////////////////////////


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

		$pdf->SetFont('Arial','B',17);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->Cell(130,5,utf8_decode('PRECIOS Y CONDICIONES'),0,0,'L',false);


		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','',12);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(23,5,utf8_decode('Los precios'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(177,5,utf8_decode('incluyen el uso completo del inventario, sábanas una plaza de aparcamiento y de la piscina.'),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(180,5,utf8_decode('En invierno una estufa de gas butano o eléctrica'),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(28,5,utf8_decode('No se incluyen'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(172,5,utf8_decode('las toallas y trapos de cocina.'),0,0,'L',false);


		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(46,5,utf8_decode('Los periodos de alquiler'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(70,5,utf8_decode('empiezan el sábado de su llegada a las'),0,0,'L',false);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(10,5,utf8_decode('17 h'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(40,5,utf8_decode('y terminan el sábado de su salida'),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(9,5,utf8_decode('a las'),0,0,'L',false);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(10,5,utf8_decode('9h.'),0,0,'L',false);


		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(12,5,utf8_decode('Pago'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(35,5,utf8_decode('50 % en el plazo de'),0,0,'L',false);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(4,5,utf8_decode('5'),0,0,'C',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(151,5,utf8_decode(' dias después de recibir nuestra confirmación de reserva y el resto 30 dias'),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(200,5,utf8_decode('antes del comienzo del periodo de alquiler.'),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(200,5,utf8_decode('En caso de no cumplir con nuestras condiciones, nos reservamos el derecho de cancelar su reserva, sin'),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(200,5,utf8_decode('previo aviso.'),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,utf8_decode('Les aconsejamos que contraten un seguro de anulacion - vacaciones.'),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(200,5,utf8_decode('Animales de compañia, no se admiten.'),0,0,'L',false);


		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,utf8_decode('Precios por semana (I.V.A. incluido)'),1,0,'C',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',14);
		$pdf->MultiCell(40,5,utf8_decode('Temporada ').$any,1,'C',false);

		$yAux = $pdf->getY() - 10;

		$pdf->SetFont('Arial','B',9);
		$yC = 1;
		while ($rowY = mysql_fetch_array($resTipo)) {
			$pdf->setY($yAux);
			$pdf->setX(($yC * $withX) + 25);
			$pdf->MultiCell($withX,5,$rowY['tipoubicacion'],1,'C',false);
			$yC += 1;
		}


		while ($rowX = mysql_fetch_array($resPeriodos)) {

			$resTipoAux =    $serviciosReferencias->traerTipoubicacionPorLocatario($idlocatario);

			$pdf->setX(5);
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(10,5,$rowX['periodo'],1,0,'C',false);
			$pdf->Cell(30,5,date('d/m', strtotime( $rowX['desdeperiode'])).' - '.date('d/m', strtotime( $rowX['finsaperiode'])),1,0,'C',false);

			while ($rowY = mysql_fetch_array($resTipoAux)) {
				$resTarifa = $serviciosReferencias->traerTarifasPorPeriodoTipoUbicacion($rowX[0],$rowY[0]);
				if (mysql_num_rows($resTarifa)>0) {
					$pdf->Cell($withX,5,mysql_result($resTarifa,0,'tarifa'),1,0,'C',false);
				} else {
					$pdf->Cell($withX,5,'0',1,0,'C',false);
				}

			}
			$pdf->Ln();
		}

		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,utf8_decode('Suplemento por persona: 20 ').EURO.utf8_decode(' por persona y semana. (adulto, niño o bebé).'),1,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,utf8_decode('No se admiten animales de compañia.'),1,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,6,utf8_decode('A los precios indicados hay que añadir el impuesto sobre estancias en establecimientos turísticos, a '),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,6,utf8_decode('partir de 17 años: 0,90 ').EURO.utf8_decode(' pers / día con un máximo de 7 unidades de estancia por persona (= 6,30').EURO.utf8_decode(' / pers)'),0,0,'L',false);



		/************************************  fin de la tercer pagina *****************************************************************/

		Footer($pdf);
		$pdf->AddPage();
		/***********************************    PRIMER CUADRANTE ******************************************/

		$pdf->Image('../imagenes/logo_casa_caliente.png',150,12,40);

		/***********************************    FIN ******************************************/

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


		$pdf->SetTextColor(0,0,0);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetFont('Arial','',9);
		$pdf->SetX(5);
		$pdf->MultiCell(200,5,utf8_decode('De conformidad con lo establecido en el Art. 12.2 del R.D. 1720/2007, de 21 de diciembre, por el que se aprueba el Reglamento de desarrollo de la Ley Orgánica 15/1999, de 13 de diciembre, de Protección de Datos de carácter personal, Vd. queda informado y consiente expresamente que los datos de carácter personal que proporciona al rellenar el presente Contrato, serán incorporados a los ficheros de IJsselstein Krom C.B, con domicilio en Rahola Molinas 5, 17480 Roses, para que éste pueda efectuar el tratamiento, automatizado o no, de los mismos con la finalidad de recabar los datos básicos del cliente, prestando su consentimiento expreso para que dichos datos puedan ser comunicados para su utilización con los fines anteriores a otras Entidades. Así mismo, queda informado que podrá ejercer los derechos de acceso, rectificación, cancelación y oposición dirigiéndose a la dirección indicada anteriormente.'),0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();

		/** fin7 **/
	break;
	case 'aleman':
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
		$pdf->Cell(130,5,utf8_decode('AUFTRAG Nº: ').$id,0,0,'L',false);

		$pdf->SetFont('Arial','',11);
		$pdf->SetTextColor(0,0,0);
		$pdf->setX(130);
		$pdf->Cell(70,5,utf8_decode(mysql_result($resLloguer,0,'nom').' '.mysql_result($resLloguer,0,'cognom')),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(130);
		$pdf->Cell(70,5,utf8_decode('Nº Ausweises: ').mysql_result($resLloguer,0,'nif'),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(130);
		$pdf->Cell(70,5,utf8_decode(mysql_result($resLloguer,0,'carrer')),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(130);
		$pdf->Cell(70,5,mysql_result($resLloguer,0,'codipostal').'  '.(mysql_result($resLloguer,0,'ciutat')),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(130);
		$pdf->Cell(70,5,utf8_decode(mysql_result($resLloguer,0,'pais')),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(70,5,'Sehr geehrter Frau/Herr. '.utf8_decode(mysql_result($resLloguer,0,'nom')),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(70,5,utf8_decode('Vielen Dank für Ihre Buchungbestätigung. '),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->MultiCell(200,5,utf8_decode('Wir haben für Sie reserviert; ein '.mysql_result($resLloguer,0,'dormitorio').' Schlafzimmerwohnung Nr. '.mysql_result($resLloguer,0,'codapartament').' für die Periode, '.mysql_result($resLloguer,0,'entradacorta').' (17h) bis '.mysql_result($resLloguer,0,'sortidacorta').' (9h) das sind '.mysql_result($resLloguer,0,'dias').' Tage. Sie können den Betrag auf unser Bankkont in Roses überweisen mit angabe von Apt.Nr und Periode'),0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(70,5,utf8_decode('Wir raten Ihnen eine Ferien-versicherung abzuschliessen.'),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(70,5,utf8_decode('Mit freundlichen Grüssen, Apartaments Casa Caliente'),0,0,'L',false);

		$pdf->Ln();
		$pdf->Line(5, 112, 200, 112);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(70,5,'Zahlung',0,0,'L',false);

		$pdf->SetFillColor(195,195,195);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(40,5,'PERIODE',0,0,'C',true);
		$pdf->Cell(40,5,'TAGE',0,0,'C',true);
		$pdf->Cell(40,5,'PREISLISTE',0,0,'C',true);
		$pdf->Cell(40,5,'PERSONEN',0,0,'C',true);
		$pdf->Cell(40,5,'PREIS',0,0,'C',true);

		$totalTarifaParcial = 0;

		for ($j=0;$j<count($detalleLloguer);$j++) {
			//echo $j;
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(40,5,$detalleLloguer[$j]['periodo'],0,0,'C',false);
			$pdf->Cell(40,5,$detalleLloguer[$j]['dias'.$detalleLloguer[$j]['periodo']],0,0,'C',false);
			$pdf->Cell(40,5,round($detalleLloguer[$j]['precio'],2).' '.EURO,0,0,'C',false);
			$pdf->Cell(40,5,mysql_result($resLloguer,0,'numpertax'),0,0,'C',false);
			$pdf->Cell(40,5,round($detalleLloguer[$j][$detalleLloguer[$j]['periodo']],2).' '.EURO,0,0,'C',false);

			$totalTarifaParcial += round($detalleLloguer[$j][$detalleLloguer[$j]['periodo']],2);
		}

		$pdf->SetFont('Arial','',10);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(170,6,utf8_decode('Apart. N°').mysql_result($resLloguer,0,'codapartament').' x '.mysql_result($resLloguer,0,'dias').utf8_decode(' Tage = '),0,0,'R',false);
		$pdf->Cell(30,6,number_format( $totalTarifaParcial,2,',','.').' '.EURO,0,0,'R',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(170,6,utf8_decode('Nº Personen. x Nº Wochen x 20 = '),0,0,'R',false);
		$pdf->Cell(30,6,number_format( $totalTaxaPersona,2,',','.').' '.EURO,0,0,'R',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(170,6,utf8_decode('(Tax Aufenthalte in touristischen Unterkünften) '.$taxaturisticaAdicionalPersonas.' Personen über 16 Jahre x '.mysql_result($resLloguer,0,'dias').' Tage x ').$taxaTur.EURO.' = ',0,0,'R',false);
		$pdf->Cell(30,6,number_format( $taxaturisticaAdicional,2,',','.').' '.EURO,0,0,'R',false);

		$pdf->SetFont('Arial','B',12);

		$pdf->Ln();
		$pdf->Line(170, $pdf->getY(), 205, $pdf->getY());

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(170,6,utf8_decode('Gesamtbetrag = '),0,0,'R',false);
		$pdf->Cell(30,6,number_format( $totalTarifaParcial + $totalTaxaPersona + $taxaturisticaAdicional,2,',','.').' '.EURO,0,0,'R',false);

		$pdf->Ln();
		$k = 0;
		$pdf->SetFont('Arial','',11);
		while ($row = mysql_fetch_array($resPagos))
		{
			$k += 1;
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(200,6,$k.utf8_decode('º Zahlung ').number_format( ($row['cuota'] + $row['taxa']),2,',','.').' '.EURO.' bevor '.date('d/m/Y',strtotime( $row['fechapago'])),0,0,'R',false);
		}

		if ($k == 0) {
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(200,6,utf8_decode('1º Zahlung ').number_format( $totalTarifaParcial + $totalTaxaPersona + $taxaturisticaAdicional + 7,2,',','.').' '.EURO.' bevor '.date('d/m/Y',$primerpago),0,0,'R',false);
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(200,6,utf8_decode('2º Zahlung ').number_format( $totalTarifaParcial + $totalTaxaPersona + $taxaturisticaAdicional - 7,2,',','.').' '.EURO.' bevor '.date('d/m/Y',$segundopago),0,0,'R',false);
		}


		$pdf->SetFont('Arial','',9);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('Wir raten Ihnen, einen Stornierung-Reiseversicherung abschliessen (die Bankkosten gehen auf Rechnung der Mieter)'),0,0,'R',false);

		$pdf->SetFont('Arial','',11);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('Banco; Banco Sabadell, nr. 0081 0213 31 0001951501 a nombre de IJSSELSTEIN KROM CB. Roses'),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('IBAN: ES40 0081 0213 31 0001951501'),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('Nombre: IJSSELSTEIN KROM CB. Dirección: Rahola Molinas Nº5, 17480 Roses - España. NIF: E55293088'),0,0,'L',false);

		Footer($pdf);

		//$pdf->SetY($contadorY1);

		/******************************************************************************** fin primera pagina *********************/


		Footer($pdf);
		$pdf->AddPage();
		/***********************************    PRIMER CUADRANTE ******************************************/

		$pdf->Image('../imagenes/logo_casa_caliente.png',150,12,40);

		/***********************************    FIN ******************************************/



		//////////////////// Aca arrancan a cargarse los datos  /////////////////////////


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

		$pdf->SetFont('Arial','B',17);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->Cell(130,5,utf8_decode('Preise und Bedingungen'),0,0,'L',false);


		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','',12);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(35,5,utf8_decode('Genannte Preisen '),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(165,5,utf8_decode('Verstehen sich einschliesslich Benützung der gesamten Einrichtung, ein Parkplatz und'),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(180,5,utf8_decode('das Schwimmbad. Während der Wintermonate gehört ein Elec.ofen zur Einrichtung.'),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(50,5,utf8_decode('Service nicht vorhanden:'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(150,5,utf8_decode('Sind geschirrhandtücher und Badezimmer hand tücher. Keine Badetücher-service'),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(50,5,utf8_decode('Die Vermietungsperioden'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(150,5,utf8_decode('Beginnen immer Samstags und enden ebenso Samstags. Die Wonung steht'),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(180,5,utf8_decode('Ihnen Samstags ab 17 Uhr zur Verfügung und muss bis 9 Uhr. am Samstag der Abreise geraümt sein.'),0,0,'L',false);


		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(45,5,utf8_decode('Zahlungs Bedingungen'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(155,5,utf8_decode('50% innerhalb 5 Tage, nach Erhalt unser Vertrag, der Rest bis spätestens 21 Tag'),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(180,5,utf8_decode('vor Mietsanfang. Sollten Sie unsere Zahlungsbedingungen nicht erfüllen, sind wir berechtigt, Ihre Reservieru'),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(180,5,utf8_decode('ohne weiteres zu anulieren.'),0,0,'L',false);


		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,utf8_decode('Ferien-versicherung. Wir raten Ihnen eine Ferien-versicherung abzuschliessen.'),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,utf8_decode('Wir Akzeptierin Keine Haustiere.'),0,0,'L',false);


		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,utf8_decode('Preise in ').EURO.utf8_decode(' pro Woche. (M.W.S. inbegriffen)'),1,0,'C',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',14);
		$pdf->MultiCell(40,5,utf8_decode('Perioden Jahr ').$any,1,'C',false);

		$yAux = $pdf->getY() - 10;

		$pdf->SetFont('Arial','B',9);
		$yC = 1;

		$tipoUbacacionIdioma = '';
		$tipoUbacacionIdiomaCad = '';
		while ($rowY = mysql_fetch_array($resTipo)) {
			$tipoUbacacionIdioma = '';
			$tipoUbacacionIdioma = $serviciosReferencias->reemplazarIdioma($rowY['tipoubicacion'],$idioma);
			$tipoUbacacionIdiomaCad .= $tipoUbacacionIdioma.'<br>';
			$pdf->setY($yAux);
			$pdf->setX(($yC * $withX) + 25);
			$pdf->MultiCell($withX,5, utf8_decode($tipoUbacacionIdioma),1,'C',false);
			$yC += 1;
		}

		//die(var_dump($tipoUbacacionIdiomaCad));


		while ($rowX = mysql_fetch_array($resPeriodos)) {

			$resTipoAux =    $serviciosReferencias->traerTipoubicacionPorLocatario($idlocatario);

			$pdf->setX(5);
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(10,5,$rowX['periodo'],1,0,'C',false);
			$pdf->Cell(30,5,date('d/m', strtotime( $rowX['desdeperiode'])).' - '.date('d/m', strtotime( $rowX['finsaperiode'])),1,0,'C',false);

			while ($rowY = mysql_fetch_array($resTipoAux)) {
				$resTarifa = $serviciosReferencias->traerTarifasPorPeriodoTipoUbicacion($rowX[0],$rowY[0]);
				if (mysql_num_rows($resTarifa)>0) {
					$pdf->Cell($withX,5,mysql_result($resTarifa,0,'tarifa'),1,0,'C',false);
				} else {
					$pdf->Cell($withX,5,'0',1,0,'C',false);
				}

			}
			$pdf->Ln();
		}

		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,utf8_decode('Für Gas, Wasser, Electra und Bettwäsche rechnen wir 20 ').EURO.' pro P/p/ Woche.',1,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,utf8_decode('Wir akzeptierin keine Haustiere.'),1,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,6,utf8_decode('Tax Aufenthalte in touristischen Unterkünften von 17 Jahren: 0,90 ').EURO.' pro Person, pro Tag, mit ein',0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,6,utf8_decode('Maximum von 6,30 ').EURO.' pro Person pro Aufenthalt',0,0,'L',false);



		/************************************  fin de la tercer pagina *****************************************************************/

		Footer($pdf);
		$pdf->AddPage();
		/***********************************    PRIMER CUADRANTE ******************************************/

		$pdf->Image('../imagenes/logo_casa_caliente.png',150,12,40);

		/***********************************    FIN ******************************************/

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


		$pdf->SetTextColor(0,0,0);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetFont('Arial','',9);
		$pdf->SetX(5);
		$pdf->MultiCell(200,5,utf8_decode('In accordance with the provisions of the current regulations on Personal Data Protection, we inform you that the personal data provided by you will become part of IJSSELSTEIN KROM CB, Carrer Rahola Molina N/5, 17480 Roses,for the provision of the services you have hired us. Your data will not be transferred to third parties except legal obligation. Likewise, we inform you that your data will not be used for the automatic decision making, nor elaboration of profiles.All the personal data collected will be kept following the guidelines of the current tax legislation.From any of the previous sections, you can exercise the rights of access, rectification, limitation, deletion and opposition, indicating it in writing to the company .(Regulation (EU) 2016/679 of the European Parliament and of the Council of April 27, 2016).'),0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();

		/** fin7 **/
	break;
	case 'frances':
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
		$pdf->Cell(130,5,utf8_decode('CONTRAT Nº: ').$id,0,0,'L',false);

		$pdf->SetFont('Arial','',11);
		$pdf->SetTextColor(0,0,0);
		$pdf->setX(130);
		$pdf->Cell(70,5,utf8_decode(mysql_result($resLloguer,0,'nom').' '.mysql_result($resLloguer,0,'cognom')),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(130);
		$pdf->Cell(70,5,utf8_decode('Nº Identité ').mysql_result($resLloguer,0,'nif'),0,0,'L',false);

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
		$pdf->Cell(70,5,'Cher Mme./Mr '.utf8_decode(mysql_result($resLloguer,0,'nom')),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(70,5,utf8_decode("Nous vous remercions pour votre confirmation et de l'intérêt que vous portez à notre éstablissement."),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(70,5,utf8_decode('Il nous serait très agrèable de vous accueillir dans notre réridence "Apartments Casa Caliente".'),0,0,'L',false);


		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);

		$pdf->MultiCell(200,5,utf8_decode('Nous avons reservé pour vous; un appartement avec '.mysql_result($resLloguer,0,'dormitorio').' ch.à coucher. Nr '.mysql_result($resLloguer,0,'codapartament').' pour le periode '.mysql_result($resLloguer,0,'entradacorta').(" (17h) jusqu'à ").mysql_result($resLloguer,0,'sortidacorta')." (9h) c'est ".mysql_result($resLloguer,0,'dias').' nuits.'),0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(70,5,utf8_decode("Vous pouvez payer par virement bancaire. Nous n'acceptons pas les chèques."),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(70,5,utf8_decode("Dans l'attente du plaisir de vous accueillir, acceptez, Mme/Mr. l'expression de nos sincères salutations."),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(70,5,"Apartaments Casa Caliente",0,0,'L',false);


		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(70,5,'Paiement',0,0,'L',false);

		$pdf->SetFillColor(195,195,195);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(40,5,'PERIODE',0,0,'C',true);
		$pdf->Cell(40,5,'JOURS',0,0,'C',true);
		$pdf->Cell(40,5,'TARIF',0,0,'C',true);
		$pdf->Cell(40,5,'PERSONES',0,0,'C',true);
		$pdf->Cell(40,5,'PRIX',0,0,'C',true);

		$totalTarifaParcial = 0;

		for ($j=0;$j<count($detalleLloguer);$j++) {
			//echo $j;
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(40,5,$detalleLloguer[$j]['periodo'],0,0,'C',false);
			$pdf->Cell(40,5,$detalleLloguer[$j]['dias'.$detalleLloguer[$j]['periodo']],0,0,'C',false);
			$pdf->Cell(40,5,round($detalleLloguer[$j]['precio'],2).' '.EURO,0,0,'C',false);
			$pdf->Cell(40,5,mysql_result($resLloguer,0,'numpertax'),0,0,'C',false);
			$pdf->Cell(40,5,round($detalleLloguer[$j][$detalleLloguer[$j]['periodo']],2).' '.EURO,0,0,'C',false);

			$totalTarifaParcial += round($detalleLloguer[$j][$detalleLloguer[$j]['periodo']],2);
		}

		$pdf->SetFont('Arial','',10);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(170,6,utf8_decode('Apart. N°').mysql_result($resLloguer,0,'codapartament').' x '.mysql_result($resLloguer,0,'dias').utf8_decode(' nuits = '),0,0,'R',false);
		$pdf->Cell(30,6,number_format( $totalTarifaParcial,2,',','.').' '.EURO,0,0,'R',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(170,6,utf8_decode('Nr personnes. x Nr semaine x 20 = '),0,0,'R',false);
		$pdf->Cell(30,6,number_format( $totalTaxaPersona,2,',','.').' '.EURO,0,0,'R',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(170,6,utf8_decode('(Taxe de séjour en hébergement touristique) '.$taxaturisticaAdicionalPersonas.' personnes plus de 16 ans x '.mysql_result($resLloguer,0,'dias').' nuits x ').$taxaTur.EURO.' = ',0,0,'R',false);
		$pdf->Cell(30,6,number_format( $taxaturisticaAdicional,2,',','.').' '.EURO,0,0,'R',false);

		$pdf->SetFont('Arial','B',12);

		$pdf->Ln();
		$pdf->Line(170, $pdf->getY(), 205, $pdf->getY());

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(170,6,utf8_decode('Total = '),0,0,'R',false);
		$pdf->Cell(30,6,number_format( $totalTarifaParcial + $totalTaxaPersona + $taxaturisticaAdicional,2,',','.').' '.EURO,0,0,'R',false);

		$pdf->Ln();
		$k = 0;
		$pdf->SetFont('Arial','',11);
		while ($row = mysql_fetch_array($resPagos))
		{
			$k += 1;
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(200,6,$k.utf8_decode('º payement ').number_format( ($row['cuota'] + $row['taxa']),2,',','.').' '.EURO.' avant '.date('d/m/Y',strtotime( $row['fechapago'])),0,0,'R',false);
		}

		if ($k == 0) {
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(200,6,utf8_decode('1º payement ').number_format( $totalTarifaParcial + $totalTaxaPersona + $taxaturisticaAdicional + 7,2,',','.').' '.EURO.' avant '.date('d/m/Y',$primerpago),0,0,'R',false);
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(200,6,utf8_decode('2º payement ').number_format( $totalTarifaParcial + $totalTaxaPersona + $taxaturisticaAdicional - 7,2,',','.').' '.EURO.' avant '.date('d/m/Y',$segundopago),0,0,'R',false);
		}


		$pdf->SetFont('Arial','B',9);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('"Nous vous conseillons de souscrire une assurance de vacances"(Les éventuels frais bancaires à payer par le locataire)'),0,0,'R',false);

		$pdf->SetFont('Arial','',11);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('Banco; Banco Sabadell, nr. 0081 0213 31 0001951501 a nombre de IJSSELSTEIN KROM CB. Roses'),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('IBAN: ES40 0081 0213 31 0001951501'),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('Nombre: IJSSELSTEIN KROM CB. Dirección: Rahola Molinas Nº5, 17480 Roses - España. NIF: E55293088'),0,0,'L',false);

		//$pdf->SetY($contadorY1);

		/******************************************************************************** fin primera pagina *********************/

		Footer($pdf);

		$pdf->AddPage();
		/***********************************    PRIMER CUADRANTE ******************************************/

		$pdf->Image('../imagenes/logo_casa_caliente.png',150,12,40);

		/***********************************    FIN ******************************************/



		//////////////////// Aca arrancan a cargarse los datos  /////////////////////////


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

		$pdf->SetFont('Arial','B',17);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->Cell(130,5,utf8_decode('Tarifs et Conditions'),0,0,'L',false);


		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','',12);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(20,5,utf8_decode('Les prix'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(177,5,utf8_decode("Comportent l´usage des utiles de l'inventaire complet, un parking et la piscine prive. Pendant les moi"),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,5,utf8_decode("d'hiver, un chauffage à gaz et electric figure sur l'inventaire."),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(55,5,utf8_decode("Il ia un maximum d'ocupation"),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(150,5,utf8_decode('pour les appartements 1 cham. 2 pers, 2 cham. 4 pers, 3 cham. 6 pers.'),0,0,'L',false);
		$pdf->SetFont('Arial','B',11);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,5,utf8_decode("adult ou enfant-bebe."),0,0,'L',false);


		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(23,5,utf8_decode("Sont exclus"),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(150,5,utf8_decode('Les torchons et les serviettes éponges. Pas de service de torchons et serviettes éponges.'),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(39,5,utf8_decode("Toutes les périodes"),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(150,5,utf8_decode("De location commencent le samedi et expirent aussi le samedi.Le samedi de l'arrivé,"),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,5,utf8_decode("l'appartement est à votre disposition à 17 heures et doit être liberé, le samedi de votre départ 9 heures."),0,0,'L',false);


		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(21,5,utf8_decode('Payement'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(177,5,utf8_decode("50% 5 jours après de la réception du contrat. Le reste 21 jours au moins avant le commencement"),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,5,utf8_decode("de votre période de location. En cas de non respecter les conditions de payement, nous nous reservons le"),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,5,utf8_decode("droit de résilier le contrat."),0,0,'L',false);


		$pdf->SetFont('Arial','B',11);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,5,utf8_decode("Nous vous conseillons de souscrire une assurance de vacances."),0,0,'L',false);

		$pdf->SetFont('Arial','B',11);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,5,utf8_decode("Les Animaux ne sont pas admis dans nos résidences."),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,utf8_decode('Prix par semaine (Taxes comprises)'),1,0,'C',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',14);
		$pdf->MultiCell(40,5,utf8_decode('Périodes ANS ').$any,1,'C',false);

		$yAux = $pdf->getY() - 10;

		$pdf->SetFont('Arial','B',9);
		$yC = 1;

		$tipoUbacacionIdioma = '';
		while ($rowY = mysql_fetch_array($resTipo)) {
			$tipoUbacacionIdioma = $serviciosReferencias->reemplazarIdioma($rowY['tipoubicacion'],$idioma);
			$pdf->setY($yAux);
			$pdf->setX(($yC * $withX) + 25);
			$pdf->MultiCell($withX,5,$tipoUbacacionIdioma,1,'C',false);
			$yC += 1;
		}


		while ($rowX = mysql_fetch_array($resPeriodos)) {

			$resTipoAux =    $serviciosReferencias->traerTipoubicacionPorLocatario($idlocatario);

			$pdf->setX(5);
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(10,5,$rowX['periodo'],1,0,'C',false);
			$pdf->Cell(30,5,date('d/m', strtotime( $rowX['desdeperiode'])).' - '.date('d/m', strtotime( $rowX['finsaperiode'])),1,0,'C',false);

			while ($rowY = mysql_fetch_array($resTipoAux)) {
				$resTarifa = $serviciosReferencias->traerTarifasPorPeriodoTipoUbicacion($rowX[0],$rowY[0]);
				if (mysql_num_rows($resTarifa)>0) {
					$pdf->Cell($withX,5,mysql_result($resTarifa,0,'tarifa'),1,0,'C',false);
				} else {
					$pdf->Cell($withX,5,'0',1,0,'C',false);
				}

			}
			$pdf->Ln();
		}

		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,utf8_decode("Pour gas, électri. de l'eau et les draps, vous payez 20 ").EURO.utf8_decode(' p/p/p/sem. adult ou enfant - Bebe.'),1,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,utf8_decode('Les Animaux ne sont pas admis dans nos résidences.'),1,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,6,utf8_decode('A los precios indicados hay que añadir el impuesto sobre estancias en establecimientos turísticos, a '),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,6,utf8_decode('Taxe de séjour en en hébergement touristique, à partir de 17 ans, 0,90 ').EURO.utf8_decode('  par personne et par jour,'),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,6,utf8_decode('avec un maximum de 6,30 ').EURO.utf8_decode(' par personne par séjour.'),0,0,'L',false);



		/************************************  fin de la tercer pagina *****************************************************************/

		Footer($pdf);
		$pdf->AddPage();
		/***********************************    PRIMER CUADRANTE ******************************************/

		$pdf->Image('../imagenes/logo_casa_caliente.png',150,12,40);

		/***********************************    FIN ******************************************/

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


		$pdf->SetTextColor(0,0,0);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetFont('Arial','',9);
		$pdf->SetX(5);
		$pdf->MultiCell(200,5,utf8_decode('In accordance with the provisions of the current regulations on Personal Data Protection, we inform you that the personal data provided by you will become part of IJSSELSTEIN KROM CB, Carrer Rahola Molina N/5, 17480 Roses,for the provision of the services you have hired us. Your data will not be transferred to third parties except legal obligation. Likewise, we inform you that your data will not be used for the automatic decision making, nor elaboration of profiles.All the personal data collected will be kept following the guidelines of the current tax legislation.From any of the previous sections, you can exercise the rights of access, rectification, limitation, deletion and opposition, indicating it in writing to the company .(Regulation (EU) 2016/679 of the European Parliament and of the Council of April 27, 2016).'),0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();

		/** fin7 **/
	break;
	case 'ingles':
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
		$pdf->Cell(130,5,utf8_decode('RENTAL AGREEMENT Nº: ').$id,0,0,'L',false);

		$pdf->SetFont('Arial','',11);
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
		$pdf->Cell(70,5,'Dear Mrs/Mr. '.utf8_decode(mysql_result($resLloguer,0,'nom')),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(70,5,'Thank you very much for your booking confirmation.',0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->MultiCell(200,5,utf8_decode('We have reserved for you a '.mysql_result($resLloguer,0,'dormitorio').' bedroom-apartment. Nr. '.mysql_result($resLloguer,0,'codapartament').' for the period, '.mysql_result($resLloguer,0,'entradacorta').' (17h) till '.mysql_result($resLloguer,0,'sortidacorta').' (9h), that is for '.mysql_result($resLloguer,0,'dias').' days. You can pay by bank.'),0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(70,5,'Should you require any further information, please do not hesitate to contact us',0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(70,5,'Looking forward to welcoming you, Yours Sincerely, Apartaments Casa Caliente',0,0,'L',false);



		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(70,5,'Rental agreement details',0,0,'L',false);

		$pdf->SetFillColor(195,195,195);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(40,5,'PERIOD',0,0,'C',true);
		$pdf->Cell(40,5,'DAYS',0,0,'C',true);
		$pdf->Cell(40,5,'PRICE LIST',0,0,'C',true);
		$pdf->Cell(40,5,'PERSONS',0,0,'C',true);
		$pdf->Cell(40,5,'PRICE',0,0,'C',true);

		$totalTarifaParcial = 0;

		for ($j=0;$j<count($detalleLloguer);$j++) {
			//echo $j;
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(40,5,$detalleLloguer[$j]['periodo'],0,0,'C',false);
			$pdf->Cell(40,5,$detalleLloguer[$j]['dias'.$detalleLloguer[$j]['periodo']],0,0,'C',false);
			$pdf->Cell(40,5,round($detalleLloguer[$j]['precio'],2).' '.EURO,0,0,'C',false);
			$pdf->Cell(40,5,mysql_result($resLloguer,0,'numpertax'),0,0,'C',false);
			$pdf->Cell(40,5,round($detalleLloguer[$j][$detalleLloguer[$j]['periodo']],2).' '.EURO,0,0,'C',false);

			$totalTarifaParcial += round($detalleLloguer[$j][$detalleLloguer[$j]['periodo']],2);
		}

		$pdf->SetFont('Arial','',10);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(170,6,utf8_decode('Apart. N°').mysql_result($resLloguer,0,'codapartament').' x '.mysql_result($resLloguer,0,'dias').utf8_decode(' days = '),0,0,'R',false);
		$pdf->Cell(30,6,number_format( $totalTarifaParcial,2,',','.').' '.EURO,0,0,'R',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(170,6,utf8_decode('Nº persons. x Nº weeks x 20 = '),0,0,'R',false);
		$pdf->Cell(30,6,number_format( $totalTaxaPersona,2,',','.').' '.EURO,0,0,'R',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(170,6,utf8_decode('(Tax on stays in tourist establishments) '.$taxaturisticaAdicionalPersonas.' people older than 16 years x '.mysql_result($resLloguer,0,'dias').' days ').$taxaTur.EURO.' = ',0,0,'R',false);
		$pdf->Cell(30,6,number_format( $taxaturisticaAdicional,2,',','.').' '.EURO,0,0,'R',false);

		$pdf->SetFont('Arial','B',12);

		$pdf->Ln();
		$pdf->Line(170, $pdf->getY(), 205, $pdf->getY());

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(170,6,utf8_decode('Total amount = '),0,0,'R',false);
		$pdf->Cell(30,6,number_format( $totalTarifaParcial + $totalTaxaPersona + $taxaturisticaAdicional,2,',','.').' '.EURO,0,0,'R',false);

		$pdf->Ln();
		$k = 0;
		$pdf->SetFont('Arial','',11);
		while ($row = mysql_fetch_array($resPagos))
		{
			$k += 1;
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(200,6,$k.utf8_decode('º payment ').number_format( ($row['cuota'] + $row['taxa']),2,',','.').' '.EURO.' before '.date('d/m/Y',strtotime( $row['fechapago'])),0,0,'R',false);
		}

		if ($k == 0) {
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(200,6,utf8_decode('1º payment ').number_format( $totalTarifaParcial + $totalTaxaPersona + $taxaturisticaAdicional + 7,2,',','.').' '.EURO.' before '.date('d/m/Y',$primerpago),0,0,'R',false);
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(200,6,utf8_decode('2º payment ').number_format( $totalTarifaParcial + $totalTaxaPersona + $taxaturisticaAdicional - 7,2,',','.').' '.EURO.' before '.date('d/m/Y',$segundopago),0,0,'R',false);
		}


		$pdf->SetFont('Arial','',9);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('(Possible commissions at the expense of the tenant.)'),0,0,'R',false);

		$pdf->SetFont('Arial','',11);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('Banco; Banco Sabadell, nr. 0081 0213 31 0001951501 a nombre de IJSSELSTEIN KROM CB. Roses'),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('IBAN: ES40 0081 0213 31 0001951501'),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('Nombre: IJSSELSTEIN KROM CB. Dirección: Rahola Molinas Nº5, 17480 Roses - España. NIF: E55293088'),0,0,'L',false);

		//$pdf->SetY($contadorY1);

		/******************************************************************************** fin primera pagina *********************/
		Footer($pdf);


		$pdf->AddPage();
		/***********************************    PRIMER CUADRANTE ******************************************/

		$pdf->Image('../imagenes/logo_casa_caliente.png',150,12,40);

		/***********************************    FIN ******************************************/



		//////////////////// Aca arrancan a cargarse los datos  /////////////////////////


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

		$pdf->SetFont('Arial','B',17);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->Cell(130,5,utf8_decode('Prices and Conditions'),0,0,'L',false);


		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','',12);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(50,5,utf8_decode('The mentioned prices'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(150,5,utf8_decode('Are inclusive the use of the complete inventory, one parking and the swimmingpools'),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(180,5,utf8_decode('During the wintermonths a buta-gas and Elect. stove is provided for heating'),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(28,5,utf8_decode('Not included'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(172,5,utf8_decode('Are towels and kitchen-towels. No towels and kitchen-towels service.'),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(28,5,utf8_decode('Rentperiods'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(172,5,utf8_decode('Begin on Saturday and end on Saturday, occupation of an apartment to commence a 5 pm'),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(180,5,utf8_decode('on the day of arrival and to terminate a 9 am on the day of departure.'),0,0,'L',false);


		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(28,5,utf8_decode('Payment'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(172,5,utf8_decode('50 % within 5 days after receiving our notice of reservation, the remaining 50 % to be pai 21 days'),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(180,5,utf8_decode('before the beginning of the rentperiod.'),0,0,'L',false);


		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(200,5,utf8_decode('In case of not complying with our conditions, we have the right to cancel the reservation, without further notice'),0,0,'L',false);


		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,utf8_decode('Pets are not permitted in our residences.'),0,0,'L',false);


		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,utf8_decode('Prices in ').EURO.(' per week (Including V.A.T.)'),1,0,'C',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',14);
		$pdf->MultiCell(40,5,utf8_decode('SEASON YEAR ').$any,1,'C',false);

		$yAux = $pdf->getY() - 10;

		$pdf->SetFont('Arial','B',9);
		$yC = 1;

		$tipoUbacacionIdioma = '';
		while ($rowY = mysql_fetch_array($resTipo)) {
			$tipoUbacacionIdioma = $serviciosReferencias->reemplazarIdioma($rowY['tipoubicacion'],$idioma);
			$pdf->setY($yAux);
			$pdf->setX(($yC * $withX) + 25);
			$pdf->MultiCell($withX,5,$tipoUbacacionIdioma,1,'C',false);
			$yC += 1;
		}


		while ($rowX = mysql_fetch_array($resPeriodos)) {

			$resTipoAux =    $serviciosReferencias->traerTipoubicacionPorLocatario($idlocatario);

			$pdf->setX(5);
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(10,5,$rowX['periodo'],1,0,'C',false);
			$pdf->Cell(30,5,date('d/m', strtotime( $rowX['desdeperiode'])).' - '.date('d/m', strtotime( $rowX['finsaperiode'])),1,0,'C',false);

			while ($rowY = mysql_fetch_array($resTipoAux)) {
				$resTarifa = $serviciosReferencias->traerTarifasPorPeriodoTipoUbicacion($rowX[0],$rowY[0]);
				if (mysql_num_rows($resTarifa)>0) {
					$pdf->Cell($withX,5,mysql_result($resTarifa,0,'tarifa'),1,0,'C',false);
				} else {
					$pdf->Cell($withX,5,'0',1,0,'C',false);
				}

			}
			$pdf->Ln();
		}

		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,'For using gas, water, electricity and sheets we charge 20 '.EURO.' per person/week.',1,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,utf8_decode('Pets are not permitted in our residences.'),1,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,6,'Supplementary tax on stays in tourist establishments from 17 years: 0,90 '.EURO.' per person, per day, with a ',0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,6,'maximum o 6,30'.EURO.' per person per stay.',0,0,'L',false);



		/************************************  fin de la tercer pagina *****************************************************************/

		Footer($pdf);
		$pdf->AddPage();
		/***********************************    PRIMER CUADRANTE ******************************************/

		$pdf->Image('../imagenes/logo_casa_caliente.png',150,12,40);

		/***********************************    FIN ******************************************/

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


		$pdf->SetTextColor(0,0,0);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetFont('Arial','',9);
		$pdf->SetX(5);
		$pdf->MultiCell(200,5,utf8_decode('In accordance with the provisions of the current regulations on Personal Data Protection, we inform you that the personal data provided by you will become part of IJSSELSTEIN KROM CB, Carrer Rahola Molina N/5, 17480 Roses,for the provision of the services you have hired us. Your data will not be transferred to third parties except legal obligation. Likewise, we inform you that your data will not be used for the automatic decision making, nor elaboration of profiles.All the personal data collected will be kept following the guidelines of the current tax legislation.From any of the previous sections, you can exercise the rights of access, rectification, limitation, deletion and opposition, indicating it in writing to the company .(Regulation (EU) 2016/679 of the European Parliament and of the Council of April 27, 2016).'),0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();

		/** fin7 **/
	break;
	case 'holandes':
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
		$pdf->Cell(130,5,utf8_decode('CONTRACT Nº: ').$id,0,0,'L',false);

		$pdf->SetFont('Arial','',11);
		$pdf->SetTextColor(0,0,0);
		$pdf->setX(130);
		$pdf->Cell(70,5,utf8_decode(mysql_result($resLloguer,0,'nom').' '.mysql_result($resLloguer,0,'cognom')),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(130);
		$pdf->Cell(70,5,'Paspoort Nr: '.mysql_result($resLloguer,0,'nif'),0,0,'L',false);

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
		$pdf->Cell(70,5,'Geachte Heer/Mevr. '.utf8_decode(mysql_result($resLloguer,0,'nom')),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(140,5,'Bedankt voor Uw bevestiging.',0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->MultiCell(200,5,utf8_decode('Wij hebben voor U gereserveerd ; een '.mysql_result($resLloguer,0,'dormitorio').' slaapkamer woning, Nr. '.mysql_result($resLloguer,0,'codapartament').' voor de periode, '.mysql_result($resLloguer,0,'entradacorta').' (17h) tot '.mysql_result($resLloguer,0,'sortidacorta').' (9h) dat is '.mysql_result($resLloguer,0,'dias').' nachten. U kunt het bedrag overmaken op onze bank met vermelding van Nr. appartement en periode'),0,'L',false);


		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(70,5,'Met vriendelijke groeten, Apartaments Casa Caliente',0,0,'L',false);

		$pdf->SetFont('Arial','B',12);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(70,5,'Betaling',0,0,'L',false);

		$pdf->SetFont('Arial','',11);
		$pdf->SetFillColor(195,195,195);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(40,5,'PERIODE',0,0,'C',true);
		$pdf->Cell(40,5,'NACHTEN',0,0,'C',true);
		$pdf->Cell(40,5,'HUURPRIJZEN',0,0,'C',true);
		$pdf->Cell(40,5,'PERSONEN',0,0,'C',true);
		$pdf->Cell(40,5,'PRIJS',0,0,'C',true);

		$totalTarifaParcial = 0;

		for ($j=0;$j<count($detalleLloguer);$j++) {
			//echo $j;
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(40,5,$detalleLloguer[$j]['periodo'],0,0,'C',false);
			$pdf->Cell(40,5,$detalleLloguer[$j]['dias'.$detalleLloguer[$j]['periodo']],0,0,'C',false);
			$pdf->Cell(40,5,round($detalleLloguer[$j]['precio'],2).' '.EURO,0,0,'C',false);
			$pdf->Cell(40,5,mysql_result($resLloguer,0,'numpertax'),0,0,'C',false);
			$pdf->Cell(40,5,round($detalleLloguer[$j][$detalleLloguer[$j]['periodo']],2).' '.EURO,0,0,'C',false);

			$totalTarifaParcial += round($detalleLloguer[$j][$detalleLloguer[$j]['periodo']],2);
		}

		$pdf->SetFont('Arial','',10);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(170,6,utf8_decode('Apt. N°').mysql_result($resLloguer,0,'codapartament').' x '.mysql_result($resLloguer,0,'dias').utf8_decode(' nachten = '),0,0,'R',false);
		$pdf->Cell(30,6,number_format( $totalTarifaParcial,2,',','.').' '.EURO,0,0,'R',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(170,6,utf8_decode('Nr personen. x Nr weken x 20 = '),0,0,'R',false);
		$pdf->Cell(30,6,number_format( $totalTaxaPersona,2,',','.').' '.EURO,0,0,'R',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(170,6,utf8_decode('(Belasting verblijf in toeristische accommodatie) '.$taxaturisticaAdicionalPersonas.' personen ouder dan 16 jaar '.mysql_result($resLloguer,0,'dias').' nach x ').$taxaTur.EURO.' = ',0,0,'R',false);
		$pdf->Cell(30,6,number_format( $taxaturisticaAdicional,2,',','.').' '.EURO,0,0,'R',false);

		$pdf->SetFont('Arial','B',12);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(170,6,utf8_decode('Totaal = '),0,0,'R',false);
		$pdf->Cell(30,6,number_format( $totalTarifaParcial + $totalTaxaPersona + $taxaturisticaAdicional,2,',','.').' '.EURO,0,0,'R',false);

		$pdf->Ln();
		$k = 0;
		$pdf->SetFont('Arial','',11);
		while ($row = mysql_fetch_array($resPagos))
		{
			$k += 1;
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(200,6,$k.utf8_decode('º betaling ').number_format( ($row['cuota'] + $row['taxa']),2,',','.').' '.EURO.' voor '.date('d/m/Y',strtotime( $row['fechapago'])),0,0,'R',false);
		}

		if ($k == 0) {
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(200,6,utf8_decode('1º betaling ').number_format( $totalTarifaParcial + $totalTaxaPersona + $taxaturisticaAdicional + 7,2,',','.').' '.EURO.' voor '.date('d/m/Y',$primerpago),0,0,'R',false);
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(200,6,utf8_decode('2º betaling ').number_format( $totalTarifaParcial + $totalTaxaPersona + $taxaturisticaAdicional - 7,2,',','.').' '.EURO.' voor '.date('d/m/Y',$segundopago),0,0,'R',false);
		}


		$pdf->SetFont('Arial','',9);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('(Eventuele Bankkosten voor rekening v/d huurder)'),0,0,'R',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('Wij wijzen U op de annulerings-verzekering bij Uw bank.'),0,0,'R',false);

		$pdf->SetFont('Arial','',11);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('Banco; Banco Sabadell, nr. 0081 0213 31 0001951501 a nombre de IJSSELSTEIN KROM CB. Roses'),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('IBAN: ES40 0081 0213 31 0001951501'),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('Nombre: IJSSELSTEIN KROM CB. Dirección: Rahola Molinas Nº5, 17480 Roses - España. NIF: E55293088'),0,0,'L',false);

		//$pdf->SetY($contadorY1);

		/******************************************************************************** fin primera pagina *********************/

		Footer($pdf);

		$pdf->AddPage();
		/***********************************    PRIMER CUADRANTE ******************************************/

		$pdf->Image('../imagenes/logo_casa_caliente.png',150,12,40);

		/***********************************    FIN ******************************************/



		//////////////////// Aca arrancan a cargarse los datos  /////////////////////////


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

		$pdf->SetFont('Arial','B',17);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->Cell(130,5,utf8_decode('Tarieven en voorwaarden'),0,0,'L',false);


		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','',12);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(35,5,utf8_decode('Genoemde prijzen'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(165,5,utf8_decode('Zijn inclusief gebruik van de complete inventaris, een parkeerplaats en het zwembad'),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,5,utf8_decode('Gedurende de wintermaanden behoort een electr. Kachel en butagaskachel, ok is er in div.appartementen'),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,5,utf8_decode('een openhaard aanwezig.'),0,0,'L',false);


		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(32,5,utf8_decode('Niet beschikbaar'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(170,5,utf8_decode('Zijn theedoeken en badkamer handdooeken.Geen theedoeken en badkamer handdoeke'),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,5,utf8_decode('pakket service.'),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(34,5,utf8_decode('Alle huurperioden'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(170,5,utf8_decode('Beginnen op Zaterdag en eindigen ook op Zaterdag. Uw woning staat op de dag van'),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,5,utf8_decode('aankomst om 17 uur te beschikking en dient op de dag van vertrek om 9 uur te zijn ontruimd.'),0,0,'L',false);


		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(18,5,utf8_decode('Betaling'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(165,5,utf8_decode('50 % binnen 5 dagen, na ontvangst van ons contract, restant uiterlijk 21 dagen voor ingangsdatum'),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,5,utf8_decode('van de huurperiode. Bij niet voldoen van deze voorwaarden,kunnen wij Uw reservering annuleren.'),0,0,'L',false);


		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(200,5,utf8_decode('Wij wijzen U op de annulerings-verzekering bij Uw bank.'),0,0,'L',false);


		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,utf8_decode('Huisdieren niet Toegestaan.'),0,0,'L',false);


		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,utf8_decode('Huurprijzen in').EURO.' per week (incl btw)',1,0,'C',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',14);
		$pdf->MultiCell(40,5,utf8_decode('Perioden Jaar ').$any,1,'C',false);

		$yAux = $pdf->getY() - 10;

		$pdf->SetFont('Arial','B',9);
		$yC = 1;
		while ($rowY = mysql_fetch_array($resTipo)) {
			$tipoubicacionIdioma = $serviciosReferencias->reemplazarIdioma($rowY['tipoubicacion'],$idioma);
			$pdf->setY($yAux);
			$pdf->setX(($yC * $withX) + 25);
			$pdf->MultiCell($withX,5,$tipoubicacionIdioma,1,'C',false);
			$yC += 1;
		}


		while ($rowX = mysql_fetch_array($resPeriodos)) {

			$resTipoAux =    $serviciosReferencias->traerTipoubicacionPorLocatario($idlocatario);

			$pdf->setX(5);
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(10,5,$rowX['periodo'],1,0,'C',false);
			$pdf->Cell(30,5,date('d/m', strtotime( $rowX['desdeperiode'])).' - '.date('d/m', strtotime( $rowX['finsaperiode'])),1,0,'C',false);

			while ($rowY = mysql_fetch_array($resTipoAux)) {
				$resTarifa = $serviciosReferencias->traerTarifasPorPeriodoTipoUbicacion($rowX[0],$rowY[0]);
				if (mysql_num_rows($resTarifa)>0) {
					$pdf->Cell($withX,5,mysql_result($resTarifa,0,'tarifa'),1,0,'C',false);
				} else {
					$pdf->Cell($withX,5,'0',1,0,'C',false);
				}

			}
			$pdf->Ln();
		}

		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,'Voor gas, water, licht en lakens rekenen wij 20 '.EURO.' per persoon per week.',1,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,'Huisdieren niet Toegestaan.',1,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,6,'Bijkomende Belasting verblijf in toeristische accommodatie vanaf 17 jaar: '.EURO.' per persoon, per dag',0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,6,'met en maximaal van 6,30 '.EURO.' per persoon per verblijf',0,0,'L',false);



		/************************************  fin de la tercer pagina *****************************************************************/

		Footer($pdf);

		$pdf->AddPage();
		/***********************************    PRIMER CUADRANTE ******************************************/

		$pdf->Image('../imagenes/logo_casa_caliente.png',150,12,40);

		/***********************************    FIN ******************************************/

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


		$pdf->SetTextColor(0,0,0);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetFont('Arial','',9);
		$pdf->SetX(5);
		$pdf->MultiCell(200,5,utf8_decode('In accordance with the provisions of the current regulations on Personal Data Protection, we inform you that the personal data provided by you will become part of IJSSELSTEIN KROM CB, Carrer Rahola Molina N/5, 17480 Roses,for the provision of the services you have hired us. Your data will not be transferred to third parties except legal obligation. Likewise, we inform you that your data will not be used for the automatic decision making, nor elaboration of profiles.All the personal data collected will be kept following the guidelines of the current tax legislation.From any of the previous sections, you can exercise the rights of access, rectification, limitation, deletion and opposition, indicating it in writing to the company .(Regulation (EU) 2016/679 of the European Parliament and of the Council of April 27, 2016).'),0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();

		/** fin7 **/
	break;
	case 'catalan':
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
		$pdf->Cell(130,5,utf8_decode('CONTRACTE Nº: ').$id,0,0,'L',false);

		$pdf->SetFont('Arial','',11);
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
		$pdf->Cell(70,5,'Estimat/da Sr. / Sra '.utf8_decode(mysql_result($resLloguer,0,'nom')),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->MultiCell(200,5,utf8_decode("Tenim reservat per vostè l' apartament Nº ".mysql_result($resLloguer,0,'codapartament').' de '.mysql_result($resLloguer,0,'dormitorio').' dormitori/s '.mysql_result($resLloguer,0,'dias').' dies, del '.mysql_result($resLloguer,0,'entradacorta').' (17h) fins '.mysql_result($resLloguer,0,'sortidacorta').' (9h). Poden pagar mitjançant una transferència bancària'),0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(70,5,'Atentamente, Apartaments Casa Caliente',0,0,'L',false);

		$pdf->Ln();
		$pdf->Line(5, 112, 200, 112);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(70,5,'Detall pagament',0,0,'L',false);

		$pdf->SetFillColor(195,195,195);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(40,5,'PERIODE',0,0,'C',true);
		$pdf->Cell(40,5,'DIES',0,0,'C',true);
		$pdf->Cell(40,5,'TARIFA',0,0,'C',true);
		$pdf->Cell(40,5,'PERSONES',0,0,'C',true);
		$pdf->Cell(40,5,'PREU',0,0,'C',true);

		$totalTarifaParcial = 0;

		for ($j=0;$j<count($detalleLloguer);$j++) {
			//echo $j;
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(40,5,$detalleLloguer[$j]['periodo'],0,0,'C',false);
			$pdf->Cell(40,5,$detalleLloguer[$j]['dias'.$detalleLloguer[$j]['periodo']],0,0,'C',false);
			$pdf->Cell(40,5,round($detalleLloguer[$j]['precio'],2).' '.EURO,0,0,'C',false);
			$pdf->Cell(40,5,mysql_result($resLloguer,0,'numpertax'),0,0,'C',false);
			$pdf->Cell(40,5,round($detalleLloguer[$j][$detalleLloguer[$j]['periodo']],2).' '.EURO,0,0,'C',false);

			$totalTarifaParcial += round($detalleLloguer[$j][$detalleLloguer[$j]['periodo']],2);
		}

		$pdf->SetFont('Arial','',10);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(170,6,utf8_decode('Apart. N°').mysql_result($resLloguer,0,'codapartament').' x '.mysql_result($resLloguer,0,'dias').utf8_decode(' dies = '),0,0,'R',false);
		$pdf->Cell(30,6,number_format( $totalTarifaParcial,2,',','.').' '.EURO,0,0,'R',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(170,6,utf8_decode('Nº persones. x Nº setmanes x 20 = '),0,0,'R',false);
		$pdf->Cell(30,6,number_format( $totalTaxaPersona,2,',','.').' '.EURO,0,0,'R',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(170,6,utf8_decode('(Impost sobre estades en establiements turístics) '.$taxaturisticaAdicionalPersonas.' persones majors de 16 anys x '.mysql_result($resLloguer,0,'dias').' estades x ').$taxaTur.EURO.' = ',0,0,'R',false);
		$pdf->Cell(30,6,number_format( $taxaturisticaAdicional,2,',','.').' '.EURO,0,0,'R',false);

		$pdf->SetFont('Arial','B',12);

		$pdf->Ln();
		$pdf->Line(170, $pdf->getY(), 205, $pdf->getY());

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(170,6,utf8_decode('Suma total = '),0,0,'R',false);
		$pdf->Cell(30,6,number_format( $totalTarifaParcial + $totalTaxaPersona + $taxaturisticaAdicional,2,',','.').' '.EURO,0,0,'R',false);

		$pdf->Ln();
		$k = 0;
		$pdf->SetFont('Arial','',11);
		while ($row = mysql_fetch_array($resPagos))
		{
			$k += 1;
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(200,6,$k.utf8_decode('º Pagament de ').number_format( ($row['cuota'] + $row['taxa']),2,',','.').' '.EURO.' abans del dia '.date('d/m/Y',strtotime( $row['fechapago'])),0,0,'R',false);
		}

		if ($k == 0) {
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(200,6,utf8_decode('1º Pagament de ').number_format( $totalTarifaParcial + $totalTaxaPersona + $taxaturisticaAdicional + 7,2,',','.').' '.EURO.' abans del dia '.date('d/m/Y',$primerpago),0,0,'R',false);
			$pdf->Ln();
			$pdf->setX(5);
			$pdf->Cell(200,6,utf8_decode('2º Pagament de ').number_format( $totalTarifaParcial + $totalTaxaPersona + $taxaturisticaAdicional - 7,2,',','.').' '.EURO.' abans del dia '.date('d/m/Y',$segundopago),0,0,'R',false);
		}


		$pdf->SetFont('Arial','',9);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('(Posibles gastos bancarios a cargo del inquilino)'),0,0,'R',false);

		$pdf->SetFont('Arial','',11);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('Banco; Banco Sabadell, nr. 0081 0213 31 0001951501 a nombre de IJSSELSTEIN KROM CB. Roses'),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('IBAN: ES40 0081 0213 31 0001951501'),0,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(200,6,utf8_decode('Nombre: IJSSELSTEIN KROM CB. Dirección: Rahola Molinas Nº5, 17480 Roses - España. NIF: E55293088'),0,0,'L',false);

		//$pdf->SetY($contadorY1);

		/******************************************************************************** fin primera pagina *********************/

		Footer($pdf);

		$pdf->AddPage();
		/***********************************    PRIMER CUADRANTE ******************************************/

		$pdf->Image('../imagenes/logo_casa_caliente.png',150,12,40);

		/***********************************    FIN ******************************************/



		//////////////////// Aca arrancan a cargarse los datos  /////////////////////////


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

		$pdf->SetFont('Arial','B',17);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetX(5);
		$pdf->Cell(130,5,utf8_decode('PREUS I CONDICIONS'),0,0,'L',false);


		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('Arial','',12);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(18,5,utf8_decode('El preu'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(177,5,utf8_decode("u inclou l'us complet de l'inventari, llençols, una plaça d'aparcament i les piscines."),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(180,5,utf8_decode("Durant l'hivern estufes de gas i elèctriques."),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(28,5,utf8_decode("No s'inclouen"),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(172,5,utf8_decode('les tovalloles i draps de cuina.'),0,0,'L',false);


		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(40,5,utf8_decode('El període de lloguer'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(80,5,utf8_decode('comença el dissabte de la seva arribada a les'),0,0,'L',false);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(10,5,utf8_decode('17 h'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(35,5,utf8_decode('i acaba el dissabte de la seva '),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->Cell(22,5,utf8_decode('sortida a las'),0,0,'L',false);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(10,5,utf8_decode('9h.'),0,0,'L',false);


		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','BU',11);
		$pdf->Cell(19,5,utf8_decode('Pagament'),0,0,'L',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(29,5,utf8_decode('50 % durant els'),0,0,'L',false);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(4,5,utf8_decode('5'),0,0,'C',false);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(151,5,utf8_decode(' dies següents a la recepció del contracte de lloguer, i la resta,21 dies abans'),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(200,5,utf8_decode('de la seva arribada.'),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(200,5,utf8_decode('En el cas de no complir amb les nostres condicions, ens reservem el dret de cancelar la seva reserva sense'),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(200,5,utf8_decode('previ avís.'),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,utf8_decode("Aconsellem que contractin una asseguraça d'anulació de vacances."),0,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','',11);
		$pdf->Cell(200,5,utf8_decode("No s'admeten animals de companyia."),0,0,'L',false);


		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,utf8_decode('Preus per semana (I.V.A. inclòs)'),1,0,'C',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',14);
		$pdf->MultiCell(40,5,utf8_decode('TEMPORADA ').$any,1,'C',false);

		$yAux = $pdf->getY() - 10;

		$pdf->SetFont('Arial','B',9);
		$yC = 1;
		while ($rowY = mysql_fetch_array($resTipo)) {
			$pdf->setY($yAux);
			$pdf->setX(($yC * $withX) + 25);
			$pdf->MultiCell($withX,5,$rowY['tipoubicacion'],1,'C',false);
			$yC += 1;
		}


		while ($rowX = mysql_fetch_array($resPeriodos)) {

			$resTipoAux =    $serviciosReferencias->traerTipoubicacionPorLocatario($idlocatario);

			$pdf->setX(5);
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(10,5,$rowX['periodo'],1,0,'C',false);
			$pdf->Cell(30,5,date('d/m', strtotime( $rowX['desdeperiode'])).' - '.date('d/m', strtotime( $rowX['finsaperiode'])),1,0,'C',false);

			while ($rowY = mysql_fetch_array($resTipoAux)) {
				$resTarifa = $serviciosReferencias->traerTarifasPorPeriodoTipoUbicacion($rowX[0],$rowY[0]);
				if (mysql_num_rows($resTarifa)>0) {
					$pdf->Cell($withX,5,mysql_result($resTarifa,0,'tarifa'),1,0,'C',false);
				} else {
					$pdf->Cell($withX,5,'0',1,0,'C',false);
				}

			}
			$pdf->Ln();
		}

		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,'Suplement per persona (nens i nadons incl.) 20 '.EURO.' per persona i semana.',1,0,'L',false);

		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,5,"No s'admeten animals de companyia.",1,0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,6,utf8_decode("Als preus indicats s'hi ha d'afegir l'impost sobre estades en establiments turístics (IEET), a partir de"),0,0,'L',false);
		$pdf->Ln();
		$pdf->setX(5);
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(200,6,utf8_decode("de 17 anys 0,90 ").EURO.utf8_decode(" pers./dia amb un màxim de 7 unitats d'estada per persona (= 6,30 ").EURO.utf8_decode(" / persona x estada)"),0,0,'L',false);



		/************************************  fin de la tercer pagina *****************************************************************/

		Footer($pdf);
		$pdf->AddPage();
		/***********************************    PRIMER CUADRANTE ******************************************/

		$pdf->Image('../imagenes/logo_casa_caliente.png',150,12,40);

		/***********************************    FIN ******************************************/

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


		$pdf->SetTextColor(0,0,0);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetFont('Arial','',9);
		$pdf->SetX(5);
		$pdf->MultiCell(200,5,utf8_decode("De conformitat amb el que estableix l'Art. 12.2 del R.D. 1720/2007, de 21 de desembre, pel qual s'aprova el Reglament de desenvolupament de la Llei Orgànica 15/1999, de 13 de desembre, de Protecció de Dades de caràcter personal, Vostè queda informat i consent expressament que les dades de caràcter personal que proporciona en emplenar el present Contracte, seran incorporades als fitxers de IJsselstein Krom CB, amb domicili a Rahola Molinas 5, 17480 Roses, perquè aquest pugui efectuar el tractament, automatitzat o no, dels mateixos amb la finalitat de recaptar les dades bàsiques del client, prestant el seu consentiment exprés perquè aquestes dades puguin ser comunicades per a la seva utilització amb els fins anteriors a altres entitats. Així mateix, queda informat que podrà exercir els drets d'accés, rectificació, cancel•lació i oposició dirigint-se a l'adreça indicada anteriorment."),0,'L',false);

		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();

		/** fin7 **/
	break;
}




Footer($pdf);



$nombreTurno = "CONTRACT-".$fecha.".pdf";

$pdf->Output($nombreTurno,'I');


?>
