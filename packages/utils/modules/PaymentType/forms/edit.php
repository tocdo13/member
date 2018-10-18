<?php
class EditPaymentTypeForm extends Form
{
	function EditPaymentTypeForm()
	{
		Form::Form('EditPaymentTypeForm');
		if(URL::get('cmd')=='edit')
		{
			$this->add('id',new IDType(true,'object_not_exists','payment_type'));
		}
		$this->add('def_code',new UniqueType(true,'miss_def_code','payment_type','def_code'));
		$languages = DB::fetch_all('select * from language',false);
		foreach($languages as $language)
		{
			$this->add('name_'.$language['id'],new TextType(true,'invalid_name',0,255)); 
		}
		$this->link_css(Portal::template('core').'/css/tabs/tabpane.css');
	}
	function on_submit()
	{
		if($this->check() and URL::get('confirm_edit'))
		{
			if(URL::get('cmd')=='edit')
			{
				$this->old_value = DB::select('payment_type','id=\''.addslashes($_REQUEST['id']).'\'');
			}
			$this->save_item();
			Url::redirect_current(Module::$current->redirect_parameters+array('just_edited_id'=>$this->id));
	}
	}	
	function draw()
	{
		$languages = DB::fetch_all('select * from language',false);
		$this->init_edit_mode();
		$this->get_parents();
		$this->parse_layout('edit',
			($this->edit_mode?$this->init_value:array())+
			array(
			'languages'=>$languages,
			'parent_id_list'=>String::get_list(PaymentTypeDB::check_categories($this->parents)),
			'parent_id'=>($this->edit_mode?si_parent_id('payment_type',$this->init_value['structure_id']):1)
			)
		);
	}
	function save_item()
	{
			$extra = array();
			$languages = DB::fetch_all('select * from language',false);;
			foreach($languages as $language)
			{
				$extra=$extra+array('name_'.$language['id']=>Url::get('name_'.$language['id'],1)); 
			}
			$new_row = $extra+array('def_code');			
		if(URL::get('cmd')=='edit')
		{
			$this->id = $_REQUEST['id'];
			DB::update_id('payment_type', $new_row,$this->id);
			if($this->old_value['structure_id']!=ID_ROOT)
			{
				if (Url::check(array('parent_id')))
				{
					$parent = DB::select('payment_type',$_REQUEST['parent_id']);					
					if($parent['structure_id']==$this->old_value['structure_id'])
					{
						$this->error('id','invalid_parent');
					}
					else
					{
						require_once 'packages/core/includes/system/si_database.php';
						if(!si_move('payment_type',$this->old_value['structure_id'],$parent['structure_id']))
						{
							$this->error('id','invalid_parent');
						}
					}
				}
			}
		}
		else
		{
			require_once 'packages/core/includes/system/si_database.php';
			if(isset($_REQUEST['parent_id']))
			{	
				$this->id = DB::insert('payment_type', $new_row+array('structure_id'=>si_child('payment_type',structure_id('payment_type',$_REQUEST['parent_id']))));			
			}
			else
			{
				$this->id = DB::insert('payment_type', $new_row+array('structure_id'=>ID_ROOT));		
			}				
		}
	}
	function init_edit_mode()
	{
		if(URL::get('cmd')=='edit' and $this->init_value= DB::fetch('select * from payment_type where id='.intval(URL::sget('id')).''))
		{		
			foreach($this->init_value as $key=>$value)
			{
				if(is_string($value) and !isset($_REQUEST[$key]))
				{
					$_REQUEST[$key] = $value;
				}
			}
			$this->edit_mode = true;
		}
		else
		{
			$this->edit_mode = false;
		}
	}
	function get_parents()
	{
		require_once 'packages/core/includes/system/si_database.php';
		$sql = '
			select 
				id,
				structure_id,
				name_'.Portal::language().' as name  
			from 
			 	payment_type
			order by 
				structure_id
		';
		$this->parents = DB::fetch_all($sql,false);		
	}
}
?>