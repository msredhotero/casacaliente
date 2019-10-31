<?php

session_start();

include ('../includes/funciones.php');
include ('../includes/funcionesReferencias.php');
include ('../includes/funcionesUsuarios.php');

$serviciosFunciones = new Servicios();
$serviciosReferencias 	= new ServiciosReferencias();
$serviciosUsuarios  		= new ServiciosUsuarios();

$tabla = $_GET['tabla'];
$draw = $_GET['sEcho'];
$start = $_GET['iDisplayStart'];
$length = $_GET['iDisplayLength'];
$busqueda = $_GET['sSearch'];

$idcliente = 0;

if (isset($_GET['idcliente'])) {
	$idcliente = $_GET['idcliente'];
} else {
	$idcliente = 0;
}


$referencia1 = 0;

if (isset($_GET['referencia1'])) {
	$referencia1 = $_GET['referencia1'];
} else {
	$referencia1 = 0;
}

$colSort = (integer)$_GET['iSortCol_0'] + 2;
$colSortDir = $_GET['sSortDir_0'];

function armarAcciones($id,$label='',$class,$icon) {
	$cad = "";

	for ($j=0; $j<count($class); $j++) {
		$cad .= '<button type="button" class="btn '.$class[$j].' btn-circle waves-effect waves-circle waves-float '.$label[$j].'" id="'.$id.'">
				<i class="material-icons">'.$icon[$j].'</i>
			</button> ';
	}

	return $cad;
}

function armarAccionesDropDown($id,$label='',$class,$icon) {
	$cad = '<div class="btn-group">
					<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						 Accions <span class="caret"></span>
					</button>
					<ul class="dropdown-menu">';

	for ($j=0; $j<count($class); $j++) {
		$cad .= '<li><a href="javascript:void(0);" id="'.$id.'" class=" waves-effect waves-block '.$label[$j].'">'.$icon[$j].'</a></li>';

	}

	$cad .= '</ul></div>';

	return $cad;
}

