<?php
class ListSwimmingPoolStaffForm extends Form
{
	function ListSwimmingPoolStaffForm()
	{
		Form::Form('ListSwimmingPoolStaffForm');
		$this->link_css('packages/hotel/'.Portal::template('swimming_pool').'/css/style.css');
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 20;
		$cond = '1=1
			'.(Url::get('keyword')?' AND (upper(swimming_pool_staff.full_name) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(swimming_pool_staff.address) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(swimming_pool_staff.native) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\' OR upper(swimming_pool_staff.description) LIKE \'%'.strtoupper(Url::sget('keyword')).'%\')':'').'
			';
		$this->map['title'] = Portal::language('SwimmingPoolStaff_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				swimming_pool_staff
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
					swimming_pool_staff.id,swimming_pool_staff.full_name,swimming_pool_staff.gender,swimming_pool_staff.phone,swimming_pool_staff.address,swimming_pool_staff.native,
					to_char(swimming_pool_staff.birth_date,\'DD/MM/YYYY\') as birth_date,
					ROWNUM AS rownumber
				FROM
					swimming_pool_staff 
				WHERE	
					'.$cond.'						
				ORDER BY
					swimming_pool_staff.ID DESC
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