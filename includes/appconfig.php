<?php

date_default_timezone_set('Europe/Madrid');

class appconfig {

function conexion() {

	$hostname = "localhost";
	$database = "u235498999_casa";
	$username = "u235498999_casa";
	$password = "rhcp7575";

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
