<?php


date_default_timezone_set('Europe/Madrid');

class ServiciosReportes {

function GUID()
{
    if (function_exists('com_create_guid') === true)
    {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}


function rptListaTaxaPorApartamento($idlocatario, $desde, $hasta) {
$sql = "select
      	r.hutg,
      	r.datalloguer,
      	r.nif,
      	r.cognom,
      	r.nom,
          r.dias as unitatsestada,
          r.mayores,
          r.menores,
          (case when r.dias <= 7 then r.dias * r.mayores else 7 * r.mayores end) as unitatssubjetes,
          (r.dias * r.menores) as unitatsexempts,
          ((r.dias * r.mayores) - (case when r.dias <= 7 then r.dias * r.mayores else 7 * r.mayores end)) as unitatsnosubjetes,
          r.taxa as total
      from (
      	SELECT
      		u.hutg,
      		l.datalloguer,
      		c.nif,
      		c.cognom,
      		c.nom,
      		sum(per.personas) as mayores,
      		sum(per.menores) as menores,
      		DATEDIFF(l.sortida, l.entrada) AS dias,
            p.taxa,
            p.fechapago
      	FROM
      		dblloguers l
      			INNER JOIN
      		dbclientes c ON l.refclientes = c.idcliente
      			INNER JOIN
      		tbestados est ON est.idestado = l.refestados
      			INNER JOIN
      		dbubicaciones u ON u.idubicacion = l.refubicaciones
      			INNER JOIN
      		tbtipoubicacion tip ON tip.idtipoubicacion = u.reftipoubicacion
      			AND tip.reflocatarios = ".$idlocatario."
      			INNER JOIN
      		dbpagos p ON p.reflloguers = l.idlloguer
      			AND p.taxa > 1
      			INNER JOIN
      		dblloguersadicional per ON per.reflloguers = l.idlloguer
            where '".$desde."' >= p.fechapago and '".$hasta."' <= p.fechapago
      	group by u.hutg,
      		l.datalloguer,
      		c.nif,
      		c.cognom,
      		c.nom,
      		l.sortida,
      		l.entrada,
            p.taxa,
            p.fechapago
          ) r";
$res = $this->query($sql,0);
return $res;
}


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
