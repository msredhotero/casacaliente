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
$serviciosSeguridad->seguridadRuta($_SESSION['refroll_sahilices'], '../lloguers/');
//*** FIN  ****/

//$dias = $serviciosReferencias->calcularTarifa(25,'2019-06-26','2019-06-30',3);
//die(var_dump($dias));

$fecha = date('Y-m-d');

//$resProductos = $serviciosProductos->traerProductosLimite(6);
$resMenu = $serviciosHTML->menu($_SESSION['nombre_sahilices'],"Lloguers",$_SESSION['refroll_sahilices'],$_SESSION['email_sahilices']);

$configuracion = $serviciosReferencias->traerConfiguracion();

$tituloWeb = mysql_result($configuracion,0,'sistema');

$breadCumbs = '<a class="navbar-brand" href="../index.php">Dashboard</a>';

/////////////////////// Opciones pagina ///////////////////////////////////////////////
$singular = "Lloguer";

$plural = "Lloguers";

$eliminar = "eliminarLloguers";

$insertar = "insertarLloguers";

$modificar = "modificarLloguers";

//////////////////////// Fin opciones ////////////////////////////////////////////////


/////////////////////// Opciones para la creacion del formulario  /////////////////////
$tabla 			= "dblloguers";

$lblCambio	 	= array('refclientes','refubicaciones','datalloguer','numpertax','persset','maxtaxa','refestados');
$lblreemplazo	= array('Client','Ubicaciones','Data Contracte','N° Pers Taxa','Pers Total','Max Taxa','Estat');


$resVar1 = $serviciosReferencias->traerClientes();
$cadRef1 	= $serviciosFunciones->devolverSelectBox($resVar1,array(1,2),' ');

$resVar2 = $serviciosReferencias->traerUbicaciones();
$cadRef2 	= $serviciosFunciones->devolverSelectBox($resVar2,array(4,1,2),' - ');

$resVar3 = $serviciosReferencias->traerEstados();
$cadRef3 	= $serviciosFunciones->devolverSelectBox($resVar3,array(1),'');


$refdescripcion = array(0 => $cadRef1,1=>$cadRef2, 2=>$cadRef3);
$refCampo 	=  array('refclientes','refubicaciones','refestados');

$frmUnidadNegocios 	= $serviciosFunciones->camposTablaViejo($insertar ,$tabla,$lblCambio,$lblreemplazo,$refdescripcion,$refCampo);
//////////////////////////////////////////////  FIN de los opciones //////////////////////////

/////////////////////// Opciones para la creacion del formulario  /////////////////////
$tablaCliente 			= "dbclientes";

$lblCambioCliente	 	= array('codipostal','telefon2','email2');
$lblreemplazoCliente	= array('Cod Postal','Tel. 2','Email 2');


$cadRefCliente 	= '';

$refdescripcionCliente = array();
$refCampoCliente 	=  array();

$frmCliente 	= $serviciosFunciones->camposTablaViejo('insertarClientes' ,$tablaCliente,$lblCambioCliente,$lblreemplazoCliente,$refdescripcionCliente,$refCampoCliente);
//////////////////////////////////////////////  FIN de los opciones //////////////////////////

$resTaxa = $serviciosReferencias->traerTaxa();

