<?php
class ListCustomerForm extends Form
{
	function ListCustomerForm()
	{
		Form::Form('ListCustomerForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css('packages/hotel/skins/default/css/w3c.css');
        $this->link_css('packages/hotel/skins/default/css/font-awesome-4.7.0/css/font-awesome.min.css');
	}
	function draw()
	{
	   require_once 'packages/core/includes/utils/vn_code.php';
		$this->map = array();
		$item_per_page = 200;
		$cond = '1=1
			'.(Url::get('group_id')?' AND customer.group_id=\''.Url::sget('group_id').'\'':'').'
			'.(Url::get('customer_id')?' AND ((UPPER(FN_CONVERT_TO_VN(customer.name))) LIKE \'%'.mb_strtoupper(convert_utf8_to_latin(Url::get('customer_id'),'utf-8')).'%\' 
            OR (UPPER(FN_CONVERT_TO_VN(customer_group.name))) LIKE \'%'.mb_strtoupper(convert_utf8_to_latin(Url::get('customer_id'),'utf-8')).'%\' 
            OR (UPPER(FN_CONVERT_TO_VN(customer.contact_person_name))) LIKE \'%'.mb_strtoupper(convert_utf8_to_latin(Url::get('customer_id'),'utf-8')).'%\' 
            OR (UPPER(FN_CONVERT_TO_VN(customer.contact_person_mobile))) LIKE \'%'.mb_strtoupper(convert_utf8_to_latin(Url::get('customer_id'),'utf-8')).'%\' 
            OR (UPPER(FN_CONVERT_TO_VN(customer.phone))) LIKE \'%'.mb_strtoupper(convert_utf8_to_latin(Url::get('customer_id'),'utf-8')).'%\' 
            OR (UPPER(FN_CONVERT_TO_VN(customer.email))) LIKE \'%'.mb_strtoupper(convert_utf8_to_latin(Url::get('customer_id'),'utf-8')).'%\' 
            OR (UPPER(FN_CONVERT_TO_VN(customer.contact_person_email))) LIKE \'%'.mb_strtoupper(convert_utf8_to_latin(Url::get('customer_id'),'utf-8')).'%\'
            OR (UPPER(FN_CONVERT_TO_VN(customer.def_name))) LIKE \'%'.mb_strtoupper(convert_utf8_to_latin(Url::get('customer_id'),'utf-8')).'%\' 
            OR (UPPER(FN_CONVERT_TO_VN(customer.mobile))) LIKE \'%'.mb_strtoupper(convert_utf8_to_latin(Url::get('customer_id'),'utf-8')).'%\')':'').'
			';
		$this->map['title'] = Portal::language('customer_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				CUSTOMER
				LEFT OUTER JOIN CUSTOMER_GROUP ON CUSTOMER_GROUP.ID = CUSTOMER.GROUP_ID
			WHERE
				'.$cond.' and PORTAL_ID=\''.PORTAL_ID.'\'
		';
		require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10,$smart=false,$page_name='page_no',array('action'));
		$sql = '
			SELECT * FROM
			(
				SELECT
					CUSTOMER.*,CUSTOMER_GROUP.NAME AS group_name,
					row_number() over(ORDER BY CUSTOMER.name) AS rownumber
				FROM
					CUSTOMER 
					LEFT OUTER JOIN CUSTOMER_GROUP ON CUSTOMER_GROUP.ID = CUSTOMER.GROUP_ID
				WHERE	
					'.$cond.'
				ORDER BY CUSTOMER.name	
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).' and PORTAL_ID=\''.PORTAL_ID.'\'
		';
		$items = DB::fetch_all($sql);
		$i = 1;
		foreach($items as $key=>$value){
			$items[$key]['i'] = $i++;
			$items[$key]['number'] = 0;
			$items[$key]['rate_policy_quantity'] = DB::fetch('select count(*) as acount from customer_rate_policy where customer_id = '.$value['id'].' and portal_id = \''.PORTAL_ID.'\'','acount');
			$items[$key]['rate_policy_commission'] = DB::fetch('select count(*) as acount from customer_rate_commission where customer_id = '.$value['id'].' and portal_id = \''.PORTAL_ID.'\'','acount');
            //Start : Giap add  13-05-2014
            $items[$key]['cutomer_care_count']  = DB::fetch('select count(*) as acount from customer_care where customer_id = '.$value['id'],'acount');
            //End
            $contacts = array();
			if($value['id'])
			{
				$contacts = DB::fetch_all('
					SELECT
						customer_contact.*
					FROM
						customer_contact
						inner join customer on customer.id = customer_contact.customer_id
					WHERE
						customer_contact.customer_id = '.$value['id'].'							
				');
			}				
			$items[$key]['contacts'] = $contacts;			
		}
        //System::debug($items);
		$this->map['items'] = $items;
        /** start: KID them doan nay de sap xep 
        ten cong ty da chon o chi tiet dat phong len dau danh sach **/
        if(Url::get('customer_id_of_kid'))
        {
            $items_s = array();
            foreach($items as $key=>$value)
            {     
                if($key==Url::get('customer_id_of_kid'))
                {
                    $items_s[$key]=$value;
                    unset($items[$key]);
                }   
            }
            $items_s +=$items;
            $this->map['items'] = $items_s;    
        }
        /** end: KID them doan nay de sap xep 
        ten cong ty da chon o chi tiet dat phong len dau danh sach **/
		$groups = DB::fetch_all('SELECT ID,NAME FROM CUSTOMER_GROUP WHERE '.IDStructure::child_cond(ID_ROOT,1).'');
		$this->map['group_id_list'] = array(''=>'---') + String::get_list($groups);	
		$this->parse_layout('list',$this->map);
	}	
}
?>