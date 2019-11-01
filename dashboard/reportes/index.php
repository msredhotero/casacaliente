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
$serviciosSeguridad->seguridadRuta($_SESSION['refroll_sahilices'], '../reportes/');
//*** FIN  ****/

$fecha = date('Y-m-d');

//$resProductos = $serviciosProductos->traerProductosLimite(6);
$resMenu = $serviciosHTML->menu($_SESSION['nombre_sahilices'],"Reportes",$_SESSION['refroll_sahilices'],$_SESSION['email_sahilices']);

$configuracion = $serviciosReferencias->traerConfiguracion();

$tituloWeb = mysql_result($configuracion,0,'sistema');

$breadCumbs = '<a class="navbar-brand" href="../index.php">Dashboard</a>';

/////////////////////// Opciones pagina ///////////////////////////////////////////////
$singular = "Reportes";

$plural = "Reportes";

$eliminar = "";

$insertar = "";

$modificar = "";

//////////////////////// Fin opciones ////////////////////////////////////////////////


/////////////////////// Opciones para la creacion del formulario  /////////////////////
$tabla 			= "tbtipoubicacion";

$lblCambio	 	= array('tipoubicacion','reflocatarios');
$lblreemplazo	= array('Tipo Ubicacion','Empresas');

if ($_SESSION['idlocatario_sahilices'] == '') {
	$resVar1 = $serviciosReferencias->traerLocatarios();
} else {
	$resVar1 = $serviciosReferencias->traerLocatariosPorId($_SESSION['idlocatario_sahilices']);
}
$cadRef 	= $serviciosFunciones->devolverSelectBox($resVar1,array(1),'');


$refdescripcion = array(0=>$cadRef);
$refCampo 	=  array('reflocatarios');

$frmUnidadNegocios 	= $serviciosFunciones->camposTablaViejo($insertar ,$tabla,$lblCambio,$lblreemplazo,$refdescripcion,$refCampo);
//////////////////////////////////////////////  FIN de los opciones //////////////////////////

if ($_SESSION['idlocatario_sahilices'] == '') {
	$resVar1 = $serviciosReferencias->traerLocatarios();
} else {
	$resVar1 = $serviciosReferencias->traerLocatariosPorId($_SESSION['idlocatario_sahilices']);
}
$cadRef 	= $serviciosFunciones->devolverSelectBox($resVar1,array(1),'');


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

		.open ul li a { z-index: 999999999 !important; }
	</style>


</head>



<body class="theme-red">

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

<section class="content" style="margin-top:-75px;">

	<div class="container-fluid">
		<div class="row clearfix">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card ">
						<div class="header bg-blue">
							<h2>
								Llistat Taxa Per Apartament
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
							<form class="form" id="formRpt">

								<div class="row">
									<div class="form-group col-md-10">
										<label class="control-label" style="text-align:left" for="refcliente">Seleccione Empresa | Desde | Hasta | Baja</label>
										<div class="input-group col-md-12">
											<span class="input-group-addon">
												<select class="form-control" id="reflocatarios" name="reflocatarios" required />
												<?php echo $cadRef; ?>
												</select>
											</span>
											<span class="input-group-addon">
												<input type="date" class="form-control" name="desde" id="desde" />
											</span>
											<span class="input-group-addon">
											<input type="date" class="form-control" name="hasta" id="hasta" />
											</span>

										</div>
									</div>
									<div class="form-group col-md-2">
										<ul class="list-inline">
											<li>
												<button type="button" class="btn btn-success" id="rptListadoTaxa" style="margin-left:0px;">Generar</button>
											</li>
										</ul>

									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="card ">
						<div class="header bg-blue">
							<h2>
								FACTURES PER CLIENT SENSE TAXA
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
							<form class="form" id="formRpt">

								<div class="row">
									<div class="form-group col-md-10">
										<label class="control-label" style="text-align:left" for="refcliente">Seleccione Empresa | Any | Baja</label>
										<div class="input-group col-md-12">
											<span class="input-group-addon">
												<select class="form-control" id="reflocatarios2" name="reflocatarios2" required />
												<?php echo $cadRef; ?>
												</select>
											</span>
											<span class="input-group-addon">
												<select class="form-control show-tick" id="any2" name="any2" required />

												</select>
											</span>

										</div>
									</div>
									<div class="form-group col-md-2">
										<ul class="list-inline">
											<li>
												<button type="button" class="btn btn-success" id="rptFacturasPorCliente" style="margin-left:0px;">Generar</button>
											</li>
										</ul>

									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div><!-- fin del row clearfix -->
	</div><!-- fin del container-fluid -->
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


<script>
	$(document).ready(function(){
		$('#rptListadoTaxa').click(function() {
			if (($("#hasta").val() != '') && ($("#desde").val() != '')) {
				window.open("../../reportes/rptListaTaxaPorApartamento.php?idlocatario=" + $("#reflocatarios").val() + "&desde=" + $("#desde").val() + "&hasta=" + $("#hasta").val() ,'_blank');
			}

		});

		$('#rptFacturasPorCliente').click(function() {
			if ($("#any2").val() != '') {
				window.open("../../reportes/rptFacturasPorClienteSinTaxa.php?idlocatario=" + $("#reflocatarios").val() + "&any=" + $("#any2").val() + "&hasta=" + $("#hasta").val() ,'_blank');
			}

		});

		traerAnyPagos($('#reflocatarios2').val());

		$('#reflocatarios2').change(function() {
			traerAnyPagos($(this).val());
		});

		function traerAnyPagos(idlocatario) {
			$.ajax({
				url: '../../ajax/ajax.php',
				type: 'POST',
				// Form data
				//datos del formulario
				data: {
					accion: 'traerAniosPagos',
					idlocatario: idlocatario
				},
				//mientras enviamos el archivo
				beforeSend: function(){
					$('#any2').html('');
				},
				//una vez finalizado correctamente
				success: function(data){
					if (data.datos == '') {
						$('#any2').html('<option value="">No existen Anys con pagaments</option>');
						$('#any2').selectpicker('refresh');
					} else {
						$('#any2').html(data.datos);
						$('#any2').selectpicker('refresh');
					}

				},
				//si ha ocurrido un error
				error: function(){
					$(".alert").html('<strong>Error!</strong> Actualice la pagina');
					$("#load").html('');
				}
			});
		}
	});
</script>








</body>
<?php } ?>
</html>
