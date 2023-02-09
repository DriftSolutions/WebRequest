<?php
if (!defined('DATABASE_INC_PHP')) {
  define('DATABASE_INC_PHP','INCLUDED');

  if (!function_exists('mysqli_connect_error')) {
  	die("ERROR: Need mysqli extension installed!");
  }

	if (version_compare(PHP_VERSION, '8.1.0') >= 0) {
		mysqli_report(MYSQLI_REPORT_OFF);
	}

  class WebRequest_DB {
			var $link = FALSE;
			var $queries = 0;

  		function init($host, $user, $pass, $dbname, $port=0) {
  			global $config;

  			$this->queries = 0;
  			if ($port <= 0 || $port > 65535) {
  				$port = 3306;
  			}
  			// Switch these next 2 lines out to enable persistent connections
				//$this->link = new mysqli("p:".$host, $user, $pass, '', $port);
				$this->link = new mysqli($host, $user, $pass, '', $port);
				$tries = 0;
				while (mysqli_connect_error() == 1040 && $tries++ < 2) {
					$tries++;
					sleep(mt_rand(1,2));
	  			$this->link = new mysqli($host, $user, $pass, '', $port);
				}
				if (mysqli_connect_error()) {
  				$msg = "Error connecting to MySQL server for site ".$config['site_name']."! Error Number: ".mysqli_connect_errno()." -> ".mysqli_connect_error();
  				if ($config['Debug']) {
  					print $msg."<br />";
  				}
					if (mysqli_connect_errno() != 1040) {
						mail($config['admin_email'], "Database Error", MakeErrorReport("MySQL", $msg."\nServer: $host:$port\nUser: $user\nPass: $pass\n"));
					}
					$this->link = FALSE;
					return false;
  			}
				if (!empty($dbname) && !mysqli_select_db($this->link, $dbname)) {
					$msg = "Error selecting database for site ".$config['site_name']."! Error Number: ".$this->errno()." -> ".$this->error();
  				if ($config['Debug']) {
  					print $msg."<br />";
  				}
					mail($config['admin_email'], "Database Error", MakeErrorReport("MySQL", $msg."\nServer: $host:$port\nUser: $user\nPass: $pass\n"));
					return false;
				}
  			mysqli_set_charset($this->link, 'utf8');
  			return true;
  		}

  		function close() {
  			mysqli_close($this->link);
  			$this->link = FALSE;
  			return true;
  		}

  		function query($query) {
  			global $config;
  			$this->queries++;
  			$ret = mysqli_query($this->link, $query);
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
  			if ($this->link !== FALSE) {
  				return mysqli_real_escape_string($this->link, $val);
  			}
  			return addslashes($val);
  		}

  		function num_rows($res) {
  			return mysqli_num_rows($res);
  		}

  		function fetch_assoc($res) { // you should use this over fetch_array in your code, mysqli_fetch_assoc() is much faster
  			return mysqli_fetch_assoc($res);
  		}

  		function fetch_array($res) {
  			return mysqli_fetch_array($res);
  		}

  		function field_type($res, $ind) {
  			return mysqli_field_type($res, $ind);
  		}

  		function insert_id() {
  			return mysqli_insert_id($this->link);
  		}
  		function error() {
				return mysqli_error($this->link);
  		}
  		function errno() {
				return mysqli_errno($this->link);
  		}
  		function free_result($res) {
				return mysqli_free_result($res);
  		}

  		function GetQueriesCount() {
  			return $this->queries;
  		}

  		function GetDBType() { return "mysqli"; }

  		function GetModuleInfo() {
  			return array(
  				"Database Type" => "MySQLi",
					"Client Version" => mysqli_get_client_info($this->link),
					"Server Version" => mysqli_get_server_info($this->link),
					"Connection Type" => mysqli_get_host_info($this->link),
					"Protocol Version" => mysqli_get_proto_info($this->link),
					"Encoding" => mysqli_character_set_name($this->link),
				);
  		}
  }
  $db = new WebRequest_DB();
}
