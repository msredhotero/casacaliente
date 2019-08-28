<?php

date_default_timezone_set('Europe/Madrid');

class appconfig {

function conexion() {

	$hostname = "localhost";
	$database = "casacaliente";
	$username = "root";
	$password = "";

/*
		$hostname = "PMYSQL105.dns-servicio.com:3306";
		$database = "6435338_riderz";
		$username = "alexriderz";
		$password = "_alexriderz123*";
		//u235498999_kike usuario
		*/

		$conexion = array("hostname" => $hostname,
						  "database" => $database,
						  "username" => $username,
						  "password" => $password);

		return $conexion;
}

}




?>
