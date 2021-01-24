<?php
if (!defined('FUNCTIONS_INC_PHP')) {
	define('FUNCTIONS_INC_PHP','INCLUDED');
	define('_WEBREQUEST_VERSION','2.0.18');
	define('REQ_TABLE', $config['db_tabprefix']."Requests");
	define('USERS_TABLE', $config['db_tabprefix']."Users");
	define('CONFIG_TABLE', $config['db_tabprefix']."Config");
	define('DNSBL_TABLE', $config['db_tabprefix']."DNSBL");
	define('DJ_TABLE', $config['db_tabprefix']."DJ");
	define('SMARTY_DIR', $config['base_path'].'include/smarty3/');
	
	require('smarty3/Smarty.class.php');
	
	function LoadDynamicConfig($uid=0) {
		global $db,$config;
		$query = "SELECT * FROM `".CONFIG_TABLE."` WHERE UserID=".$uid;
		$res = $db->query($query);
		while ($arr = $db->fetch_assoc($res)) {
			$config[$arr['Name']] = $arr['Value'];
		}
	}
	
	function SaveConfigValue($name, $val) {
		global $db,$config;
		$query = "INSERT INTO `".CONFIG_TABLE."` (`Name`,`Value`) VALUES ('".$db->escape($name)."', '".$db->escape($val)."') ON DUPLICATE KEY UPDATE `Value`=\"".$db->escape($val)."\"";
		$res = $db->query($query);
		$config[$name] = $val;
	}

	function SaveUserConfigValue($name, $val) {
		global $db,$config,$userinfo;
		$query = "INSERT INTO `".CONFIG_TABLE."` (`Name`,`Value`,`UserID`) VALUES ('".$db->escape($name)."', '".$db->escape($val)."', ".$userinfo['ID'].") ON DUPLICATE KEY UPDATE `Value`=\"".$db->escape($val)."\"";
		$res = $db->query($query);
		$config[$name] = $val;
	}
	
	function iif($blah,$ret1,$ret2="") {
		if ($blah) { return $ret1; }
		return $ret2;
	}
	
	function MakeErrorReport($cat,$str) {
		global $userinfo;
		
		$ret = "WebRequest Error Report\n\n";
		$ret .= "Error category: $cat\n";
		$ret .= "Error Description: $str\n\n";
		
		$ret .= "Globals Dump\n\n";
		foreach($_SERVER as $key => $val) {
			$ret .= "\$_SERVER[$key] => $val\n";
		}
		foreach($_GET as $key => $val) {
			$ret .= "\$_GET[$key] => $val\n";
		}
		foreach($_POST as $key => $val) {
			$ret .= "\$_POST[$key] => $val\n";
		}
		$ret .= "\n";
		
	  $ret .= "End of report...\n";	
	  return $ret;
	};
	
	function duration($secs) {
		$vHour = floor($secs / 3600);
		if ($vHour) {
			$secs -= ($vHour * 3600);
		}
		$vMin = floor($secs / 60);
		if ($vMin) {
			$secs -= ($vMin * 60);
		}		
		if ($vHour) {
			return sprintf("%d:%02d:%02d", $vHour, $vMin, $secs);
		} else {
			return sprintf("%d:%02d", $vMin, $secs);
		}
	}
	
	function duration2($secs) {
		$vDay = floor($secs / 86400);
		if ($vDay) {
			$secs -= ($vDay * 86400);
		}
		$vHour = floor($secs / 3600);
		if ($vHour) {
			$secs -= ($vHour * 3600);
		}
		$vMin = floor($secs / 60);
		if ($vMin) {
			$secs -= ($vMin * 60);
		}		
	
		$sout = "";
		if ($vDay > 0) { $sout = $vDay."d "; }
		if ($vHour > 0) { $sout = $sout.$vHour."h "; }
		if ($vMin > 0) { $sout = $sout.$vMin."m "; }
		if ($secs > 0) { $sout = $sout.$secs."s "; }
	
		return trim($sout);
	}	
	
	function RequestSong($song) {
		global $config;
		$rc = new RemoteClient();
		if ($rc->Connect()) {
			$arr = $rc->SendCommandRecvReply(0x14, $song, strlen($song));
			if (count($arr)) {
				if (stristr($arr['data'], "will play in") !== FALSE) {
					$query = "INSERT INTO ".REQ_TABLE." (IP,Expire) VALUES (\"".$_SERVER['REMOTE_ADDR']."\", ".(time()+$config['mintimeperrequest']).")";
					$result = $db->query($query);
				}
				$rc->Disconnect();
				return $arr['data'];
			} else {
				$rc->SetError("Error receiving reply from IRCBot");
			}
			$rc->Disconnect();
		}
  	@mail($config['admin_email'], "WebRequest Error", MakeErrorReport("RequestSong()", $rc->GetError()));					
		return $rc->GetError();
	}				

	function RateSong($song,$nick,$rating) {		
		global $config;
		$rc = new RemoteClient();
		if ($rc->Connect()) {
			$str = $nick."\xFE".$rating."\xFE".$song;
			$arr = $rc->SendCommandRecvReply(0x36, $str, strlen($str));
			if (count($arr)) {
				$rc->Disconnect();
				return $arr['data'];
			} else {
				$rc->SetError("Error receiving reply from IRCBot");
			}
			$rc->Disconnect();
		}
  	@mail($config['admin_email'], "WebRequest Error", MakeErrorReport("RateSong()", $rc->GetError()));					
		return $rc->GetError();	
	}

	function xencode($data) {
		if (defined('ENT_HTML401')) {
			return htmlspecialchars($data,ENT_QUOTES|ENT_HTML401,'UTF-8');
		} else {
			return htmlspecialchars($data,ENT_QUOTES,'UTF-8');
		}
	}

	// functions for Smarty
	function songdisp($params, $smarty)
	{
		if (strlen($params['song']['Artist']) && strlen($params['song']['Title'])) {
			return $params['song']['Artist']." - ".$params['song']['Title'];
		} else {
			return $params['song']['FN'];
		}
	}
	
	function timedisp($params, $smarty)
	{
		return duration($params['len']);
	}

	$SIL_didcheck = false;
	$SIL_useThemeImage = false;

	function songinfolink($params, $smarty)
	{		
		global $config, $SIL_didcheck, $SIL_useThemeImage;
		if (!$SIL_didcheck) {
			if (is_file("./themes/".$config['theme']."/images/info.png")) {
				$SIL_useThemeImage = true;
			}
			$SIL_didcheck = true;
		}
		$ret = "<a href=\"#\" onClick=\"javascript:window.open('songinfo.php?pl=".$config['db_table']."&id=".$params['id']."','wndsonginfo','scrollbars=yes,status=no,menubar=no,location=no,resizeable=yes,height=400,width=640'); return false;\"><img src=\"";
		if ($SIL_useThemeImage) {
			$ret .= "themes/".$config['theme']."/images/info.png";
		} else {
			$ret .= "images/info.png";
		}	
		return $ret .= "\" border=0 alt=\"Song Information\" title=\"Song Information\"></a>";
	}

	$RL_didcheck = false;
	$RL_useThemeImage = false;

	function requestlink($params, $smarty)
	{
		global $config, $RL_didcheck, $RL_useThemeImage;
		//$ret = "<a href=\"#\" onClick=\"javascript:window.open('request.php?id=".$params['id']."','wndrequest','scrollbars=yes,status=no,menubar=no,location=no,resizeable=yes,height=200,width=400'); return false;\"><img src=\"";
		$ret = "<a href=\"#\" onClick=\"var script = document.createElement('script'); script.type = 'text/javascript'; script.src = 'request.php?js=1&id=".$params['id']."'; document.getElementsByTagName('head')[0].appendChild(script); return false;\"><img src=\"";
		if (!$RL_didcheck) {
			if (is_file("./themes/".$config['theme']."/images/request.png")) {
				$RL_useThemeImage = true;
			}
			$RL_didcheck = true;
		}
		if ($RL_useThemeImage) {
			$ret .= "themes/".$config['theme']."/images/request.png";
		} else {
			$ret .= "images/request.png";
		}
		return $ret."\" border=0 alt=\"Request Song\" title=\"Request Song\"></a>";
	}

	$R_didcheck = false;
	$RS_useThemeImage = false;
	$RD_useThemeImage = false;
	$RR_useThemeImage = false;
	
	function rating($params, $smarty)
	{
		global $config, $R_didcheck, $RS_useThemeImage, $RD_useThemeImage, $RR_useThemeImage;
		if (!$R_didcheck) {
			if (is_file("./themes/".$config['theme']."/images/star.png")) {
				$RS_useThemeImage = true;
			}
			if (is_file("./themes/".$config['theme']."/images/disstar.png")) {
				$RD_useThemeImage = true;
			}
			if (is_file("./themes/".$config['theme']."/images/remrate.png")) {
				$RR_useThemeImage = true;
			}
			$R_didcheck = true;
		}
		if ($RS_useThemeImage) {
			$star = "themes/".$config['theme']."/images/star.png";
		} else {
			$star = "images/star.png";
		}
		if ($RD_useThemeImage) {
			$disstar = "themes/".$config['theme']."/images/disstar.png";
		} else {
			$disstar = "images/disstar.png";
		}
		if ($RR_useThemeImage) {
			$remrate = "themes/".$config['theme']."/images/remrate.png";
		} else {
			$remrate = "images/remrate.png";
		}
		if ($config['allow_rate']) {
			$alt = "Remove your existing rating (if any)";
			//$ret = "<a href=\"#\" onClick=\"javascript:window.open('rate.php?id=".$params['song']['ID']."&rating=0','wndrate','scrollbars=yes,status=no,menubar=no,location=no,resizeable=yes,height=200,width=400'); return false;\"><img src=\"".$remrate."\" border=0 alt=\"".$alt."\" title=\"".$alt."\"></a>";
			$ret = "<a href=\"#\" onClick=\"var script = document.createElement('script'); script.type = 'text/javascript'; script.src = 'rate.php?js=1&id=".$params['song']['ID']."&rating=0'; document.getElementsByTagName('head')[0].appendChild(script); return false;\"><img src=\"".$remrate."\" border=0 alt=\"".$alt."\" title=\"".$alt."\"></a>";
			for ($i=1; $i <= $params['song']['Rating']; $i++) {
				$alt = "Give song a rating of ".$i;
				//$ret .= "<a href=\"#\" onClick=\"javascript:window.open('rate.php?id=".$params['song']['ID']."&rating=".$i."','wndrate','scrollbars=yes,status=no,menubar=no,location=no,resizeable=yes,height=200,width=400'); return false;\"><img src=\"".$star."\" border=0 alt=\"".$alt."\" title=\"".$alt."\"></a>";
				$ret .= "<a href=\"#\" onClick=\"var script = document.createElement('script'); script.type = 'text/javascript'; script.src = 'rate.php?js=1&id=".$params['song']['ID']."&rating=".$i."'; document.getElementsByTagName('head')[0].appendChild(script); return false;\"><img src=\"".$star."\" border=0 alt=\"".$alt."\" title=\"".$alt."\"></a>";
			}
			for (; $i <= 5; $i++) {
				$alt = "Give song a rating of ".$i;
				//$ret .= "<a href=\"#\" onClick=\"javascript:window.open('rate.php?id=".$params['song']['ID']."&rating=".$i."','wndrate','scrollbars=yes,status=no,menubar=no,location=no,resizeable=yes,height=200,width=400'); return false;\"><img src=\"".$disstar."\" border=0 alt=\"".$alt."\" title=\"".$alt."\"></a>";
				$ret .= "<a href=\"#\" onClick=\"var script = document.createElement('script'); script.type = 'text/javascript'; script.src = 'rate.php?js=1&id=".$params['song']['ID']."&rating=".$i."'; document.getElementsByTagName('head')[0].appendChild(script); return false;\"><img src=\"".$disstar."\" border=0 alt=\"".$alt."\" title=\"".$alt."\"></a>";
			}
		} else {
			$ret = str_repeat("<img src=\"".$star."\" border=0>",$params['song']['Rating']).str_repeat("<img src=\"".$disstar."\" border=0>",$params['song']['RevRating']);
		}		
		return $ret;
	}
	
	$ONAIR_didcheck = false;
	$ONAIR_useThemeImage = false;

	function onairimg($params, $smarty)
	{		
		global $config, $ONAIR_didcheck, $ONAIR_useThemeImage;
		if (!$ONAIR_didcheck) {
			if (is_file("./themes/".$config['theme']."/images/onair.png")) {
				$ONAIR_useThemeImage = true;
			}
			$ONAIR_didcheck = true;
		}
		$ret = "<img src=\"";
		if ($ONAIR_useThemeImage) {
			$ret .= "themes/".$config['theme']."/images/onair.png";
		} else {
			$ret .= "images/onair.png";
		}	
		return $ret .= "\" border=0 alt=\"On Air\" title=\"On Air\">";
	}	
	
	$getimagecache = array();
	function getimage($params, $smarty)
	{		
		global $config,$getimagecache;		
		if (!isset($params['fn']) || empty($params['fn']) || strstr($params['fn'], '..') !== FALSE || strstr($params['fn'], '/') !== FALSE || strstr($params['fn'], '\\') !== FALSE) {
			return 'Empty or path characters found';
		}
		if (!isset($getimagecache[$params['fn']])) {
			if (is_file("./themes/".$config['theme']."/images/".$params['fn'])) {
				$getimagecache[$params['fn']] = "themes/".$config['theme']."/images/".$params['fn'];
			} else {
				$getimagecache[$params['fn']] = "images/".$params['fn'];
			}
		}
		return $getimagecache[$params['fn']];
	}
		
	function sm_str_repeat($params, $smarty) {
		return str_repeat($params['text'],$params['count']);
	}
	
}// !defined()
