<?php
class ReportEmailMarketingForm extends Form
{
    function ReportEmailMarketingForm()
    {
        Form::Form('ReportEmailMarketingForm');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
                
    }
    function draw()
    {
        $this->map=array();
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):100;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        $this->map['email_status_list']=array(''=>Portal::language('all'),
                                              '0'=>Portal::language('pending'),
                                              '1'=>Portal::language('sent'),
                                              '2'=>Portal::language('error')			
                                        		);
        $sql = 'SELECT id,name FROM email_group_event';
        $list_group_event =DB::fetch_all($sql);
        $this->map['group_event_list']=array(''=>Portal::language('------------')) +String::get_list($list_group_event);
        $this->map['portal_id_list'] = array(''=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
        
        if(Url::get('date_from')=='' || Url::get('date_to')=='')
        {
            $_REQUEST['date_from']=date('d/m/Y');
            $_REQUEST['date_to']=date('d/m/Y');
        }
        else
        {
            $date_from = Date_Time::to_time(Url::get('date_from'));
            $date_to = Date_Time::to_time(Url::get('date_to'));
        }
        $this->map['items'] =array();
        if(Url::get('group_event')!='')
        {
            if(Url::get('date_from')=='' || Url::get('date_to')=='')
            {
                $date_from = Date_Time::to_orc_date(date('d/m/y'));
                $date_to = Date_Time::to_orc_date(date('d/m/y'));
            }
            else
            {
                $date_from = Date_Time::to_orc_date(Url::get('date_from'));
                $date_to = Date_Time::to_orc_date(Url::get('date_to'));
            }
            $group_event =DB::fetch('SELECT * FROM email_group_event WHERE id ='.Url::get('group_event'));
            switch($group_event['code'])
            {
                case 'SN':
                {
                    $cond  = '1=1 ';
                    $from_time = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_from,"/"));
                    $to_time = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_to,"/"));
                    
                    $cond .= ' AND TO_CHAR(traveller.birth_date,\'dd-mm\') >= \''.date('d-m',$from_time).'\' AND TO_CHAR(traveller.birth_date,\'dd-mm\') <= \''.date('d-m',$to_time).'\'';
                    $date_from = substr($date_from,7,4);
                    $date_to = substr($date_to,7,4);
                    
                    if(Url::get('email_status')!='')
                    {
                        if(Url::get('email_status')=='1')
                            $cond .= ' AND traveller.check_send_mail >='.$date_from.' AND traveller.check_send_mail <='.$date_to.' ';
                        else
                            $cond .= " AND traveller.check_send_mail =".Url::get('email_status');   
                        
                    }
                    $sql = "
                            SELECT traveller.id,
                                   traveller.id AS tra_id,   
                                   traveller.first_name| |traveller.last_name AS full_name,
                                   traveller.email,
                                   traveller.phone,
                                   traveller.birth_date as date_send,
                                   traveller.check_send_mail as status
                            FROM traveller 
                                LEFT JOIN country ON country.id = traveller.nationality_id
                            where traveller.email is not null AND country.code_1='VNM' AND ".$cond."  
                            ORDER BY id DESC
                            ";
                            
