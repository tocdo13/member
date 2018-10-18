<?php 
class RevenAndExpenGroupForm extends Form{
    function RevenAndExpenGroupForm(){
        Form::Form('RevenAndExpenGroupForm');
        $this->link_js('packages/core/includes/js/multi_items.js');
    }
    function on_submit()
    {
        if(URL::get('deleted_ids'))
        {
            $ids = explode(',',URL::get('deleted_ids'));
            foreach($ids as $id)
            {
                DB::delete('REVEN_EXPEN_GROUP','id=\''.$id.'\'');
            }
		}
		if(isset($_REQUEST['mi_group']))
        {	
            foreach($_REQUEST['mi_group'] as $key=>$record)
            {
                if($record['id'] and DB::exists_id('REVEN_EXPEN_GROUP',$record['id']))
                {
                    unset($record['no']);
                    $bar_id = $record['id'];
                    DB::update('REVEN_EXPEN_GROUP',$record,'id=\''.$bar_id.'\'');
                }
                else
                {
                    unset($record['no']);
                    unset($record['id']);
                    $id = DB::insert('REVEN_EXPEN_GROUP',$record);
                }
            }
        }
		Url::redirect_current();
    }
    function draw(){
        if(!isset($_REQUEST['mi_group']))
        {
			$cond = ' 1>0 ';
			$sql = '
				SELECT
					*
				FROM
					REVEN_EXPEN_GROUP order by id';
			$bars = DB::fetch_all($sql);
			$i=1;
			foreach($bars as $key => $value)
            {
				$bars[$key]['no'] = $i;
				$i++;
			}
            
			$_REQUEST['mi_group'] = $bars;
            //System::Debug($_REQUEST['mi_group']);
            //exit();
		}		
		$this->parse_layout('report',array());
    }
}
?>
