<?php
if (!defined('DATABASE_INC_PHP')) {
  define('DATABASE_INC_PHP','INCLUDED'); 
 	
  class WebRequest_DB {  	
			var $link=0;
			var $queries=0;
			
  		function init($host, $user, $pass, $dbname, $port=0) {
  			global $config;
  			$link=0;
  			$queries=0;
  			if (!$port) {
  				$port = 3306;
  			}
  			$this->link = mysql_pconnect($host.":".$port, $user, $pass);
  			if ($this->link === FALSE) {
  				$msg = "Error connecting to MySQL server for site ".$config['base_url']."! Error Number: ".mysql_errno()." -> ".mysql_error();
  				if ($config['Debug']) {
  					print $msg."<br />";
  				}
  				mail($config['admin_email'], "Database Error", MakeErrorReport("MySQL", $msg."\nServer: $host:$port\nUser: $user\nPass: $pass\n"));
    			return false;
  			}  			
  			if (strlen($dbname) && !mysql_select_db($dbname,$this->link)) {
  				$msg = "Error selecting database for site ".$config['base_url']."! Error Number: ".$this->errno()." -> ".$this->error();
  				if ($config['Debug']) {
  					print $msg."<br />";
  				}
  				mail($config['admin_email'], "Database Error", MakeErrorReport("MySQL", $msg));
    			return false;
  			}
  			mysql_set_charset('utf8', $this->link);
  			return true;
  		}
  		
  		function close() {
  			return mysql_close($this->link);
  		}
  		
  		function query($query) {
  			global $config;
  			$this->queries++;
  			$ret = mysql_query($query,$this->link);
  			if ($ret === FALSE) {
  				$msg = "Error executing query: $query => Error Number: ".$this->errno()." -> ".$this->error();
  				if ($config['Debug']) {
  					print $msg."<br />";
  				}
  				mail($config['admin_email'], "Database Error", MakeErrorReport("MySQL", $msg));
  			}
  			return $ret;
  		}
  		
  		function insert($table, $arr) {
				$query = "INSERT INTO `".$table."` (".
				$values = "";
				foreach($arr as $field => $val) {
					$query .= "`".$field."`,";
					$values .= "'".$this->escape($val)."',";
				}
				$query = substr($query,0,strlen($query)-1);
				$values = substr($values,0,strlen($values)-1);
				$query .= ") VALUES (".$values.")";  			
				return $this->query($query);
  		}

  		function update($table, $arr) {
				$query = "UPDATE `".$table."` SET ";
				foreach($arr as $field => $val) {
					if ($field != "ID") {
						$query .= "`".$field."`='".$this->escape($val)."',";
					}
				}
				$query = substr($query,0,strlen($query)-1);
				$query .= " Where `ID`='".$this->escape($arr['ID'])."'";
				return $this->query($query);
  		}
  		 
  		function escape($val) {
  			if ($this->link != 0) {
  				return mysql_real_escape_string($val, $this->link);
  			}
  			return addslashes($val);
  		}
  		
  		function num_rows($res) {
  			return mysql_num_rows($res);
  		}
  		
  		function fetch_assoc($res) { // you should use this over fetch_array in your code, mysql_fetch_assoc() is much faster
  			return mysql_fetch_assoc($res);
  		}
  		
  		function fetch_array($res) {
  			return mysql_fetch_array($res);
  		}
  		
  		function field_type($res, $ind) {
  			return mysql_field_type($res, $ind);
  		}
  		
  		function insert_id() {
  			return mysql_insert_id($this->link);
  		}
  		function error() {
				return mysql_error($this->link);
  		}
  		function errno() {
				return mysql_errno($this->link);
  		}
  		function free_result($res) {
				return mysql_free_result($res);
  		}  		

  		function GetQueriesCount() {
  			return $this->queries;
  		}
  		
  		function GetDBType() { return "mysql"; }

  		function GetModuleInfo() {
  			return array(	"Database Type" => "MySQL",
  										"Client Version" => mysql_get_client_info(),
  										"Server Version" => mysql_get_server_info(),
  										"Connection Type" => mysql_get_host_info(),
  										"Protocol Version" => mysql_get_proto_info(),
  										"Encoding" => mysql_client_encoding());
  		}
  }
  $db = new WebRequest_DB();
}
