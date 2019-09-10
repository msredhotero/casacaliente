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
case 'frmAjaxVer':
frmAjaxVer($serviciosFunciones, $serviciosReferencias, $serviciosUsuarios);
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
case 'devolverTarifa':
   devolverTarifa($serviciosReferencias);
break;
case 'devolverTarifaArray';
   devolverTarifaArray($serviciosReferencias);
break;
case 'insertarPagare':
   insertarPagare($serviciosReferencias);
break;
case 'modificarPagoCliente':
   modificarPagoCliente($serviciosReferencias);
break;
case 'frmAjaxNuevo':
   frmAjaxNuevo($serviciosReferencias, $serviciosFunciones);
break;
case 'insertarLloguersadicional':
   insertarLloguersadicional($serviciosReferencias);
break;
case 'modificarLloguersadicional':
   modificarLloguersadicional($serviciosReferencias);
break;
case 'eliminarLloguersadicional':
   eliminarLloguersadicional($serviciosReferencias);
break;

case 'traerDisponibilidad':
   traerDisponibilidad($serviciosReferencias);
break;

case 'insertarLocatarios':
   insertarLocatarios($serviciosReferencias);
break;
case 'modificarLocatarios':
   modificarLocatarios($serviciosReferencias);
break;
case 'eliminarLocatarios':
   eliminarLocatarios($serviciosReferencias);
break;
case 'verLloguer':
   verLloguer($serviciosReferencias);
break;

case 'insertarLloguercomentarios':
   insertarLloguercomentarios($serviciosReferencias);
break;
case 'modificarLloguercomentarios':
   modificarLloguercomentarios($serviciosReferencias);
break;
case 'eliminarLloguercomentarios':
   eliminarLloguercomentarios($serviciosReferencias);
break;
/* Fin */

}
/* Fin */

function insertarLloguercomentarios($serviciosReferencias) {
   $reflloguers = $_POST['reflloguers'];
   $comentario = $_POST['comentario'];

   $resComentario = $serviciosReferencias->traerLloguercomentariosPorLloguer($reflloguers);

   if (mysql_num_rows($resComentario) > 0) {

      $res = $serviciosReferencias->modificarLloguercomentarios(mysql_result($resComentario,0,0),$reflloguers,$comentario);

      if ($res == true) {
         echo '';
      } else {
         echo 'Hubo un error al modificar datos';
      }
   } else {
      $res = $serviciosReferencias->insertarLloguercomentarios($reflloguers,$comentario);

      if ((integer)$res > 0) {
         echo '';
      } else {
         echo 'Hubo un error al insertar datos';
      }
   }

}

function modificarLloguercomentarios($serviciosReferencias) {
   $id = $_POST['id'];
   $reflloguers = $_POST['reflloguers'];
   $comentario = $_POST['comentario'];

   $res = $serviciosReferencias->modificarLloguercomentarios($id,$reflloguers,$comentario);

   if ($res == true) {
      echo '';
   } else {
      echo 'Hubo un error al modificar datos';
   }
}

function verLloguer($serviciosReferencias) {
   $id = $_POST['id'];
   $res = $serviciosReferencias->traerLloguersPorIdCompleto($id);

   $resLloguerAdicional =  $serviciosReferencias->traerLloguersadicionalPorLloguer($id);

   $resPagos = $serviciosReferencias->traerPagosPorLloguers($id);

   $resComentario = $serviciosReferencias->traerLloguercomentariosPorLloguer($id);

   $pago = 0;

   while ($row = mysql_fetch_array($resPagos))
	{
      $pago += $row['monto'];
   }

   $taxaturisticaAdicional = 0;
   $taxaturisticaAdicionalPersonas = 0;
   $totalTaxaPersona = 0;

   while ($rowAd = mysql_fetch_array($resLloguerAdicional)) {
   	$taxaturisticaAdicionalPersonas += $rowAd['personas'];
   	$taxaturisticaAdicional += $rowAd['taxaturistica'];
   	$totalTaxaPersona += $rowAd['taxapersona'];
   }

   $cad = '<table class="table table-striped table-bordered">';
   $cad .= '<thead>';
   $cad .= '<th>Client</th>
            <th>Persones</th>
            <th>Total</th>
            <th>Falta Pagar</th>';
   $cad .= '</thead>';
   $cad .= '<tbody>';
   $cad .= '<tr>';
   while ($row = mysql_fetch_array($res)) {
      $cad .= '<td>'.utf8_encode($row['nom']).' '.utf8_encode($row['cognom']).'</td>';
      $cad .= '<td>'.$row['personasreales'].'</td>';
      $cad .= '<td>'.number_format( $row['total'] + $totalTaxaPersona + $taxaturisticaAdicional,2,',','.').' €</td>';
      if (($row['total'] + $totalTaxaPersona + $taxaturisticaAdicional - $pago) < 0) {
         $cad .= '<td>'.number_format( 0,2,',','.').' €</td>';
      } else {
         $cad .= '<td>'.number_format( $row['total'] + $totalTaxaPersona + $taxaturisticaAdicional - $pago,2,',','.').' €</td>';
      }

   }
   $cad .= '</tr>';
   $cad .= '</tbody></table';

   $resV['lloguer'] = $cad;

   if (mysql_num_rows($resComentario) > 0) {
      $resV['comentario'] = mysql_result($resComentario,0,'comentario');
   } else {
      $resV['comentario'] = '';
   }


   header('Content-type: application/json');
   echo json_encode($resV);

}


