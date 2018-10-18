<?php
class ListLogForm extends Form
{
	function ListLogForm()
	{
		Form::Form('ListLogForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
	   $array_pageing = array();
	   require_once 'packages/core/includes/utils/vn_code.php';
		//if(!Url::get('date_from')){
			//$_REQUEST['date_from'] = date('d/m/Y',time());
		//}
        //if(!Url::get('date_to')){
			//$_REQUEST['date_to'] = date('d/m/Y',time()+24*3600);
		//}
        $users = DB::fetch_all('select 
                                    account.id,party.full_name 
                                from 
                                    account 
                                    INNER JOIN party on party.user_id = account.id 
                                    AND party.type=\'USER\' 
                                WHERE 
                                    (account.portal_department_id <> \'1001\' AND account.portal_department_id <> \'1002\' )
    			                 	AND account.type=\'USER\' ORDER BY account.id');
                                    
        $this->map['user_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list($users);
        $module = DB::fetch_all('select package.title_1 as id, package.title_1 as name from package ORDER BY package.title_1 DESC');
        $this->map['module_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list($module);
        $rooms = DB::fetch_all('select room.name as id,room.name from room ORDER BY room.name');
        $this->map['room_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list($rooms);
		$cond = '	
				1 >0
			'.(Url::get('date_from')?' and log.time >= '.Date_time::to_time(Url::get('date_from')):'').'
			'.(Url::get('date_to')?' and log.time < '.(Date_time::to_time(Url::get('date_to'))+24*3600):'') 
		;
        //(URL::get('user_id')?' and log.user_id=\''.URL::get('user_id').'\'':'');
        if(Url::get('date_from')) $array_pageing['date_from'] = Url::get('date_from');
        if(Url::get('date_to')) $array_pageing['date_to'] = Url::get('date_to');
        if(Url::get('user_id') !='ALL' && Url::get('user_id') !='')
        {
            $cond.= ' and log.user_id=\''.Url::get('user_id').'\'';
            $array_pageing['user_id'] = Url::get('user_id');
        }
        if(Url::get('room_id') !='ALL' && Url::get('room_id') !='')
        {
            $cond.= ' and (upper(log.description) like \'%'.mb_strtolower(Url::get('room_id')).'%\' or log.description like \'%'.Url::get('room_id').'%\' )';
            $array_pageing['room_id'] = Url::get('room_id');
        }
        if(Url::get('module_id') !='ALL' && Url::get('module_id') !='')
        {
            $cond.= ' and (package.title_1 =\''.Url::get('module_id').'\' )';
            $array_pageing['module_id'] = Url::get('module_id');
        }
        if(Url::get('keyword'))
        {
            //$cond = '(LOWER(FN_CONVERT_TO_VN(log.note)) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(Url::get('keyword'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(log.title)) LIKE  \'%'.convert_utf8_to_latin(mb_strtolower(Url::get('keyword'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(log.type)) LIKE  \'%'.convert_utf8_to_latin(mb_strtolower(Url::get('keyword'),'utf-8')).'%\' OR LOWER(FN_CONVERT_TO_VN(package.title_1)) LIKE  \'%'.convert_utf8_to_latin(mb_strtolower(Url::get('keyword'),'utf-8')).'%\')';
            //$cond .= ' or log.description like \'%'.Url::get('keyword').'%\'';
            $cond = '(log.title LIKE  \'%'.Url::get('keyword').'%\'';
            $cond .= ' OR log.description like \'%'.Url::get('keyword').'%\')';
            $array_pageing = array();
            $array_pageing['keyword'] = Url::get('keyword');
        }
        //echo $cond;
		$item_per_page = 20;
		$sql = '
			SELECT 
				count(*) as id
			FROM 
				log
				LEFT OUTER JOIN block on block.id = log.module_id
                LEFT OUTER JOIN module on module.id = block.module_id
                LEFT OUTER JOIN package on package.id = module.package_id
			WHERE '.$cond.'
		';
		$count = DB::fetch($sql);		
		require_once 'packages/core/includes/utils/paging.php';
        //System::debug($_REQUEST);
		$paging = paging($count['id'],$item_per_page,10,false,'page_no',array('date_from','date_to','user_id','room_id','module_id','keyword'));
		$sql = '
		SELECT * FROM
		(
			SELECT 
				log.id
				,log.time
				,FROM_UNIXTIME(log.time) as in_date
				,log.type
				,log.parameter
				,log.note
				,log.title
				,log.user_id
				,log.description
				,row_number() over (order by log.time DESC) as rownumber
				,log.module_id
                ,package.title_1 as module_name
			FROM 
			 	log
				LEFT OUTER JOIN block on block.id = log.module_id
                LEFT OUTER JOIN module on module.id = block.module_id
                LEFT OUTER JOIN package on package.id = module.package_id
			WHERE 
				'.$cond.'
			ORDER BY 
				log.time DESC
		)
		WHERE
			 rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
		foreach($items as $key=>$item)
		{
			$items[$key]['title'] = strip_tags($item['title']);
            if(file_exists('packages/user/modules/Log/file/description_'.$key.'.txt')) {
                $items[$key]['description'] = file_get_contents('packages/user/modules/Log/file/description_'.$key.'.txt');
            }
		}
		$this->parse_layout('list',
			array(
				'items'=>$items,
				'paging'=>$paging,
				'type'=>URL::get('type'),
				'user_id'=>URL::get('user_id'),
				'module_id'=>URL::get('module_id')
			)+$this->map
		);
	}
}
?>