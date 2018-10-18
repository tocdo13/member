<?php
class EditProductCategoryForm extends Form
{
	function EditProductCategoryForm()
	{
		Form::Form('EditProductCategoryForm');
		$this->add('name',new TextType(true,'invalid_name',0,2000)); 
        $this->add('product_category.code',new TextType(false,'miss_code',0,255));		
	}
	function on_submit()
	{
	    //System::debug($_REQUEST);exit(); 
		if($this->check() and URL::get('confirm_edit'))
		{
			if(URL::get('cmd')=='edit')
			{
				$this->old_value = DB::select(PRODUCT_CATEGORY,'id=\''.addslashes($_REQUEST['id']).'\'');
			}          
			$this->save_item();
			Url::redirect_current(Module::$current->redirect_parameters+array('just_edited_id'=>$this->id));                   	   
		}
     
	}	
	function draw()
	{
		$this->init_edit_mode();
		$this->get_parents();
		$this->init_database_field_select();
		$this->parse_layout('edit',
			($this->edit_mode?$this->init_value:array())+
			array(
			'parent_id_list'=>String::get_list(ProductCategoryDB::check_categories($this->parents)),
			'parent_id'=>($this->edit_mode?si_parent_id(PRODUCT_CATEGORY,$this->init_value['structure_id']):1),
			'type_list'=>$this->type_list
			)
		);
	}
	function save_item()
	{
		require_once 'packages/core/includes/utils/upload_file.php';
		//update_upload_file('icon_url',str_replace('#','',PORTAL_ID));
		$extra = array();
		$extra=$extra+array('name'=>Url::get('name'),'name_en'=>Url::get('name_en'),'position'=>Url::get('position')); 
		$new_row = $extra+array('code'=>trim(strtoupper(Url::get('code'))));
   
		require_once 'packages/core/includes/utils/vn_code.php';
		if(URL::get('cmd')=='edit')
		{
			$this->id = $_REQUEST['id'];
			DB::update_id(PRODUCT_CATEGORY, $new_row,$this->id);
            
			if($this->old_value['structure_id']!=ID_ROOT)
			{
				if (Url::check(array('parent_id')))
				{
					$parent = DB::select(PRODUCT_CATEGORY,$_REQUEST['parent_id']);	
                    //System::debug($parent); exit();				
					if($parent['structure_id']==$this->old_value['structure_id'])   
					{
						$this->error('id','invalid_parent'); 
					}
					else
					{
						require_once 'packages/core/includes/system/si_database.php';
						if(!si_move(PRODUCT_CATEGORY,$this->old_value['structure_id'],$parent['structure_id']))
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
			{   //  add dieu kien 
               if(DB::exists('select id,code from PRODUCT_CATEGORY where code =\''.strtoupper(Url::get('code')).'\'' ))
               {
                    echo '<script>';
                    echo 'alert("Mã sản phẩm đã tồn tại! Hãy nhập mã mới!")';
                    echo '</script>';
                    //return;
                    exit();
                }
                else
                { 
                    $this->id = DB::insert(PRODUCT_CATEGORY, $new_row+array('structure_id'=>si_child(PRODUCT_CATEGORY,structure_id(PRODUCT_CATEGORY,$_REQUEST['parent_id'])))); 
                    //System::debug($new_row);exit();
                }                    				
			}
			else
			{
                $this->id = DB::insert(PRODUCT_CATEGORY, $new_row+array('structure_id'=>ID_ROOT));	
			}
       				
	  }
  
	}
	function init_edit_mode()
	{
		if(URL::get('cmd')=='edit' and $this->init_value= DB::fetch('select * from '.PRODUCT_CATEGORY.' where id='.intval(URL::sget('id')).''))
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
        //System::debug($this->edit_mode);
	}
	function get_parents()
	{
		require_once 'packages/core/includes/system/si_database.php';
		$sql = '
			select 
				id,
				structure_id
				,name
			from 
			 	'.PRODUCT_CATEGORY.'
			where 
				1>0
			order by 
				structure_id
		';
		$this->parents = DB::fetch_all($sql,false);		
	}
	function init_database_field_select()
	{
		$sql = 'select
					type.id,
					type.title_'.Portal::language().' as name
				from 
					portal_type,type
				where
					 type.typeoftype=\'ITEM\' 
					 and  portal_type.portal_id=\''.PORTAL_ID.'\'
					 and type.id(+)=portal_type.type
				'
			;
			$this->type_list = String::get_list(DB::fetch_all($sql,false)); 
	}	
}
?>