<?php namespace BitPHP;

  use Exception;
  use \BitPHP\Error;
  use \BitPHP\Config;
  use \BitPHP\Route;

  /**
  *	@author Eduardo B <ms7rbeta@gmail.com>
  */
  class Load
  {

    /**
    *	Includes the specified file if it exists, and if the specified class is inside the file.
    *
    *	@param string $_f Path and file name to be loaded, passed by reference.
    *	@param string $_c Class that should exist inside the file.
    *	@throws FileNotExist if the file does not exist
    *	@throws ClassNotFound the file exist, but does not contain the proper class
    *	@return void
    */
    private static function include_file( &$_f, $_c ) {
      if( file_exists( $_f ) ) {
        require_once( $_f );
        if( class_exists( $_c ) ) {
          return 1;
        } else {
          throw new Exception('La clase <b>'.$_c.'</b> no existe dentro del fichero <b>'.$_f.'</b>.');
        }
      } else {
        throw new Exception('El fichero <b>'.$_f.'</b> no existe');
      }
    }

    /**
     *  Carga los modulos registrados para carga automatica
     *
     *  @return void
     */
    public static function auto() {
      $_n = count( Config::$AUTO_LOAD );

      for ( $_i = 0; $_i < $_n; $_i++ ) {
        self::module( Config::$AUTO_LOAD[ $_i ] );
      }
    }

    /**
    *	Attempts to include the specified module.
    *
    *	@param string $_name Name of the module to be loaded, without extension.
    *	@return void
    */
    public static function module( $_name ) {
      $_file = 'core/modules/'. $_name .'.php';

      if( file_exists( $_file ) ){
          require_once( $_file );
      }	else {
        $_d = 'El modulo <b>'.$_name.'</b> no se pudo cargar';
        $_c = 'El fichero <b>'.$_file.'</b> no existe';
        Error::trace($_d, $_c);
      }
    }

    /**
     *  Ejecuta un controlador/accion ya sea de otra applicacion (HMVC) o simplemente
     *  otro controlador (MVC)
     *
     *  @param string $_app Controlador que se va ejecutar, eg. ..run('app/controller1/acction1') //hmvc
     *                      ..run('controller1/acction1') //mvc
     *  @param bool $echo = true  Indica si debe haber salida de datos por parte del controlador o el resultado
     *                            debe ser retornado para poder usarlo como variable. 
     *                            eg. $var = ..run('app/controller/acction', false);
     *                            eg. ..run('app/controller/acction');
     *  @return mixed
     */
    public static function run( $_app_, $echo = true ) {
      global $_ROUTE;
      $_TEMP_ROUTE = $_ROUTE;

      $_ROUTE = Route::parse_route( $_app_ );
      $_CONTROLLER = $_ROUTE['APP_CONTROLLER'];
      $_ACCTION = $_ROUTE['APP_ACCTION'];

      if( !$echo ) {
        ob_start();
      }

      self::controller( $_CONTROLLER, $_ACCTION );

      $_ROUTE = $_TEMP_ROUTE;

      return $echo ? null : ob_get_clean();
    }

    /**
    *	Try loading the controller indicated, only used by bitphp core.
    *
    *	@param string $_name Name of the controller to be loaded, without extension, passed by reference.
    * @param string $_method Nombre del metodo (accion) del controlador a ejecutar
    *	@return void
    */
    public static function controller(&$_name, &$_method) {
      global $_ROUTE;
      $_file = $_ROUTE['APP_PATH'] .'/controllers/'.$_name.'.php';
      try {
        self::include_file($_file, $_name);
        if( method_exists($_name, $_method) ) {
          $_instance = new $_name();
          $_instance->$_method();
        } else {
          $d = 'Error en controlador <b>'. $_name .'</b>';
          $m = 'No contiene el metodo <b>'. $_method .'()</b>';
          Error::trace($d, $m, False);
        }
      } catch(Exception $_e) {
        $_d = 'El controlador <b>'.$_name.'</b> no se pudo cargar.';
        $_c = $_e->getMessage();
        Error::trace($_d, $_c, False);
      }
    }

    /**
    *	Try loading the model indicated.
    *
    *	@param string $_name Name of the model to be loaded, without extension.
    *	@return object
    */
    public static function model( $_name ) {
      global $_ROUTE;
      $_file = $_ROUTE['APP_PATH'] .'/models/'.$_name.'.php';
      try {
        self::include_file($_file, $_name);
        return new $_name();
      } catch(Exception $_e) {
        $_d = 'El modelo <b>'.$_name.'</b> no se pudo cargar.';
        $_c = $_e->getMessage();
        Error::trace($_d, $_c);
      }
    }

    /**
    *	Attempts to load and display the specified view.
    *
    *	@param mixed $_names Name of the view(s) to be loaded, without extension.
    *	@param array $_params Associative array of parameters (name => value), that may use the view.
    *	@return void
    */
    public static function view($_names, $_params = array()) {

      global $_ROUTE;

      $_names = is_array($_names) ? $_names : [$_names];
      $_i = count($_names);

      extract($_params);
      $_BASE_PATH = $_ROUTE['BASE_PATH'];
      $_PUBLIC_PATH = $_ROUTE['PUBLIC'];
      $_APP_LINK = $_ROUTE['APP_LINK'];

      for($_j = 0; $_j < $_i; $_j++) {
        $_file = $_ROUTE['APP_PATH'] .'/views/'.$_names[$_j].'.php';
        if(file_exists($_file)) {
          require($_file);
        } else {
          $_d = 'La vista <b>'.$_names[$_j].'</b> no se pudo cargar.';
          $_c = 'El fichero <b>'.$_file.'</b> no existe.';
          Error::trace($_d, $_c);
        }
      }
    }

  }
?>
