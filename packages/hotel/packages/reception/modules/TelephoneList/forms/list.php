<?php
class ListTelephoneListForm extends Form
{
	function ListTelephoneListForm()
	{
		Form::Form('ListTelephoneListForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        //echo date('d/m/Y H i ',1380946727);
	}
	function on_submit()
	{
        set_time_limit(0);
		if(Url::get('update'))
		{
            if(Url::get('month') and Url::get('year'))
            {
                require_once 'packages/hotel/packages/reception/modules/includes/telephone_paradise.php';
                TelephoneLib::update_telephone_daily(Url::get('month'),Url::get('year'));

                Url::redirect_current(array('room_id','from_date','to_date'));
            }
            /*
            define('TELEPHONE_LINK','118.70.187.84');
            define('TELEPHONE_FTP_USER','tongdai');
            define('TELEPHONE_FTP_PASSWORD','123456');
            //System::debug(TELEPHONE_LINK);
            //System::debug(TELEPHONE_FTP_USER);
            //System::debug(TELEPHONE_FTP_PASSWORD);
            //exit();
			require_once 'packages/hotel/packages/reception/modules/includes/telephone_happy.php';
			TelephoneLib::update_telephone(Url::get('month'),Url::get('year'));
            */
            
		}
		elseif(Url::get('empty') and User::is_admin())
		{
              DB::delete('TELEPHONE_REPORT_DAILY',"portal_id='".PORTAL_ID."'");
              //DB::delete('TELEPHONE_REPORT_DAILY',"portal_id='#hongngoc4'");
			//DB::query('TRUNCATE TABLE TELEPHONE_REPORT_DAILY');
			Url::redirect_current(array('room_id','from_date','to_date'));
		}
	}
	function draw()
	{
/*		require_once 'packages/hotel/packages/reception/modules/include/telephone.php';
		TelephoneLib::update_telephone();
*/		//set_time_limit(0);
		//DB::delete('TELEPHONE_REPORT_DAILY','1=1');
		$cond = ' 1 > 0 AND telephone_report_daily.portal_id=\''.PORTAL_ID.'\'';	
		if(Url::get('room_id') and Url::get('room_id')!='all')
		{
			$cond.=' and telephone_number.id = \''.Url::get('room_id').'\'';
		}
        if(Url::get('phone_number_id'))
        {
            $cond.='and telephone_report_daily.phone_number_id = \''.Url::get('phone_number_id').'\'';
        }
		if(Url::get('from_date'))
		{
			$cond .= ' and telephone_report_daily.hdate >='.Date_Time::to_time(Url::get('from_date')).'';
		}
		if(Url::get('to_date'))
		{
			$cond .= ' and telephone_report_daily.hdate <='.(Date_Time::to_time(Url::get('to_date'))+24*3600).'';
		}
		$order_by = (URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):' '):' order by telephone_report_daily.hdate DESC');
		$item_per_page = 400;
		$sql = '
			select 
				count(*) as acount
			from 
				telephone_report_daily
				left outer join telephone_number on telephone_report_daily.phone_number_id=telephone_number.phone_number
				left outer join room on room.id = telephone_number.room_id
			where 
				'.$cond;
		$count = DB::fetch($sql);
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page,10,false,'page_no',array('month','day','year','room_id'));
		$sql = 'SELECT
				 	* 
				FROM
					(
						select 
							telephone_report_daily.*
							,telephone_report_daily.hdate as in_date
							,room.name as room_name
							,row_number() over ('.$order_by.') as rownumber
						from 
							telephone_report_daily
							left outer join telephone_number on telephone_number.phone_number=telephone_report_daily.phone_number_id
							left outer join room on room.id = telephone_number.room_id
						where 
							'.$cond.'
					)
				WHERE 
					rownumber>'.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page);	
        //System::debug($sql);
		$items = DB::fetch_all($sql);	
        //System::debug($items);
        //exit();				
		$_REQUEST['from_date'] = Url::get('from_date',date('01/m/Y'));
		$_REQUEST['to_date'] = Url::get('to_date',date('d/m/Y'));
		if(!isset($_REQUEST['year'])){
			$_REQUEST['year'] = date('Y');
		}
		if(!isset($_REQUEST['month'])){
			$_REQUEST['month'] = date('n');
		}
		$rooms = DB::fetch_all('
			select distinct
				telephone_number.id
				,room.name
				,telephone_number.room_id
				,room.name as room_name
			from
				telephone_report_daily
				inner join telephone_number on telephone_number.phone_number=telephone_report_daily.phone_number_id
				left outer join room on room.id = telephone_number.room_id
			WHERE
				room.portal_id =\''.PORTAL_ID.'\' AND telephone_report_daily.portal_id=\''.PORTAL_ID.'\'
			order by
				name ASC
		');
		foreach($rooms as $key=>$value)
		{
			if(!$value['room_id'])
			{
				$rooms[$key]['name'] = $value['room_name'];
			}
		}
        
		$this->parse_layout('list',
			array(
				'items'=>$items,
				'paging'=>$paging,
				'total'=>$count['acount'],
				'room_id_list'=>array('all'=>Portal::language('all'))+String::get_list($rooms)
			)
		);
	}
}
?>