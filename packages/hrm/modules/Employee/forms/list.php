<?php
class ListEmployeeForm extends Form
{
	function ListEmployeeForm()
	{
		Form::Form('ListEmployeeForm');
		$this->link_css(Portal::template('core').'/css/admin.css');
		$this->link_css('skins/default/datetime.css');
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			foreach(URL::get('selected_ids') as $id)
			{
			}
			require_once 'detail.php';
			foreach(URL::get('selected_ids') as $id)
			{
				EmployeeForm::delete($this,$id);
				if($this->is_error())
				{
					return;
				}
			}
			require_once 'packages/core/includes/system/update_privilege.php';
			make_privilege_cache();
			Url::redirect_current(array('join_date_start','join_date_end',  'active'=>isset($_GET['active'])?$_GET['active']:'', 'block'=>isset($_GET['block'])?$_GET['block']:'',  'user_id'=>isset($_GET['user_id'])?$_GET['user_id']:''));
		}
	}
	function draw()
	{
		//require_once 'packages/core/includes/system/access_database.php';
		$adb = new ADB("Driver={Microsoft Access Driver (*.mdb)};Dbq=C:\att2000.mdb",'','');
		$this->map = array();
		require_once 'packages/core/includes/utils/time_select.php';
		$this->year = get_time_parameter('year', date('Y'), $this->end_year);
		$this->month = get_time_parameter('month', date('m'), $this->end_month);
		$this->day = get_time_parameter('day', date('d'), $this->end_day);
		$this->map['year'] = $this->year;
		$this->map['month'] = $this->month;
		$this->map['day'] = $this->day;
		$this->map['end_month'] = $this->end_month;
		$this->map['end_day'] = $this->end_day;
		$this->map += get_time_parameters();	
		$selected_ids="";
		if(URL::get('selected_ids'))
		{
			$selected_ids=URL::get('selected_ids');
			foreach($selected_ids as $key=>$selected_id)
			{
				$selected_ids[$key]='"'.$selected_id.'"';
			}
		}
		$cond = ' 1 = 1 ';
		/*$item_per_page = 100;
		$count = $adb->fetch('
			SELECT
				count(*) as acount
			FROM 
				USERINFO
			WHERE 
					1=1
		');
		//require_once 'packages/core/includes/utils/paging.php';
		//$paging = paging($count['acount'],$item_per_page);*/
		$start_in_day = $this->year.'-'.$this->month.'-'.$this->day.' 00:00:0 AM';
		$end_in_day = $this->end_year.'-'.$this->end_month.'-'.$this->end_day.' 23:59:00 PM';
		$checkinouts = $adb->fetch_all('
			SELECT
				CHECKINOUT.USERID, CHECKINOUT.CHECKTIME AS id, CHECKINOUT.CHECKTIME,CHECKINOUT.CHECKTYPE
			FROM
				CHECKINOUT
			WHERE
				CHECKINOUT.CHECKTIME >= #'.$start_in_day.'# AND CHECKINOUT.CHECKTIME <= #'.$end_in_day.'#
		');
		$items = $adb->fetch_all('
			SELECT * FROM
			(
				SELECT 
					USERINFO.USERID AS id,USERINFO.*,DEPARTMENTS.DEPTNAME AS department_name
				FROM
					USERINFO
					INNER JOIN DEPARTMENTS ON DEPARTMENTS.DEPTID = USERINFO.DEFAULTDEPTID
				WHERE 
					1=1
				ORDER BY 
					DEPARTMENTS.DEPTNAME,USERINFO.USERID
			)
			WHERE
				1=1
		');// rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		$i=1;
		foreach ($items as $key => $value)
		{
			$items[$key]['i'] = $i++;
			$items[$key]['BIRTHDAY'] = str_replace(' 00:00:00','',$value['BIRTHDAY']);
			$items[$key]['checkin_time'] = '00:00:00';
			$items[$key]['checkout_time'] = '00:00:00';
			foreach($checkinouts as $k => $v){
				if($v['USERID'] == $value['id']){
					if($v['CHECKTYPE']=='I'){
						$items[$key]['checkin_time'] = substr($v['CHECKTIME'],11,strlen($v['CHECKTIME']));
					}else{
						$items[$key]['checkout_time'] = substr($v['CHECKTIME'],11,strlen($v['CHECKTIME']));
					}
				}
				
			}
			if($items[$key]['checkin_time']!='00:00:00' and $items[$key]['checkout_time'] != '00:00:00'){
				$items[$key]['duration'] = Date_Time::count_hour(strtotime($items[$key]['checkin_time']),strtotime($items[$key]['checkout_time']));
			}else{
				$items[$key]['duration'] = '';
			}
		}
		$just_edited_id['just_edited_ids'] = array();
		if (UrL::get('selected_ids'))
		{
			if(is_string(UrL::get('selected_ids')))
			{
				if (strstr(UrL::get('selected_ids'),','))
				{
					$just_edited_id['just_edited_ids']=explode(',',UrL::get('selected_ids'));
				}
				else
				{
					$just_edited_id['just_edited_ids']=array('0'=>UrL::get('selected_ids'));
				}
			}
		}
		//print_r($items);
		$this->parse_layout('list',$just_edited_id+$this->map+
			array(
				'items'=>$items
			)
		);
	}
}
?>
