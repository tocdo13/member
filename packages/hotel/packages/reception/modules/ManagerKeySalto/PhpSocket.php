<?php
# PHPSocket.php -- php sockets
#
# Copyright (C) 2010 Progyan Softwares
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

class PhpSocket {  
	private $socket = NULL;
	private $server = NULL;
	private $port = null;
	private $eol = "\r\n";
    
    public function __construct($server, $port, $eol = NULL) {
        
        $this->socket = NULL;
        $this->server = $server;
		$this->port = $port;

		if ($eol !== NULL) {
			$this->eol = $eol;
		}
	}

    public function Start()
    {
        $s = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
       
        $server = $this->server;
        $port = $this->port;
        $serverIP = gethostbyname($server);
        
        //$status = socket_connect($s, $serverIP, $port);
        $status = @socket_connect($s, $serverIP, $port);
        if($status==true)
        {
            $this->socket = $s;
            return true;  
        }  
        return false;//tra ve true neu ket noi thanh cong
    }
    public function Send($str)
    {
        if ($this->socket == NULL) {
            return false;
        }
        $total = strlen($str);
        $sent = socket_send($this->socket, $str, strlen($str), 0);
        while ($sent < $total) {
            $str_str = substr($str, $sent);
            $sent = $sent + socket_send($this->socket,$str_str,strlen($str_str),0);
        }
        return $sent;  //tra ve so number lengh duoc gui di or false
    }

    public function Receive()
    {
        if ($this->socket == NULL) {
			return '';
		}
		$buf = '';
        socket_recv($this->socket, $buf, 1, 0);//MSG_WAITALL
		return $buf;
        
    }
    public function receive_data($bufsize) {
		if ($this->socket == NULL) {
			return '';
		}
		$buf = '';
        socket_recv($this->socket, $buf, $bufsize, MSG_WAITALL);
		return $buf;
        
	}
    public function receive_data_line() {
		if ($this->socket == NULL) {
			return '';
		}
        
        $buf = '';
        
        $bol = 0;
        //return $buf;
        while (true) {
            $in_byte = $this->receive_data(1);
            if($bol == 1 and $in_byte != '')
                return $buf.$in_byte;
            
            if (strToHex($in_byte) == '06') {
                $buf = '';
			}
            
            if (strToHex($in_byte) == '15') {
                return $in_byte;
			}
            
            if($in_byte == "")
                $bol = 1;
            if ($in_byte == '') {
                return NULL;
			}
            
			$buf .= $in_byte;
		}	
	}
    public function  read($waitSecs = 30)
    {
		$buf = '';
		if ($this->socket != NULL) 
        {
			$read = array($this->socket);
    		$write = array();
    		$except = array();
    		
    		$updated = @socket_select($read, $write, $except, $waitSecs);
            
    		if ($updated > 0){
    			$buf = $this->receive_data_line();
    		}
		}
		return $buf;
	}
    
	public function Stop()
    {
        if ($this->socket != NULL) {
            socket_shutdown($this->socket,2);
            socket_close($this->socket);
            $this->socket = NULL;
        }
    }
    function strToHex($string)
    {
        $hex='';
        for ($i=0; $i < strlen($string); $i++)
        {
            $hex .= dechex(ord($string[$i]));
        }
        return $hex;
    }
}




