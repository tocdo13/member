<?php
/* ---------------------------------------------------------------------------   
   phpVnconv: Vietnamese Converting Engine for PHP v1.0 (20/09/2002)
   http://linux.nguyendai.org/phpvnconv/
   (C) 2001-2002, NGUYEN-DAI Quy <Quy@NguyenDai.org>

   This program is free software; you can redistribute it and/or
   modify it under the terms of the GNU General Public License
   as published by the Free Software Foundation; either version 2
   of the License, or (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
   --------------------------------------------------------------------------- */
  
/* Wed Jan  8 22:22:27 CET 2003 */
if (defined('_PHPVNCONV_INC')) return;
define('_PHPVNCONV_INC',1);

class phpVnconv {
	var $FROM = 'utf8';
	var $TO   = 'viqr';
	var $PATH = '.';

	var $vowel 	= array("a",  "á",  "à",  "ả",  "ã",  "ạ",  "ă",  "ắ",  "ằ",  "ẳ",  "ẵ",  "ặ",  "â",  "ấ",  "ầ",  "ẩ",  "ẫ",  "ậ",  "e",  "é",  "è",  "ẻ",  "ẽ",  "ẹ",  "ê",  "ế",  "ề",  "ể",  "ễ",  "ệ",  "i",  "í",  "ì",  "ỉ",  "ĩ",  "ị",  "o",  "ó",  "ò",  "ỏ",  "õ ",  "ọ",  "ô",  "ố",  "ồ",  "ổ",  "ỗ",  "ộ",  "ơ",  "ớ",  "ờ",  "ở",  "ỡ",  "ợ",  "u",  "ú",  "ù",  "ủ",  "ũ",  "ụ",  "ư",  "ứ",  "ừ ",  "ử ",  "ữ ",  "ự ",  "y ",  "ý ",  "ỳ ",  "ỷ ",  "ỹ",  "ỵ",  "đ",  "",  "A",  "Á",  "À",  "Ả",  "Ã",  "Ạ",  "Ă",  "Ắ",  "Ằ",  "Ẳ",  "Ẵ",  "Ặ ",  "Â",  "Ấ",  "Ầ",  "Ẩ",  "Ẫ",  "Ậ",  "E",  "É",  "È ",  "Ẻ",  "Ẽ",  "Ẹ",  "Ê",  "Ế",  "Ề",  "Ể ",  "Ễ",  "Ệ",  "I",  "Í ",  "Ì",  "Ỉ",  "Ĩ",  "Ị",  "O",  "Ó",  "Ò",  "Ỏ",  "Õ",  "Ọ",  "Ô",  "Ố",  "Ồ",  "Ổ",  "Ỗ",  "Ộ",  "Ơ",  "Ớ",  "Ờ",  "Ở",  "Ỡ",  "Ợ",  "U",  "Ú ",  "Ù",  "Ủ",  "Ũ",  "Ụ",  "Ư",  "Ứ",  "Ừ",  "Ử",  "Ữ",  "Ự",  "Y",  "Ý ",  "Ỳ ",  "Ỷ ",  "Ỹ ",  "Ỵ ",  "Đ");
	var $hexa  	= array("&#x0061;", "&#x00E1;", "&#x00E0;", "&#x1EA3;", "&#x00E3;", "&#x1EA1;", "&#x0103;", "&#x1EAF;", "&#x1EB1;", "&#x1EB3;", "&#x1EB5;", "&#x1EB7;", "&#x00E2; ", "&#x1EA5; ", "&#x1EA7;", "&#x1EA9; ", "&#x1EAB;", "&#x1EAD;", "&#x0065;", "&#x00E9;", "&#x00E8", "&#x1EBB; ", "&#x1EBD; ", "&#x1EB9;", "&#x00EA;", "&#x1EBF;", "&#x1EC1;", "&#x1EC3;", "&#x1EC5; ", "&#x1EC7; ", "&#x0069;", "&#x00ED;", "&#x00EC;", "&#x1EC9; ", "&#x0129;", "&#x1ECB; ", "&#x006F;", "&#x00F3;", "&#x00F2;", "&#x1ECF;", "&#x00F5;", "&#x1ECD;", "&#x00F4;", "&#x1ED1;", "&#x1ED3;", "&#x1ED5;", "&#x1ED7; ", "&#x1ED9; ", "&#x01A1;", "&#x1EDB;", "&#x1EDD;", "&#x1EDF;", "&#x1EE1;", "&#x1EE3; ", "&#x0075;", "&#x00FA;", "&#x00F9;", "&#x1EE7;", "&#x0169;", "&#x1EE5;", "&#x01B0;", "&#x1EE9;", "&#x1EEB;", "&#x1EED;", "&#x1EEF;", "&#x1EF1;", "&#x0079;", "&#x00FD;", "&#x1EF3;", "&#x1EF7;", "&#x1EF9;", "&#x1EF5;", "&#x0111;", "", "&#x0041;", "&#x00C1;", "&#x00C0;", "&#x1EA2;", "&#x00C3;", "&#x1EA0;", "&#x0102; ", "&#x1EAE;", "&#x1EB0; ", "&#x1EB2; ", "&#x1EB4; ", "&#x1EB6;", "&#x00C2;", "&#x1EA4; ", "&#x1EA6; ", "&#x1EA8; ", "&#x1EAA; ", "&#x1EAC; ", "&#x0045;", "&#x00C9;", "&#x00C8;", "&#x1EBA; ", "&#x1EBC; ", "&#x1EB8;", "&#x00CA;", "&#x1EBE;", "&#x1EC0;", "&#x1EC2;", "&#x1EC4;", "&#x1EC6;", "&#x0049;", "&#x00CD;", "&#x00CC;", "&#x1EC8;", "&#x128;", "&#x1ECA;", "&#x004F;", "&#x00D3;", "&#x00D2;", "&#x1ECE;", "&#x00D5;", "&#x1ECC;", "&#x00D4;", "&#x1ED0;", "&#x1ED2;", "&#x1ED4;", "&#x1ED6;", "&#x1ED8;", "&#x01A0;", "&#x1EDA;", "&#x1EDC;", "&#x1EDE;", "&#x1EE0;", "&#x1EE2;", "&#x0055;", "&#x00DA;", "&#x00D9;", "&#x1EE6;", "&#x168;", "&#x1EE4;", "&#x01AF;", "&#x1EE8;", "&#x1EEA;", "&#x1EEC;", "&#x1EEE;", "&#x1EF0;", "&#x0059;", "&#x00DD;", "&#x1EF2;", "&#x1EF6;", "&#x1EF8;", "&#x1EF4;", "&#x0110;");
	var $deci 	= array("&#97;", "&#225;", "&#224;", "&#7843;", "&#227;", "&#7841;", "&#259;", "&#7855;", "&#7857;", "&#7859;", "&#7861;", "&#7863;", "&#226;", "&#7845;", "&#7847;", "&#7849;", "&#7851;", "&#7853;", "&#101;", "&#233;", "&#232;", "&#7867;", "&#7869;", "&#7865;", "&#234;", "&#7871;", "&#7873;", "&#7875;", "&#7877;", "&#7879;", "&#105;", "&#237;", "&#236;", "&#7881;", "&#297;", "&#7883;", "&#111;", "&#243;", "&#242;", "&#7887;", "&#245;", "&#7885;", "&#244;", "&#7889;", "&#7891;", "&#7893;", "&#7895;", "&#7897;", "&#417;", "&#7899;", "&#7901;", "&#7903;", "&#7905;", "&#7907;", "&#117;", "&#250;", "&#249;", "&#7911;", "&#361;", "&#7909;", "&#432;", "&#7913;", "&#7915;", "&#7917;", "&#7919;", "&#7921;", "&#121;", "&#253;", "&#7923;", "&#7927;", "&#7929;", "&#7925;", "&#273;", "", "&#65;", "&#193;", "&#192;", "&#7842;", "&#195;", "&#7840;", "&#258;", "&#7854;", "&#7856;", "&#7858;", "&#7860;", "&#7862;", "&#194;", "&#7844;", "&#7846;", "&#7848;", "&#7850;", "&#7852;", "&#69;", "&#201;", "&#200;", "&#7866;", "&#7868;", "&#7864;", "&#202;", "&#7870; ", "&#7872;", "&#7874;", "&#7876;", "&#7878;", "&#73;", "&#205;", "&#204;", "&#7880;", "&#296;", "&#7882;", "&#79;", "&#211;", "&#210;", "&#7886;", "&#213;", "&#7884;", "&#212;", "&#7888;", "&#7890;", "&#7892;", "&#7894;", "&#7896;", "&#416;", "&#7898;", "&#7900;", "&#7902;", "&#7904;", "&#7906;", "&#85;", "&#218;", "&#217;", "&#7910;", "&#360;", "&#7908;", "&#431;", "&#7912;", "&#7914;", "&#7916;", "&#7918;", "&#7920;", "&#89;", "&#221;", "&#7922;", "&#7926;", "&#7928;", "&#7924;", "&#272;");
	
