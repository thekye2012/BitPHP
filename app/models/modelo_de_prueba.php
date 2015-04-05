<?php

	use \BitPHP\DataBase;

	class Modelo_de_prueba {

		public function __construct() {

			$this->db = DataBase::driver( 'bitphp_testing' );
		}

		public function leer_todo() {

			$stmt = $this->db->query( "SELECT * FROM crud" );
			return $stmt->fetchAll();
		}

		public function leer_primeros_3() {

			$stmt = $this->db->query( "SELECT * FROM crud LIMIT 0,3" );
			return $stmt->fetchAll();
		}

		public function nueva( $title, $msg, $usr ) {

			$this->db->query( "INSERT INTO crud(title,message,user) VALUES ('$title','$msg','$usr')" );
		}
	}
?>