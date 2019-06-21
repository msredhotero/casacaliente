<?php

include ('../includes/funcionesUsuarios.php');
include ('../includes/funciones.php');
include ('../includes/funcionesHTML.php');
include ('../includes/funcionesReferencias.php');
include ('../includes/funcionesNotificaciones.php');
include ('../includes/validadores.php');


$serviciosUsuarios  		= new ServiciosUsuarios();
$serviciosFunciones 		= new Servicios();
$serviciosHTML				= new ServiciosHTML();
$serviciosReferencias		= new ServiciosReferencias();
$serviciosNotificaciones	= new ServiciosNotificaciones();
$serviciosValidador        = new serviciosValidador();


$accion = $_POST['accion'];

$resV['error'] = '';
$resV['mensaje'] = '';



switch ($accion) {
    case 'login':
        enviarMail($serviciosUsuarios);
        break;
	case 'entrar':
		entrar($serviciosUsuarios);
		break;
	case 'insertarUsuario':
        insertarUsuario($serviciosUsuarios);
        break;
	case 'modificarUsuario':
        modificarUsuario($serviciosUsuarios);
        break;
	case 'registrar':
		registrar($serviciosUsuarios);
		break;
   case 'registrarme':
      registrarme($serviciosUsuarios, $serviciosReferencias, $serviciosValidador);
   break;
   case 'insertarUsuarios':
        insertarUsuarios($serviciosReferencias);
   break;
   case 'recuperar':
      recuperar($serviciosUsuarios);
   break;

   case 'eliminarUsuarios':
      eliminarUsuarios($serviciosUsuarios, $serviciosReferencias);
   break;


   case 'insertarClientes':
insertarClientes($serviciosReferencias);
break;
case 'modificarClientes':
modificarClientes($serviciosReferencias);
break;
case 'eliminarClientes':
eliminarClientes($serviciosReferencias);
break;
case 'traerClientes':
traerClientes($serviciosReferencias);
break;
case 'traerClientesPorId':
traerClientesPorId($serviciosReferencias);
break;
case 'insertarPeriodos':
insertarPeriodos($serviciosReferencias);
break;
case 'modificarPeriodos':
modificarPeriodos($serviciosReferencias);
break;
case 'eliminarPeriodos':
eliminarPeriodos($serviciosReferencias);
break;
case 'traerPeriodos':
traerPeriodos($serviciosReferencias);
break;
case 'traerPeriodosPorId':
traerPeriodosPorId($serviciosReferencias);
break;
case 'insertarTarifas':
insertarTarifas($serviciosReferencias);
break;
case 'modificarTarifas':
modificarTarifas($serviciosReferencias);
break;
case 'modificarTarifaSola':
   modificarTarifaSola($serviciosReferencias);
break;
case 'eliminarTarifas':
eliminarTarifas($serviciosReferencias);
break;
case 'traerTarifas':
traerTarifas($serviciosReferencias);
break;
case 'traerTarifasPorId':
traerTarifasPorId($serviciosReferencias);
break;
case 'insertarUbicaciones':
insertarUbicaciones($serviciosReferencias);
break;
case 'modificarUbicaciones':
modificarUbicaciones($serviciosReferencias);
break;
case 'eliminarUbicaciones':
eliminarUbicaciones($serviciosReferencias);
break;
case 'traerUbicaciones':
traerUbicaciones($serviciosReferencias);
break;
case 'traerUbicacionesPorId':
traerUbicacionesPorId($serviciosReferencias);
break;

case 'insertarFormaspagos':
insertarFormaspagos($serviciosReferencias);
break;
case 'modificarFormaspagos':
modificarFormaspagos($serviciosReferencias);
break;
case 'eliminarFormaspagos':
eliminarFormaspagos($serviciosReferencias);
break;
case 'traerFormaspagos':
traerFormaspagos($serviciosReferencias);
break;
case 'traerFormaspagosPorId':
traerFormaspagosPorId($serviciosReferencias);
break;
case 'insertarTipoubicacion':
insertarTipoubicacion($serviciosReferencias);
break;
case 'modificarTipoubicacion':
modificarTipoubicacion($serviciosReferencias);
break;
case 'eliminarTipoubicacion':
eliminarTipoubicacion($serviciosReferencias);
break;
case 'traerTipoubicacion':
traerTipoubicacion($serviciosReferencias);
break;
case 'traerTipoubicacionPorId':
traerTipoubicacionPorId($serviciosReferencias);
break;

case 'frmAjaxModificar':
frmAjaxModificar($serviciosFunciones, $serviciosReferencias, $serviciosUsuarios);
break;

case 'armarTablaTarifas':
   armarTablaTarifas($serviciosReferencias);
break;

case 'insertarLloguers':
   insertarLloguers($serviciosReferencias);
break;
case 'modificarLloguers':
   modificarLloguers($serviciosReferencias);
break;
case 'eliminarLloguers':
   eliminarLloguers($serviciosReferencias);
break;
/* Fin */

}
/* Fin */

