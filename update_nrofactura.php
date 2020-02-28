<?php

	function query($sql,$accion) {

		$hostname = "localhost";
		$database = "u235498999_casa";
		$username = "u235498999_casa";
		$password = "rhcp7575";

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

	$sql = "SELECT date_format(fechapago, '%y') as anio,idpago
FROM dbpagos
order by fechapago";

	$res = query($sql,0);

	$cadInsert = '';
	$i = 1;
	$anio = 0;
	while ($row = mysql_fetch_array($res)) {
		if ($anio != $row['anio']) {
			$anio = $row['anio'];
			$i = 1;
		}
		$cadInsert .= "update dbpagos set nrofactura = '".$anio.'-'.substr(('0000'.$i),-4)."' where idpago = ".$row['idpago'].";<br>";

		$i += 1;

	}

	echo $cadInsert;


?>
