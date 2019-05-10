<?php

session_start();

if (!isset($_SESSION['usua_sahilices']))
{
	header('Location: ../../error.php');
} else {


include ('../../includes/funcionesUsuarios.php');
include ('../../includes/funcionesHTML.php');
include ('../../includes/funciones.php');
include ('../../includes/funcionesReferencias.php');
include ('../../includes/base.php');

$serviciosUsuario = new ServiciosUsuarios();
$serviciosHTML = new ServiciosHTML();
$serviciosFunciones = new Servicios();
$serviciosReferencias 	= new ServiciosReferencias();
$baseHTML = new BaseHTML();

//*** SEGURIDAD ****/
include ('../../includes/funcionesSeguridad.php');
$serviciosSeguridad = new ServiciosSeguridad();
$serviciosSeguridad->seguridadRuta($_SESSION['refroll_sahilices'], '../documentos/');
//*** FIN  ****/

$fecha = date('Y-m-d');

//$resProductos = $serviciosProductos->traerProductosLimite(6);
$resMenu = $serviciosHTML->menu($_SESSION['nombre_sahilices'],"Mis Documentos",$_SESSION['refroll_sahilices'],$_SESSION['email_sahilices']);

$configuracion = $serviciosReferencias->traerConfiguracion();

$tituloWeb = mysql_result($configuracion,0,'sistema');

$breadCumbs = '';



/////////////////////// Opciones pagina ///////////////////////////////////////////////
$singular = "Mis Documentos";

$plural = "Mis Documentos";

$eliminar = "";

$insertar = "";

//$tituloWeb = "Gestión: Talleres";
//////////////////////// Fin opciones ////////////////////////////////////////////////


/////////////////////// Opciones para la creacion del formulario  /////////////////////


$resTrimestres = $serviciosReferencias->traerMeses();
$resTrimestreActual = $serviciosReferencias->traerMesesPorMes(date('m'));

$cadTrimestre 	= $serviciosFunciones->devolverSelectBox($resTrimestres,array(1),'');

$resAniosFacturados = $serviciosReferencias->traerAniosFacturadosPorCliente($_SESSION['idcliente']);
$cadAnios 	= $serviciosFunciones->devolverSelectBox($resAniosFacturados,array(0),'');

//($campos,$idestado='', $idtipofactura='', $idcliente='', $idmes='', $anio='', $fecha='',$limit='')
$campos = 'f.concepto, f.total, f.fechaingreso, est.estado';
$idtipofactura = 1;
$idcliente = $_SESSION['idcliente'];
$limit = 'limit 10';
$resIngresos = $serviciosReferencias->traerFacturasPorGeneral($campos,$idestado='', $idtipofactura, $idcliente, $idmes='', $anio='', $fecha='',$limit);

//die(var_dump($resIngresos));

$campos = 'f.concepto, f.total, f.fechaingreso, est.estado';
$idtipofactura = 2;
$idcliente = $_SESSION['idcliente'];
$limit = 'limit 10';
$resGastos = $serviciosReferencias->traerFacturasPorGeneral($campos,$idestado='', $idtipofactura, $idcliente, $idmes='', $anio='', $fecha='',$limit);



///////////////////////////              fin                   ////////////////////////

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

	 <!-- CSS file -->
	<link rel="stylesheet" href="../../css/easy-autocomplete.min.css">

	<!-- Additional CSS Themes file - not required-->
	<link rel="stylesheet" href="../../css/easy-autocomplete.themes.min.css">

	<!-- Dropzone Css -->
    <link href="../../plugins/dropzone/dropzone.css" rel="stylesheet">

	 <!-- Morris Chart Css-->
    <link href="../../plugins/morrisjs/morris.css" rel="stylesheet" />

	 <!-- Animation Css -->
    <link href="../../plugins/animate-css/animate.css" rel="stylesheet" />

	 <!-- Custom Css -->
    <link href="../../css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="../../css/themes/all-themes.css" rel="stylesheet" />

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

    <section class="content" style="margin-top:-35px;">

		<div class="container-fluid">
			<!-- Widgets -->
			<div class="row clearfix">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card ">
							<div class="header bg-riderz">
								<h2 style="color:#fff">
									<?php echo strtoupper($singular); ?>
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
							<div class="body table-responsive">
								<form class="form" id="formCountry">
									<!-- Nav tabs -->
									<ul class="nav nav-tabs tab-nav-right" role="tablist">
										<li role="presentation" class="active"><a href="#ingresos" data-toggle="tab">INGRESOS</a></li>
										<li role="presentation"><a href="#gastos" data-toggle="tab">GASTOS</a></li>
										<li role="presentation"><a href="#archivos" data-toggle="tab">ARCHIVOS</a></li>
										<li role="presentation"><a href="#subidas" data-toggle="tab">SUBIDAS</a></li>
									</ul>

									<!-- Tab panes -->
									<div class="tab-content">
										<div role="tabpanel" class="tab-pane fade in active" id="ingresos">
											<b>Facturas de Ingresos Subidas (solo aceptadas)</b>
											<table id="example1" class="display table " style="width:100%">
												<thead>
													<tr>
														<th>Concepto</th>
														<th>Importe Total</th>
														<th>Subido el</th>
														<th>Acciones</th>
													</tr>
												</thead>
												<tfoot>
													<tr>
														<th>Concepto</th>
														<th>Importe Total</th>
														<th>Subido el</th>
														<th>Acciones</th>
													</tr>
												</tfoot>
											</table>

										</div>
										<div role="tabpanel" class="tab-pane fade" id="gastos">
											<b>Facturas de Gastos Subidas (solo aceptadas)</b>
											<table id="example2" class="display table " style="width:100%">
												<thead>
													<tr>
														<th>Concepto</th>
														<th>Importe Total</th>
														<th>Subido el</th>
														<th>Acciones</th>
													</tr>
												</thead>
												<tfoot>
													<tr>
														<th>Concepto</th>
														<th>Importe Total</th>
														<th>Subido el</th>
														<th>Acciones</th>
													</tr>
												</tfoot>
											</table>
										</div>
										<div role="tabpanel" class="tab-pane fade" id="archivos">
											<b>Archivos Subidos por el Administrador</b>
											<table id="example" class="display table " style="width:100%">
												<thead>
													<tr>
														<th>Categoria</th>
														<th>Año</th>
														<th>Mes</th>
														<th>Token</th>
														<th>Obs.</th>
														<th>Acciones</th>
													</tr>
												</thead>
												<tfoot>
													<tr>
														<th>Categoria</th>
														<th>Año</th>
														<th>Mes</th>
														<th>Token</th>
														<th>Obs.</th>
														<th>Acciones</th>
													</tr>
												</tfoot>
											</table>
										</div>

										<div role="tabpanel" class="tab-pane fade" id="subidas">
											<b>Archivos Subidos al Administrador</b>
											<table id="example3" class="display table " style="width:100%">
												<thead>
													<tr>
														<th>Archivo</th>
														<th>Fecha</th>
														<th>Acciones</th>
													</tr>
												</thead>
												<tfoot>
													<tr>
														<th>Archivo</th>
														<th>Fecha</th>
														<th>Acciones</th>
													</tr>
												</tfoot>
											</table>
										</div>

									</div>
								</form>
								</div>
							</div>
						</div>

					</div>

				</div>

				<div class="row clearfix subirImagen">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card">
							<div class="header">
								<h2>
									SUBIR ARCHIVOS A SU ASESOR
								</h2>

							</div>
							<div class="body">

								<form action="subir.php" id="frmFileUpload" class="dropzone" method="post" enctype="multipart/form-data">
									<div class="dz-message">
										<div class="drag-icon-cph">
											<i class="material-icons">touch_app</i>
										</div>
										<h3>Arrastre y suelte una imagen aqui o haga click y busque una imagen en su ordenador.</h3>

									</div>
									<div class="fallback">

										<input name="file" type="file" id="archivos" />
										<input type="hidden" id="idjugador" name="idjugador" value="<?php echo $_SESSION['idcliente']; ?>" />


									</div>
								</form>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>


    </section>


    <?php echo $baseHTML->cargarArchivosJS('../../'); ?>

	 <script src="../../js/jquery.easy-autocomplete.min.js"></script>

	 <script src="../../DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>

	 <!-- Dropzone Plugin Js -->
	 <script src="../../plugins/dropzone/dropzone.js"></script>



	<script>

	$(document).ready(function(){

	Dropzone.prototype.defaultOptions.dictFileTooBig = "Este archivo es muy grande ({{filesize}}MiB). Peso Maximo: {{maxFilesize}}MiB.";

	Dropzone.options.frmFileUpload = {
		maxFilesize: 30,
		acceptedFiles: ".png,.jpg,.gif,.bmp,.jpeg,.pdf,.doc,.docx",
		accept: function(file, done) {
			done();
		},
		init: function() {
			this.on("sending", function(file, xhr, formData){
					formData.append("idcliente", '<?php echo $_SESSION['idcliente']; ?>');
			});
			this.on('success', function( file, resp ){

				swal("Correcto!", resp.replace("1", ""), "success");
				table3.ajax.reload();

			});

			this.on('error', function( file, resp ){
				swal("Error!", resp.replace("1", ""), "warning");
			});
		}
	};

	var myDropzone = new Dropzone("#archivos", {
		params: {
			 idcliente: <?php echo $_SESSION['idcliente']; ?>
		},
		url: 'subir.php'
	});




			var table1 = $('#example1').DataTable({
				"bProcessing": true,
				"bServerSide": true,
				"sAjaxSource": "../../json/jstablasajax.php?tabla=facturasingresos&idcliente=<?php echo $_SESSION['idcliente']; ?>",
				"language": {
					"emptyTable":     "No hay datos cargados",
					"info":           "Mostrar _START_ hasta _END_ del total de _TOTAL_ filas",
					"infoEmpty":      "Mostrar 0 hasta 0 del total de 0 filas",
					"infoFiltered":   "(filtrados del total de _MAX_ filas)",
					"infoPostFix":    "",
					"thousands":      ",",
					"lengthMenu":     "Mostrar _MENU_ filas",
					"loadingRecords": "Cargando...",
					"processing":     "Procesando...",
					"search":         "Buscar:",
					"zeroRecords":    "No se encontraron resultados",
					"paginate": {
						"first":      "Primero",
						"last":       "Ultimo",
						"next":       "Siguiente",
						"previous":   "Anterior"
					},
					"aria": {
						"sortAscending":  ": activate to sort column ascending",
						"sortDescending": ": activate to sort column descending"
					}
				}
			});


			var table2 = $('#example2').DataTable({
				"bProcessing": true,
				"bServerSide": true,
				"sAjaxSource": "../../json/jstablasajax.php?tabla=facturasgastos&idcliente=<?php echo $_SESSION['idcliente']; ?>",
				"language": {
					"emptyTable":     "No hay datos cargados",
					"info":           "Mostrar _START_ hasta _END_ del total de _TOTAL_ filas",
					"infoEmpty":      "Mostrar 0 hasta 0 del total de 0 filas",
					"infoFiltered":   "(filtrados del total de _MAX_ filas)",
					"infoPostFix":    "",
					"thousands":      ",",
					"lengthMenu":     "Mostrar _MENU_ filas",
					"loadingRecords": "Cargando...",
					"processing":     "Procesando...",
					"search":         "Buscar:",
					"zeroRecords":    "No se encontraron resultados",
					"paginate": {
						"first":      "Primero",
						"last":       "Ultimo",
						"next":       "Siguiente",
						"previous":   "Anterior"
					},
					"aria": {
						"sortAscending":  ": activate to sort column ascending",
						"sortDescending": ": activate to sort column descending"
					}
				}
			});

			var tablePlanta = $('#example').DataTable({
				"bProcessing": true,
				"bServerSide": true,
				"sAjaxSource": "../../json/jstablasajax.php?tabla=archivos&idcliente=<?php echo $_SESSION['idcliente']; ?>",
				"language": {
					"emptyTable":     "No hay datos cargados",
					"info":           "Mostrar _START_ hasta _END_ del total de _TOTAL_ filas",
					"infoEmpty":      "Mostrar 0 hasta 0 del total de 0 filas",
					"infoFiltered":   "(filtrados del total de _MAX_ filas)",
					"infoPostFix":    "",
					"thousands":      ",",
					"lengthMenu":     "Mostrar _MENU_ filas",
					"loadingRecords": "Cargando...",
					"processing":     "Procesando...",
					"search":         "Buscar:",
					"zeroRecords":    "No se encontraron resultados",
					"paginate": {
						"first":      "Primero",
						"last":       "Ultimo",
						"next":       "Siguiente",
						"previous":   "Anterior"
					},
					"aria": {
						"sortAscending":  ": activate to sort column ascending",
						"sortDescending": ": activate to sort column descending"
					}
				}
			});


			var table3 = $('#example3').DataTable({
				"bProcessing": true,
				"bServerSide": true,
				"sAjaxSource": "../../json/jstablasajax.php?tabla=subidas&idcliente=<?php echo $_SESSION['idcliente']; ?>",
				"language": {
					"emptyTable":     "No hay datos cargados",
					"info":           "Mostrar _START_ hasta _END_ del total de _TOTAL_ filas",
					"infoEmpty":      "Mostrar 0 hasta 0 del total de 0 filas",
					"infoFiltered":   "(filtrados del total de _MAX_ filas)",
					"infoPostFix":    "",
					"thousands":      ",",
					"lengthMenu":     "Mostrar _MENU_ filas",
					"loadingRecords": "Cargando...",
					"processing":     "Procesando...",
					"search":         "Buscar:",
					"zeroRecords":    "No se encontraron resultados",
					"paginate": {
						"first":      "Primero",
						"last":       "Ultimo",
						"next":       "Siguiente",
						"previous":   "Anterior"
					},
					"aria": {
						"sortAscending":  ": activate to sort column ascending",
						"sortDescending": ": activate to sort column descending"
					}
				}
			});

			$("#example").on("click",'.btnDescargar', function(){
				usersid =  $(this).attr("id");

				url = "descargaradmin.php?token=" + usersid;
				$(location).attr('href',url);

			});//fin del boton modificar

			$("#example1").on("click",'.btnDescargar', function(){
				usersid =  $(this).attr("id");

				url = "descargar.php?token=" + usersid;
				$(location).attr('href',url);

			});//fin del boton modificar

			$("#example2").on("click",'.btnDescargar', function(){
				usersid =  $(this).attr("id");

				url = "descargar.php?token=" + usersid;
				$(location).attr('href',url);


			});//fin del boton modificar

			$("#example3").on("click",'.btnDescargarSubidas', function(){
				usersid =  $(this).attr("id");

				url = "descargarsubidas.php?token=" + usersid;
			
				window.open(url ,'_blank');

			});//fin del boton modificar

		});
	</script>



</body>
<?php } ?>
</html>