function insertarLloguers($serviciosReferencias) {
   $refclientes = $_POST['refclientes'];
   $refubicaciones = $_POST['refubicaciones'];
   $datalloguer = $_POST['datalloguer'];
   $entrada = $_POST['entrada'];
   $sortida = $_POST['sortida'];
   $total = $_POST['total'];
   $numpertax = $_POST['numpertax'];
   $persset = $_POST['persset'];
   $taxa = $_POST['taxa'];
   $maxtaxa = $_POST['maxtaxa'];

   $res = $serviciosReferencias->insertarLloguers($refclientes,$refubicaciones,$datalloguer,$entrada,$sortida,$total,$numpertax,$persset,$taxa,$maxtaxa);

   if ((integer)$res > 0) {
      echo '';
   } else {
      echo 'Huvo un error al insertar datos';
   } 
}

function modificarLloguers($serviciosReferencias) {
   $id = $_POST['id'];
   $refclientes = $_POST['refclientes'];
   $refubicaciones = $_POST['refubicaciones'];
   $datalloguer = $_POST['datalloguer'];
   $entrada = $_POST['entrada'];
   $sortida = $_POST['sortida'];
   $total = $_POST['total'];
   $numpertax = $_POST['numpertax'];
   $persset = $_POST['persset'];
   $taxa = $_POST['taxa'];
   $maxtaxa = $_POST['maxtaxa'];

   $res = $serviciosReferencias->modificarLloguers($id,$refclientes,$refubicaciones,$datalloguer,$entrada,$sortida,$total,$numpertax,$persset,$taxa,$maxtaxa);

   if ($res == true) {
      echo '';
   } else {
      echo 'Huvo un error al modificar datos';
   }
}

function eliminarLloguers($serviciosReferencias) {
   $id = $_POST['id'];
   $res = $serviciosReferencias->eliminarLloguers($id);
   echo $res;
}

function armarTablaTarifas($serviciosReferencias) {
   $any = $_POST['any'];

   $resPeriodos = $serviciosReferencias->traerPeriodosPorOrdenPorAny($any);
   $resTipo =     $serviciosReferencias->traerTipoubicacion();


   $cad = "<table class='table table-striped' id='tblTarifas'>
            <thead>
               <th>Periode</th>
               <th>".$any."</th>";
   while ($rowY = mysql_fetch_array($resTipo)) {
      $cad .= "<th>".$rowY['tipoubicacion']."</th>";
   }
   $cad .= "</thead><tbody>";

   while ($rowX = mysql_fetch_array($resPeriodos)) {
      $cad .= "<tr>";
      $cad .= "<td>".$rowX['periodo']."</td>";
      $cad .= "<td>".$rowX['desdeperiode'].' - '.$rowX['finsaperiode']."</td>";
      $resTipoAux =    $serviciosReferencias->traerTipoubicacion();
      while ($rowY = mysql_fetch_array($resTipoAux)) {
         $resTarifa = $serviciosReferencias->traerTarifasPorPeriodoTipoUbicacion($rowX[0],$rowY[0]);

         //$cad .= "<td>asd</td>";
         if (mysql_num_rows($resTarifa)>0) {
            $cad .= "<td><input type='number' class='form-control txtTarifa' name='tarifa' id='".mysql_result($resTarifa,0,'idtarifa')."' value='".mysql_result($resTarifa,0,'tarifa')."'/></td>";
         } else {
            $cad .= '<td><button type="button" class="btn bg-light-green waves-effect btnNuevoTarifa" data-toggle="modal" data-target="#lgmNuevoTarifa" data-tipo="'.$rowY[0].'" data-periodo="'.$rowX[0].'">
               <i class="material-icons">add</i>
            </button></td>';
         }

      }
      $cad .= "</tr>";
   }

   $cad .= "</tbody></table>";

   echo $cad;
}

