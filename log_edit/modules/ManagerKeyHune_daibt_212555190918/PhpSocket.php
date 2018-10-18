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
	private $debug = true;

	// generic tcp PhpSocket wrapper
	/**
	 * create PhpSocket object
	 * params 
	 * $server - string, ip or domain name to connect to
	 * $port - int, port to make connection to
	 * $eol - string, the end of line string, by default its "\r\n"
	**/
    public function __construct($server, $port, $eol = NULL) {

        $this->socket = NULL;
        $this->server = $server;
		$this->port = $port;

		if ($eol !== NULL) {
			$this->eol = $eol;
		}
	}

	/**
	 * return eol
	 * params 
	 * return end of line
	**/
	public function getEol() {
		return $this->eol;
	}

	/**
	 * connect to socket
	 * params 
	 * return socket reference
	**/
    public function log($text) {
        /*
		$file = 'log.txt';
        //packages/hotel/packages/reception/modules/ManagerKey/
        $current = file_get_contents($file);
        $current .= $text."\n";
        file_put_contents($file, $current);
        */
	}
    
    //phuong thuc start socket  giap.luunguyen add 28-9-2014
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
    //phuong thuc Stop// ket thuc ket noi socket giap.luunguyen add 28-9-2014
    public function Stop()
    {
        if ($this->socket != NULL) {
            socket_shutdown($this->socket,2);
            socket_close($this->socket);
            $this->socket = NULL;
        }
    }
    //Phuong thuc Send data socket giap.luunguyen add 28-9-2014
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
    //phuong thuc recieve data socket giap.luunguyen add 29-9-2014
    public function Receive()
    {
        $recvbuf = '';
        $tmp ='';
        $iResult = socket_recv($this->socket, $recvbuf, 512, 0);
        if ($iResult > 0)
        {
            $tmp = substr($recvbuf,0,$iResult);
        }
        return $tmp;
    }
    //end recieve function
}




