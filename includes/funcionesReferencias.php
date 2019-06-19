<?php

/**
 * @Usuarios clase en donde se accede a la base de datos
 * @ABM consultas sobre las tablas de usuarios y usarios-clientes
 */

date_default_timezone_set('Europe/Madrid');

class ServiciosReferencias {

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
		order by 2";
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
		$where = " where c.cognom like '%".$busqueda."%' or c.nom like '%".$busqueda."%' or c.nif like '%".$busqueda."%' or c.carrer like '%".$busqueda."%' or c.codipostal like '%".$busqueda."%' or c.ciutat like '%".$busqueda."%' or td.pais like '%".$busqueda."%' or td.telefon like '%".$busqueda."%' or td.email like '%".$busqueda."%' or td.comentaris like '%".$busqueda."%' or td.telefon2 like '%".$busqueda."%' or td.email2 like '%".$busqueda."%'";
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
order by 1";
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

   function insertarUsuarios($usuario,$password,$refroles,$email,$nombrecompleto,$activo,$refclientes) {
   $sql = "insert into dbusuarios(idusuario,usuario,password,refroles,email,nombrecompleto,activo,refclientes)
   values (null,'".$usuario."','".$password."',".$refroles.",'".$email."','".$nombrecompleto."',".$activo.",".$refclientes.")";
   $res = $this->query($sql,1);
   return $res;
   }


   function modificarUsuarios($id,$usuario,$password,$refroles,$email,$nombrecompleto,$activo,$refclientes) {
   $sql = "update dbusuarios
   set
   usuario = '".$usuario."',password = '".$password."',refroles = ".$refroles.",email = '".$email."',nombrecompleto = '".$nombrecompleto."',activo = ".$activo." ,refclientes = ".($refclientes)."
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
   u.refpersonal
   from dbusuarios u
   inner join tbroles rol ON rol.idrol = u.refroles
   order by 1";
   $res = $this->query($sql,0);
   return $res;
   }


   function traerUsuariosPorId($id) {
   $sql = "select idusuario,usuario,password,refroles,email,nombrecompleto,(case when activo = 1 then 'Si' else 'No' end) as activo,refclientes from dbusuarios where idusuario =".$id;
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
