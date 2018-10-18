<?php
class AddManageDepartmentForm extends Form
{
    function AddManageDepartmentForm()
    {
        Form::Form('AddManageDepartmentForm');
        //check input select = 2 c�ch
        //$this->add('portal_id',new TextType(true,'invalid_type',0,16));
		$this->add('code',new TextType(true,'code_is_required',0,20));
        $this->add('name_1',new TextType(true,'name_VN_is_required',0,255));
        $this->add('name_2',new TextType(true,'name_EN_is_required',0,255));
        //$this->add('portal_id',new SelectType(true,'invalid_portal_name',array(String::get_list(Portal::get_portal_list()))));
        /*
		$this->add('product.id',new UniqueType(true,'duplicate_product_id','product','id'));
		$this->add('type',new SelectType(false,'invalid_type',array('GOODS'=>'GOODS','PRODUCT'=>'PRODUCT','MATERIAL'=>'MATERIAL','EQUIPMENT'=>'EQUIPMENT','SERVICE'=>'SERVICE','TOOL'=>'TOOL')));
		$languages = DB::select_all('language');
		foreach($languages as $language)
		{
			$this->add('product.name_'.$language['id'],new TextType(true,'invalid_name_'.$language['id'],0,2000)); 
		}
		$this->add('product.unit_id',new TextType(true,'invalid_unit_id',0,255));*/
    }
    
    function on_submit()
    {
        if($this->check())
        {
            $department = array(
                            'name_1' => Url::get('name_1'),
                            'name_2' => Url::get('name_2'),
                            'area_id' => Url::get('area_id'),
                            'parent_id' => Url::get('parent_id'),  
							'acc_revenue_code' => Url::get('acc_revenue_code'),  
							'acc_deposit_code' => Url::get('acc_deposit_code'),
                            'mice_use' => Url::get('mice_use')
                            );
                            
            if(Url::get('cmd')=='add')
            {
                $department['code'] = strtoupper(Url::get('code'));
                //System::debug($department['code']);
                //exit();
                if(DB::exists('Select * from department where code = \''.Url::get('code').'\''))
                {
                    $this->error('duplicate_department_code','duplicate_department_code');   
                    return;
                } 
                else
                {
                    DB::insert('department',$department);
                    //Kiem tra neu cha cua no da dc acvite thi add vao bang active_dep
                    //lay ra ma cua cha
                    $parent_code = DB::fetch('Select code from department where id = '.Url::get('parent_id'),'code');
                    //Lay cac portal
                    $portal_list = Portal::get_portal_list();
                    if($parent_code)
                    {
                        foreach($portal_list as $key=>$value)
                        {
                            //kiem tra xem parent code da dc acvite chua, neu roi thi active luon ca child
                            if(DB::exists('Select * from portal_department where department_code = \''.$parent_code.'\' and portal_id = \''.$key.'\''))
                            {
                                DB::insert('portal_department',array('department_code'=>$department['code'], 'portal_id'=>$key));
                            } 
                        }
                    }
                }
                    
            }
            else
            {
                if(Url::get('cmd')=='edit')
                    DB::update_id('department',$department,Url::get('id'));
            }
            Url::redirect('manage_department'); 
        }


        
    }
    
    function draw()
    {
        require_once 'packages/hotel/packages/warehousing/includes/php/warehouse.php';
        $this->map = array();
        //Lay danh sach cac kho
		//$warehouses = get_warehouse();
        //$this->map['warehouse_id_list'] = array(''=>Portal::language('select_warehouse'))+String::get_list($warehouses);
        
        //$portal_list = Portal::get_portal_list();
        //System::debug($portal_list);
        //list area
        $db_items = DB::select_all('area_group','portal_id = \''.PORTAL_ID.'\'','name_'.Portal::language());
		$area_options = '';
		foreach($db_items as $item)
		{
			$area_options .= '<option value="'.$item['id'].'">'.$item['name_'.Portal::language()].'</option>';
		}
        $this->map['area_options'] = $area_options;	
        $this->map['mice_use_option'] = '<option value="0">'.Portal::language('not').'</option>'.'<option value="1">'.Portal::language('yes').'</option>';
        if(Url::get('cmd')=='edit')
        {
            $row = DB::fetch('Select * from department where id = '.Url::get('id'));
            foreach($row as $key=>$value)
                $_REQUEST[$key] = $value;
            //System::debug($_REQUEST);
        }
        if(Url::get('cmd')=='add')
            $this->map['title'] = Portal::language('add_department');
        if(Url::get('cmd')=='edit')
            $this->map['title'] = Portal::language('edit_department');
        $this->map['parent_id_list'] = array('0'=>Portal::language('none'))+String::get_list(DB::fetch_all('Select * from department where parent_id = 0'));                
        $languages = DB::select_all('language');
        $this->parse_layout('add',$this->map+array('languages'=>$languages,));
    }
}

?>