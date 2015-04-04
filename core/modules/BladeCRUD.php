<?php

	use \BitPHP\DataBase;
	use \BitPHP\Error;

	class BladeCRUD extends DataBase {

		private $db;
		private $table_name = null;
		private $initialized_query = null;
		private $executed_query = null;
		public $query = null;
		public $stmt = null;
		public $error;

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

			$values = array();
			$i = 0;

			foreach ($q as $key => $value) {
				$key = str_replace(['&&','||'], ['AND', 'OR'], $key);

				$or_in_value = explode('||', $value);
				$or_in_value_i = count( $or_in_value );

				if( $or_in_value_i == 1 ) {

					$value = explode( ' ', $value);
					$value_len = count( $value );
					$operator = $value_len == 1 ? '=' : $value[0];
					$value = $value_len == 1 ? $value[0] : $value[1];

					$values[$i] = $key . " $operator " . self::sanatize( $value );
				} else {
					
					$or_values = array();

					for( $j = 0; $j < $or_in_value_i; $j++ ) {

						$value = explode( ' ', trim( $or_in_value[$j] ));
						$value_len = count( $value );
						$operator = $value_len == 1 ? '=' : $value[0];
						$value = $value_len == 1 ? $value[0] : $value[1];

						$or_values[$j] = $key . " $operator " . self::sanatize( $value );
					}

					$values[$i] = '(' . implode( ' OR ', $or_values ) . ')';
				}

				$i++;
			}

			$this->string .= " WHERE " . implode( ' ', $values);
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

		public function execute() {
			$this->stmt = $this->db->query( $this->string );
			$this->executed_query = $this->initialized_query;

			$this->error = $this->db->errorInfo()[2];

			if( $this->initialized_query == 'select' && !$this->error ) {
				$this->result = $this->stmt->fetchAll();
			} 

			return $this;
		}
	}
?>