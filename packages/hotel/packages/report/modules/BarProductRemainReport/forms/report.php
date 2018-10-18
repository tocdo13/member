<?php
class BarProductRemainReportForm extends Form
{
	function BarProductRemainReportForm()
    {
		Form::Form('BarProductRemainReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
    {
		$this->map = array();
        require_once 'packages/core/includes/utils/lib/report.php';
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):20;
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):4000;
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
        if(!isset($_REQUEST['date'])){
			$_REQUEST['date'] = date('d/m/Y');
		}
        if(!isset($_REQUEST['to_date']))
        {
            $_REQUEST['to_date'] = date('d/m/Y');
        }
		$date = Url::get('date')?Url::get('date'):date('d/m/Y');
        $to_date = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        // KimTan doan nay de xem bc theo portal
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
        $cond_bar = '';
        if(Url::get('portal_id')!='ALL')
        {
            $cond_bar = Url::get('portal_id')?'where portal_id = \''.Url::get('portal_id').'\'':'';
        }
        if(Url::get('portal_id'))
         {
             $portal_id =  Url::get('portal_id');
             if(Url::get('portal_id')!='ALL')
             {
                 $bars = DB::fetch_all("select id,name FROM bar where portal_id='".Url::get('portal_id')."'");
             }
             else
             {
                $bars = DB::select_all('bar',false); 
             }
         }
         else
         {
            $portal_id =PORTAL_ID;  
            $bars = DB::select_all('bar',false); 
         }
        //Start Luu Nguyen Giap add portal
        
        
        $bars = DB::fetch_all('select id,name from bar');
		$bar_ids = '';
        $bar_name = '';
		foreach($bars as $k => $bar)
        {
			if(Url::get('bar_id_'.$k))
            {
				$bar_ids .= ($bar_ids=='')?$k:(','.$k);	
                $bar_name .= ($bar_name=='')?$bar['name']:(', '.$bar['name']);
                $_REQUEST['bar_id_'.$k] = $bar['id'];
			}
		};
        $_REQUEST['bar_name'] = $bar_name;
        
        if($bar_name!='')
        {
            $cond_bar = " AND bar_reservation.bar_id in (".$bar_ids.")";
            //$cond_bar ='';
        }
        else
        {
            $cond_bar='';
        }
        $this -> map['bars'] = $bars;
        
        $cond = ' 1=1';
        if(Url::get('portal_id'))
        {
            if(Url::get('portal_id')!='ALL')
            {
                $cond.='and bar_reservation.portal_id = \''.Url::get('portal_id').'\'';
            }
        }
        if(Url::get('bar_id'))
        {
            if(Url::get('bar_id')!='ALL')
            {
                $cond.=(Url::get('bar_id')?' and bar_reservation.bar_id = '.Url::get('bar_id').'':'');
            }
        }
        //end KimTan
            $cond.=' AND (bar_reservation.departure_time >= '.Date_Time::to_time($date).' AND bar_reservation.departure_time < '.(Date_Time::to_time($to_date)+(24*3600)).')'
			;
        
        $sql = '
			SELECT 
				* 
			FROM
				(SELECT 
					brp.id
					,brp.product_id
                    ,bar_reservation.code
					,bar_reservation.id as br_id
					,brp.remain as quantity
					,brp.price
					,CASE
						WHEN
							brp.product_name is not null
						THEN
							brp.product_name
						ELSE
							product.name_'.Portal::language().'
					END name
					,bar_reservation.time_in
					,bar_reservation.time_out
					,bar_reservation.user_id
				FROM  
					bar_reservation_product brp
					INNER JOIN bar_reservation ON bar_reservation.id = brp.bar_reservation_id
					inner join product ON product.id = brp.product_id 
				WHERE 
					'.$cond.' AND brp.remain>0
                    '.$cond_bar.'
				ORDER BY bar_reservation.time_in DESC
				)
		';
        $report = new Report;
		$report->items = DB::fetch_all($sql);                                   
		$i = 1;			
		foreach($report->items as $key=>$value)
        {
			$report->items[$key]['stt'] = $i++;
			$report->items[$key]['total'] = $value['quantity'] * $value['price'];
        }
        //System::debug($report->items);
		$this->print_all_pages($report);
	}
    
    function print_all_pages(&$report)
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
			$this->group_function_params = array(
					'quantity'=>0,
					'total'=>0
				);
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
            $this->parse_layout('header',$this->map+array('page_no'=>1,'total_page'=>1));
			$this->parse_layout('no_record');
		}
	}
    
    function print_page($items, &$report, $page_no,$total_page)
	{
		$last_group_function_params = $this->group_function_params;
		foreach($items as $item)
		{
            $this->group_function_params['quantity']+=$item['quantity']; 
			$this->group_function_params['total']+=$item['total']; 
		}
        
        if($page_no>=$this->map['start_page'])
		{
		    $this->map['page_no'] = $page_no;   
            $this->parse_layout('header',$this->map+array('total_page'=>$total_page));    
      		
    		$this->parse_layout('report',array(
    				'items'=>$items,
    				'last_group_function_params'=>$last_group_function_params,
    				'group_function_params'=>$this->group_function_params,
    				'total_page'=>$total_page,
    			)+$this->map
    		);
        }
	}
}
?>