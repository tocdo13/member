<?php
class EditProvinceForm extends Form
{
    function EditProvinceForm()
    {
        Form::Form('EditProvinceForm');
		$this->add('code',new TextType(true,'code_is_required',0,20));
        $this->add('name',new TextType(true,'name_is_required',0,255));
    }
    
    function on_submit()
    {
        if($this->check())
        {
            $province = array(
                            'name' => Url::get('name'), 
                            );
            if(Url::get('cmd')=='edit')
                DB::update_id('province',$province,Url::get('id'));
            Url::redirect_current(); 
        }
    }
    
    function draw()
    {
        $this->map = array();
        if(Url::get('cmd')=='edit')
        {
            $row = DB::fetch('Select * from province where id = '.Url::get('id'));
            foreach($row as $key=>$value)
                $_REQUEST[$key] = $value;
        }
        $this->map['title'] = Portal::language('edit_province');
        $this->parse_layout('edit',$this->map);
    }
}

?>