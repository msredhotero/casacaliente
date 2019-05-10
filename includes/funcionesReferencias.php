<?php

/**
 * @Usuarios clase en donde se accede a la base de datos
 * @ABM consultas sobre las tablas de usuarios y usarios-clientes
 */

date_default_timezone_set('Europe/Madrid');

class ServiciosReferencias {

   /* PARA Subidas */

   function insertarSubidas($refclientes,$archivo,$type) {
      $sql = "insert into dbsubidas(idsubida,refclientes,archivo,type)
      values ('',".$refclientes.",'".($archivo)."','".($type)."')";

      $res = $this->query($sql,1);
      return $res;
   }


   function modificarSubidas($id,$refclientes,$archivo,$type,$fecha) {
      $sql = "update dbsubidas
      set
      refclientes = ".$refclientes.",archivo = '".($archivo)."',type = '".($type)."',fecha = ".$fecha."
      where idsubida =".$id;

      $res = $this->query($sql,0);
      return $res;
   }


   function eliminarSubidas($id, $idcliente) {

      $resSubida = $this->traerSubidasPorIdCliente($id, $idcliente);

      $dir_destino = '../subidas/'.$id.'/';

      $this->borrarDirecctorio($dir_destino);

      //unlink($dir_destino);

      $sql = "delete from dbsubidas where idsubida =".$id;
      $res = $this->query($sql,0);
      return $res;
   }


   function traerSubidas() {
      $sql = "select
      s.idsubida,
      s.refclientes,
      s.archivo,
      s.type,
      s.fecha
      from dbsubidas s
      order by 1";

      $res = $this->query($sql,0);
      return $res;
   }

   function traerSubidasPorCliente($idcliente) {
      $sql = "select
      s.idsubida,
      s.refclientes,
      s.archivo,
      s.type,
      s.fecha
      from dbsubidas s
      where s.refclientes = ".$idcliente."
      order by 1";

      $res = $this->query($sql,0);
      return $res;
   }

   function traerSubidasPorClienteajax($idcliente,$length, $start, $busqueda) {

      $where = '';

   		$busqueda = str_replace("'","",$busqueda);
   		if ($busqueda != '') {
   			$where = "and s.archivo like '%".$busqueda."%' or s.fecha like '%".$busqueda."%'";
   		}

      $sql = "SELECT
               s.idsubida,
               s.archivo,
               s.fecha,
               s.refclientes,
               s.type
            from dbsubidas s
         where s.refclientes = ".$idcliente." ".$where."
         ORDER BY s.fecha DESC";
      $res = $this->query($sql,0);
      return $res;
   }


   function traerSubidasPorId($id) {
      $sql = "select idsubida,refclientes,archivo,type,fecha from dbsubidas where idsubida =".$id;

      $res = $this->query($sql,0);
      return $res;
   }

   function traerSubidasPorIdCliente($id, $idcliente) {
      $sql = "select idsubida,refclientes,archivo,type,fecha from dbsubidas where idsubida =".$id." and refclientes = ".$idcliente;

      $res = $this->query($sql,0);
      return $res;
   }

   /* Fin */
   /* /* Fin de la Tabla: dbsubidas*/

   function traerAniosFacturadosPorCliente($id) {
      $sql = "select f.anio from dbfacturas f where f.refclientes = ".$id." group by f.anio";

      $res = $this->query($sql,0);

      $sqlVacio = 'select 2019';

      $resVacio = $this->query($sqlVacio,0);

      if (mysql_num_rows($res) > 0) {
         return $res;
      }

      return $resVacio;
   }

   function traerTotalPorCliente($idcliente, $tipo, $anio, $trimestre) {
      $sql = 'select sum(f.total) from dbfacturas f where f.anio = '.$anio.' and f.refmeses = '.$trimestre.' and  f.refclientes = '.$idcliente.' and f.refestados = 2 and f.reftipofacturas = '.$tipo;

      $res = $this->query($sql,0);

      if (mysql_num_rows($res) > 0) {
         return mysql_result($res,0,0);
      }

      return 0;
   }

   function existeAnioTrimestreFacturasPorCliente($idcliente,$anio,$trimestre) {
      $sql = 'select f.* from dbfacturas f where f.anio = '.$anio.' and  f.refclientes = '.$idcliente.' and f.refestados = 2 and f.reftipofacturas = 1 and f.refmeses = '.$trimestre;

      $res = $this->query($sql,0);

      if (mysql_num_rows($res) > 0) {
         return 1;
      }

      return 0;
   }

