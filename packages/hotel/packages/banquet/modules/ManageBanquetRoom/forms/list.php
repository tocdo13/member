<?php
class ManageBanquetRoomForm extends Form
{
	function ManageBanquetRoomForm()
	{
		Form::Form('ManageBanquetRoomForm');
		$this->add('party_room.name',new TextType(true,'name',0,255));
        $this->add('party_room.bar_id',new IntType(true,'miss_bar','1','100000000000')); 
		$this->link_js('packages/core/includes/js/multi_items.js');
	
	}
	function on_submit()
	{
		if($this->check()){		
			if(URL::get('deleted_ids'))
			{
				$ids = explode(',',URL::get('deleted_ids'));
				foreach($ids as $id)
				{
					DB::delete('party_room','id=\''.$id.'\'');
				}
			}
			if(isset($_REQUEST['party_room']))
			{	
				foreach($_REQUEST['party_room'] as $key=>$record)
				{
					if($record['name'] == '')
					{
						echo "<script> alert 'Tên không được để trống ' </script>"; 
						
					}
					else if($record['id'] and DB::exists_id('party_room',$record['id']))//neu bang do ton tai thi chi cap nhat thoi.
					{
					    $row = DB::select_id('party_room',$record['id']);   
						$data = array(
							'name'=>$record['name'],
							'group_name'=>$record['group_name'],
                            'address'=>$record['address'],
							'price'=>System::calculate_number($record['price']),
							'price_half_day'=>System::calculate_number($record['price_half_day']),
                            'portal_id'=>PORTAL_ID,
                            'num'=>$record['num'],
                            //'bar_id'=>$record['bar_id'],
                            //'bar_code'=>$row['code'],
                            //'bar_DEPARTMENT_ID'=>$row['department_id'],
                            //'bar_name'=>$row['name']
						);
						DB::update('party_room',$data,'id='.$record['code']);
					}
					else
					{ 
						unset($record['no']);
						unset($record['id']);
                        $row = DB::select_id('bar',$record['bar_id']);
						$data = array(
							'name'=>$record['name'],
							'group_name'=>$record['group_name'],
                            'address'=>$record['address'],
							'price'=>System::calculate_number($record['price']),
							'price_half_day'=>System::calculate_number($record['price_half_day']),
                            'portal_id'=>PORTAL_ID,
                            'num'=>$record['num'],
                            //'bar_id'=>$record['bar_id'],
                            //'bar_code'=>$row['code'],
                            //'bar_DEPARTMENT_ID'=>$row['department_id'],
                            //'bar_name'=>$row['name']
						);
						DB::insert('party_room',$data);
					}
				}
			}
			Url::redirect_current();
		}
	}
	function draw()
	{
        $this->map = array();
		if(!isset($_REQUEST['party_room'])){
			$cond = ' 1>0 ';
			$sql='SELECT id, id as code,name,group_name,address,price,price_half_day,num FROM party_room where portal_id = \''.PORTAL_ID.'\' ORDER BY id';
			$party_room = DB::fetch_all($sql);
			$i=1;
			foreach($party_room as $key => $value){
				$party_room[$key]['no'] = $i;
				$party_room[$key]['price'] = System::display_number($value['price']);
				$party_room[$key]['price_half_day'] = System::display_number($value['price_half_day']);				
				$i++;
			}
			$_REQUEST['party_room'] = $party_room;
		}
        /*
        $bar = DB::select_all('bar','portal_id = \''.PORTAL_ID.'\'');
		$bar_options = '<option value="">'.Portal::language('choose_bar').'</option>';
		foreach($bar as $key=>$value)
		{
			$bar_options.='<option value="'.$value['id'].'">'.$value['name'].'</option>';
		}
        $this->map['bar'] = $bar_options;
        */
		//system::debug($party_room);
		//exit();
		$this->parse_layout('list',$this->map);
	}
	
}
?>