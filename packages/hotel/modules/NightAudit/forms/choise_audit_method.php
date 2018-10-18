<?php
class ChoiseAuditMethodForm extends Form
{
	function ChoiseAuditMethodForm()
	{
		Form::Form('ChoiseAuditMethodForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css('packages/hotel/skins/default/css/night_audit.css');
	}
	function on_submit(){
		if(Url::get('perform_night_audit')){
			$this->check_night_audit();
			Url::redirect_current(array('cmd'=>'user_session_control','start_time_night_audit'=>time()));
		}else{
		  
			$from_date = Date_Time::to_time(Url::get('from_date'));
			$to_date = Date_Time::to_time(Url::get('to_date'));
			for($d=$from_date;$d<=$to_date;$d=$d+24*3600){
				$date = Date_Time::to_orc_date(date('d/m/Y',$d));
				DB::query('
						UPDATE 
							(SELECT 
								closed_time,in_date 
							FROM
								room_status
								INNER JOIN reservation ON reservation.id = room_status.reservation_id
							WHERE
								reservation.portal_id = \''.PORTAL_ID.'\'
							) rs
						SET
							rs.closed_time = '.time().'
						WHERE
							rs.in_date = \''.$date.'\'
							
				');
				if(!DB::exists('SELECT id FROM night_audit WHERE portal_id = \''.PORTAL_ID.'\' AND in_date = \''.$date.'\'')){
					DB::insert('night_audit',array('in_date'=>$date,'time'=>time(),'status'=>'CHECKED','portal_id'=>PORTAL_ID));
				}else{
					DB::update('night_audit',array('time'=>time(),'status'=>'CHECKED'),'portal_id = \''.PORTAL_ID.'\' AND in_date = \''.$date.'\'');
				}
			}
			Url::redirect_current();
		}
	}
	function draw()
	{
		if(isset($_SESSION['night_audit_date'])){
			unset($_SESSION['night_audit_date']);
		}
		$this->map = array();  
        //$dates = DB::fetch_all('SELECT to_char(in_date,\'DD/MM/YYYY\') AS id,to_char(in_date,\'DD/MM/YYYY\') AS name FROM night_audit WHERE status=\'UNCHECK\' AND portal_id = \''.PORTAL_ID.'\' ORDER BY IN_DATE');
		if(Url::get('date')){// and preg_match('/^[0-9]{2}/[0-9]{2}/[0-9]{4}/i',Url::get('date'))
			$date = Url::get('date');
			$this->map['date'] = $date;
		}else{
			$dates = DB::fetch_all('SELECT to_char(in_date,\'DD/MM/YYYY\') AS id,to_char(in_date,\'DD/MM/YYYY\') AS name FROM night_audit WHERE status=\'CHECKED\' AND portal_id = \''.PORTAL_ID.'\' ORDER BY IN_DATE');
			$this->map['date_list'] = String::get_list($dates);
		}
        $max_date = DB::fetch('SELECT MAX(in_date) AS id FROM night_audit WHERE status=\'UNCHECK\' AND portal_id = \''.PORTAL_ID.'\'','id');
		$max_date = Date_Time::convert_orc_date_to_date($max_date,'/');
		$this->map['max_date'] = Date_Time::to_time($max_date);
		if(!empty($dates)){
			$this->map['in_date_list'] = String::get_list($dates);
		}else{
			$this->map['in_date_list'] = array(date('d/m/Y')=>date('d/m/Y'));
		}
		//$this->map['from_date_list'] = $this->map['in_date_list'];
		//$this->map['to_date_list'] = array(date('d/m/Y',(time() - 24*3600))=>date('d/m/Y',(time() - 24*3600)));
        $this->map['from_date'] = date('d/m/Y',(time() - 3*24*3600)); 
		$this->map['to_date'] = date('d/m/Y',(time() - 2*24*3600));
		$this->parse_layout('choise_audit_method',$this->map);
	}
	function check_night_audit(){
		global $night_audit_date;
		if(User::is_login() and User::can_view($this->get_module_id('NightAudit'),ANY_CATEGORY)){
			if(Url::get('in_date')){
				$current_today = Date_Time::to_orc_date(Url::get('in_date'));	
			}else{
				$current_today = Date_Time::to_orc_date(date('d/m/Y'));
			}
			/////////////////////////////Chen ngay moi//////////////////////////////////////////////////
			if(!isset($_SESSION['night_audit_date'])){
				$today = $current_today;
				if(!DB::exists('SELECT id FROM night_audit WHERE in_date = \''.$today.'\' AND portal_id = \''.PORTAL_ID.'\'')){
					DB::insert('night_audit',array(
						'in_date' => $today,
						'time'=>'',
						'status'=>'UNCHECK',
						'portal_id'=>PORTAL_ID
					));
				}
				$tmp_arr = explode(':',NIGHT_AUDIT_TIME);
				$night_audit_time = Date_Time::to_time(Date_Time::convert_orc_date_to_date($today,'/')) + intval($tmp_arr[0])*3600 + intval($tmp_arr[1])*60;
				$_SESSION['night_audit_date']  = Date_Time::convert_orc_date_to_date($today,'/');
			}
		}
	}
}
?>