switch ($tabla) {
	case 'lloguers':
		if (($busqueda == '')) {
			//$colSort = 'l.entrada';
			//$colSortDir = 'desc';
		}

		if ($_SESSION['idlocatario_sahilices'] == '') {
			$resAjax = $serviciosReferencias->traerLloguersajax($length, $start, $busqueda,$colSort,$colSortDir);
			$res = $serviciosReferencias->traerLloguers();
			$termina = 7;
		} else {
			$resAjax = $serviciosReferencias->traerLloguersLocatarioajax($length, $start, $busqueda,$colSort,$colSortDir,$_SESSION['idlocatario_sahilices']);
			$res = $serviciosReferencias->traerLloguersPorLocatario($_SESSION['idlocatario_sahilices']);
			$termina = 6;
		}


		$label = array('btnCliente','btnModificar','btnEliminar','btnPagar','btnContratos','btnAgregarPersonas');
		$class = array('bg-blue','bg-amber','bg-red','bg-green','bg-brown','bg-teal');
		$icon = array('Client','Modificar','Eliminar','Pagos','Contrats','Persones');
		$indiceID = 0;
		$empieza = 1;

	break;
	case 'clientes':

		if ($_SESSION['idlocatario_sahilices'] == '') {
			$resAjax = $serviciosReferencias->traerClientesajax($length, $start, utf8_decode($busqueda),$colSort,$colSortDir);
			$res = $serviciosReferencias->traerClientes();
			$termina = 13;
		} else {
			$resAjax = $serviciosReferencias->traerClientesLocatarioajax($length, $start, utf8_decode($busqueda),$colSort,$colSortDir, $_SESSION['idlocatario_sahilices']);
			$res = $serviciosReferencias->traerClientesLocatario($_SESSION['idlocatario_sahilices']);
			$termina = 12;
		}

		$label = array('btnVer','btnModificar','btnEliminar');
		$class = array('bg-green','bg-amber','bg-red');
		$icon = array('Ver','Modificar','Eliminar');
		$indiceID = 0;
		$empieza = 1;


		break;
	case 'locatarios':
		$resAjax = $serviciosReferencias->traerLocatariosajax($length, $start, $busqueda,$colSort,$colSortDir);
		$res = $serviciosReferencias->traerLocatarios();
		$label = array('btnModificar','btnEliminar');
		$class = array('bg-amber','bg-red');
		$icon = array('create','delete');
		$indiceID = 0;
		$empieza = 1;
		$termina = 8;

		break;
	case 'tipoubicacion':

		if ($_SESSION['idlocatario_sahilices'] == '') {
			$resAjax = $serviciosReferencias->traerTipoubicacionajax($length, $start, $busqueda,$colSort,$colSortDir);
			$res = $serviciosReferencias->traerTipoubicacion();

			$termina = 2;
		} else {
			$resAjax = $serviciosReferencias->traerTipoubicacionLocatarioajax($length, $start, $busqueda,$colSort,$colSortDir,$_SESSION['idlocatario_sahilices']);
			$res = $serviciosReferencias->traerTipoubicacionPorLocatario($_SESSION['idlocatario_sahilices']);
			$termina = 1;
		}


		$label = array('btnModificar','btnEliminar');
		$class = array('bg-amber','bg-red');
		$icon = array('create','delete');
		$indiceID = 0;
		$empieza = 1;

	break;
	case 'ubicaciones':
		if ($_SESSION['idlocatario_sahilices'] == '') {
			$resAjax = $serviciosReferencias->traerUbicacionesajax($length, $start, $busqueda,$colSort,$colSortDir);
			$res = $serviciosReferencias->traerUbicaciones();

			$termina = 6;
		} else {
			$resAjax = $serviciosReferencias->traerUbicacionesLocatarioajax($length, $start, $busqueda,$colSort,$colSortDir,$_SESSION['idlocatario_sahilices']);
			$res = $serviciosReferencias->traerUbicacionesPorLocatario($_SESSION['idlocatario_sahilices']);
			$termina = 5;
		}

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-amber','bg-red');
		$icon = array('create','delete');
		$indiceID = 0;
		$empieza = 1;


	break;
	case 'tarifes':

		if ($_SESSION['idlocatario_sahilices'] == '') {
			$resAjax = $serviciosReferencias->traerTarifasajax($length, $start, $busqueda,$colSort,$colSortDir);
			$res = $serviciosReferencias->traerTarifas();

			$termina = 5;
		} else {
			$resAjax = $serviciosReferencias->traerTarifasLocatarioajax($length, $start, $busqueda,$colSort,$colSortDir,$_SESSION['idlocatario_sahilices']);
			$res = $serviciosReferencias->traerTarifasPorLocatario($_SESSION['idlocatario_sahilices']);
			$termina = 4;
		}

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-amber','bg-red');
		$icon = array('create','delete');
		$indiceID = 0;
		$empieza = 1;


	break;
	case 'periodes':

		if ($_SESSION['idlocatario_sahilices'] == '') {
			$resAjax = $serviciosReferencias->traerPeriodosajax($length, $start, $busqueda,$colSort,$colSortDir);
			$res = $serviciosReferencias->traerPeriodos();

			$termina = 5;
		} else {
			$resAjax = $serviciosReferencias->traerPeriodosLocatariosajax($length, $start, $busqueda,$colSort,$colSortDir,$_SESSION['idlocatario_sahilices']);
			$res = $serviciosReferencias->traerPeriodosPorLocatario($_SESSION['idlocatario_sahilices']);
			$termina = 4;
		}

		$label = array('btnModificar','btnEliminar');
		$class = array('bg-amber','bg-red');
		$icon = array('create','delete');
		$indiceID = 0;
		$empieza = 1;

	break;
	case 'usuarios':
		$resAjax = $serviciosUsuarios->traerUsuariosajax($length, $start, $busqueda,$colSort,$colSortDir);
		$res = $serviciosUsuarios->traerUsuarios();
		$label = array('btnModificar','btnEliminar');
		$class = array('bg-amber','bg-red');
		$icon = array('create','delete');
		$indiceID = 0;
		$empieza = 1;
		$termina = 6;

	break;

	default:
		// code...
		break;
}


$cantidadFilas = mysql_num_rows($res);


header("content-type: Access-Control-Allow-Origin: *");

$ar = array();
$arAux = array();
$cad = '';
$id = 0;
	while ($row = mysql_fetch_array($resAjax)) {
		//$id = $row[$indiceID];

		for ($i=$empieza;$i<=$termina;$i++) {
			array_push($arAux, utf8_encode($row[$i]));
		}

		if (($tabla == 'lloguers') || ($tabla == 'clientes')) {
			array_push($arAux, armarAccionesDropDown($row[0],$label,$class,$icon));
		} else {
			array_push($arAux, armarAcciones($row[0],$label,$class,$icon));
		}


		array_push($ar, $arAux);

		$arAux = array();
		//die(var_dump($ar));
	}

$cad = substr($cad, 0, -1);

$data = '{ "sEcho" : '.$draw.', "iTotalRecords" : '.$cantidadFilas.', "iTotalDisplayRecords" : 10, "aaData" : ['.$cad.']}';

//echo "[".substr($cad,0,-1)."]";
echo json_encode(array(
			"draw"            => $draw,
			"recordsTotal"    => $cantidadFilas,
			"recordsFiltered" => $cantidadFilas,
			"data"            => $ar
		));

?>
