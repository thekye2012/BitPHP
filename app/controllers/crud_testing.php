<?php

	use \BitPHP\DataBase;
	use \BitPHP\Load;
	use \BitPHP\Input;

	Load::module('BladeCRUD');

	class Crud_Testing {

		public function __construct() {
			//carga de base de datos por su alias
			$this->db = new BladeCRUD('alias:TESTING');
		}

		public function select() {
			
			$query = $this->db->table('crud')->select('*')->where([
				  'user | message' => "is like '%as%'"
				, 'AND:id' => "is BETWEEN 10 AND 15 | is BETWEEN 20 AND 30"
			])->order('id','down')->limit(5)->execute();

			if( $query->error() ) { 
				echo $query->error;
				exit;
			}

			$params = [
				  'consulta' => $query->string()
				, 'i_resultados' => $query->count()
				, 'resultados' => $query->result()
			];

			Template::render('testing/crud/select', $params);
		}

		public function insert() {

			$acction = Input::get('action');
			if( !$acction ) {
				Template::render('testing/crud/insert');
				return 0;
			}

			$nombre = Input::post('nombre');
			$msg = Input::post('msg');
			
			$query = $this->db->table('crud')->insert([
				  'user' => $nombre
				, 'message' => $msg
			])->execute();

			if( !$query->error ) { 
				echo 'OK';
			} else {
				echo $query->error;
			}
		}

		public function update() {

			$id = Input::url_param('id');
			$msg = Input::url_param('msg');

			$query = $this->db->table('crud')->update([ 'message' => $msg ])->where(['id' => $id])->execute();
			echo !$query->error ? 'OKAS' : $query->error;
		}

		public function delete() {

			$id = Input::url_param('id');

			$query = $this->db->table('crud')->delete()->where([ 'user' => 'lalo || like %aca%' ])->execute();
			echo !$query->error ? 'OKAS' : $query->error;
		}
	}
?>