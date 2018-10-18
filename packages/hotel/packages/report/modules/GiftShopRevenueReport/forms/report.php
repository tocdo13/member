<?php
class GiftShopRevenueReportForm extends Form
{
	function GiftShopRevenueReportForm()
	{
		Form::Form('GiftShopRevenueReportForm');
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
			$end_day = Date_Time::day_of_month(date('m'),date('Y'));
			if(URL::get('from_day'))
			{
				$day = URL::get('from_day');
				$end_day = URL::get('to_day');
			}
			$month = URL::get('from_month');
			$end_month = URL::get('to_month');
			if(!checkdate($month,$day,$year))
			{
				$day = 1;
			}
			if(!checkdate($end_month,$end_day,$end_year))
			{
				$end_day = Date_time::day_of_month($end_month,$end_year);
			}		
			$this->line_per_page = URL::get('line_per_page',15);
			$cond = ' 1 >0 '.(URL::get('shop_id')?' and shop_id = '.URL::get('shop_id').'
					':'') 
				.(URL::get('from_year')?' and shop_invoice.time>=\''.strtotime($month.'/'.$day.'/'.$year).'\' and shop_invoice.time<\''.(strtotime($end_month.'/'.$end_day.'/'.$end_year)+24*3600).'\'':'')
			;
			$sql = '
				SELECT
					sum(tax) as tax
				FROM 
					shop_invoice 
				WHERE '.$cond;		
			$fee_summary = DB::fetch($sql);
			$sql = '
			SELECT 
				* 
			FROM
			(
				SELECT 
					bar.*,ROWNUM as id
				FROM
				(	
					select
						sum(shop_invoice_detail.quantity) as quantity 
						,shop_invoice_detail.price as price
						,sum(shop_invoice_detail.quantity_discount) as quantity_discount 
						,sum((shop_invoice_detail.quantity-shop_invoice_detail.quantity_discount)*shop_invoice_detail.price*shop_invoice_detail.discount_rate/100) as discount 
						,sum((shop_invoice_detail.quantity-shop_invoice_detail.quantity_discount)*shop_invoice_detail.price) as amount
						,product_id as product_code
						,shop_product.name_'.Portal::language().' as product_name 
						,unit.name_'.Portal::language().' as unit 
						,sum((shop_invoice_detail.quantity-shop_invoice_detail.quantity_discount)*shop_invoice_detail.price*(100-shop_invoice_detail.discount_rate)/100) as total
					from 
						shop_invoice_detail
						left outer join shop_invoice on shop_invoice_id=shop_invoice.id
						left outer join shop_product on product_id=shop_product.id
						left outer join unit on unit_id=unit.id
					where '.$cond.'
					group by 
						shop_invoice_detail.price
						,unit.name_'.Portal::language().' 
						,product_id
						,shop_product.name_'.Portal::language().'
					order by product_code	
					) bar
				)	
			WHERE
			 id > '.((URL::get('start_page')-1)*$this->line_per_page).' and id<='.(URL::get('no_of_page')*$this->line_per_page);	
			$report = new Report;
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
					$report->items[$key]['quantity']=$item['quantity'];
				} 
				if($item['price']==0)
				{
					$report->items[$key]['price']='';
				}
				else
				{
					$report->items[$key]['price']=System::display_number_report($item['price']);
				} 
				if($item['quantity_discount']==0)
				{
					$report->items[$key]['quantity_discount']='';
				}
				else
				{
					$report->items[$key]['quantity_discount']=System::display_number_report($item['quantity_discount']);
				} 
				if($item['discount']==0)
				{
					$report->items[$key]['discount']='';
				}
				else
				{
					$report->items[$key]['discount']=System::display_number_report($item['discount']);
				} 
				if($item['amount']==0)
				{
					$report->items[$key]['amount']='';
				}
				else
				{
					$report->items[$key]['amount']=System::display_number_report($item['amount']);
				} 
				if($item['total']==0)
				{
					$report->items[$key]['total']='';
				}
				else
				{
					$report->items[$key]['total']=System::display_number_report($item['total']);
				}
				if($item['total']==0)
				{
					$report->items[$key]['total']='';
				}
				else
				{
					$report->items[$key]['total']=System::display_number_report($item['total']);
				}
			}
			$this->print_all_pages($report,$fee_summary);
		}
		else
		{
			$this->parse_layout('search',
				array(				
				'shop_id' => URL::get('shop_id',''),
				'shop_id_list' => array(''=>'')+String::get_list(DB::select_all('shop',false)), 
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
				    'discount'=>0,
					'discount_count'=>0, 
					'amount'=>0,
					'amount_count'=>0, 
					'total'=>0,
					'total_count'=>0, 
					'pay_by_cash'=>0,
					'pay_by_room'=>0,
					'breakfast'=>0,
					'free'=>0,
					'debt'=>0
				);
			foreach($pages as $page_no=>$page)
			{
				$this->print_page($page, $report, $page_no,$total_page,$fee_summary);
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
	function print_page($items, &$report, $page_no,$total_page,$fee_summary)
	{
		$last_group_function_params = $this->group_function_params;
		foreach($items as $item)
		{
			if($temp=System::calculate_number($item['discount']))
			{
				$this->group_function_params['discount'] += $temp;
			}
			if($temp=System::calculate_number($item['amount']))
			{
				$this->group_function_params['amount'] += $temp;
			}
			if($temp=System::calculate_number($item['total']))
			{
				$this->group_function_params['total'] += $temp;
			}
		}
		$this->parse_layout('header',
			array(
				'page_no'=>$page_no,
				'total_page'=>$total_page,
			)
		);		
		$this->parse_layout('report',
			(($page_no==$total_page)?$fee_summary:array())
			+array(
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