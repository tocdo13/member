<?php
class UpdateVersionForm extends Form
{
	function UpdateVersionForm()
	{
		Form::Form('UpdateVersionForm');
		$this->link_css(Portal::template('hotel').'/css/setting.css');
	}
	function on_submit()
	{
        if(Url::get('act')=='RESTORE' and isset($_REQUEST['selected_ids']) and User::id()=='developer05'){
            foreach($_REQUEST['selected_ids'] as $key=>$value){
                
                $table = strtoupper($value);
                
                
                /** PARTY - ACCOUNT - SESSION_USER **/
                if($table=='PARTY'){
                    DB::query('delete '.$table.' where USER_ID!=\'#default\' and USER_ID!=\'developer05\'');
                }elseif($table=='ACCOUNT'){
                    DB::query('delete '.$table.' where ID!=\'#default\' and ID!=\'developer05\' and ID!=\'3\' and ID!=\'4\' and ID!=\'GUESTGROUP\' and ID!=\'USER\'');
                }elseif($table=='SESSION_USER'){
                    DB::query('delete '.$table.' where USER_ID!=\'developer05\'');
                }else{
                    DB::query('truncate table '.$table);
                }
                /** PARTY **/
                
                /** SITEMINDER_ROOM_RATE **/
                if($table=='SITEMINDER_ROOM_RATE'){
                    DB::insert('SITEMINDER_ROOM_RATE',array('rate_plan_code'=>'ROOT','rate_name'=>'ROOT','structure_id'=>1000000000000000000));
                }
                /** SITEMINDER_ROOM_RATE **/
                
                /** LOG **/
                if($table=='LOG'){
                    if(is_dir('packages/user/modules/Log/file')){
                        $log_file = scandir('packages/user/modules/Log/file');
                        for($i_file=0;$i_file<sizeof($log_file);$i_file++){
                            if($i_file>1){
                                unlink('packages/user/modules/Log/file/'.$log_file[$i_file]);
                            }
                        }
                    }
                    if(is_dir('packages/hotel/log')){
                        $log_file = scandir('packages/hotel/log');
                        for($i_file=0;$i_file<sizeof($log_file);$i_file++){
                            if($i_file>1){
                                unlink('packages/hotel/log/'.$log_file[$i_file]);
                            }
                        }
                    }
                }
                /** LOG **/
                /** SITEMINDER_ROOM_RATE **/
                if($table=='SITEMINDER_ROOM_RATE'){
                    DB::insert('SITEMINDER_ROOM_RATE',array('rate_plan_code'=>'ROOT','rate_name'=>'ROOT','structure_id'=>1000000000000000000));
                }
                /** SITEMINDER_ROOM_RATE **/
                /** SITEMINDER_SOAP_BODY **/
                if($table=='SITEMINDER_SOAP_BODY'){
                    if(is_dir('packages/hotel/packages/siteminder/includes/dataXml')){
                        $log_file = scandir('packages/hotel/packages/siteminder/includes/dataXml');
                        for($i_file=0;$i_file<sizeof($log_file);$i_file++){
                            if($i_file>1 and is_file('packages/hotel/packages/siteminder/includes/dataXml/'.$log_file[$i_file])){
                                unlink('packages/hotel/packages/siteminder/includes/dataXml/'.$log_file[$i_file]);
                            }
                        }
                    }
                    if(is_dir('packages/hotel/packages/siteminder/includes/dataRes')){
                        $log_file = scandir('packages/hotel/packages/siteminder/includes/dataRes');
                        for($i_file=0;$i_file<sizeof($log_file);$i_file++){
                            if($i_file>1 and is_file('packages/hotel/packages/siteminder/includes/dataRes/'.$log_file[$i_file])){
                                unlink('packages/hotel/packages/siteminder/includes/dataRes/'.$log_file[$i_file]);
                            }
                        }
                    }
                }
                /** SITEMINDER_SOAP_BODY **/
            }
            Url::redirect('update_version',array('cmd'=>'sync_system'));
        }
        
	}
	function draw()
	{
		$tables = DB::get_all_tables();
        
		$this->parse_layout('backup',array('tables'=>$tables));
	}
}
?>
