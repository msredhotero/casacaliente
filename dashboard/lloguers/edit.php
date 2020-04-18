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

if (isset($_GET['id'])) {
	$id = $_GET['id'];
} else {
	$id = 0;
}




$fecha = date('Y-m-d');

//$resProductos = $serviciosProductos->traerProductosLimite(6);
$resMenu = $serviciosHTML->menu($_SESSION['nombre_sahilices'],"Lloguers",$_SESSION['refroll_sahilices'],$_SESSION['email_sahilices']);

$configuracion = $serviciosReferencias->traerConfiguracion();

$tituloWeb = mysql_result($configuracion,0,'sistema');

$breadCumbs = '<a class="navbar-brand" href="../index.php">Dashboard</a>';

/////////////////////// Opciones pagina ///////////////////////////////////////////////
$singular = "Lloguer - Edit";

$plural = "Lloguers - Edit";

$eliminar = "eliminarLloguers";

$insertar = "insertarLloguers";

$modificar = "modificarLloguers";

//////////////////////// Fin opciones ////////////////////////////////////////////////

$resultado = $serviciosReferencias->traerLloguersPorIdCompleto($id);

$nro 			= mysql_result($resultado,0,'nrolloguer');
$entredaR 	= mysql_result($resultado,0,'entradacorta');
$sortidaR 	= mysql_result($resultado,0,'sortidacorta');
$totalR		= mysql_result($resultado,0,'total');

//die(var_dump($totalR));

$plural .= $plural.' - Nro: '.$nro;

/////////////////////// Opciones para la creacion del formulario  /////////////////////
$tabla 			= "dblloguers";

$lblCambio	 	= array('refclientes','refubicaciones','datalloguer','numpertax','persset','maxtaxa','refestados');
$lblreemplazo	= array('Client','Ubicaciones','Data Contracte','N° Pers Taxa','Pers Total','Max Taxa','Estat');


$resVar1 = $serviciosReferencias->traerClientesPorId(mysql_result($resultado,0,'refclientes'));
$cadRef1 	= $serviciosFunciones->devolverSelectBoxActivo($resVar1,array(1,2,13),' ',mysql_result($resultado,0,'refclientes'));

$apyn = mysql_result($resVar1, 0,'cognom').' '.mysql_result($resVar1, 0,'nom').' '.mysql_result($resVar1, 0,'nif');



if ($_SESSION['idlocatario_sahilices'] == '') {
	$resVar2 = $serviciosReferencias->traerUbicaciones();
	$cadRef2 	= $serviciosFunciones->devolverSelectBoxArray($resVar2,array(4,1,2,6),array(' - Dor: ',' - Color: ',' - Empresa: ',''),'');
} else {
	$resVar2 = $serviciosReferencias->traerUbicacionesPorLocatario($_SESSION['idlocatario_sahilices']);
	$cadRef2 	= $serviciosFunciones->devolverSelectBoxArray($resVar2,array(4,1,2),array(' - Dor: ',' - Color: ',''),'');
}



$resVar3 = $serviciosReferencias->traerEstados();
$cadRef3 	= $serviciosFunciones->devolverSelectBoxActivo($resVar3,array(1),'',mysql_result($resultado,0,'refestados'));


$refdescripcion = array(0 => $cadRef1,1=>$cadRef2, 2=>$cadRef3);
$refCampo 	=  array('refclientes','refubicaciones','refestados');

$frmUnidadNegocios 	= $serviciosFunciones->camposTablaModificar($id, 'idlloguer',$modificar,$tabla,$lblCambio,$lblreemplazo,$refdescripcion,$refCampo);
//////////////////////////////////////////////  FIN de los opciones //////////////////////////

/////////////////////// Opciones para la creacion del formulario  /////////////////////
$tablaP 			= "dblloguersadicional";

$insertarP = "insertarLloguersadicional";
$idTablaP = "idllogueradicional";

$lblCambioP	 	= array("reflloguers",'personas');
$lblreemplazoP	= array("Lloguer",'Adultos');

$resVar1P = $serviciosReferencias->traerLloguersPorIdAux($id);
$cadRef1P 	= $serviciosFunciones->devolverSelectBoxActivo($resVar1P,array(4,5),' - ', $id);


$refdescripcionP = array(0=>$cadRef1P);
$refCampoP 	=  array('reflloguers');

$formularioPersones = $serviciosFunciones->camposTablaViejo($insertarP ,$tablaP,$lblCambioP,$lblreemplazoP,$refdescripcionP,$refCampoP);
//////////////////////////////////////////////  FIN de los opciones //////////////////////////


$resTaxa = $serviciosReferencias->traerTaxa();

$taxaPer = mysql_result($resTaxa,0,1);
$taxaTur = mysql_result($resTaxa,0,2);
$maxTaxaTur = mysql_result($resTaxa,0,3);

