<?php 
class RealityRoomMapSettingForm extends Form{
    function RealityRoomMapSettingForm(){
        Form::Form('RealityRoomMapSettingForm');
        $this->link_css('packages/hotel/packages/reception/skins/default/css/realityroommap.css');
        $this->link_css('packages/core/includes/js/jquery/ui/jquery.ui.all.css');
    }
    function on_submit(){
        if(isset($_FILES['background_room_map']) and $_FILES['background_room_map']['name']){
            $temp = preg_split('/[\/\\\\]+/', $_FILES['background_room_map']['name']);
            $file_name = $temp[0];
            if (preg_match('/\.(gif|jpg|png)$/i',$file_name)){
				$ext = substr($file_name,strrpos($file_name,'.')+1);
				if(file_exists('resources/interfaces/images/bg_room_map.'.$ext)){
                    unlink('resources/interfaces/images/bg_room_map.'.$ext);
				}
				    move_uploaded_file($_FILES['background_room_map']['tmp_name'],'resources/interfaces/images/bg_room_map.'.$ext);
            }
        }
    }
    function draw(){
        $this->map['room_list'] = RealityRoomMapDB::get_rooms();
        $this->parse_layout('room_setting' , $this->map);
    }
}
?>