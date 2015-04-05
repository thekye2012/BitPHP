<?php

	use \BitPHP\Load;

	Load::module('SwordEngine');

	class Home {

		public function __construct() {

			$this->skin = new SwordEngine();
		}

		public function main() {

			$this->skin->load('header')->render()->draw();
			
			Load::run('other_hmvc_test/saludo/estilo1');

			$this->skin->load('footer')->render()->draw();
		}
	}
?>