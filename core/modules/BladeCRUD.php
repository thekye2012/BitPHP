<?php

	use \BitPHP\DataBase;
	use \BitPHP\Error;

	/**
  	*	@author Eduardo B <ms7rbeta@gmail.com>
  	*/
	class BladeCRUD extends DataBase {

		private $db;
		private $table_name = null;
		private $initialized_query = null;
		private $executed_query = null;
		public $result = null;
		public $query = null;
		public $stmt = null;
		public $error = null;

		public function __construct( $dbname ) {

			$this->db = self::driver( $dbname );
		}

		public function table( $table_name ) {
			$this->table_name = $table_name;
			return $this;
		}

		/* Create */
		public function insert( $q ) {

			if( !$this->table_name ) { 

				$m = 'Error al ejecutar sentencia BladeCRUD::insert';
				$e = 'BladeCRUD::insert no puede ser usada antes de inicializar la tabla (BladeCRUD::table)';
				Error::trace( $m, $e );
			}

			$this->initialized_query = 'insert';

			$keys = array();
			$values = array();
			$i = 0;

			foreach ($q as $key => $value) {
				$keys[$i] = $key;
				$values[$i] = self::sanatize( $value );
				$i++;
			}

			$this->string =  'INSERT INTO ' . $this->table_name . '(' . implode( ',', $keys );
			$this->string .= ') VALUES (' . implode( ',', $values ) . ')';
			return $this;
		}

		/* Read */
		public function select( $q ) {

			if( !$this->table_name ) { 

				$m = 'Error al ejecutar sentencia SELECT';
				$e = 'BladeCRUD::select no puede ser usada antes de inicializar la tabla (BladeCRUD::table)';
				Error::trace( $m, $e );
			}

			$this->initialized_query = 'select';
			$this->string = "SELECT $q FROM $this->table_name";
			return $this;
		}

		/* Update */
		public function update( $q ) {

			if( !$this->table_name ) { 

				$m = 'Error al ejecutar sentencia BladeCRUD::update';
				$e = 'BladeCRUD::update no puede ser usada antes de inicializar la tabla (BladeCRUD::table)';
				Error::trace( $m, $e );
			}

			$this->initialized_query = 'update';

			$values = array();
			$i = 0;

			foreach ($q as $key => $value) {
				$values[$i] = $key . '=' . self::sanatize( $value );
				$i++;
			}

			$this->string = "UPDATE $this->table_name SET " . implode( ',', $values);
			return $this;
		}

		/* Delete */
		public function delete() {

			if( !$this->table_name ) { 

				$m = 'Error al ejecutar sentencia BladeCRUD::delete';
				$e = 'BladeCRUD::delete no puede ser usada antes de inicializar la tabla (BladeCRUD::table)';
				Error::trace( $m, $e );
			}

			$this->initialized_query = 'delete';

			$this->string = "DELETE FROM $this->table_name";
			return $this;
		}

		public function where( $q ) {

			if( !$this->string  ) {
				$m = 'Error al ejecutar sentencia WHERE';
				$e = 'Ninguna consulta a sido inicializada (BladeCRUD::select, BladeCRUD::update, BladeCRUD::delete)';
				Error::trace( $m, $e );
			}

			$this->string .= ' WHERE ';

			foreach ($q as $vars => $values) {

				$vars = explode(':', $vars);
				if ( count($vars) > 1 ) {
					$this->string .= " $vars[0] ";
					$vars = $vars[1];
				} else {
					$vars = $vars[0];
				}

				$vars_array = preg_split('/[\|\&]/', $vars);
   				$vars_len = count( $vars_array );
   				$vars = str_replace( ['|','&'], [' OR ',' AND '], $vars );

   				$var_values = array();

   				for( $i = 0; $i< $vars_len; $i++ ) {
	      			$var = trim( $vars_array[$i] );
      				$var_values[$i] = '( ' . str_replace( ['is','|','&'], [$var,'OR', 'AND'], $values) . ' )';
   				}

   				$vars = str_replace( $vars_array, $var_values, $vars);
   				$this->string .= "( $vars )";
			}

			return $this;
		}

		public function limit( $l, $r = null ) {

			if( $this->initialized_query != 'select' ) {
				$m = 'Error al ejecutar sentencia BladeCRUD::limit';
				$e = 'No se a inicializado ninguna consulta BladeCRUD::select';
				Error::trace( $m, $e );
			}

			$l_value = $r ? $l : 0;
			$r_value = $r ? $r : $l;

			$this->string .= " LIMIT $l_value,$r_value";
			return $this;
		}

		public function order( $key, $mode ) {

			if( $this->initialized_query != 'select' ) {
				$m = 'Error al ejecutar sentencia BladeCRUD::order';
				$e = 'No se a inicializado ninguna consulta BladeCRUD::select';
				Error::trace( $m, $e );
			}

			$this->string .= " ORDER BY $key " . ( $mode == 'up' ? 'ASC' : 'DESC' );
			return $this;
		}

		public function count() {

			if( $this->executed_query != 'select' ) {
				$m = 'Error al contar los resultados de la consulta';
				$e = 'Ninguna sentencia BladeCRUD::select ha sido ejecutada (BladeCRUD::execute)';
				Error::trace( $m, $e );
			}

			return $this->error ? false : $this->stmt->rowCount();
		}

		public function first() {

			if( $this->executed_query != 'select' ) {
				$m = 'Error al extraer el primer resultado de la consulta';
				$e = 'Ninguna sentencia BladeCRUD::select ha sido ejecutada (BladeCRUD::execute)';
				Error::trace( $m, $e );
			}

			return $this->error ? false : $this->result()[0];
		}

		public function last() {

			if( $this->executed_query != 'select' ) {
				$m = 'Error al extraer el ultimo resultado de la consulta';
				$e = 'Ninguna sentencia BladeCRUD::select ha sido ejecutada (BladeCRUD::execute)';
				Error::trace( $m, $e );
			}

			return $this->error ? false : $this->result()[$this->stmt->rowCount() - 1];
		}

		public function result() {

			if( $this->result === null ) {
				$m = 'Error al mostrar los resultados de la consulta';
				$e = 'Ninguna sentencia ha sido ejecutada (BladeCRUD::execute)';
				Error::trace( $m, $e );
			}

			return $this->result;
		}

		public function string() {
			return $this->string;
		}


		public function error() {
			return $this->error;
		}

		public function execute() {
			$this->stmt = $this->db->query( $this->string );
			$this->executed_query = $this->initialized_query;

			$this->error = $this->db->errorInfo()[2];

			if( $this->initialized_query == 'select' && !$this->error ) {
				$this->result = $this->stmt->fetchAll();
			} else if ( !$this->error ) {
				$this->result = true;
			} else {
				$this->result = false;
			}

			return $this;
		}
	}
?>