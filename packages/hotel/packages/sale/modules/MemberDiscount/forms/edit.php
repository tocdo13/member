<?php
class EditMemberDiscountForm extends Form
{
	function EditMemberDiscountForm()
	{
		Form::Form('EditMemberDiscountForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
	}
	function on_submit()
	{
	   $data = array(
                    'code'=>Url::get('code'),
                    'title'=>Url::get('title'),
                    'description'=>Url::get('description'),
                    'is_parent'=>Url::get('is_parent'),
                    'start_date'=>Date_Time::to_orc_date(Url::get('start_date')),
                    'end_date'=>Date_Time::to_orc_date(Url::get('end_date')),
                    'access_pin_service_code'=>Url::get('access_pin_service'),
                    'num_people'=>Url::get('num_people'),
                    'operator'=>Url::get('operator'),
                    'percent'=>Url::get('percent')
                    );
        $data_old = array();
        if(Url::get('id'))
       {
            $data_old = DB::fetch('SELECT * FROM member_discount WHERE id='.Url::get('id'));
            $key = 'EDIT';
            $id = Url::get('id');
            $data['last_editer'] = User::id();
            $data['last_edit_time'] = time();
            DB::update('member_discount',$data,'id='.Url::get('id'));
       }
       else
       {
            $key = 'ADD';
            $data['code'] = 'GGTV-'.$this->get_code();
            $data['creater'] = User::id();
            $data['create_time'] = time();
            $id = DB::insert('member_discount',$data);
       }
       
       /**log **/
        $this->log_traveller($data,$id,$key,$data_old);
        
        
       if(Url::get('save_stay'))
       {
            Url::redirect('member_discount',array());
                
       }
       else
       {
            Url::redirect('member_discount',array('cmd'=>'edit','id'=>$id));
       }
	}	
	function draw()
	{
        
        $this->map = array(
                        'code'=>'XXXXXXXXXXXXXXXXXXX',
                        'title'=>'',
                        'description'=>'',
                        'is_parent'=>'',
                        'start_date'=>'',
                        'end_date'=>'',
                        'access_pin_service_code'=>'',
                        'num_people'=>'',
                        'operator'=>'=',
                        'percent'=>0
                        );
        if(Url::get('id'))
        {
            $this->map = DB::fetch("SELECT * FROM member_discount WHERE id=".Url::get('id'));
        }
        $_REQUEST += $this->map;
        $this->map['is_parent_list'] = array(''=>Portal::language('all'),'PARENT'=>Portal::language('parent_card'),'SON'=>Portal::language('son_card'));
        $this->map['operator_list'] = array('='=>'=','>'=>'>','>='=>'>=','<'=>'<','<='=>'<=');
        $_REQUEST['start_date'] = Date_time::convert_orc_date_to_date($this->map['start_date'],'/');
        $_REQUEST['end_date'] = Date_time::convert_orc_date_to_date($this->map['end_date'],'/');
        $this->parse_layout('edit',$this->map);
	}
    function get_code()
    {
        $max_code = DB::fetch("SELECT max(id) as max FROM member_discount","max");
        if(!isset($max_code) OR $max_code=='' OR $max_code==0 OR !$max_code)
        {
            $max_code = 1;
        }
        else
        {
            $max_code ++;
        }
        return $max_code;
    }
    function log_traveller($recode,$id,$key,$recode_old)
    {
        if($key=='ADD')
        {
            $description = '';
            foreach($recode as $key=>$value)
            {
                $description .= '<p><b>'.$key.'</b>: '.$value.'</p>';
            }
            $log_title = 'Make Discount '.$recode['code'].' #ID: '.$id;
        }
        else
        {
            $description = '';
            foreach($recode as $key=>$value)
            {
                if($recode_old[$key]!=$value)
                $description .= '<p><b>'.$key.'</b>: '.$recode_old[$key].'<b> change to </b>:'.$value.'</p>';
            }
            $log_title = 'Update Discount '.$recode['code'].' #ID: '.$id;
        }
        if($description !='')
        System::log($key,$log_title,$description,$id);
    }
}
?>
