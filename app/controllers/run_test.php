<?php

	use \BitPHP\Load;

	Load::module('SwordEngine');

	class Run_Test {

		public function main() {

			global $_ROUTE;

			$template = new SwordEngine();

			$template->load('testing/sword/test')->render()->draw();
			
			echo '<pre>';

			var_dump( $_ROUTE );

			echo '</pre>';
			
			$template->load('testing/sword/test1')->render()->draw();
		}
	}
?>