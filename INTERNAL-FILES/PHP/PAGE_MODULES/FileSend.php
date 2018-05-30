<?php

namespace SITE_PAGE_MODULES;

class FileSend {
	
	//
	// Not Use
	//
	
	public static function NativeSend($FileName, $FakeFileName, $ContentType = 'application/octet-stream') {
		if (file_exists($FileName)) {
			header('Content-Description: File Transfer');
			header('Content-Type: ' . $ContentType);
			header('Content-Disposition: attachment; filename="' . $FakeFileName . '"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($FileName));
			readfile($FileName);
			exit;
		}
	}
	
	//
	// Use
	// Load module xsendfile
	//
	
	public static function ExtendedSend($FileName, $FakeFileName, $ContentType = 'application/octet-stream') {
		if (file_exists($FileName)) {
			header('X-SendFile: ' . $FileName );
			header('Content-Type: ' . $ContentType);
			header('Content-Disposition: attachment; filename="' . $FakeFileName . '"');
			exit;
		}
	}
	
}


?>