function insertarLocatarios($serviciosReferencias) {
   $cognom = $_POST['cognom'];
   $nom = $_POST['nom'];
   $nif = $_POST['nif'];
   $carrer = $_POST['carrer'];
   $codipostal = $_POST['codipostal'];
   $ciutat = $_POST['ciutat'];
   $pais = $_POST['pais'];
   $telefon = $_POST['telefon'];
   $email = $_POST['email'];

   $res = $serviciosReferencias->insertarLocatarios($cognom,$nom,$nif,$carrer,$codipostal,$ciutat,$pais,$telefon,$email);

   if ((integer)$res > 0) {
      echo '';
   } else {
      echo 'Hubo un error al insertar datos';
   }
}

function modificarLocatarios($serviciosReferencias) {
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

   $res = $serviciosReferencias->modificarLocatarios($id,$cognom,$nom,$nif,$carrer,$codipostal,$ciutat,$pais,$telefon,$email);

   if ($res == true) {
      echo '';
   } else {
      echo 'Hubo un error al modificar datos';
   }
}


function eliminarLocatarios($serviciosReferencias) {
   $id = $_POST['id'];

   $sqlUsuarios = "select * from dbusuarios where reflocatarios = ".$id;
   $resUsuarios = $serviciosReferencias->query($sql,0);

   if (mysql_num_rows($resUsuarios)>0) {
      echo 'No se puede eliminar el Locatario ya que tiene datos cargados';
   } else {
      $res = $serviciosReferencias->eliminarLocatarios($id);

      if ($res == true) {
         echo '';
      } else {
         echo 'Hubo un error al modificar datos';
      }
   }

}

function traerDisponibilidad($serviciosReferencias) {
   $any  =  $_POST['any'];
   $resPeriodos      =  $serviciosReferencias->traerPeriodosDisponibilidad($any);
   $resUbicaciones   =  $serviciosReferencias->traerUbicaciones();

   $ejeX = mysql_num_rows($resUbicaciones);

   $cad = '';
   $contAux = 1;
   $fechaAuxYDesde = '';
   $fechaAuxYHasta = '';

   $temporada = '';
   $linea = '';
   $primero = 0;


   $cad = "<table class='display table table-bordered table-responsive' id='tblPlaning'>
            <thead>
               <th>".$any."</th>";
   while ($rowY = mysql_fetch_array($resUbicaciones)) {
      $cad .= "<th>".$rowY['codapartament']."</th>";
   }
   $cad .= "</thead><tbody>";

   while ($rowY = mysql_fetch_array($resPeriodos)) {
      if ($temporada != $rowY['periodo']) {
         $temporada = $rowY['periodo'];
         if ($primero == 1) {
            $linea = 'border-bottom: 2px solid #F00;';
         }
      } else {
         $linea = '';
      }
      $primero = 1;

      $fechaAuxYDesde = $rowY['desdeperiode'];
      //

      for ($i=0; $i < $rowY['semanas']; $i++) {


         $fechaAuxYHastaAux = new DateTime($fechaAuxYDesde);
         $fechaAuxYDesdeAux = new DateTime($fechaAuxYDesde);
         $fechaAuxYHastaAux->add(new DateInterval('P7D'));

         //echo $fechaAuxYHasta->format('Y-m-d') . "\n";
         //die(var_dump($fechaAuxYDesde));
         $cad .= "<tr>";
         $cad .= "<td>".$fechaAuxYDesdeAux->format('d/m').'-'.$fechaAuxYHastaAux->format('d/m')."</td>";

         for ($k=0; $k < $ejeX; $k++) {
            $cad .= '<td class="achique" style="'.$linea.'">';
            $cad .= "<table class='tablaInterna'>";

            for ($d=1; $d <= 7; $d++) {

               $cad .= "<tr>";

               $fechaAuxYHasta = new DateTime($fechaAuxYDesde);
               //$fechaAuxYDesde = new DateTime($fechaAuxYDesde);
               $fechaAuxYHasta->add(new DateInterval('P'.$d.'D'));

               $resAlquiler = $serviciosReferencias->buscarAlquilerPorFechaUbicacionPorDia($fechaAuxYHasta->format('Y-m-d'), mysql_result($resUbicaciones,$k,0));
               if (mysql_num_rows($resAlquiler)>0) {
                  if (mysql_result($resAlquiler,0,'entrada') == $fechaAuxYHasta->format('Y-m-d')) {
                     $cad .= '<td bgcolor="#00FF00" class="disponibilidadLloguer" id="'.mysql_result($resAlquiler,0,'idlloguer').'"><b>'.$fechaAuxYHasta->format('Y-m-d').'</b></td>';
                  } else {
                     if (mysql_result($resAlquiler,0,'sortida') == $fechaAuxYHasta->format('Y-m-d')) {
                        $cad .= '<td bgcolor="#00FF00" id="'.mysql_result($resAlquiler,0,'idlloguer').'" class="disponibilidadLloguer"><b>'.$fechaAuxYHasta->format('Y-m-d').'</b></td>';
                     } else {
                        $cad .= '<td bgcolor="#00FF00" id="'.mysql_result($resAlquiler,0,'idlloguer').'" class="disponibilidadLloguer"></td>';
                     }
                  }

               } else {
                  $cad .= '<td></td>';
               }

               $cad .= "</tr>";
            }

            $cad .= "</table>";
            $cad .= "</td>";


         }
         $linea = '';
         $cad .= "</tr>";
         $fechaAuxYDesdeAux->add(new DateInterval('P7D'));
         $fechaAuxYDesde = $fechaAuxYDesdeAux->format('Y-m-d');
      }


   }

   $cad .= "</tbody></table>";

   echo $cad;

}