	function phpVnconv ($path = ''){
		if  ($path == '') $path = dirname(__FILE__) . "/phpvnconv";
		
		if (!is_dir($path)) 
			$this->error('Path not found');
			
	//	if (substr($path, -1) != '/')
	//		$path .= '/';
		$this->PATH = $path;
	}
	
	function set_from ($from) {
		if ($from)
			$this->FROM = strtolower($from);
		else
			$this->FROM = 'viqr';
	}
	
	function set_to ($to) {
		if ($to)
			$this->TO = strtolower($to);
		else
			$this->TO = 'utf8';
	}

	function error ($msg){
		echo 'ERROR: '.$msg;
		exit;
	}
	
	function RemoveSign($str="") {
		
		$s = $this->VnConv($str,"utf8","enc");
		
		return $s;
				
	}
	///////////////////////////////////////////////////////////
	// Input:
	//    $str : string
	//    $from: convert from this charset (VIQR by default)
	//    $to  : convert to this charset   (UTF-8 by default)
	// Output: string in $to charset
	///////////////////////////////////////////////////////////
	function VnConv ($str='', $from="", $to="") {
		$this->set_from($from);
		$this->set_to($to);
		
		$vn_from = $vn_to = array();
		
		if ($str === '' OR $this->FROM === '' OR $this->TO === '')
			return $str;
	
/*		// Some aliases
		switch ($this->TO){
			case 'iso8859-1' : $this->TO = 'viqr'; break;
			case 'iso10646-1': $this->TO = 'utf8'; break;
		}
	*/
		// Not need to go more
		if ($this->FROM == $this->TO)
			return $str;

		//convert hexa to unicode
		if ($this->FROM == "hexa" && $this->TO=="utf8")
			return str_replace($hexa, $vowel, $str);
			
		
		//convert decimal to unicode
		if ($this->FROM == "deci" && $this->TO=="utf8")
			return str_replace($deci, $vowel, $str);
		
			
		// sviqr: Special VIQR => Escape for URL, E-mail,...
		if ($this->FROM == 'sviqr'){
			$is_sviqr = 1;
			$this->FROM = 'viqr';
		} else {
			$is_sviqr = 0;
		}
	
		// Including path	
		//$path = '/opt/apache/htdocs/vn';
//		$path = 'phpvnconv';
		// Extension of including files
		$ext  = '.inc';
		$fto   = $this->PATH.'/'.$this->TO.$ext;
		$ffrom = $this->PATH.'/'.$this->FROM.$ext;
		// Check including files
		if ( !file_exists($ffrom) OR !file_exists($fto)) {
			echo $ffrom;
			$this->error('Vietnamese charset not found');
		}
		
		
		include ($ffrom);	
		include ($fto);

		$cfrom   = $this->FROM;
		$cto     = $this->TO;
		$vn_from = $$cfrom;
		$vn_to   = $$cto;

		// Build converting array
		$size = sizeof ($vn_from);
		for ($i = 0; $i < $size; $i++){
			$trans[$vn_from[$i]] = $vn_to[$i];
		}
		//print_r($trans);
		// Some escape for VIQR
		if ($this->FROM == 'viqr'){
			$trans["\d"] = "d";
			$trans["\D"] = "D";
			$trans["\^"] = "^";
			$trans["\+"] = "+";
			$trans["\."] = ".";
			$trans["\'"] = "'";
			$trans["\`"] = "`";
			$trans["\?"] = "?";
			$trans["\~"] = "~";
		}
		// If string contains URL, E-mail=> it should be escaped
		if ($is_sviqr == 1) {
			$tmp = $str;
			$str = '';
			while ( preg_match("/http|ftp|mailto|@/i", $tmp) ){
				$ss   = Escape_Viqr($tmp);
				$str .= $ss[0].' ';
				$tmp  = $ss[1];
			}
			if ($ss[1] != ''){ $str .= $ss[1]; }
		}

		return Strtr ($str, $trans);
	}

