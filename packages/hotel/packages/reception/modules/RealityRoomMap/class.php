<?php 
class RealityRoomMap extends Module{
    function RealityRoomMap($row){
        Module::Module($row);
        if(User::can_view()){
            require_once 'db.php';
            switch(Url::get('cmd')){
                case 'update_position' :
                    $this->update_position();
                    break;
                case 'update_height_room_map':
                    $this->update_height_room_map();
                    break;
                case 'update_position_all_room' :
                    $this->update_all_room_position();
                    break;
                case 'room_setting' :
                    $this->room_setting();
                    break;
                case 'update_align_to_grid' : 
                    $this->update_align_to_grid();
                    break;
                default : 
                    require_once('forms/list.php');
                    $this->add_form(new RealityRoomMapForm());
                    break;
            }
        }else{
            Url::access_denied();
        }
    }
    function room_setting(){
        if(User::is_admin()){
            require_once('forms/room_setting.php');
            $this->add_form(new RealityRoomMapSettingForm());
        }
    }
    function update_height_room_map(){
        if(User::is_admin() && Url::get('height')){
            if(file_exists('cache/config/logo.php')){
                $file_contents = file_get_contents('cache/config/logo.php');
                $file_contents = explode('<?php',$file_contents);
                $file_content = explode(';' , $file_contents[1]);
                foreach($file_content as $key => $value){
                    if(preg_match('/HEIGHT_ROOM_MAP/' , $value)){
                        $file_content[$key] =  str_replace(HEIGHT_ROOM_MAP , Url::get('height') , $value);
                    }
                }
                $file_contents = '<?php '.implode(';',$file_content);
                require 'install_lib.php';
                save_file('logo',$file_contents);
            }
        }
        exit();
    }
    function update_all_room_position(){
        if(User::is_admin() && Url::get('room_list')){
            $strings = explode('|' , Url::get('room_list'));
            foreach($strings as $key => $string){
                $string = explode(',' , $string);
                DB::update('room' , array('left' => $string[1] , 'top' => $string[2]) , ' id = \''.$string[0].'\'');
            } 
        }
        exit();
    }
    function update_position(){
        if(User::is_admin()){
            if(Url::get('room_id')){
                if(Url::get('left') && Url::get('top')){
                    $query = 'UPDATE room SET left = \''.Url::get('left').'\' ,top = \''.Url::get('top').'\' WHERE id = \''.Url::get('room_id').'\'' ; 
                }else{
                    $query = 'UPDATE room SET left = NULL , top = NULL WHERE id = \''.Url::get('room_id').'\'';
                } 
                DB::query($query);
            }
        }   
        exit();
    } 
    function update_align_to_grid(){
        if(User::is_admin() && Url::get('grid')){
            if(file_exists('cache/config/logo.php')){
                $file_contents = file_get_contents('cache/config/logo.php');
                $file_contents = explode('<?php',$file_contents);
                $file_content = explode(';' , $file_contents[1]);
                foreach($file_content as $key => $value){
                    if(preg_match('/ALIGN_TO_GRID/' , $value)){
                        $file_content[$key] =  str_replace(ALIGN_TO_GRID , Url::get('grid') , $value);
                    }
                }
                $file_contents = '<?php '.implode(';',$file_content);
                require 'install_lib.php';
                save_file('logo',$file_contents);
            }
        }
    }
}
?>