function insertarLloguersadicional($serviciosReferencias) {
   $reflloguers = $_POST['reflloguers'];
   $personas = $_POST['personas'];
   $menores = $_POST['menores'];
   $entrada = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['entrada'])));
   $sortida = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['sortida'])));

   $resTaxa = $serviciosReferencias->traerTaxa();

   $taxaPer = mysql_result($resTaxa,0,1);
   $taxaTur = mysql_result($resTaxa,0,2);
   $taxaMax = mysql_result($resTaxa,0,3);

   $dias = $serviciosReferencias->s_datediff('d', $entrada, $sortida, false);

   $totalTaxaPersona = 0;
   $totalTaxaTuristica = 1 * $dias * $taxaTur;

   if ($totalTaxaTuristica > $taxaMax) {
      $totalTaxaTuristica  = $personas * $taxaMax;
   } else {
      $totalTaxaTuristica = $personas * $dias * $taxaTur;
   }

   $totalTarifa = 0;

   // si es menos de una semana
   if ($dias < 7) {
      $totalTaxaPersona = ($personas + $menores) * 1 * $taxaPer;
   } else {
      $totalTaxaPersona = ($personas + $menores) * $dias / 7 * $taxaPer;
   }

   $taxapersona = $totalTaxaPersona;
   $taxaturistica = $totalTaxaTuristica;

   $res = $serviciosReferencias->insertarLloguersadicional($reflloguers,$personas,$entrada,$sortida,$taxapersona,$taxaturistica,$menores);

   if ((integer)$res > 0) {
      echo '';
   } else {
      echo 'Hubo un error al insertar datos';
   }
}

function modificarLloguersadicional($serviciosReferencias) {
   $id = $_POST['id'];
   $reflloguers = $_POST['reflloguers'];
   $personas = $_POST['personas'];
   $menores = $_POST['menores'];
   $entrada = $_POST['entrada'];
   $sortida = $_POST['sortida'];

   $resTaxa = $serviciosReferencias->traerTaxa();

   $taxaPer = mysql_result($resTaxa,0,1);
   $taxaTur = mysql_result($resTaxa,0,2);
   $taxaMax = mysql_result($resTaxa,0,3);

   $dias = $this->s_datediff('d', $entrada, $sortida, false);

   $totalTaxaPersona = 0;
   $totalTaxaTuristica = 1 * $dias * $taxaTur;

   if ($totalTaxaTuristica > $taxaMax) {
      $totalTaxaTuristica  = $personas * $taxaMax;
   } else {
      $totalTaxaTuristica = $personas * $dias * $taxaTur;
   }

   $totalTarifa = 0;

   // si es menos de una semana
   if ($dias < 7) {
      $totalTaxaPersona = ($personas + $menores) * 1 * $taxaPer;
   } else {
      $totalTaxaPersona = ($personas + $menores) * $dias / 7 * $taxaPer;
   }

   $taxapersona = $totalTaxaPersona;
   $taxaturistica = $totalTaxaTuristica;

   $res = $serviciosReferencias->modificarLloguersadicional($id,$reflloguers,$personas,$entrada,$sortida,$taxapersona,$taxaturistica,$menores);

   if ($res == true) {
      echo '';
   } else {
      echo 'Hubo un error al modificar datos';
   }
}


