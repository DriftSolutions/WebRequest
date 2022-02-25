<?php
if (!defined('ADMIN_CORE')) {
	define('ADMIN_CORE', 'INCLUDED');	
	require("../core.inc.php");
	require('../include/user_flags.inc.php');
	session_start();
	
	$realm = 'WebRequest v'._WEBREQUEST_VERSION.' Admin Panel';

	if ($_GET['logout'] == 1) {
		$_SESSION['force_login'] = true;
		die("You are now logged out of the system, to log back in click <a href=\"index.php\">here</a>. To return to the main page, click <a href=\"../index.php\">here</a>");
	}
	
	function force_login() {
		global $alt_bad_login_text,$realm;
		header('WWW-Authenticate: Basic realm="'.$realm.'"');
    header('HTTP/1.0 401 Unauthorized');
    if (!isset($alt_bad_login_text) || empty($alt_bad_login_text)) { $alt_bad_login_text = "You must log in to use the Admin Panel. Your user/pass are the same as your IRCBot user/pass."; }
		die($alt_bad_login_text);
	}

	if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || (isset($_SESSION['force_login']) && $_SESSION['force_login'] == true)) {
		unset($_SESSION['force_login']);
		force_login();
	}
	
	$userinfo = array();
	function authUser($user,$pass) {
		global $db,$userinfo,$config;
		
		$user = trim($user);
		$pass = trim($pass);
 		if (strlen($user) == 0 || strlen($pass) == 0) { return false; }
 		
 		//see if we are in the DB already... (and user record updated within the last hour)
 		$ts = time() - 3600;
 		$query = "SELECT * FROM `".USERS_TABLE."` WHERE Username='".$db->escape($user)."' AND Pass='".$db->escape($pass)."' AND ((Status > 0 AND Status <= 4) OR (`Flags`&20488)>0) AND `TimeStamp`>=".$ts;
 		$res = $db->query($query);
 		if ($db->num_rows($res) == 1) {//only valid if one match exists, although Username is UNIQUE we'll put == 1 just in case
 			$userinfo = $db->fetch_assoc($res);
 			return true;
 		}
 		
 		//nope, let's ask the bot then
		$rc = new RemoteClient();
		if ($rc->Connect($user,$pass)) {
			$query = "INSERT INTO `".USERS_TABLE."` (`Username`,`Pass`,`Status`,`Flags`,`TimeStamp`,`LastSeen`) VALUES ('".$db->escape($user)."', '".$db->escape($pass)."', ".$rc->GetLevel().", ".$rc->GetFlags().", ".time().", ".time().") ON DUPLICATE KEY UPDATE Pass='".$db->escape($pass)."', Status=".$rc->GetLevel().", `Flags`=".$rc->GetFlags().", LastSeen=".time().", `TimeStamp`=".time();
			$res = $db->query($query);
			$rc->Disconnect();

	 		$query = "SELECT * FROM `".USERS_TABLE."` WHERE Username='".$db->escape($user)."' AND Pass='".$db->escape($pass)."' AND ((Status > 0 AND Status <= 4) OR (`Flags`&20488)>0)";
	 		$res = $db->query($query);
	 		if ($db->num_rows($res) == 1) {
	 			$userinfo = $db->fetch_assoc($res);
	 			return true;
	 		}
		} else {
			if (!stristr($rc->GetError(), "Error logging in to")) { //ignore an invalid user/pass error
	  		@mail($config['admin_email'], "WebRequest Error", MakeErrorReport("authUser()", $rc->GetError()));					
	  	}
	  }  	
		return false; 		 		
	}
	
	if (!authUser($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
		force_login();
	}

	// ok, valid username & password
	
	// disable music uploads if no +g flag
	if (!($userinfo['Flags'] & UFLAG_DCC_XFER)) {
		$config['allow_musicup'] = 0;
	}
	
	if ($_GET['chg_theme']) {
		SaveUserConfigValue("theme", $_GET['chg_theme']);
	}
	LoadDynamicConfig($userinfo['ID']);
	$smarty->template_dir = $config['base_path']."themes/".$config['theme'];	
	$smarty->assign("config", $config);
		
	$smarty->assign("userinfo", $userinfo);
	$smarty->assign("user", $userinfo['Username']);
		
} // defined()
