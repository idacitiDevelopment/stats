<?php
$connect = mysql_connect(db_config::$host, db_config::$username, db_config::$password); //connect to mysql (server, username, password)
if(!$connect)
{
	die("Could not connect: ".mysql_error());
} 
//failure to connect error message
//mysql_query("SET NAMES UTF8"); //this is needed for UTF 8 characters - multilanguage
mysql_query ("set character_set_client='utf8'"); 
mysql_query ("set character_set_results='utf8'"); 
mysql_query ("set collation_connection='utf8_general_ci'"); 	
mysql_select_db(db_config::$database, $connect); //connect to database

ini_set("error_reporting", E_WARNING);
ini_set("display_errors", 1);
ini_set("session.gc_probability", 1); //probability garbage collection runs
ini_set("session.gc_divisor", 20); //probability garbage collection runs
ini_set("session.gc_maxlifetime", 3600); //max session lifetime
ini_set("session.save_path", "sessions"); //alt session save path

class DbConnector {

var $theQuery;
var $link;

function DbConnector(){

        // Get the main settings from the array we just loaded
        $host = db_config::$host;
        $db = db_config::$database;
        $user = db_config::$username;
        $pass = db_config::$password;

        // Connect to the database
        $this->link = mysql_connect($host, $user, $pass);
        mysql_select_db($db);
        register_shutdown_function(array(&$this, 'close'));

    }
	
  //*** Function: query, Purpose: Execute a database query ***
    function query($query) {

        $this->theQuery = $query;
        return mysql_query($query, $this->link);

    }

    //*** Function: fetchArray, Purpose: Get array of query results ***
    function fetchArray($result) {

        return mysql_fetch_array($result);

    }

    //*** Function: close, Purpose: Close the connection ***
    function close() {
        mysql_close($this->link);

    }
	
}

?>