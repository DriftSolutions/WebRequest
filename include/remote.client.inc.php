<?php

if (!defined('REMOTE_CLIENT_INC_PHP')) {
	define('REMOTE_CLIENT_INC_PHP','INCLUDED');

	function packlong($val) {
		return pack("V", $val);
	}
		
	class RemoteClient {
		var $ulevel = 0;
		var $uflags = 0;
		var $fp = 0;
		var $error = "";
		
		function GetError() { return $this->error; }
		function SetError($msg) { $this->error = $msg; }
		function GetLevel() { return $this->ulevel; }
		function GetFlags() { return $this->uflags; }
		
		function Connect($user="", $pass="") {
			global $config;
			if ($user == "") { $user = $config['ircbot_user']; }
			if ($pass == "") { $pass = $config['ircbot_pass']; }
			$this->fp = @fsockopen($config['ircbot_host'],$config['ircbot_port'], $errno, $errstr, 10);
			if ($this->fp !== FALSE) {
				stream_set_timeout($this->fp, 5);
				$str = $user."\xFE".$pass."\xFE\x14\xFEWeb Request 2 Script";
				$data = $this->SendCommandRecvReply(0x00, $str, strlen($str));
				if ($data['cmd'] == 1) {//Login OK
					$this->ulevel = unpack("C",substr($data['data'],0,1)); // user level
					$this->ulevel = $this->ulevel[1];
					if ($this->ulevel == 0 && $data['len'] >= 5) {
						$this->uflags = unpack("V",substr($data['data'],1,4)); // user flags
						$this->uflags = $this->uflags[1];
					}
					return true;
				} else {
					$this->error = "Error logging in to the Request Engine!";
		  		if ($config['Debug']) {
		  			print $this->error."<br />";
		  		}
		  		fclose($this->fp);
					return false;
				}
			} else {
	  		$this->error = "Error connecting to Remote Request Engine! => Error Number: ".$errno." -> ".$errstr;
	  		if ($config['Debug']) {
	  			print $this->error."<br />";
	  		}
				return false;
			}				
		}
		
		function SendCommand($code, $data="", $datalen=0) {		
			$len = 8 + $datalen;
			/*
			print "$datalen <br />";
			print "$data <br />";
			print "$code <br />";
			print packlong(0)." <br />";
			print packlong(10)." <br />";
			print packlong($code)." <br />";
			*/			
			$data=packlong($code).packlong($datalen).$data;
			fwrite($this->fp,$data,$len); // put command
		}
		
		function RecvData() {
			$data = fread($this->fp, 8);
			if ($data === FALSE || strlen($data) < 8) {
				$this->error = "Error receiving data from the Remote Request Engine! This is often a sign that your IRCBot host/port is incorrect.";
	  		if ($config['Debug']) {
	  			print $this->error."<br />";
	  		}
				return array('cmd' => 0xFF, 'len' => strlen($this->error), 'data' => $this->error);
			}
			$cmd = unpack("V", substr($data, 0, 4));
			$cmd = $cmd[1];
			$len = unpack("V", substr($data, 4, 4));			
			$len = $len[1];
			$data = "";
			if ($len > 0) {
				if ($len <= 4096) {
					$left = $len;
					while ($left > 0) {
						$tmp = fread($this->fp, $left);							
						if ($tmp === FALSE || strlen($tmp) == 0) {
							break;
						}
						$data .= $tmp;
						$left -= strlen($tmp);
					}
					if (strlen($data) < $len) {
						$this->error = "Error receiving data from the Remote Request Engine! This is often a sign that your IRCBot host/port is incorrect.";
			  		if ($config['Debug']) {
			  			print $this->error."<br />";
			  		}
						return array('cmd' => 0xFF, 'len' => strlen($this->error), 'data' => $this->error);
					}
				} else {
					$this->error = "Received packet too large ($cmd / $len)";
		  		if ($config['Debug']) {
		  			print $this->error."<br />";
		  		}
					return array('cmd' => 0xFF, 'len' => strlen($this->error), 'data' => $this->error);
				}
			}
			return array('cmd' => $cmd, 'len' => $len, 'data' => $data);
		}
		
		function SendCommandRecvReply($code, $data="", $datalen=0) {
			$this->SendCommand($code, $data, $datalen);
			return $this->RecvData();
		}
		
		function Disconnect() {
			//you only have to call Disconnect() if Connect() succeeded
			@fclose($this->fp);
		}
		
	};
	
}// !defined()
?>