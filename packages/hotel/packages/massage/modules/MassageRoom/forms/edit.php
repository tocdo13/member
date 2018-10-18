<?php
class EditMassageRoomForm extends Form
{
	function EditMassageRoomForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->add('category',new TextType(true,'miss_room_level_name',0,255));		
		$this->add('name',new TextType(true,'miss_name',0,255));
	}
	function on_submit(){
		if($this->check()){
		    $description = '';  
			$array = array(
				'name',
				'POSITION',
				'category',
                'area_id',
				'massage_room.portal_id'=>PORTAL_ID
			);
			if(Url::get('cmd')=='edit'){
			     $log_action = 'edit';
                if(Url::get('area_id')== 1)
                {
                    $area = 'FU';
                }
                if(Url::get('area_id')== 2)
                {
                    $area = 'FA';
                }
                if(Url::get('area_id')== 3)
                {
                    $area = 'EL';
                }
			    $log_title = 'Edit Massage Room: #'.Url::iget('id').'';
				$description.= '<strong>Room:</strong><br>';
                $id = Url::iget('id');
                $description.= '[Mass Room Level: '.Url::get('category').', Mass Room No: '.Url::get('name').',  Mass Room Position: '.Url::get('POSITION').', Area: '.$area.']<br>'; 
				DB::update('massage_room',$array,'id='.Url::iget('id'));
			}else{
			    $log_action = 'add';
                if(Url::get('area_id')== 1)
                {
                    $area = 'FU';
                }
                if(Url::get('area_id')== 2)
                {
                    $area = 'FA';
                }
                if(Url::get('area_id')== 3)
                {
                    $area = 'EL';
                } 
				$id = DB::insert('massage_room',$array);
                $log_title = 'Add Massage Room: #'.$id.'';
				$description.= '<strong>Room:</strong><br>';
                $description.= '[Mass Room Level: '.Url::get('category').', Mass Room No: '.Url::get('name').',  Mass Room Position: '.Url::get('POSITION').', Area: '.$area.']<br>';
			}
            System::log($log_action,$log_title,$description,$id);  
			Url::redirect_current();
		}
	}
	function draw()
	{
		$this->map = array();
		$item = MassageRoom::$item;
		if($item){
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
		}
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_room'):Portal::language('edit_room');
	
    $area_name = DB::select_all('area_group');
		$area_name_options = '<option value="">'.Portal::language('choose_area_name').'</option>';
		foreach($area_name as $key=>$value)
		{
			$area_name_options.='<option value="'.$value['id'].'">'.$value['name_1'].'</option>';
		}
        $this->map['area'] = $area_name_options;
    
    	$this->parse_layout('edit',$this->map);
	}	
}
?>