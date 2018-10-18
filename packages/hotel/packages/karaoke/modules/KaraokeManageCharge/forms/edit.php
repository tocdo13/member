<?php
class ManageKaraokeChargeForm extends Form{
	function ManageKaraokeChargeForm()
    {
		Form::Form('ManageKaraokeChargeForm');
    	//$this->add('karaoke_charge.code',new TextType(true,'code',0,255));
		//$this->add('karaoke_charge.name',new TextType(true,'name',0,255));
		$this->add('karaoke_id',new IntType(true,'karaoke_id',0,1000000000000000000000));
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
					DB::delete('karaoke_charge','id=\''.$id.'\'');
				}
			}
			if(Url::get('save')){
				echo 2; //exit();	
			}
			$karaokes = DB::fetch_all('select * from karaoke');
            //System::debug($karaokes);
            //System::debug($_REQUEST['mi_karaoke_charge']);
			if(isset($_REQUEST['mi_karaoke_charge']) && Url::get('karaoke_id') && Url::get('karaoke_id')>0  && Url::get('save')){	
				foreach($_REQUEST['mi_karaoke_charge'] as $key=>$record){
					$karaoke_id = Url::get('karaoke_id');
					if($record['id']){// and DB::exists_id('karaoke_charge',$record['id'])
						unset($record['no']);
						$id = $record['id'];
						unset($record['id']);
						$record['karaoke_id'] = $karaoke_id;
						$record['code'] =strtoupper($record['code']);
						$record['portal_id'] = PORTAL_ID;
						if(isset($karaokes[$record['karaoke_id_from']])){
							$record['department_id'] = $karaokes[$record['karaoke_id_from']]['department_id'];
						}
						if($record['percent']!='' && $record['percent'] >=0){
                            //System::debug($record);exit();
							DB::update('karaoke_charge',$record,'id=\''.$id.'\'');
						}else{
							DB::delete('karaoke_charge','id=\''.$id.'\'');
						}
					}else{
						unset($record['id']);
						unset($record['no']);
						$record['karaoke_id'] = $karaoke_id;
						$record['code'] =strtoupper($record['code']);
						$record['portal_id'] = PORTAL_ID;
						if(isset($karaokes[$record['karaoke_id_from']])){
							$record['department_id'] = $karaokes[$record['karaoke_id_from']]['department_id'];
						}
						if($record['percent']!='' && $record['percent'] >=0){
							$id = DB::insert('karaoke_charge',$record);
						}
					}
				}
			}
			Url::redirect_current(array('karaoke_id'));
		}
	}	
	function draw()
    {
		$cond = ' ';
		$cond2 = ' ';
		if(Url::get('karaoke_id')){
			$cond .= ' AND karaoke.id = '.Url::get('karaoke_id').'';
			$cond2 .= ' AND karaoke.id <> '.Url::get('karaoke_id').'';
			$_REQUEST['karaoke_id'] = Url::get('karaoke_id');	
		}
		if(!isset($_REQUEST['mi_karaoke_charge']) && Url::get('karaoke_id'))
        {
			$sql = '
				SELECT
					karaoke_charge.*
				FROM
					karaoke_charge 
					inner join karaoke ON karaoke.id = karaoke_charge.karaoke_id
				WHERE karaoke.portal_id=\''.PORTAL_ID.'\' '.$cond.' order by karaoke_charge.karaoke_id';
			$karaokes = DB::fetch_all($sql);
			//System::Debug($karaokes);
			$i=1;
			foreach($karaokes as $key => $value)
            {
				$karaokes[$key]['no'] = $i;
				$i++;
			}
			$_REQUEST['mi_karaoke_charge'] = $karaokes;
		}
        $karaokes = DB::fetch_all('select karaoke.id,karaoke.name,karaoke.code from karaoke where 1>0 ');//'.$cond2.'
		$karaoke_id_options = '';  
		$karaoke_id_options .= '<option value="">------Select------</option>';
		foreach($karaokes as $k => $item)
		{
			$karaoke_id_options .= '<option value="'.$item['id'].'" id="karaoke_id_'.$item['id'].'">'.$item['name'].'</option>';
		}	
		$this->parse_layout('edit',array('karaoke_id_options'=>$karaoke_id_options,'karaoke_js'=>String::array2js($karaokes)));
	}
}
?>