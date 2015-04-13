<?php namespace BitPHP;

  use PDO;
  
  /**
  * @author Eduardo B <ms7rbeta@gmail.com>
  */
  class DataBase
  {
    /**
    *	Returns mysql driver, with default data base conection parameters (if not overwritten)
    *
    *	@param string $dbname data base's name
    *	@param array $p connection parameters
    *	@return object
    *	@todo modified to other controllers database and charset, mysql and utf8 by default
    */
    public static function driver($dbname, $p = Null) {
      $dbname = explode( ':', $dbname);
      $dbname = ( $dbname[0] == 'alias' ) ? Config::db_name( $dbname[1] ) : $dbname[0] ;
      $host = empty($p['host']) ? Config::db_host() : $p['host'];
      $user = empty($p['user']) ? Config::db_user() : $p['user'];
      $pass = empty($p['pass']) ? Config::db_pass() : $p['pass'];

      return new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8',$user,$pass);
    }

    /**
    *	Cleaning a sql query potentially dangerous characters
    *
    *	@staticvar array $_warnings characters that should be treated
    *	@param string $string string to be cleaned
    *	@param boolean $remove Indicates whether to delete characters, or must be replaced by its html notation
    *	@return string
    */
    public static function sanatize($string, $remove = False) {
      $_warnings = ['\'','\\'];
      $_replaces = $remove ? ['',''] : ['&#146;','\\\\'];

      $string = str_replace($_warnings, $_replaces, $string);
      return '\'' . $string . '\'';
    }
  }
?>