<?php
class ListTennisGuestForm extends Form
{
	function ListTennisGuestForm()
	{
		Form::Form('ListTennisGuestForm');
		$this->link_css('packages/hotel/'.Portal::template('tennis').'/css/style.css');
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 20;
		$cond = '1=1
			'.(Url::get('keyword')?' AND (upper(tennis_guest.full_name) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(tennis_guest.address) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(tennis_guest.phone) LIKE \'%'.strtoupper(Url::sget('phone')).'%\' OR upper(tennis_guest.note) LIKE \'%'.strtoupper(Url::sget('note')).'%\' OR upper(tennis_guest.category) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\')':'').'
			';
		$this->map['title'] = Portal::language('TennisGuest_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				tennis_guest
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
					tennis_guest.*,
					ROWNUM AS rownumber
				FROM
					tennis_guest 
				WHERE	
					'.$cond.'						
				ORDER BY
					tennis_guest.ID DESC
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