   function traerTotalImpuestosPorClienteAnioTrimestre($idcliente, $tipo, $anio, $trimestre) {
      // tipo 1 irpf
      // tipo 2 iva
      $acumulador = 0;
      $acumuladorIRFP = 0;
      $inicio = $trimestre;

      if ($tipo == 1) {
         for ($i=$trimestre;$i>=$trimestre;$i--) {
            $sql = 'select (SUM(r.total) * 0.2) , sum(r.irff) as irff from (
               select sum(f.total) as total, 0 AS irff from dbfacturas f where f.anio = '.$anio.' and  f.refclientes = '.$idcliente.' and f.refestados = 2 and f.reftipofacturas = 1 and f.refmeses = '.$i.'
               union all
               select -1*sum(f.total) as total, 0 AS irff from dbfacturas f where f.anio = '.$anio.' and  f.refclientes = '.$idcliente.' and f.refestados = 2 and f.reftipofacturas = 2 and f.refmeses = '.$i.'
               union all
               select 0 as total, f.irff AS irff from dbfacturas f where f.anio = '.$anio.' and  f.refclientes = '.$idcliente.' and f.refestados = 2 and f.reftipofacturas = 1 and f.refmeses = '.$i.'
               ) r';


               if ($this->existeAnioTrimestreFacturasPorCliente($idcliente,$anio,$i) == 0) {
                  $inicio -= 1;
               }


               $res = $this->query($sql,0);
               if (mysql_num_rows($res) > 0) {
                  if ($i == $inicio) {
                     //if ($this->existeAnioTrimestreFacturasPorCliente($idcliente,$anio,$trimestre) == 1) {
                        $acumulador = mysql_result($res,0,0);
                        $acumuladorIRFP = mysql_result($res,0,1);
                     //}
                  } else {
                     if ($this->existeAnioTrimestreFacturasPorCliente($idcliente,$anio,$i) == 1) {
                        //die(var_dump($acumulador));
                        $acumulador = mysql_result($res,0,0);
                        $acumuladorIRFP = mysql_result($res,0,1);
                     }
                  }
               }


         }
      } else {
         $sql = 'select sum(r.total) from (
            select sum(f.iva) as total from dbfacturas f where f.anio = '.$anio.' and  f.refclientes = '.$idcliente.' and f.refestados = 2 and f.reftipofacturas = 1 and f.refmeses = '.$trimestre.'
            union all
            select -1*sum(f.iva) as total from dbfacturas f where f.anio = '.$anio.' and  f.refclientes = '.$idcliente.' and f.refestados = 2 and f.reftipofacturas = 2 and f.refmeses = '.$trimestre.'
            ) r';

            $res = $this->query($sql,0);


