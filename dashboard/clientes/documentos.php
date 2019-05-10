<?php


session_start();

if (!isset($_SESSION['usua_sahilices']))
{
	header('Location: ../../error.php');
} else {


include ('../../includes/funciones.php');
include ('../../includes/funcionesUsuarios.php');
include ('../../includes/funcionesHTML.php');
include ('../../includes/funcionesReferencias.php');
include ('../../includes/base.php');

$serviciosFunciones 	= new Servicios();
$serviciosUsuario 		= new ServiciosUsuarios();
$serviciosHTML 			= new ServiciosHTML();
$serviciosReferencias 	= new ServiciosReferencias();
$baseHTML = new BaseHTML();

//*** SEGURIDAD ****/
include ('../../includes/funcionesSeguridad.php');
$serviciosSeguridad = new ServiciosSeguridad();
$serviciosSeguridad->seguridadRuta($_SESSION['refroll_sahilices'], '../clientes/');
//*** FIN  ****/

$fecha = date('Y-m-d');

$id = $_GET['id'];

//$resProductos = $serviciosProductos->traerProductosLimite(6);
$resMenu = $serviciosHTML->menu($_SESSION['nombre_sahilices'],"Clientes",$_SESSION['refroll_sahilices'],$_SESSION['email_sahilices']);

$configuracion = $serviciosReferencias->traerConfiguracion();

$tituloWeb = mysql_result($configuracion,0,'sistema');

$breadCumbs = '<a class="navbar-brand" href="../index.php">Dashboard</a>';

/////////////////////// Opciones pagina ///////////////////////////////////////////////
$singular = "Documentos";

$plural = "Documentos";

$eliminar = "eliminarArchivos";

$insertar = "insertarArchivos";

$modificar = "modificarArchivos";

//////////////////////// Fin opciones ////////////////////////////////////////////////

$token = $serviciosReferencias->GUID();

/////////////////////// Opciones para la creacion del formulario  /////////////////////
$tabla 			= "dbarchivos";

$lblCambio	 	= array("refclientes","refcategorias","anio");
$lblreemplazo	= array("Cliente","Categorias","Año");

if ($_SESSION['idroll_sahilices'] != 3) {
	$idcliente = $_GET['id'];
} else {
	$idcliente = $_SESSION['idcliente'];
}


$refClientes = $serviciosReferencias->traerClientesPorId($idcliente);
$cadRef = $serviciosFunciones->devolverSelectBox($refClientes,array(1,2,3),' ');

$refCate = $serviciosReferencias->traerCategorias();
$cadRef2 = $serviciosFunciones->devolverSelectBox($refCate,array(1),' ');

$cadMes = '<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>';

$cadAnio = '';
for ($i=date('Y');$i>=1980;$i--) {
	$cadAnio .= '<option value="'.$i.'">'.$i.'</option>';
}
//die(var_dump($cadAnio));

$refdescripcion = array(0 => $cadRef, 1=>$cadRef2, 2=>$cadMes,3=>$cadAnio);
$refCampo 	=  array("refclientes","refcategorias","mes","anio");



$frm 	= $serviciosFunciones->camposTablaViejo($insertar ,$tabla,$lblCambio,$lblreemplazo,$refdescripcion,$refCampo);
//////////////////////////////////////////////  FIN de los opciones //////////////////////////


$resultado = $serviciosReferencias->traerClientesPorId($idcliente);


?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title><?php echo $tituloWeb; ?></title>
	<!-- Favicon-->
	<link rel="icon" href="../../favicon.ico" type="image/x-icon">

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

	<?php echo $baseHTML->cargarArchivosCSS('../../'); ?>

	<link href="../../plugins/waitme/waitMe.css" rel="stylesheet" />
	<link href="../../plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

	<!-- Bootstrap Material Datetime Picker Css -->
	<link href="../../plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

	<!-- Dropzone Css -->
	<link href="../../plugins/dropzone/dropzone.css" rel="stylesheet">


	<link rel="stylesheet" href="../../DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="../../DataTables/DataTables-1.10.18/css/dataTables.bootstrap.css">
	<link rel="stylesheet" href="../../DataTables/DataTables-1.10.18/css/dataTables.jqueryui.min.css">
	<link rel="stylesheet" href="../../DataTables/DataTables-1.10.18/css/jquery.dataTables.css">

	<style>
		.alert > i{ vertical-align: middle !important; }
	</style>


</head>



<body class="theme-purple">

<!-- Page Loader -->
<div class="page-loader-wrapper">
	<div class="loader">
		<div class="preloader">
			<div class="spinner-layer pl-red">
				<div class="circle-clipper left">
					<div class="circle"></div>
				</div>
				<div class="circle-clipper right">
					<div class="circle"></div>
				</div>
			</div>
		</div>
		<p>Cargando...</p>
	</div>
</div>
<!-- #END# Page Loader -->
<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<!-- #END# Overlay For Sidebars -->
<!-- Search Bar -->
<div class="search-bar">
	<div class="search-icon">
		<i class="material-icons">search</i>
	</div>
	<input type="text" placeholder="Ingrese palabras...">
	<div class="close-search">
		<i class="material-icons">close</i>
	</div>
</div>
<!-- #END# Search Bar -->
<!-- Top Bar -->
<?php echo $baseHTML->cargarNAV($breadCumbs); ?>
<!-- #Top Bar -->
<?php echo $baseHTML->cargarSECTION($_SESSION['usua_sahilices'], $_SESSION['nombre_sahilices'], $resMenu,'../../'); ?>

<section class="content" style="margin-top:-15px;">

	<div class="container-fluid">
		<div class="row clearfix">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card ">
						<div class="header bg-riderz">
							<h2 style="color:#fff;">
								<?php echo strtoupper($singular); ?>: <?php echo strtoupper(mysql_result($resultado,0,'apellido')); ?> <?php echo strtoupper(mysql_result($resultado,0,'nombre')); ?>
							</h2>
							<ul class="header-dropdown m-r--5">
								<li class="dropdown">
									<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
										<i class="material-icons">more_vert</i>
									</a>
									<ul class="dropdown-menu pull-right">

									</ul>
								</li>
							</ul>
						</div>
						<div class="body">

								<div class="row demo-masked-input">
									<div class="col-xs-6 col-md-6 col-lg-6">
										<h4>Foto DNI / NIE Frente</h4>
										<a href="javascript:void(0);" class="thumbnail imgfrente">
											<img class="img-responsive">
										</a>

										<form action="subirfrente.php" id="frmFileUploadFrente" class="dropzone" method="post" enctype="multipart/form-data">
											<div class="dz-message">
												<div class="drag-icon-cph">
													<i class="material-icons">touch_app</i>
												</div>
												<h3>Arrastre y suelte una imagen aqui o haga click y busque una imagen en su ordenador.</h3>

											</div>
											<div class="fallback">
												<input name="file" type="file" id="archivosfrente" />
												<input type="hidden" id="idcliente" name="idcliente" value="<?php echo $id; ?>" />

											</div>
										</form>
									</div>

									<div class="col-xs-6 col-md-6 col-lg-6">
										<h4>Foto DNI / NIE Dorsal</h4>
										<a href="javascript:void(0);" class="thumbnail imgdorsal">
											<img class="img-responsive">
										</a>

										<form action="subirdorsal.php" id="frmFileUploadDorsal" class="dropzone" method="post" enctype="multipart/form-data">
											<div class="dz-message">
												<div class="drag-icon-cph">
													<i class="material-icons">touch_app</i>
												</div>
												<h3>Arrastre y suelte una imagen aqui o haga click y busque una imagen en su ordenador.</h3>

											</div>
											<div class="fallback">
												<input name="file" type="file" id="archivosdorsal" />
												<input type="hidden" id="idcliente" name="idcliente" value="<?php echo $id; ?>" />


											</div>
										</form>
									</div>
								</div>




						</div>
					</div>
				</div>

			</div>


		</div>


	</div>
</section>






<?php echo $baseHTML->cargarArchivosJS('../../'); ?>
<!-- Wait Me Plugin Js -->
<script src="../../plugins/waitme/waitMe.js"></script>

<!-- Custom Js -->
<script src="../../js/pages/cards/colored.js"></script>

<script src="../../plugins/jquery-validation/jquery.validate.js"></script>

<script src="../../js/pages/examples/sign-in.js"></script>

<!-- Bootstrap Material Datetime Picker Plugin Js -->
<script src="../../plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

<script src="../../DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>

<!-- Bootstrap Material Datetime Picker Plugin Js -->
<script src="../../plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

<!-- Dropzone Plugin Js -->
<script src="../../plugins/dropzone/dropzone.js"></script>


<script>

	function traerImagen(tipo, contenedor) {
	$.ajax({
		data:  {idcliente: <?php echo $id; ?>,
				tipo: tipo,
				accion: 'traerImgenCliente'},
		url:   '../../ajax/ajax.php',
		type:  'post',
		beforeSend: function () {

		},
		success:  function (response) {

			$("."+contenedor).attr("src",response.imagen);

		}
	});
	}

	traerImagen(1,'imgfrente img');
	traerImagen(2,'imgdorsal img');




	Dropzone.prototype.defaultOptions.dictFileTooBig = "Este archivo es muy grande ({{filesize}}MiB). Peso Maximo: {{maxFilesize}}MiB.";

	Dropzone.options.frmFileUploadFrente = {
		maxFilesize: 30,
		acceptedFiles: ".png,.jpg,.gif,.bmp,.jpeg",
		accept: function(file, done) {
			done();
		},
		init: function() {
			this.on("sending", function(file, xhr, formData){
					formData.append("idcliente", '<?php echo $id; ?>');
			});
			this.on('success', function( file, resp ){
				traerImagen(1,'imgfrente img');
				swal("Correcto!", resp.replace("1", ""), "success");

			});

			this.on('error', function( file, resp ){
				swal("Error!", resp.replace("1", ""), "warning");
			});
		}
	};

	Dropzone.options.frmFileUploadDorsal = {
		maxFilesize: 30,
		acceptedFiles: ".png,.jpg,.gif,.bmp,.jpeg",
		accept: function(file, done) {
			done();
		},
		init: function() {
			this.on("sending", function(file, xhr, formData){
					formData.append("idcliente", '<?php echo $id; ?>');
			});
			this.on('success', function( file, resp ){
				traerImagen(2,'imgdorsal img');
				swal("Correcto!", resp.replace("1", ""), "success");

			});

			this.on('error', function( file, resp ){
				swal("Error!", resp.replace("1", ""), "warning");
			});
		}
	};

	var myDropzone = new Dropzone("#archivosfrente", {
		params: {
			 idcliente: <?php echo $id; ?>
		},
		url: 'subirfrente.php'
	});

	var myDropzone = new Dropzone("#archivosdorsal", {
		params: {
			 idcliente: <?php echo $id; ?>
		},
		url: 'subirdorsal.php'
	});


	$(document).ready(function(){


	});
</script>








</body>
<?php } ?>
</html>
