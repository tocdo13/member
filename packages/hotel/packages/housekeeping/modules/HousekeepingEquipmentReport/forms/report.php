<?php
class HousekeepingEquipmentReportForm extends Form
{
	function HousekeepingEquipmentReportForm()
	{
		Form::Form('HousekeepingEquipmentReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
		if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			$from_date_tan = Date_Time::to_time(Url::get('from_date_tan'));
            $to_date_tan = Date_Time::to_time(Url::get('to_date_tan'));
			$this->line_per_page = URL::get('line_per_page',15);
			$this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
			$cond = '1>0 '
					.(Url::get('hotel_id')?' and housekeeping_equipment.portal_id=\''.Url::get('hotel_id').'\'':' and housekeeping_equipment.portal_id=\''.PORTAL_ID.'\'')
					.(Url::get('room_id')!=''?' and housekeeping_equipment.room_id=\''.Url::get('room_id').'\'':'')
					.' and housekeeping_equipment.time>=\''.$from_date_tan.'\' and housekeeping_equipment.time<\''.($to_date_tan+24*3600).'\''
			;
			$fee_summary = DB::fetch('
				SELECT 
					sum(housekeeping_equipment.quantity) as grant_total 
				FROM 
					housekeeping_equipment 
				WHERE '.$cond
			);
			$sql = 'select hs.*,ROWNUM as id from
					(
						select 
							to_char(FROM_UNIXTIME(housekeeping_equipment.time)) as time
							,sum(housekeeping_equipment.quantity) as quantity 
							,product.name_'.Portal::language().' as product_name
							,room.name as room_name
							,unit.name_'.Portal::language().' as unit_name
							,housekeeping_equipment.product_id
						from 
							housekeeping_equipment 
							left outer join product on product.id=housekeeping_equipment.product_id 
							left outer join room on room.id=housekeeping_equipment.room_id 
							left outer join unit on unit.id=product.unit_id
						where '.$cond.'
						group by
							to_char(FROM_UNIXTIME(housekeeping_equipment.time))
							,product.name_'.Portal::language().'
							,room.name
							,unit.name_'.Portal::language().'	
							,housekeeping_equipment.product_id
						order by 
							room.name						
					) hs
				';			
			$report = new Report;
			$report->items = DB::fetch_all($sql);
            //System::debug($sql);
			$sql = 'select 
						distinct housekeeping_equipment.product_id as id,
						 product.name_'.Portal::language().' as name
					from 
						housekeeping_equipment 
						inner join product on product.id=housekeeping_equipment.product_id
					where 							
						'.$cond;
			$hk_product = DB::fetch_all($sql);
			$items = array();
			foreach($report->items as $key=>$value)
			{
				if(!isset($items[$value['room_name']]))
				{
					$items[$value['room_name']] = array();
				}
				$items[$value['room_name']]['room'] = $value['room_name'];
				$items[$value['room_name']]['time'] = $value['time'];
				$items[$value['room_name']]['product'][$value['product_id']] = $value;				
			}
			$report->items = $items;
			$this->print_all_pages($report,$fee_summary,$hk_product);
		}
		else
		{
			$this->parse_layout('search',
				array(
				'room_id' => URL::get('room_id',''),
				'room_id_list' => array(''=>'')+String::get_list(DB::select_all('room','room.portal_id=\''.PORTAL_ID.'\'')), 
				'hotel_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list())
				)
			);	
		}			
	}
	function print_all_pages(&$report,$fee_summary,$hk_product)
	{
		$count = 0;
		$total_page = 1;
		$pages = array();
		$status="0";
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
				    'quantity'=>0, 'quantity_count'=>0, 
				);
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page,$fee_summary,$hk_product);
			}
		}
		else
		{
			$this->parse_layout('header',
			get_time_parameters()+
				array(
					'page_no'=>1,
					'total_page'=>1
				)+$this->map
			);
                        $this->parse_layout('no_record');//KimTan: xu ly truong hop khong co du lieu
			$this->parse_layout('footer',array(
				'page_no'=>1,
				'total_page'=>1
			)+$this->map
            );
		}
	}
	function print_page($items, &$report, $page_no,$total_page,$fee_summary,$hk_product)
	{
		$status="0";
		$last_group_function_params = $this->group_function_params;
		foreach($items as $item)
		{
		  	/*if($temp=System::calculate_number($item['quantity']))
			{
				$this->group_function_params['quantity'] += $temp;
			}
			*/
			$this->group_function_params['quantity_count'] ++; 
		}
		if($page_no>=$this->map['start_page'])
		{ 
        $this->parse_layout('header',
			array(
				'page_no'=>$page_no,
				'total_page'=>$total_page
			)+$this->map
		);		
		$this->parse_layout('report',
			(($page_no==$total_page)?$fee_summary:array())
			+array(
				'items'=>$items,
				'hk_product'=>$hk_product,
				'last_group_function_params'=>$last_group_function_params,
				'group_function_params'=>$this->group_function_params,
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)+$this->map
		);
		$this->parse_layout('footer',array(				
			'page_no'=>$page_no,
			'total_page'=>$total_page,
		)+$this->map
        );
	}}
}
?>