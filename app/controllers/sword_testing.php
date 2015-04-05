<?php

	use \BitPHP\Load;

	Load::module('SwordEngine');

	class Sword_Testing {

		public function __construct() {

			$this->sword = new SwordEngine();
		}

		public function main() {

			$vars = [
				'hola' => 'k ase'
			];

			$this->sword->load('testing/sword/test1')->render()->draw();
		}
	}
?>