	function Escape_Viqr($str){
		if (preg_match("/(http|ftp|mailto)/", $str, $mat)){
			$url = $mat[1];
			$aa = preg_split("/$url/", $str, 2);
			$s1 = $aa[0];
			$bb = $url.$aa[1];
			$aa = preg_split("/ /", $bb, 2);
			// for return
			$tmp[0] = $s1. ' ' . preg_replace("/([aeyuio])\./i","\\1\\.",$aa[0]);
			$tmp[1] = $aa[1];
		} else if (preg_match("/[\w\.]+@[\w\-\.]+\.\w{2,3}/", $str)){
			$aa = preg_split("/@/", $str, 2);
			$pos1 = strrpos($aa[0],' ');
			if ($pos1 === false){ $pos1 = 0;}
			$pos2 = strpos($aa[1], ' ');
			if ($pos2 === false){ $pos2 = strlen($aa[1]);}
			//echo 'pos1=',$pos1,', pos2=',$pos2,'<br>';
			$tmp[0] = substr($aa[0], 0, $pos1);
			$email = substr($aa[0],$pos1).'@'.substr($aa[1],0,$pos2);
			// for return
			$tmp[0] .= ' ' . preg_replace("/([aeyuio])\./i","\\1\\.",$email);
			$tmp[1] = substr($aa[1], $pos2);
			
		} else {
			$tmp[0] = $str; $tmp[1] = '';
		}
		return $tmp;
	}

} // end of class	
/* vim: set si ai ts=4 tw=0 sw=4 : */
//$VnConv =new phpVnconv("phpvnconv");
//echo $VnConv->VnConv("?");
?>
