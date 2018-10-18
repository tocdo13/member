<?php
class ListCustomerForm extends Form
{
	function ListCustomerForm()
	{
		Form::Form('ListCustomerForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 200;
		$cond = '1=1
			'.(Url::get('group_id')?' AND VENDING_customer.group_id=\''.Url::sget('group_id').'\'':'').'
			'.(Url::get('keyword')?' AND (upper(VENDING_CUSTOMER.NAME) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(VENDING_CUSTOMER.CODE) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(VENDING_CUSTOMER_GROUP.NAME) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(VENDING_CUSTOMER.CONTACT_PERSON_NAME) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(VENDING_CUSTOMER.CONTACT_PERSON_MOBILE) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(VENDING_CUSTOMER.PHONE) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(VENDING_CUSTOMER.EMAIL) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(VENDING_CUSTOMER.CONTACT_PERSON_EMAIL) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(VENDING_CUSTOMER.MOBILE) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\')':'').'
			';
		$this->map['title'] = Portal::language('customer_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				VENDING_CUSTOMER
				LEFT OUTER JOIN VENDING_CUSTOMER_GROUP ON VENDING_CUSTOMER_GROUP.ID = VENDING_CUSTOMER.GROUP_ID
			WHERE
				'.$cond.'
		';
		require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10,$smart=false,$page_name='page_no',array('action'));
		$sql = '
			SELECT * FROM
			(
				SELECT
					VENDING_CUSTOMER.*,VENDING_CUSTOMER_GROUP.NAME AS group_name,
					row_number() over(ORDER BY VENDING_CUSTOMER.name) AS rownumber
				FROM
					VENDING_CUSTOMER 
					LEFT OUTER JOIN VENDING_CUSTOMER_GROUP ON VENDING_CUSTOMER_GROUP.ID = VENDING_CUSTOMER.GROUP_ID
				WHERE	
					'.$cond.'
				ORDER BY VENDING_CUSTOMER.name	
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
		$i = 1;
		foreach($items as $key=>$value){
			$items[$key]['i'] = $i++;
			$items[$key]['number'] = 0;
			$items[$key]['rate_policy_quantity'] = DB::fetch('select count(*) as acount from customer_rate_policy where customer_id = '.$value['id'].' and portal_id = \''.PORTAL_ID.'\'','acount');
			$contacts = array();
			if($value['id'])
			{
				$contacts = DB::fetch_all('
					SELECT
						customer_contact.*
					FROM
						customer_contact
						inner join VENDING_customer on VENDING_customer.id = customer_contact.customer_id
					WHERE
						customer_contact.customer_id = '.$value['id'].'							
				');
			}				
			$items[$key]['contacts'] = $contacts;			
		}
		$this->map['items'] = $items;
		$groups = DB::fetch_all('SELECT ID,NAME FROM VENDING_CUSTOMER_GROUP WHERE '.IDStructure::child_cond(ID_ROOT,1).'');
		$this->map['group_id_list'] = array(''=>'---') + String::get_list($groups);	
		$this->parse_layout('list',$this->map);
	}	
}
?>