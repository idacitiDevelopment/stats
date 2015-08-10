<?php

/*
 * php 5 database utility class
 * Requires: db.class.php, phpmailer.class.php
 * written by Scott Darby info@scottdarby.com
 */


class db_class {

    private $host, $user, $pass, $database, $auto_slashes;
	
	public $db_link, $row_count, $last_error, $last_query;
    
    private static $MYSQL_TYPES_NUMERIC = 'int real '; 
    private static $MYSQL_TYPES_DATE = 'datetime timestamp year date time ';
    private static $MYSQL_TYPES_STRING = 'string blob ';
    
    function __construct() {
    
       $this->host = 'internal-db.s187835.gridserver.com';
        $this->user = 'db187835';
        $this->pass = 'Id@citi1026';
        $this->database = 'db187835_wp_ida';
        $this->auto_slashes = true;
        
        //connect
        $this->db_link = mysqli_connect($this->host, $this->user, $this->pass, $this->database);
        //check for an error establishing a connection
        if (!$this->db_link) { $this->last_error = mysqli_connect_error(); return false; }
//MATT DONE THIS
		else { $this->db_link->set_charset("utf8"); }
        
    }
    
    // Create a function for escaping the data.
    public function escape_data($data) {
    
        //Address Magic Quotes.
        if (ini_get('magic_quotes_gpc')) {
            $data = stripslashes($data);
        }
        
        $data = mysqli_real_escape_string($this->$db, trim($data));
        
        return $data;
        
    }
    
    public function select($sql) {
    
        $this->last_query = $sql;
        
        $result = mysqli_query($this->db_link, $sql);
        
        if (!$result) {
            $this->last_error = mysqli_error($this->db_link);
            return false;
        }
        $this->row_count = mysqli_num_rows($result);
        
        return $result;
    }
    
    public function get_row($result, $type = 'MYSQL_BOTH') {
    
        if (!$result) {
            $this->last_error = "Invalid resource identifier passed to get_row() function.";
            return false;
        }
        
        if ($type == 'MYSQL_ASSOC')
            $row = mysqli_fetch_array($result, MYSQL_ASSOC);
        if ($type == 'MYSQL_NUM')
            $row = mysqli_fetch_array($result, MYSQL_NUM);
        if ($type == 'MYSQL_BOTH')
            $row = mysqli_fetch_array($result, MYSQL_BOTH);
            
        if (!$row)
            return false;
        if ($this->auto_slashes) {
            // strip all slashes out of row...
            foreach ($row as $key=>$value) {
                $row[$key] = stripslashes($value);
            }
        }
        return $row;
        
        $this->close();
        
    }
    
    public function select_one($sql) {

   		$sql .= " LIMIT 1";
		
        $this->last_query = $sql;
        
        $result = mysqli_query($this->db_link, $sql);
		
        if (!$result) {
            $this->last_error = mysqli_error($this->db_link);
            return false;
        }
        
        while ($row = mysqli_fetch_array($result)) {
        		$return = $row[0];
    	}
						
        mysqli_free_result($result);
		
        if ($this->auto_slashes)
            return stripslashes($return);
        else
            return $return;
    }
    
	
	public function update($sql) {

		$this->last_query = $sql;

		$result = mysqli_query($this->db_link, $sql);
		
        if (!$result) {
            $this->last_error = mysqli_error($this->db_link);
            return false;
        }
		
		$rows = mysqli_affected_rows($this->db_link);
		if ($rows == 0) return true;
		else return $rows;
	
	}
	
    public function insert_sql($sql) {
    
        $this->last_query = $sql;
        
        $result = mysqli_query($this->db_link, $sql);
        if (!$result) {
            $this->last_error = mysqli_error($this->db_link);
            return false;
        }
        
        $id = mysqli_insert_id();
        if ($id == 0)
            return true;
        else
            return $id;
            
        $this->close();
    }
    
