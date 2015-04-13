<?php

  /**	
   * BitPHP - MVC/HMVC for dummies
   *	 @author Eduardo B <ms7rbeta@gmail.com>
   *	 @version beta 3.1.2
   *	 @website http://bitphp.root404.com/ <contacto@root404.com>
   *	 @license GNU/GPLv2
   */
  require('core/sys/Config.php');
  require('core/sys/Error.php');
  require('core/sys/Load.php');
  require('core/sys/DataBase.php');
  require('core/sys/Input.php');
  require('core/sys/Route.php');

  if( !\BitPHP\Config::php_errors() ) {
    error_reporting(0);
    ini_set('display_errors', '0');
  }
  
  \BitPHP\Load::auto();

  $_ROUTE = \BitPHP\Route::parse_route();
  \BitPHP\Load::controller( $_ROUTE['APP_CONTROLLER'], $_ROUTE['APP_ACCTION'] ) ;
?>