function eliminarLloguersadicional($serviciosReferencias) {
   $id = $_POST['id'];
   $res = $serviciosReferencias->eliminarLloguersadicional($id);
   if ($res == true) {
      echo '';
   } else {
      echo 'Hubo un error en la operacion';
   }

}

function modificarPagoCliente($serviciosReferencias) {
   session_start();

   $reflloguers =  $_POST['idlloguerpagarecliente'];

   $resPagos   = $serviciosReferencias->traerPagosPorLloguers($reflloguers);

   $refformaspagos = 0;
   $monto1 = $_POST['valorpagocliente1'];
   $monto2 = $_POST['valorpagocliente2'];
   $taxa = $_POST['pagotaxacliente'];
   $fecha1 = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['fechapagocliente1'])));
   $fecha2 = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['fechapagocliente2'])));

   $formapago1 = $_POST['formapago1'];
   $formapago2 = $_POST['formapago2'];

   $cargarpago1 = $_POST['cargarpago1'];
   $cargarpago2 = $_POST['cargarpago2'];

   $usuario = $_SESSION['usua_sahilices'];

   $unicoPagoTaxa = $_POST['pagotaxaunico'];

   $taxa1 = 0;
   $taxa2 = 0;

   if ($unicoPagoTaxa == 1) {
      $taxa1 = $taxa;
   } else {
      $taxa2 = $taxa;
   }

   $error = '';

   if (mysql_num_rows($resPagos)>0) {
      $res1 = $serviciosReferencias->modificarPagos(mysql_result($resPagos,0,'idpago'),$reflloguers,$formapago1,$monto1,$cargarpago1,$taxa1,date('Y-m-d H:i:s'),$fecha1,$usuario,0);

      if ($res1 == true) {
         $error = '';
      } else {
         $error .= 'Hubo un error al insertar datos ';
      }

      if (mysql_num_rows($resPagos)>1) {
         $res2 = $serviciosReferencias->modificarPagos(mysql_result($resPagos,1,'idpago'),$reflloguers,$formapago2,$monto2,$cargarpago2,$taxa2,date('Y-m-d H:i:s'),$fecha2,$usuario,0);

         if ($res2 == true) {
            $error .= '';
         } else {
            $error .= ' - Hubo un error al insertar datos';
         }
      } else {
         $res2 = $serviciosReferencias->insertarPagos($reflloguers,$formapago2,$monto2,$cargarpago2,$taxa2,date('Y-m-d H:i:s'),$fecha2,$usuario,0);
         if ((integer)$res2 > 0) {
            $error .= '';
         } else {
            $error .= ' - Hubo un error al insertar datos';
         }
      }
   } else {
      $res1 = $serviciosReferencias->insertarPagos($reflloguers,$formapago1,$monto1,$cargarpago1,$taxa1,date('Y-m-d H:i:s'),$fecha1,$usuario,0);

      if ((integer)$res1 > 0) {
         $error = '';
      } else {
         $error .= 'Hubo un error al insertar datos ';
      }

      $res2 = $serviciosReferencias->insertarPagos($reflloguers,$formapago2,$monto2,$cargarpago2,$taxa2,date('Y-m-d H:i:s'),$fecha2,$usuario,0);
      if ((integer)$res2 > 0) {
         $error .= '';
      } else {
         $error .= ' - Hubo un error al insertar datos';
      }
   }


   echo $error;
}