function insertarClientes($serviciosReferencias) {
   $cognom = $_POST['cognom'];
   $nom = $_POST['nom'];
   $nif = $_POST['nif'];
   $carrer = $_POST['carrer'];
   $codipostal = $_POST['codipostal'];
   $ciutat = $_POST['ciutat'];
   $pais = $_POST['pais'];
   $telefon = $_POST['telefon'];
   $email = $_POST['email'];
   $comentaris = $_POST['comentaris'];
   $telefon2 = $_POST['telefon2'];
   $email2 = $_POST['email2'];

   $res = $serviciosReferencias->insertarClientes($cognom,$nom,$nif,$carrer,$codipostal,$ciutat,$pais,$telefon,$email,$comentaris,$telefon2,$email2);

   if ((integer)$res > 0) {
      echo '';
   } else {
      echo 'Huvo un error al insertar datos';
   }
}

function modificarClientes($serviciosReferencias) {
   $id = $_POST['id'];
   $cognom = $_POST['cognom'];
   $nom = $_POST['nom'];
   $nif = $_POST['nif'];
   $carrer = $_POST['carrer'];
   $codipostal = $_POST['codipostal'];
   $ciutat = $_POST['ciutat'];
   $pais = $_POST['pais'];
   $telefon = $_POST['telefon'];
   $email = $_POST['email'];
   $comentaris = $_POST['comentaris'];
   $telefon2 = $_POST['telefon2'];
   $email2 = $_POST['email2'];

   $res = $serviciosReferencias->modificarClientes($id,$cognom,$nom,$nif,$carrer,$codipostal,$ciutat,$pais,$telefon,$email,$comentaris,$telefon2,$email2);

   if ($res == true) {
      echo '';
   } else {
      echo 'Huvo un error al modificar datos';
   }
}

function eliminarClientes($serviciosReferencias) {
   $id = $_POST['id'];
   $res = $serviciosReferencias->eliminarClientes($id);
   if ($res == true) {
   echo '';
   } else {
   echo 'Huvo un error al modificar datos';
   }
}



function frmAjaxModificar($serviciosFunciones, $serviciosReferencias, $serviciosUsuarios) {
   $tabla = $_POST['tabla'];
   $id = $_POST['id'];

   session_start();

   switch ($tabla) {
      case 'tbtipoubicacion':
         $modificar = "modificarTipoubicacion";
         $idTabla = "idtipoubicacion";

         $lblCambio	 	= array();
         $lblreemplazo	= array();

         $cadRef 	= '';

         $refdescripcion = array();
         $refCampo 	=  array();
      break;

      case 'dbclientes':

         $modificar = "modificarClientes";
         $idTabla = "idcliente";

         $lblCambio	 	= array('codipostal','telefon2','email2');
         $lblreemplazo	= array('Cod Postal','Tel. 2','Email 2');


         $cadRef 	= '';

         $refdescripcion = array();
         $refCampo 	=  array();


      break;
      case 'dbusuarios':
         $resultado = $serviciosReferencias->traerUsuariosPorId($id);

         $modificar = "modificarUsuario";
         $idTabla = "idusuario";

         $lblCambio	 	= array('nombrecompleto','refclientes','refroles');
         $lblreemplazo	= array('Nombre Completo','Cliente','Perfil');

         $refClientes = $serviciosReferencias->traerClientesPorId(mysql_result($resultado,0,'refclientes'));
         $cadRef2 = $serviciosFunciones->devolverSelectBox($refClientes,array(2,3),' ');



         $resRoles 	= $serviciosUsuarios->traerRoles();


         $cadRef = $serviciosFunciones->devolverSelectBoxActivo($resRoles,array(1),'',mysql_result($resultado,0,'refroles'));

         $refdescripcion = array(0 => $cadRef, 1=>$cadRef2);
         $refCampo 	=  array("refroles","refclientes");
         break;
      case 'dbubicaciones':
         $resultado = $serviciosReferencias->traerUbicacionesPorId($id);

         $modificar = "modificarUbicaciones";
         $idTabla = "idubicacion";

         $lblCambio	 	= array('reftipoubicacion','codapartament');
         $lblreemplazo	= array('Tipo Ubicaciones','Cod. Apart.');

         $resVar1 = $serviciosReferencias->traerTipoubicacion();
         $cadRef1 	= $serviciosFunciones->devolverSelectBoxActivo($resVar1,array(1),'',mysql_result($resultado,0,'reftipoubicacion'));

         $refdescripcion = array(0 => $cadRef1);
         $refCampo 	=  array('reftipoubicacion');
      break;
      case 'dbtarifas':
         $resultado = $serviciosReferencias->traerTarifasPorId($id);

         $modificar = "modificarTarifas";
         $idTabla = "idtarifa";

         $lblCambio	 	= array('reftipoubicacion','refperiodos');
         $lblreemplazo	= array('Tipo Ubicaciones','Periodes');

         $resVar1 = $serviciosReferencias->traerTipoubicacion();
         $cadRef1 	= $serviciosFunciones->devolverSelectBoxActivo($resVar1,array(1),'',mysql_result($resultado,0,'reftipoubicacion'));

         $resVar2 = $serviciosReferencias->traerPeriodos();
         $cadRef2 	= $serviciosFunciones->devolverSelectBoxActivo($resVar2,array(1,3,4),' - ',mysql_result($resultado,0,'refperiodos'));

         $refdescripcion = array(0 => $cadRef1,1=>$cadRef2);
         $refCampo 	=  array('reftipoubicacion','refperiodos');
      break;
      case 'dbperiodos':
         $resultado = $serviciosReferencias->traerPeriodosPorId($id);

         $modificar = "modificarPeriodos";
         $idTabla = "idperiodo";

         $lblCambio	 	= array('desdeperiode','finsaperiode');
         $lblreemplazo	= array('Perio. Desde','Perio. Finsa');

         $refdescripcion = array();
         $refCampo 	=  array();
      break;


      default:
         // code...
         break;
   }

   $formulario = $serviciosFunciones->camposTablaModificar($id, $idTabla,$modificar,$tabla,$lblCambio,$lblreemplazo,$refdescripcion,$refCampo);

   echo $formulario;
}


