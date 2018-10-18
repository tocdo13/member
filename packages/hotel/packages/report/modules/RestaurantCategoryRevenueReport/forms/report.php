<?php
class RestaurantCategoryRevenueReportForm extends Form
{
	function RestaurantCategoryRevenueReportForm()
	{
		Form::Form('RestaurantCategoryRevenueReportForm');
	}
	function draw()
	{
		if(URL::get('do_search'))
		{
			require_once 'packages/core/includes/utils/time_select.php';
			require_once 'packages/core/includes/utils/lib/report.php';
			$year = URL::get('from_year');
			$end_year = URL::get('from_year');
			$day=1;
			if(URL::get('from_day'))
			{
				$day = URL::get('from_day');
			}
			$month = URL::get('from_month');
			if(!checkdate($month,$day,$year))
			{
				$day = 1;
			}
			$this->line_per_page = URL::get('line_per_page',15);
			$cond = $this->cond = ' 1=1'
							.(URL::get('from_year')?' and bar_reservation.time_out>=\''.strtotime($month.'/'.$day.'/'.$year).'\' and bar_reservation.time_out<\''.(strtotime($month.'/'.$day.'/'.$year)+24*3600).'\'':'')
			.' and (bar_reservation.status=\'CHECKIN\' or bar_reservation.status=\'CHECKOUT\')'

			;
			$sql = '
				SELECT 
					sum(total_before_tax*bar_fee_rate/(100)) as fee,
					sum(CASE WHEN (payment_result=\'CASH\' or payment_result=\'\') THEN total_before_tax*bar_fee_rate/(100) ELSE 0 END ) as pay_by_cash_fee,
					sum(DECODE(payment_result,\'DEBT\',total_before_tax*bar_fee_rate/(100),0)) as debt_fee,
					sum(DECODE(payment_result,\'ROOM\',total_before_tax*bar_fee_rate/(100),0)) as pay_by_room_fee,
					sum(DECODE(payment_result,\'FREE\',total_before_tax*bar_fee_rate/(100),0)) as free_fee,
					sum(DECODE(payment_result,\'BREAKFAST\',total_before_tax*bar_fee_rate/(100),0)) as breakfast_fee,
					sum(tax) as tax,
					sum(CASE WHEN(payment_result=\'CASH\' or payment_result=\'\') THEN tax ELSE 0 END ) as pay_by_cash_tax,
					sum(DECODE(payment_result,\'DEBT\',tax,0)) as debt_tax,
					sum(DECODE(payment_result,\'FREE\',tax,0)) as free_tax,
					sum(DECODE(payment_result,\'BREAKFAST\',tax,0)) as breakfast_tax,
					sum(DECODE(payment_result,\'ROOM\',tax,0)) as pay_by_room_tax
				FROM 
					bar_reservation 
				WHERE '.$cond;		
			
			$fee_summary = DB::fetch($sql);
			$categories = DB::fetch_all('
				SELECT
					id,name,structure_id
				FROM
					res_product_category
				WHERE
					'.IDStructure::direct_child_cond(ID_ROOT).'
			');
			$total_amount = 0;
			$total_amount_before_tax = 0;
			foreach($categories as $key=>$value)
			{
				$sql = '
					select
						bar_reservation_product.id
						,bar_reservation.tax_rate
						,bar_reservation.bar_fee_rate as service_rate
						,((bar_reservation_product.quantity-bar_reservation_product.quantity_discount)*bar_reservation_product.price*(100-bar_reservation_product.discount_rate)/100) as total_before_tax
					from 
						bar_reservation_product
						inner join bar_reservation on bar_reservation_id = bar_reservation.id
						left outer join res_product on bar_reservation_product.product_id = res_product.code
						left outer join res_product_category on res_product_category.id = res_product.category_id
					where
						'.$cond.' and '.IDStructure::child_cond($value['structure_id']).'
				';
				$items = DB::fetch_all($sql);
				$total_before_tax = 0;
				$total = 0;				
				foreach($items as $id=>$item)
				{
					$total_before_tax+=$item['total_before_tax']+($item['total_before_tax']*$item['service_rate']/100);
					$total+=($item['total_before_tax']+$item['total_before_tax']*($item['tax_rate']/100))+($item['total_before_tax']+$item['total_before_tax']*($item['tax_rate']/100))*$item['service_rate']/100;
				}
				$categories[$key]['total_amount_before_tax'] = $total_before_tax;
				$categories[$key]['total_tax'] = $total - $total_before_tax;
				$categories[$key]['total_amount'] = $total;
				$total_amount+= $total;
				$total_amount_before_tax+= $total_before_tax;
			}
			$this->parse_layout('header');
			$this->parse_layout('report',array('items'=>$categories,'total_amount'=>$total_amount,'total_amount_before_tax'=>$total_amount_before_tax));
			$this->parse_layout('footer');
		}
		else
		{
			$this->parse_layout('search',
				array(				
				'bar_id' => URL::get('bar_id',''),
				'bar_id_list' =>String::get_list(DB::select_all('bar',false)), 
				)
			);	
		}			
	}
}
?>