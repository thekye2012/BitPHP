<?php 

	use \BitPHP\Database;

	class BasicDbQueries extends Database {

		public $db;
		public $table;

		public function __construct( $db_tbl ) {
			$db_tbl = explode(':', $db_tbl);
			$this->db = self::driver( $db_tbl[0] );
			$this->table = $db_tbl[1];
		}

	    public function insert( $data ) {
      		$values = array();
      		$keys = array();

      		$i = 0;

      		foreach ($data as $key => $value) {
        		$keys[$i] = $key;
        		$values[$i] = self::sanatize($value);
        		$i++;
      		}

	      	$keys = implode(',', $keys);
      		$values = implode(',', $values);
	      
      		$this->db->query("INSERT INTO  $this->table($keys) VALUES($values)");
      		$db = null;
    	}

    	public function select( $data, $where = 1 ) {

      		$stmt = $this->db->query("SELECT $data FROM $this->table WHERE $where");
      		$result = $stmt->fetchAll( PDO::FETCH_ASSOC );
      		$db = null;
      		return $result;
    	}

    	public function delete( $where = 1 ) {

      		$stmt = $this->db->query("DELETE FROM $this->table WHERE $where");
      		$db = null;
    	}

    	public function update( $data, $where = 1) {
      		$values = array();

		    $i = 0;

      		foreach ($data as $key => $value) {
        		$values[$i] = $key . '=' . self::sanatize($value);
        		$i++;
      		}

      		$values = implode(',', $values);
      
      		$this->db->query("UPDATE $this->table SET $values WHERE $where");
    	  	$db = null;
	    }
	}
?>