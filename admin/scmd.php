<?php
	require("./admincore.inc.php");
	
	if ($_GET['mode'] == "get_info") {
		$rc = new RemoteClient();
		if ($rc->Connect($userinfo['Username'],$userinfo['Pass'])) {
			$arr = $rc->SendCommandRecvReply(0x02);
			if (count($arr) && $arr['cmd'] == 19 && $arr['len'] == 140) {
				/*
					typedef struct {
						char title[64]; //stream title
						char dj[64];
						int32 listeners;
						int32 peak;
						int32 max;
					} STREAM_INFO;
					140 byte structure
				*/
				$title = trim(substr($arr['data'], 0, 64));
				$dj = trim(substr($arr['data'], 64, 64));
				$listeners = unpack("V", substr($arr['data'], 128, 4));
				$listeners = $listeners[1];
				$peak = unpack("V", substr($arr['data'], 132, 4));
				$peak = $peak[1];
				$max = unpack("V", substr($arr['data'], 136, 4));
				$max = $max[1];
				print "setStreamInfo('".str_replace("'","`",$title)."', 'Listeners: ".$listeners."/".$max.", Peak: ".$peak."');";
			} else {
				print "setStreamInfo('Error receiving reply from bot', '".str_replace("'","`",$rc->GetError())."');";
			}
			$rc->Disconnect();
		} else {
			print "setStreamInfo('Error logging in to bot', '".str_replace("'","`",$rc->GetError())."');";
		}
	}
	
	if (isset($_GET['cmd'])) {
		$rc = new RemoteClient();
		if ($rc->Connect($userinfo['Username'],$userinfo['Pass'])) {
			$arr = $rc->SendCommandRecvReply($_GET['cmd'], $_GET['parms'], strlen($_GET['parms']));
			if (count($arr)) {
				print "setServerStatus('".str_replace("'","`",$arr['data'])."');";
			} else {
				print "setServerStatus('".str_replace("'","`",$rc->GetError())."');";
			}
			$rc->Disconnect();
		} else {
			print "setServerStatus('".str_replace("'","`",$rc->GetError())."');";
		}		
	}
?>