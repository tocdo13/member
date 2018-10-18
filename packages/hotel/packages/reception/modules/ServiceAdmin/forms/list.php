<?php
class ListServiceAdminForm extends Form
{
	function ListServiceAdminForm()
	{
		Form::Form('ListServiceAdminForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 100;
		$cond = '1=1
			'.(Url::get('keyword')?' AND (service.name LIKE \'%'.Url::sget('keyword').'%\')':'').'
			';
		$this->map['title'] = Portal::language('Service_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				service
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
					service.*,
					ROWNUM as rownumber
				FROM
					service
				WHERE	
					'.$cond.'	
				ORDER BY
					service.ID DESC
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