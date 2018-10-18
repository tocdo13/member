<?php
class ListTennisStaffForm extends Form
{
	function ListTennisStaffForm()
	{
		Form::Form('ListTennisStaffForm');
		$this->link_css('packages/hotel/'.Portal::template('tennis').'/css/style.css');
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 20;
		$cond = '1=1
			'.(Url::get('keyword')?' AND (upper(tennis_staff.full_name) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(tennis_staff.address) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(tennis_staff.native) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(tennis_staff.description) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\')':'').'
			';
		$this->map['title'] = Portal::language('TennisStaff_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				tennis_staff
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
					tennis_staff.*, TO_CHAR(tennis_staff.birth_date,\'DD-MM-YYYY\') AS birth_day,
					ROWNUM AS rownumber
				FROM
					tennis_staff 
				WHERE	
					'.$cond.'						
				ORDER BY
					tennis_staff.ID DESC
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
		$i = 1;
		foreach($items as $key=>$value){
			$items[$key]['i'] = $i++;
			$items[$key]['number'] = 0;
			$items[$key]['birth_date'] = $items[$key]['birth_day'];//Date_Time::convert_orc_date_to_date($value['birth_date'],'/');
			$items[$key]['gender'] = ($value['gender']==1)?Portal::language('female'):Portal::language('male');
		}
		$this->map['items'] = $items;
		$this->parse_layout('list',$this->map);
	}	
}
?>