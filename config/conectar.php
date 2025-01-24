<?php
	function connect(){
		if (!($connection = mysqli_connect("localhost", "admin", "admin"))) {
			echo "Error conectando a la base de datos";
			exit();
		}
		if (!mysqli_select_db($connection, "kdoce_classtouch")) {
			echo "Error seleccionando la base de datos";
			exit();
		}
		mysqli_set_charset($connection, "utf8");
		return $connection;
	}
?>