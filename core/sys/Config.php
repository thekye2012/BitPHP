<?php namespace BitPHP;

  use \BitPHP\Error;

  /**
  * @author Eduardo B <ms7rbeta@gmail.com>
  */
  class Config
  {

    const DEV = True;
    const ENABLE_HMVC = False;
    const MAIN_APP = 'cpanel';
    const MAIN_CONTROLLER = 'home';
    const MAIN_ACTION = 'main';

    public static $AUTO_LOAD = array(
        'Template'
    );

    public static $ON_DEV = array(
        'BASE_PATH' => '/bitphp/'
      , 'PHP_ERRORS' => True
      , 'DB_HOST' => 'localhost'
      , 'DB_USER' => 'root'
      , 'DB_PASS' => 'holamundo'
    );

    public static $ON_PRO = array(
        'BASE_PATH' => '/'
      , 'PHP_ERRORS' => False
      , 'DB_HOST' => 'localhost'
      , 'DB_USER' => 'YOUR_USER'
      , 'DB_PASS' => 'YOUR_PASSWORD'
    );

    public static $DB_ALIESES = array(
        'TESTING' => [ 
              'dev' => 'bitphp_testing'
            , 'pro' => 'testing_pro'
          ]
    );

    /* ATENCION */
    #
    # Si no eres del equipo de desarrollo, por favor no toques nada de aquí para abajo,
    # la civilicacion como la conocemos depende de ello, gracias.

    const CORE_VERSION = 'Beta-3.1.2';

    public static function db_name( $alias ) {

      if( empty( self::$DB_ALIESES[ $alias ] ) ) {
        $m = "No se pudo cargar el nombre de base de datos del alias <b>$alias</b>";
        $e = 'No se a definido el alias';
        Error::trace( $m, $e );
      }

      return self::DEV ? self::$DB_ALIESES[ $alias ]['dev'] : self::$DB_ALIESES[ $alias ]['pro'] ;
    }

    public static function php_errors() { 
      return self::DEV ? self::$ON_DEV['PHP_ERRORS'] : self::$ON_PRO['PHP_ERRORS']; 
    }

    public static function db_host() { 
      return self::DEV ? self::$ON_DEV['DB_HOST'] : self::$ON_PRO['DB_HOST']; 
    }

    public static function db_user() { 
      return self::DEV ? self::$ON_DEV['DB_USER'] : self::$ON_PRO['DB_USER']; 
    }

    public static function db_pass() { 
      return self::DEV ? self::$ON_DEV['DB_PASS'] : self::$ON_PRO['DB_PASS']; 
    }

    public static function base_path() { 
      $path = self::DEV ? self::$ON_DEV['BASE_PATH'] : self::$ON_PRO['BASE_PATH']; 
      $len = strlen( $path ) - 1;
      $path = ( $path[ $len ] == '/' ) ? substr( $path, 0, $len) : $path ;

      return $path;
    }
  } 
?>
