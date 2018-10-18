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
  
/* Sun Sep 22 15:44:51 CEST 2002 */

///////////////////////////////////////////////////////////
// Input:
//    $str : string
//    $from: convert from this charset (VIQR by default)
//    $to  : convert to this charset   (UTF-8 by default)
// Output: string in $to charset
///////////////////////////////////////////////////////////
$s = "Chào chị

Xin cảm ơn chị đã quan tâm và sử dụng
dịch vụ của Vneshop.

Mã đơn đặt hàng chị đã được chuẩn
bị xong, nhưng do một số sai xót do bên lưu
hàng – Vneshop rất tiếc thông báo cho chị
về mặt hàng mã VNE 655 – “Xích đu tiên
“ đã hết hàng.

 

 Vneshop hiện nay đang có một số mặt hàng
ca nhạc thiếu nhi mới-nội dung hấp dẫn,
phong phú, bổ ích. Mong chị vui lòng lựa
chọn để thay thế vào mặt hàng đã hết.
Đặc biệt khi lựa chọn các mặt hàng
này,chị sẽ nhận được thêm 1 đĩa ca
nhạc nữa, vì vậy xin lựa chọn 2 trong số
những mặt hàng dưới đây.

 

 Mã các mặt hàng mới:

http://www.Hoangduoc.com/detail.php?good=VNE1800

http://www.Hoangduoc.com/detail.php?good=VNE1801

http://www.Hoangduoc.com/detail.php?good=VNE1802

http://www.Hoangduoc.com/detail.php?good=VNE1803

http://www.Hoangduoc.com/detail.php?good=VNE1804

 

Hoặc chị có thể lựa chọn phương thức
nhận lại tiền. Vneshop sẽ nhanh chóng trả
lại tiền mặt hàng không cung cấp được
cho chị.

Rất xin lỗi chị về sai xót này của
Vneshop. 

Hi vọng nhận được sự góp ý của chị cho
Vneshop.

 

Đại diện Vneshop

Sales Manager

Trương Quỳnh

 


";
function Remove_text_sign() {
	return  str_replace (array("`","'","?","~",".", "^","+"),"",VnConv($s, "utf8", "viqr"));
}

function VnConv($str='', $from='viqr', $to='utf8') {
	
	if ($str === '' OR $from === '' OR $to === '') { return ''; }
	
	$from = strtolower($from);
	$to   = strtolower($to);
	// Some aliases
	switch ($to){
		case 'iso8859-1' : $to = 'viqr'; break;
		case 'iso10646-1': $to = 'utf8'; break;
	}

	// Not need to go more
	if ($from == $to){ return $str; }

	// sviqr: Special VIQR => Escape for URL, E-mail,...
	if ($from == 'sviqr'){
		$is_sviqr = 1;
		$from = 'viqr';
	} else {
		$is_sviqr = 0;
	}
	
	// Including path	
	//$path = '/opt/apache/htdocs/vn';
	$path = '.';
	// Extension of including files
	$ext  = '.inc';

	// Check including files
	if ( !file_exists("$path/$from$ext") OR !file_exists("$path/$to$ext")){
		echo 'Vietnamese charset not found<br>';
		exit;
	}	

	include ("$path/$from$ext");	
	include ("$path/$to$ext");
	$vn_from = $$from;
	$vn_to = $$to;

	// Build converting array
	$size = sizeof ($vn_from);
	for ($i = 0; $i < $size; $i++){
		$trans[$vn_from[$i]] = $vn_to[$i];
	}
	// Some escape for VIQR
	if ($from == 'viqr'){
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
/* vim: set si ai ts=4 tw=0 sw=4 : */
?>
