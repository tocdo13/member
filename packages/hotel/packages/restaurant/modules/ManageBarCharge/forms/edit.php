<?php
class ManageBarChargeForm extends Form{
	function ManageBarChargeForm()
    {
		Form::Form('ManageBarChargeForm');
    	//$this->add('bar_charge.code',new TextType(true,'code',0,255));
		//$this->add('bar_charge.name',new TextType(true,'name',0,255));
		$this->add('bar_id',new IntType(true,'bar_id',0,1000000000000000000000));
		$this->link_js('packages/core/includes/js/multi_items.js');
        require_once 'packages/hotel/includes/php/module.php';
	}
	function on_submit()
    {
		if($this->check())
        {		
			if(URL::get('deleted_ids')){
				$ids = explode(',',URL::get('deleted_ids'));
				foreach($ids as $id){
					DB::delete('bar_charge','id=\''.$id.'\'');
				}
			}
			if(Url::get('save')){
				echo 2; //exit();	
			}
			$bars = DB::fetch_all('select * from bar');
			if(isset($_REQUEST['mi_bar_charge']) && Url::get('bar_id') && Url::get('bar_id')>0  && Url::get('save')){	
				foreach($_REQUEST['mi_bar_charge'] as $key=>$record){
					$bar_id = Url::get('bar_id');
					if($record['id']){// and DB::exists_id('bar_charge',$record['id'])
						unset($record['no']);
						$id = $record['id'];
						unset($record['id']);
						$record['bar_id'] = $bar_id;
						$record['code'] =strtoupper($record['code']);
						$record['portal_id'] = PORTAL_ID;
						if(isset($bars[$record['bar_id_from']])){
							$record['department_id'] = $bars[$record['bar_id_from']]['department_id'];
						}
						if($record['percent']!='' && $record['percent'] >=0){
							DB::update('bar_charge',$record,'id=\''.$id.'\'');
						}else{
							DB::delete('bar_charge','id=\''.$id.'\'');
						}
					}else{
						unset($record['id']);
						unset($record['no']);
						$record['bar_id'] = $bar_id;
						$record['code'] =strtoupper($record['code']);
						$record['portal_id'] = PORTAL_ID;
						if(isset($bars[$record['bar_id_from']])){
							$record['department_id'] = $bars[$record['bar_id_from']]['department_id'];
						}
						if($record['percent']!='' && $record['percent'] >=0){
							$id = DB::insert('bar_charge',$record);
						}
					}
				}
			}
			Url::redirect_current(array('bar_id'));
		}
	}	
	function draw()
    {
		$cond = ' ';
		$cond2 = ' ';
		if(Url::get('bar_id')){
			$cond .= ' AND bar.id = '.Url::get('bar_id').'';
			$cond2 .= ' AND bar.id <> '.Url::get('bar_id').'';
			$_REQUEST['bar_id'] = Url::get('bar_id');	
		}
		if(!isset($_REQUEST['mi_bar_charge']) && Url::get('bar_id'))
        {
			$sql = '
				SELECT
					bar_charge.*
				FROM
					bar_charge 
					inner join bar ON bar.id = bar_charge.bar_id
				WHERE bar.portal_id=\''.PORTAL_ID.'\' '.$cond.' order by bar_charge.bar_id';
			$bars = DB::fetch_all($sql);
			//System::Debug($bars);
			$i=1;
			foreach($bars as $key => $value)
            {
				$bars[$key]['no'] = $i;
				$i++;
			}
			$_REQUEST['mi_bar_charge'] = $bars;
		}
        $bars = DB::fetch_all('select bar.id,bar.name,bar.code from bar where 1>0 ');//'.$cond2.'
		$bar_id_options = '';  
		$bar_id_options .= '<option value="">------Select------</option>';
		foreach($bars as $k => $item)
		{
			$bar_id_options .= '<option value="'.$item['id'].'" id="bar_id_'.$item['id'].'">'.$item['name'].'</option>';
		}	
		$this->parse_layout('edit',array('bar_id_options'=>$bar_id_options,'bar_js'=>String::array2js($bars)));
	}
}
?>