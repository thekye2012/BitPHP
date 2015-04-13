<?php namespace BitPHP;
  
  /**
  * @author Eduardo B <ms7rbeta@gmail.com>
  * @since bitphp 2.0
  */
  class Input {

    /**
    *	<p>ONLY WITH BITPHP, gets the value of the specified index in url params, and filters
    *	html chars, return null if index isn't set.</p><p>Url parameters are received here more
    *	easily, however, you can do as in previous versions, for compatibility with applications
    *	developed in previous versions.</p>
    *
    *	@global string $_URLPARAMS this variable contains url parameters
    *	@param string $i index of $_params to search
    *	@param boolean $html_filter optional param, indicates whether to filter html chars, true by default
    *	@return string
    */
    public static function url_param($i, $html_filter = true)
    {
      global $_ROUTE;

      if( is_numeric($i) ) { 
        $i += ( Config::ENABLE_HMVC ) ? 3 : 2 ;
        $s = !empty($_ROUTE['URL'][$i]) ? $_ROUTE['URL'][$i] : null;
      } else {
        $i = array_search($i, $_ROUTE['URL']);
        $s = ($i !== false) ? $_ROUTE['URL'][$i + 1] : null ;
      }
    
      return $html_filter ? htmlentities($s, ENT_QUOTES) : $s;
    }

    /**
    *	Gets the value of the specified key in $_POST, and filters
    *	html chars, return null if key isn't set.
    *
    *	@param string $k index of $_POST to search
    *	@param boolean $html_filter optional param, indicates whether to filter html chars, true by default
    *	@return string
    */
    public static function post($k, $html_filter = true)
    {
      $s = !empty($_POST[$k]) ? $_POST[$k] : null;
      return $html_filter ? htmlentities($s, ENT_QUOTES) : $s;
    }

    /**
    *	Gets the value of the specified key in $_GET, and filters
    *	html chars, return null if key isn't set.
    *
    *	@param string $k index of $_GET to search
    *	@param boolean $html_filter optional param, indicates whether to filter html chars, true by default
    *	@return string
    */
    public static function get($k, $html_filter = true)
    {
      $s = !empty($_GET[$k]) ? $_GET[$k] : null;
      return $html_filter ? htmlentities($s, ENT_QUOTES) : $s;
    }

    /**
    *	Gets the value of the specified key in $_COOKIE, and filters
    *	html chars, return null if key isn't set.
    *
    *	@param string $k index of $_COOKIE to search
    *	@param boolean $html_filter optional param, indicates whether to filter html chars, true by default
    *	@return string
    */
    public static function cookie($k, $html_filter = true)
    {
      $s = !empty($_COOKIE[$k]) ? $_COOKIE[$k] : null;
      return $html_filter ? htmlentities($s, ENT_QUOTES) : $s;
    }
  }
?>