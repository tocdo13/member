<?php
/******************************
COPY RIGHT BY NYN PORTAL - TCV
WRITTEN BY vuonggialong
******************************/
class module
{
	var $forms = array();
	var $data = false;
	static $current = false;
	static $blocks = array();
	function module($row)
	{
		module::$current=&$this;
		$this->data = $row;
		module::invoke_event('ONLOAD',$this,System::$false);
	}
	static function block_id()
	{
		return module::$current->data['id'];
	}
	function add_form($form)
	{
		$this->forms[]=$form;
	}
	function submit()
	{
		module::invoke_event('ONSUBMIT',$this,System::$false);
		module::$current=&$this;
		$submit=$this->on_submit();
		module::invoke_event('ONENDSUBMIT',$this,System::$false);
		module::$current=&System::$false;
	}
	function on_submit()
	{
		if($this->forms)
		{
		    /** Manh them de log moi thao tac */
            if( (isset($_REQUEST['page']) and $_REQUEST['page']!='module') or (!isset($_REQUEST['page'])) )
            {
    		    if(!is_dir('packages/hotel/log')){
    		      mkdir('packages/hotel/log');
                  $handler_log_submit_index = fopen('packages/hotel/log/index.php','w+');
                	fwrite($handler_log_submit_index,'404 page not poun!');
                	fclose($handler_log_submit_index); 
    		    }
                $time_user_log_submit = time();
                $handler_user_log_submit = fopen('packages/hotel/log/log_user_'.$time_user_log_submit.'.php','w+');
                $content_log_submit = '<?php $user_log=\''.User::id().'\'; ?>';
            	fwrite($handler_user_log_submit,$content_log_submit);
            	fclose($handler_user_log_submit); 
                $handler_data_log_submit = fopen('packages/hotel/log/log_submit_'.$time_user_log_submit.'.json','w+');
            	fwrite($handler_data_log_submit,json_encode($_REQUEST));
            	fclose($handler_data_log_submit); 
            }
            /** end Manh **/
			for($i=0;$i<sizeof($this->forms);$i++)
			{
				if($this->forms[$i]->on_submit())
				{
					return true;
				}
			}
		}
	}	
	function draw()
	{
		if($this->forms)
		{
			foreach($this->forms as $form)
			{
				$form->on_draw();
			}
		}
	}
	function on_draw()
	{
		module::invoke_event('ONDRAW',$this,System::$false);
		module::$current=&$this;
		
		echo '<div id="module_'.$this->data['id'].'">';
		if(User::can_admin(MODULE_MODULEADMIN) and !isset($this->data['module']['use_dbclick']) and !defined('xpath'))
		{
			echo '<script type="text/javascript">make_module_title('.$this->data['module']['id'].',"'.$this->data['module']['name'].'","'.$this->data['module']['type'].'",'.$this->data['id'].',"'.$this->data['region'].'","'.$this->data['page_id'].'","'.Portal::$page_gen_time->get_timer().'","'.$this->data['container_id'].'");</script>';
		}
		$this->draw();
		if(User::can_admin())
		{
			echo '<script type="text/javascript">echo("<"+"/div>");</script>';
		}
		echo '</div>';
		module::invoke_event('ONENDDRAW',$this,System::$false);
		module::$current=&System::$false;
		
	}
	function get_setting($name,$default=false, $block_id = false)
	{
		if($block_id)
		{
			if($block_id<1000)
			{
				if($block=DB::select('BLOCK','ID=\''.intval(URL::sget('block_id')).'\''))
				{
					return DB::fetch('SELECT VALUE FROM block_SETTING WHERE block_id=\''.intval(URL::sget('block_id')).'\' and SETTING_id=\''.$block['module_id'].'_'.$name.'\'','VALUE',$default);
				}
			}
			else
			{
				if($block=DB::select('BLOCK','ID=\''.intval($block_id).'\''))
				{
					return DB::fetch('SELECT VALUE FROM block_SETTING WHERE block_id=\''.intval($block_id).'\' and SETTING_id=\''.$block['module_id'].'_'.$name.'\'','VALUE',$default);
				}
			}
		}
		return isset(module::$current->data['settings'][module::$current->data['module_id'].'_'.$name])?module::$current->data['settings'][module::$current->data['module_id'].'_'.$name]:$default;
	}
	function set_setting($setting_id,$value)
	{
		if(isset($this) and isset($this->data['id']))
		{
			$block_id = $this->data['id'];
			$module_id = $this->data['module_id'];
			$page_id = $this->data['page_id'];
		}
		else
		{
			$block_id = module::block_id();
			$module_id = module::$current->data['module_id'];
			$page_id = module::$current->data['page_id'];
		}
		if($setting = DB::select('block_SETTING','block_id=\''.$block_id.'\' and SETTING_id=\''.$module_id.'_'.$setting_id.'\''))
		{
			DB::update('block_SETTING',array('VALUE'=>$value),'ID=\''.$setting['id'].'\'');
		}
		else
		{
			DB::insert('block_SETTING',array('SETTING_id'=>$module_id.'_'.$setting_id,'VALUE'=>$value,'block_id'=>$block_id));
		}
		require_once 'packages/core/includes/portal/update_page.php';
		update_page($page_id);
	}
	function get_help_topic_id()
	{
		if(isset(module::$current->data['help_topics'][URL::get('cmd')]))
		{
			return module::$current->data['help_topics'][URL::get('cmd')];
		}
		else
		if(isset(module::$current->data['help_topics']['']))
		{
			return module::$current->data['help_topics'][''];
		}
		else
		{
			return 1;
		}
	}
	static function get_sub_regions($region)
	{
		$last_module = &module::$current;
		$block_id = module::block_id();
		global $blocks;
		foreach($blocks as $id => &$block)
		{
			if($block['container_id'] == $block_id and $block['region'] == $region)
			{
				if($block['module']['type'] == 'HTML')
				{
					module::generate_module_html($block);
				}
				else
				if($block['module']['type'] == 'CONTENT')
				{
					module::generate_module_content($block);
				}
				else
				{
					$block['object']->on_draw();
				}
			}
		}
		module::$current = &$last_module;
	}
	static function generate_module_html(&$block)
	{
		$block_id = $block['id'];
		module::$blocks[$block_id]['object'] = new module(module::$blocks[$block_id]);
		$last = &module::$current;
		module::$current=&module::$blocks[$block_id]['object'];
		module::invoke_event('ONDRAW',$this);
		if(User::can_admin(MODULE_MODULEADMIN) and !module::$blocks[$block_id]['object']->data['module']['use_dbclick']and !defined('xpath'))
		{
			echo '<script type="text/javascript">make_module_title('.module::$blocks[$block_id]['object']->data['module']['id'].',"'.module::$blocks[$block_id]['object']->data['module']['name'].'","'.module::$blocks[$block_id]['object']->data['module']['type'].'",'.module::$blocks[$block_id]['object']->data['id'].',"'.module::$blocks[$block_id]['object']->data['region'].'","'.module::$blocks[$block_id]['object']->data['page_id'].'","'.Portal::$page_gen_time->get_timer().'","'.module::$blocks[$block_id]['object']->data['container_id'].'");</script>';
		}
		
		module::convert_language($block['module']['LAYOUT']);
		
		if(User::can_admin(MODULE_MODULEADMIN) and !module::$blocks[$block_id]['object']->data['module']['use_dbclick']and !defined('xpath'))
		{
			echo '<script type="text/javascript">echo("<"+"/div>");</script>';
		}
		module::invoke_event('ONENDDRAW',$this);
		module::$current=&$last;

	}
	
