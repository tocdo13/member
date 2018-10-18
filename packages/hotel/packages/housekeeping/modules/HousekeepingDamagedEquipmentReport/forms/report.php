<?php
class HousekeepingDamagedEquipmentReportForm extends Form
{
	function HousekeepingDamagedEquipmentReportForm()
	{
		Form::Form('HousekeepingDamagedEquipmentReportForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
	function draw()
	{
	   //start:KID 
	    if(!isset($_REQUEST['date_from'])){
			$_REQUEST['date_from'] = '01/'.date('m/Y');
		}
		if(!isset($_REQUEST['date_to'])){
		
			$_REQUEST['date_to'] = date('d/m/Y');
		}
        $this->map = array();
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):32;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):50;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
        $this->map['date_from'] = Url::sget('date_from')?Url::sget('date_from'):('01/'.date('m/Y'));
        
        $this->map['date_to'] = Url::sget('date_to')?Url::sget('date_to'):date('d/m/Y');   
		//end
        if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			get_time_range($this);	
			$cond = '1=1 '
					.(Url::get('hotel_id')?' and housekeeping_equipment_damaged.portal_id=\''.Url::get('hotel_id').'\'':' and housekeeping_equipment_damaged.portal_id=\''.PORTAL_ID.'\'')
					.(Url::get('room_id')!=''?' and housekeeping_equipment_damaged.room_id=\''.Url::get('room_id').'\'':'')
					.' and housekeeping_equipment_damaged.time>=\''.Date_time::to_time($this->map['date_from']).'\' and housekeeping_equipment_damaged.time<\''.(Date_time::to_time($this->map['date_to'])+86400).'\''
			;
			$fee_summary = DB::fetch('
				SELECT 
					sum(housekeeping_equipment_damaged.quantity) as grant_total 
				FROM 
					housekeeping_equipment_damaged 
				WHERE '.$cond
			);
			$sql = '
				select * from
				(	
					select hs.*, ROWNUM as id from
					(
						select
							to_char(FROM_UNIXTIME(housekeeping_equipment_damaged.time),\'DD/MM/YYYY\') as time
							,housekeeping_equipment_damaged.type
                            ,housekeeping_equipment_damaged.note
							,sum(housekeeping_equipment_damaged.quantity) as quantity 
							,product.name_'.Portal::language().' as product_name
							,room.name as room_name
							,unit.name_'.Portal::language().' as unit_name
						from 
							housekeeping_equipment_damaged 
							left outer join product on product.id=housekeeping_equipment_damaged.product_id 
							left outer join room on room.id=housekeeping_equipment_damaged.room_id 
							left outer join unit on unit.id=product.unit_id
						where '.$cond.'
						group by 
							to_char(FROM_UNIXTIME(housekeeping_equipment_damaged.time),\'DD/MM/YYYY\'),
							housekeeping_equipment_damaged.product_id,
							housekeeping_equipment_damaged.type,
							product.name_'.Portal::language().',
							room.name,
							unit.name_'.Portal::language().',
                            housekeeping_equipment_damaged.note
					) hs
				)
			';// where id > '.(($this->map['start_page']-1)*$this->map['line_per_page']).' and id <= '.($this->map['no_of_page']*$this->map['line_per_page']).'
			require_once 'packages/core/includes/utils/lib/report.php';
			$report = new Report;
			$report->items = DB::fetch_all($sql);
			$i = 1;
			foreach($report->items as $key=>$item)
			{
				$report->items[$key]['stt'] = $i++;
				$report->items[$key]['date'] = $item['time'];
				$report->items[$key]['damaged_type']=$item['type']=='LOST'?Portal::language('lost'):Portal::language('damaged');
			}
			$this->print_all_pages($report,$fee_summary);
		}
		else
		{
			$this->parse_layout('search',
				array(
				'room_id' => URL::get('room_id',''),
				'room_id_list' => array(''=>'')+String::get_list(DB::fetch_all('select * from room where room.portal_id=\''.PORTAL_ID.'\' order by name')), 
				'hotel_id_list'=>array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list())
				)
			);	
		}			
	}
	function print_all_pages(&$report,$fee_summary)
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
        //start:KID 
        
        //Xoa bo cac phan tu trong mang ma co key (o day la page < start page)
        //for($i = 1; $i< $this->map['start_page']; $i++)
            //unset($pages[$i]);
                
        //muon xem bao nhieu trang ?
        
        //lay ra trang bat dau dc in (neu muon xem tu trang 5 th� se tra ve 5)
        $arr = array_keys($pages);
        if(!empty($arr))
        {
            $begin = $arr['0'];
            //trang cuoi cung dc in
            $end = $begin + $this->map['no_of_page'] - 1;
            //Xoa bo cac phan tu trong mang ma co key (o day la page < end page)
            for($i = $total_page; $i> $end; $i--)
                unset($pages[$i]);      
        }
                
        //end
        
		if(sizeof($pages)>0)
		{
			$this->group_function_params = array(
				    'total_quantity'=>0, 'quantity_count'=>0, 
				);
            $this->map['real_total_page']=count($pages);
            $this->map['real_page_no'] = 1;
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page,$fee_summary);
                $this->map['real_page_no'] ++;                
			}
		}
		else
		{
		    
            $this->parse_layout('header',
			get_time_parameters()+
				array(
					'page_no'=>1,
					'total_page'=>1,
					'from_year'=>$this->from_year,
					'to_year'=>$this->to_year,
					'from_month'=>$this->from_month,
					'to_month'=>$this->to_month,
					'from_day'=>$this->from_day,
					'to_day'=>$this->to_day,
				)+$this->map
			);
            $this->map['real_total_page'] = 0;
            $this->map['real_page_no'] = 0;
            $this->parse_layout('no_record');//KimTan xu ly neu khong co du lieu thi het show code va cho quay lai
            $this->parse_layout('footer',array(
				'page_no'=>1,
				'total_page'=>1
			));
            /*            		  
			$this->parse_layout('header',
			get_time_parameters()+
				array(
					'page_no'=>1,
					'total_page'=>1,
					'from_year'=>$this->from_year,
					'to_year'=>$this->to_year,
					'from_month'=>$this->from_month,
					'to_month'=>$this->to_month,
					'from_day'=>$this->from_day,
					'to_day'=>$this->to_day,
				)
			);
			$this->parse_layout('footer',array(
				'page_no'=>1,
				'total_page'=>1
			));
            */
		}
	}
	function print_page($items, &$report, $page_no,$total_page,$fee_summary)
	{
		$status="0";
		
		$last_group_function_params = $this->group_function_params;
		foreach($items as $item)
		{
		    if($temp=System::calculate_number($item['quantity']))
			{
				$this->group_function_params['total_quantity'] += $temp;
			}
			$this->group_function_params['quantity_count'] ++; 
		}
		if($page_no>=$this->map['start_page'])
		{
    		$this->parse_layout('header',
    			array(
    				'page_no'=>$page_no,
    				'total_page'=>$total_page,
    				
    			)+$this->map
    		);		
    		$this->parse_layout('report',
    			(($page_no==$total_page)?$fee_summary:array())
    			+array(
    				'items'=>$items,
    				'last_group_function_params'=>$last_group_function_params,
    				'group_function_params'=>$this->group_function_params,
    				'page_no'=>$page_no,
    				'total_page'=>$total_page,
    			)+$this->map
    		);
    		$this->parse_layout('footer',array(				
    			'page_no'=>$page_no,
    			'total_page'=>$total_page,
    		)+$this->map);
        }
	}
}
?>