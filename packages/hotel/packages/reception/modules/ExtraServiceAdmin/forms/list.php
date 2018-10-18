<?php
class ListExtraServiceAdminForm extends Form
{
	function ListExtraServiceAdminForm()
	{
		Form::Form('ListExtraServiceAdminForm');
		$this->link_css(Portal::template('hotel').'/css/style.css');
	}
	function draw()
	{
		$this->map = array();
		$item_per_page = 200;
		//$cond = '1=1
//			'.(Url::get('keyword')?' AND (extra_service.name LIKE \'%'.Url::sget('keyword').'%\')':'').'
//			';
        /** Minh fix tìm theo code+name */
        $cond = ' 1=1'.(Url::get('keyword')?' AND (lower(extra_service.name) LIKE \'%'.mb_strtolower(Url::sget('keyword'),'utf-8').'%\') OR (lower(extra_service.code) LIKE \'%'.mb_strtolower(Url::sget('keyword'),'utf-8').'%\')':'');
		$this->map['title'] = Portal::language('Service_list');
		$sql = '
			SELECT
				count(*) AS acount
			FROM
				extra_service
			WHERE
				'.$cond.' order by type

		';
		require_once 'packages/core/includes/utils/paging.php';
		$this->map['total'] =  DB::fetch($sql,'acount');
		$this->map['paging'] =  paging($this->map['total'],$item_per_page,10);
		$sql = '
			SELECT * FROM
			(
				SELECT
					extra_service.*,
					ROWNUM as rownumber
				FROM
					extra_service
				WHERE	
					'.$cond.'	
				ORDER BY
					extra_service.ID DESC
			)
			WHERE
			 	rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
        $service_use=DB::fetch_all('select service_id as id from extra_service_invoice_detail group by service_id ');
		$i = 1;
		foreach($items as $key=>$value){
		  if(isset($service_use[$key])){
		     $items[$key]['can_delete']=1; 
		  }else{
		     $items[$key]['can_delete']=0;  
		  }
			if($i%2==0){
				$items[$key]['row_class'] = 'row-even';
			}else{
				$items[$key]['row_class'] = 'row-odd';
			}
			$items[$key]['i'] = $i++;
			$items[$key]['number'] = 0;
		}
		$this->map['items'] = $items;
		$this->parse_layout('list',$this->map);
	}	
}
?>