function insertarPagare($serviciosReferencias) {
   session_start();

   $reflloguers =  $_POST['idlloguerpagare'];

   $resPagos   = $serviciosReferencias->traerPagosPorLloguers($reflloguers);

   $refformaspagos = 0;
   $monto1 = $_POST['valorpago1'];
   $monto2 = $_POST['valorpago2'];
   $taxa = $_POST['pagotaxa'];
   $fecha1 = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['fechapago1'])));
   $fecha2 = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['fechapago2'])));
   $usuario = $_SESSION['usua_sahilices'];

   $error = '';

   if (mysql_num_rows($resPagos)>0) {
      $res1 = $serviciosReferencias->modificarPagosParcial(mysql_result($resPagos,0,'idpago'),$monto1,0,$fecha1,$usuario);

      if ($res1 == true) {
         $error = '';
      } else {
         $error .= 'Hubo un error al insertar datos ';
      }

      $res2 = $serviciosReferencias->modificarPagosParcial(mysql_result($resPagos,1,'idpago'),$monto2,$taxa,$fecha2,$usuario);

      if ($res2 == true) {
         $error .= '';
      } else {
         $error .= ' - Hubo un error al insertar datos';
      }
   } else {
      $res1 = $serviciosReferencias->insertarPagos($reflloguers,$refformaspagos,$monto1,0,0,'00/00/0000',$fecha1,$usuario,0);

      if ((integer)$res1 > 0) {
         $error = '';
      } else {
         $error .= 'Hubo un error al insertar datos ';
      }

      $res2 = $serviciosReferencias->insertarPagos($reflloguers,$refformaspagos,$monto2,0,$taxa,'00/00/0000',$fecha2,$usuario,0);
      if ((integer)$res2 > 0) {
         $error .= '';
      } else {
         $error .= ' - Hubo un error al insertar datos';
      }
   }


   echo $error;
}


function devolverTarifaArray($serviciosReferencias) {
   $id = $_POST['id'];

   $resLloguer = $serviciosReferencias->traerLloguersPorId($id);

   $resPagos   = $serviciosReferencias->traerPagosPorLloguers($id);

   $resLloguerAdicional =  $serviciosReferencias->traerLloguersadicionalPorLloguer($id);

   $taxaturisticaAdicional = 0;
   $totalTaxaPersona = 0;

   $idPago1 = 0;
   $idPago2 = 0;

   while ($rowAd = mysql_fetch_array($resLloguerAdicional)) {

   	$taxaturisticaAdicional += $rowAd['taxaturistica'];

   	$totalTaxaPersona += $rowAd['taxapersona'];

   }

   $refubicaciones      =  mysql_result($resLloguer,0,'refubicaciones');
   $desdeperiode        =  mysql_result($resLloguer,0,'entrada');
   $finsaperiode        =  mysql_result($resLloguer,0,'sortida');
   $personas            =  mysql_result($resLloguer,0,'numpertax');
   $total               =  mysql_result($resLloguer,0,'total');
   $sortida             =  mysql_result($resLloguer,0,'sortida');

   $segundopago = strtotime ( '-30 day' , strtotime ( $sortida ) ) ;

   if ($segundopago < strtotime(date("d-m-Y H:i:00",time()))) {
      $segundopago = strtotime ( '0 day' , strtotime ( $sortida ) ) ;
      $segundopago = date ( 'Y-m-d' , $segundopago );
   } else {
      $segundopago = date ( 'Y-m-d' , $segundopago );
   }


   $resFaltaPagar       =  $serviciosReferencias->faltaPagar($id);

   $falta               =  mysql_result($resFaltaPagar,0,'falta');

   $taxaUnica = 0;

   $taxa = $taxaturisticaAdicional + $totalTaxaPersona;

   if (mysql_num_rows($resPagos)>0) {
      $existePago = 1;

      $idPago1 = mysql_result($resPagos,0,'idpago');

      $pago1 = mysql_result($resPagos,0,'cuota');
      $primerpago = mysql_result($resPagos,0,'fechapago');
      $monto1 = mysql_result($resPagos,0,'monto');
      $formapago1 = mysql_result($resPagos,0,'refformaspagos');

      if (mysql_result($resPagos,0,'taxa') > 0) {
         $taxaUnica = 1;
      }

      if (mysql_num_rows($resPagos)>1) {
         $pago2 = mysql_result($resPagos,1,'cuota');

         $idPago2 = mysql_result($resPagos,1,'idpago');

         $segundopago = mysql_result($resPagos,1,'fechapago');
         $monto2 = mysql_result($resPagos,1,'monto');
         $formapago2 = mysql_result($resPagos,1,'refformaspagos');

         if (mysql_result($resPagos,1,'taxa') > 0) {
            $taxaUnica = 2;
         }
      } else {
         $fecha2pago = date($sortida);
         $segundopago = strtotime ( '-30 day' , strtotime ( $fecha2pago ) ) ;

         if ($segundopago < strtotime(date("d-m-Y H:i:00",time()))) {
            $segundopago = strtotime ( '0 day' , strtotime ( $fecha2pago ) ) ;
            $segundopago = date ( 'd/m/Y' , $segundopago );
         } else {
            $segundopago = date ( 'd/m/Y' , $segundopago );
         }


         $pago2 = 0;
         //$segundopago = 0;
         $monto2 = 0;
         $formapago2 = 0;
      }

   } else {
      $existePago = 0;
      $pago1 = 0;
      $pago2 = 0;

      $primerpago = 0;
      $monto1 = 0;
      $monto2 = 0;

      $monto1 = 0;
      $monto2 = 0;
      $formapago1 = 0;
      $formapago2 = 0;
   }

   $resV['pagos'] = array(
                     'existe' => $existePago,
                     'pago1' => $pago1,
                     'pago2' => $pago2,
                     'taxa' => $taxa,
                     'primerpago' => $primerpago,
                     'segundopago' => $segundopago,
                     'monto1' => $monto1,
                     'monto2' => $monto2,
                     'formapago1' => $formapago1,
                     'formapago2' => $formapago2,
                     'taxaunica' => $taxaUnica,
                     'idpago1' => $idPago1,
                     'idpago2' => $idPago2
                  );

   $resV['datos'] = $serviciosReferencias->calcularTarifaArray($refubicaciones,$desdeperiode,$finsaperiode,$taxa=array($totalTaxaPersona,$taxaturisticaAdicional),$total,$falta,$segundopago);

   header('Content-type: application/json');
   echo json_encode($resV);
}

