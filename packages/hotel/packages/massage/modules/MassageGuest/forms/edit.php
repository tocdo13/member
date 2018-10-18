<?php
class EditMassageGuestForm extends Form
{
	function EditMassageGuestForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->add('full_name',new TextType(true,'miss_name',0,255));
        $this->add('code',new TextType(true,'miss_code',0,255));
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function on_submit(){
		if($this->check()){
		    $description = '';  
			$array = array(
				'code'=>strtoupper(Url::get('code')),		
				'full_name',
				'gender',
				'phone',
				'address',
				'email',
				'category',
				'note',
				'portal_id'=>PORTAL_ID
			);
            if(DB::exists('Select * from MASSAGE_GUEST where portal_id = \''.PORTAL_ID.'\' and upper(code) = \''.strtoupper(Url::get('code')).'\' '))
			{
   			  if(Url::get('cmd')!='edit'){
                    $this->error('duplicate_code','duplicate_code');
                    return false;
                }else{// la edit 
                    if(DB::exists('Select * from MASSAGE_GUEST where portal_id = \''.PORTAL_ID.'\' and upper(code) = \''.strtoupper(Url::get('code')).'\' and id !='.Url::get('id'))){
                        $this->error('duplicate_code','duplicate_code');
                        return false;    
                    }
                }
			}
            if(Url::get('cmd')=='edit'){
                $log_action = 'edit';
                $category = (Url::get('category')== 'COMMON')?'Thông thường':'VIP';
			    $log_title = 'Edit Massage Guest: #'.Url::iget('id').'';
				$description.= '<strong>Staff:</strong><br>';
                $id = Url::iget('id');
                $description.= '[Guest group: '.$category.', Guest id: '.$id.', Guest code: '.Url::get('code').', Guest name: '.Url::get('full_name').', Email: '.Url::get('email').', Address: '.Url::get('address').']<br>'; 
				DB::update('massage_guest',$array,'id='.Url::iget('id'));
			}else{
                $log_action = 'add';
                $category = (Url::get('category')== 'COMMON')?'Thông thường':'VIP';
				$id = DB::insert('massage_guest',$array);
                $log_title = 'Add Massage Guest: #'.$id.'';
				$description.= '<strong>Staff:</strong><br>';
                $description.= '[Guest group: '.$category.', Guest id: '.$id.', Guest code: '.Url::get('code').', Guest name: '.Url::get('full_name').', Email: '.Url::get('email').', Address: '.Url::get('address').']<br>';  
			}
            System::log($log_action,$log_title,$description,$id); 
			Url::redirect_current();
		}
	}
	function draw()
	{
		$this->map = array();
		$this->map['gender_list']=array(
			'1'=>Portal::language('female'),
			'0'=>Portal::language('male'),			
		);
		$this->map['category_list']=array(
			'COMMON'=>Portal::language('COMMON'),
			'VIP'=>Portal::language('VIP')
		);
		$item = MassageGuest::$item;
		if($item){
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
		}
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_MassageGuest'):Portal::language('edit_MassageGuest');
		$this->parse_layout('edit',$this->map);
	}	
}
?>