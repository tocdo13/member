<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY vuonggialong
EDITED BY KHOAND
******************************/
class Url
{
	static public $root = 'ver18fixtosilverland/';
	function build_all($except=array(), $addition=false)
	{
		$url=false;
		foreach($_GET as $key=>$value)
		{	
			if(!in_array($key, $except))
			{
				
				if(!$url)
				{
					$url='?'.urlencode($key).'='.urlencode($value);
				}
				else
				{
					$url.='&'.urlencode($key).'='.urlencode($value);
				}
			}
		}
		foreach($_POST as $key=>$value)
		{
			if($key!='form_block_id')
			{
				if(!in_array($key, $except))
				{
					if(is_array($value))
					{
						$value = '';
					}
					if(!$url)
					{
						$url='?'.urlencode($key).'='.urlencode($value);
					}
					else
					{
						$url.='&'.urlencode($key).'='.urlencode($value);
					}
				}
			}
		}
		
		if($addition)
		{
			if($url)
			{
				$url.='&'.$addition;
			}
			else
			{
				$url.='?'.$addition;
			}
		}
		return $url;
	}
	function build_current($params=array(),$smart=false,$anchor='')
	{
		return URL::build(Portal::$page['name'],$params,$smart,Url::get('portal'),$anchor);
	}
	/*-------------------- edit by thanhpt 08/10/2008: add rewrite --------------------------*/
	function build($page,$params=array(),$smart=false,$portal_id=false,$anchor='')
	{
		//require_once 'packages/portal/includes/utils/vn_code.php';
		if($smart)
		{
			$request_string = URL::get('portal').'/'.$page;
			if($portal_id)
			{
				$request_string =$portal_id.'/'.$page;
			}
			if ($params)
			{
				foreach ($params as $param=>$value)
				{
					if(is_numeric($param))
					{
						if(isset($_REQUEST[$value]))
						{
							$request_string .= '/'.urlencode($_REQUEST[$value]);
						}
					}
					else
					{
						if($param=='name')
						{
							$request_string .= '/'.convert_utf8_to_url_rewrite($value);
						}
						else
						{
							if(preg_match('/page_no/',$param,$matches))
							{
								$request_string .= '/trang-'.$value;
							}
							else
							{
								$request_string .= '/'.substr($param,0,1).$value;
							}	
						}
					}
				}
			}
			$request_string.='.html';
		}
		else
		{
			if(!isset($params['portal']))
			{
				$params['portal'] = URL::get('portal');
			}
			$request_string = '?page='.$page;
	
			if ($params)
			{
				foreach ($params as $param=>$value)
				{
					if(is_numeric($param))
					{
						if(isset($_REQUEST[$value]))
						{
							$request_string .= '&'.$value.'='.urlencode($_REQUEST[$value]);
						}
					}
					else
					{
						$request_string .= '&'.$param.'='.urlencode($value);
					}
				}
			}
		}	
		return $request_string.$anchor;
	}
	function build_page($page,$params=array(),$anchor='')
	{
		return URL::build(Portal::get_setting('page_name_'.$page),$params,$anchor);
	}
	function redirect_current($params=array(),$anchor = '')
	{
		URL::redirect(Portal::$page['name'],$params+array('portal'),$anchor);
	}
	function redirect_href($params=false)
	{
		if(Url::check('href'))
		{
			Url::redirect_url(Url::attach($_REQUEST['href'],$params));
			return true;
		}
	}
	function check($params)
	{
		if(!is_array($params))
		{
			$params=array(0=>$params);
		}
		foreach($params as $param=>$value)
		{
			if(is_numeric($param))
			{
				if(!isset($_REQUEST[$value]))
				{
					return false;
				}
			}
			else
			{
				if(!isset($_REQUEST[$param]))
				{
					return false;
				}
				else
				{
					if($_REQUEST[$param]!=$value)
					{
						return false;
					}
				}
			}
		}
		return true;
	}
	function check_link($link)
	{
		if(preg_match('/http:\/\//',$link,$matches))
		{
			return $link;
		}
		else
		{
			return WEB_ROOT.$link;
		}
	}
	//Chuyen sang trang chi ra voi $url
	function redirect($page=false,$params=false,$smart=false,$anchor='')
	{
		if(!$page and !$params)
		{
			Url::redirect_url();
		}
		else
		{
			Url::redirect_url(Url::build($page, $params,$smart,$anchor));
		}
	}
	function redirect_url($url=false)
	{
		if(!$url||$url=='')
		{
			$url='?'.$_SERVER['QUERY_STRING'];
		}
		header('Location:'.str_replace('&','&','http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.$url));
		System::halt();
	}
	function access_denied()
	{
		if(Portal::$page['name']!='home')
		{
			Url::redirect('access_denied');
		}
		else
		{
			System::halt();
		}
	}
	function get_num($name,$default='')
	{
		if (preg_match('/[^0-9.,]/',URL::get($name)))
		{
			return $default;
		}
		else
		{
			return str_replace(',','.',str_replace('.','',$_REQUEST[$name]));
		}
	}
	function get_value($name,$default='')
	{
		if (isset($_REQUEST[$name]))
		{
			return $_REQUEST[$name];
		}
		else
		if (isset($_POST[$name]))
		{
			return $_POST[$name];
		}
		else
		if(isset($_GET[$name]))
		{
			return $_GET[$name];
		}
		else
		{
			return $default;		
		}
	}
	function get($name,$default='')
	{
		/*if(eregi("(^xxx)|(^fuck)",Url::get_value($name,$default=''))){
			$string = 'Warning: has bad word...please change another word';
			return $string;
		}*/
		if(isset($_REQUEST[$name]))
		{
			return Url::get_value($name,$default='');
		}
		else if(isset($_REQUEST[strtoupper($name)]))
		{
			return Url::get_value(strtoupper($name),$default='');
		}
		else if(isset($_REQUEST[substr($name,0,1)]))
		{
			return Url::get_value(substr($name,0,1),$default='');
		}
		else
		{
			return $default;
		}
	}
	function sget($name,$default='')
	{
		return strtr(URL::get($name, $default),array('"'=>'\\"'));
	}
	function iget($name)
    {//Lay theo so nguyen
		if(!is_numeric(Url::sget($name)))
        {
			return 0;
		}else
        {
			return intval(Url::sget($name));
		}
	}
	function fget($name){// lay theo so float
		if(!is_numeric(Url::sget($name))){
			return 0;
		}else{
			return floatval(Url::sget($name));
		}
	}
	function jget($name,$default='')
	{
		return String::string2js(URL::get($name, $default));
	}
}
?>