function frmAjaxNuevo($serviciosFunciones, $serviciosReferencias) {
   $tabla = $_POST['tabla'];
   $id = $_POST['id'];

   switch ($tabla) {
      case 'dbplantas':

         $insertar = "insertarPlantas";
         $idTabla = "idplanta";

         $lblCambio	 	= array("reflientes");
         $lblreemplazo	= array("Cliente");

         $resVar1 = $serviciosReferencias->traerClientesPorId($id);
         $cadRef1 	= $serviciosFunciones->devolverSelectBoxActivo($resVar1,array(1),'', $id);

         $refdescripcion = array(0=>$cadRef1);
         $refCampo 	=  array('refclientes');
         break;

      default:
         // code...
         break;
   }

   $formulario = $serviciosFunciones->camposTablaViejo($insertar ,$tabla,$lblCambio,$lblreemplazo,$refdescripcion,$refCampo);

   echo $formulario;
}



function insertarPeriodos($serviciosReferencias) {
$periodo = $_POST['periodo'];
$desdeperiode = $_POST['desdeperiode'];
$finsaperiode = $_POST['finsaperiode'];
$any = $_POST['any'];
$res = $serviciosReferencias->insertarPeriodos($periodo,$any,$desdeperiode,$finsaperiode);
if ((integer)$res > 0) {
echo '';
} else {
echo 'Huvo un error al insertar datos';
}
}
function modificarPeriodos($serviciosReferencias) {
$id = $_POST['id'];
$periodo = $_POST['periodo'];
$desdeperiode = $_POST['desdeperiode'];
$finsaperiode = $_POST['finsaperiode'];
$any = $_POST['any'];
$res = $serviciosReferencias->modificarPeriodos($id,$periodo,$any,$desdeperiode,$finsaperiode);
if ($res == true) {
echo '';
} else {
echo 'Huvo un error al modificar datos';
}
}
function eliminarPeriodos($serviciosReferencias) {
$id = $_POST['id'];
$res = $serviciosReferencias->eliminarPeriodos($id);
if ($res == true) {
echo '';
} else {
echo 'Huvo un error al modificar datos';
}
}

   function insertarTarifas($serviciosReferencias) {
      $tarifa = $_POST['tarifa'];
      $reftipoubicacion = $_POST['reftipoubicacion'];
      $refperiodos = $_POST['refperiodos'];


      $res = $serviciosReferencias->insertarTarifas($tarifa,$reftipoubicacion,$refperiodos);

      if ((integer)$res > 0) {
         echo '';
      } else {
         echo 'Huvo un error al insertar datos';
      }
   }

   function modificarTarifas($serviciosReferencias) {
      $id = $_POST['id'];
      $tarifa = $_POST['tarifa'];
      $reftipoubicacion = $_POST['reftipoubicacion'];
      $refperiodos = $_POST['refperiodos'];

      $res = $serviciosReferencias->modificarTarifas($id,$tarifa,$reftipoubicacion,$refperiodos);

      if ($res == true) {
         echo '';
      } else {
         echo 'Huvo un error al modificar datos';
      }
   }

   function modificarTarifaSola($serviciosReferencias) {
      $id = $_POST['idtarifamod'];
      $tarifa = $_POST['tarifamod'];

      $res = $serviciosReferencias->modificarTarifaSola($id,$tarifa);

      if ($res == true) {
         echo '';
      } else {
         echo 'Huvo un error al modificar datos';
      }
   }



   function eliminarTarifas($serviciosReferencias) {
      $id = $_POST['id'];

      $res = $serviciosReferencias->eliminarTarifas($id);

      if ($res == true) {
         echo '';
      } else {
         echo 'Huvo un error al modificar datos';
      }
   }

   function insertarUbicaciones($serviciosReferencias) {
      $dormitorio = $_POST['dormitorio'];
      $color = $_POST['color'];
      $reftipoubicacion = $_POST['reftipoubicacion'];
      $codapartament = $_POST['codapartament'];
      $hutg = $_POST['hutg'];

      $res = $serviciosReferencias->insertarUbicaciones($dormitorio,$color,$reftipoubicacion,$codapartament,$hutg);

      if ((integer)$res > 0) {
         echo '';
      } else {
         echo 'Huvo un error al insertar datos';
      }
   }

   function modificarUbicaciones($serviciosReferencias) {
      $id = $_POST['id'];
      $dormitorio = $_POST['dormitorio'];
      $color = $_POST['color'];
      $reftipoubicacion = $_POST['reftipoubicacion'];
      $codapartament = $_POST['codapartament'];
      $hutg = $_POST['hutg'];

      $res = $serviciosReferencias->modificarUbicaciones($id,$dormitorio,$color,$reftipoubicacion,$codapartament,$hutg);

      if ($res == true) {
         echo '';
      } else {
         echo 'Huvo un error al modificar datos';
      }
   }

   function eliminarUbicaciones($serviciosReferencias) {
      $id = $_POST['id'];

      $res = $serviciosReferencias->eliminarUbicaciones($id);

      if ($res == true) {
         echo '';
      } else {
         echo 'Huvo un error al modificar datos';
      }
   }


