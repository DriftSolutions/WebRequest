<?php
	require("./admincore.inc.php");

	if (!$config['allow_musicup']) {
  	$smarty->assign("db_queries",$db->GetQueriesCount());
  	$smarty->display("admin_notenabled.tpl");
  	exit;		
	}	
		
	$allowed_exts = array('.mp3','.ogg','.wav','.opus','.m4a','.aac','.mp4','.wma');
	
	function is_allowed_ext($fn) {
		global $allowed_exts;
		$n = strrpos($fn, '.');
		if ($n === FALSE) {
			return false;
		}
		$ext = strtolower(substr($fn, $n));
		return in_array($ext, $allowed_exts);
	}
	
	function let_to_num($v){ //This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
	    $l = substr($v, -1);
	    $ret = substr($v, 0, -1);
	    switch(strtoupper($l)){
	    case 'P':
	        $ret *= 1024;
	    case 'T':
	        $ret *= 1024;
	    case 'G':
	        $ret *= 1024;
	    case 'M':
	        $ret *= 1024;
	    case 'K':
	        $ret *= 1024;
	        break;
	    }
	    return $ret;
	}
	
	function GetMusicFolder($nick) {
		global $config;
		$ret = $config['upload_path']."music";
		if (!is_dir($ret)) { @mkdir($ret, 0777); @chmod($ret, 0777); }
		$ret .= '/'.$nick;
		if (!is_dir($ret)) { @mkdir($ret, 0777); @chmod($ret, 0777); }
		return $ret.'/';
	}
	
	function DispLine($msg) {
		global $smarty;
		$smarty->assign("msg", $msg);
		$smarty->display("admin_upload_exec_line.tpl");
	}	

	if (isset($_FILES['musfile']) && count($_FILES["musfile"]["name"])) {		
	  $smarty->display( "admin_upload_exec_header.tpl");	  

  	@set_time_limit(0);
	  $dir = GetMusicFolder($userinfo['Username']);
	  
		foreach ($_FILES["musfile"]["error"] as $key => $error) {	  
			if ($error == UPLOAD_ERR_OK) {
				if (is_allowed_ext($_FILES['musfile']['name'][$key])) {
					$ffn = $dir.basename($_FILES['musfile']['name'][$key]);
			  	if (@move_uploaded_file($_FILES['musfile']['tmp_name'][$key], $ffn) !== FALSE) {
			  		//we at least don't want the file to have execute permissions
			  		@chmod($ffn, 0666);
						DispLine("File '".$_FILES['musfile']['name'][$key]."' uploaded OK");
				  } else {
				  	DispLine("Error uploading file '".$_FILES['musfile']['name'][$key]."': error moving file to upload folder!");
				  }
				} else {
					DispLine("Error uploading file '".$_FILES['musfile']['name'][$key]."': file extension not allowed.");
				}
			} else if ($error != UPLOAD_ERR_NO_FILE) {
				DispLine("Error uploading file '".$_FILES['musfile']['name'][$key]."': ".$error);
			}
		}
	  
	 	$smarty->assign("db_queries",$db->GetQueriesCount());
	  $smarty->display( "admin_upload_exec_footer.tpl");
	} else if (isset($_POST['musurl'])) {
	  $smarty->display( "admin_upload_exec_header.tpl");	  

  	@set_time_limit(0);
	  $dir = GetMusicFolder($userinfo['Username']);
	  
	  $tmp = str_replace("\r","\n",$_POST['musurl']);
	  $urls = explode("\n", $tmp);
	  
		foreach ($urls as $url) {
			$url = trim($url);
			if (strlen($url) > 0) {
				if (($pi = parse_url($url)) !== FALSE) {
					if (is_allowed_ext($pi['path'])) {
						$fn = basename($pi['path']);
						if (strlen($fn) > 0) {
							$ffn = $dir.$fn;
							DispLine("Downloading ".$url."...");
							$opts = array();
							$opts['http']['timeout'] = 30;
							$h = stream_context_create($opts);
							$fp = @fopen($url, "rb", false, $h);
							if ($fp !== FALSE) {
								$fp2 = @fopen($ffn, "wb");
								if ($fp2 !== FALSE) {
									while (!feof($fp) && ($data = fread($fp, 16834)) !== FALSE) {
										fwrite($fp2, $data);				
									}
									fclose($fp2);
									//we at least don't want the file to have execute permissions
									@chmod($ffn, 0666);
									DispLine("File '".$fn."' downloaded OK");
								} else {
									DispLine("Error: could not open output file.");
								}
								fclose($fp);
							} else {
								DispLine("Error downloading URL: ".$url);
							}
						} else {
							DispLine("Error getting filename from URL: ".$url);
						}
					} else {
						DispLine("Error downloading file '".$url."': file extension not allowed.");
					}
				} else {
					DispLine("Error downloading file '".$url."': error parsing URL into components.");
				}
			}
		}
	  
	 	$smarty->assign("db_queries",$db->GetQueriesCount());
	  $smarty->display( "admin_upload_exec_footer.tpl");		
	} else {			
		$max_upload_size = let_to_num(ini_get('post_max_size'));
		$max_file_size = let_to_num(ini_get('upload_max_filesize'));
		$fu = ini_get('file_uploads');
		if (!strcasecmp($fu, "on") || $fu == "1") {
			$smarty->assign("max_file_size_mb", round($max_file_size/(1024*1024), 2)."MB");	
			$smarty->assign("max_upload_size_mb", round($max_upload_size/(1024*1024), 2)."MB");	
			$smarty->assign("file_uploads", 1);
		} else {
			$smarty->assign("max_file_size_mb", "File uploads disabled, contact your administrator for help");
			$smarty->assign("max_upload_size_mb", "File uploads disabled, contact your administrator for help");	
			$smarty->assign("file_uploads", 0);
		}
		
	 	$smarty->assign("db_queries",$db->GetQueriesCount());
	  $smarty->display("admin_musicup_index.tpl");
	}

?>