	public function update_array($table, $data, $id_col_name, $row_id) {
    
        if (empty($data)) {
            $this->last_error = "You must pass an array to the update_array() function.";
            return false;
        }

		if (empty($id_col_name)) {
            $this->last_error = "You must specify the id column you wish to update.";
            return false;
        }
		
		if (empty($row_id)) {
            $this->last_error = "You must pass an id of the row you need to update to the update_array() function.";
            return false;
        }
        
		$values = '';
		
        foreach ($data as $key=>$value) { // iterate values to input
        
            $values .= "$key=";

            if (is_null($value)) {
                $values .= "NULL,";
            } else {
	            if ($this->auto_slashes)
	                $value = addslashes($value);
	            $values .= "'$value',";            	
            }
			
        }
		
		$values = rtrim($values, ',');

        // insert values
        $sql = "UPDATE $table SET $values WHERE $id_col_name = $row_id";
        return $this->insert_sql($sql);
        
    }
	
    public function insert_array($table, $data) {
    
        if ( empty($data)) {
            $this->last_error = "You must pass an array to the insert_array() function.";
            return false;
        }
        
        $cols = '(';
        $values = '(';
        
        foreach ($data as $key=>$value) { // iterate values to input
        
            $cols .= "$key,";
            
            $col_type = 'string';
            
            /*
             * $col_type = $this->get_column_type($table, $key); // get column type
             * if (!$col_type)
             return false; // error!*/
            if (is_null($value)) {
                $values .= "NULL,";
            } elseif (substr_count($MYSQL_TYPES_NUMERIC, "$col_type ")) {
                $values .= "$value,";
            } elseif (substr_count($MYSQL_TYPES_DATE, "$col_type ")) {
                $value = $this->sql_date_format($value, $col_type); // format date
                $values .= "'$value',";
            } elseif (substr_count($MYSQL_TYPES_STRING, "$col_type ")) {
                if ($this->auto_slashes)
                    $value = addslashes($value);
                $values .= "'$value',";
            }
            
            if (is_null($value)) {
                $values .= "NULL,";
            } else {
            
                if ($this->auto_slashes)
                    $value = addslashes($value);
                $values .= "'$value',";
                
            }

            
        }
        $cols = rtrim($cols, ',').')';
        $values = rtrim($values, ',').')';

        
        // insert values
        $sql = "INSERT INTO $table $cols VALUES $values";
        return $this->insert_sql($sql);
        
    }
    
    private function get_column_type($table, $column) {
    
        $result = mysqli_query($this->db_link, "SELECT $column FROM $table");
        
        if (!$result) {
            $this->last_error = mysqli_error($this->db_link);
            return false;
        }
        
        $finfo = mysqli_fetch_field_direct($result, 0);
        
        if ($finfo->type === 253) {
        }
        
        $result->close();
        
        if (!$type) {
        
            $this->last_error = "Unable to get column information on $table.$column.";
            mysqli_free_result($result);
            
            return false;
        }
        
        mysqli_free_result($result);
        return $type;
        
    }
    
    private function close() {
        mysqli_close($this->db_link); // Close the database connection.
    }

    public function print_last_error($show_query = true) {
        
?>
<div style="border: 1px solid red; font-size: 10px; font-family: monospace; color: red; padding: .5em; margin: 8px; background-color: #FFE2E2">
    <h2 style="font-weight: bold">db.class.php Error:</h2>
    <br/>
<?= $this->last_error?>
</div>
<? 
if ($show_query && (! empty($this->last_query))) {
    $this->print_last_query();
}

}

public function print_last_query() {
    

?>
<div style="border: 1px solid blue; font-size: 10px; font-family: monospace; color: blue; padding: .5em; margin: 8px; background-color: #E6E5FF">
    <span style="font-weight: bold">Last SQL Query:</span>
    <br/>
<?= str_replace("\n", '<br />', $this->last_query)?>
</div>
<? 
}


}

?>