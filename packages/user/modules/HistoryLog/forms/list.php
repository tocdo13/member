<?php
class ListHistoryLogForm extends Form
{
	function ListHistoryLogForm()
	{
		Form::Form('ListHistoryLogForm');
	}
	function draw()
	{
 	   $this->map = array();
       $sql = 'SELECT 
				log.id
				,log.time
				,FROM_UNIXTIME(log.time) as in_date
				,log.type
				,log.parameter
				,log.note
				,log.title
				,log.user_id
				,log.description
				,log.module_id
			FROM
                history_log 
			 	inner join log on log.id=history_log.log_id
			WHERE 
				history_log.type=\'RECODE\'
                AND history_log.invoice_id='.Url::get('recode').'
			ORDER BY 
				log.time DESC';
        
        $items = DB::fetch_all($sql);
		foreach($items as $key=>$item)
		{
			$items[$key]['title'] = strip_tags($item['title']);
            if(file_exists('packages/user/modules/Log/file/description_'.$key.'.txt')) {
                $items[$key]['description'] = file_get_contents('packages/user/modules/Log/file/description_'.$key.'.txt');
            }
		}
        $this->map['items'] = $items;
		$this->parse_layout('list',$this->map);
	}
}
?>