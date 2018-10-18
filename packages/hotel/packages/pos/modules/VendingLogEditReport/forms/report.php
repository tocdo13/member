<?php
class VendingLogEditReportForm extends Form
{
	function VendingLogEditReportForm()
	{
		Form::Form('VendingLogEditReportForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
        $this->map = array();
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):999;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
    	//L?y ra c�c account
		$users = DB::fetch_all('select 
									account.id
									,party.full_name 
								from account 
								INNER JOIN party on party.user_id = account.id AND party.type=\'USER\' WHERE account.type=\'USER\'  ORDER BY account.id');			
		//System::debug($users);
        $this->map['user_id_list'] = array(''=>Portal::language('All'))+String::get_list($users);
        
        
        $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $_REQUEST['to_date'] = $this->map['to_date'];
        //dau thang
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('1/m/Y');
        $_REQUEST['from_date'] = $this->map['from_date'];
        
		$cond = ' 1=1';
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
        if(Url::get('portal_id'))
        {
            $portal_id = Url::get('portal_id');
        }
        else
        {
            $portal_id = PORTAL_ID;
            $_REQUEST['portal_id'] = PORTAL_ID;                       
        }
        /*
        if($portal_id != 'ALL')
        {
            $cond.=' AND ve_reservation.portal_id = \''.$portal_id.'\' '; 
        }
        */
        
		$cond .= ' '
				.(URL::get('from_date')?' and log.time>=\''.Date_Time::to_time(URL::get('from_date')).'\'':'')
				.(URL::get('to_date')?' and log.time<\''.(Date_Time::to_time(URL::get('to_date'))+86400).'\'':'')
                .(URL::get('user_id')?' and log.user_id = \''.URL::get('user_id').'\'':'')
                
             ;
        $cond.=' '
        .(URL::get('invoice_number')!=''?' and log.parameter = '.trim(URL::iget('invoice_number')).' or ve_reservation.id = '.trim(URL::iget('invoice_number')).'':'')
          ;
        $cond.=' '
        .(URL::get('keyword')!=''?'and lower(log.DESCRIPTION) LIKE \'%'.strtolower(addslashes(URL::get('keyword'))).'%\'
        or lower(log.TITLE) LIKE \'%'.strtolower(addslashes(URL::get('keyword'))).'%\'
        or lower(log.USER_ID) LIKE \'%'.strtolower(addslashes(URL::get('keyword'))).'%\'
        ':'')
        ;
        $cond .= ' and log.type = \'edit\' and log.note = \'VENDING\''; 
        System::debug($cond);
		$sql = '
			SELECT * FROM
			(
				SELECT 
                    log.*, 
                    ve_reservation.department_id,
                    ve_reservation.arrival_time,
					ROW_NUMBER() OVER (ORDER BY  log.id ) as rownumber,
                    ve_reservation.id as ve_reservation_id
				FROM 
                    log
                    left join ve_reservation on ve_reservation.id = log.parameter
				WHERE 
                    '.$cond.'
                ORDER BY 
					ve_reservation.arrival_time desc
			)
			where 
				rownumber > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' AND rownumber<='.($this->map['no_of_page']*$this->map['line_per_page']).'
		';
        
        //System::debug($sql);
  
		$items = DB::fetch_all($sql);
        //System::debug($items);
        $i=1;
		foreach($items as $key=>$value)
		{
			$items[$key]['stt'] = $i++;
            $items[$key]['time'] = date('H\h:i d/m/Y', $items[$key]['time']);
            $items[$key]['arrival_time'] = date('d/m/Y', $items[$key]['arrival_time']);
		}
        //System::debug($items);
        $this->print_all_pages($items);
	}
    
    function print_all_pages($items)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
		foreach($items as $key=>$item)
		{
			if($count>=$this->map['line_per_page'])
			{
				$count = 0;
				$total_page++;
			}
			$pages[$total_page][$key] = $item;
			$count++;
		}
        
		if(sizeof($pages)>0)
		{
			$this->group_function_params = array(
					'total_amount'=>0,
				);
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $page_no,$total_page);
			}
		}
		else
		{
			$this->parse_layout('report',
				array(
					'page_no'=>0,
					'total_page'=>0,
                    'has_no_data'=>true
				)+$this->map
			);
		}
	}
    function print_page($items, $page_no, $total_page)
	{
        $last_group_function_params = $this->group_function_params;
		$this->parse_layout('report',array(
				'items'=>$items,
				'last_group_function_params'=>$last_group_function_params,
				'group_function_params'=>$this->group_function_params,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)+$this->map
		);
	}
}
?>