function insertarFormaspagos($serviciosReferencias) {
$formapago = $_POST['formapago'];
$abreviatura = $_POST['abreviatura'];
$res = $serviciosReferencias->insertarFormaspagos($formapago,$abreviatura);
if ((integer)$res > 0) {
echo '';
} else {
echo 'Huvo un error al insertar datos';
}
}
function modificarFormaspagos($serviciosReferencias) {
$id = $_POST['id'];
$formapago = $_POST['formapago'];
$abreviatura = $_POST['abreviatura'];
$res = $serviciosReferencias->modificarFormaspagos($id,$formapago,$abreviatura);
if ($res == true) {
echo '';
} else {
echo 'Huvo un error al modificar datos';
}
}
function eliminarFormaspagos($serviciosReferencias) {
$id = $_POST['id'];
$res = $serviciosReferencias->eliminarFormaspagos($id);
echo $res;
}

   function insertarTipoubicacion($serviciosReferencias) {
      $tipoubicacion = $_POST['tipoubicacion'];

      $res = $serviciosReferencias->insertarTipoubicacion($tipoubicacion);

      if ((integer)$res > 0) {
         echo '';
      } else {
         echo 'Huvo un error al insertar datos';
      }
   }

   function modificarTipoubicacion($serviciosReferencias) {
      $id = $_POST['id'];
      $tipoubicacion = $_POST['tipoubicacion'];

      $res = $serviciosReferencias->modificarTipoubicacion($id,$tipoubicacion);

      if ($res == true) {
         echo '';
      } else {
         echo 'Huvo un error al modificar datos';
      }
   }

   function eliminarTipoubicacion($serviciosReferencias) {
      $id = $_POST['id'];

      $res = $serviciosReferencias->eliminarTipoubicacion($id);

      if ($res == true) {
         echo '';
      } else {
         echo 'Huvo un error al modificar datos';
      }
   }