	static function generate_module_content(&$block)
	{
		$block_id = $block['id'];
		module::$blocks[$block_id]['object'] = new module(module::$blocks[$block_id]);
		$last = module::$current;
		module::$current = &module::$blocks[$block_id]['object'];
		module::invoke_event('ONDRAW',$this,System::$false);
		if(User::can_admin(MODULE_MODULEADMIN) and !module::$blocks[$block_id]['object']->data['module']['use_dbclick']and !defined('xpath'))
		{
			echo '<script type="text/javascript">make_module_title('.module::$blocks[$block_id]['object']->data['module']['id'].',"'.module::$blocks[$block_id]['object']->data['module']['name'].'","'.module::$blocks[$block_id]['object']->data['module']['type'].'",'.module::$blocks[$block_id]['object']->data['id'].',"'.module::$blocks[$block_id]['object']->data['region'].'","'.module::$blocks[$block_id]['object']->data['page_id'].'","'.Portal::$page_gen_time->get_timer().'","'.module::$blocks[$block_id]['object']->data['container_id'].'");</script>';
		}
		require_once 'packages/core/includes/portal/generate_layout.php';
		$generate_layout = new GenerateLayout($block['module']['LAYOUT']);
		$layout = str_replace('$this->map','$map',$generate_layout->generate_text($generate_layout->synchronize())); 
		//if(!$row['is_cached'])
		{
			$map = array('CONTENT_NAME'=>''.$module_data['name'].'');
			$ok=true;
			eval($block['module']['code']);
			if($ok){
				module::convert_language($layout);
			}
		}

		if(User::can_admin(MODULE_MODULEADMIN) and !module::$blocks[$block_id]['object']->data['module']['use_dbclick']and !defined('xpath'))
		{
			echo '<script type="text/javascript">echo("<"+"/div>");</script>';
		}
		module::invoke_event('ONENDDRAW',$this,System::$false);
		module::$current=&$last;
	}
	function convert_language($layout)
	{
		eval('?>'. preg_replace('/\[\[\.(\w+)\.\]\]/','<?php echo Portal::language(\'\\1\');?>',$layout).'<?php ');
	}
	
	function make_ext_region($region, $container_id=0, $baseCls=false)
	{
		global $blocks;
		$first = true;
		$st = '';
		foreach($blocks as $block)
		{
			if($block['region'] == $region and $block['container_id'] == $container_id)
			{
				if(!$first)
				{
					$st .= ',';
				}
				$first = false;

				$st .= '{
					'.($block['name']?'title: \''.($block['name']?$block['name']:$block['module']['name']).'\',
					tools: tools,
					':'header:false,border:false,bodyBorder:false,frame:false,footer:false,').'
					
					'.($baseCls?'baseCls:\'simple-panel\',':'').'
					contentEl :\'module_'.$block['id'].'\'
                }';
			}
		}
		if($first)
		{
			$st = 'html:\'\'';
		}
		else
		{
			$st = 'items:['.$st.']';
		}
		echo $st;
	}
	static function invoke_event($event, &$module, &$params)
	{
		global $plugins;
		if($plugins)
		{
			foreach($plugins as $plugin)
			{
				if($plugin['action'] == $event and (($module === System::$false) or ($module->data['module_id'] == $plugin['ACTION_module_id'])))
				{
					if(!class_exists($plugin['name']))
					{
						require_once $plugin['PATH'].'class.php';
						eval($plugin['name'].'::init($module,$params);');
					}
					eval($plugin['name'].'::run($module,$params);');
				}
			}
			if($event == 'ONUNLOAD' and $module === System::$false)
			{
				if(class_exists($plugin['name']))
				{
					eval($plugin['name'].'::finish($module,$params);');
				}
			}
		}
	}
	
}
class Plugin
{
	static function init(&$module,&$params){
	}
	static function run(&$module,&$params){
	}
	static function finish(&$module,&$params){
	}
}
?>