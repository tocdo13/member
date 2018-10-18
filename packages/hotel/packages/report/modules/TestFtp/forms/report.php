<?php
class AgentStatisticReportForm extends Form
{
	function AgentStatisticReportForm()
	{
		Form::Form('AgentStatisticReportForm');
	}
	function draw()
	{      
        $this->map = array();
        
        // path to remote file
        $remote_file = 'Pbxlog_Aug-2013.txt';
        $local_file = 'Pbxlog_Aug-2013.txt';
        
        // open some file to write to
        $handle = fopen($local_file, 'w');
        
        // set up basic connection
        $conn_id = ftp_connect('222.253.18.105');
        
        // login with username and password
        $login_result = ftp_login($conn_id, 'ngocdatbk', 'dat@123');
        
        // try to download $remote_file and save it to $handle
        if (ftp_fget($conn_id, $handle, $remote_file, FTP_ASCII, 0)) {
         echo "successfully written to $local_file\n";
        } else {
         echo "There was a problem while downloading $remote_file to $local_file\n";
        }
        
        // close the connection and the file handler
        ftp_close($conn_id);
        fclose($handle);
        
        $this->parse_layout('report',$this->map);			
	}
}
?>