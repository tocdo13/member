<?php
class AddProvinceForm extends Form
{
    function AddProvinceForm()
    {
        Form::Form('AddProvinceForm');
        $this->link_js('packages/core/includes/js/multi_items.js');
		$this->add('province.code',new TextType(true,'code_is_required',0,20));
        $this->add('province.code',new UniqueType(true,'code_is_duplicate','province','upper(code)'));
        $this->add('province.code',new UniqueType(true,'code_is_duplicate','province','lower(code)'));
        $this->add('province.name',new TextType(true,'name_is_required',0,255));

    }
    
    function on_submit()
    {
//System::debug($_REQUEST['mi_province']);
                //exit();
        if($this->check())
        {
            if(isset($_REQUEST['mi_province']))
            {
            	foreach($_REQUEST['mi_province'] as $key=>$record)
            	{
                    unset($record['id']);
                    $record['code'] = strtoupper(trim($record['code']));
                    $empty = true;
            		foreach($record as $k=>$record_value)
            		{
            			if($record_value)
            			{
            				$empty = false;
            			}
            		}
                    if(!$empty)
            		{
                        if(!DB::exists('Select * from province where UPPER(code) = \''.$record['code'].'\' '))
                        {
                            $row = array(
                                            'code'=>$record['code'],
                                            'name'=>$record['name'],
                                        );
                            DB::insert('province',$row);
                        }
            		}
            	}
            }
            Url::redirect_current(); 
        }


        
    }
    
    function draw()
    {
        $this->map = array();
        $this->map['title'] = Portal::language('add_province');
        $this->parse_layout('add',$this->map);
    }
}

?>