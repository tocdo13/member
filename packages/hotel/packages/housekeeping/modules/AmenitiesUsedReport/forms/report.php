<?php
class AmenitiesUsedReportForm extends Form
{
	function AmenitiesUsedReportForm()
	{
		Form::Form('AmenitiesUsedReportForm');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function draw()
	{
	   
        $this->map = array();
        
        $this->map['line_per_page'] = Url::iget('line_per_page')?Url::iget('line_per_page'):100000;
        
        $this->map['no_of_page'] = Url::iget('no_of_page')?Url::iget('no_of_page'):1000;
        
        $this->map['start_page'] = Url::iget('start_page')?Url::iget('start_page'):1;
        
        
        $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
        $_REQUEST['to_date'] = $this->map['to_date'];
        //dau thang
        $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
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
        
        if($portal_id != 'ALL')
        {
            $cond.=' AND room_amenities.portal_id = \''.$portal_id.'\' '; 
        }
        
		if(Url::get('from_date'))
		{
			$cond .= ' and room_amenities.time>='.Date_Time::to_time($this->map['from_date']);
		}
		if(Url::get('to_date'))
		{
			$cond .= ' and room_amenities.time < '.(Date_Time::to_time($this->map['to_date']) + 86400);
		}
        
        //Lấy các amenities đã được khai báo trong phòng
		$sql='SELECT 
                    DISTINCT
					product.id as id,
                    product.name_'.Portal::language().' AS name
				FROM 
					room_amenities 
                    inner join product ON room_amenities.product_id=product.id
                    inner join product_price_list on product.id = product_price_list.product_id
				WHERE
					product_price_list.portal_id=\''.PORTAL_ID.'\'
				ORDER BY
					product.id
			';
        
		$room_amenities=DB::fetch_all($sql);
        $this->map['room_amenities'] = $room_amenities;
        //System::debug($room_amenities);
        
        //join ntn để chỉ lấy ra các phòng phát sinh sử dụng hàng hóa
		$sql='SELECT 
					DISTINCT room.*, 
                    room.name as room_name
				FROM 
                    room
                    inner join amenities_used_detail on room.id = amenities_used_detail.room_id
				WHERE
					room.portal_id=\''.PORTAL_ID.'\'
				ORDER BY
					room.name
			';	
		$rooms=DB::fetch_all($sql);
        //System::debug($rooms);
		foreach($rooms as $key=>$minibar)
		{
			$rooms[$key]['products'] = $room_amenities;
		}
        //System::debug($rooms);
        		
//-------------------------------------------------------------------------------
        //Lấy các sp trong các phòng
		$sql='
				SELECT 
					room_amenities.id, 
                    room_id, 
                    product_id,
                    1 as is_real
				FROM 
					room_amenities
					inner join product on product.id=room_amenities.product_id
					inner join room on room_amenities.room_id = room.id
				WHERE
					room_amenities.portal_id=\''.PORTAL_ID.'\'
			';
		$products=DB::fetch_all($sql);
        //System::debug($products);
        //System::debug($minibars);
//-------------------------------------------------------------------------------
        //is_real : phân biệt phòng nào đã đc khai báo, phòng nào chưa
		foreach($products as $product)
		{
            if( isset( $rooms[$product['room_id']] ) )
                $rooms[$product['room_id']]['products'][$product['product_id']] = $product;
		}
        $this->map['rooms'] = $rooms;
        //System::debug($rooms);
        
        $sql = '
                select
                    amenities_used_detail.product_id || \'_\' || amenities_used_detail.room_id as id,
                    amenities_used_detail.room_id,
                    sum(quantity) as quantity,
                    amenities_used_detail.product_id,
                    product.type,
                    product.name_'.Portal::language().' as product_name,
                    unit.name_'.Portal::language().' as unit_name
                from
                    amenities_used_detail
                    inner join product on product.id = amenities_used_detail.product_id
                    inner join unit on product.unit_id = unit.id
                    inner join amenities_used on amenities_used.id=amenities_used_detail.amenities_used_id
                where
                    amenities_used.portal_id = \''.PORTAL_ID.'\'
                    AND amenities_used.time >= '.Date_Time::to_time($this->map['from_date']).'
                    AND amenities_used.time <= '.(Date_Time::to_time($this->map['to_date']) + 86400).'
                group by 
                    amenities_used_detail.product_id,
                    amenities_used_detail.room_id,
                    product.type,
                    product.name_'.Portal::language().',
                    unit.name_'.Portal::language().'
                ';
        $items = DB::fetch_all($sql);
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
			$this->group_function_params = array();
            foreach($this->map['room_amenities'] as $key => $value)
            {
                $this->group_function_params[$key] = 0;
            }
            //System::debug($this->group_function_params );
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
        //System::debug($items);	
		foreach($items as $item)
		{
            $_REQUEST[$item['room_id'].'_'.$item['product_id']] = $item['quantity'];
            
            foreach($this->map['room_amenities'] as $key => $value)
            {
                if($item['product_id']==$key)
                    $this->group_function_params[$key] += $item['quantity'];
            }			
		}
        //System::debug($this->group_function_params);	
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