$taxaPer = mysql_result($resTaxa,0,1);
$taxaTur = mysql_result($resTaxa,0,2);

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
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

	<style>
		.alert > i{ vertical-align: middle !important; }
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
						<div class="header bg-red">
							<h2>
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
							<form class="form" id="formCountry">

								<div class="row">
									<div class="col-lg-12 col-md-12">
										<div class="button-demo">
											<button type="button" class="btn bg-light-green waves-effect btnNuevo" data-toggle="modal" data-target="#lgmNuevo">
												<i class="material-icons">add</i>
												<span>NOU</span>
											</button>
											<button type="button" class="btn bg-teal waves-effect btnNuevo" data-toggle="modal" data-target="#lgmNuevo">
												<i class="material-icons">date_range</i>
												<span>DISPONIBILITAT</span>
											</button>
											<button type="button" class="btn bg-green waves-effect btnNuevoCliente" data-toggle="modal" data-target="#lgmNuevoCliente">
												<i class="material-icons">add</i>
												<span>NOU CLIENT</span>
											</button>

										</div>
									</div>
								</div>

								<div class="row" style="padding: 5px 20px;">

									<table id="example" class="display table " style="width:100%">
										<thead>
											<tr>
												<th>Client</th>
												<th>Ubicació</th>
												<th>Entrada</th>
												<th>Sortida</th>
												<th>Dies</th>
												<th>Preu</th>
												<th>m. d'edat</th>
												<th>Tot.Per.</th>
												<th>Estat</th>
												<th>Accions</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th>Client</th>
												<th>Ubicació</th>
												<th>Entrada</th>
												<th>Sortida</th>
												<th>Dies</th>
												<th>Preu</th>
												<th>m. d'edat</th>
												<th>Tot.Per.</th>
												<th>Estat</th>
												<th>Accions</th>
											</tr>
										</tfoot>
									</table>
								</div>
							</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<!-- NUEVO -->
	<form class="formulario" role="form" id="sign_in">
	   <div class="modal fade" id="lgmNuevo" tabindex="-1" role="dialog">
	       <div class="modal-dialog modal-lg" role="document">
	           <div class="modal-content">
	               <div class="modal-header">
	                   <h4 class="modal-title" id="largeModalLabel">NOU <?php echo strtoupper($singular); ?></h4>
	               </div>
	               <div class="modal-body">
							<div class="row">
								<?php echo $frmUnidadNegocios; ?>
							</div>

	               </div>
	               <div class="modal-footer">
	                   <button type="submit" class="btn btn-primary waves-effect nuevo">GUARDAR</button>
	                   <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CERRAR</button>
	               </div>
	           </div>
	       </div>
	   </div>
		<input type="hidden" id="accion" name="accion" value="<?php echo $insertar; ?>"/>
	</form>



	<!-- MODIFICAR -->
		<form class="formulario" role="form" id="sign_in">
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
			<input type="hidden" id="accion" name="accion" value="<?php echo $modificar; ?>"/>
		</form>



	<!-- ELIMINAR -->
		<form class="formulario" role="form" id="sign_in">
		   <div class="modal fade" id="lgmEliminar" tabindex="-1" role="dialog">
		       <div class="modal-dialog modal-lg" role="document">
		           <div class="modal-content">
		               <div class="modal-header">
		                   <h4 class="modal-title" id="largeModalLabel">ELIMINAR <?php echo strtoupper($singular); ?></h4>
		               </div>
		               <div class="modal-body">
										 <p>¿Esta seguro que desea eliminar el registro?</p>
										 <small>* Si este registro esta relacionado con algun otro dato no se podría eliminar.</small>
		               </div>
		               <div class="modal-footer">
		                   <button type="button" class="btn btn-danger waves-effect eliminar">ELIMINAR</button>
		                   <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CERRAR</button>
		               </div>
		           </div>
		       </div>
		   </div>
			<input type="hidden" id="accion" name="accion" value="<?php echo $eliminar; ?>"/>
			<input type="hidden" name="ideliminar" id="ideliminar" value="0">
		</form>



		<!-- ver -->
			<form class="formulario" role="form" id="sign_in">
			   <div class="modal fade" id="lgmVer" tabindex="-1" role="dialog">
			       <div class="modal-dialog modal-lg" role="document">
			           <div class="modal-content">
			               <div class="modal-header">
			                   <h4 class="modal-title" id="largeModalLabelVer"></h4>
			               </div>
			               <div class="modal-body">
									<div class="row modalVer">

									</div>

			               </div>
			               <div class="modal-footer">
			                   <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CERRAR</button>
			               </div>
			           </div>
			       </div>
			   </div>
				<input type="hidden" id="accion" name="accion" value="<?php echo $insertar; ?>"/>
			</form>

		<!-- NUEVO -->
			<form class="formulario" role="form" id="sign_in">
				<div class="modal fade" id="lgmNuevoCliente" tabindex="-1" role="dialog">
					 <div class="modal-dialog modal-lg" role="document">
						  <div class="modal-content">
								<div class="modal-header">
									 <h4 class="modal-title" id="largeModalLabel">NOU CLIENT</h4>
								</div>
								<div class="modal-body">
									<div class="row">
										<?php echo $frmCliente; ?>
									</div>

								</div>
								<div class="modal-footer">
									 <button type="button" class="btn btn-primary waves-effect nuevoCliente">GUARDAR</button>
									 <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CERRAR</button>
								</div>
						  </div>
					 </div>
				</div>
				<input type="hidden" id="accion" name="accion" value="insertarClientes"/>
			</form>


		<!-- Pagos -->
			<form class="formulario" role="form" id="sign_in">
				<div class="modal fade" id="lgmNuevoPago" tabindex="-1" role="dialog">
					 <div class="modal-dialog modal-lg" role="document">
						  <div class="modal-content">
								<div class="modal-header">
									 <h4 class="modal-title" id="largeModalLabel">PAGAMENTS</h4>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 margTop" style="display:block;">
											<div class="row">
												<label class="form-label">Total a Pagar</label>
												<div class="form-line">
													<input readonly="readonly" style="width:200px;" value="0" type="text" class="form-control" id="totalapagar" name="totalapagar" required />
												</div>
											</div>
											<div class="row">
												<label class="form-label">Falta Pagar</label>
												<div class="form-line">
													<input readonly="readonly" style="width:200px;" value="0" type="text" class="form-control" id="faltapagar" name="faltapagar" required />
												</div>
											</div>
										</div>

										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 margTop" style="display:block;">
											<div class="row">
												<label class="form-label">Valor Pago 1°</label>
												<div class="form-line">
													<input value="0" style="width:200px;" type="text" class="form-control" id="valorpago1" name="valorpago1" required />
												</div>
											</div>
											<div class="row">
												<label class="form-label">Valor Pago 2°</label>
												<div class="form-line">
													<input value="0" style="width:200px;" type="text" class="form-control" id="valorpago2" name="valorpago2" required />
												</div>
											</div>
											<div class="row">
												<label class="form-label">Taxa</label>
												<div class="form-line">
													<input value="0" style="width:200px;" type="text" class="form-control" id="pagotaxa" name="pagotaxa" required />
												</div>
											</div>
										</div>

										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 margTop" style="display:block;">
											<div class="row">
												<label class="form-label">Fecha Pago 1°</label>
												<div class="form-line">
													<input readonly="readonly" style="width:200px;" value="0" type="text" class="form-control" id="fechapago1" name="fechapago1" required />
												</div>
											</div>
											<div class="row">
												<label class="form-label">Fecha Pago 2°</label>
												<div class="form-line">
													<input readonly="readonly" style="width:200px;" value="0" type="text" class="form-control" id="fechapago2" name="fechapago2" required />
												</div>
											</div>
										</div>

									</div>


								</div>
								<div class="modal-footer">
									 <button type="button" class="btn btn-primary waves-effect nuevoPagare">GUARDAR</button>
									 <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CERRAR</button>
								</div>
						  </div>
					 </div>
				</div>
				<input type="hidden" id="accion" name="accion" value="insertarPagare"/>
				<input type="hidden" id="idlloguerpagare" name="idlloguerpagare" value="0"/>
			</form>