                    $this->map['items'] = DB::fetch_all($sql);
                    break;
                }
                case 'BD':
                {
                    $cond = '1>0 ';
                    $from_time = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_from,"/"));
                    $to_time = Date_Time::to_time(Date_Time::convert_orc_date_to_date($date_to,"/"));
                    
                    $cond .= ' AND TO_CHAR(traveller.birth_date,\'dd-mm\') >= \''.date('d-m',$from_time).'\' AND TO_CHAR(traveller.birth_date,\'dd-mm\') <= \''.date('d-m',$to_time).'\'';
                    $date_from = substr($date_from,7,4);
                    $date_to = substr($date_to,7,4);
                    if(Url::get('email_status')!='')
                    {
                        if(Url::get('email_status')=='1')
                            $cond .= ' AND traveller.check_send_mail >=\''.$date_from.'\' AND traveller.check_send_mail <=\''.$date_to.'\' ';
                        else
                            $cond .= " AND traveller.check_send_mail =".Url::get('email_status');
                    }                  
                    $sql = "
                            SELECT traveller.id,
                                   traveller.id AS tra_id,
                                   traveller.first_name| |traveller.last_name AS full_name,
                                   traveller.email,
                                   traveller.phone,
                                   traveller.birth_date as date_send,
                                   traveller.check_send_mail as status
                            FROM traveller 
                            LEFT JOIN country ON country.id = traveller.nationality_id
                            where traveller.email is not null AND country.code_1 !='VNM' AND ". $cond." 
                            ORDER BY id DESC
                            ";
                            
                    $this->map['items'] = DB::fetch_all($sql);
                    break;
                }
                case 'NTL':
                {
                    $cond = '1>0 ';
                    $cond .= ' AND customer.creart_date >=\''.$date_from.'\' AND customer.creart_date <=\''.$date_to.'\' ';
                    $date_from = substr($date_from,7,4);
                    $date_to = substr($date_to,7,4);
                    if(Url::get('email_status')!='')
                    {
                        if(Url::get('email_status')=='1')
                            $cond .= ' AND customer.check_send_mail >=\''.$date_from.'\' AND customer.check_send_mail <=\''.$date_to.'\' ';
                        else
                            $cond .= " AND customer.check_send_mail =".Url::get('email_status');
                    }
                    $sql = "
                            SELECT customer.id,
                                   customer.id AS cus_id,
                                   customer.name AS full_name,
                                   customer.email,
                                   customer.phone,
                                   customer.creart_date as date_send,
                                   customer.check_send_mail as status
                            FROM customer 
                            WHERE customer.email is not null AND ".$cond." 
                            ORDER BY id DESC
                            ";
                            
                    $this->map['items'] = DB::fetch_all($sql);
                    break;
                }
                default:
                {
                    $cond = '1>0 ';
                    $cond .= ' AND email_send.date_send >=\''.$date_from.'\' AND email_send.date_send <=\''.$date_to.'\' ';
                    if(Url::get('email_status')!='')
                        $cond .= " AND email_list.status =".Url::get('email_status');
                    $sql = '
                            SELECT email_list.id,
                                   email_send.date_send as date_send,
                                   traveller.id AS tra_id,
                                   customer.id AS cus_id,
                                                                     
                                   CASE WHEN email_list.traveller_id is NOT NULL
                                        THEN traveller.first_name| |traveller.last_name
                                        ELSE customer.name
                                   END  full_name,
                                   
                                   CASE WHEN email_list.traveller_id is NOT NULL
                                        THEN traveller.email
                                        ELSE customer.email
                                   END  email,
                                   
                                   CASE WHEN email_list.traveller_id is NOT NULL
                                        THEN traveller.phone
                                        ELSE customer.phone
                                   END  phone,
                                   
                                   email_list.status AS status
                            FROM   email_list
                                    LEFT JOIN email_send ON email_list.email_send_id = email_send.id
                                    LEFT JOIN customer ON customer.id = email_list.customer_id
                                    LEFT JOIN traveller ON traveller.id = email_list.traveller_id   
                                    LEFT JOIN email_send ON email_list.email_send_id = email_send.id
                                    LEFT JOIN email_group_event ON email_group_event.id = email_send.email_group_event_id
                            WHERE 1=1 AND (traveller.email is not null OR customer.email is not null)  AND  '.$cond.' AND email_group_event.id ='. $group_event['id']        
                            ;              
                    $this->map['items'] = DB::fetch_all($sql);
                    break;      
                }
            }       
        }
        else
        {
            if(Url::get('date_from')=='' || Url::get('date_to')=='')
            {
                $date_from = Date_Time::to_orc_date(date('d/m/y'));
                $date_to = Date_Time::to_orc_date(date('d/m/y'));
            }
            else
            {
                $date_from = Date_Time::to_orc_date(Url::get('date_from'));
                $date_to = Date_Time::to_orc_date(Url::get('date_to'));
            }
            $cond = '1=1 ';
                    $cond .= ' AND email_send.date_send >=\''.$date_from.'\' AND email_send.date_send <=\''.$date_to.'\' ';
                    if(Url::get('email_status')!='')
                        $cond .= " AND email_list.status =".Url::get('email_status');
                    $sql = '
                            SELECT email_list.id,
                                   email_send.date_send as date_send,
                                   traveller.id AS tra_id,
                                   customer.id AS cus_id,
                                                                     
                                   CASE WHEN email_list.traveller_id is NOT NULL
                                        THEN traveller.first_name| |traveller.last_name
                                        ELSE customer.name
                                   END  full_name,
                                   
                                   CASE WHEN email_list.traveller_id is NOT NULL
                                        THEN traveller.email
                                        ELSE customer.email
                                   END  email,
                                   
                                   CASE WHEN email_list.traveller_id is NOT NULL
                                        THEN traveller.phone
                                        ELSE customer.phone
                                   END  phone,
                                   
                                   email_list.status AS status
                            FROM   email_list
                                    LEFT JOIN email_send ON email_list.email_send_id = email_send.id
                                    LEFT JOIN customer ON customer.id = email_list.customer_id
                                    LEFT JOIN traveller ON traveller.id = email_list.traveller_id                                       
                                    LEFT JOIN email_group_event ON email_group_event.id = email_send.email_group_event_id
                            WHERE 1=1 AND (traveller.email is not null OR customer.email is not null)  AND '.$cond.''       
                            ;              
                    $this->map['items'] = DB::fetch_all($sql);                    
            
        }
        require_once 'packages/core/includes/utils/lib/report.php';
        $report = new Report;
        $report->items=$this->map['items'];
        //System::debug($this->map['items']);
        $this->print_all_parse($report);
    }
    function print_all_parse($report)
    {
		$count = 0;
		$total_page = 1;
		$pages = array();
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
		if(sizeof($pages)>0)
		{
		    $this->map['real_total_page']=count($pages);
            $this->map['real_page_no'] = 1;   
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $page_no, $total_page);
			}
		}
        else
		{
			$this->parse_layout('default',
				array(
					'page_no'=>0,
					'total_page'=>0,
                    'has_no_data'=>true
				)+$this->map
			);
		}
	}
    function print_page($items, $page_no,$total_page)
	{
	   if($page_no >= $this->map['start_page'])
       {
            $this->parse_layout('default',
			array(
    				'items'=>$items,
    				'page_no'=>$page_no,
    				'total_page'=>$total_page,
			)+$this->map
		  );
       }
		
	} 
}
?>