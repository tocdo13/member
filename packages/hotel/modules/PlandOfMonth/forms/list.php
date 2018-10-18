<?php
class ListPlandOfMonthForm extends Form
{
	function ListPlandOfMonthForm()
	{
		Form::Form('ListPlandOfMonthForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
        $this->map = array();
		$sql = '
			select 
                sum(plan_of_month_detail.units_built) as units_built
                ,sum(plan_of_month_detail.room_repair) as room_repair
                ,sum(plan_of_month_detail.rooms_available_for_sale) as rooms_available_for_sale
                ,sum(plan_of_month_detail.rooms_sold) as rooms_sold
                ,sum(plan_of_month_detail.complimentary_rooms) as complimentary_rooms
                ,sum(plan_of_month_detail.total_rooms_occupied) as total_rooms_occupied
                ,sum(plan_of_month_detail.house_use_rooms) as house_use_rooms
                ,sum(plan_of_month_detail.no_of_guests) as no_of_guests
                ,sum(plan_of_month_detail.room_revenue) as room_revenue
                ,sum(plan_of_month_detail.bar_revenue) as bar_revenue
            	,sum(plan_of_month_detail.telephone_revenue) as telephone_revenue
            	,sum(plan_of_month_detail.laundry_revenue) as laundry_revenue
            	,sum(plan_of_month_detail.minibar_revenue) as minibar_revenue
            	,sum(plan_of_month_detail.transport_revenue) as transport_revenue
            	,sum(plan_of_month_detail.spa_revenue) as spa_revenue
            	,sum(plan_of_month_detail.others_revenue) as others_revenue
            	,sum(plan_of_month_detail.vending_revenue) as vending_revenue
            	,plan_of_month_detail.pland_of_month_id as id
              ,year 
             from 
                plan_of_month_detail
             group by pland_of_month_id,year
		';
		$items = DB::fetch_all($sql);
		
		$this->parse_layout('list',$this->map+
			array(
				'items'=>$items,
			)
		);
	}
}
?>
