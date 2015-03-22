<?php

	use \BitPHP\Load;

	class Home {

		public function __construct() {

			Load::module('BasicDbQueries');
			Load::module('Random');
		}

		public function main() {

			$db = new BasicDbQueries('root404:demo');

			$r = $db->select('*','id=5');
			var_dump ( empty( $r ) );
		}
	}
?>