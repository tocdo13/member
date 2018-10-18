<?php
class EditCurrencyForm extends Form
{
	function EditCurrencyForm()
	{
		Form::Form('EditCurrencyForm');
		$this->add('currency.id',new TextType(true,'invalid_currency_id',0,20)); 
		$this->add('currency.name',new TextType(true,'invalid_currency_name',0,255)); 
		$this->add('currency.symbol',new TextType(false,'invalid_currency_symbol',0,255)); 
		$this->add('currency.exchange',new TextType(true,'invalid_currency_exchange',0,255)); 
	}
	function on_submit()
	{
		if($this->check() and URL::get('confirm_edit'))
		{
			if(isset($_REQUEST['mi_currency']))
			{
				foreach($_REQUEST['mi_currency'] as $key=>$record)
				{
					$record['exchange'] = System::calculate_number($record['exchange']);
					$record['allow_payment'] = isset($record['allow_payment'])?1:0;
					if($record['id'] and DB::exists_id('currency',$record['id']))
					{
						DB::update('currency',$record,'id=\''.$record['id'].'\'');
					}
					else
					{
						DB::insert('currency',$record);
					}
				}
				if (isset($ids) and sizeof($ids))
				{
					$_REQUEST['selected_ids'].=','.join(',',$ids);
				}
			}
			if(URL::get('deleted_ids'))
			{
				$ids = explode(',',URL::get('deleted_ids'));
				foreach($ids as $id)
				{
					DB::delete_id('currency',$id);
				}
			}
			Url::redirect_current(array());
		}
	}	
	function draw()
	{
		$paging = '';
		if(!isset($_REQUEST['mi_currency']))
		{
			$item_per_page = Portal::get_setting('item_per_page',50);
			require_once 'packages/core/includes/utils/paging.php';
			$paging = paging(DB::fetch('select count(*) as acount from currency','acount'),$item_per_page);
			$sql = '
				select * from 
				(
					select 
						currency.*,ROWNUM as rownumber
					from 
						currency
					order by name
				)
				WHERE
			 		rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
			';
			$mi_currency = DB::fetch_all($sql);
			foreach($mi_currency as $key=>$value)
			{
			  $mi_currency[$key]['exchange'] = System::display_number_report($value['exchange']); 
			}
			$_REQUEST['mi_currency'] = $mi_currency;
		}
		if(!isset($_REQUEST['currency_query_id'])){
			$_REQUEST['currency_query_id'] = 'USD';
		}
		if(!isset($_REQUEST['currency_result_id'])){
			$_REQUEST['currency_result_id'] = 'VND';
		}
		if(!isset($_REQUEST['query'])){
			$_REQUEST['query'] = 1;
		}
		$currencies = DB::select_all('currency',false,'name');
		$this->parse_layout('edit',
			array(
				'paging'=>$paging,
				'currencies'=>$currencies,
				'currency_query_id_list'=>String::get_list($currencies),
				'currency_result_id_list'=>String::get_list($currencies)
			)
		);
	}
}
?>