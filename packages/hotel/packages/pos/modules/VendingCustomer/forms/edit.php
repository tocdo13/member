<?php
class EditCustomerForm extends Form
{
	function EditCustomerForm()
	{
		Form::Form();
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->add('code',new UniqueType(true,'code_missed_or_code_duplicated','customer','code'));
		$this->add('name',new TextType(true,'miss_name',0,255));
	}
	function on_submit(){
		if($this->check()){
			$description = '';
			$array = array(
				'code',
				'name'=>trim(Url::get('name')),
				'fax',
				'tax_code',
				'PHONE',
				'MOBILE',
				'ADDRESS',
				'EMAIL',
				'group_id'
			);
			if(Url::get('cmd')=='edit'){
				$log_action = 'edit';
				$description.= 'Edit customer';
				$customer_id = Url::iget('id');
				DB::update('vending_CUSTOMER',$array,'ID='.Url::iget('id'));
			}else{
				$description.= 'Add customer';
				$log_action = 'add';	
				$customer_id = DB::insert('vending_CUSTOMER',$array);
			}
			if(URl::get('group_deleted_ids')){
				$group_deleted_ids = explode(',',URl::get('group_deleted_ids'));
				$description .= '<hr>';
				foreach($group_deleted_ids as $delete_id)
				{
					$description .= 'Delete contact id: '.$delete_id.'<br>';
					DB::delete_id('customer_contact',$delete_id);
				}
			}
			if(isset($_REQUEST['mi_contact_group']))
			{	
				$description .= '<hr>';
				foreach($_REQUEST['mi_contact_group'] as $key=>$record)
				{
					$empty = true;
					foreach($record as $record_value)
					{
						if($record_value)
						{
							$empty = false;
						}
					}
					if(!$empty)
					{
						$record['contact_name'] = $record['contact_name'];
						$record['contact_phone'] = $record['contact_phone'];
						$record['contact_mobile'] = $record['contact_mobile'];
						$record['contact_email'] = $record['contact_email'];
						$record['customer_id'] = $customer_id;
						$record_id = false;
						if($record['id']){
							$record_id = $record['id'];	
						}
						if($record['id'])
						{
							$id = $record['id'];
							unset($record['id']);
							$description .= 'Edit [Contact name: '.$record['contact_name'].', Mobile: '.$record['contact_phone'].', Mobile: '.$record['contact_mobile'].', Email: '.$record['contact_email'].']<br>';
							DB::update('customer_contact',$record,'id=\''.$id.'\'');
						}
						else
						{
							if(isset($record['id'])){
								unset($record['id']);
							}
							$description .= 'Edit [Contact name: '.$record['contact_name'].', Mobile: '.$record['contact_phone'].', Mobile: '.$record['contact_mobile'].', Email: '.$record['contact_email'].']<br>';
							DB::insert('customer_contact',$record);
						}
					}
				}
			}			
			$log_title = 'Customer: #'.$id.'';
			System::log($log_action,$log_title,$description,$id);// Edited in 07/03/2011
			Url::redirect_current(array('group_id','action'));
		}
	}
	function draw()
	{
		$this->map = array();
		$item = VendingCustomer::$item;
		if($item){
			foreach($item as $key=>$value){
				if(!isset($_REQUEST[$key])){
					$_REQUEST[strtoupper($key)] = $value;
				}
			}
			if(!isset($_REQUEST['mi_contact_group']))
			{
				$sql = '
					SELECT
						customer_contact.*
					FROM
						customer_contact
						inner join vending_customer on vending_customer.id = customer_contact.customer_id
					WHERE 1>0
						AND customer_contact.customer_id=\''.$item['id'].'\' 
					ORDER BY
						customer_contact.id
				';
				$mi_contact_group = DB::fetch_all($sql);
				$_REQUEST['mi_contact_group'] = $mi_contact_group;
			} 
		}
		$this->map['title'] = (Url::get('cmd')=='add')?Portal::language('add_customer'):Portal::language('edit_customer');
		$groups = DB::fetch_all('SELECT ID,NAME FROM vending_CUSTOMER_GROUP WHERE '.IDStructure::child_cond(ID_ROOT,1).'');
		$this->map['group_id_list'] = array(''=>'---') + String::get_list($groups);
		$this->map['customers'] = DB::fetch_all('SELECT * FROM vending_customer');
		$this->parse_layout('edit',$this->map);
	}	
}
?>