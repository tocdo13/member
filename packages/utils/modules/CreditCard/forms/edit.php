<?php
class EditCreditCardForm extends Form
{
	function EditCreditCardForm()
	{
		Form::Form('EditCreditCardForm');
		//$this->link_js('packages/core/includes/js/multi_items.js');
		$this->add('credit_card.id',new TextType(true,'invalid_credit_card_id',0,20)); 
		$this->add('credit_card.name',new TextType(true,'invalid_credit_card_name',0,255)); 
		$this->add('credit_card.code',new TextType(false,'invalid_credit_card_code',0,255)); 
		
	}
	function on_submit()
	{
		if($this->check() and URL::get('confirm_edit'))
		{
			if(isset($_REQUEST['mi_credit_card']))
			{
				foreach($_REQUEST['mi_credit_card'] as $key=>$record)
				{
					$record['allow_payment'] = isset($record['allow_payment'])?1:0;
					if($record['id'] and DB::exists_id('credit_card',$record['id']))
					{
						DB::update('credit_card',$record,'id=\''.$record['id'].'\'');
					}
					else
					{
						DB::insert('credit_card',$record);
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
					DB::delete_id('credit_card',$id);
				}
			}
			Url::redirect_current(array());
		}
	}	
	function draw()
	{
		$paging = '';
		if(!isset($_REQUEST['mi_credit_card']))
		{
			$item_per_page = Portal::get_setting('item_per_page',50);
			require_once 'packages/core/includes/utils/paging.php';
			$paging = paging(DB::fetch('select count(*) as acount from credit_card','acount'),$item_per_page);
			$sql = '
				select * from 
				(
					select 
						credit_card.*,ROWNUM as rownumber
					from 
						credit_card
					order by name
				)
				WHERE
			 		rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
			';
			$mi_credit_card = DB::fetch_all($sql);
			/*foreach($mi_credit_card as $key=>$value)
			{
			  $mi_credit_card[$key]['exchange'] = System::display_number_report($value['exchange']); 
			}*/
			$_REQUEST['mi_credit_card'] = $mi_credit_card;
		}
		/*if(!isset($_REQUEST['credit_card_query_id'])){
			$_REQUEST['credit_card_query_id'] = 'USD';
		}
		if(!isset($_REQUEST['credit_card_result_id'])){
			$_REQUEST['credit_card_result_id'] = 'VND';
		}
		if(!isset($_REQUEST['query'])){
			$_REQUEST['query'] = 1;
		}*/
		$credit_cards = DB::select_all('credit_card',false,'name');
		$this->parse_layout('edit',
			array(
				'paging'=>$paging,
				'credit_cards'=>$credit_cards
				//'credit_card_query_id_list'=>String::get_list($currencies),
				//'credit_card_result_id_list'=>String::get_list($currencies)
			)
		);
	}
}
?>