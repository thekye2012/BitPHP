<?php

	use \BitPHP\Config;
	use \BitPHP\Error;

	/**
  	*	@author Eduardo B <ms7rbeta@gmail.com>
  	*/
	class SwordEngine {

		public $template_source = '';
		public $template_vars = array();
		public $result = null;

		private function clean() {
			$this->template_source = '';
			$this->template_vars = array();
			$this->result = null;
		}

		private function compile() {

			global $_ROUTE;

			$sword_sintax = [
				  '<?'
				, '{if'
				, ':}'
				, '{elif'
				, '{else}'
				, '{/if}'
				, '{{'
				, '}}'
				, '{each'
				, '{/each}'
				, '{css '
				, ' css}'
				, '{js '
				, ' js}'
			];

			$php_sintax = [
				  '<?php'
				, '<?php if('
				, '): ?>'
				, '<?php elseif('
				, '<?php else: ?>'
				, '<?php endif ?>'
				, '<?php echo'
				, '?>'
				, '<?php foreach('
				, '<?php endforeach ?>'
				, '<link rel="stylesheet" type="text/css" href="'.$_ROUTE['PUBLIC'].'/css/'
				, '.css">'
				, '<script src="'.$_ROUTE['PUBLIC'].'/js/'
				, '.js"></script>'
			];

			return str_replace( $sword_sintax, $php_sintax, $this->template_source );
		}

		public function load( $templates ) {

			$this->clean();

			global $_ROUTE;

			$templates = is_array($templates) ? $templates : [$templates];
      		$i = count($templates);

      		for($j = 0; $j < $i; $j++) {
	        	$read = @file_get_contents( $_ROUTE['APP_PATH'] .'/views/'.$templates[$j].'.tmpl.php' );

        		if($read === FALSE){
	          		$m = 'Error al renderizar <b>'.$templates[$j].'</b>';
          			$c = 'El fichero <b>../' . $_ROUTE['APP_PATH'] .'/views/'.$templates[$j].'.tmpl.php</b> no existe';
          			Error::trace($m, $c);
		    	}

        		$this->template_source .= $read;
      		}

      		return $this;
		}

		public function vars( $vars ) {
			
			$this->template_vars = $vars;
			return $this;
		}

		public function render() {

			global $_ROUTE;

			if( $this->template_source == '' ) {
				$m = 'No se puede renderizar';
				$e = 'No se a cargado ninguna plantilla (SwordEngine::read)';
				Error::trace( $m, $e );
			}

			$_BASE_PATH = $_ROUTE['BASE_PATH'];
      		$_PUBLIC_PATH = $_ROUTE['PUBLIC'];
      		$_APP_LINK = $_ROUTE['APP_LINK'];

			extract( $this->template_vars );
			$compiled_source = $this->compile();

			ob_start();
			eval('?> ' . $compiled_source . '<?php ' );
			$this->result = ob_get_clean();

			return $this;
		}

		public function read() {

			if( !$this->result ) {
				$m = 'No se puede leer la plantilla';
				$e = 'Aun no se ha renderizado (SwordEngine::render)';
				Error::trace( $m, $e );
			}

			return $this->result;
		}

		public function draw() {

			if( !$this->result ) {
				$m = 'No se puede imprimir la plantilla';
				$e = 'Aun no se ha renderizado (SwordEngine::render)';
				Error::trace( $m, $e );
			}

			echo $this->result;
		}
	}
?>