////////////////////////// FIN DE TRAER DATOS ////////////////////////////////////////////////////////////

//////////////////////////  BASICO  /////////////////////////////////////////////////////////////////////////

function toArray($query)
{
    $res = array();
    while ($row = @mysql_fetch_array($query)) {
        $res[] = $row;
    }
    return $res;
}


function entrar($serviciosUsuarios) {
	$email		=	$_POST['email'];
	$pass		=	$_POST['pass'];
	echo $serviciosUsuarios->loginUsuario($email,$pass);
}


function registrar($serviciosUsuarios) {
	$usuario			=	$_POST['usuario'];
	$password			=	$_POST['password'];
	$refroll			=	$_POST['refroles'];
   $refclientes			=	$_POST['refclientes'];
	$email				=	$_POST['email'];
	$nombre				=	$_POST['nombrecompleto'];

	$res = $serviciosUsuarios->insertarUsuario($usuario,$password,$refroll,$email,$nombre);
	if ((integer)$res > 0) {
		echo '';
	} else {
		echo $res;
	}
}


function insertarUsuario($serviciosUsuarios) {
	$usuario			=	$_POST['usuario'];
	$password			=	$_POST['password'];
	$refroll			=	$_POST['refroles'];
	$email				=	$_POST['email'];
	$nombre				=	$_POST['nombrecompleto'];

	$res = $serviciosUsuarios->insertarUsuario($usuario,$password,$refroll,$email,$nombre);
	if ((integer)$res > 0) {
		echo '';
	} else {
		echo $res;
	}
}


function insertarUsuarios($serviciosReferencias) {
	$usuario			=	$_POST['usuario'];
	$password			=	$_POST['password'];
	$refroll			=	$_POST['refroles'];
	$email				=	$_POST['email'];
	$nombre				=	$_POST['nombrecompleto'];
   $refclientes			=	$_POST['refclientes'];

	$res = $serviciosReferencias->insertarUsuarios($usuario,$password,$refroll,$email,$nombre,1,$refclientes);
	if ((integer)$res > 0) {
		echo '';
	} else {
		echo $res;
	}
}


function modificarUsuario($serviciosUsuarios) {
	$id					=	$_POST['id'];
	$usuario			=	$_POST['usuario'];
	$password			=	$_POST['password'];
	$refroll			=	$_POST['refroles'];
	$email				=	$_POST['email'];
	$nombre				=	$_POST['nombrecompleto'];

   if (isset($_POST['activo'])) {
      $activo = 1;
   } else {
      $activo = 0;
   }



	echo $serviciosUsuarios->modificarUsuario($id,$usuario,$password,$refroll,$email,$nombre,$activo);
}


function enviarMail($serviciosUsuarios) {
	$email		=	$_POST['email'];
	$pass		=	$_POST['pass'];
	//$idempresa  =	$_POST['idempresa'];

	echo $serviciosUsuarios->login($email,$pass);
}

function registrarme($serviciosUsuarios, $serviciosReferencias, $serviciosValidador) {
   $error = '';

   $email      = trim($_POST['email']);
   $pass       = trim($_POST['pass']);
   $apellido   = trim($_POST['apellido']);
   $nombre     = trim($_POST['nombre']);
   $telefono   = trim($_POST['telefono']);
   $celular    = trim($_POST['celular']);
   $cuit       = trim($_POST['cuit']);
   $reftipodocumentos = trim($_POST['reftipodocumentos']);

   $aceptaterminos   = $_POST['aceptaterminos'];
   $subscripcion     = $_POST['subscripcion'];

   $existeEmail = $serviciosUsuarios->existeUsuario($email);
   $existeCliente = $serviciosReferencias->existeCliente($cuit);

   if ($existeEmail == 1) {
      $error .= 'El Email ingresado ya existe!
      ';
   }

   if ($existeCliente == 1) {
      $error .= 'El DNI ingresado ya existe!
      ';
   }

   if ($aceptaterminos == 0) {
      $error .= 'Debe Aceptar los Terminos y Condiciones
      ';
   }

   if ($error == '') {
      // todo ok
      $res = $serviciosReferencias->insertarClientes($reftipodocumentos,$apellido,$nombre,$cuit,$telefono,$celular,$email,$aceptaterminos,$subscripcion,0);

      // empiezo la activacion del usuarios
      $resActivacion = $serviciosUsuarios->registrarSocio($email, $pass, $apellido, $nombre, $res);

      if ((integer)$resActivacion > 0) {

         echo '';
      } else {
         echo 'Hubo un error al insertar datos ';
      }
   } else {
      // error
      echo $error;
   }
}


