<?php

/**
 * @Usuarios clase en donde se accede a la base de datos
 * @ABM consultas sobre las tablas de usuarios y usarios-clientes
 */

date_default_timezone_set('Europe/Madrid');

class ServiciosReferencias {


	/* PARA Lloguercomentarios */

function insertarLloguercomentarios($reflloguers,$comentario) {
$sql = "insert into dblloguercomentarios(idlloguercomentario,reflloguers,comentario)
values ('',".$reflloguers.",'".$comentario."')";
$res = $this->query($sql,1);
return $res;
}


function modificarLloguercomentarios($id,$reflloguers,$comentario) {
$sql = "update dblloguercomentarios
set
reflloguers = ".$reflloguers.",comentario = '".$comentario."'
where idlloguercomentario =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarLloguercomentarios($id) {
$sql = "delete from dblloguercomentarios where idlloguercomentario =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerLloguercomentarios() {
$sql = "select
l.idlloguercomentario,
l.reflloguers,
l.comentario
from dblloguercomentarios l
inner join dblloguers llo ON llo.idlloguer = l.reflloguers
inner join dbclientes cl ON cl.idcliente = llo.refclientes
inner join dbubicaciones ub ON ub.idubicacion = llo.refubicaciones
inner join tbestados es ON est.idestado = llo.refestados
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerLloguercomentariosPorId($id) {
$sql = "select idlloguercomentario,reflloguers,comentario from dblloguercomentarios where idlloguercomentario =".$id;
$res = $this->query($sql,0);
return $res;
}

function traerLloguercomentariosPorLloguer($id) {
$sql = "select idlloguercomentario,comentario from dblloguercomentarios where reflloguers =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* /* Fin de la Tabla: dblloguercomentarios*/

	/* PARA Locatarios */

	function traerLocatariosajax($length, $start, $busqueda,$colSort,$colSortDir) {

		$where = '';

		$busqueda = str_replace("'","",$busqueda);
		if ($busqueda != '') {
			$where = " where c.cognom like '%".$busqueda."%' or c.nom like '%".$busqueda."%' or c.nif like '%".$busqueda."%' or c.carrer like '%".$busqueda."%' or c.codipostal like '%".$busqueda."%' or c.ciutat like '%".$busqueda."%' or td.pais like '%".$busqueda."%' or td.telefon like '%".$busqueda."%' or td.email like '%".$busqueda."%'";
		}

		$sql = "select
	   c.idlocatario,
	   c.cognom,
	   c.nom,
	   c.nif,
	   c.carrer,
	   c.codipostal,
	   c.ciutat,
	   c.pais,
	   c.telefon,
	   c.email
	   from dblocatarios c
		".$where."
		ORDER BY ".$colSort." ".$colSortDir."
		limit ".$start.",".$length;

		$res = $this->query($sql,0);
		return $res;
	}

	function insertarLocatarios($cognom,$nom,$nif,$carrer,$codipostal,$ciutat,$pais,$telefon,$email) {
		$sql = "insert into dblocatarios(idlocatario,cognom,nom,nif,carrer,codipostal,ciutat,pais,telefon,email)
		values ('','".($cognom)."','".($nom)."','".($nif)."','".($carrer)."','".($codipostal)."','".($ciutat)."','".($pais)."','".($telefon)."','".($email)."')";

		$res = $this->query($sql,1);
		return $res;
	}


	function modificarLocatarios($id,$cognom,$nom,$nif,$carrer,$codipostal,$ciutat,$pais,$telefon,$email) {
		$sql = "update dblocatarios
		set
		cognom = '".($cognom)."',nom = '".($nom)."',nif = '".($nif)."',carrer = '".($carrer)."',codipostal = '".($codipostal)."',ciutat = '".($ciutat)."',pais = '".($pais)."',telefon = '".($telefon)."',email = '".($email)."'
		where idlocatario =".$id;

		$res = $this->query($sql,0);
		return $res;
	}


	function eliminarLocatarios($id) {
		$sql = "delete from dblocatarios where idlocatario =".$id;

		$res = $this->query($sql,0);
		return $res;
	}


	function traerLocatarios() {
		$sql = "select
		l.idlocatario,
		l.cognom,
		l.nom,
		l.nif,
		l.carrer,
		l.codipostal,
		l.ciutat,
		l.pais,
		l.telefon,
		l.email
		from dblocatarios l
		order by 1";
		$res = $this->query($sql,0);
		return $res;
	}


	function traerLocatariosPorId($id) {
		$sql = "select idlocatario,cognom,nom,nif,carrer,codipostal,ciutat,pais,telefon,email from dblocatarios where idlocatario =".$id;
		$res = $this->query($sql,0);
		return $res;
	}

	/* Fin */
	/* /* Fin de la Tabla: dblocatarios*/

	function buscarAlquilerPorFechaUbicacionPorDia($fecha, $idubicacion) {
		$sql = "SELECT
				    l.idlloguer, c.nom, c.cognom, c.telefon, est.estado, l.total, l.persset,
					 est.color, DATE_FORMAT(l.entrada, '%Y-%m-%d') as entrada, DATE_FORMAT(l.sortida, '%Y-%m-%d') as sortida
				FROM
				    dblloguers l
				        INNER JOIN
				    dbclientes c ON l.refclientes = c.idcliente
				        INNER JOIN
				    tbestados est ON est.idestado = l.refestados
				WHERE
				    CAST('".$fecha."' AS DATE) BETWEEN l.entrada AND l.sortida
				    and l.refubicaciones = ".$idubicacion;

		$res = $this->query($sql,0);
		return $res;
	}

	function buscarAlquilerPorFechaUbicacion($fechadesde, $fechahasta, $idubicacion) {
		$sql = "SELECT
				    l.idlloguer, c.nom, c.cognom, c.telefon, est.estado, l.total, l.persset,
					 est.color
				FROM
				    dblloguers l
				        INNER JOIN
				    dbclientes c ON l.refclientes = c.idcliente
				        INNER JOIN
				    tbestados est ON est.idestado = l.refestados
				WHERE
				    (((l.entrada BETWEEN '".$fechadesde."' AND '".$fechahasta."')
				        AND (l.entrada <> '".$fechahasta."'))
				        OR ((l.sortida BETWEEN '".$fechadesde."' AND '".$fechahasta."')
				        AND (l.sortida <> '".$fechadesde."'))) and l.refubicaciones = ".$idubicacion;

		$res = $this->query($sql,0);
		return $res;
	}

	function traerPeriodosDisponibilidad($any) {
		$sql = "SELECT
				    p.idperiodo,p.periodo, (datediff(p.finsaperiode,p.desdeperiode) / 7) as semanas ,p.desdeperiode
				FROM
				    dbperiodos p
				where p.any = ".$any."
				order by p.desdeperiode ";

		$res = $this->query($sql,0);
 		return $res;
	}

	/* PARA Lloguersadicional */

	function insertarLloguersadicional($reflloguers,$personas,$entrada,$sortida,$taxapersona,$taxaturistica,$menores) {
	$sql = "insert into dblloguersadicional(idllogueradicional,reflloguers,personas,entrada,sortida,taxapersona,taxaturistica,menores)
	values ('',".$reflloguers.",".$personas.",'".($entrada)."','".($sortida)."',".$taxapersona.",".$taxaturistica.",".$menores.")";
	$res = $this->query($sql,1);
	return $res;
	}


	function modificarLloguersadicional($id,$reflloguers,$personas,$entrada,$sortida,$taxapersona,$taxaturistica,$menores) {
	$sql = "update dblloguersadicional
	set
	reflloguers = ".$reflloguers.",personas = ".$personas.",entrada = '".($entrada)."',sortida = '".($sortida)."',taxapersona = ".$taxapersona.",taxaturistica = ".$taxaturistica.",menores = ".$menores."
	where idllogueradicional =".$id;
	$res = $this->query($sql,0);
	return $res;
	}


	function eliminarLloguersadicional($id) {
	$sql = "delete from dblloguersadicional where idllogueradicional =".$id;
	$res = $this->query($sql,0);
	return $res;
	}

	function eliminarLloguersadicionalPorLloguer($idlloguer) {
	$sql = "delete from dblloguersadicional where reflloguers =".$idlloguer;
	$res = $this->query($sql,0);
	return $res;
	}

	function traerLloguersadicional() {
	$sql = "select
	l.idllogueradicional,
	l.reflloguers,
	l.personas,
	l.entrada,
	l.sortida,
	l.taxapersona,
	l.taxaturistica,
	l.menores
	from dblloguersadicional l
	inner join dblloguers llo ON llo.idlloguer = l.reflloguers
	order by 1";
	$res = $this->query($sql,0);
	return $res;
	}

	function traerLloguersadicionalPorLloguer($idlloguer) {
	$sql = "select
	l.idllogueradicional,
	l.reflloguers,
	l.personas,
	l.entrada,
	l.sortida,
	l.taxapersona,
	l.taxaturistica,
	l.menores,
	datediff(l.sortida,l.entrada) as dias
	from dblloguersadicional l
	inner join dblloguers llo ON llo.idlloguer = l.reflloguers
	where l.reflloguers = ".$idlloguer."
	order by 1";
	$res = $this->query($sql,0);
	return $res;
	}



	function traerLloguersadicionalPorId($id) {
	$sql = "select idllogueradicional,reflloguers,personas,entrada,sortida,taxapersona,taxaturistica,menores from dblloguersadicional where idllogueradicional =".$id;
	$res = $this->query($sql,0);
	return $res;
	}

	/* Fin */
	/* /* Fin de la Tabla: dblloguersadicional*/

	/* PARA Pagos */

	function insertarPagos($reflloguers,$refformaspagos,$cuota,$monto,$taxa,$fecha,$fechapago,$usuario,$cancelado) {
		$sql = "insert into dbpagos(idpago,reflloguers,refformaspagos,cuota,monto,taxa,fecha,fechapago,usuario,cancelado)
		values ('',".$reflloguers.",".$refformaspagos.",".$cuota.",".$monto.",".$taxa.",'".($fecha)."','".($fechapago)."','".($usuario)."',".$cancelado.")";

		$res = $this->query($sql,1);
		return $res;
	}


	function modificarPagos($id,$reflloguers,$refformaspagos,$cuota,$monto,$taxa,$fecha,$fechapago,$usuario,$cancelado) {
		$sql = "update dbpagos
		set
		reflloguers = ".$reflloguers.",refformaspagos = ".$refformaspagos.",cuota = ".$cuota.",monto = ".$monto.",taxa = ".$taxa.",fecha = '".($fecha)."',fechapago = '".($fechapago)."',usuario = '".($usuario)."',cancelado = ".$cancelado."
		where idpago =".$id;

		$res = $this->query($sql,0);
		return $res;
	}

	function modificarPagosParcial($id,$cuota,$taxa,$fechapago,$usuario) {
		$sql = "update dbpagos
		set
		cuota = ".$cuota.",taxa = ".$taxa.",fechapago = '".($fechapago)."',usuario = '".($usuario)."'
		where idpago =".$id;

		$res = $this->query($sql,0);
		return $res;
	}


	function eliminarPagos($id) {
		$sql = "delete from dbpagos where idpago =".$id;
		$res = $this->query($sql,0);
		return $res;
	}


	function traerPagos() {
		$sql = "select
		p.idpago,
		p.reflloguers,
		p.refformaspagos,
		p.cuota,
		p.monto,
		p.taxa,
		p.fecha,
		p.fechapago,
		p.usuario,
		p.cancelado
		from dbpagos p
		inner join dblloguers llo ON llo.idlloguer = p.reflloguers
		inner join dbclientes cl ON cl.idcliente = llo.refclientes
		inner join dbubicaciones ub ON ub.idubicacion = llo.refubicaciones
		inner join tbformaspagos fo ON fo.idformapago = p.refformaspagos
		order by 1";
		$res = $this->query($sql,0);
		return $res;
	}

	function traerPagosPorLloguers($idlloguer) {
		$sql = "select
		p.idpago,
		p.reflloguers,
		p.refformaspagos,
		p.cuota,
		p.monto,
		p.taxa,
		p.fecha,
		p.fechapago,
		p.usuario,
		p.cancelado
		from dbpagos p
		inner join dblloguers llo ON llo.idlloguer = p.reflloguers
		inner join dbclientes cl ON cl.idcliente = llo.refclientes
		inner join dbubicaciones ub ON ub.idubicacion = llo.refubicaciones
		left join tbformaspagos fo ON fo.idformapago = p.refformaspagos
		where p.reflloguers = ".$idlloguer."
		order by 1";
		$res = $this->query($sql,0);
		return $res;
	}

	function faltaPagar($idlloguer) {

		$resLloguerAdicional =  $this->traerLloguersadicionalPorLloguer($idlloguer);

	   $taxaturisticaAdicional = 0;
	   $totalTaxaPersona = 0;

	   while ($rowAd = mysql_fetch_array($resLloguerAdicional)) {

	   	$taxaturisticaAdicional += $rowAd['taxaturistica'];

	   	$totalTaxaPersona += $rowAd['taxapersona'];

	   }

		$cadAgregar = $taxaturisticaAdicional + $totalTaxaPersona;

		$sql = "SELECT
				    l.total + ".$cadAgregar.", COALESCE((l.total + ".$cadAgregar.") - SUM(p.monto), (l.total + ".$cadAgregar.")) AS falta
				FROM
				    dblloguers l
				        LEFT JOIN
				    dbpagos p ON l.idlloguer = p.reflloguers
				where l.idlloguer = ".$idlloguer."
				GROUP BY l.total";

		//die(var_dump($sql));

		$res = $this->query($sql,0);
		return $res;
	}


	function faltaPagarDato($idlloguer) {
		$sql = "SELECT
				    l.total, COALESCE(l.total - SUM(p.monto), l.total) AS falta
				FROM
				    dblloguers l
				        LEFT JOIN
				    dbpagos p ON l.idlloguer = p.reflloguers
				where l.idlloguer = ".$idlloguer."
				GROUP BY l.total";

		$res = $this->query($sql,0);

		if (mysql_num_rows($res)>0) {
			return mysql_result($res,0,1);
		}
		return 0;
	}


	function traerPagosPorId($id) {
		$sql = "select idpago,reflloguers,refformaspagos,cuota,monto,taxa,fecha,fechapago,usuario,cancelado from dbpagos where idpago =".$id;
		$res = $this->query($sql,0);
		return $res;
	}

	function traerPagosPorIdCompleto($id) {
		$sql = "select
					idpago,
					reflloguers,
					refformaspagos,
					cuota,
					monto,
					taxa,
					fecha,
					fechapago,
					usuario,
					cancelado,
					fp.formapago
				from dbpagos p
				inner
				join 		tbformaspagos fp
				on			p.refformaspagos = fp.idformapago
				where idpago =".$id;
		$res = $this->query($sql,0);
		return $res;
	}

	/* Fin */
	/* /* Fin de la Tabla: dbpagos*/

	function s_datediff( $str_interval, $dt_menor, $dt_maior, $relative=false){

       if( is_string( $dt_menor)) $dt_menor = date_create( $dt_menor);
       if( is_string( $dt_maior)) $dt_maior = date_create( $dt_maior);

       $diff = date_diff( $dt_menor, $dt_maior, ! $relative);

       switch( $str_interval){
           case "y":
               $total = $diff->y + $diff->m / 12 + $diff->d / 365.25; break;
           case "m":
               $total= $diff->y * 12 + $diff->m + $diff->d/30 + $diff->h / 24;
               break;
           case "d":
               $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h/24 + $diff->i / 60;
               break;
           case "h":
               $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60;
               break;
           case "i":
               $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;
               break;
           case "s":
               $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
               break;
          }
       if( $diff->invert)
               return -1 * $total;
       else    return $total;
   }


	/* calculos para el alquiler */
	function calcularCoeficienteTarifa($idubicacion, $fecha) {
		$sql = "SELECT
					    t.tarifa / 7, p.periodo, t.tarifa
					FROM
					    dbtarifas t
					        INNER JOIN
					    dbperiodos p ON t.refperiodos = p.idperiodo
					where ('".$fecha."' between p.desdeperiode and p.finsaperiode)
					and t.reftipoubicacion = ".$idubicacion."
					and p.desdeperiode <> '".$fecha."'";
		$res = $this->query($sql,0);

		//die(var_dump($sql));
		if (mysql_num_rows($res)>0) {
			return array('tarifa' => mysql_result($res,0,0), 'periodo' => mysql_result($res,0,1), 'precio' => mysql_result($res,0,2));
		}

		return array('tarifa' => 0, 'periodo' => '', 'precio' => 0);
	}


	function calcularTarifa($idubicacion, $fechadesde, $fechahasta, $personas) {
		$resUbicacion = $this->traerUbicacionesPorId($idubicacion);
		$idtipoubicacion = mysql_result($resUbicacion,0,'reftipoubicacion');

		$resTaxa = $this->traerTaxa();

		$taxaPer = mysql_result($resTaxa,0,1);
		$taxaTur = mysql_result($resTaxa,0,2);
		$taxaMax = mysql_result($resTaxa,0,3);

		$dias = $this->s_datediff('d', $fechadesde, $fechahasta, false);

		$fechaInicio	=	strtotime($fechadesde);
		$fechaFin		=	strtotime($fechahasta);

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
			$totalTaxaPersona = $personas * 1 * $taxaPer;
		} else {
			$totalTaxaPersona = $personas * $dias / 7 * $taxaPer;
		}

		for($i=$fechaInicio+86400; $i<=$fechaFin; $i+=86400){
		    $totalTarifa += $this->calcularCoeficienteTarifa($idtipoubicacion,date("Y-m-d", $i))['tarifa'];
		}

		return $totalTarifa;
		// + $totalTaxaPersona + $totalTaxaTuristica;

	}


	function calcularTarifaArray($idubicacion, $fechadesde, $fechahasta, $personas,$total,$falta,$segundopago) {
		$resUbicacion = $this->traerUbicacionesPorId($idubicacion);
		$idtipoubicacion = mysql_result($resUbicacion,0,'reftipoubicacion');

		$resTaxa = $this->traerTaxa();

		$taxaPer = mysql_result($resTaxa,0,1);
		$taxaTur = mysql_result($resTaxa,0,2);
		$taxaMax = mysql_result($resTaxa,0,3);

		$dias = $this->s_datediff('d', $fechadesde, $fechahasta, false);

		$fechaInicio	=	strtotime($fechadesde);
		$fechaFin		=	strtotime($fechahasta);

		$totalTarifa = 0;

		for($i=$fechaInicio+86400; $i<=$fechaFin; $i+=86400){
		    $totalTarifa += $this->calcularCoeficienteTarifa($idtipoubicacion,date("Y-m-d", $i))['tarifa'];
		}

		return array('tarifa'=> round($totalTarifa,2, PHP_ROUND_HALF_UP), 'taxapersona'=> $personas[0], 'taxaturistica'=> $personas[1], 'total' => $total, 'falta' => $falta, 'fechasegundopago' => $segundopago);

	}
	/* fin alquileres */

	/* PARA Lloguers */

	function insertarLloguers($refclientes,$refubicaciones,$datalloguer,$entrada,$sortida,$total,$numpertax,$persset,$taxa,$maxtaxa,$refestados) {
		$sql = "insert into dblloguers(idlloguer,refclientes,refubicaciones,datalloguer,entrada,sortida,total,numpertax,persset,taxa,maxtaxa, refestados)
		values ('',".$refclientes.",".$refubicaciones.",'".($datalloguer)."','".($entrada)."','".($sortida)."',".$total.",".$numpertax.",".$persset.",".$taxa.",".$maxtaxa.",".$refestados.")";

		$res = $this->query($sql,1);
		return $res;
	}


	function modificarLloguers($id,$refclientes,$refubicaciones,$datalloguer,$entrada,$sortida,$total,$numpertax,$persset,$taxa,$maxtaxa,$refestados) {
	$sql = "update dblloguers
	set
	refclientes = ".$refclientes.",refubicaciones = ".$refubicaciones.",datalloguer = '".($datalloguer)."',entrada = '".($entrada)."',sortida = '".($sortida)."',total = ".$total.",numpertax = ".$numpertax.",persset = ".$persset.",taxa = ".$taxa.",maxtaxa = ".$maxtaxa.",refestados = ".$refestados."
	where idlloguer =".$id;
	$res = $this->query($sql,0);
	return $res;
	}


	function eliminarLloguers($id) {
	$sql = "delete from dblloguers where idlloguer =".$id;
	$res = $this->query($sql,0);
	return $res;
	}


	function traerLloguers() {
	$sql = "select
	l.idlloguer,
	l.refclientes,
	l.refubicaciones,
	l.datalloguer,
	l.entrada,
	l.sortida,
	l.total,
	l.numpertax,
	l.persset,
	l.taxa,
	l.maxtaxa,
	l.refestados,
	nrolloguer
	from dblloguers l
	inner join dbclientes cli ON cli.idcliente = l.refclientes
	inner join dbubicaciones ubi ON ubi.idubicacion = l.refubicaciones
	inner join tbtipoubicacion ti ON ti.idtipoubicacion = ubi.reftipoubicacion
	inner join tbestados est on est.idestado = l.refestados
	where l.entrada is not null
	order by 1";
	$res = $this->query($sql,0);
	return $res;
	}

	function traerLloguersPorIdCompleto($id) {
	$sql = "select
	l.idlloguer,
	l.refclientes,
	l.refubicaciones,
	l.datalloguer,
	l.entrada,
	l.sortida,
	l.total,
	l.numpertax,
	l.persset,
	l.taxa,
	l.maxtaxa,
	l.refestados,
	est.estado,
	cli.nom,
	cli.cognom,
	cli.nif,
	cli.pais,
	cli.carrer,
	cli.codipostal,
	cli.ciutat,
	ubi.codapartament,
	DATE_FORMAT(l.entrada, '%d/%m/%Y') as entradacorta,
	DATE_FORMAT(l.sortida, '%d/%m/%Y') as sortidacorta,
	year(l.entrada) as anyentrada,
	year(l.sortida) as anysortida,
	ubi.dormitorio,
	DATEDIFF(l.sortida,l.entrada) as dias,
	ti.idtipoubicacion,
    coalesce((max(p.personas) + max(p.menores)), l.numpertax) as personasreales
	from dblloguers l
	inner join dbclientes cli ON cli.idcliente = l.refclientes
	inner join dbubicaciones ubi ON ubi.idubicacion = l.refubicaciones
	inner join tbtipoubicacion ti ON ti.idtipoubicacion = ubi.reftipoubicacion
	inner join tbestados est on est.idestado = l.refestados
	left join dblloguersadicional p ON p.reflloguers = l.idlloguer
	where l.idlloguer = ".$id."
	group by l.idlloguer,
	l.refclientes,
	l.refubicaciones,
	l.datalloguer,
	l.entrada,
	l.sortida,
	l.total,
	l.numpertax,
	l.persset,
	l.taxa,
	l.maxtaxa,
	l.refestados,
	est.estado,
	cli.nom,
	cli.cognom,
	cli.nif,
	cli.pais,
	cli.carrer,
	cli.codipostal,
	cli.ciutat,
	ubi.codapartament,
	ubi.dormitorio,
	ti.idtipoubicacion
	order by 1";
	$res = $this->query($sql,0);
	return $res;
	}

	function traerLloguersajax($length, $start, $busqueda,$colSort,$colSortDir) {
		$where = '';

		switch ($colSort) {
			case 4:
				$colSort = 'l.entrada';
			break;
			case 5:
				$colSort = 'l.sortida';
			break;
			default:
				$colSort = 'l.entrada';
			break;
		}


		$busqueda = str_replace("'","",$busqueda);
		if ($busqueda != '') {
			$where = " and (concat(cli.cognom, ' ', cli.nom) like '%".$busqueda."%' or ti.tipoubicacion like '%".$busqueda."%' or DATE_FORMAT(l.entrada, '%d/%m/%Y') like '%".$busqueda."%' or DATE_FORMAT(l.sortida, '%d/%m/%Y') like '%".$busqueda."%' or l.persset like '%".$busqueda."%' or coalesce(nrolloguer,l.idlloguer) like '%".$busqueda."%')";
		}

		$sql = "select
		l.idlloguer,
		concat(cli.cognom, ' ', cli.nom, ' - NIF:', cli.nif) as cliente,
		ti.tipoubicacion,
		DATE_FORMAT(l.entrada, '%d/%m/%Y') as entrada,
		DATE_FORMAT(l.sortida, '%d/%m/%Y') as sortida,
		datediff(l.sortida, l.entrada) as dias,
		l.total,
		est.estado,
		coalesce(nrolloguer,l.idlloguer) as nrolooguer
		from dblloguers l
		inner join dbclientes cli ON cli.idcliente = l.refclientes
		inner join dbubicaciones ubi ON ubi.idubicacion = l.refubicaciones
		inner join tbtipoubicacion ti ON ti.idtipoubicacion = ubi.reftipoubicacion
		inner join tbestados est on est.idestado = l.refestados
		where (l.entrada is not null and l.sortida is not null) ".$where."
		ORDER BY ".$colSort." ".$colSortDir."
		limit ".$start.",".$length;

		$res = $this->query($sql,0);
		return $res;
	}


	function traerLloguersPorId($id) {
	$sql = "select idlloguer,refclientes,refubicaciones,datalloguer,entrada,sortida,total,numpertax,persset,taxa,maxtaxa,refestados from dblloguers where idlloguer =".$id;
	$res = $this->query($sql,0);
	return $res;
	}

	function traerLloguersPorIdAux($id) {
	$sql = "select idlloguer,refclientes,refubicaciones,datalloguer,
	DATE_FORMAT(entrada, '%d/%m/%Y') as entrada,
	DATE_FORMAT(sortida, '%d/%m/%Y') as sortida,
	total,numpertax,persset,taxa,maxtaxa,refestados from dblloguers where idlloguer =".$id;
	$res = $this->query($sql,0);
	return $res;
	}

	/* Fin */
	/* /* Fin de la Tabla: dblloguers*/

	/* PARA Periodos */

	function insertarPeriodos($periodo,$any,$desdeperiode,$finsaperiode) {
	$sql = "insert into dbperiodos(idperiodo,periodo,any,desdeperiode,finsaperiode)
	values ('','".($periodo)."',".$any.",'".($desdeperiode)."','".($finsaperiode)."')";
	$res = $this->query($sql,1);
	return $res;
	}


	function modificarPeriodos($id,$periodo,$any,$desdeperiode,$finsaperiode) {
	$sql = "update dbperiodos
	set
	periodo = '".($periodo)."',any = ".$any.",desdeperiode = '".($desdeperiode)."',finsaperiode = '".($finsaperiode)."'
	where idperiodo =".$id;
	$res = $this->query($sql,0);
	return $res;
	}


	function eliminarPeriodos($id) {
	$sql = "delete from dbperiodos where idperiodo =".$id;
	$res = $this->query($sql,0);
	return $res;
	}

	function traerPeriodosajax($length, $start, $busqueda,$colSort,$colSortDir) {

		$where = '';

		$busqueda = str_replace("'","",$busqueda);
		if ($busqueda != '') {
			$where = " where p.periodo like '%".$busqueda."%' or p.any like '%".$busqueda."%' or p.desdeperiode like '%".$busqueda."%' or p.finsaperiode like '%".$busqueda."%'";
		}

		$sql = "select
		p.idperiodo,
		p.periodo,
		p.any,
		p.desdeperiode,
		p.finsaperiode
		from dbperiodos p
		".$where."
		ORDER BY ".$colSort." ".$colSortDir."
		limit ".$start.",".$length;

		$res = $this->query($sql,0);
		return $res;
	}

	function traerPeriodos() {
	$sql = "select
	p.idperiodo,
	p.periodo,
	p.any,
	p.desdeperiode,
	p.finsaperiode
	from dbperiodos p
	order by 1";
	$res = $this->query($sql,0);
	return $res;
	}

	function traerPeriodosPorOrdenPorAny($any) {
		$sql = "select
		p.idperiodo,
		p.periodo,
		p.any,
		p.desdeperiode,
		p.finsaperiode
		from dbperiodos p
		where p.any = ".$any."
		order by 2,4,5,1";
		$res = $this->query($sql,0);
		return $res;
	}


	function traerPeriodosPorId($id) {
	$sql = "select idperiodo,periodo,any,desdeperiode,finsaperiode from dbperiodos where idperiodo =".$id;
	$res = $this->query($sql,0);
	return $res;
	}

	/* Fin */
	/* /* Fin de la Tabla: dbperiodos*/

/* PARA Clientes */

function traerClientesajax($length, $start, $busqueda,$colSort,$colSortDir) {

	$where = '';

	$busqueda = str_replace("'","",$busqueda);
	if ($busqueda != '') {
		$where = " where c.cognom like '%".$busqueda."%' or c.nom like '%".$busqueda."%' or c.nif like '%".$busqueda."%' or c.carrer like '%".$busqueda."%' or c.codipostal like '%".$busqueda."%' or c.ciutat like '%".$busqueda."%' or c.pais like '%".$busqueda."%' or c.telefon like '%".$busqueda."%' or c.email like '%".$busqueda."%' or c.comentaris like '%".$busqueda."%' or c.telefon2 like '%".$busqueda."%' or c.email2 like '%".$busqueda."%'";
	}

	$sql = "select
   c.idcliente,
   c.cognom,
   c.nom,
   c.nif,
   c.carrer,
   c.codipostal,
   c.ciutat,
   c.pais,
   c.telefon,
   c.email,
   c.comentaris,
   c.telefon2,
   c.email2
   from dbclientes c
	".$where."
	ORDER BY ".$colSort." ".$colSortDir."
	limit ".$start.",".$length;

	$res = $this->query($sql,0);
	return $res;
}

function insertarClientes($cognom,$nom,$nif,$carrer,$codipostal,$ciutat,$pais,$telefon,$email,$comentaris,$telefon2,$email2) {
$sql = "insert into dbclientes(idcliente,cognom,nom,nif,carrer,codipostal,ciutat,pais,telefon,email,comentaris,telefon2,email2)
values ('','".$cognom."','".$nom."','".$nif."','".$carrer."','".$codipostal."','".$ciutat."','".$pais."','".$telefon."','".$email."','".$comentaris."','".$telefon2."','".$email2."')";
$res = $this->query($sql,1);
return $res;
}


function modificarClientes($id,$cognom,$nom,$nif,$carrer,$codipostal,$ciutat,$pais,$telefon,$email,$comentaris,$telefon2,$email2) {
$sql = "update dbclientes
set
cognom = '".$cognom."',nom = '".$nom."',nif = '".$nif."',carrer = '".$carrer."',codipostal = '".$codipostal."',ciutat = '".$ciutat."',pais = '".$pais."',telefon = '".$telefon."',email = '".$email."',comentaris = '".$comentaris."',telefon2 = '".$telefon2."',email2 = '".$email2."'
where idcliente =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarClientes($id) {
$sql = "delete from dbclientes where idcliente =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerClientes() {
$sql = "select
c.idcliente,
c.cognom,
c.nom,
c.nif,
c.carrer,
c.codipostal,
c.ciutat,
c.pais,
c.telefon,
c.email,
c.comentaris,
c.telefon2,
c.email2
from dbclientes c
order by trim(c.cognom),trim(c.nom)";
$res = $this->query($sql,0);
return $res;
}


function traerClientesPorId($id) {
$sql = "select idcliente,cognom,nom,nif,carrer,codipostal,ciutat,pais,telefon,email,comentaris,telefon2,email2 from dbclientes where idcliente =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* /* Fin de la Tabla: dbclientes*/



/* PARA Tarifas */

function insertarTarifas($tarifa,$reftipoubicacion,$refperiodos) {
$sql = "insert into dbtarifas(idtarifa,reftipoubicacion,refperiodos,tarifa)
values ('',".$reftipoubicacion.",".$refperiodos.",".$tarifa.")";
$res = $this->query($sql,1);
return $res;
}


function modificarTarifas($id,$tarifa,$reftipoubicacion,$refperiodos) {
$sql = "update dbtarifas
set
reftipoubicacion = ".$reftipoubicacion.",refperiodos = ".$refperiodos.",tarifa = ".$tarifa."
where idtarifa =".$id;
$res = $this->query($sql,0);
return $res;
}

function modificarTarifaSola($id,$tarifa) {
$sql = "update dbtarifas
set
tarifa = ".$tarifa."
where idtarifa =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarTarifas($id) {
$sql = "delete from dbtarifas where idtarifa =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerTarifasajax($length, $start, $busqueda,$colSort,$colSortDir) {

	$where = '';

	$busqueda = str_replace("'","",$busqueda);
	if ($busqueda != '') {
		$where = " where t.tarifa like '%".$busqueda."%' or p.desdeperiode like '%".$busqueda."%' or tip.tipoubicacion like '%".$busqueda."%' or p.finsaperiode like '%".$busqueda."%'";
	}

	$sql = "select
	t.idtarifa,
	t.tarifa,
	tip.tipoubicacion,
	p.desdeperiode,
	p.finsaperiode,
	t.reftipoubicacion,
	t.refperiodos
	from dbtarifas t
	inner join tbtipoubicacion tip ON tip.idtipoubicacion = t.reftipoubicacion
	inner join dbperiodos p ON p.idperiodo = t.refperiodos
	".$where."
	ORDER BY ".$colSort." ".$colSortDir."
	limit ".$start.",".$length;

	//die(var_dump($sql));
	$res = $this->query($sql,0);
	return $res;
}


function traerTarifas() {
$sql = "select
t.idtarifa,
t.tarifa,
t.reftipoubicacion,
t.refperiodos
from dbtarifas t
inner join tbtipoubicacion tip ON tip.idtipoubicacion = t.reftipoubicacion
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerTarifasPorId($id) {
$sql = "select idtarifa,tarifa,reftipoubicacion,refperiodos from dbtarifas where idtarifa =".$id;
$res = $this->query($sql,0);
return $res;
}

function traerTarifasPorPeriodoTipoUbicacion($idperiodo, $idtipoubicacion) {
$sql = "select idtarifa,tarifa from dbtarifas where refperiodos =".$idperiodo." and reftipoubicacion = ".$idtipoubicacion;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* Fin de la Tabla: dbtarifas */


/* PARA Ubicaciones */

function insertarUbicaciones($dormitorio,$color,$reftipoubicacion,$codapartament,$hutg) {
$sql = "insert into dbubicaciones(idubicacion,dormitorio,color,reftipoubicacion,codapartament,hutg)
values ('',".$dormitorio.",'".$color."',".$reftipoubicacion.",'".$codapartament."','".$hutg."')";
$res = $this->query($sql,1);
return $res;
}


function modificarUbicaciones($id,$dormitorio,$color,$reftipoubicacion,$codapartament,$hutg) {
$sql = "update dbubicaciones
set
dormitorio = ".$dormitorio.",color = '".$color."',reftipoubicacion = ".$reftipoubicacion.",codapartament = '".$codapartament."',hutg = '".$hutg."'
where idubicacion =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarUbicaciones($id) {
$sql = "delete from dbubicaciones where idubicacion =".$id;
$res = $this->query($sql,0);
return $res;
}

function traerUbicacionesajax($length, $start, $busqueda,$colSort,$colSortDir) {

	$where = '';

	$busqueda = str_replace("'","",$busqueda);
	if ($busqueda != '') {
		$where = " where u.dormitorio like '%".$busqueda."%' or u.color like '%".$busqueda."%' or tip.tipoubicacion like '%".$busqueda."%' or u.codapartament like '%".$busqueda."%' or u.hutg like '%".$busqueda."%'";
	}

	$sql = "select
	u.idubicacion,
	u.dormitorio,
	u.color,
	tip.tipoubicacion,
	u.codapartament,
	u.hutg,
	u.reftipoubicacion
	from dbubicaciones u
	inner join tbtipoubicacion tip ON tip.idtipoubicacion = u.reftipoubicacion
	".$where."
	ORDER BY ".$colSort." ".$colSortDir."
	limit ".$start.",".$length;

	//die(var_dump($sql));
	$res = $this->query($sql,0);
	return $res;
}

function traerUbicaciones() {
$sql = "select
u.idubicacion,
u.dormitorio,
u.color,
u.reftipoubicacion,
u.codapartament,
u.hutg
from dbubicaciones u
inner join tbtipoubicacion tip ON tip.idtipoubicacion = u.reftipoubicacion
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerUbicacionesPorId($id) {
$sql = "select idubicacion,dormitorio,color,reftipoubicacion,codapartament,hutg from dbubicaciones where idubicacion =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* /* Fin de la Tabla: dbubicaciones*/

/* PARA Formaspagos */

function insertarFormaspagos($formapago,$abreviatura) {
$sql = "insert into tbformaspagos(idformapago,formapago,abreviatura)
values ('','".$formapago."','".$abreviatura."')";
$res = $this->query($sql,1);
return $res;
}


function modificarFormaspagos($id,$formapago,$abreviatura) {
$sql = "update tbformaspagos
set
formapago = '".$formapago."',abreviatura = '".$abreviatura."'
where idformapago =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarFormaspagos($id) {
$sql = "delete from tbformaspagos where idformapago =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerFormaspagos() {
$sql = "select
f.idformapago,
f.formapago,
f.abreviatura
from tbformaspagos f
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerFormaspagosPorId($id) {
$sql = "select idformapago,formapago,abreviatura from tbformaspagos where idformapago =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* /* Fin de la Tabla: tbformaspagos*/


/* PARA Tipoubicacion */

function insertarTipoubicacion($tipoubicacion) {
$sql = "insert into tbtipoubicacion(idtipoubicacion,tipoubicacion)
values ('','".$tipoubicacion."')";
$res = $this->query($sql,1);
return $res;
}


function modificarTipoubicacion($id,$tipoubicacion) {
$sql = "update tbtipoubicacion
set
tipoubicacion = '".$tipoubicacion."'
where idtipoubicacion =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarTipoubicacion($id) {
$sql = "delete from tbtipoubicacion where idtipoubicacion =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerTipoubicacionajax($length, $start, $busqueda,$colSort,$colSortDir) {

	$where = '';

	$busqueda = str_replace("'","",$busqueda);
	if ($busqueda != '') {
		$where = " where t.tipoubicacion like '%".$busqueda."%'";
	}

	$sql = "select
	t.idtipoubicacion,
	t.tipoubicacion
	from tbtipoubicacion t
	".$where."
	ORDER BY ".$colSort." ".$colSortDir."
	limit ".$start.",".$length;

	//die(var_dump($sql));
	$res = $this->query($sql,0);
	return $res;
}

function traerTipoubicacion() {
$sql = "select
t.idtipoubicacion,
t.tipoubicacion
from tbtipoubicacion t
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerTipoubicacionPorId($id) {
$sql = "select idtipoubicacion,tipoubicacion from tbtipoubicacion where idtipoubicacion =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* Fin de la Tabla: tbtipoubicacion */

   function GUID()
   {
       if (function_exists('com_create_guid') === true)
       {
           return trim(com_create_guid(), '{}');
       }

       return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
   }


///**********  PARA SUBIR ARCHIVOS  ***********************//////////////////////////
	function borrarDirecctorio($dir) {
		array_map('unlink', glob($dir."/*.*"));

	}

	function borrarArchivo($id,$archivo) {
		$sql	=	"delete from images where idfoto =".$id;

		$res =  unlink("./../archivos/".$archivo);
		if ($res)
		{
			$this->query($sql,0);
		}
		return $res;
	}


	function existeArchivo($id,$nombre,$type) {
		$sql		=	"select * from images where refproyecto =".$id." and imagen = '".$nombre."' and type = '".$type."'";
		$resultado  =   $this->query($sql,0);

			   if(mysql_num_rows($resultado)>0){

				   return mysql_result($resultado,0,0);
			   }

			   return 0;
	}

	function sanear_string($string)
   {

       $string = trim($string);

       $string = str_replace(
           array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
           array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
           $string
       );

       $string = str_replace(
           array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
           array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
           $string
       );

       $string = str_replace(
           array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
           array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
           $string
       );

       $string = str_replace(
           array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
           array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
           $string
       );

       $string = str_replace(
           array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
           array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
           $string
       );

       $string = str_replace(
           array('ñ', 'Ñ', 'ç', 'Ç'),
           array('n', 'N', 'c', 'C',),
           $string
       );

       $string = str_replace(
           array('(', ')', '{', '}',' '),
           array('', '', '', '',''),
           $string
       );



       return $string;
   }

   function crearDirectorioPrincipal($dir) {
   	if (!file_exists($dir)) {
   		mkdir($dir, 0777);
   	}
   }



	function subirArchivo($file,$carpeta,$id,$token,$observacion, $refcategorias, $anio, $mes, $reftipoarchivos, $asunto) {


		$dir_destino_padre = '../archivos/'.$carpeta.'/';
		$dir_destino = '../archivos/'.$carpeta.'/'.$id.'/';
		$imagen_subida = $dir_destino . $this->sanear_string(str_replace(' ','',basename($_FILES[$file]['name'])));

		$noentrar = '../imagenes/index.php';
		$nuevo_noentrar = '../archivos/'.$carpeta.'/'.$id.'/'.'index.php';

		//die(var_dump($dir_destino));
		if (!file_exists($dir_destino_padre)) {
			mkdir($dir_destino_padre, 0777);
		}

		if (!file_exists($dir_destino)) {
			mkdir($dir_destino, 0777);
		}


		if(!is_writable($dir_destino)){

			echo "no tiene permisos";

		}	else	{
			if ($_FILES[$file]['tmp_name'] != '') {
				if(is_uploaded_file($_FILES[$file]['tmp_name'])){
					//la carpeta de libros solo los piso
					if ($carpeta == 'galeria') {
						$this->eliminarFotoPorObjeto($id);
					}
					/*echo "Archivo ". $_FILES['foto']['name'] ." subido con éxtio.\n";
					echo "Mostrar contenido\n";
					echo $imagen_subida;*/
					if (move_uploaded_file($_FILES[$file]['tmp_name'], $imagen_subida)) {

						$archivo = $this->sanear_string($_FILES[$file]["name"]);
						$tipoarchivo = $_FILES[$file]["type"];

						$filename = $dir_destino.'descarga.zip';
						$zip = new ZipArchive();

						if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
						exit('cannot open <$filename>\n');
						}

						$zip->addFile($dir_destino.$archivo, $archivo);

						$zip->close();

                  copy($noentrar, $nuevo_noentrar);

						$this->insertarArchivos($carpeta,$token,str_replace(' ','',$archivo),$tipoarchivo, $observacion, $refcategorias, $anio, $mes,$reftipoarchivos,$asunto);

                  //$this->modificarClienteImagenPorId($carpeta,$archivo);

						echo "";

						copy($noentrar, $nuevo_noentrar);

					} else {
						echo "Posible ataque de carga de archivos!\n";
					}
				}else{
					echo "Posible ataque del archivo subido: ";
					echo "nombre del archivo '". $_FILES[$file]['tmp_name'] . "'.";
				}
			}
		}
	}


   function subirFoto($file,$carpeta,$id) {

      // carpeta = 'idcliente'
      // id = 'foto frente 1 o foto dorsal 2'


		$dir_destino_padre = '../data/'.$carpeta.'/';

		$dir_destino = '../data/'.$carpeta.'/'.$id.'/';

      $this->borrarDirecctorio($dir_destino);

		$imagen_subida = $dir_destino . $this->sanear_string(str_replace(' ','',basename($_FILES[$file]['name'])));

		$noentrar = '../imagenes/index.php';
		$nuevo_noentrar = '../data/'.$carpeta.'/'.$id.'/'.'index.php';

		//die(var_dump($dir_destino));
		if (!file_exists($dir_destino_padre)) {
			mkdir($dir_destino_padre, 0777);
		}

		if (!file_exists($dir_destino)) {
			mkdir($dir_destino, 0777);
		}


		if(!is_writable($dir_destino)){

			echo "no tiene permisos";

		}	else	{
			if ($_FILES[$file]['tmp_name'] != '') {
				if(is_uploaded_file($_FILES[$file]['tmp_name'])){
					//la carpeta de libros solo los piso
					if ($carpeta == 'galeria') {
						$this->eliminarFotoPorObjeto($id);
					}
					/*echo "Archivo ". $_FILES['foto']['name'] ." subido con éxtio.\n";
					echo "Mostrar contenido\n";
					echo $imagen_subida;*/
					if (move_uploaded_file($_FILES[$file]['tmp_name'], $imagen_subida)) {

						$archivo = $this->sanear_string($_FILES[$file]["name"]);
						$tipoarchivo = $_FILES[$file]["type"];

						$filename = $dir_destino.'descarga.zip';
						$zip = new ZipArchive();

						if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
						exit('cannot open <$filename>\n');
						}

						$zip->addFile($dir_destino.$archivo, $archivo);

						$zip->close();

                  if ($id == 1) {
                     $resMod = $this->modificarClienteImagenFrentePorId($carpeta, $archivo);
                  } else {
                     $resMod = $this->modificarClienteImagenDorsalPorId($carpeta, $archivo);
                  }

						echo '';

						copy($noentrar, $nuevo_noentrar);

					} else {
						echo "Posible ataque de carga de archivos!\n";
					}
				}else{
					echo "Posible ataque del archivo subido: ";
					echo "nombre del archivo '". $_FILES[$file]['tmp_name'] . "'.";
				}
			}
		}
	}



	function TraerFotosRelacion($id) {
		$sql    =   "select 'galeria',s.idproducto,f.imagen,f.idfoto,f.type
							from dbproductos s

							inner
							join images f
							on	s.idproducto = f.refproyecto

							where s.idproducto = ".$id;
		$result =   $this->query($sql, 0);
		return $result;
	}


	function eliminarFoto($id)
	{

		$sql		=	"select concat('galeria','/',s.idproducto,'/',f.imagen) as archivo
							from dbproductos s

							inner
							join images f
							on	s.idproducto = f.refproyecto

							where f.idfoto =".$id;
		$resImg		=	$this->query($sql,0);

		if (mysql_num_rows($resImg)>0) {
			$res 		=	$this->borrarArchivo($id,mysql_result($resImg,0,0));
		} else {
			$res = true;
		}
		if ($res == false) {
			return 'Error al eliminar datos';
		} else {
			return '';
		}
	}


   function zerofill($valor, $longitud){
    $res = str_pad($valor, $longitud, '0', STR_PAD_LEFT);
    return $res;
   }

   function existeDevuelveId($sql) {

   	$res = $this->query($sql,0);

   	if (mysql_num_rows($res)>0) {
   		return mysql_result($res,0,0);
   	}
   	return 0;
   }



   /* PARA Estados */

   function insertarEstados($estado,$color,$icono) {
   $sql = "insert into tbestados(idestado,estado,color,icono)
   values (null,'".$estado."','".$color."','".$icono."')";
   $res = $this->query($sql,1);
   return $res;
   }


   function modificarEstados($id,$estado,$color,$icono) {
   $sql = "update tbestados
   set
   estado = '".$estado."',color = '".$color."',icono = '".$icono."'
   where idestado =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function eliminarEstados($id) {
   $sql = "delete from tbestados where idestado =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function traerEstados() {
   $sql = "select
   e.idestado,
   e.estado,
   e.color,
   e.icono
   from tbestados e
   order by 1";
   $res = $this->query($sql,0);
   return $res;
   }


   function traerEstadosPorId($id) {
   $sql = "select idestado,estado,color,icono from tbestados where idestado =".$id;
   $res = $this->query($sql,0);
   return $res;
   }

   /* Fin */
   /* /* Fin de la Tabla: tbestados*/

   /* PARA Meses */

   function insertarMeses($meses,$desde,$hasta) {
   $sql = "insert into tbmeses(idmes,meses,desde,hasta)
   values (null,'".$meses."',".$desde.",".$hasta.")";
   $res = $this->query($sql,1);
   return $res;
   }


   function modificarMeses($id,$meses,$desde,$hasta) {
   $sql = "update tbmeses
   set
   meses = '".$meses."',desde = ".$desde.",hasta = ".$hasta."
   where idmes =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function eliminarMeses($id) {
   $sql = "delete from tbmeses where idmes =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function traerMeses() {
   $sql = "select
   m.idmes,
   m.meses,
   m.desde,
   m.hasta
   from tbmeses m
   order by 1";
   $res = $this->query($sql,0);
   return $res;
   }


   function traerMesesPorMes($mes) {
   $sql = "select
   m.idmes,
   m.meses,
   m.desde,
   m.hasta
   from tbmeses m
   where ".$mes." between m.desde and m.hasta
   order by 1";
   $res = $this->query($sql,0);
   return $res;
   }


   function traerMesesPorId($id) {
   $sql = "select idmes,meses,desde,hasta from tbmeses where idmes =".$id;
   $res = $this->query($sql,0);
   return $res;
   }

   /* Fin */
   /* /* Fin de la Tabla: tbmeses*/



   /* PARA Clientes */

   function existeCliente($nrodocumento, $modifica = 0, $id = 0) {
      if ($modifica == 1) {
         $sql = "select * from dbclientes where nrodocumento = '".$nrodocumento."' and idcliente <> ".$id;
      } else {
         $sql = "select * from dbclientes where nrodocumento = '".$nrodocumento."'";
      }

   	$res = $this->query($sql,0);
   	if (mysql_num_rows($res)>0) {
   		return true;
   	} else {
   		return false;
   	}
   }



   /* PARA Usuarios */

   function insertarUsuarios($usuario,$password,$refroles,$email,$nombrecompleto,$activo,$reflocatarios) {
   $sql = "insert into dbusuarios(idusuario,usuario,password,refroles,email,nombrecompleto,activo,reflocatarios)
   values (null,'".$usuario."','".$password."',".$refroles.",'".$email."','".$nombrecompleto."',".$activo.",".$reflocatarios.")";
   $res = $this->query($sql,1);
   return $res;
   }


   function modificarUsuarios($id,$usuario,$password,$refroles,$email,$nombrecompleto,$activo,$reflocatarios) {
   $sql = "update dbusuarios
   set
   usuario = '".$usuario."',password = '".$password."',refroles = ".$refroles.",email = '".$email."',nombrecompleto = '".$nombrecompleto."',activo = ".$activo." ,reflocatarios = ".($reflocatarios)."
   where idusuario =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function eliminarUsuarios($id) {
   $sql = "update dbusuarios set activo = 0 where idusuario =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function traerUsuarios() {
   $sql = "select
   u.idusuario,
   u.usuario,
   u.password,
   u.refroles,
   u.email,
   u.nombrecompleto,
   u.reflocatarios
   from dbusuarios u
   inner join tbroles rol ON rol.idrol = u.refroles
   order by 1";
   $res = $this->query($sql,0);
   return $res;
   }


   function traerUsuariosPorId($id) {
   $sql = "select idusuario,usuario,password,refroles,email,nombrecompleto,(case when activo = 1 then 'Si' else 'No' end) as activo,reflocatarios from dbusuarios where idusuario =".$id;
   $res = $this->query($sql,0);
   return $res;
   }

   /* Fin */
   /* /* Fin de la Tabla: dbusuarios*/




   /* PARA Predio_menu */

   function insertarPredio_menu($url,$icono,$nombre,$Orden,$hover,$permiso) {
   $sql = "insert into predio_menu(idmenu,url,icono,nombre,Orden,hover,permiso)
   values (null,'".$url."','".$icono."','".$nombre."',".$Orden.",'".$hover."','".$permiso."')";
   $res = $this->query($sql,1);
   return $res;
   }


   function modificarPredio_menu($id,$url,$icono,$nombre,$Orden,$hover,$permiso) {
   $sql = "update predio_menu
   set
   url = '".$url."',icono = '".$icono."',nombre = '".$nombre."',Orden = ".$Orden.",hover = '".$hover."',permiso = '".$permiso."'
   where idmenu =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function eliminarPredio_menu($id) {
   $sql = "delete from predio_menu where idmenu =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function traerPredio_menu() {
   $sql = "select
   p.idmenu,
   p.url,
   p.icono,
   p.nombre,
   p.Orden,
   p.hover,
   p.permiso
   from predio_menu p
   order by 1";
   $res = $this->query($sql,0);
   return $res;
   }


   function traerPredio_menuPorId($id) {
   $sql = "select idmenu,url,icono,nombre,Orden,hover,permiso from predio_menu where idmenu =".$id;
   $res = $this->query($sql,0);
   return $res;
   }

   /* Fin */
   /* /* Fin de la Tabla: predio_menu*/



   /* PARA Roles */

   function insertarRoles($descripcion,$activo) {
   $sql = "insert into tbroles(idrol,descripcion,activo)
   values (null,'".$descripcion."',".$activo.")";
   $res = $this->query($sql,1);
   return $res;
   }


   function modificarRoles($id,$descripcion,$activo) {
   $sql = "update tbroles
   set
   descripcion = '".$descripcion."',activo = ".$activo."
   where idrol =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function eliminarRoles($id) {
   $sql = "delete from tbroles where idrol =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function traerRoles() {
   $sql = "select
   r.idrol,
   r.descripcion,
   r.activo
   from tbroles r
   order by 1";
   $res = $this->query($sql,0);
   return $res;
   }


   function traerRolesPorId($id) {
   $sql = "select idrol,descripcion,activo from tbroles where idrol =".$id;
   $res = $this->query($sql,0);
   return $res;
   }

   /* Fin */
   /* /* Fin de la Tabla: tbroles*/




   /* PARA Configuracion */

   function insertarConfiguracion($razonsocial,$empresa,$sistema,$direccion,$telefono,$email) {
   $sql = "insert into tbconfiguracion(idconfiguracion,razonsocial,empresa,sistema,direccion,telefono,email)
   values (null,'".$razonsocial."','".$empresa."','".$sistema."','".$direccion."','".$telefono."','".$email."')";
   $res = $this->query($sql,1);
   return $res;
   }


   function modificarConfiguracion($id,$razonsocial,$empresa,$sistema,$direccion,$telefono,$email) {
   $sql = "update tbconfiguracion
   set
   razonsocial = '".$razonsocial."',empresa = '".$empresa."',sistema = '".$sistema."',direccion = '".$direccion."',telefono = '".$telefono."',email = '".$email."'
   where idconfiguracion =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function eliminarConfiguracion($id) {
   $sql = "delete from tbconfiguracion where idconfiguracion =".$id;
   $res = $this->query($sql,0);
   return $res;
   }


   function traerConfiguracion() {
   $sql = "select
   c.idconfiguracion,
   c.razonsocial,
   c.empresa,
   c.sistema,
   c.direccion,
   c.telefono,
   c.email
   from tbconfiguracion c
   order by 1";
   $res = $this->query($sql,0);
   return $res;
   }


   function traerConfiguracionPorId($id) {
   $sql = "select idconfiguracion,razonsocial,empresa,sistema,direccion,telefono,email from tbconfiguracion where idconfiguracion =".$id;
   $res = $this->query($sql,0);
   return $res;
   }

   /* Fin */
   /* /* Fin de la Tabla: tbconfiguracion*/

	/* PARA Taxa */

	function insertarTaxa($taxaper,$taxaturistico,$maxtaxa) {
	$sql = "insert into tbtaxa(idtaxa,taxaper,taxaturistico,maxtaxa)
	values ('',".$taxaper.",".$taxaturistico.")";
	$res = $this->query($sql,1);
	return $res;
	}


	function modificarTaxa($id,$taxaper,$taxaturistico,$maxtaxa) {
	$sql = "update tbtaxa
	set
	taxaper = ".$taxaper.",taxaturistico = ".$taxaturistico.",maxtaxa = ".$maxtaxa."
	where idtaxa =".$id;
	$res = $this->query($sql,0);
	return $res;
	}


	function eliminarTaxa($id) {
	$sql = "delete from tbtaxa where idtaxa =".$id;
	$res = $this->query($sql,0);
	return $res;
	}


	function traerTaxa() {
	$sql = "select
	t.idtaxa,
	t.taxaper,
	t.taxaturistico,
	t.maxtaxa
	from tbtaxa t
	order by 1";
	$res = $this->query($sql,0);
	return $res;
	}


	function traerTaxaPorId($id) {
	$sql = "select idtaxa,taxaper,taxaturistico,maxtaxa from tbtaxa where idtaxa =".$id;
	$res = $this->query($sql,0);
	return $res;
	}

	/* Fin */
	/* /* Fin de la Tabla: tbtaxa*/


function query($sql,$accion) {



		require_once 'appconfig.php';

		$appconfig	= new appconfig();
		$datos		= $appconfig->conexion();
		$hostname	= $datos['hostname'];
		$database	= $datos['database'];
		$username	= $datos['username'];
		$password	= $datos['password'];

		$conex = mysql_connect($hostname,$username,$password) or die ("no se puede conectar".mysql_error());

		mysql_select_db($database);

		        $error = 0;
		mysql_query("BEGIN");
		$result=mysql_query($sql,$conex);
		if ($accion && $result) {
			$result = mysql_insert_id();
		}
		if(!$result){
			$error=1;
		}
		if($error==1){
			mysql_query("ROLLBACK");
			return false;
		}
		 else{
			mysql_query("COMMIT");
			return $result;
		}

	}

}

?>