<?php echo $baseHTML->cargarArchivosJS('../../'); ?>
<!-- Wait Me Plugin Js -->
<script src="../../plugins/waitme/waitMe.js"></script>

<!-- Custom Js -->
<script src="../../js/pages/cards/colored.js"></script>

<script src="../../plugins/jquery-validation/jquery.validate.js"></script>

<script src="../../js/pages/examples/sign-in.js"></script>

<!-- Moment Plugin Js -->
    <script src="../../plugins/momentjs/moment.js"></script>

<script src="../../plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>


<!-- Bootstrap Material Datetime Picker Plugin Js -->
<script src="../../plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

<script src="../../DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="../../js/datepicker-es.js"></script>
<script>
	$(document).ready(function(){
		<?php $date = date('Y-m-d'); ?>

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

		$('.maximizar').click();

		$('.datepicker').bootstrapMaterialDatePicker({
        format: 'DD/MM/YYYY',
        clearButton: true,
        weekStart: 1,
        time: false
    });

		$( "#fechapago1" ).datepicker({ dateFormat: 'dd/mm/yy' });
		$( "#fechapago1" ).val('<?php echo date('d/m/Y', strtotime($date.' + 5 days')); ?>');
		$( "#fechapago2" ).datepicker({ dateFormat: 'dd/mm/yy' });
		$( "#fechapago2" ).val('<?php echo date('d/m/Y', strtotime($date.' + 30 days')); ?>');

		$('#valorpago1').number(true,2,'.','');
		$('#valorpago2').number(true,2,'.','');
		$('#pagotaxa').number(true,2,'.','');

		$('#datalloguer').val('<?php echo date('d/m/Y'); ?>');
		$('#entrada').val('<?php echo date('d/m/Y'); ?>');
		$('#sortida').val('<?php echo date('d/m/Y', strtotime($date.' + 7 days')); ?>');



		$('#numpertax').val(2);
		$('#persset').val(2);

		$('#maxtaxa').val(<?php echo $taxaTur; ?>);
		$('#taxa').val(<?php echo $taxaPer; ?>);

		$('#sortida').change(function() {
			devolverTarifa($('#refubicaciones').val(), $('#entrada').val(), $('#sortida').val(), $('#numpertax').val());
		});

		$('#entrada').change(function() {
			devolverTarifa($('#refubicaciones').val(), $('#entrada').val(), $('#sortida').val(), $('#numpertax').val());
		});

		$('#refubicaciones').change(function() {
			devolverTarifa($('#refubicaciones').val(), $('#entrada').val(), $('#sortida').val(), $('#numpertax').val());
		});

		$('#numpertax').change(function() {
			devolverTarifa($('#refubicaciones').val(), $('#entrada').val(), $('#sortida').val(), $('#numpertax').val());
			$('#persset').val($('#numpertax').val());
		});

		function formato(texto){
			return texto.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1');
		}

		function devolverTarifaArray(id) {
			$.ajax({
				url: '../../ajax/ajax.php',
				type: 'POST',
				// Form data
				//datos del formulario
				data: {
					accion: 'devolverTarifaArray',
					id: id
				},
				//mientras enviamos el archivo
				beforeSend: function(){
					$('#total').val(0);
				},
				//una vez finalizado correctamente
				success: function(data){

					$('#totalapagar').val(data.datos.total);
					$('#faltapagar').val(data.datos.falta);
					if (data.pagos.existe == 1) {
						$('#valorpago1').val(data.pagos.pago1);
						$('#valorpago2').val(data.pagos.pago2);
						$('#pagotaxa').val(data.pagos.taxa);
						$( "#fechapago1" ).val(formato(data.pagos.primerpago));
						$( "#fechapago2" ).val(formato(data.pagos.segundopago));
					} else {
						$('#valorpago1').val(data.datos.tarifa / 2);
						$('#valorpago2').val(data.datos.tarifa / 2);
						$('#pagotaxa').val(data.datos.taxapersona + data.datos.taxaturistica);
						$( "#fechapago2" ).val(formato(data.datos.fechasegundopago));
					}



				},
				//si ha ocurrido un error
				error: function(){
					$(".alert").html('<strong>Error!</strong> Actualice la pagina');
					$("#load").html('');
				}
			});
		}

		function devolverTarifa(refubicaciones, entrada, sortida, personas) {
			$.ajax({
				url: '../../ajax/ajax.php',
				type: 'POST',
				// Form data
				//datos del formulario
				data: {
					accion: 'devolverTarifa',
					refubicaciones: refubicaciones,
					entrada: entrada,
					sortida: sortida,
					personas: personas
				},
				//mientras enviamos el archivo
				beforeSend: function(){
					$('#total').val(0);
				},
				//una vez finalizado correctamente
				success: function(data){

					$('#total').val(data);
				},
				//si ha ocurrido un error
				error: function(){
					$(".alert").html('<strong>Error!</strong> Actualice la pagina');
					$("#load").html('');
				}
			});
		}

		devolverTarifa($('#refubicaciones').val(), $('#entrada').val(), $('#sortida').val(), $('#numpertax').val());

		var $demoMaskedInput = $('.demo-masked-input');

		$('#desdeperiode').inputmask('yyyy-mm-dd', { placeholder: '____-__-__' });
		$('#finsaperiode').inputmask('yyyy-mm-dd', { placeholder: '____-__-__' });

		var table = $('#example').DataTable({
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "../../json/jstablasajax.php?tabla=lloguers",
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

		$("#sign_in").submit(function(e){
			e.preventDefault();
		});

		$('#activo').prop('checked',true);



		function frmAjaxModificar(id) {
			$.ajax({
				url: '../../ajax/ajax.php',
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


		function frmAjaxVer(id,tabla) {
			$.ajax({
				url: '../../ajax/ajax.php',
				type: 'POST',
				// Form data
				//datos del formulario
				data: {accion: 'frmAjaxVer',tabla: tabla, id: id},
				//mientras enviamos el archivo
				beforeSend: function(){
					$('.modalVer').html('');
				},
				//una vez finalizado correctamente
				success: function(data){

					if (data != '') {
						$('.modalVer').html(data);
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

		$("#example").on("click",'.btnCliente', function(){
			$('.largeModalLabelVer').html('CLIENT');
			idTable =  $(this).attr("id");

			frmAjaxVer(idTable,'dbclientes');
			$('#lgmVer').modal();
		});

		$("#example").on("click",'.btnPagos', function(){

			idTable =  $(this).attr("id");

			$('#idlloguerpagare').val(idTable);

			devolverTarifaArray(idTable);
			$('#lgmNuevoPago').modal();
		});


		function frmAjaxEliminar(id) {
			$.ajax({
				url: '../../ajax/ajax.php',
				type: 'POST',
				// Form data
				//datos del formulario
				data: {accion: '<?php echo $eliminar; ?>', id: id},
				//mientras enviamos el archivo
				beforeSend: function(){

				},
				//una vez finalizado correctamente
				success: function(data){

					if (data == '') {
						swal({
								title: "Respuesta",
								text: "Registro Eliminado con exito!!",
								type: "success",
								timer: 1500,
								showConfirmButton: false
						});
						$('#lgmEliminar').modal('toggle');
						table.ajax.reload();
						armarTablaTarifas($('#any').val());
					} else {
						swal({
								title: "Respuesta",
								text: data,
								type: "error",
								timer: 2000,
								showConfirmButton: false
						});

					}
				},
				//si ha ocurrido un error
				error: function(){
					swal({
							title: "Respuesta",
							text: 'Actualice la pagina',
							type: "error",
							timer: 2000,
							showConfirmButton: false
					});

				}
			});

		}

		$("#example").on("click",'.btnEliminar', function(){
			idTable =  $(this).attr("id");
			$('#ideliminar').val(idTable);
			$('#lgmEliminar').modal();
		});//fin del boton eliminar

		$('.eliminar').click(function() {
			frmAjaxEliminar($('#ideliminar').val());
		});

		$("#example").on("click",'.btnModificar', function(){
			idTable =  $(this).attr("id");
			frmAjaxModificar(idTable);
			$('#lgmModificar').modal();
		});//fin del boton modificar

		$('.nuevo').click(function(){

			//información del formulario
			var formData = new FormData($(".formulario")[0]);
			var message = "";
			//hacemos la petición ajax
			$.ajax({
				url: '../../ajax/ajax.php',
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
								text: "Registro Creado con exito!!",
								type: "success",
								timer: 1500,
								showConfirmButton: false
						});

						$('#lgmNuevo').modal('hide');
						$('#unidadnegocio').val('');
						table.ajax.reload();
						armarTablaTarifas($('#any').val());
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
		});


		$('.modificar').click(function(){

			//información del formulario
			var formData = new FormData($(".formulario")[1]);
			var message = "";
			//hacemos la petición ajax
			$.ajax({
				url: '../../ajax/ajax.php',
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
						armarTablaTarifas($('#any').val());
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
		});


		$('.modificarTarifa').click(function(){

			//información del formulario
			var formData = new FormData($(".formulario")[2]);
			var message = "";
			//hacemos la petición ajax
			$.ajax({
				url: '../../ajax/ajax.php',
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

						$('#lgmModificarTarifa').modal('hide');
						table.ajax.reload();
						armarTablaTarifas($('#any').val());
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
		});

		$('.nuevoCliente').click(function(){

			//información del formulario
			var formData = new FormData($(".formulario")[4]);
			var message = "";
			//hacemos la petición ajax
			$.ajax({
				url: '../../ajax/ajax.php',
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
								text: "Registro Creado con exito!!",
								type: "success",
								timer: 1500,
								showConfirmButton: false
						});

						$('#lgmNuevo').modal('hide');
						location.reload();
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
		});


		$('.nuevoPagare').click(function(){

			//información del formulario
			var formData = new FormData($(".formulario")[5]);
			var message = "";
			//hacemos la petición ajax
			$.ajax({
				url: '../../ajax/ajax.php',
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
								text: "Registro Creado con exito!!",
								type: "success",
								timer: 1500,
								showConfirmButton: false
						});

						$('#lgmNuevo').modal('hide');
						location.reload();
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
		});




	});
</script>








</body>
<?php } ?>
</html>