            if (mysql_num_rows($res) > 0) {
               $acumulador += mysql_result($res,0,0);
            }
      }


      return $acumulador - $acumuladorIRFP;
   }


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


	function obtenerNuevoId($tabla) {
        //u235498999_aif
        $sql = "SELECT AUTO_INCREMENT FROM information_schema.TABLES
                WHERE TABLE_SCHEMA = '6435338_riderz'
                AND TABLE_NAME = '".$tabla."'";
        $res = $this->query($sql,0);
        return mysql_result($res, 0,0);
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

	function eliminarLibro($id)
	{

		$sql		=	"update dblibros set ruta = '' where idlibro =".$id;
		$res		=	$this->query($sql,0);

		if ($res == false) {
			return 'Error al eliminar datos';
		} else {
			return '';
		}
	}


	function eliminarFotoPorObjeto($id)
	{

		$sql		=	"select concat('galeria','/',s.idproducto,'/',f.imagen) as archivo,f.idfoto
							from dbproductos s

							inner
							join images f
							on	s.idproducto = f.refproyecto

							where s.idproducto =".$id;
		$resImg		=	$this->query($sql,0);

		if (mysql_num_rows($resImg)>0) {
			$res 		=	$this->borrarArchivo(mysql_result($resImg,0,1),mysql_result($resImg,0,0));
		} else {
			$res = true;
		}
		if ($res == false) {
			return 'Error al eliminar datos';
		} else {
			return '';
		}
	}

/* fin archivos */



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


function descargar($token) {
	session_start();

	if (isset($_SESSION['usua_predio'])) {

		$res = $this->traerArchivosPorToken($token);

		if (mysql_num_rows($res)>0) {
		    $file = '../archivos/'.mysql_result($res, 0,'refcliente').'/'.mysql_result($res, 0,'idarchivo').'/'.mysql_result($res, 0,'imagen');

		    header('Content-type: application/x-rar-compressed');
		    header('Content-length: ' . filesize($file));
		    readfile($file);
		} else {
			echo 'No existe el archivo o fue borrado';
		}

	} else {

	    echo 'No tienes permiso para la descarga';
	}
}



function validarDNI($dni, $dnicompleto) {
   $letras = array(0=>'T',1=>'R',2=>'W',3=>'A',4=>'G',5=>'W',6=>'Y',7=>'F',8=>'P',9=>'D',10=>'X',11=>'B',12=>'N',
13=>'J',14=>'Z',15=>'S',16=>'Q',17=>'V',18=>'H',19=>'L',20=>'C',21=>'K',22=>'E');

   $resto = $dni % 23;

   $dnicompletoreal = $dni.$letras[$resto];

   if ($dnicompletoreal == $dnicompleto) {
      return 1;
   }
   return 0;

}


function validarNIE($nie, $niecompleto) {
   $letrasP= array('X'=>0,'Y'=>1,'Z'=>2);

   $letras = array(0=>'T',1=>'R',2=>'W',3=>'A',4=>'G',5=>'W',6=>'Y',7=>'F',8=>'P',9=>'D',10=>'X',11=>'B',12=>'N',
13=>'J',14=>'Z',15=>'S',16=>'Q',17=>'V',18=>'H',19=>'L',20=>'C',21=>'K',22=>'E');

   $primeraLetra = substr($niecompleto,0,1);

   $resto = (integer)$letrasP[$primeraLetra].$nie % 23;

   $niecompletoreal = $letrasP[$primeraLetra].$nie.$letras[$resto];

   if ($niecompletoreal == $niecompleto) {
      return $niecompletoreal;
   }
   return $niecompletoreal;

}


/* PARA Facturas */

function insertarFacturas($refclientes,$reftipofacturas,$refestados,$refmeses,$anio,$concepto,$total,$iva,$irff,$fechaingreso,$fechasubido,$imagen) {
$sql = "insert into dbfacturas(idfactura,refclientes,reftipofacturas,refestados,refmeses,anio,concepto,total,iva,irff,fechaingreso,fechasubido,imagen)
values (null,".$refclientes.",".$reftipofacturas.",".$refestados.",".$refmeses.",".$anio.",'".($concepto)."',".($total == '' ? 'null' : $total).",".($iva == '' ? 'null' : $iva).",".($irff == '' ? 'null' : $irff).",'".$fechaingreso."','".$fechasubido."','".$imagen."')";
$res = $this->query($sql,1);
return $res;
}


function modificarFacturas($id,$refclientes,$reftipofacturas,$refestados,$refmeses,$anio,$concepto,$total,$iva,$irff,$fechaingreso,$fechasubido,$imagen) {
$sql = "update dbfacturas
set
refclientes = ".$refclientes.",reftipofacturas = ".$reftipofacturas.",refestados = ".$refestados.",refmeses = ".$refmeses.",anio = ".$anio.",concepto = '".($concepto)."',total = ".$total.",iva = ".$iva.",irff = ".$irff.",fechaingreso = '".($fechaingreso)."',fechasubido = '".($fechasubido)."',imagen = '".($imagen)."'
where idfactura =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarFacturas($id) {
$sql = "delete from dbfacturas where idfactura =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerFacturas() {
   $sql = "SELECT
          f.idfactura,
          CONCAT(c.apellido, ' ', c.nombre) AS cliente,
          tip.tipofactura,
          m.meses,
          f.anio,
          f.concepto,
          f.total,
          f.iva,
          f.irff,
          f.fechaingreso,
          f.fechasubido,
          est.estado,
          f.imagen,
          f.refclientes,
          f.reftipofacturas,
          f.refestados,
          f.refmeses
      FROM
          dbfacturas f
              INNER JOIN
          tbtipofacturas tip ON tip.idtipofactura = f.reftipofacturas
              INNER JOIN
          tbestados est ON est.idestado = f.refestados
              INNER JOIN
          tbmeses m ON m.idmes = f.refmeses
              INNER JOIN
          dbclientes c ON c.idcliente = f.refclientes
      ORDER BY f.anio DESC , m.idmes DESC";
   $res = $this->query($sql,0);
   return $res;
}

function traerFacturasPorEstado($idestado) {
   $sql = "SELECT
          f.idfactura,
          CONCAT(c.apellido, ' ', c.nombre) AS cliente,
          tip.tipofactura,
          m.meses,
          f.anio,
          f.concepto,
          f.total,
          f.iva,
          f.irff,
          f.fechaingreso,
          f.fechasubido,
          est.estado,
          f.imagen,
          f.refclientes,
          f.reftipofacturas,
          f.refestados,
          f.refmeses
      FROM
          dbfacturas f
              INNER JOIN
          tbtipofacturas tip ON tip.idtipofactura = f.reftipofacturas
              INNER JOIN
          tbestados est ON est.idestado = f.refestados
              INNER JOIN
          tbmeses m ON m.idmes = f.refmeses
              INNER JOIN
          dbclientes c ON c.idcliente = f.refclientes
      where est.idestado = ".$idestado."
      ORDER BY f.anio DESC , m.idmes DESC";
   $res = $this->query($sql,0);
   return $res;
}


function traerFacturasPorTipo($idtipofactura) {
   $sql = "SELECT
          f.idfactura,
          CONCAT(c.apellido, ' ', c.nombre) AS cliente,
          tip.tipofactura,
          m.meses,
          f.anio,
          f.concepto,
          f.total,
          f.iva,
          f.irff,
          f.fechaingreso,
          f.fechasubido,
          est.estado,
          f.imagen,
          f.refclientes,
          f.reftipofacturas,
          f.refestados,
          f.refmeses
      FROM
          dbfacturas f
              INNER JOIN
          tbtipofacturas tip ON tip.idtipofactura = f.reftipofacturas
              INNER JOIN
          tbestados est ON est.idestado = f.refestados
              INNER JOIN
          tbmeses m ON m.idmes = f.refmeses
              INNER JOIN
          dbclientes c ON c.idcliente = f.refclientes
      where tip.idtipofactura = ".$idtipofactura."
      ORDER BY f.anio DESC , m.idmes DESC";
   $res = $this->query($sql,0);
   return $res;
}

function traerFacturasPorClienteTipo($idcliente,$tipo) {
   $sql = "SELECT
            f.idfactura,
            tip.tipofactura,
            est.estado,
            f.concepto,
            f.total,
            f.iva,
            f.irff,
            f.total + f.iva - f.irff as importetotal,
            f.fechaingreso,
            f.fechasubido,
            CONCAT(c.apellido, ' ', c.nombre) AS cliente,
            m.meses,
            f.anio,
            f.imagen,
            f.refclientes,
            f.reftipofacturas,
            f.refestados,
            f.refmeses
      FROM
          dbfacturas f
              INNER JOIN
          tbtipofacturas tip ON tip.idtipofactura = f.reftipofacturas
              INNER JOIN
          tbestados est ON est.idestado = f.refestados
              INNER JOIN
          tbmeses m ON m.idmes = f.refmeses
              INNER JOIN
          dbclientes c ON c.idcliente = f.refclientes
      where c.idcliente = ".$idcliente." and f.refestados = 2 and f.reftipofacturas = ".$tipo."
      ORDER BY f.anio DESC , m.idmes DESC";
   $res = $this->query($sql,0);
   return $res;
}


function traerFacturasPorClienteTipoajax($idcliente,$tipo,$length, $start, $busqueda) {

   $where = '';

		$busqueda = str_replace("'","",$busqueda);
		if ($busqueda != '') {
			$where = "and tip.tipofactura like '%".$busqueda."%' or est.estado like '%".$busqueda."%' or f.concepto like '%".$busqueda."%' or f.fechaingreso like '%".$busqueda."%' or f.fechasubido like '%".$busqueda."%'";
		}


   $sql = "SELECT
          ar.token,
          f.concepto,
          f.total + f.iva - f.irff as importetotal,
          f.fechasubido,
          tip.tipofactura,
          (case when est.idestado = 1 then '<h4><span class=''label bg-blue''>Iniciado</span></h4>'
               when est.idestado = 2 then '<h4><span class=''label bg-green''>Aceptado</span></h4>'
               when est.idestado = 3 then '<h4><span class=''label bg-red''>Rechazado</span></h4>' end) as estado,
         f.total,
         f.iva,
         f.irff,
         f.fechaingreso,
          CONCAT(c.apellido, ' ', c.nombre) AS cliente,
          m.meses,
          f.anio,
          f.imagen,
          f.refclientes,
          f.reftipofacturas,
          f.refestados,
          f.refmeses
      FROM
          dbfacturas f
              INNER JOIN
          tbtipofacturas tip ON tip.idtipofactura = f.reftipofacturas
              INNER JOIN
          tbestados est ON est.idestado = f.refestados
              INNER JOIN
          tbmeses m ON m.idmes = f.refmeses
              INNER JOIN
          dbclientes c ON c.idcliente = f.refclientes
              INNER JOIN
         dbarchivos ar ON ar.refclientes = f.idfactura and ar.reftipoarchivos = 1
      where c.idcliente = ".$idcliente." and f.refestados = 2 and f.reftipofacturas = ".$tipo." ".$where."
      ORDER BY f.anio DESC , m.idmes DESC";
   $res = $this->query($sql,0);
   return $res;
}

function traerFacturasPorCliente($idcliente) {
   $sql = "SELECT
            f.idfactura,
            tip.tipofactura,
            est.estado,
            f.concepto,
            f.total,
            f.iva,
            f.irff,
            f.total + f.iva - f.irff as importetotal,
            f.fechaingreso,
            f.fechasubido,
            CONCAT(c.apellido, ' ', c.nombre) AS cliente,
            m.meses,
            f.anio,
            f.imagen,
            f.refclientes,
            f.reftipofacturas,
            f.refestados,
            f.refmeses
      FROM
          dbfacturas f
              INNER JOIN
          tbtipofacturas tip ON tip.idtipofactura = f.reftipofacturas
              INNER JOIN
          tbestados est ON est.idestado = f.refestados
              INNER JOIN
          tbmeses m ON m.idmes = f.refmeses
              INNER JOIN
          dbclientes c ON c.idcliente = f.refclientes
      where c.idcliente = ".$idcliente."
      ORDER BY f.anio DESC , m.idmes DESC";
   $res = $this->query($sql,0);
   return $res;
}


function traerFacturasPorClienteajax($idcliente,$length, $start, $busqueda) {

   $where = '';

		$busqueda = str_replace("'","",$busqueda);
		if ($busqueda != '') {
			$where = "and tip.tipofactura like '%".$busqueda."%' or est.estado like '%".$busqueda."%' or f.concepto like '%".$busqueda."%' or f.fechaingreso like '%".$busqueda."%' or f.fechasubido like '%".$busqueda."%'";
		}


   $sql = "SELECT
          ar.token,
          tip.tipofactura,
          (case when est.idestado = 1 then '<h4><span class=''label bg-blue''>Iniciado</span></h4>'
               when est.idestado = 2 then '<h4><span class=''label bg-green''>Aceptado</span></h4>'
               when est.idestado = 3 then '<h4><span class=''label bg-red''>Rechazado</span></h4>' end) as estado,
          f.concepto,
          f.total,
          f.iva,
          f.irff,
          f.total + f.iva - f.irff as importetotal,
          f.fechaingreso,
          f.fechasubido,
          concat('../../archivos/',f.idfactura,'/',f.imagen) as archivo,
          CONCAT(c.apellido, ' ', c.nombre) AS cliente,
          m.meses,
          f.anio,
          f.imagen,
          f.refclientes,
          f.reftipofacturas,
          f.refestados,
          f.refmeses
      FROM
          dbfacturas f
              INNER JOIN
          tbtipofacturas tip ON tip.idtipofactura = f.reftipofacturas
              INNER JOIN
          tbestados est ON est.idestado = f.refestados
              INNER JOIN
          tbmeses m ON m.idmes = f.refmeses
              INNER JOIN
          dbclientes c ON c.idcliente = f.refclientes
              INNER JOIN
         dbarchivos ar ON ar.refclientes = f.idfactura and ar.reftipoarchivos = 1
      where c.idcliente = ".$idcliente." ".$where."
      ORDER BY f.anio DESC , m.idmes DESC";
   $res = $this->query($sql,0);
   return $res;
}



function traerFacturasajax($length, $start, $busqueda) {

   $where = '';

		$busqueda = str_replace("'","",$busqueda);
		if ($busqueda != '') {
			$where = "where tip.tipofactura like '%".$busqueda."%' or est.estado like '%".$busqueda."%' or f.concepto like '%".$busqueda."%' or f.fechaingreso like '%".$busqueda."%' or f.fechasubido like '%".$busqueda."%'";
		}


   $sql = "SELECT
          f.idfactura,
          tip.tipofactura,
          (case when est.idestado = 1 then '<h4><span class=''label bg-blue''>Iniciado</span></h4>'
               when est.idestado = 2 then '<h4><span class=''label bg-green''>Aceptado</span></h4>'
               when est.idestado = 3 then '<h4><span class=''label bg-red''>Rechazado</span></h4>' end) as estado,
          f.concepto,
          f.total,
          f.iva,
          f.irff,
          f.total + f.iva - f.irff as importetotal,
          f.fechaingreso,
          f.fechasubido,
          CONCAT(c.apellido, ' ', c.nombre) AS cliente,
          m.meses,
          f.anio,
          f.imagen,
          f.refclientes,
          f.reftipofacturas,
          f.refestados,
          f.refmeses
      FROM
          dbfacturas f
              INNER JOIN
          tbtipofacturas tip ON tip.idtipofactura = f.reftipofacturas
              INNER JOIN
          tbestados est ON est.idestado = f.refestados
              INNER JOIN
          tbmeses m ON m.idmes = f.refmeses
              INNER JOIN
          dbclientes c ON c.idcliente = f.refclientes
              INNER JOIN
         dbarchivos ar ON ar.refclientes = f.idfactura and ar.reftipoarchivos = 1
      ".$where."
      ORDER BY f.anio DESC , m.idmes DESC
      limit ".$start.",".$length;
   $res = $this->query($sql,0);
   return $res;
}


function traerFacturasPorGeneral($campos,$idestado='', $idtipofactura='', $idcliente='', $idmes='', $anio='', $fecha='',$limit='') {
   $where = '';

   if ($idestado != '') {
      $where .= ' est.idestado = '.$idestado.' and';
   }
   if ($idtipofactura != '') {
      $where .= ' tip.idtipofactura = '.$idtipofactura.' and';
   }
   if ($idcliente != '') {
      $where .= ' c.idcliente = '.$idcliente.' and';
   }
   if ($idmes != '') {
      $where .= ' m.idmes = '.$idmes.' and';
   }
   if ($anio != '') {
      $where .= ' f.anio = '.$anio.' and';
   }
   if ($fecha != '') {
      $where .= ' ('.$idestado.' between f.fechaingreso and current_date()) and';
   }

   if ($where != '') {
      $where = 'where'.substr($where,0,-3);
   }

   $sql = "SELECT
          ".$campos."
      FROM
          dbfacturas f
              INNER JOIN
          tbtipofacturas tip ON tip.idtipofactura = f.reftipofacturas
              INNER JOIN
          tbestados est ON est.idestado = f.refestados
              INNER JOIN
          tbmeses m ON m.idmes = f.refmeses
              INNER JOIN
          dbclientes c ON c.idcliente = f.refclientes
      ".$where."
      ORDER BY f.anio DESC , m.idmes DESC ".$limit;
   $res = $this->query($sql,0);
   return $res;
}


function traerFacturasPorId($id) {
$sql = "select idfactura,refclientes,reftipofacturas,refestados,refmeses,anio,concepto,total,iva,irff,fechaingreso,fechasubido,imagen from dbfacturas where idfactura =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* /* Fin de la Tabla: dbfacturas*/

/* PARA Estados */

function insertarEstados($estado,$color,$icono) {
$sql = "insert into tbestados(idestado,estado,color,icono)
values (null,'".($estado)."','".($color)."','".($icono)."')";
$res = $this->query($sql,1);
return $res;
}


function modificarEstados($id,$estado,$color,$icono) {
$sql = "update tbestados
set
estado = '".($estado)."',color = '".($color)."',icono = '".($icono)."'
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
values (null,'".($meses)."',".$desde.",".$hasta.")";
$res = $this->query($sql,1);
return $res;
}


function modificarMeses($id,$meses,$desde,$hasta) {
$sql = "update tbmeses
set
meses = '".($meses)."',desde = ".$desde.",hasta = ".$hasta."
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


/* PARA Tipofacturas */

function insertarTipofacturas($tipofactura) {
$sql = "insert into tbtipofacturas(idtipofactura,tipofactura)
values (null,'".($tipofactura)."')";
$res = $this->query($sql,1);
return $res;
}


function modificarTipofacturas($id,$tipofactura) {
$sql = "update tbtipofacturas
set
tipofactura = '".($tipofactura)."'
where idtipofactura =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarTipofacturas($id) {
$sql = "delete from tbtipofacturas where idtipofactura =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerTipofacturas() {
$sql = "select
t.idtipofactura,
t.tipofactura
from tbtipofacturas t
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerTipofacturasPorId($id) {
$sql = "select idtipofactura,tipofactura from tbtipofacturas where idtipofactura =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* /* Fin de la Tabla: tbtipofacturas*/


/* PARA Archivos */
function insertarArchivos($refclientes,$token,$imagen,$type,$observacion,$refcategorias,$anio,$mes,$reftipoarchivos,$asunto) {
$sql = "insert into dbarchivos(idarchivo,refclientes,token,imagen,type,observacion, refcategorias, anio, mes, reftipoarchivos, asunto)
values (null,".$refclientes.",'".($token)."','".($imagen)."','".($type)."','".($observacion)."',".$refcategorias.",".$anio.",".$mes.",".$reftipoarchivos.",'".$asunto."')";
$res = $this->query($sql,1);
return $res;
}


function modificarArchivos($id,$refclientes,$token,$imagen,$type,$observacion,$refcategorias,$anio,$mes) {
$sql = "update dbarchivos
set
refclientes = ".$refclientes.",token = '".($token)."',imagen = '".($imagen)."',type = '".($type)."',observacion = '".($observacion)."' ,refcategorias = ".$refcategorias.",anio = ".$anio.",mes = ".$mes."
where idarchivo =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarArchivos($id) {
$sql = "delete from dbarchivos where token = '".$id."'";
$res = $this->query($sql,0);
return $res;
}

function eliminarArchivosPorId($id) {
$sql = "delete from dbarchivos where idarchivo = ".$id;
$res = $this->query($sql,0);
return $res;
}


function traerArchivos() {
$sql = "select
a.idarchivo,
a.refclientes,
a.token,
a.imagen,
a.type,
a.observacion
from dbarchivos a
order by 1";
$res = $this->query($sql,0);
return $res;
}



function traerArchivosajax($length, $start, $busqueda) {

	$where = '';

	$busqueda = str_replace("'","",$busqueda);
	if ($busqueda != '') {
		$where = "where concat(c.apellido, ' ', c.nombre) like '%".$busqueda."%' or cat.categoria like '%".$busqueda."%' or a.anio like '%".$busqueda."%' or a.mes like '%".$busqueda."%'";
	}

	$sql = "select
   a.token,
   concat(c.apellido, ' ', c.nombre) as apyn,
   cat.categoria,
   a.anio,
   a.mes,
   a.refclientes,
   a.token,
   a.imagen,
   a.type,
   a.observacion
   from dbarchivos a
   inner join dbclientes c on c.idcliente = a.refclientes
   inner join tbcategorias cat on cat.idcategoria = a.refcategorias
	".$where."
	order by a.anio, a.mes
	limit ".$start.",".$length;

	$res = $this->query($sql,0);
	return $res;
}


function traerArchivosGrid() {
$sql = "select
a.token,
concat(c.apellido, ' ', c.nombre) as apyn,
cat.categoria,
a.anio,
a.mes,
a.refclientes,
a.token,
a.imagen,
a.type,
a.observacion
from dbarchivos a
inner join dbclientes c on c.idcliente = a.refclientes
inner join tbcategorias cat on cat.idcategoria = a.refcategorias
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerArchivosPorId($id) {
$sql = "select idarchivo,refclientes,token,imagen,type,observacion from dbarchivos where idarchivo =".$id;
$res = $this->query($sql,0);
return $res;
}

function traerArchivosPorIdCliente($id) {
$sql = "select idarchivo,refclientes,token,imagen,type,observacion from dbarchivos where refclientes =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerArchivosPorToken($token) {
$sql = "select
a.idarchivo,
a.refclientes,
a.token,
a.imagen,
a.type,
a.observacion,
cat.categoria,
a.anio,
a.mes
from dbarchivos a
inner join dbfacturas f on f.idfactura = a.refclientes
inner join tbcategorias cat on cat.idcategoria = a.refcategorias
where a.token = '".$token."'";
$res = $this->query($sql,0);
return $res;
}


function traerArchivosPorClienteajax($idcliente,$length, $start, $busqueda) {
   $where = '';

	$busqueda = str_replace("'","",$busqueda);
	if ($busqueda != '') {
		$where = "where a.asunto like '%".$busqueda."%' or a.observacion like '%".$busqueda."%' or a.mes like '%".$busqueda."%'";
	}

	$sql = "select
a.idarchivo,
a.asunto,
a.observacion,
cat.categoria,
a.anio,
a.mes,
a.token,
a.imagen,
a.refclientes,
a.fechacreacion,
a.type
from dbarchivos a
inner join dbclientes c on c.idcliente = a.refclientes
inner join tbcategorias cat on cat.idcategoria = a.refcategorias
where a.reftipoarchivos = 2 and refclientes = ".$idcliente;
$res = $this->query($sql,0);
return $res;
}

function traerArchivosPorCliente($idcliente) {
	$sql = "select
a.idarchivo,
cat.categoria,
a.anio,
a.mes,
a.observacion,
a.imagen,
a.refclientes,
a.token,
a.fechacreacion,
a.type
from dbarchivos a
inner join dbclientes c on c.idcliente = a.refclientes
inner join tbcategorias cat on cat.idcategoria = a.refcategorias
where a.reftipoarchivos = 2 and refclientes = ".$idcliente;
$res = $this->query($sql,0);
return $res;
}


/* Fin */
/* PARA Archivos */


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

function insertarClientes($reftipodocumentos,$apellido,$nombre,$nrodocumento,$telefono,$celular,$email,$aceptaterminos,$subscripcion,$activo) {
$sql = "insert into dbclientes(idcliente,reftipodocumentos,apellido,nombre,nrodocumento,telefono,celular,email,aceptaterminos,subscripcion, activo)
values (null,".$reftipodocumentos.",'".($apellido)."','".($nombre)."','".($nrodocumento)."','".($telefono)."','".($celular)."','".($email)."',".$aceptaterminos.",".$subscripcion.",".$activo.")";
$res = $this->query($sql,1);
return $res;
}


function modificarClientes($id,$reftipodocumentos,$apellido,$nombre,$nrodocumento,$telefono,$celular,$email,$aceptaterminos,$subscripcion, $activo,$ciudad,$fechanacimiento,$domicilio,$codigopostal,$municipio,$iban,$nroseguro,$fotofrente,$fotodorsal,$codigoreferencia) {
$sql = "update dbclientes
set reftipodocumentos = ".$reftipodocumentos.",
apellido = '".($apellido)."',nombre = '".($nombre)."',nrodocumento = '".($nrodocumento)."',telefono = '".($telefono)."',celular = '".($celular)."',email = '".($email)."',aceptaterminos = ".$aceptaterminos.",subscripcion = ".$subscripcion.",activo = ".$activo."
,ciudad = '".($ciudad)."'
,fechanacimiento = '".($fechanacimiento)."'
,domicilio = '".($domicilio)."'
,codigopostal = '".($codigopostal)."'
,municipio = '".($municipio)."'
,iban = '".($iban)."'
,fotofrente = '".($fotofrente)."'
,fotodorsal = '".($fotodorsal)."'
,codigoreferencia = '".($codigoreferencia)."'
where idcliente =".$id;
$res = $this->query($sql,0);
return $res;
}


function modificarClientePorCliente($id,$apellido,$nombre,$telefono,$celular) {
   $sql = "update dbclientes
   set
   apellido = '".($apellido)."',nombre = '".($nombre)."',telefono = '".($telefono)."',celular = '".($celular)."'
   where idcliente =".$id;
   $res = $this->query($sql,0);
   return $res;
}

function modificarClienteImagenFrentePorIdCliente($idcliente, $fotofrente, $campo) {
   $sql = "update dbclientes c
            set c.".$campo." = '".$fotofrente."' where c.idcliente = ".$idcliente;
   $res = $this->query($sql,0);
   return $res;
}

function modificarClienteImagenFrentePorId($idusuario, $fotofrente) {
   $sql = "update dbclientes c
            inner join dbusuarios u on c.idcliente = u.refclientes
            set c.fotofrente = '".$fotofrente."' where u.idusuario = ".$idusuario;
   $res = $this->query($sql,0);
   return $res;
}

function modificarClienteImagenDorsalPorId($idusuario, $fotodorsal) {
   $sql = "update dbclientes c
            inner join dbusuarios u on c.idcliente = u.refclientes
            set c.fotodorsal = '".$fotodorsal."' where u.idusuario = ".$idusuario;
   $res = $this->query($sql,0);
   return $res;
}

function traerUsuarioPorIdCliente($idcliente) {
   $sql = "select
               u.idusuario
            from dbusuarios u
            inner join dbclientes c on c.idcliente = u.refclientes
            where c.idcliente = ".$idcliente;

   $res = $this->query($sql,0);
   return $res;
}

function modificarClientePorUsuario($idusuario,$ciudad,$fechanacimiento,$domicilio,$codigopostal,$municipio,$iban,$nroseguro,$codigoreferencia) {
   $sql = "update dbclientes c
   inner join dbusuarios u on c.idcliente = u.refclientes
   set
   ciudad = '".$ciudad."',
   fechanacimiento = '".$fechanacimiento."',
   domicilio = '".$domicilio."',
   codigopostal = '".$codigopostal."',
   municipio = '".$municipio."',
   iban = '".$iban."',
   nroseguro = '".$nroseguro."',
   codigoreferencia = '".$codigoreferencia."'
   where u.idusuario =".$idusuario;
   $res = $this->query($sql,0);
   return $res;
}


function eliminarClientes($id) {
$sql = "update dbclientes set activo = 0 where idcliente =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerClientesajax($length, $start, $busqueda) {

	$where = '';

	$busqueda = str_replace("'","",$busqueda);
	if ($busqueda != '') {
		$where = " and c.apellido like '%".$busqueda."%' or c.nombre like '%".$busqueda."%' or c.nrodocumento like '%".$busqueda."%' or c.telefono like '%".$busqueda."%' or c.celular like '%".$busqueda."%' or c.email like '%".$busqueda."%' or td.tipodocumento like '%".$busqueda."%'";
	}

	$sql = "select
   c.idcliente,
   td.tipodocumento,
   c.apellido,
   c.nombre,
   c.nrodocumento,
   c.telefono,
   c.celular,
   c.email,
   (case when c.aceptaterminos = 1 then 'Si' else 'No' end) as aceptaterminos,
   (case when c.subscripcion = 1 then 'Si' else 'No' end) as subscripcion,
   (case when c.activo = 1 then 'Si' else 'No' end) as activo
   from dbclientes c
   inner join tbtipodocumentos td
   on td.idtipodocumento = c.reftipodocumentos
	where c.idcliente <> 1".$where."
	order by c.apellido, c.nombre
	limit ".$start.",".$length;

	$res = $this->query($sql,0);
	return $res;
}


function traerClientes() {
$sql = "select
c.idcliente,
td.tipodocumento,
c.apellido,
c.nombre,
c.nrodocumento,
c.telefono,
c.celular,
c.email,
c.aceptaterminos,
c.subscripcion,
(case when c.activo = 1 then 'Si' else 'No' end) as activo,
c.ciudad,
c.fechanacimiento,
c.domicilio,
c.codigopostal,
c.municipio,
c.iban,
c.nroseguro,
c.fotofrente,
c.fotodorsal,
c.codigoreferencia
from dbclientes c
inner join tbtipodocumentos td
on td.idtipodocumento = c.reftipodocumentos
where c.idcliente <> 1
order by c.apellido, c.nombre";
$res = $this->query($sql,0);
return $res;
}

function traerClientesActivos() {
$sql = "select
c.idcliente,
td.tipodocumento,
c.apellido,
c.nombre,
c.nrodocumento,
c.telefono,
c.celular,
c.email,
c.aceptaterminos,
c.subscripcion,
(case when c.activo = 1 then 'Si' else 'No' end) as activo
from dbclientes c
inner join tbtipodocumentos td
on td.idtipodocumento = c.reftipodocumentos
where c.activo = 1
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerClientesPorId($id) {
$sql = "select idcliente,reftipodocumentos,apellido,nombre,nrodocumento,telefono,celular,email,aceptaterminos,subscripcion,(case when activo = 1 then 'Si' else 'No' end) as activo, ciudad, fechanacimiento, domicilio, codigopostal,
municipio,iban,nroseguro,fotofrente, fotodorsal, codigoreferencia from dbclientes where idcliente =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerClientesTodos() {
$sql = "select
c.idcliente,
td.tipodocumento,
c.apellido,
c.nombre,
c.nrodocumento,
c.telefono,
c.celular,
c.email,
c.aceptaterminos,
c.subscripcion,
(case when c.activo = 1 then 'Si' else 'No' end) as activo,
c.ciudad,
c.fechanacimiento,
c.domicilio,
c.codigopostal,
c.municipio,
c.iban,
c.nroseguro,
c.fotofrente,
c.fotodorsal,
c.codigoreferencia
from dbclientes c
inner join tbtipodocumentos td
on td.idtipodocumento = c.reftipodocumentos
order by c.apellido, c.nombre";
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* PARA Clientes */



/* PARA Tipodocumentos */

function insertarTipodocumentos($tipodocumento) {
$sql = "insert into tbtipodocumentos(idtipodocumento,tipodocumento)
values (null,'".($tipodocumento)."')";
$res = $this->query($sql,1);
return $res;
}


function modificarTipodocumentos($id,$tipodocumento) {
$sql = "update tbtipodocumentos
set
tipodocumento = '".($tipodocumento)."'
where idtipodocumento =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarTipodocumentos($id) {
$sql = "delete from tbtipodocumentos where idtipodocumento =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerTipodocumentos() {
$sql = "select
t.idtipodocumento,
t.tipodocumento
from tbtipodocumentos t
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerTipodocumentosPorId($id) {
$sql = "select idtipodocumento,tipodocumento from tbtipodocumentos where idtipodocumento =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* /* Fin de la Tabla: tbtipodocumentos*/


/* PARA Images */

function insertarImages($refproyecto,$refuser,$imagen,$type,$principal) {
$sql = "insert into images(idfoto,refproyecto,refuser,imagen,type,principal)
values (null,".$refproyecto.",".$refuser.",'".($imagen)."','".($type)."',".$principal.")";
$res = $this->query($sql,1);
return $res;
}


function modificarImages($id,$refproyecto,$refuser,$imagen,$type,$principal) {
$sql = "update images
set
refproyecto = ".$refproyecto.",refuser = ".$refuser.",imagen = '".($imagen)."',type = '".($type)."',principal = ".$principal."
where idfoto =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarImages($id) {
$sql = "delete from images where idfoto =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerImages() {
$sql = "select
i.idfoto,
i.refproyecto,
i.refuser,
i.imagen,
i.type,
i.principal
from images i
order by 1";
$res = $this->query($sql,0);
return $res;
}


function traerImagesPorId($id) {
$sql = "select idfoto,refproyecto,refuser,imagen,type,principal from images where idfoto =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* /* Fin de la Tabla: images*/


function nuevoBuscador($busqueda) {
$sql = "select
c.idcliente,
c.apellido,
c.nombre,
c.nrodocumento
from dbclientes c
where concat(c.apellido,' ',c.nombre,' ',c.nrodocumento) like '%".$busqueda."%'
order by c.apellido,c.nombre
limit 15";
$res = $this->query($sql,0);
return $res;
}



/* PARA Usuarios */

function insertarUsuarios($usuario,$password,$refroles,$email,$nombrecompleto,$activo,$refclientes) {
$sql = "insert into dbusuarios(idusuario,usuario,password,refroles,email,nombrecompleto,activo,refclientes)
values (null,'".($usuario)."','".($password)."',".$refroles.",'".($email)."','".($nombrecompleto)."',".$activo.",".$refclientes.")";
$res = $this->query($sql,1);
return $res;
}


function modificarUsuarios($id,$usuario,$password,$refroles,$email,$nombrecompleto,$activo,$refclientes) {
$sql = "update dbusuarios
set
usuario = '".($usuario)."',password = '".($password)."',refroles = ".$refroles.",email = '".($email)."',nombrecompleto = '".($nombrecompleto)."',activo = ".$activo." ,refclientes = ".($refclientes)."
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
values (null,'".($url)."','".($icono)."','".($nombre)."',".$Orden.",'".($hover)."','".($permiso)."')";
$res = $this->query($sql,1);
return $res;
}


function modificarPredio_menu($id,$url,$icono,$nombre,$Orden,$hover,$permiso) {
$sql = "update predio_menu
set
url = '".($url)."',icono = '".($icono)."',nombre = '".($nombre)."',Orden = ".$Orden.",hover = '".($hover)."',permiso = '".($permiso)."'
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
values (null,'".($descripcion)."',".$activo.")";
$res = $this->query($sql,1);
return $res;
}


function modificarRoles($id,$descripcion,$activo) {
$sql = "update tbroles
set
descripcion = '".($descripcion)."',activo = ".$activo."
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



/* PARA Categorias */

function insertarCategorias($categoria) {
$sql = "insert into tbcategorias(idcategoria,categoria)
values (null,'".($categoria)."')";
$res = $this->query($sql,1);
return $res;
}


function modificarCategorias($id,$categoria) {
$sql = "update tbcategorias
set
categoria = '".($categoria)."'
where idcategoria =".$id;
$res = $this->query($sql,0);
return $res;
}


function eliminarCategorias($id) {
$sql = "delete from tbcategorias where idcategoria =".$id;
$res = $this->query($sql,0);
return $res;
}


function traerCategoriasajax($length, $start, $busqueda) {

	$where = '';

	$busqueda = str_replace("'","",$busqueda);
	if ($busqueda != '') {
		$where = "where c.categoria like '%".$busqueda."%'";
	}

	$sql = "select
   c.idcategoria,
   c.categoria
   from tbcategorias c
	".$where."
	order by c.categoria
	limit ".$start.",".$length;

	$res = $this->query($sql,0);
	return $res;
}


function traerCategorias() {
$sql = "select
c.idcategoria,
c.categoria
from tbcategorias c
order by c.categoria";
$res = $this->query($sql,0);
return $res;
}


function traerCategoriasPorId($id) {
$sql = "select idcategoria,categoria from tbcategorias where idcategoria =".$id;
$res = $this->query($sql,0);
return $res;
}

/* Fin */
/* PARA Categorias */

/* PARA Configuracion */

function insertarConfiguracion($razonsocial,$empresa,$sistema,$direccion,$telefono,$email) {
$sql = "insert into tbconfiguracion(idconfiguracion,razonsocial,empresa,sistema,direccion,telefono,email)
values (null,'".($razonsocial)."','".($empresa)."','".($sistema)."','".($direccion)."','".($telefono)."','".($email)."')";
$res = $this->query($sql,1);
return $res;
}


function modificarConfiguracion($id,$razonsocial,$empresa,$sistema,$direccion,$telefono,$email) {
$sql = "update tbconfiguracion
set
razonsocial = '".($razonsocial)."',empresa = '".($empresa)."',sistema = '".($sistema)."',direccion = '".($direccion)."',telefono = '".($telefono)."',email = '".($email)."'
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
