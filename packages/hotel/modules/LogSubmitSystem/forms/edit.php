<?php
class LogSubmitSystemForm extends Form{
	function LogSubmitSystemForm()
    {
		Form::Form('AreaManageForm');
	}
    function on_submit(){
        
    }
	function draw()
    {
		$this->map = array();
        $this->map['start_date'] = $_REQUEST['start_date'] = Url::get('start_date')?Url::get('start_date'):date('d/m/Y');
        $this->map['end_date'] = $_REQUEST['end_date'] = Url::get('end_date')?Url::get('end_date'):date('d/m/Y');
        $this->map['start_time'] = $_REQUEST['start_time'] = Url::get('start_time')?Url::get('start_time'):'00:00';
        $this->map['end_time'] = $_REQUEST['end_time'] = Url::get('end_time')?Url::get('end_time'):'23:59';
        
        $start_time = Date_Time::to_time($this->map['start_date'])+$this->calc_time($this->map['start_time']);
        $end_time = Date_Time::to_time($this->map['end_date'])+$this->calc_time($this->map['end_time']);
        
 	    $items = array();
        $stt=0;
        if(is_dir('packages/hotel/log')){
            $log_system = scandir('packages/hotel/log');
            for($i=0;$i<sizeof($log_system);$i++){
                if($i>1){
                    if(strpos($log_system[$i],'.php')){
                        $file_log_name = str_replace('.php','',$log_system[$i]);
                        if($file_log_name!='index'){
                            $file_time = str_replace('log_user_','',$file_log_name);
                            if(is_numeric($file_time) and $start_time<=$file_time and $end_time>=$file_time){
                                require_once 'packages/hotel/log/'.$log_system[$i];
                                if(!isset($items[$file_time])){
                                    $stt++;
                                    $items[$file_time]['id'] = $file_time;
                                    $items[$file_time]['stt'] = $stt;
                                    $items[$file_time]['time'] = $file_time;
                                    $items[$file_time]['date'] = date('H:i d/m/Y',$file_time);
                                    $items[$file_time]['user_id'] = '';
                                    $items[$file_time]['data_json'] = array();
                                    $items[$file_time]['page'] = '';
                                }
                                $items[$file_time]['user_id'] = $user_log;
                            }
                        }
                    }elseif(strpos($log_system[$i],'.json')){
                        $file_log_name = str_replace('.json','',$log_system[$i]);
                        if($file_log_name!='index'){
                            $file_time = str_replace('log_submit_','',$file_log_name);
                            if(is_numeric($file_time) and $start_time<=$file_time and $end_time>=$file_time){
                                if(!isset($items[$file_time])){
                                    $stt++;
                                    $items[$file_time]['id'] = $file_time;
                                    $items[$file_time]['stt'] = $stt;
                                    $items[$file_time]['time'] = $file_time;
                                    $items[$file_time]['date'] = date('H:i d/m/Y',$file_time);
                                    $items[$file_time]['user_id'] = '';
                                    $items[$file_time]['data_json'] = array();
                                    $items[$file_time]['page'] = '';
                                }
                                $data_log = json_decode(file_get_contents('packages/hotel/log/'.$log_system[$i]), true);
                                $items[$file_time]['data_json'] = $data_log;
                                if(isset($data_log['page']))
                                    $items[$file_time]['page'] = $data_log['page'];
                            }
                        }
                    }
                    
                }
           }
        }   
        $this->map['items'] = $items;
        //System::debug($items); die;
		$this->parse_layout('edit',$this->map);
	}
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
}
?>