$resFormaPago = $serviciosReferencias->traerFormaspagos();
$cadFormaPago = $serviciosFunciones->devolverSelectBox($resFormaPago,array(1),'');

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

	<link rel="stylesheet" type="text/css" href="../../css/default.css"/>
	<link rel="stylesheet" type="text/css" href="../../css/default.date.css"/>

	<!-- CSS file -->
	<link rel="stylesheet" href="../../css/easy-autocomplete.min.css">
	<!-- Additional CSS Themes file - not required-->
	<link rel="stylesheet" href="../../css/easy-autocomplete.themes.min.css">


	<style>
		.alert > i{ vertical-align: middle !important; }
		.contDisponibilidad table tbody tr td { border: 1px solid #444; }
		.contDisponibilidad table thead tr th { border: 1px solid #222 !important; }
		.easy-autocomplete-container { width: 400px; z-index:999999 !important; }
		.modal-dialog2 {
		  width: 100%;
		  height: 100%;
		  margin: 10px 10px;
		  padding: 0;
		}

		.highlight {
		  background-color: #FF9;
		}

		.modal-content2 {
		  height: auto;
		  min-height: 100%;
		  border-radius: 0;
		}

		.bs-searchbox {
			margin-left:25px !important;
		}

		.dropdown-menu .inner {
			margin-left:25px !important;
		}
		#refclientesaux { width: 400px; }

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
							<form class="form" id="frmModificar">

								<div class="row">
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display:block">
											<div class="form-line">
												<label for="refclientes" class="control-label" style="text-align:left">Client</label>
												<input tabindex="1" id="refclientesaux" name="refclientesaux" value="<?php echo $apyn; ?>" required>
												<input type="hidden" id="refclientes" name="refclientes" value='0'/>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display:block">
											<div class="form-line">
												<label for="refubicaciones" class="control-label" style="text-align:left">Ubicaciones</label>
												<select tabindex="2" class="form-control show-tick" data-live-search="true" id="refubicaciones" name="refubicaciones" required>
													<?php echo $cadRef2; ?>
												</select>
											</div>
										</div>
									</div>
									<div class="row">

										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6" style="display:block">
											<label class="form-label">Data Contracte</label>
											<div class="form-group">
												<div class="form-line">
													<input tabindex="3" type="text" class="form-control" id="datalloguer" name="datalloguer" />

												</div>
											</div>
										</div>

										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6" style="display:block">
			                         <label class="form-label">Entrada</label>
			                         <div class="input-group">

												 <span class="input-group-addon">
													  <i class="material-icons">date_range</i>
												 </span>
			                             <div class="form-line">
												   	<input tabindex="4" type="text" class="form-control" id="entradaR" name="entradaR" value="<?php echo $entredaR; ?>" required />
			                             </div>

			                         </div>
			                     </div>

										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6" style="display:block">
			                         <label class="form-label">Sortida</label>
			                         <div class="input-group">

			                             <span class="input-group-addon">
			                                 <i class="material-icons">date_range</i>
			                             </span>
			                             <div class="form-line">
												   	<input tabindex="5" type="text" class="form-control" id="sortidaR" name="sortidaR" value="<?php echo $sortidaR; ?>" required />

			                             </div>
			                         </div>
			                     </div>
									</div>

									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display:none">
											<label class="form-label">N° Pers Taxa</label>
											<div class="form-group">
												<div class="form-line">
													<input tabindex="66" type="number" class="form-control" id="numpertax" name="numpertax" />

												</div>
											</div>
										</div>

										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display:none">
											<label class="form-label">Pers Total</label>
											<div class="form-group">
												<div class="form-line">
													<input tabindex="77" type="number" class="form-control" id="persset" name="persset" />

												</div>
											</div>
										</div>
									</div>

									<div class="row">

										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-3" style="display:block">
											<label for="taxa" class="control-label" style="text-align:left">Taxa</label>
											<div class="input-group">
			                           <span class="input-group-addon">€</span>
			                           <div class="form-line">
			                              <input tabindex="6" type="text" class="form-control" id="taxa" name="taxa" value="" >
			                           </div>
			                           <span class="input-group-addon">.00</span>
			                        </div>
										</div>

										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-3" style="display:block">
											<label for="Max Taxa" class="control-label" style="text-align:left">Max Taxa</label>
											<div class="input-group">
			                           <span class="input-group-addon">€</span>
			                           <div class="form-line">
			                              <input tabindex="7" type="text" class="form-control" id="maxtaxa" name="maxtaxa" value="" >
			                           </div>
			                           <span class="input-group-addon">.00</span>
			                        </div>
										</div>

										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6" style="display:block">
											<div class="form-line">
												<label for="refestados" class="control-label" style="text-align:left">Estat</label>
												<select tabindex="8" class="form-control show-tick" id="refestados" name="refestados">
													<?php echo $cadRef3; ?>
												</select>
											</div>
										</div>

									</div>

									<div class="row">

										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display:block;font-size:16px;">
											<label for="total" class="control-label" style="text-align:left; color:red;">Total Importe Estadia</label>
											<div class="input-group">
			                           <span class="input-group-addon">€</span>
			                           <div class="form-line">
			                              <input type="text" class="form-control" id="total" name="total" value="<?php echo $totalR; ?>" >
			                           </div>
			                           <span class="input-group-addon">.00</span>
			                        </div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="alert bg-deep-orange alert-dismissible">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
										¡Recuerde que si modifica el <b>Total</b> y tiene pagos realizados deberá modificarlos!.
									</div>
								</div>
								<div class="row">
									<button tabindex="14" type="button" class="btn bg-orange waves-effect" id="btnModificarLloguer"><i class="material-icons">edit</i> <span>MODIFICAR</span></button>
								</div>
								<input type="hidden" name="accion" id="accion" value="modificarLloguers" />
								<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
							</form>

							<div class="row">
								<hr>
								<h4>Persones</h4>
							</div>
							<form class="formulario" role="form" id="frmPersones">
								<div class="row frmAjaxGrilla">

								</div>
								<div class="row frmAjaxNuevo">

								</div>
								<div class="row">
									<button tabindex="16" type="button" class="btn bg-green waves-effect" id="btnNuevoPersones"><i class="material-icons">add</i> <span>AGREGAR</span></button>
								</div>
								<input type="hidden" name="accion" id="accion" value="insertarLloguersadicional" />
								<input type="hidden" name="reflloguers" id="reflloguers" value="<?php echo $id; ?>" />

							</form>
							<hr>
							<div class="row">
								<hr>
								<h4>Pagos Realitzat</h4>
							</div>
							<form class="formulario" role="form" id="frmPagosRealizados">
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 margTop" style="display:block;">
										<div class="row">
											<label class="form-label" style="font-size: 18px; font-weight: bold;">Total a Pagar Final</label>
											<div class="form-line">
												<input style="width:200px;border: 0;" value="0" type="text" class="form-control" id="totalapagarcliente" name="totalapagarcliente" required />
											</div>
										</div>
										<div class="row">
											<label class="form-label">Falta Pagar</label>
											<div class="form-line">
												<input readonly="readonly" style="width:200px;border:0;" value="0" type="text" class="form-control" id="faltapagarcliente" name="faltapagarcliente" required />
											</div>
										</div>
									</div>



									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 margTop" style="display:block;">
										<div class="row">
											<label class="form-label">Realitzat Pag. 1°</label>
											<div class="form-line" style="width:250px;">
												<div class="input-group input-group-lg">
													<span class="input-group-addon">
														<input type="radio" class="with-gap" name="pagotaxaunico" id="pagotaxaunico1" value="1">
														<label for="pagotaxaunico1"></label>
													</span>
													<div class="form-line">
														<input value="0" type="text" class="form-control" id="cargarpago1" name="cargarpago1" required />

													</div>
													<span class="input-group-addon">
														<span style="color:green; text-align:left;" class="lblTaxaPaga1"></span>
													</span>

												</div>

											</div>
										</div>

										<div class="row">
											<label class="form-label">Realitzat Pag. 2°</label>
											<div class="form-line" style="width:250px;">
												<div class="input-group input-group-lg">

													<span class="input-group-addon">
														<input type="radio" class="with-gap" name="pagotaxaunico" id="pagotaxaunico2" value="2">
														<label for="pagotaxaunico2"></label>
													</span>
													<div class="form-line">
														<input value="0" type="text" class="form-control" id="cargarpago2" name="cargarpago2" required />
													</div>
													<span class="input-group-addon">
														<label style="color:green; text-align:left;" class="lblTaxaPaga2"></label>
													</span>

												</div>

											</div>
										</div>


									</div>

									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 margTop" style="display:block;">

										<div class="row">
											<label class="form-label">Forma de Pag. 1°</label>
											<div class="form-line">
												<select type="text" class="form-control" id="formapago1" name="formapago1" required />
												<?php echo $cadFormaPago; ?>
												</select>
											</div>
										</div>

										<div class="row">
											<label class="form-label">Forma de Pag. 2°</label>
											<div class="form-line">
												<select type="text" class="form-control" id="formapago2" name="formapago2" required />
												<?php echo $cadFormaPago; ?>
												</select>
											</div>
										</div>

									</div>

								</div>
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 margTop" style="display:block;">
										<div class="row">
											<label class="form-label">Fecha Pag. 1°</label>
											<div class="form-line">
												<input style="width:200px;border: 0;" value="0" type="text" class="form-control" id="fechapagocliente1" name="fechapagocliente1" required />
											</div>
										</div>
										<div class="row">
											<label class="form-label">Fecha Pag. 2°</label>
											<div class="form-line">
												<input style="width:200px;border: 0;" value="0" type="text" class="form-control" id="fechapagocliente2" name="fechapagocliente2" required />
											</div>
										</div>
									</div>

									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 margTop" style="display:block;">
										<div class="row">
											<label class="form-label">Valor Pag. 1°</label>
											<div class="form-line">
												<input value="0" style="width:200px;border: 0;" type="text" class="form-control" id="valorpagocliente1" name="valorpagocliente1" required />
											</div>
										</div>
										<div class="row">
											<label class="form-label">Valor Pag. 2°</label>
											<div class="form-line">
												<input value="0" style="width:200px;border: 0;" type="text" class="form-control" id="valorpagocliente2" name="valorpagocliente2" required />
											</div>
										</div>
										<div class="row">
											<label class="form-label">Taxa</label>
											<div class="form-line">
												<input value="0" style="width:200px;border: 0;" type="text" class="form-control" id="pagotaxacliente" name="pagotaxacliente" required />
											</div>
										</div>
									</div>
								</div><!-- fin del ultimo row -->
								<div class="row">
									<button tabindex="16" type="button" class="btn bg-orange waves-effect" id="btnModificarPagos"><i class="material-icons">edit</i> <span>MODIFICAR</span></button>
								</div>
								<input type="hidden" name="accion" id="accion" value="modificarPagoCliente" />
								<input type="hidden" name="idlloguerpagarecliente" id="idlloguerpagarecliente" value="<?php echo $id; ?>" />
							</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


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


			<!-- Pagos -->
				<form class="formulario" role="form" id="sign_in">
					<div class="modal fade" id="lgmNuevoPagoCliente" tabindex="-1" role="dialog">
						 <div class="modal-dialog modal-lg" role="document">
							  <div class="modal-content">
									<div class="modal-header">
										 <h4 class="modal-title" id="largeModalLabel">PAG. REALITZATS</h4>
									</div>
									<div class="modal-body">



									</div>
									<div class="modal-footer">
										<div class="btnFacturas" style="float:left;">

										</div>
										<button type="button" class="btn btn-primary waves-effect nuevoPagareCliente2">GUARDAR</button>
										<button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CERRAR</button>
									</div>
							  </div>
						 </div>
					</div>
					<input type="hidden" id="accion" name="accion" value="modificarPagoCliente"/>
					<input type="hidden" id="idlloguerpagarecliente" name="idlloguerpagarecliente" value="0"/>
				</form>



				<!-- NUEVO -->
				<form class="formulario" role="form" id="sign_in">
				   <div class="modal fade" id="lgmNuevoCualquiera" tabindex="-1" role="dialog">
				       <div class="modal-dialog modal-lg" role="document">
				           <div class="modal-content">
				               <div class="modal-header">
				                   <h4 class="modal-title" id="largeModalLabel"><span class="tituloNuevo"></span></h4>
				               </div>
				               <div class="modal-body demo-masked-input">
										<div class="row frmAjaxGrilla2">

										</div>
										<div class="row frmAjaxNuevo2">

										</div>
				               </div>
				               <div class="modal-footer">
				                   <button type="submit" class="btn btn-primary waves-effect nuevoCualquiera">GUARDAR</button>
				                   <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CERRAR</button>
				               </div>
				           </div>
				       </div>
				   </div>

				</form>


				<!-- ELIMINAR -->
					<form class="formulario" role="form" id="sign_in">
					   <div class="modal fade" id="lgmEliminarLA" tabindex="-1" role="dialog">
					       <div class="modal-dialog modal-lg" role="document">
					           <div class="modal-content">
					               <div class="modal-header">
					                   <h4 class="modal-title" id="largeModalLabel">ELIMINAR PERSONES ADDICIONALS</h4>
					               </div>
					               <div class="modal-body">
													 <p>¿Esta seguro que desea eliminar el registro?</p>
													 <small>* Si este registro esta relacionado con algun otro dato no se podría eliminar.</small>
					               </div>
					               <div class="modal-footer">
					                   <button type="button" class="btn btn-danger waves-effect eliminarLA">ELIMINAR</button>
					                   <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CERRAR</button>
					               </div>
					           </div>
					       </div>
					   </div>
						<input type="hidden" id="accion" name="accion" value="eliminarLloguersadicional"/>
						<input type="hidden" name="ideliminarLA" id="ideliminarLA" value="0">
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
	 <script src="../../js/moment-with-locales.js"></script>

<script src="../../plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>


<!-- Bootstrap Material Datetime Picker Plugin Js -->
<script src="../../plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

<script src="../../DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="../../js/datepicker-es.js"></script>

<script src="../../js/dateFormat.js"></script>
<script src="../../js/jquery.dateFormat.js"></script>

<script src="../../js/picker.js"></script>
<script src="../../js/picker.date.js"></script>

<script src="../../js/jquery.easy-autocomplete.min.js"></script>

<script>
	var indice = 1;
	$(document).ready(function(){

		var options = {

			url: "../../json/jsbuscarclientes.php",

			getValue: function(element) {
				return element.cognom + ' ' + element.nom + ' ' + element.locatario;
			},

			ajaxSettings: {
				dataType: "json",
				method: "POST",
				data: {
					busqueda: $("#refclientesaux").val()
				}
			},

			preparePostData: function (data) {
				data.busqueda = $("#refclientesaux").val();
				return data;
			},

			list: {
				maxNumberOfElements: 20,
				match: {
					enabled: true
				},
				onClickEvent: function() {
					var value = $("#refclientesaux").getSelectedItemData().id;
					//alert(value);
					$("#refclientes").val(value);

				}
			}
		};


		$("#refclientesaux").easyAutocomplete(options);

		$('#entradaR').inputmask('dd/mm/yyyy', { placeholder: '__/__/<?php echo date('Y'); ?>' });
		$('#sortidaR').inputmask('dd/mm/yyyy', { placeholder: '__/__/<?php echo date('Y'); ?>' });

		function validarmasivo(refclientes, refubicaciones, datalloguer, entrada, sortida, total, numpertax, persset, taxa, maxtaxa, refestados) {
		$.ajax({
			data:  {
				refclientes: refclientes,
				refubicaciones: refubicaciones,
				datalloguer: datalloguer,
				entrada: entrada,
				sortida: sortida,
				total: total,
				numpertax: numpertax,
				persset: persset,
				taxa: taxa,
				maxtaxa: maxtaxa,
				refestados: refestados,
				id: <?php echo $id; ?>,
				accion: 'modificarLloguers'},
			url:   '../../ajax/ajax.php',
			type:  'post',
			beforeSend: function () {
				$('.btnModificarLloguer').hide();
			},
			success:  function (response) {

				$('.btnModificarLloguer').show();
				if (response.error) {
					swal("Error!", response.data, "warning");
				} else {
					swal({
					  title: response.data + ', Desea guardar el Alquiler?',
					  text: "Una vez guardado finalizara su carga",
					  type: "success",
					  showCancelButton: true,
					  confirmButtonColor: "#28a745",
					  confirmButtonText: "Si, deseo guardar el Alquiler",
					  cancelButtonText: "No!",
					  closeOnConfirm: false,
					  closeOnCancel: false,
					  showLoaderOnConfirm: true
					},
					function(isConfirm) {
					  if (isConfirm) {
						  guardarLloguer();
						  /*
						  setTimeout(function () {
						    swal("Alquiler Cargado Correctamente!", "El alquiler se cargo de manera correcta.", "success");
						 }, 20000);
						 */

					  } else {
					    swal("Alquiler Sin Cargar!", "El Alquiler no fue guardado", "error");
					  }
					});

					//swal("Correcto!", response.data, "success");
				}


			}
		});
	}

	function devolverFechaCorrecta(fecha) {
		var cadNuevaFecha = fecha.split('/');

		return cadNuevaFecha[2] + '-' + cadNuevaFecha[1] + '-' + cadNuevaFecha[0];
	}

	$('#btnModificarLloguer').click(function() {
		var errorValida = false;
		var cadErrorValida = '';

		var cadFechaSortida = '';

		if ($('#refclientes').val() == '') {
			cadErrorValida += 'Es obligatorio seleccionar un Cliente. \
			\n';
			errorValida = true;
		}

		if ($('#refubicaciones').val() == '') {
			cadErrorValida += 'Es obligatorio seleccionar una Ubicacion. \
			\n';
			errorValida = true;
		}

		if (devolverFechaCorrecta($('#entradaR').val()) >= devolverFechaCorrecta($('#sortidaR').val())) {

			cadErrorValida += 'La fecha de Sortida no puede ser menor que la de Entrada. \
			\n';
			errorValida = true;
		}


		if (errorValida) {
			swal("Error!", cadErrorValida, "warning");
		} else {

			swal({
			  title: ' Desea modificar el Lloguer?',
			  text: "Una vez modificado finalizara su carga",
			  type: "success",
			  showCancelButton: true,
			  confirmButtonColor: "#28a745",
			  confirmButtonText: "Si, deseo modificar el Lloguer",
			  cancelButtonText: "No!",
			  closeOnConfirm: false,
			  closeOnCancel: false,
			  showLoaderOnConfirm: true
			},
			function(isConfirm) {
			  if (isConfirm) {
				  guardarLloguer();
				  /*
				  setTimeout(function () {
					 swal("Alquiler Cargado Correctamente!", "El alquiler se cargo de manera correcta.", "success");
				 }, 20000);
				 */

			  } else {
				 swal("Alquiler Sin Cargar!", "El Alquiler no fue guardado", "error");
			  }
			});
		}

		//validarmasivo($('#refclientes').val(), $('#refubicaciones').val(), $('#datalloguer').val(), $('#entrada').val(), $('#sortida').val(), $('#total').val(), $('#numpertax').val(), $('#persset').val(), $('#taxa').val(), $('#maxtaxa').val(), $('#refestados').val(), indice);


	});


		$('.btnDisponibilidad').click(function() {
			traerDisponibilidad();
		});



		$('.btnFacturas').on("click",'.rptFactura', function() {
			idTable =  $(this).attr("id");
			window.open("../../reportes/rptFacturaCC.php?id=" + idTable,'_blank');
		});


		$('#lgmNuevo').on("click",'.quitarRenglon', function() {
			indice -= 1;
			usersid =  $(this).attr("id");
			$('.renglon' + usersid).remove();
		});

		$('#lgmNuevo').on("click",'.agregarRenglon', function() {
			indice += 1;
			$('.lstPersonaLloguer').append('<div class="row renglon' + indice + '" style="padding-left: 20px;"> \
			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="display:block"> \
				<label class="form-label">Adultos</label> \
				<div class="form-group"> \
					<div class="form-line"> \
						<input type="number" value="1" class="form-control personasLloguer" id="personas' + indice + '" name="personas' + indice + '" required=""> \
					</div> \
				</div> \
			</div> \
			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="display:block"> \
				<label class="form-label">Menores</label> \
				<div class="form-group"> \
					<div class="form-line"> \
						<input type="number" value="0" class="form-control menoresLloguer" id="menores' + indice + '" name="menores' + indice + '" required=""> \
					</div> \
				</div> \
			</div> \
			<div class="col-lg-3 col-md-3 col-sm-4 col-xs-4" style="display:block"> \
				 <label class="form-label">Entrada</label> \
				 <div class="input-group"> \
					  <span class="input-group-addon"> \
							<i class="material-icons">date_range</i> \
					  </span> \
					  <div class="form-line"> \
							<input type="text" class="datepickerNuevo form-control entradaImp" id="entradapersonas' + indice + '" name="entradapersonas' + indice + '" value="' + $('#entrada').val() + '" required /> \
					  </div> \
				 </div> \
			</div> \
			<div class="col-lg-3 col-md-3 col-sm-4 col-xs-4" style="display:block"> \
				 <label class="form-label">Sortida</label> \
				 <div class="input-group"> \
					  <span class="input-group-addon"> \
							<i class="material-icons">date_range</i> \
					  </span> \
					  <div class="form-line"> \
							<input type="text" class="datepickerNuevo form-control sortidaImp" id="sortidapersonas' + indice + '" name="sortidapersonas' + indice + '" value="' + $('#sortida').val() + '" required /> \
					  </div> \
				 </div> \
			</div> \
			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="display:block"> \
				<label class="form-label">Acciones</label> \
				<div class="form-group"> \
					<div class="form-line" style="border:none;"> \
					  <button id="' + indice + '" type="button" class="btn bg-red btn-circle waves-effect waves-circle waves-float quitarRenglon"> \
						  <i class="material-icons">remove_circle</i> \
					 </button> \
					</div> \
				</div> \
			</div></div>');

			$('.datepickerNuevo').inputmask('dd/mm/yyyy', { placeholder: '__/__/<?php echo date('Y'); ?>' });
		});

		$("#lgmNuevo").on("change",'.personasLloguer', function(){
			if ($(this).val() <= 0) {
				$(this).val(1);
			}
		});

		function traerDisponibilidad() {
			$.ajax({
				url: '../../ajax/ajax.php',
				type: 'POST',
				// Form data
				//datos del formulario
				data: {accion: 'traerDisponibilidad',any: 2019},
				//mientras enviamos el archivo
				beforeSend: function(){
					$('.contDisponibilidad').html('');
				},
				//una vez finalizado correctamente
				success: function(data){

					if (data != '') {
						$('.contDisponibilidad').html(data);
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


		$('#entradapersonas1').inputmask('dd/mm/yyyy', { placeholder: '__/__/____' });
		$('#sortidapersonas1').inputmask('dd/mm/yyyy', { placeholder: '__/__/____' });

		$( "#fechapago1" ).datepicker({ dateFormat: 'dd/mm/yy' });
		$( "#fechapago1" ).val('<?php echo date('d/m/Y', strtotime($date.' + 5 days')); ?>');
		$( "#fechapagocliente1" ).val('<?php echo date('d/m/Y', strtotime($date.' + 5 days')); ?>');
		$( "#fechapago2" ).datepicker({ dateFormat: 'dd/mm/yy' });
		$( "#fechapago2" ).val('<?php echo date('d/m/Y', strtotime($date.' + 30 days')); ?>');

		$( "#entradaR" ).change(function() {
			$('#entrada').val($('#entradaR').val());
		});

		$('#taxa').number(true,2,'.','');
		$('#maxtaxa').number(true,2,'.','');
		$('#valorpago1').number(true,2,'.','');
		$('#valorpago2').number(true,2,'.','');
		$('#pagotaxa').number(true,2,'.','');

		$('#cargarpago1').number(true,2,'.','');
		$('#cargarpago2').number(true,2,'.','');

		$('#cargarpago1').dblclick(function() {
			$(this).val($('#totalapagarcliente').val() / 2);
		});

		$('#cargarpago2').dblclick(function() {
			$(this).val($('#totalapagarcliente').val() / 2);
		});

		$('#totalapagarcliente').number(true,2,'.','');
		$('#pagotaxacliente').number(true,2,'.','');
		$('#valorpagocliente1').number(true,2,'.','');
		$('#valorpagocliente2').number(true,2,'.','');


		$('#datalloguer').val('<?php echo date('d/m/Y'); ?>');
		/*
		$('#entrada').val('<?php //echo date('d/m/Y'); ?>');
		$('#sortida').val('<?php //echo date('d/m/Y', strtotime($date.' + 7 days')); ?>');

		$('#entradapersonas1').val('<?php //echo date('d/m/Y'); ?>');
		$('#sortidapersonas1').val('<?php //echo date('d/m/Y', strtotime($date.' + 7 days')); ?>');
		*/


		$('#numpertax').val(2);
		$('#persset').val(2);

		$('#maxtaxa').val(<?php echo $maxTaxaTur; ?>);
		$('#taxa').val(<?php echo $taxaTur; ?>);

		$('#sortidaR').change(function() {
			devolverTarifa($('#refubicaciones').val(), $('#entradaR').val(), $('#sortidaR').val(), $('#numpertax').val());
		});

		$('#entradaR').change(function() {
			devolverTarifa($('#refubicaciones').val(), $('#entradaR').val(), $('#sortidaR').val(), $('#numpertax').val());
		});

		$('#refubicaciones').change(function() {
			devolverTarifa($('#refubicaciones').val(), $('#entradaR').val(), $('#sortidaR').val(), $('#numpertax').val());
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

						if (data.pagos.primerpago != 0) {
							alert('asd');
							$( "#fechapago1" ).val(formato(data.pagos.primerpago));
						}
						if (data.pagos.segundopago != 0) {
							alert('asd2');
							$( "#fechapago2" ).val(formato(data.pagos.segundopago));
						}

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


		function devolverTarifaArrayPagament(id) {
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

					$('.lblTaxaPaga1').html('');
					$('.lblTaxaPaga2').html('');
					$('#pagotaxacliente').val(0);
					$('.btnFacturas').html('');
					$("#formapago1 option:selected").attr('disabled','disabled').siblings().removeAttr('disabled');
					$("#formapago2 option:selected").attr('disabled','disabled').siblings().removeAttr('disabled');
				},
				//una vez finalizado correctamente
				success: function(data){

					$('#totalapagarcliente').val( parseFloat(data.datos.total) + parseFloat(data.pagos.tasapersona) + parseFloat(data.pagos.taxa));
					$('#faltapagarcliente').val(data.datos.falta);

					$('#fechapagocliente2').datepicker({ dateFormat: 'dd/mm/yy' });
					$('#fechapagocliente1').datepicker({ dateFormat: 'dd/mm/yy' });

					if (data.pagos.existe == 1) {

						if (data.pagos.idpago1 > 0) {
							$('.btnFacturas').append('<button type="button" class="btn bg-deep-orange waves-effect rptFactura" id="' + data.pagos.idpago1 + '"><i class="material-icons">receipt</i>IMPRIMIMR FACTURA 1er PAGO</button>');
						}

						if (data.pagos.idpago2 > 0) {
							$('.btnFacturas').append('<button type="button" class="btn bg-deep-orange waves-effect rptFactura" id="' + data.pagos.idpago2 + '"><i class="material-icons">receipt</i>IMPRIMIMR FACTURA 2do PAGO</button>');
						}

						$('#valorpagocliente1').val(parseFloat((parseFloat(data.datos.total) + parseFloat(data.datos.taxapersona)) / 2) + parseFloat(data.datos.taxaturistica));
						$('#valorpagocliente2').val(parseFloat((parseFloat(data.datos.total) + parseFloat(data.datos.taxapersona)) / 2));
						$('#pagotaxacliente').val(data.pagos.taxa);
						if (data.pagos.primerpago != 0) {
							$( "#fechapagocliente1" ).val(formato(data.pagos.primerpago));
						}

						if (data.pagos.segundopago != 0) {
							$( "#fechapagocliente2" ).val(formato(data.pagos.segundopago));
						}

						$( "#cargarpago1" ).val(data.pagos.monto1);
						$( "#cargarpago2" ).val(data.pagos.monto2);

						$( "#formapago1" ).val(data.pagos.formapago1);
						$( "#formapago2" ).val(data.pagos.formapago2);

						if (data.pagos.formapago1 == 1) {
							$("#formapago1 option:selected").attr('disabled','disabled').siblings().removeAttr('disabled');
						}

						if (data.pagos.formapago2 == 1) {
							$("#formapago2 option:selected").attr('disabled','disabled').siblings().removeAttr('disabled');
						}

						$('#formapago1').selectpicker('refresh');
						$('#formapago2').selectpicker('refresh');

						if (data.pagos.taxaunica == 1) {
							$('#pagotaxaunico1').prop('checked',true);
							$('.lblTaxaPaga1').html('+ ' + data.pagos.taxa);
						}
						if (data.pagos.taxaunica == 2) {
							$('#pagotaxaunico2').prop('checked',true);
							$('.lblTaxaPaga2').html('+ ' + data.pagos.taxa);
						}

					} else {
						$('#valorpagocliente1').val(parseFloat((parseFloat(data.datos.total) + parseFloat(data.datos.taxapersona)) / 2) + parseFloat(data.datos.taxaturistica));
						$('#valorpagocliente2').val(parseFloat((parseFloat(data.datos.total) + parseFloat(data.datos.taxapersona)) / 2));
						$('#pagotaxacliente').val(data.datos.taxaturistica);
						$( "#fechapagocliente2" ).val(formato(data.datos.fechasegundopago));
						$( "#fechapagocliente1" ).val(formato(data.pagos.primerpago));
						$( "#cargarpago1" ).val(0);
						$( "#cargarpago2" ).val(0);
						$( "#formapago1" ).val(1);
						$( "#formapago2" ).val(1);

						$('#formapago1').selectpicker('refresh');
						$('#formapago2').selectpicker('refresh');
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

		/*devolverTarifa($('#refubicaciones').val(), $('#entradaR').val(), $('#sortidaR').val(), $('#numpertax').val());*/

		var $demoMaskedInput = $('.demo-masked-input');

		$('#desdeperiode').inputmask('yyyy-mm-dd', { placeholder: '____-__-__' });
		$('#finsaperiode').inputmask('yyyy-mm-dd', { placeholder: '____-__-__' });



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
						$('.show-tick').selectpicker({
							liveSearch: true
						});
						$('.show-tick').selectpicker('refresh');


						$('.datepicker').bootstrapMaterialDatePicker({
				        format: 'DD/MM/YYYY',
						  lang : 'ca',
				        clearButton: true,
				        weekStart: 1,
				        time: false
				   	});
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


		$("#example").on("click",'.btnContratos', function(){

			idTable =  $(this).attr("id");

			window.open("../../reportes/rptContratoIdioma.php?id=" + idTable + "&idioma=catalan" ,'_blank');
		});


		$(".frmAjaxModificar").on("change",'#sortida', function(){
			devolverTarifa($('.frmAjaxModificar #refubicaciones').val(), $('.frmAjaxModificar #entrada').val(), $('.frmAjaxModificar #sortida').val(), $('.frmAjaxModificar #numpertax').val());
		});

		$(".frmAjaxModificar").on("change",'#entrada', function(){
			devolverTarifa($('.frmAjaxModificar #refubicaciones').val(), $('.frmAjaxModificar #entrada').val(), $('.frmAjaxModificar #sortida').val(), $('.frmAjaxModificar #numpertax').val());
		});

		$(".frmAjaxModificar").on("change",'#refubicaciones', function(){
			devolverTarifa($('.frmAjaxModificar #refubicaciones').val(), $('.frmAjaxModificar #entrada').val(), $('.frmAjaxModificar #sortida').val(), $('.frmAjaxModificar #numpertax').val());
		});

		$(".frmAjaxModificar").on("change",'#numpertax', function(){
			devolverTarifa($('.frmAjaxModificar #refubicaciones').val(), $('.frmAjaxModificar #entrada').val(), $('.frmAjaxModificar #sortida').val(), $('.frmAjaxModificar #numpertax').val());
			$('.frmAjaxModificar #persset').val($('.frmAjaxModificar #numpertax').val());
		});

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


		$("#example").on("click",'.btnPagar', function(){

			idTable =  $(this).attr("id");

			$('#idlloguerpagarecliente').val(idTable);

			devolverTarifaArrayPagament(idTable);
			$('#lgmNuevoPagoCliente').modal();
		});


		function frmAjaxEliminar(id, accion, contenedor) {
			$.ajax({
				url: '../../ajax/ajax.php',
				type: 'POST',
				// Form data
				//datos del formulario
				data: {accion: accion, id: id},
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
						$('#' + contenedor).modal('toggle');

						frmAjaxNuevo(<?php echo $id; ?>, 'dblloguersadicional');

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
			frmAjaxEliminar($('#ideliminar').val(), 'eliminarLloguers', 'lgmEliminar');
		});


		$(".frmAjaxGrilla").on("click",'.btnEliminarLA', function(){
			idTable =  $(this).attr("id");
			$('#ideliminarLA').val(idTable);
			$('#lgmEliminarLA').modal();
		});//fin del boton eliminar

		$('.eliminarLA').click(function() {
			frmAjaxEliminar($('#ideliminarLA').val(), 'eliminarLloguersadicional', 'lgmEliminarLA');
		});

		$("#example").on("click",'.btnModificar', function(){
			idTable =  $(this).attr("id");
			frmAjaxModificar(idTable);
			$('#lgmModificar').modal();
		});//fin del boton modificar

		function guardarLloguer() {

			//información del formulario
			var formData = new FormData($("#frmModificar")[0]);
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
						devolverTarifaArrayPagament(<?php echo $id; ?>);
						devolverTarifa($('#refubicaciones').val(), $('#entradaR').val(), $('#sortidaR').val(), $('#numpertax').val());

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

		$('#btnModificarPagos').click(function(){

			//información del formulario
			var formData = new FormData($("#frmPagosRealizados")[0]);
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

		$('#btnNuevoPersones2').click(function(){
			var formData = new FormData($("#frmPersones").val());
			alert(formData.personas);
		});

		$('#btnNuevoPersones').click(function(){

			//información del formulario
			var formData = new FormData($("#frmPersones")[0]);
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


		frmAjaxNuevo(<?php echo $id; ?>, 'dblloguersadicional');

		$("#example").on("click",'.btnAgregarPersonas', function(){

			var tabla =  'dblloguersadicional';
			var id = $(this).attr("id");
			$('.tituloNuevo').html('PERSONES ADDICIONALS');
			$('#accion').html('insertarLloguersadicional');
			$('#lgmNuevoCualquiera').modal();
			frmAjaxNuevo(id, tabla);

		});//fin del boton nuevo planata

		$(".frmAjaxGrilla").on("click",'.btnEliminarLA', function(){

			var tabla =  'dblloguersadicional';
			var id = $(this).attr("id");
			$('.tituloNuevo').html('PERSONES ADDICIONALS');
			$('#accion').html('insertarLloguersadicional');
			//$('#lgmNuevoCualquiera').modal();
			frmAjaxNuevo(id, tabla);

		});//fin del boton nuevo planata



		function frmAjaxNuevo(id, tabla) {
			$.ajax({
				url: '../../ajax/ajax.php',
				type: 'POST',
				// Form data
				//datos del formulario
				data: {accion: 'frmAjaxNuevo',tabla: tabla, id: id},
				//mientras enviamos el archivo
				beforeSend: function(){

					$('.frmAjaxNuevo2').html('');
					$('.frmAjaxGrilla2').html('');
				},
				//una vez finalizado correctamente
				success: function(data){

					if (data != '') {
						$('.frmAjaxNuevo').html(data.formulario);
						$('.frmAjaxNuevo #entrada').val(data.aux.desde);
						$('.frmAjaxNuevo #sortida').val(data.aux.hasta);
						$('.frmAjaxGrilla').html(data.aux.vista);
						$('#personas').val(2);
						$('#menores').val(0);
						$('#entrada').inputmask('dd/mm/yyyy', { placeholder: '__/__/____' });
						$('#sortida').inputmask('dd/mm/yyyy', { placeholder: '__/__/____' });
						$('#entrada').attr('readonly',false);
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

		devolverTarifaArrayPagament(<?php echo $id; ?>);


	});
</script>


</body>
<?php } ?>
</html>
