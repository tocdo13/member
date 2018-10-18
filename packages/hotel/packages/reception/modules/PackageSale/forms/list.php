<?php
class ListPackageSaleForm extends Form
{
	function ListPackageSaleForm()
	{
		Form::Form('ListPackageSaleForm');
        
	}
    function on_submit()
    {
    }
    
	function draw()
	{  
	  
       $sql = '
            select 
                package_sale.*,
                (CASE 
                    WHEN exists(SELECT * FROM reservation_room WHERE reservation_room.package_sale_id=package_sale.id)
                    THEN 1
                    ELSE 0
                    END
                ) as can_delete
            from 
                package_sale    
            ';      
        $items = DB::fetch_all($sql);
        foreach($items as $key=>$value)
        {
            $items[$key]['total_amount'] = System::display_number($value['total_amount']);
            $items[$key]['start_date'] = Date_Time::convert_orc_date_to_date($value['start_date'],"/");
            $items[$key]['end_date'] = Date_Time::convert_orc_date_to_date($value['end_date'],"/");
        }
        
		$this->parse_layout('list',array('items'=>$items,'title'=>Portal::language('list_package_sale')));
        
	}	
}
?>