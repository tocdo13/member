<?php
// Edited by khoand
class Form
{
	static $current = false;
	var $name = false;
	var $inputs = array();
	var $errors = false;
	var $error_messages = false;
	var $is_submit = false;
	var $count = 1;
	static $form_count = 1;
	function Form($name=false)
	{
		$this->name=$name;
		if(!defined('VERSION')){
			define('VERSION',3.01);
		}
	}
	function on_submit()   
	{

	}
	function is_submit()
	{
		if(!$this->is_submit)
		{
			$this->is_submit = 1;
			if(isset(Module::$current))
			{
				if(isset($_REQUEST['form_block_id']))
				{
					if($_REQUEST['form_block_id']==Module::block_id())
					{
						if($this->inputs)
						{
							$this->is_submit = 2;
							foreach($this->inputs as $name=>$types)
							{
								if(!strpos($name,'.') and !isset($_REQUEST[$name]))
								{
									$this->is_submit = 1;
									break;
								}
							}
						}
					}
				}
			}
		}
		return $this->is_submit == 2;
	}
	function is_error()
	{
		return $this->errors<>false or $this->error_messages<>false;
	}
	function add($name, $type)
	{
		$this->inputs[$name][] = $type;
	}
	function get_messages()
	{
		$this->error_messages=false;
		if($this->errors)
		{
			foreach($this->errors as $name=>$types)
			{
				foreach($types as $type)
				{
					$this->error_messages[$name][]=$type->get_message();
				}
			}
		}
		return $this->error_messages;
	}
	function check($exclude=array()){
		if($this->is_submit()){
			$this->errors = false;
			if($this->inputs){
				foreach ($this->inputs as $name=>$types){
					foreach($types as $type){
						if(!in_array($name,$exclude)){
							if(!strpos($name,'.')){
								if(!$type->check($_REQUEST[$name])){
									$this->errors[$name][] = $type;
								}
							}else{
								$names = explode('.',$name);
								$table = 'mi_'.$names[0];
								$field = $names[1];
								if(isset($_REQUEST[$table])){
									if(is_array($_REQUEST[$table])){
										foreach($_REQUEST[$table] as $key=>$record){
											if(isset($record[$field])){
												if(!$type->check($record[$field])){
													$this->errors[$table.'['.$key.']['.$field.']'][] = $type;
												}
											}else{
												if(!$type->check('')){
													$this->errors[$table.'['.$key.']['.$field.']'][] = $type;
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
			$this->get_messages();
			if(!$this->errors)
			{
				foreach ($this->inputs as $name=>$types)
				{
					foreach($types as $type)
					{
						if(get_class($type)=='floattype' or get_class($type)=='inttype')
						{
							if(!strpos($name,'.'))
							{
								$_REQUEST[$name] = str_replace(',','',$_REQUEST[$name]);
							}
							else
							{
								$names = explode('.',$name);
								$table = $names[0];
								$field = $names[1];
								if(isset($_REQUEST['mi_'.$table]))
								{
									if(is_array($_REQUEST['mi_'.$table]))
									{
										foreach($_REQUEST['mi_'.$table] as $key=>$record)
										{
											if(isset($record[$field]))
											{
												$_REQUEST['mi_'.$table][$key][$field] = str_replace(',','',$record[$field]);
											}
										}
									}
								}
							}
						}
					}
				}
			}
			return !$this->errors;
		}
		else
		{
			return false;
		}
	}
	function error($name, $message ,$use_language=true)
	{
		$this->error_messages[$name][]=$use_language?Portal::language($message):$message;
	}
	function parse_layout($name, $params=array())
	{
		$dir = ROOT_PATH.'cache/modules/'.Module::$current->data[(Module::$current->data['module']['type']!='WRAPPER')?'module':'wrapper']['name'];
		$cache_file_name = $dir.'/'.$name.'.php';
		$file_name = Module::$current->data[(Module::$current->data['module']['type']!='WRAPPER')?'module':'WRAPPER']['path'].'layouts/'.$name.'.php';
        if(!file_exists($cache_file_name) or (($cache_time=@filemtime($cache_file_name)) and (@filemtime($cache_file_name)<@filemtime($file_name))) or true)
		{
			require_once 'packages/core/includes/portal/generate_layout.php';
			$generate_layout = new GenerateLayout(file_get_contents($file_name));
			$text = $generate_layout->generate_text($generate_layout->synchronize());
			if(!is_dir($dir))
			{
				@mkdir($dir);
			}
			if($file = @fopen($cache_file_name,'w+'))
			{
				fwrite($file,$text);
				fclose($file);
			}
			$this->map = $params;
			$this->map['parse_layout'] = $text;
		}
		else
		{
			$this->map = $params;
			$this->map['parse_layout'] = file_get_contents($cache_file_name);
		}
		Module::invoke_event('ONPARSELAYOUT',Module::$current,$this->map);
		eval('?>'.$this->map['parse_layout'].'<?php ');
	}
	
	//In ra cac thong bao loi neu co
	function error_messages()
	{
		$this->count = Form::$form_count;
		Form::$form_count++;
		if(!$this->error_messages)
		{
			$show = ' style="display:none;"';
		}
		else
		{
			$show = '';
		}
		if (Portal::language()==1)
		{
			$notify = Portal::language('user_error');
		}
		else
		{
			$notify = 'Errors';
		}
		$txt = '<div id="error_messages_'.$this->count.'"'.$show.'><table cellpadding=5><tr valign="top">';
		$txt .= '<td nowrap><div class="error-notice">'.$notify.'</div><div align="center"><img src="packages/core/skins/default/images/buttons/warning.png" width="40" height="40" /></div></td>';
		$txt.='<td id="error_messages_content'.$this->count.'" >';
		if($this->error_messages)
		{
			foreach ($this->error_messages as $name=>$error_messages)
			{
				foreach($error_messages as $error_message)
				{
					if(trim($this->name))
					{
						$txt .= ' + <a class="error-notice link" onclick = "javascript:if(typeof(document.forms.'.$this->name.')!=\'undefined\'){document.forms.'.$this->name.'.namedItem(\''.$name.'\').focus();document.forms.'.$this->name.'.namedItem(\''.$name.'\').style.backgroundColor=\'#FFFFF2\';}">'.$error_message.'</a>';// title="&#7844;n v&#224;o &#273;&#226;y &#273;&#7875; xem v&#7883; tr&#237; x&#7843;y ra l&#7895;i"
					}
					else
					{
						$txt .= $error_message;
					}
					$txt .= '<br>';
				}
			}
		}
		$txt .= '</td></tr></table></div>';
		return $txt;
	}
	//In ra cac thong bao loi neu co
	function ext_error_messages($form_name)
	{
		$this->count = Form::$form_count;
		Form::$form_count++;
		if($this->error_messages)
		{

			foreach ($this->error_messages as $name=>$error_messages)
			{
				foreach($error_messages as $error_message)
				{
					echo $form_name.'.findById(\''.$name.'\').markInvalid(\''.addslashes($error_message).'\');
';
				}
			}

		}
		return $txt;
	}
	function draw()
	{
		
	}
	//Gan lai $current
	//Goi ham draw()
	function on_draw()
	{
		$last_form = &Form::$current;
		Form::$current = &$this;
		$this->draw();
		Form::$current=&$last_form;
	}
	function link_css($file_name)
	{
		if(strpos(Portal::$extra_css,'<LINK rel="stylesheet" href="'.$file_name.'?v='.VERSION.'" type="text/css">')===false)
		{
			Portal::$extra_css .= '<LINK rel="stylesheet" href="'.$file_name.'?v='.VERSION.'" type="text/css">
';
		}
	}
	function link_js($file_name,$version=true)
	{
		if($version)
		{
			if(strpos(Portal::$extra_js,'<script type="text/javascript" src="'.$file_name.'?v='.VERSION.'"></script>')===false)
			{
				Portal::$extra_js .= '<script type="text/javascript" src="'.$file_name.'?v='.VERSION.'"></script>
';
			}
		}
		else
		{
			if(strpos(Portal::$extra_js,'<script type="text/javascript" src="'.$file_name.'"></script>')===false)
			{
				Portal::$extra_js .= '<script type="text/javascript" src="'.$file_name.'"></script>
';
			}
		}
	}
	function add_footer_js_content($content)
	{
		Portal::$footer_js .= $content;
	}
	function auto_refresh($time, $url)
	{
		Portal::$extra_header .= '<META HTTP-EQUIV="Refresh" CONTENT="'.$time.'; URL='.$url.'">';
	}
	public static function get_module_id($name){
		if($row = DB::fetch('SELECT id,name FROM module WHERE name = \''.$name.'\'')){
			return $row['id'];
		}else{
			return false;
		}
	}
}
Form::$current=&System::$false;
?>