function devolverImagen($nroInput) {

	if( $_FILES['archivo'.$nroInput]['name'] != null && $_FILES['archivo'.$nroInput]['size'] > 0 ){
	// Nivel de errores
	  error_reporting(E_ALL);
	  $altura = 100;
	  // Constantes
	  # Altura de el thumbnail en píxeles
	  //define("ALTURA", 100);
	  # Nombre del archivo temporal del thumbnail
	  //define("NAMETHUMB", "/tmp/thumbtemp"); //Esto en servidores Linux, en Windows podría ser:
	  //define("NAMETHUMB", "c:/windows/temp/thumbtemp"); //y te olvidas de los problemas de permisos
	  $NAMETHUMB = "c:/windows/temp/thumbtemp";
	  # Servidor de base de datos
	  //define("DBHOST", "localhost");
	  # nombre de la base de datos
	  //define("DBNAME", "portalinmobiliario");
	  # Usuario de base de datos
	  //define("DBUSER", "root");
	  # Password de base de datos
	  //define("DBPASSWORD", "");
	  // Mime types permitidos
	  $mimetypes = array("image/jpeg", "image/pjpeg", "image/gif", "image/png");
	  // Variables de la foto
	  $name = $_FILES["archivo".$nroInput]["name"];
	  $type = $_FILES["archivo".$nroInput]["type"];
	  $tmp_name = $_FILES["archivo".$nroInput]["tmp_name"];
	  $size = $_FILES["archivo".$nroInput]["size"];
	  // Verificamos si el archivo es una imagen válida
	  if(!in_array($type, $mimetypes))
		die("El archivo que subiste no es una imagen válida");
	  // Creando el thumbnail
	  switch($type) {
		case $mimetypes[0]:
		case $mimetypes[1]:
		  $img = imagecreatefromjpeg($tmp_name);
		  break;
		case $mimetypes[2]:
		  $img = imagecreatefromgif($tmp_name);
		  break;
		case $mimetypes[3]:
		  $img = imagecreatefrompng($tmp_name);
		  break;
	  }

	  $datos = getimagesize($tmp_name);

	  $ratio = ($datos[1]/$altura);
	  $ancho = round($datos[0]/$ratio);
	  $thumb = imagecreatetruecolor($ancho, $altura);
	  imagecopyresized($thumb, $img, 0, 0, 0, 0, $ancho, $altura, $datos[0], $datos[1]);
	  switch($type) {
		case $mimetypes[0]:
		case $mimetypes[1]:
		  imagejpeg($thumb, $NAMETHUMB);
			  break;
		case $mimetypes[2]:
		  imagegif($thumb, $NAMETHUMB);
		  break;
		case $mimetypes[3]:
		  imagepng($thumb, $NAMETHUMB);
		  break;
	  }
	  // Extrae los contenidos de las fotos
	  # contenido de la foto original
	  $fp = fopen($tmp_name, "rb");
	  $tfoto = fread($fp, filesize($tmp_name));
	  $tfoto = addslashes($tfoto);
	  fclose($fp);
	  # contenido del thumbnail
	  $fp = fopen($NAMETHUMB, "rb");
	  $tthumb = fread($fp, filesize($NAMETHUMB));
	  $tthumb = addslashes($tthumb);
	  fclose($fp);
	  // Borra archivos temporales si es que existen
	  //@unlink($tmp_name);
	  //@unlink(NAMETHUMB);
	} else {
		$tfoto = '';
		$type = '';
	}
	$tfoto = utf8_decode($tfoto);
	return array('tfoto' => $tfoto, 'type' => $type);
}


?>