function devolverTarifa($serviciosReferencias) {
   $refubicaciones    =  $_POST['refubicaciones'];
   $desdeperiode        =  date('Y-m-d', strtotime(str_replace('/', '-', $_POST['entrada'])));
   $finsaperiode        =  date('Y-m-d', strtotime(str_replace('/', '-', $_POST['sortida'])));
   $personas            =  $_POST['personas'];



   $tarifa = $serviciosReferencias->calcularTarifa($refubicaciones,$desdeperiode,$finsaperiode,$personas);

   echo round($tarifa,2);
}

function insertarLloguers($serviciosReferencias) {
   $refclientes = $_POST['refclientes'];
   $refubicaciones = $_POST['refubicaciones'];
   $datalloguer = $_POST['datalloguer'];

   $entrada = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['entrada'])));
   $sortida = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['sortida'])));

   $total = $_POST['total'];
   $numpertax = $_POST['numpertax'];
   $persset = $_POST['persset'];
   $taxa = $_POST['taxa'];
   $maxtaxa = $_POST['maxtaxa'];
   $refestados = $_POST['refestados'];

   $indice = $_POST['indice'];

   if ($indice < 1) {
      echo 'Debe seleccionar la cantidad de personas';
   } else {
      $res = $serviciosReferencias->insertarLloguers($refclientes,$refubicaciones,$datalloguer,$entrada,$sortida,$total,$numpertax,$persset,$taxa,$maxtaxa,$refestados);

      if ((integer)$res > 0) {

         $resTaxa = $serviciosReferencias->traerTaxa();

         $taxaPer = mysql_result($resTaxa,0,1);
         $taxaTur = mysql_result($resTaxa,0,2);
         $taxaMax = mysql_result($resTaxa,0,3);

         for ($i=1;$i<$indice+1;$i++) {
            $entrada = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['entradapersonas'.$i])));
            $sortida = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['sortidapersonas'.$i])));
            $personas = $_POST['personas'.$i];
            $menores = $_POST['menores'.$i];

            $dias = $serviciosReferencias->s_datediff('d', $entrada, $sortida, false);

            $totalTaxaPersona = 0;
            $totalTaxaTuristica = 1 * $dias * $taxaTur;

            if ($totalTaxaTuristica > $taxaMax) {
               $totalTaxaTuristica  = $personas * $taxaMax;
            } else {
               $totalTaxaTuristica = $personas * $dias * $taxaTur;
            }

            $totalTarifa = 0;

            // si es menos de una semana
            if ($dias < 7) {
               $totalTaxaPersona = ($personas + $menores) * 1 * $taxaPer;
            } else {
               $totalTaxaPersona = ($personas + $menores) * $dias / 7 * $taxaPer;
            }

            $taxapersona = $totalTaxaPersona;
            $taxaturistica = $totalTaxaTuristica;

            $serviciosReferencias->insertarLloguersadicional($res,$personas,$entrada,$sortida,$taxapersona,$taxaturistica,$menores);
         }
         echo '';
      } else {
         echo 'Hubo un error al insertar datos';
      }
   }

}

function modificarLloguers($serviciosReferencias) {
   $id = $_POST['id'];
   $refclientes = $_POST['refclientes'];
   $refubicaciones = $_POST['refubicaciones'];
   $datalloguer = $_POST['datalloguer'];
   $entrada = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['entrada'])));
   $sortida = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['sortida'])));
   $total = $_POST['total'];
   $numpertax = $_POST['numpertax'];
   $persset = $_POST['persset'];
   $taxa = $_POST['taxa'];
   $maxtaxa = $_POST['maxtaxa'];
   $refestados = $_POST['refestados'];

   $res = $serviciosReferencias->modificarLloguers($id,$refclientes,$refubicaciones,$datalloguer,$entrada,$sortida,$total,$numpertax,$persset,$taxa,$maxtaxa,$refestados);

   if ($res == true) {
      echo '';
   } else {
      echo 'Hubo un error al modificar datos';
   }
}

