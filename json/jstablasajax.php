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

function armarAcciones($id,$label='',$class,$icon) {
	$cad = "";

	for ($j=0; $j<count($class); $j++) {
		$cad .= '<button type="button" class="btn '.$class[$j].' btn-circle waves-effect waves-circle waves-float '.$label[$j].'" id="'.$id.'">
				<i class="material-icons">'.$icon[$j].'</i>
			</button> ';
	}

	return $cad;
}

switch ($tabla) {
	case 'subidas':
		$resAjax = $serviciosReferencias->traerSubidasPorClienteajax($idcliente,$length, $start, $busqueda);
		$res = $serviciosReferencias->traerSubidasPorCliente($idcliente);
		if ($_SESSION['idroll_sahilices'] == 1) {
			$label = array('btnDescargarSubidas','btnEliminar');
			$class = array('bg-green','bg-red');
			$icon = array('file_download','delete');
		} else {
			$label = array('btnDescargarSubidas');
			$class = array('bg-green');
			$icon = array('file_download');
		}
		$indiceID = 0;
		$empieza = 1;
		$termina = 2;

		break;
	case 'facturas':
		$resAjax = $serviciosReferencias->traerFacturasPorClienteajax($idcliente,$length, $start, $busqueda);
		$res = $serviciosReferencias->traerFacturasPorCliente($idcliente);
		$label = array('btnDescargar');
		$class = array('bg-green');
		$icon = array('file_download');
		$indiceID = 0;
		$empieza = 1;
		$termina = 9;

		break;
	case 'facturasingresos':
		$resAjax = $serviciosReferencias->traerFacturasPorClienteTipoajax($idcliente,1,$length, $start, $busqueda);
		$res = $serviciosReferencias->traerFacturasPorClienteTipo($idcliente,1);
		$label = array('btnDescargar');
		$class = array('bg-green');
		$icon = array('file_download');
		$indiceID = 0;
		$empieza = 1;
		$termina = 3;

		break;
	case 'facturasgastos':
		$resAjax = $serviciosReferencias->traerFacturasPorClienteTipoajax($idcliente,2,$length, $start, $busqueda);
		$res = $serviciosReferencias->traerFacturasPorClienteTipo($idcliente,2);
		$label = array('btnDescargar');
		$class = array('bg-green');
		$icon = array('file_download');
		$indiceID = 0;
		$empieza = 1;
		$termina = 3;

		break;
	case 'facturastodas':
		$resAjax = $serviciosReferencias->traerFacturasajax($length, $start, $busqueda);
		$res = $serviciosReferencias->traerFacturas();
		$label = array('btnDescargar','btnModificar');
		$class = array('bg-green','bg-orange');
		$icon = array('file_download','edit');
		$indiceID = 0;
		$empieza = 1;
		$termina = 10;

		break;
	case 'clientes':
		$resAjax = $serviciosReferencias->traerClientesajax($length, $start, $busqueda);
		$res = $serviciosReferencias->traerClientes();
		$label = array('btnVer','btnModificar','btnEliminar','btnFoto','btnSubidas');
		$class = array('bg-riderz','bg-amber','bg-red','bg-green','bg-blue');
		$icon = array('backup','create','delete','assignment_ind','cloud_download');
		$indiceID = 0;
		$empieza = 1;
		$termina = 10;

		break;
	case 'usuarios':
		$resAjax = $serviciosUsuarios->traerUsuariosajax($length, $start, $busqueda);
		$res = $serviciosUsuarios->traerUsuarios();
		$label = array('btnModificar','btnEliminar','btnEnviar');
		$class = array('bg-amber','bg-red','bg-blue-grey');
		$icon = array('create','delete','vpn_key');
		$indiceID = 0;
		$empieza = 1;
		$termina = 5;
		break;
	case 'archivos':
		$resAjax = $serviciosReferencias->traerArchivosPorClienteajax($idcliente,$length, $start, $busqueda);
		$res = $serviciosReferencias->traerArchivosPorCliente($idcliente);
		if ($_SESSION['idroll_sahilices'] == 1) {
			$label = array('btnDescargar','btnEliminar');
			$class = array('bg-green','bg-red');
			$icon = array('file_download','delete');
		} else {
			$label = array('btnDescargar');
			$class = array('bg-green');
			$icon = array('file_download');
		}
		$indiceID = 0;
		$empieza = 1;
		$termina = 2;

		break;
	case 'categorias':
		$resAjax = $serviciosReferencias->traerCategoriasajax($length, $start, $busqueda);
		$res = $serviciosReferencias->traerCategorias();
		$label = array('btnModificar','btnEliminar');
		$class = array('bg-orange','bg-red');
		$icon = array('edit','delete');
		$indiceID = 0;
		$empieza = 1;
		$termina = 1;

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
			array_push($arAux, $row[$i]);
		}

		array_push($arAux, armarAcciones($row[0],$label,$class,$icon));

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
