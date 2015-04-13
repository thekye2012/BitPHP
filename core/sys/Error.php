<?php namespace BitPHP;

  use Exception;
  use \BitPHP\Load;
  use \BitPHP\Config;

 /**
  * @author Eduardo B <ms7rbeta@gmail.com>
  */
  class Error
  {

  /**
    *	Trace errors and terminate the app, normally only used by bitphp's core
    *
    *	@param string $d error's description, passed by reference
    *	@param string $e error's exception message, passed by reference
    *	@param boolean $trace indicates whether the error should be traced
    *	@return void
    */
    public static function trace(&$d, &$e, $print_trace = true) {
      $ex = new Exception();
      $trace = $ex->getTrace();

      $log = [
          "TimeStamp" => date('l jS \of F Y h:i:s A')
        , "Description" => $d
        , "Exception" => $e
        , "Trace" => $trace
        , "PrintTrace" => $print_trace
      ];

      if( Config::php_errors() ){
          require('core/views/error.php');
      } else {
        self::not_found();
      }

      self::log( json_encode( $log, JSON_PRETTY_PRINT ) . '%/error/%' );
      exit;
    }

    public static function log( $log ) {
      error_log( $log, 3, 'core/log/errors.log' );
    }

    public static function not_found() {
      global $_ROUTE;
      $_file = $_ROUTE['APP_PATH'] .'/views/errors/404.php';

      http_response_code(404);
      
      if( file_exists( $_file ) ) {
        require( $_file );
      } else {
        echo '404 - Not Found';
      }

      exit;
    }

    public function forbidden() {
      global $_ROUTE;
      $_file = $_ROUTE['APP_PATH'] .'/views/errors/403.php';

      http_response_code(403);
      
      if( file_exists( $_file ) ) {
        require( $_file );
      } else {
        echo '403 - Forbidden';
      }

      exit;
    }
  }
?>