function eliminarLloguers($serviciosReferencias) {
   $id = $_POST['id'];
   $resAd = $serviciosReferencias->eliminarLloguersadicionalPorLloguer($id);

   $res = $serviciosReferencias->eliminarLloguers($id);
   if ($res == true) {
      echo '';
   } else {
      echo 'Hubo un error al modificar datos';
   }
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
      echo 'Hubo un error al insertar datos';
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
      echo 'Hubo un error al modificar datos';
   }
}

function eliminarClientes($serviciosReferencias) {
   $id = $_POST['id'];
   $res = $serviciosReferencias->eliminarClientes($id);
   if ($res == true) {
   echo '';
   } else {
   echo 'Hubo un error al modificar datos';
   }
}


function frmAjaxVer($serviciosFunciones, $serviciosReferencias, $serviciosUsuarios) {
   $tabla = $_POST['tabla'];
   $id = $_POST['id'];

   session_start();

   switch ($tabla) {
      case 'dbclientes':

         $idTabla = "idcliente";

         $lblCambio	 	= array('codipostal','telefon2','email2');
         $lblreemplazo	= array('Cod Postal','Tel. 2','Email 2');


         $cadRef 	= '';

         $refdescripcion = array();
         $refCampo 	=  array();


      break;

      default:
         // code...
         break;
   }

   //$formulario = $serviciosFunciones->camposTablaViejo($id, $idTabla,$modificar,$tabla,$lblCambio,$lblreemplazo,$refdescripcion,$refCampo);
   $formulario = $serviciosFunciones->camposTablaVer($id,$idTabla,$tabla,$lblCambio,$lblreemplazo,$refdescripcion,$refCampo);

   echo $formulario;
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
      case 'dblloguers':
         $resultado = $serviciosReferencias->traerLloguersPorId($id);

         $modificar = "modificarLloguers";
         $idTabla = "idlloguer";

         $lblCambio	 	= array('refclientes','refubicaciones','datalloguer','numpertax','persset','maxtaxa','refestados');
         $lblreemplazo	= array('Client','Ubicaciones','Data Contracte','N° Pers Taxa','Pers Total','Max Taxa','Estat');

         $resVar1 = $serviciosReferencias->traerClientes();
         $cadRef1 	= $serviciosFunciones->devolverSelectBoxActivo($resVar1,array(1,2),' ',mysql_result($resultado,0,'refclientes'));

         $resVar2 = $serviciosReferencias->traerUbicaciones();
         $cadRef2 	= $serviciosFunciones->devolverSelectBoxActivo($resVar2,array(4,1,2),' - ',mysql_result($resultado,0,'refubicaciones'));

         $resVar3 = $serviciosReferencias->traerEstados();
         $cadRef3 	= $serviciosFunciones->devolverSelectBoxActivo($resVar3,array(1),'',mysql_result($resultado,0,'refestados'));


         $refdescripcion = array(0 => $cadRef1,1=>$cadRef2, 2=>$cadRef3);
         $refCampo 	=  array('refclientes','refubicaciones','refestados');

      break;


      default:
         // code...
         break;
   }

   $formulario = $serviciosFunciones->camposTablaModificar($id, $idTabla,$modificar,$tabla,$lblCambio,$lblreemplazo,$refdescripcion,$refCampo);

   echo $formulario;
}


