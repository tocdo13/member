<?php
class HousekeepingGoodsReportForm extends Form
{
	function HousekeepingGoodsReportForm()
	{
		Form::Form('HousekeepingGoodsReportForm');
	}
	function draw()
	{
		if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			$from_year =2009;
			$to_year =date('Y',time());
			$from_month=1;
			$to_month=1;
			$from_day=1;
			$to_day=31;
			if(URL::get('from_year'))
			{
				$from_year = Url::get('from_year');
			}
			if(URL::get('to_year'))
			{
				$to_year = Url::get('to_year');
			}
			if(Url::get('from_month'))
			{	
				$from_month = Url::get('from_month');
			}
			if(Url::get('to_month'))
			{	
				$to_month = Url::get('to_month');
			}
			if(URL::get('from_day'))
			{
				$from_day = URL::get('from_day');
			}
			if(URL::get('to_day'))
			{
				$to_day = URL::get('to_day');
			}
			$this->line_per_page = URL::get('line_per_page',15);
			$cond = ' 1>0 '
					.(Url::get('hotel_id')?' and hk_product.portal_id=\''.Url::get('hotel_id').'\'':' and hk_product.portal_id=\''.PORTAL_ID.'\'')
					.(URL::get('product_id')?' and product_id=\''.URL::get('product_id').'\'':'') 
					.' and ROOM_PRODUCT_DETAIL.time>=\''.strtotime($from_month.'/'.$from_day.'/'.$from_year).'\' and ROOM_PRODUCT_DETAIL.time<\''.(strtotime($to_month.'/'.$to_day.'/'.$to_year)+24*3600).'\'';
			$report = new Report;
			$sql = '
			select * from
			(	
				select house.*,ROWNUM as id from
				(
					select
						sum(ROOM_PRODUCT_detail.quantity) as quantity
						,'.(Url::get('product_id')?'room.name as room_name':'product_id as product_code').'
						,hk_product.name_'.Portal::language().' as product_name 
						,unit.name_'.Portal::language().' as unit
					from 
						ROOM_PRODUCT_detail
						left outer join hk_product on product_id=hk_product.code
						'.(URL::get('category_id')?'left outer join hk_product_category on category_id=hk_product_category.id':'').'
						left outer join unit on unit_id=unit.id
						left outer join room on room.id = ROOM_PRODUCT_detail.room_id
					where '.$cond.'
					group by 
						'.(Url::get('product_id')?'room.name':'product_id').'
						,unit.name_'.Portal::language().'
						,hk_product.name_'.Portal::language().'
					order by 
						'.(Url::get('product_id')?'room.name':'product_id').'
					) house	
				)
				where
				id > '.((URL::get('start_page')-1)*$this->line_per_page).' and id <= '.(URL::get('no_of_page')*$this->line_per_page);
			$report->items = DB::fetch_all($sql);	
			$i = 1;			
			foreach($report->items as $key=>$item)
			{
				$report->items[$key]['stt'] = $i++;
				if($item['quantity']==0)
				{
					$report->items[$key]['quantity']='';
				}
				else
				{
					$report->items[$key]['quantity']=System::display_number($item['quantity']);
				}
			}
			$this->print_all_pages($report);
		}
		else
		{
			$this->parse_layout('search',array(
				'hotel_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list())
			));	
		}			
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
				'amount'=>0,
				'amount_count'=>0,
				'quantity'=>0,
				'quantity_count'=>0
			);
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page);
			}
		}
		else
		{
			$this->parse_layout('header',
			get_time_parameters()+
				array(
					'page_no'=>0,
					'total_page'=>0
				)
			);
			$this->parse_layout('footer',array(
				'page_no'=>0,
				'total_page'=>0
			));
		}
	}
	function print_page($items, &$report, $page_no,$total_page)
	{
		$last_group_function_params = $this->group_function_params;
		$this->parse_layout('header',
			array(
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)
		);		
		$this->parse_layout('report',
			array(
				'items'=>$items,
				'last_group_function_params'=>$last_group_function_params,
				'group_function_params'=>$this->group_function_params,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)
		);
		$this->parse_layout('footer',array(				
			'page_no'=>$page_no,
			'total_page'=>$total_page,
		));
	}
}
?>