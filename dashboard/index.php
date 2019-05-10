<?php

session_start();

if (!isset($_SESSION['usua_sahilices']))
{
	header('Location: ../error.php');
} else {


include ('../includes/funcionesUsuarios.php');
include ('../includes/funcionesHTML.php');
include ('../includes/funciones.php');
include ('../includes/funcionesReferencias.php');
include ('../includes/base.php');

$serviciosUsuario = new ServiciosUsuarios();
$serviciosHTML = new ServiciosHTML();
$serviciosFunciones = new Servicios();
$serviciosReferencias 	= new ServiciosReferencias();
$baseHTML = new BaseHTML();

$fecha = date('Y-m-d');

//$resProductos = $serviciosProductos->traerProductosLimite(6);
$resMenu = $serviciosHTML->menu($_SESSION['nombre_sahilices'],"Dashboard",$_SESSION['refroll_sahilices'],'');

$configuracion = $serviciosReferencias->traerConfiguracion();

$tituloWeb = mysql_result($configuracion,0,'sistema');

$breadCumbs = '';



/////////////////////// Opciones pagina ///////////////////////////////////////////////
$singular = "";

$plural = "";

$eliminar = "";

$insertar = "";

//$tituloWeb = "Gestión: Talleres";
//////////////////////// Fin opciones ////////////////////////////////////////////////


/////////////////////// Opciones para la creacion del formulario  /////////////////////

if ($_SESSION['idroll_sahilices'] == 1) {
	$singular = "Factura";

	$plural = "Facturas";

	$eliminar = "eliminarFacturas";

	$insertar = "insertarFacturas";

	$modificar = "modificarFacturas";

	/////////////////////// Opciones para la creacion del formulario  /////////////////////
	$tabla 			= "dbfacturas";

	$lblCambio	 	= array('refclientes','refmeses','refestados','reftipofacturas','iva','irff','fechaingreso','fechasubido','total','anio');
	$lblreemplazo	= array('Cliente','Trimestre','Estado','Tipo Factura','IVA','IRPF','Fecha Ingreso','Fecha Subido','Importe Total','Año');


	$resVar1 = $serviciosReferencias->traerMeses();
	$cadRef1 	= $serviciosFunciones->devolverSelectBox($resVar1,array(1),'');

	$resVar2 = $serviciosReferencias->traerTipofacturas();
	$cadRef2 	= $serviciosFunciones->devolverSelectBox($resVar2,array(1),'');

	$resVar3 = $serviciosReferencias->traerClientes();
	$cadRef3 	= $serviciosFunciones->devolverSelectBox($resVar3,array(1),'');

	$resVar4 = $serviciosReferencias->traerEstadosPorId(1);
	$cadRef4 	= $serviciosFunciones->devolverSelectBox($resVar4,array(1),'');
	//die(var_dump(mysql_result($resVar4,0,0)));

	$refdescripcion = array(0 => $cadRef1, 1 => $cadRef2, 2 => $cadRef3, 3 => $cadRef4);
	$refCampo 	=  array('refmeses','reftipofacturas','refclientes','refestados');

	$frmUnidadNegocios 	= $serviciosFunciones->camposTablaViejo($insertar ,$tabla,$lblCambio,$lblreemplazo,$refdescripcion,$refCampo);
	//////////////////////////////////////////////  FIN de los opciones //////////////////////////
} else {
	$resTrimestres = $serviciosReferencias->traerMeses();
	$resTrimestreActual = $serviciosReferencias->traerMesesPorMes(date('m'));

	$cadTrimestre 	= $serviciosFunciones->devolverSelectBox($resTrimestres,array(1),'');

	$resAniosFacturados = $serviciosReferencias->traerAniosFacturadosPorCliente($_SESSION['idcliente']);
	$cadAnios 	= $serviciosFunciones->devolverSelectBox($resAniosFacturados,array(0),'');
   //$cadAnios 	= '<option value="2019">2019</option><option value="2018">2018</option>';
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

}

///////////////////////////              fin                   ////////////////////////

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?php echo $tituloWeb; ?></title>
    <!-- Favicon-->
    <link rel="icon" href="../favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <?php echo $baseHTML->cargarArchivosCSS('../'); ?>

	 <!-- CSS file -->
	<link rel="stylesheet" href="../css/easy-autocomplete.min.css">

	<!-- Additional CSS Themes file - not required-->
	<link rel="stylesheet" href="../css/easy-autocomplete.themes.min.css">



	 <!-- Morris Chart Css-->
    <link href="../plugins/morrisjs/morris.css" rel="stylesheet" />

	 <!-- Animation Css -->
    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

	 <!-- Custom Css -->
    <link href="../css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="../css/themes/all-themes.css" rel="stylesheet" />

	 <link rel="stylesheet" href="../DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css">
 	<link rel="stylesheet" href="../DataTables/DataTables-1.10.18/css/dataTables.bootstrap.css">
 	<link rel="stylesheet" href="../DataTables/DataTables-1.10.18/css/dataTables.jqueryui.min.css">
 	<link rel="stylesheet" href="../DataTables/DataTables-1.10.18/css/jquery.dataTables.css">

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
    <?php echo $baseHTML->cargarSECTION($_SESSION['usua_sahilices'], $_SESSION['nombre_sahilices'], str_replace('..','../dashboard',$resMenu),'../'); ?>

    <section class="content" style="margin-top:-35px;">

		<div class="container-fluid">
			<!-- Widgets -->
			<div class="row clearfix">
				<?php if ($_SESSION['idroll_sahilices'] == 1) { ?>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="card ">
							<div class="header bg-riderz">
								<h2 style="color:#fff">
									<?php echo strtoupper($plural); ?>
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
								<form class="form" id="formFacturas">
								<div class="row" style="padding: 5px 20px;">

									<table id="example" class="display table " style="width:100%">
										<thead>
											<tr>
												<th>Tipo</th>
												<th>Estado</th>
												<th>Concepto</th>
												<th>Importe Base</th>
												<th>IVA</th>
												<th>IRPF</th>
												<th>Importe Total</th>
												<th>Fecha</th>
												<th>Subido el</th>
												<th>Cliente</th>
												<th>Acciones</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th>Tipo</th>
												<th>Estado</th>
												<th>Concepto</th>
												<th>Importe Base</th>
												<th>IVA</th>
												<th>IRPF</th>
												<th>Importe Total</th>
												<th>Fecha</th>
												<th>Subido el</th>
												<th>Cliente</th>
												<th>Acciones</th>
											</tr>
										</tfoot>
									</table>
								</div>
							</form>
						</div>
					</div>
				</div>
				<?php } else { ?>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">




					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="info-box bg-green hover-expand-effect">
							<div class="icon">
								<i class="material-icons">euro_symbol</i>
							</div>
							<div class="content">
								<div class="text">INGRESOS</div>
								<div class="number">€ <span class="lblTotalIngresos"></span></div>
							</div>
						</div>
					</div>


					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="info-box bg-orange hover-expand-effect">
							<div class="icon">
								<i class="material-icons">assignment_returned</i>
							</div>
							<div class="content">
								<div class="text">GASTOS</div>
								<div class="number">€ <span class="lblTotalGastos"></span></div>
							</div>
						</div>
					</div>

					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<select class="form-control show-tick" name="trimestre" id="trimestre">
							<?php echo $cadTrimestre; ?>
						</select>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<select class="form-control show-tick" name="anio" id="anio">
							<?php echo $cadAnios; ?>
						</select>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <div class="card">
                        <div class="header bg-riderz">
                           <h2 style="color:white;">
                              <span class="lblTrimestre"></span> del <span class="lblAnio"></span> <small style="color:white;">Últimas Facturas de Ingresos</small>
                           </h2>

                        </div>
                        <div class="body">
									<table id="example1" class="display table " style="width:100%">
										<thead>
											<tr>
												<th>Concepto</th>
												<th>Importe Total</th>
												<th>Fecha</th>
												<th>Estado</th>
											</tr>
										</thead>
										<tbody id="lstIngresos">

										</tbody>
									</table>
                        </div>
                  </div>
               </div>

					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <div class="card">
                        <div class="header bg-riderz">
                           <h2 style="color:white;">
                              <span class="lblTrimestre"></span> del <span class="lblAnio"></span>  <small style="color:white;">Últimas Facturas de Gastos</small>
                           </h2>

                        </div>
                        <div class="body">
									<table id="example2" class="display table " style="width:100%">
										<thead>
											<tr>
												<th>Concepto</th>
												<th>Importe Total</th>
												<th>Fecha</th>
												<th>Estado</th>
											</tr>
										</thead>
										<tbody id="lstGastos">

										</tbody>
									</table>
                        </div>
                  </div>
               </div>

				</div>

			<?php } ?>
			</div>
		</div>


    </section>


	 <!-- MODIFICAR -->
		 <form class="formulario formMod" role="form" id="sign_in">
			 <div class="modal fade" id="lgmModificar" tabindex="-1" role="dialog">
				  <div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							 <div class="modal-header">
								  <h4 class="modal-title" id="largeModalLabel">MODIFICAR <?php echo strtoupper($singular); ?></h4>
							 </div>
							 <div class="modal-body">
								 <div class="row frmAjaxModificar">

								 </div>

							 </div>
							 <div class="modal-footer">
								  <button type="button" class="btn btn-warning waves-effect modificar">MODIFICAR</button>
								  <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CERRAR</button>
							 </div>
						</div>
				  </div>
			 </div>
			 <input type="hidden" id="accion" name="accion" value="modificarFacturas"/>
		 </form>


    <?php echo $baseHTML->cargarArchivosJS('../'); ?>

	 <script src="../js/jquery.easy-autocomplete.min.js"></script>

	 <script src="../DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>

	 <!-- Bootstrap Material Datetime Picker Plugin Js -->
	 <script src="../plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>



	<script>
		$(document).ready(function(){

			<?php if ($_SESSION['idroll_sahilices'] == 3) { ?>
			$('.lblTrimestre').html($('#trimestre option:selected').text());
			$('.lblAnio').html($('#anio').val());

			function traerTotales(tipo, anio, trimestre, contenedor) {
				$.ajax({
					url: '../ajax/ajax.php',
					type: 'POST',
					// Form data
					//datos del formulario
					data: {
						accion: 'traerTotalPorClienteAnioTrimestre',
						id: <?php echo $idcliente; ?>,
						tipo: tipo,
						anio: anio,
						trimestre: trimestre
					},
					//mientras enviamos el archivo
					beforeSend: function(){
						$('.' + contenedor).html('');
					},
					//una vez finalizado correctamente
					success: function(data){

						$('.' + contenedor).html(data);

					},
					//si ha ocurrido un error
					error: function(){
						$(".alert").html('<strong>Error!</strong> Actualice la pagina');
						$("#load").html('');
					}
				});
			}


			function traerUltimasFacturas(tipo, anio, trimestre, contenedor) {
				$.ajax({
					url: '../ajax/ajax.php',
					type: 'POST',
					// Form data
					//datos del formulario
					data: {
						accion: 'traerUltimasFacturas',
						tipo: tipo,
						anio: anio,
						trimestre: trimestre
					},
					//mientras enviamos el archivo
					beforeSend: function(){
						$('.' + contenedor).html('');
					},
					//una vez finalizado correctamente
					success: function(data){

						$('#' + contenedor).html(data);

					},
					//si ha ocurrido un error
					error: function(){
						$(".alert").html('<strong>Error!</strong> Actualice la pagina');
						$("#load").html('');
					}
				});
			}

			traerTotales(1, $('#anio').val(), $('#trimestre').val(), 'lblTotalIngresos');
			traerTotales(2, $('#anio').val(), $('#trimestre').val(), 'lblTotalGastos');

			traerUltimasFacturas(1, $('#anio').val(), $('#trimestre').val(), 'lstIngresos');
			traerUltimasFacturas(2, $('#anio').val(), $('#trimestre').val(), 'lstGastos');

			$('#anio').change(function() {
				traerTotales(1, $(this).val(), $('#trimestre').val(), 'lblTotalIngresos');
				traerTotales(2, $(this).val(), $('#trimestre').val(), 'lblTotalGastos');

				traerUltimasFacturas(1, $(this).val(), $('#trimestre').val(), 'lstIngresos');
				traerUltimasFacturas(2, $(this).val(), $('#trimestre').val(), 'lstGastos');

				$('.lblTrimestre').html($('#trimestre option:selected').text());
				$('.lblAnio').html($('#anio').val());
			});

			$('#trimestre').change(function() {
				traerTotales(1, $('#anio').val(), $(this).val(), 'lblTotalIngresos');
				traerTotales(2, $('#anio').val(), $(this).val(), 'lblTotalGastos');

				traerUltimasFacturas(1, $('#anio').val(), $(this).val(), 'lstIngresos');
				traerUltimasFacturas(2, $('#anio').val(), $(this).val(), 'lstGastos');

				$('.lblTrimestre').html($('#trimestre option:selected').text());
				$('.lblAnio').html($('#anio').val());
			});
			<?php } else { ?>
				var table = $('#example').DataTable({
					"bProcessing": true,
					"bServerSide": true,
					"sAjaxSource": "../json/jstablasajax.php?tabla=facturastodas&idcliente=0",
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

				function frmAjaxModificar(id) {
					$.ajax({
						url: '../ajax/ajax.php',
						type: 'POST',
						// Form data
						//datos del formulario
						data: {accion: 'frmAjaxModificar',tabla: '<?php echo $tabla; ?>', id: id},
						//mientras enviamos el archivo
						beforeSend: function(){
							$('.frmAjaxModificar').html('');
						},
						//una vez finalizado correctamente
						success: function(data){

							if (data != '') {
								$('.frmAjaxModificar').html(data);
								$('#fechaingreso').inputmask('yyyy-mm-dd', { placeholder: '____-__-__' });
								$('#fechasubido').inputmask('yyyy-mm-dd', { placeholder: '____-__-__' });

							} else {
								swal("Error!", data, "warning");

								$("#load").html('');
							}
						},
						//si ha ocurrido un error
						error: function(){
							$(".alert").html('<strong>Error!</strong> Actualice la pagina');
							$("#load").html('');
						}
					});

				}

				$("#example").on("click",'.btnModificar', function(){
					idTable =  $(this).attr("id");
					frmAjaxModificar(idTable);
					$('#lgmModificar').modal();
				});//fin del boton modificar

				$('.maximizar').click(function() {
					if ($('.icomarcos').text() == 'web') {
						$('#marcos').show();
						$('.content').css('marginLeft', '315px');
						$('.icomarcos').html('aspect_ratio');
					} else {
						$('#marcos').hide();
						$('.content').css('marginLeft', '15px');
						$('.icomarcos').html('web');
					}

				});

				$("#example").on("click",'.btnDescargar', function(){
					usersid =  $(this).attr("id");

					url = "descargaradmin.php?token=" + usersid;
					$(location).attr('href',url);

				});//fin del boton modificar

				$('.modificar').click(function(e){

					e.preventDefault();
		         if ($('.formulario')[0].checkValidity()) {
					//información del formulario
					var formData = new FormData($(".formulario")[0]);
					var message = "";
					//hacemos la petición ajax
					$.ajax({
						url: '../ajax/ajax.php',
						type: 'POST',
						// Form data
						//datos del formulario
						data: formData,
						//necesario para subir archivos via ajax
						cache: false,
						contentType: false,
						processData: false,
						//mientras enviamos el archivo
						beforeSend: function(){

						},
						//una vez finalizado correctamente
						success: function(data){

							if (data == '') {
								swal({
										title: "Respuesta",
										text: "Registro Modificado con exito!!",
										type: "success",
										timer: 1500,
										showConfirmButton: false
								});

								$('#lgmModificar').modal('hide');
								table.ajax.reload();
							} else {
								swal({
										title: "Respuesta",
										text: data,
										type: "error",
										timer: 2500,
										showConfirmButton: false
								});


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

			<?php } ?>

		});
	</script>



</body>
<?php } ?>
</html>
