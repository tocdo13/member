<?php
class ListMassageStaffForm extends Form
{
	function ListMassageStaffForm()
	{
		Form::Form('ListMassageStaffForm');
		$this->link_css('packages/hotel/'.Portal::template('massage').'/css/style.css');
	}
	function draw()
	{
        require_once 'packages/core/includes/utils/vn_code.php';
		$this->map = array();
		$item_per_page = 200;
		$cond = '1=1 and massage_staff.portal_id=\''.PORTAL_ID.'\'
			'.(
                Url::get('keyword')?' AND (LOWER(FN_CONVERT_TO_VN(massage_staff.full_name))) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(URL::get('keyword'),'utf-8')).'%\' '
                                      :''
               ).'
			';
		$this->map['title'] = Portal::language('MassageStaff_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				massage_staff
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
					massage_staff.id,
                    massage_staff.full_name,
                    massage_staff.gender,
                    massage_staff.phone,
                    massage_staff.address,
                    massage_staff.native,
                    massage_staff.status,
					to_char(massage_staff.birth_date,\'DD/MM/YYYY\') as birth_date,
                    --massage_reservation_room.id as res_id,
                    ROW_NUMBER() OVER (ORDER BY massage_staff.id Desc) as rownumber
				FROM
					massage_staff
					--LEFT OUTER JOIN massage_staff_room ON massage_staff_room.staff_id = massage_staff.id
					--LEFT OUTER JOIN massage_reservation_room on massage_reservation_room.id = massage_staff_room.reservation_room_id
					--LEFT OUTER JOIN massage_room ON massage_room.id = massage_staff_room.room_id
				WHERE	
					'.$cond.'			
				ORDER BY
					massage_staff.id ASC
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
        
		$items = DB::fetch_all($sql);
		$i = 1;
		foreach($items as $key=>$value)
        {
			$items[$key]['i'] = $i++;
			$items[$key]['number'] = 0;
			$items[$key]['gender'] = ($value['gender']==1)?Portal::language('female'):Portal::language('male');
			$items[$key]['status'] = Portal::language($items[$key]['status']);
			
		}
       
		$this->map['items'] = $items;
        //system::debug($this->map['items']);
		$this->parse_layout('list',$this->map);
	}	
}
?>