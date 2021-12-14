<?php



function ftp_file( $ftpservername, $ftpusername, $ftppassword, $ftpsourcefile, $ftpdirectory, $ftpdestinationfile )
{

    // var_dump($ftpservername, $ftpusername, $ftppassword, $ftpsourcefile, $ftpdirectory, $ftpdestinationfile );
    
    
    
      
	$conn_id = ftp_connect($ftpservername);

	if ( $conn_id == false )
	{
		echo "FTP open connection failed to $ftpservername \n" ;
		return false;
	}

	$login_result = ftp_login($conn_id, $ftpusername, $ftppassword);

	if ((!$conn_id) || (!$login_result)) {
		echo "FTP connection has failed!\n";
		echo "Attempted to connect to " . $ftpservername . " for user " . $ftpusername . "\n";
		return false;
	} else {
		echo "Connected to " . $ftpservername . ", for user " . $ftpusername . "\n";
	}

	if ( strlen( $ftpdirectory ) > 0 )
	{
        if (!is_dir( __DIR__ . '/../media/' . $ftpdirectory)) {
            ftp_mkdir($conn_id, $ftpdirectory);
        }
            if (ftp_chdir($conn_id, $ftpdirectory .'/')) {
                echo "Current directory is now: " . ftp_pwd($conn_id) . "\n";
            } else {
                echo "Couldn't change directory on $ftpservername\n dir =" . ftp_pwd($conn_id);
                return false;
            }
        
		
	}

	ftp_pasv ( $conn_id, true ) ;
	$upload = ftp_put( $conn_id, $ftpdestinationfile, $ftpsourcefile);

	if (!$upload) {
		echo "$ftpservername: FTP upload has failed!\n";
		return false;
	} else {
		echo "Uploaded " . $ftpsourcefile . " to " . $ftpservername . " as " . $ftpdestinationfile . "\n";
	}

	ftp_close($conn_id);
	return true;
}
?>
