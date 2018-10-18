<?php
class ListMassageGuestForm extends Form
{
	function ListMassageGuestForm()
	{
		Form::Form('ListMassageGuestForm');
		$this->link_css('packages/hotel/'.Portal::template('massage').'/css/style.css');
	}
	function draw()
	{
        require_once 'packages/core/includes/utils/vn_code.php';
		$this->map = array();
		$item_per_page = 20;
		$cond = '1=1 and massage_guest.portal_id=\''.PORTAL_ID.'\'
			'.(Url::get('keyword')?' AND (lower(FN_CONVERT_TO_VN(massage_guest.code)) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('keyword'),'utf-8')).'%\' OR lower(FN_CONVERT_TO_VN(massage_guest.full_name)) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('keyword'),'utf-8')).'%\' OR lower(FN_CONVERT_TO_VN(massage_guest.address)) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('keyword'),'utf-8')).'%\' OR lower(FN_CONVERT_TO_VN(massage_guest.phone)) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('keyword'),'utf-8')).'%\' OR lower(FN_CONVERT_TO_VN(massage_guest.note)) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('keyword'),'utf-8')).'%\' OR lower(FN_CONVERT_TO_VN(massage_guest.category)) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(Url::sget('keyword'),'utf-8')).'%\'  )':'').'
			';
        //echo $cond;
		$this->map['title'] = Portal::language('MassageGuest_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				massage_guest
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
					massage_guest.*,
					ROWNUM AS rownumber
				FROM
					massage_guest 
				WHERE	
					'.$cond.'						
				ORDER BY
					massage_guest.ID DESC
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
		$i = 1;
		foreach($items as $key=>$value){
			$items[$key]['i'] = $i++;
			$items[$key]['number'] = 0;
			$items[$key]['gender'] = ($value['gender']==1)?Portal::language('female'):Portal::language('male');
		}
		$this->map['items'] = $items;
		$this->parse_layout('list',$this->map);
	}	
}
?>