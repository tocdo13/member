<?php
class EditMassageStaffForm extends Form
{
	function EditMassageStaffForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->add('full_name',new TextType(true,'miss_full_name',0,255));
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
	}
	function on_submit(){	   
		if($this->check()){
		    $description = '';  
			$array = array(
				'full_name',
				'birth_date'=>Date_Time::to_orc_date(Url::get('birth_date')),
				'gender',
				'native',//Nguyen quan
				'address',
				'email',
				'phone',
				'date_in'=>Date_Time::to_orc_date(Url::get('date_in')),
				'date_out'=>Date_Time::to_orc_date(Url::get('date_out')),
				'marrital_status'=>Url::get('marrital_status'),
				'description',
                'status'=>Url::get('status'),
				'portal_id'=>PORTAL_ID
			);
			if(Url::get('cmd')=='edit')
            {
                $log_action = 'edit';
                $gender = (Url::get('gender')== '1')?'Nữ':'Nam';                
			    $log_title = 'Edit Massage Staff: #'.Url::iget('id').'';
				$description.= '<strong>Staff:</strong><br>';
                $id = Url::iget('id');
                $description.= '[Staff id: '.$id.', Staff name: '.Url::get('full_name').', Gender: '.$gender.', Birth: '.Url::get('birth_date').', Address: '.Url::get('address').']<br>'; 
				DB::update('massage_staff',$array,'id='.Url::iget('id'));
			}
            else
            {
                $log_action = 'add';
				$id = DB::insert('massage_staff',$array);
                $log_title = 'Add Massage Staff: #'.$id.'';
				$description.= '<strong>Staff:</strong><br>';
                $gender = (Url::get('gender')== '1')?'Nữ':'Nam';                
                $description.= '[Staff id: '.$id.', Staff name: '.Url::get('full_name').', Gender: '.$gender.', Birth: '.Url::get('birth_date').', Address: '.Url::get('address').']<br>'; 
            } 
            System::log($log_action,$log_title,$description,$id);
			Url::redirect_current();
		}
	}
	function draw()
	{
		$this->map = array();
		$this->map['gender_id_list']=array(
			'1'=>Portal::language('female'),
			'0'=>Portal::language('male'),			
		);
		$item = MassageStaff::$item;
		if($item){
			$item['date_in'] = Date_Time::convert_orc_date_to_date($item['date_in'],'/');
			$item['date_out'] = Date_Time::convert_orc_date_to_date($item['date_out'],'/');
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
		}
        $this->map['status_list'] = array('ready'=>Portal::language('ready'),
                                            'off'=>Portal::language('off')			
                                        	);
        		
        $this->map['marial_id_list'] = array(
                                        '0' => Portal::language('married'),
                                        '1' => Portal::language('not_married')
        );        
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_MassageStaff'):Portal::language('edit_MassageStaff');
		$this->parse_layout('edit',$this->map);
	}	
}
?>