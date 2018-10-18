<?php
class KaraokeProductRemainReportForm extends Form
{
	function KaraokeProductRemainReportForm()
    {
		Form::Form('KaraokeProductRemainReportForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
    {
		$this->map = array();
        require_once 'packages/core/includes/utils/lib/report.php';
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):20;
        $this->line_per_page = $this->map['line_per_page']; 
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):4000;
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
        if(!isset($_REQUEST['date'])){
			$_REQUEST['date'] = date('d/m/Y');
		}
		$date = Url::get('date')?Url::get('date'):date('d/m/Y');
        $this->map['karaoke_id_list'] = String::get_list(DB::fetch_all('Select * from karaoke where portal_id = \''.PORTAL_ID.'\''));
        
        $cond = ' 1=1 and karaoke_reservation.portal_id = \''.PORTAL_ID.'\''
				.(Url::get('karaoke_id')?' and karaoke_reservation.karaoke_id = '.Url::get('karaoke_id').'':'') 
				.' AND (karaoke_reservation.departure_time >= '.Date_Time::to_time($date).' AND karaoke_reservation.departure_time < '.(Date_Time::to_time($date)+(24*3600)).')'
			;
        
        $sql = '
			SELECT 
				* 
			FROM
				(SELECT 
					brp.id
					,brp.product_id
					,karaoke_reservation.id as br_id
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
					,karaoke_reservation.time_in
					,karaoke_reservation.time_out
					,karaoke_reservation.user_id
				FROM  
					karaoke_reservation_product brp
					INNER JOIN karaoke_reservation ON karaoke_reservation.id = brp.karaoke_reservation_id
					inner join product ON product.id = brp.product_id 
				WHERE 
					'.$cond.' AND brp.remain>0
				ORDER BY karaoke_reservation.time_in DESC
				)
			WHERE
				rownum > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' AND rownum<='.($this->map['no_of_page']*$this->map['line_per_page']).'
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
			if($count>=$this->line_per_page)
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
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page);
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
        if($page_no==1){
            $this->parse_layout('header',$this->map+array('page_no'=>$page_no,'total_page'=>$total_page));    
        }
        
        		
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