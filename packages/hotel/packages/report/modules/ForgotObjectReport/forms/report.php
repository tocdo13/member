<?php
class ForgotObjectReportForm extends Form
{
	function ForgotObjectReportForm()
	{
		Form::Form('ForgotObjectReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css'); 
	}
	function draw()
	{
		if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			$from_day = Url::get('date_from');
            $to_day = Url::get('date_to');
			
			$this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        
            $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
            
            $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
			
			$cond = '1=1 '
					.(Url::get('hotel_id')?' and room.portal_id=\''.Url::get('hotel_id').'\'':' and room.portal_id=\''.PORTAL_ID.'\'')
					.(Url::get('room_id')!=''?' and forgot_object.room_id=\''.Url::get('room_id').'\'':'')
					.(Url::get('employee_id')!=''?' and forgot_object.employee_id=\''.Url::get('room_id').'\'':'')
					.(Url::get('name')!=''?' and forgot_object.name LIKE \'%'.Url::get('name').'%\'':'')
					.(Url::get('type')!=''?' and forgot_object.name LIKE \'%'.Url::get('name').'%\'':'')
					.(Url::get('status')!=''?' and forgot_object.status = \''.Url::get('status').'\'':'')
					.' and forgot_object.time>=\''.Date_Time::to_time($from_day).'\' and forgot_object.time<\''.(Date_Time::to_time($to_day)+24*3600).'\''
			;
			$sql = '
				select * from
				(
					select 
						forgot_object.id, forgot_object.name, forgot_object.object_type, forgot_object.room_id, 
						forgot_object.time, forgot_object.status, forgot_object.unit, forgot_object.quantity,
						forgot_object.date_paid,RESERVATION_room_ID
						,forgot_object.employee_name
						,room.name as room_name,
                        forgot_object.object_code, forgot_object.reason, forgot_object.position 
					from 
						forgot_object 
						left outer join employee_profile on employee_profile.id=forgot_object.employee_id 
						left outer join room on room.id=forgot_object.room_id 
					where '.$cond.'
					order by 
						forgot_object.time
				)
			';
			//,concat(concat(employee_profile.first_name,\' \'),employee_profile.last_name) as employee_name
			require_once 'packages/core/includes/utils/lib/report.php';
			$report = new Report;
			$report->items = DB::fetch_all($sql);
			$i = 1;
			foreach($report->items as $key=>$item)
			{
				$report->items[$key]['stt'] = $i++;
				$report->items[$key]['date'] = date('d/m/Y',$item['time']);
			}
			$this->print_all_pages($report);
		}
		else
		{
		    $_REQUEST['date_from'] = date('d/m/Y');
            $_REQUEST['date_to'] = date('d/m/Y');
			$this->parse_layout('search',
				array(
				'employee_id' => URL::get('employee_id',''),
				'employee_id_list' => array(''=>'')+String::get_list(DB::fetch_all('select id, concat(first_name,concat(\' \',last_name)) as name from employee_profile order by name')), 
				'room_id' => URL::get('room_id',''),
				'room_id_list' => array(''=>'')+String::get_list(DB::fetch_all('select id, name from room order by name')), 
				'hotel_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list())
				)
			);	
		}			
	}
	function print_all_pages(&$report)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
		$from_day = Url::get('date_from');
        $to_day = Url::get('date_to');
		
		if(!empty($report->items)){
			foreach($report->items as $key=>$item)
			{
				if($count>=$this->map['line_per_page'])
    			{
    				$count = 0;
    				$total_page++;
    			}
    			$pages[$total_page][$key] = $item;
    			$count++;
			}
		}
		if(sizeof($pages)>0)
		{
			$this->map['real_total_page']=count($pages);
            $this->map['real_page_no'] = 1;  
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page);
                $this->map['real_page_no'] ++;
			}
		}
		else
		{
		
            $this->parse_layout('header',$this->map+get_time_parameters()+
				array(
					'page_no'=>0,
					'total_page'=>0,
    				'from_date'=>$from_day,
    				'to_date'=>$to_day
				)
			);
			$this->parse_layout('footer',array(
				'page_no'=>0,
                'real_page_no'=>0,
                'real_total_page'=>0,
				'total_page'=>0
			)+$this->map);
		}
	}
	function print_page($items, &$report, $page_no,$total_page)
	{
		$from_day = Url::get('date_from');
        $to_day = Url::get('date_to');
		
        System::debug($to_day);
		if($page_no>=$this->map['start_page'])
		{
		    $this->map['page_no'] = $page_no;   
    		$this->parse_layout('header',$this->map+
    			array(
    				
    				'total_page'=>$total_page,
    				'to_date'=>$to_day,
                    'from_date'=>$from_day
    			)
    		);		
    		$this->parse_layout('report',
    			array(
    				'items'=>$items,
                    
    				'total_page'=>$total_page,
    			)+$this->map
    		);
    		$this->parse_layout('footer',array(
            				
    			'total_page'=>$total_page,
    		)+$this->map);
        }
	}
}
?>