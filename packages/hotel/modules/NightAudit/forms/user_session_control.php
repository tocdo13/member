<?php
class UserSessionControlForm extends Form
{
	function UserSessionControlForm()
	{
		Form::Form('UserSessionControlForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css('packages/hotel/skins/default/css/night_audit.css');
		if(Url::get('id')){
		    require 'packages/hotel/includes/php/hotel.php';
            $id_arr = split(',',Url::get('id'));
            System::debug($id_arr);
            echo(count($id_arr));
            for($i=0;$i<count($id_arr);$i++)
            {
                Hotel::log_off_user($id_arr[$i]);
            }
			//Hotel::log_off_user(Url::sget('id'));
			Url::redirect_current(array('cmd'));
		}
	}
	function draw()
	{
		$this->map = array();
		$sql = '
			SELECT
				account.id,party.full_name,session_user.ip,session_user.time,party.description_1 as department_name
			FROM
				account
				INNER JOIN party ON party.user_id = account.id
				INNER JOIN session_user ON session_user.user_id = account.id
			WHERE
				account.type = \'USER\'
				AND session_user.portal_id = \''.PORTAL_ID.'\'
			ORDER BY
				account.id
		';
		$items = DB::fetch_all($sql);
		foreach($items as $key=>$value){
			$items[$key]['login'] =  date('d/m/Y H:i\':s\'\'',$value['time']);
		}
		$this->map['items'] = $items;
		$this->parse_layout('user_session_control',$this->map);
	}
}
?>