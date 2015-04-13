<?php namespace BitPHP;
	
	use \BitPHP\Config;

	class Route {

		private static function validate_app( $app ) {
			if( !is_dir( $app ) ) {
				$d = 'Oops! hubo un problema al ejecutar la aplicacion.';
				$e = 'La aplicacion <b>/'. $app .'/</b> no existe.';
				Error::trace($d, $e, false);
			}

			return $app;
		}

		public static function parse_route( $route = null ) {

			//BASE_PATH
			$_ROUTE['BASE_PATH'] = Config::base_path();

			//String and URL array
			if ( !$route ) {
				$_ROUTE['STRING'] = empty($_GET['_route']) ? null : $_GET['_route'];
				$_ROUTE['URL'] =  empty($_GET['_route']) ? array() : explode('/', $_GET['_route']);
			} else {
				$_ROUTE['STRING'] = $route;
				$_ROUTE['URL'] = explode('/', $route);
			}

			//APP_PATH
			$_ROUTE['APP_PATH'] = 'app';

			if( Config::ENABLE_HMVC ) {
				$_ROUTE['APP_PATH'] .= '/' . ( empty( $_ROUTE['URL'][0] ) ? Config::MAIN_APP : $_ROUTE['URL'][0] );
			}

			self::validate_app( $_ROUTE['APP_PATH'] );

			//APP CONTROLLER
			$i = ( Config::ENABLE_HMVC ) ? 1 : 0 ;
			$_ROUTE['APP_CONTROLLER'] = empty( $_ROUTE['URL'][$i] ) ? Config::MAIN_CONTROLLER : $_ROUTE['URL'][$i];

			//APP ACCTION
			$i = ( Config::ENABLE_HMVC ) ? 2 : 1 ;
			$_ROUTE['APP_ACCTION'] = empty( $_ROUTE['URL'][$i] ) ? Config::MAIN_ACTION : $_ROUTE['URL'][$i];

			//SERVER NAME
			$_ROUTE['SERVER_NAME'] = ( empty( $_SERVER['HTTPS'] ) ? 'http://' : 'https://' ) . $_SERVER['SERVER_NAME'];

			//APP_LINK
			$_ROUTE['APP_LINK'] = $_ROUTE['SERVER_NAME'] . $_ROUTE['BASE_PATH'];

			if( Config::ENABLE_HMVC ) {
				$_ROUTE['APP_LINK'] .= ( empty($_ROUTE['URL'][0]) ? Config::MAIN_APP : $_ROUTE['URL'][0] );
			}

			//PUBLIC_PATH_LINK
			$_ROUTE['PUBLIC'] = $_ROUTE['SERVER_NAME'] . $_ROUTE['BASE_PATH'] . '/public';

			return $_ROUTE;
		}
	}
?>