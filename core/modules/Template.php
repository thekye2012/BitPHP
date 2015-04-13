<?php

	use \BitPHP\Config;
	use \BitPHP\Error;
  use \BitPHP\Route;

  /**
  * @author Eduardo B <ms7rbeta@gmail.com>
  */
	class Template {

    public function render($_tmplts, $_values = array()) {
      global $_ROUTE;

			$_BASE_PATH = $_ROUTE['BASE_PATH'];
      $_PUBLIC_PATH = $_ROUTE['PUBLIC'];
      $_APP_LINK = $_ROUTE['APP_LINK'];

      $_content = '';
      $_search   = ['<?','{if',':}','{elif','{el}','{/if}','{{','}}','{each','{/each}','{css ',' css}','{js ',' js}'];
      $_replace  = ['<?php','<?php if(','): ?>','<?php elseif(','<?php else: ?>','<?php endif ?>',
        '<?php echo','?>','<?php foreach(','<?php endforeach ?>',
        '<link rel="stylesheet" type="text/css" href="'.$_PUBLIC_PATH.'/css/','.css">',
        '<script src="'.$_PUBLIC_PATH.'/js/','.js"></script>'];

      // Para poder cargar mas de un template de una sola vez
      $_tmplts = is_array($_tmplts) ? $_tmplts : [$_tmplts];
      $_i = count($_tmplts);

      for($_j = 0; $_j < $_i; $_j++) {
        $_read = @file_get_contents( $_ROUTE['APP_PATH'] .'/views/'.$_tmplts[$_j].'.tmpl.php' );

        if($_read === FALSE){
          $_m = 'Error al renderizar <b>'.$_tmplts[$_j].'</b>';
          $_c = 'El fichero <b>../' . $_ROUTE['APP_PATH'] .'/views/'.$_tmplts[$_j].'.tmpl.php</b> no existe!';
          Error::trace($_m, $_c);
          return 0;
        }

        $_content .= $_read;
      }

        extract($_values);

        $_content = str_replace($_search, $_replace, $_content);
        eval('?> '.$_content.'<?php ');
    }
} ?>
