<?php
class ListTennisCourtForm extends Form
{
	function ListTennisCourtForm()
	{
		Form::Form('ListTennisCourtForm');
		$this->link_css('packages/hotel/'.Portal::template('tennis').'/css/style.css');
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 500;
		$cond = '1=1
			'.(Url::get('keyword')?' AND UPPER(tennis_court.name) LIKE "%'.strtoupper(Url::sget('keyword')).'%"':'').'
			';
		$this->map['title'] = Portal::language('court_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				tennis_court
			WHERE
				'.$cond.'
		';
		require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10);
		$sql = '
			SELECT * FROM
			(
				SELECT
					tennis_court.*,
					ROWNUM as rownumber
				FROM
					tennis_court
				ORDER BY
					tennis_court.position
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
		$i = 1;
		foreach($items as $key=>$value){
			$items[$key]['i'] = $i++;
			$items[$key]['number'] = 0;
		}
		$this->map['items'] = $items;
		$this->parse_layout('list',$this->map);
	}	
}
?>