function frmAjaxNuevo($serviciosReferencias,$serviciosFunciones) {
   $tabla = $_POST['tabla'];
   $id = $_POST['id'];

   switch ($tabla) {
      case 'dblloguersadicional':

         $insertar = "insertarLloguersadicional";
         $idTabla = "idllogueradicional";

         $lblCambio	 	= array("reflloguers",'personas');
         $lblreemplazo	= array("Lloguer",'Adultos');

         $resVar1 = $serviciosReferencias->traerLloguersPorIdAux($id);
         $cadRef1 	= $serviciosFunciones->devolverSelectBoxActivo($resVar1,array(4,5),' - ', $id);


         $refdescripcion = array(0=>$cadRef1);
         $refCampo 	=  array('reflloguers');

         $resLA = $serviciosReferencias->traerLloguersadicionalPorLloguer($id);
         $cadTabla = "<table class='table table-hover'>
                     <thead>
                     <th>Adultos</th>
                     <th>Menores</th>
                     <th>Entrada</th>
                     <th>Sortida</th>
                     <th>Taxa Per</th>
                     <th>Taxa Tur.</th>
                     <th>Total</th>
                     <th>Accions</th>
                     </thead>
                     <tbody>";
         while ($row = mysql_fetch_array($resLA)) {
            $cadTabla .= "<tr>";
            $cadTabla .= "<td>".$row['personas']."</td>";
            $cadTabla .= "<td>".$row['menores']."</td>";
            $cadTabla .= "<td>".$row['entrada']."</td>";
            $cadTabla .= "<td>".$row['sortida']."</td>";
            $cadTabla .= "<td>".$row['taxapersona']."</td>";
            $cadTabla .= "<td>".$row['taxaturistica']."</td>";
            $cadTabla .= "<td>".($row['taxaturistica'] + $row['taxapersona'])."</td>";
            $cadTabla .= '<td><button type="button" class="btn bg-red btn-circle waves-effect waves-circle waves-float btnEliminarLA" id="'.$row['idllogueradicional'].'">
				<i class="material-icons">delete</i>
			</button></td>';
            $cadTabla .= "</tr>";
         }
         $cadTabla .= "</tbody></table>";

         $resV['aux'] = array(
                        'desde' => mysql_result($resVar1,0,'entrada'),
                        'hasta' => mysql_result($resVar1,0,'sortida'),
                        'vista' => $cadTabla
                     );

      break;

      default:
         // code...
         break;
   }

   $formulario = $serviciosFunciones->camposTablaViejo($insertar ,$tabla,$lblCambio,$lblreemplazo,$refdescripcion,$refCampo);

   $resV['formulario'] = $formulario;

   header('Content-type: application/json');
   echo json_encode($resV);
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
echo 'Hubo un error al insertar datos';
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
echo 'Hubo un error al modificar datos';
}
}
function eliminarPeriodos($serviciosReferencias) {
$id = $_POST['id'];
$res = $serviciosReferencias->eliminarPeriodos($id);
if ($res == true) {
echo '';
} else {
echo 'Hubo un error al modificar datos';
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
         echo 'Hubo un error al insertar datos';
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
         echo 'Hubo un error al modificar datos';
      }
   }

   function modificarTarifaSola($serviciosReferencias) {
      $id = $_POST['idtarifamod'];
      $tarifa = $_POST['tarifamod'];

      $res = $serviciosReferencias->modificarTarifaSola($id,$tarifa);

      if ($res == true) {
         echo '';
      } else {
         echo 'Hubo un error al modificar datos';
      }
   }



   function eliminarTarifas($serviciosReferencias) {
      $id = $_POST['id'];

      $res = $serviciosReferencias->eliminarTarifas($id);

      if ($res == true) {
         echo '';
      } else {
         echo 'Hubo un error al modificar datos';
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
         echo 'Hubo un error al insertar datos';
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
         echo 'Hubo un error al modificar datos';
      }
   }

   function eliminarUbicaciones($serviciosReferencias) {
      $id = $_POST['id'];

      $res = $serviciosReferencias->eliminarUbicaciones($id);

      if ($res == true) {
         echo '';
      } else {
         echo 'Hubo un error al modificar datos';
      }
   }


function insertarFormaspagos($serviciosReferencias) {
$formapago = $_POST['formapago'];
$abreviatura = $_POST['abreviatura'];
$res = $serviciosReferencias->insertarFormaspagos($formapago,$abreviatura);
if ((integer)$res > 0) {
echo '';
} else {
echo 'Hubo un error al insertar datos';
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
echo 'Hubo un error al modificar datos';
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
         echo 'Hubo un error al insertar datos';
      }
   }

   function modificarTipoubicacion($serviciosReferencias) {
      $id = $_POST['id'];
      $tipoubicacion = $_POST['tipoubicacion'];

      $res = $serviciosReferencias->modificarTipoubicacion($id,$tipoubicacion);

      if ($res == true) {
         echo '';
      } else {
         echo 'Hubo un error al modificar datos';
      }
   }

   function eliminarTipoubicacion($serviciosReferencias) {
      $id = $_POST['id'];

      $res = $serviciosReferencias->eliminarTipoubicacion($id);

      if ($res == true) {
         echo '';
      } else {
         echo 'Hubo un error al modificar datos';
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
   $reflocatarios			=	$_POST['reflocatarios'];

	$res = $serviciosReferencias->insertarUsuarios($usuario,$password,$refroll,$email,$nombre,1,$reflocatarios);
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
   $reflocatarios			=	$_POST['reflocatarios'];

   if (isset($_POST['activo'])) {
      $activo = 1;
   } else {
      $activo = 0;
   }



	echo $serviciosUsuarios->modificarUsuario($id,$usuario,$password,$refroll,$email,$nombre,$activo,$reflocatarios);
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
