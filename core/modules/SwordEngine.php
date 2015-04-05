<?php

	use \BitPHP\Config;
	use \BitPHP\Error;
  	use \BitPHP\Route;

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

			$_PUBLIC_PATH = Route::public_folder_link();

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
				, '<link rel="stylesheet" type="text/css" href="'.$_PUBLIC_PATH.'/css/'
				, '.css">'
				, '<script src="'.$_PUBLIC_PATH.'/js/'
				, '.js"></script>'
			];

			return str_replace( $sword_sintax, $php_sintax, $this->template_source );
		}

		public function load( $templates ) {

			$this->clean();

			global $_APP;

			$templates = is_array($templates) ? $templates : [$templates];
      		$i = count($templates);

      		for($j = 0; $j < $i; $j++) {
	        	$read = @file_get_contents( $_APP .'/views/'.$templates[$j].'.sword.php' );

        		if($read === FALSE){
	          		$m = 'Error al renderizar <b>'.$templates[$j].'</b>';
          			$c = 'El fichero <b>../' . $_APP .'/views/'.$templates[$j].'.sword.php</b> no existe';
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

			if( $this->template_source == '' ) {
				$m = 'No se puede renderizar';
				$e = 'No se a cargado ninguna plantilla (SwordEngine::read)';
				Error::trace( $m, $e );
			}

			$_BASE_PATH = Config::base_path();
      		$_PUBLIC_PATH = Route::public_folder_link();
      		$_APP_LINK = Route::app_link();

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